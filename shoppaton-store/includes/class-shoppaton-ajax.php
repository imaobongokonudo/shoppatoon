<?php
/**
 * AJAX Handler Class
 *
 * @package ShoppatonStore
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Shoppaton AJAX Class
 */
class Shoppaton_Ajax {

    /**
     * Single instance
     *
     * @var Shoppaton_Ajax
     */
    private static $instance = null;

    /**
     * Get instance
     *
     * @return Shoppaton_Ajax
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
        $ajax_actions = array(
            'add_to_cart',
            'update_cart_item',
            'remove_from_cart',
            'get_cart',
            'sync_cart',
            'toggle_wishlist',
            'search_products',
            'get_product',
            'track_event',
            'contact_form',
            'newsletter_subscribe',
            'track_order',
            'get_shipping_rate',
        );

        foreach ($ajax_actions as $action) {
            add_action('wp_ajax_shoppaton_' . $action, array($this, $action));
            add_action('wp_ajax_nopriv_shoppaton_' . $action, array($this, $action));
        }
    }

    /**
     * Add to cart
     */
    public function add_to_cart() {
        check_ajax_referer('shoppaton_nonce', 'nonce');

        $product_id = intval($_POST['product_id'] ?? 0);
        $quantity = intval($_POST['quantity'] ?? 1);

        if (!$product_id) {
            wp_send_json_error(array('message' => 'Invalid product'));
        }

        $cart = Shoppaton_Cart::instance();
        $added = $cart->add_item($product_id, $quantity);

        if ($added) {
            // Track event
            Shoppaton_Analytics::instance()->track('add_to_cart', array(
                'product_id' => $product_id,
                'quantity' => $quantity,
            ));

            wp_send_json_success(array(
                'message' => 'Product added to cart',
                'cart' => $cart->get_items(),
                'count' => $cart->get_count(),
                'subtotal' => Shoppaton_Settings::format_price($cart->get_subtotal()),
            ));
        } else {
            wp_send_json_error(array('message' => 'Failed to add product to cart'));
        }
    }

    /**
     * Update cart item
     */
    public function update_cart_item() {
        check_ajax_referer('shoppaton_nonce', 'nonce');

        $product_id = intval($_POST['product_id'] ?? 0);
        $quantity = intval($_POST['quantity'] ?? 1);

        if (!$product_id) {
            wp_send_json_error(array('message' => 'Invalid product'));
        }

        $cart = Shoppaton_Cart::instance();
        $cart->update_item($product_id, $quantity);

        wp_send_json_success(array(
            'cart' => $cart->get_items(),
            'count' => $cart->get_count(),
            'subtotal' => Shoppaton_Settings::format_price($cart->get_subtotal()),
        ));
    }

    /**
     * Remove from cart
     */
    public function remove_from_cart() {
        check_ajax_referer('shoppaton_nonce', 'nonce');

        $product_id = intval($_POST['product_id'] ?? 0);

        if (!$product_id) {
            wp_send_json_error(array('message' => 'Invalid product'));
        }

        $cart = Shoppaton_Cart::instance();
        $cart->remove_item($product_id);

        wp_send_json_success(array(
            'cart' => $cart->get_items(),
            'count' => $cart->get_count(),
            'subtotal' => Shoppaton_Settings::format_price($cart->get_subtotal()),
        ));
    }

    /**
     * Get cart
     */
    public function get_cart() {
        check_ajax_referer('shoppaton_nonce', 'nonce');

        $cart = Shoppaton_Cart::instance();

        wp_send_json_success(array(
            'cart' => $cart->get_items(),
            'count' => $cart->get_count(),
            'subtotal' => Shoppaton_Settings::format_price($cart->get_subtotal()),
        ));
    }

    /**
     * Sync cart from offline storage
     */
    public function sync_cart() {
        check_ajax_referer('shoppaton_nonce', 'nonce');

        $offline_cart = json_decode(stripslashes($_POST['cart'] ?? '[]'), true);

        $cart = Shoppaton_Cart::instance();
        $synced = $cart->sync_from_offline($offline_cart);

        wp_send_json_success(array(
            'cart' => $synced,
            'count' => $cart->get_count(),
            'subtotal' => Shoppaton_Settings::format_price($cart->get_subtotal()),
        ));
    }

    /**
     * Toggle wishlist
     */
    public function toggle_wishlist() {
        check_ajax_referer('shoppaton_nonce', 'nonce');

        $product_id = intval($_POST['product_id'] ?? 0);

        if (!$product_id) {
            wp_send_json_error(array('message' => 'Invalid product'));
        }

        $added = Shoppaton_User::instance()->toggle_wishlist($product_id);

        wp_send_json_success(array(
            'added' => $added,
            'message' => $added ? 'Added to wishlist' : 'Removed from wishlist',
        ));
    }

    /**
     * Search products
     */
    public function search_products() {
        check_ajax_referer('shoppaton_nonce', 'nonce');

        $query = sanitize_text_field($_POST['query'] ?? '');

        if (strlen($query) < 2) {
            wp_send_json_error(array('message' => 'Query too short'));
        }

        $products = Shoppaton_Products::instance()->search($query);

        $results = array();
        foreach ($products as $product) {
            $results[] = array(
                'id' => $product->id,
                'name' => $product->name,
                'price' => Shoppaton_Settings::format_price($product->sale_price ?: $product->price),
                'image' => !empty($product->images) ? $product->images[0] : SHOPPATON_ASSETS_URL . 'images/placeholder-product.png',
                'url' => Shoppaton_Products::instance()->get_product_url($product),
            );
        }

        wp_send_json_success(array('products' => $results));
    }

    /**
     * Get single product
     */
    public function get_product() {
        check_ajax_referer('shoppaton_nonce', 'nonce');

        $product_id = intval($_POST['product_id'] ?? 0);

        if (!$product_id) {
            wp_send_json_error(array('message' => 'Invalid product'));
        }

        $product = Shoppaton_Products::instance()->get($product_id);

        if (!$product) {
            wp_send_json_error(array('message' => 'Product not found'));
        }

        wp_send_json_success(array(
            'product' => array(
                'id' => $product->id,
                'name' => $product->name,
                'price' => Shoppaton_Settings::format_price($product->sale_price ?: $product->price),
                'regular_price' => $product->sale_price ? Shoppaton_Settings::format_price($product->price) : null,
                'sale_price' => $product->sale_price ? Shoppaton_Settings::format_price($product->sale_price) : null,
                'short_description' => $product->short_description,
                'description' => $product->description,
                'image' => !empty($product->images) ? $product->images[0] : SHOPPATON_ASSETS_URL . 'images/placeholder-product.png',
                'images' => $product->images,
                'skin_type' => $product->skin_type,
                'target_user' => $product->target_user,
                'usage_guide' => $product->usage_guide,
                'stock_status' => $product->stock_status,
            ),
        ));
    }

    /**
     * Track event
     */
    public function track_event() {
        check_ajax_referer('shoppaton_nonce', 'nonce');

        $event_type = sanitize_text_field($_POST['event_type'] ?? '');
        $event_data = json_decode(stripslashes($_POST['event_data'] ?? '{}'), true);

        if (!$event_type) {
            wp_send_json_error(array('message' => 'Invalid event'));
        }

        Shoppaton_Analytics::instance()->track($event_type, $event_data);

        wp_send_json_success();
    }

    /**
     * Contact form
     */
    public function contact_form() {
        check_ajax_referer('shoppaton_nonce', 'nonce');

        $name = sanitize_text_field($_POST['name'] ?? '');
        $email = sanitize_email($_POST['email'] ?? '');
        $phone = sanitize_text_field($_POST['phone'] ?? '');
        $subject = sanitize_text_field($_POST['subject'] ?? 'Contact Form Submission');
        $message = sanitize_textarea_field($_POST['message'] ?? '');

        if (empty($name) || empty($email) || empty($message)) {
            wp_send_json_error(array('message' => 'Please fill in all required fields'));
        }

        if (!is_email($email)) {
            wp_send_json_error(array('message' => 'Please enter a valid email address'));
        }

        $to = Shoppaton_Settings::instance()->get('contact_email');
        $email_subject = 'Contact Form: ' . $subject . ' - Shoppaton Store';
        
        $email_body = "Name: {$name}\n";
        $email_body .= "Email: {$email}\n";
        $email_body .= "Phone: {$phone}\n\n";
        $email_body .= "Message:\n{$message}";

        $headers = array(
            'From: ' . $name . ' <' . $email . '>',
            'Reply-To: ' . $email,
        );

        $sent = wp_mail($to, $email_subject, $email_body, $headers);

        if ($sent) {
            wp_send_json_success(array('message' => 'Message sent successfully'));
        } else {
            wp_send_json_error(array('message' => 'Failed to send message'));
        }
    }

    /**
     * Newsletter subscribe
     */
    public function newsletter_subscribe() {
        check_ajax_referer('shoppaton_nonce', 'nonce');

        $email = sanitize_email($_POST['email'] ?? '');

        if (!is_email($email)) {
            wp_send_json_error(array('message' => 'Please enter a valid email address'));
        }

        // Store subscriber (you can integrate with email service providers)
        $subscribers = get_option('shoppaton_newsletter_subscribers', array());
        
        if (in_array($email, $subscribers)) {
            wp_send_json_error(array('message' => 'You are already subscribed'));
        }

        $subscribers[] = $email;
        update_option('shoppaton_newsletter_subscribers', $subscribers);

        wp_send_json_success(array('message' => 'Successfully subscribed'));
    }

    /**
     * Track order
     */
    public function track_order() {
        check_ajax_referer('shoppaton_nonce', 'nonce');

        $order_number = sanitize_text_field($_POST['order_number'] ?? '');
        $identifier = sanitize_text_field($_POST['identifier'] ?? '');

        if (empty($order_number) || empty($identifier)) {
            wp_send_json_error(array('message' => 'Please enter order number and email/phone'));
        }

        $order = Shoppaton_Orders::instance()->track_order($order_number, $identifier);

        if (!$order) {
            wp_send_json_error(array('message' => 'Order not found'));
        }

        wp_send_json_success(array(
            'order' => array(
                'order_number' => $order->order_number,
                'status' => $order->status,
                'total' => Shoppaton_Settings::format_price($order->total),
                'created_at' => date('F j, Y', strtotime($order->created_at)),
                'items' => array_map(function($item) {
                    return array(
                        'name' => $item->product_name,
                        'quantity' => $item->quantity,
                        'price' => Shoppaton_Settings::format_price($item->total),
                    );
                }, $order->items),
                'tracking' => $order->tracking,
            ),
        ));
    }

    /**
     * Get shipping rate
     */
    public function get_shipping_rate() {
        check_ajax_referer('shoppaton_nonce', 'nonce');

        $state = sanitize_text_field($_POST['state'] ?? 'lagos');

        $rate = Shoppaton_Settings::instance()->get_shipping_rate($state);

        wp_send_json_success(array(
            'rate' => $rate,
            'formatted' => Shoppaton_Settings::format_price($rate),
        ));
    }
}
