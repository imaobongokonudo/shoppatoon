<?php
/**
 * Checkout Class
 *
 * @package ShoppatonStore
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Shoppaton Checkout Class
 */
class Shoppaton_Checkout {

    /**
     * Single instance
     *
     * @var Shoppaton_Checkout
     */
    private static $instance = null;

    /**
     * Get instance
     *
     * @return Shoppaton_Checkout
     */
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        add_action('wp_ajax_shoppaton_process_checkout', array($this, 'process_checkout'));
        add_action('wp_ajax_nopriv_shoppaton_process_checkout', array($this, 'process_checkout'));
        add_action('wp_ajax_shoppaton_verify_payment', array($this, 'verify_payment'));
        add_action('wp_ajax_nopriv_shoppaton_verify_payment', array($this, 'verify_payment'));
    }

    /**
     * Process checkout
     */
    public function process_checkout() {
        check_ajax_referer('shoppaton_nonce', 'nonce');

        $cart = Shoppaton_Cart::instance();
        $items = $cart->get_items();

        if (empty($items)) {
            wp_send_json_error(array('message' => 'Your cart is empty'));
        }

        // Validate required fields
        $required_fields = array('billing_name', 'billing_email', 'billing_phone', 'billing_address', 'billing_city', 'billing_state');
        
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                wp_send_json_error(array('message' => 'Please fill in all required fields'));
            }
        }

        // Sanitize input
        $billing_data = array(
            'name' => sanitize_text_field($_POST['billing_name']),
            'email' => sanitize_email($_POST['billing_email']),
            'phone' => sanitize_text_field($_POST['billing_phone']),
            'address' => sanitize_textarea_field($_POST['billing_address']),
            'city' => sanitize_text_field($_POST['billing_city']),
            'state' => sanitize_text_field($_POST['billing_state']),
        );

        // Shipping data (if different)
        $shipping_data = null;
        if (!empty($_POST['ship_to_different']) && $_POST['ship_to_different'] === 'yes') {
            $shipping_data = array(
                'name' => sanitize_text_field($_POST['shipping_name'] ?? ''),
                'phone' => sanitize_text_field($_POST['shipping_phone'] ?? ''),
                'address' => sanitize_textarea_field($_POST['shipping_address'] ?? ''),
                'city' => sanitize_text_field($_POST['shipping_city'] ?? ''),
                'state' => sanitize_text_field($_POST['shipping_state'] ?? ''),
            );
        }

        // Calculate totals
        $subtotal = $cart->get_subtotal();
        $shipping_state = $shipping_data ? $shipping_data['state'] : $billing_data['state'];
        $shipping_cost = $cart->get_shipping_cost($shipping_state);
        $total = $subtotal + $shipping_cost;

        // Create order
        $order_data = array(
            'user_id' => get_current_user_id() ?: null,
            'status' => 'pending',
            'subtotal' => $subtotal,
            'shipping_cost' => $shipping_cost,
            'total' => $total,
            'payment_method' => 'paystack',
            'billing_name' => $billing_data['name'],
            'billing_email' => $billing_data['email'],
            'billing_phone' => $billing_data['phone'],
            'billing_address' => $billing_data['address'],
            'billing_city' => $billing_data['city'],
            'billing_state' => $billing_data['state'],
            'notes' => sanitize_textarea_field($_POST['order_notes'] ?? ''),
        );

        if ($shipping_data) {
            $order_data['shipping_name'] = $shipping_data['name'];
            $order_data['shipping_phone'] = $shipping_data['phone'];
            $order_data['shipping_address'] = $shipping_data['address'];
            $order_data['shipping_city'] = $shipping_data['city'];
            $order_data['shipping_state'] = $shipping_data['state'];
        }

        $order_id = Shoppaton_Orders::instance()->create($order_data, $items);

        if (!$order_id) {
            wp_send_json_error(array('message' => 'Failed to create order'));
        }

        $order = Shoppaton_Orders::instance()->get($order_id);

        // Generate Paystack payment reference
        $reference = 'SHOPPATON-' . $order->order_number . '-' . time();

        // Update order with reference
        Shoppaton_Orders::instance()->update($order_id, array(
            'payment_reference' => $reference
        ));

        wp_send_json_success(array(
            'order_id' => $order_id,
            'order_number' => $order->order_number,
            'payment' => array(
                'reference' => $reference,
                'amount' => $total,
                'email' => $billing_data['email'],
                'name' => $billing_data['name'],
                'phone' => $billing_data['phone'],
            )
        ));
    }

    /**
     * Verify Paystack payment
     */
    public function verify_payment() {
        check_ajax_referer('shoppaton_nonce', 'nonce');

        $reference = sanitize_text_field($_POST['reference'] ?? '');
        
        if (empty($reference)) {
            wp_send_json_error(array('message' => 'Invalid payment reference'));
        }

        // Get order by reference
        $order = Shoppaton_Orders::instance()->get_by_reference($reference);
        
        if (!$order) {
            wp_send_json_error(array('message' => 'Order not found'));
        }

        // Verify with Paystack
        $verified = $this->verify_paystack_transaction($reference);

        if ($verified && $verified['status'] === 'success') {
            // Verify amount matches
            $paid_amount = $verified['amount'] / 100; // Convert from kobo
            
            if (abs($paid_amount - $order->total) > 1) { // Allow 1 naira tolerance
                wp_send_json_error(array('message' => 'Payment amount mismatch'));
            }

            // Update order status
            Shoppaton_Orders::instance()->update($order->id, array(
                'status' => 'processing',
                'payment_status' => 'paid'
            ));

            // Clear cart
            Shoppaton_Cart::instance()->clear();

            // Mark abandoned cart as recovered
            $this->mark_cart_recovered();

            // Send confirmation email
            Shoppaton_Emails::instance()->send_order_confirmation($order->id);

            // Track analytics
            Shoppaton_Analytics::instance()->track('purchase', array(
                'order_id' => $order->id,
                'total' => $order->total
            ));

            // Redirect URL
            $thank_you_url = add_query_arg(array(
                'order' => $order->order_number,
                'key' => wp_hash($order->order_number)
            ), home_url('/checkout/order-received/'));

            wp_send_json_success(array(
                'redirect' => $thank_you_url,
                'order_number' => $order->order_number
            ));
        } else {
            // Update order status
            Shoppaton_Orders::instance()->update($order->id, array(
                'payment_status' => 'failed'
            ));

            wp_send_json_error(array('message' => 'Payment verification failed'));
        }
    }

    /**
     * Verify Paystack transaction
     *
     * @param string $reference Transaction reference
     * @return array|false
     */
    private function verify_paystack_transaction($reference) {
        $secret_key = Shoppaton_Settings::instance()->get_paystack_secret_key();
        
        if (empty($secret_key)) {
            return false;
        }

        $url = 'https://api.paystack.co/transaction/verify/' . rawurlencode($reference);

        $response = wp_remote_get($url, array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $secret_key,
                'Content-Type' => 'application/json'
            ),
            'timeout' => 30
        ));

        if (is_wp_error($response)) {
            return false;
        }

        $body = json_decode(wp_remote_retrieve_body($response), true);

        if (!$body || !$body['status'] || !isset($body['data'])) {
            return false;
        }

        return array(
            'status' => $body['data']['status'],
            'amount' => $body['data']['amount'],
            'reference' => $body['data']['reference'],
            'paid_at' => $body['data']['paid_at'] ?? null
        );
    }

    /**
     * Mark abandoned cart as recovered
     */
    private function mark_cart_recovered() {
        global $wpdb;
        $table = $wpdb->prefix . 'shoppaton_abandoned_carts';
        $session_id = Shoppaton_Store::get_session_id();

        $wpdb->update(
            $table,
            array('recovered' => 1),
            array('session_id' => $session_id)
        );
    }

    /**
     * Initialize Paystack payment
     *
     * @param array $data Payment data
     * @return array|WP_Error
     */
    public function initialize_payment($data) {
        $secret_key = Shoppaton_Settings::instance()->get_paystack_secret_key();
        
        if (empty($secret_key)) {
            return new WP_Error('no_key', 'Paystack is not configured');
        }

        $url = 'https://api.paystack.co/transaction/initialize';

        $payload = array(
            'email' => $data['email'],
            'amount' => intval($data['amount'] * 100), // Convert to kobo
            'reference' => $data['reference'],
            'callback_url' => home_url('/checkout/verify/'),
            'metadata' => array(
                'order_number' => $data['order_number'],
                'customer_name' => $data['name'],
                'customer_phone' => $data['phone']
            )
        );

        $response = wp_remote_post($url, array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $secret_key,
                'Content-Type' => 'application/json'
            ),
            'body' => wp_json_encode($payload),
            'timeout' => 30
        ));

        if (is_wp_error($response)) {
            return $response;
        }

        $body = json_decode(wp_remote_retrieve_body($response), true);

        if (!$body || !$body['status']) {
            return new WP_Error('init_failed', $body['message'] ?? 'Payment initialization failed');
        }

        return array(
            'authorization_url' => $body['data']['authorization_url'],
            'access_code' => $body['data']['access_code'],
            'reference' => $body['data']['reference']
        );
    }

    /**
     * Get checkout fields
     *
     * @return array
     */
    public function get_checkout_fields() {
        return array(
            'billing' => array(
                'billing_name' => array(
                    'label' => 'Full Name',
                    'type' => 'text',
                    'required' => true,
                    'placeholder' => 'Enter your full name'
                ),
                'billing_email' => array(
                    'label' => 'Email Address',
                    'type' => 'email',
                    'required' => true,
                    'placeholder' => 'Enter your email'
                ),
                'billing_phone' => array(
                    'label' => 'Phone Number',
                    'type' => 'tel',
                    'required' => true,
                    'placeholder' => 'Enter your phone number'
                ),
                'billing_address' => array(
                    'label' => 'Address',
                    'type' => 'textarea',
                    'required' => true,
                    'placeholder' => 'Enter your delivery address'
                ),
                'billing_city' => array(
                    'label' => 'City',
                    'type' => 'text',
                    'required' => true,
                    'placeholder' => 'Enter your city'
                ),
                'billing_state' => array(
                    'label' => 'State',
                    'type' => 'select',
                    'required' => true,
                    'options' => Shoppaton_Settings::get_nigeria_states()
                )
            ),
            'order' => array(
                'order_notes' => array(
                    'label' => 'Order Notes (Optional)',
                    'type' => 'textarea',
                    'required' => false,
                    'placeholder' => 'Any special instructions for your order?'
                )
            )
        );
    }
}
