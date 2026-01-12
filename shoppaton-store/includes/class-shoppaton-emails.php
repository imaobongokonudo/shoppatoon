<?php
/**
 * Emails Class
 *
 * @package ShoppatonStore
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Shoppaton Emails Class
 */
class Shoppaton_Emails {

    /**
     * Single instance
     *
     * @var Shoppaton_Emails
     */
    private static $instance = null;

    /**
     * From email
     *
     * @var string
     */
    private $from_email = 'hello@shoppaton.com';

    /**
     * From name
     *
     * @var string
     */
    private $from_name = 'Shoppaton Store';

    /**
     * Get instance
     *
     * @return Shoppaton_Emails
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
        add_filter('wp_mail_from', array($this, 'set_from_email'));
        add_filter('wp_mail_from_name', array($this, 'set_from_name'));
        add_filter('wp_mail_content_type', array($this, 'set_content_type'));

        // Schedule abandoned cart emails
        if (!wp_next_scheduled('shoppaton_abandoned_cart_emails')) {
            wp_schedule_event(time(), 'daily', 'shoppaton_abandoned_cart_emails');
        }
        add_action('shoppaton_abandoned_cart_emails', array($this, 'send_abandoned_cart_emails'));
    }

    /**
     * Set from email
     *
     * @return string
     */
    public function set_from_email() {
        return $this->from_email;
    }

    /**
     * Set from name
     *
     * @return string
     */
    public function set_from_name() {
        return $this->from_name;
    }

    /**
     * Set content type
     *
     * @return string
     */
    public function set_content_type() {
        return 'text/html';
    }

    /**
     * Send order confirmation email
     *
     * @param int $order_id Order ID
     * @return bool
     */
    public function send_order_confirmation($order_id) {
        $order = Shoppaton_Orders::instance()->get($order_id);
        
        if (!$order) {
            return false;
        }

        $subject = 'Order Confirmation - ' . $order->order_number . ' | Shoppaton Store';
        
        $content = $this->get_email_template('order-confirmation', array(
            'order' => $order,
            'tracking_url' => home_url('/track-order/?order=' . $order->order_number),
        ));

        return wp_mail($order->billing_email, $subject, $content);
    }

    /**
     * Send order status update email
     *
     * @param object $order Order object
     * @return bool
     */
    public function send_order_status_update($order) {
        $status_labels = Shoppaton_Orders::get_statuses();
        $status_label = $status_labels[$order->status] ?? $order->status;

        $subject = 'Order Update: ' . $status_label . ' - ' . $order->order_number . ' | Shoppaton Store';
        
        $content = $this->get_email_template('order-status', array(
            'order' => $order,
            'status_label' => $status_label,
            'tracking_url' => home_url('/track-order/?order=' . $order->order_number),
        ));

        return wp_mail($order->billing_email, $subject, $content);
    }

    /**
     * Send abandoned cart emails
     */
    public function send_abandoned_cart_emails() {
        if (!Shoppaton_Settings::instance()->get('abandoned_cart_enabled')) {
            return;
        }

        global $wpdb;
        $table = $wpdb->prefix . 'shoppaton_abandoned_carts';
        $interval = Shoppaton_Settings::instance()->get('abandoned_cart_interval');

        // Get abandoned carts that haven't been recovered and need email
        $abandoned_carts = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$table} 
             WHERE recovered = 0 
             AND email IS NOT NULL 
             AND email != ''
             AND (last_email_sent IS NULL OR last_email_sent < DATE_SUB(NOW(), INTERVAL %d DAY))
             AND email_sent_count < 3
             AND created_at < DATE_SUB(NOW(), INTERVAL 1 HOUR)",
            $interval
        ));

        foreach ($abandoned_carts as $cart) {
            $this->send_abandoned_cart_email($cart);
        }
    }

    /**
     * Send single abandoned cart email
     *
     * @param object $cart Abandoned cart object
     * @return bool
     */
    private function send_abandoned_cart_email($cart) {
        global $wpdb;
        $table = $wpdb->prefix . 'shoppaton_abandoned_carts';

        $cart_items = json_decode($cart->cart_data, true);
        
        if (empty($cart_items)) {
            return false;
        }

        $email_count = $cart->email_sent_count + 1;
        
        // Different subjects for different reminder emails
        $subjects = array(
            1 => "You left something behind! Complete your order at Shoppaton",
            2 => "Still thinking about it? Your cart is waiting at Shoppaton",
            3 => "Last chance! Your items are almost gone - Shoppaton Store"
        );

        $subject = $subjects[$email_count] ?? $subjects[3];
        
        $content = $this->get_email_template('abandoned-cart', array(
            'cart' => $cart,
            'items' => $cart_items,
            'email_count' => $email_count,
            'cart_url' => home_url('/cart/'),
        ));

        $sent = wp_mail($cart->email, $subject, $content);

        if ($sent) {
            $wpdb->update(
                $table,
                array(
                    'email_sent_count' => $email_count,
                    'last_email_sent' => current_time('mysql')
                ),
                array('id' => $cart->id)
            );
        }

        return $sent;
    }

    /**
     * Get email template
     *
     * @param string $template Template name
     * @param array $data Template data
     * @return string
     */
    private function get_email_template($template, $data = array()) {
        $logo_url = SHOPPATON_ASSETS_URL . 'images/logo.png';
        
        ob_start();
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?php echo esc_html(get_bloginfo('name')); ?></title>
            <style>
                body {
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    line-height: 1.6;
                    color: #333;
                    background-color: #f5f5f5;
                    margin: 0;
                    padding: 0;
                }
                .email-wrapper {
                    max-width: 600px;
                    margin: 0 auto;
                    background-color: #ffffff;
                }
                .email-header {
                    background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%);
                    padding: 30px;
                    text-align: center;
                }
                .email-logo {
                    max-width: 200px;
                    height: auto;
                }
                .email-body {
                    padding: 40px 30px;
                }
                .email-title {
                    color: #D4AF37;
                    font-size: 24px;
                    margin-bottom: 20px;
                }
                .email-text {
                    color: #555;
                    margin-bottom: 20px;
                }
                .order-table {
                    width: 100%;
                    border-collapse: collapse;
                    margin: 20px 0;
                }
                .order-table th,
                .order-table td {
                    padding: 12px;
                    text-align: left;
                    border-bottom: 1px solid #eee;
                }
                .order-table th {
                    background-color: #f9f9f9;
                    color: #333;
                }
                .order-total {
                    font-size: 18px;
                    font-weight: bold;
                    color: #D4AF37;
                }
                .btn {
                    display: inline-block;
                    padding: 15px 30px;
                    background: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%);
                    color: #0a0a0a !important;
                    text-decoration: none;
                    border-radius: 10px;
                    font-weight: bold;
                    margin: 20px 0;
                }
                .email-footer {
                    background-color: #0a0a0a;
                    color: #999;
                    padding: 30px;
                    text-align: center;
                    font-size: 12px;
                }
                .email-footer a {
                    color: #D4AF37;
                    text-decoration: none;
                }
                .social-links {
                    margin: 15px 0;
                }
                .social-links a {
                    display: inline-block;
                    margin: 0 10px;
                    color: #D4AF37;
                }
                .tracking-box {
                    background: #f9f9f9;
                    border: 2px solid #D4AF37;
                    border-radius: 10px;
                    padding: 20px;
                    text-align: center;
                    margin: 20px 0;
                }
                .tracking-number {
                    font-size: 24px;
                    color: #D4AF37;
                    font-weight: bold;
                    letter-spacing: 2px;
                }
            </style>
        </head>
        <body>
            <div class="email-wrapper">
                <div class="email-header">
                    <img src="<?php echo esc_url($logo_url); ?>" alt="Shoppaton Store" class="email-logo">
                </div>
                
                <div class="email-body">
                    <?php
                    switch ($template) {
                        case 'order-confirmation':
                            $this->render_order_confirmation_template($data);
                            break;
                        case 'order-status':
                            $this->render_order_status_template($data);
                            break;
                        case 'abandoned-cart':
                            $this->render_abandoned_cart_template($data);
                            break;
                    }
                    ?>
                </div>
                
                <div class="email-footer">
                    <div class="social-links">
                        <a href="#">Instagram</a>
                        <a href="#">Facebook</a>
                        <a href="#">TikTok</a>
                    </div>
                    <p>
                        Shoppaton Store - Premium Skincare Essentials<br>
                        Lagos, Nigeria
                    </p>
                    <p>
                        <a href="<?php echo esc_url(home_url()); ?>">Visit our store</a> | 
                        <a href="mailto:hello@shoppaton.com">Contact us</a>
                    </p>
                    <p>&copy; <?php echo date('Y'); ?> Shoppaton Store. All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>
        <?php
        return ob_get_clean();
    }

    /**
     * Render order confirmation template
     *
     * @param array $data Template data
     */
    private function render_order_confirmation_template($data) {
        $order = $data['order'];
        ?>
        <h1 class="email-title">Thank You for Your Order!</h1>
        
        <p class="email-text">
            Hi <?php echo esc_html($order->billing_name); ?>,<br><br>
            Thank you for shopping with Shoppaton Store! We've received your order and it's being processed.
        </p>
        
        <div class="tracking-box">
            <p style="margin: 0 0 10px; color: #666;">Your Order Number</p>
            <div class="tracking-number"><?php echo esc_html($order->order_number); ?></div>
        </div>
        
        <h2 style="color: #333;">Order Summary</h2>
        
        <table class="order-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($order->items as $item) : ?>
                <tr>
                    <td><?php echo esc_html($item->product_name); ?></td>
                    <td><?php echo esc_html($item->quantity); ?></td>
                    <td><?php echo esc_html(Shoppaton_Settings::format_price($item->total)); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2">Subtotal</td>
                    <td><?php echo esc_html(Shoppaton_Settings::format_price($order->subtotal)); ?></td>
                </tr>
                <tr>
                    <td colspan="2">Shipping</td>
                    <td><?php echo esc_html(Shoppaton_Settings::format_price($order->shipping_cost)); ?></td>
                </tr>
                <tr>
                    <td colspan="2" class="order-total">Total</td>
                    <td class="order-total"><?php echo esc_html(Shoppaton_Settings::format_price($order->total)); ?></td>
                </tr>
            </tfoot>
        </table>
        
        <h2 style="color: #333;">Delivery Address</h2>
        <p class="email-text">
            <?php echo esc_html($order->shipping_name ?: $order->billing_name); ?><br>
            <?php echo esc_html($order->shipping_address ?: $order->billing_address); ?><br>
            <?php echo esc_html(($order->shipping_city ?: $order->billing_city) . ', ' . ($order->shipping_state ?: $order->billing_state)); ?>
        </p>
        
        <p style="text-align: center;">
            <a href="<?php echo esc_url($data['tracking_url']); ?>" class="btn">Track Your Order</a>
        </p>
        
        <p class="email-text">
            We'll send you another email when your order ships. If you have any questions, reply to this email or contact us at hello@shoppaton.com.
        </p>
        <?php
    }

    /**
     * Render order status template
     *
     * @param array $data Template data
     */
    private function render_order_status_template($data) {
        $order = $data['order'];
        ?>
        <h1 class="email-title">Order Update: <?php echo esc_html($data['status_label']); ?></h1>
        
        <p class="email-text">
            Hi <?php echo esc_html($order->billing_name); ?>,<br><br>
            Your order <?php echo esc_html($order->order_number); ?> has been updated.
        </p>
        
        <div class="tracking-box">
            <p style="margin: 0 0 10px; color: #666;">Current Status</p>
            <div class="tracking-number"><?php echo esc_html(strtoupper($data['status_label'])); ?></div>
            <?php if ($order->tracking_number) : ?>
            <p style="margin: 15px 0 0; color: #666;">
                Tracking Number: <strong><?php echo esc_html($order->tracking_number); ?></strong>
            </p>
            <?php endif; ?>
        </div>
        
        <p style="text-align: center;">
            <a href="<?php echo esc_url($data['tracking_url']); ?>" class="btn">Track Your Order</a>
        </p>
        
        <p class="email-text">
            If you have any questions about your order, please don't hesitate to contact us.
        </p>
        <?php
    }

    /**
     * Render abandoned cart template
     *
     * @param array $data Template data
     */
    private function render_abandoned_cart_template($data) {
        $items = $data['items'];
        $email_count = $data['email_count'];
        
        $messages = array(
            1 => array(
                'title' => "Don't Forget Your Cart!",
                'text' => "We noticed you left some amazing skincare essentials in your cart. Complete your purchase and start your journey to glowing skin!"
            ),
            2 => array(
                'title' => "Your Cart Misses You!",
                'text' => "Still thinking about those products? They're waiting for you! Don't miss out on achieving your best skin yet."
            ),
            3 => array(
                'title' => "Last Chance!",
                'text' => "Your cart items are still available, but they might not be for long! Complete your purchase now before they sell out."
            )
        );

        $message = $messages[$email_count] ?? $messages[3];
        ?>
        <h1 class="email-title"><?php echo esc_html($message['title']); ?></h1>
        
        <p class="email-text"><?php echo esc_html($message['text']); ?></p>
        
        <table class="order-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $total = 0;
                foreach ($items as $item) : 
                    $total += $item['price'] * $item['quantity'];
                ?>
                <tr>
                    <td><?php echo esc_html($item['name']); ?></td>
                    <td><?php echo esc_html($item['quantity']); ?></td>
                    <td><?php echo esc_html(Shoppaton_Settings::format_price($item['price'] * $item['quantity'])); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" class="order-total">Total</td>
                    <td class="order-total"><?php echo esc_html(Shoppaton_Settings::format_price($total)); ?></td>
                </tr>
            </tfoot>
        </table>
        
        <p style="text-align: center;">
            <a href="<?php echo esc_url($data['cart_url']); ?>" class="btn">Complete Your Purchase</a>
        </p>
        
        <p class="email-text" style="text-align: center;">
            Need help? Contact us at hello@shoppaton.com or chat with us on WhatsApp.
        </p>
        <?php
    }

    /**
     * Update abandoned cart email
     *
     * @param string $session_id Session ID
     * @param string $email Customer email
     */
    public function update_abandoned_cart_email($session_id, $email) {
        global $wpdb;
        $table = $wpdb->prefix . 'shoppaton_abandoned_carts';

        $wpdb->update(
            $table,
            array('email' => sanitize_email($email)),
            array('session_id' => $session_id)
        );
    }
}
