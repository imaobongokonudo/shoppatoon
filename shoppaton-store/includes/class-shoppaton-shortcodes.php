<?php
/**
 * Shortcodes Class
 *
 * @package ShoppatonStore
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Shoppaton Shortcodes Class
 */
class Shoppaton_Shortcodes {

    /**
     * Single instance
     *
     * @var Shoppaton_Shortcodes
     */
    private static $instance = null;

    /**
     * Get instance
     *
     * @return Shoppaton_Shortcodes
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
        $shortcodes = array(
            'shoppaton_header',
            'shoppaton_footer',
            'shoppaton_homepage',
            'shoppaton_shop',
            'shoppaton_product',
            'shoppaton_cart',
            'shoppaton_checkout',
            'shoppaton_account',
            'shoppaton_dashboard',
            'shoppaton_about',
            'shoppaton_shipping_returns',
            'shoppaton_track_order',
            'shoppaton_faq',
            'shoppaton_wishlist',
            'shoppaton_admin_dashboard',
            'shoppaton_products',
            'shoppaton_hero',
            'shoppaton_trust_signals',
            'shoppaton_reviews',
            'shoppaton_contact_form',
            'shoppaton_categories',
        );

        foreach ($shortcodes as $shortcode) {
            add_shortcode($shortcode, array($this, str_replace('shoppaton_', '', $shortcode)));
        }
    }

    /**
     * Header shortcode
     */
    public function header($atts) {
        ob_start();
        include SHOPPATON_PLUGIN_DIR . 'templates/partials/header.php';
        return ob_get_clean();
    }

    /**
     * Footer shortcode
     */
    public function footer($atts) {
        ob_start();
        include SHOPPATON_PLUGIN_DIR . 'templates/partials/footer.php';
        return ob_get_clean();
    }

    /**
     * Homepage shortcode
     */
    public function homepage($atts) {
        ob_start();
        include SHOPPATON_PLUGIN_DIR . 'templates/pages/homepage.php';
        return ob_get_clean();
    }

    /**
     * Shop shortcode
     */
    public function shop($atts) {
        $atts = shortcode_atts(array(
            'category' => '',
            'limit' => 12,
        ), $atts);

        ob_start();
        include SHOPPATON_PLUGIN_DIR . 'templates/pages/shop.php';
        return ob_get_clean();
    }

    /**
     * Single product shortcode
     */
    public function product($atts) {
        $atts = shortcode_atts(array(
            'id' => 0,
            'slug' => '',
        ), $atts);

        ob_start();
        include SHOPPATON_PLUGIN_DIR . 'templates/pages/single-product.php';
        return ob_get_clean();
    }

    /**
     * Cart shortcode
     */
    public function cart($atts) {
        ob_start();
        include SHOPPATON_PLUGIN_DIR . 'templates/pages/cart.php';
        return ob_get_clean();
    }

    /**
     * Checkout shortcode
     */
    public function checkout($atts) {
        ob_start();
        include SHOPPATON_PLUGIN_DIR . 'templates/pages/checkout.php';
        return ob_get_clean();
    }

    /**
     * Account shortcode
     */
    public function account($atts) {
        if (!is_user_logged_in()) {
            // Show login/register form
            ob_start();
            include SHOPPATON_PLUGIN_DIR . 'templates/pages/login.php';
            return ob_get_clean();
        }

        // Redirect to dashboard for logged in users
        ob_start();
        include SHOPPATON_PLUGIN_DIR . 'templates/pages/dashboard.php';
        return ob_get_clean();
    }

    /**
     * About shortcode
     */
    public function about($atts) {
        ob_start();
        include SHOPPATON_PLUGIN_DIR . 'templates/pages/about.php';
        return ob_get_clean();
    }

    /**
     * Shipping & Returns shortcode
     */
    public function shipping_returns($atts) {
        ob_start();
        include SHOPPATON_PLUGIN_DIR . 'templates/pages/shipping-returns.php';
        return ob_get_clean();
    }

    /**
     * Track order shortcode
     */
    public function track_order($atts) {
        ob_start();
        include SHOPPATON_PLUGIN_DIR . 'templates/pages/track-order.php';
        return ob_get_clean();
    }

    /**
     * FAQ shortcode
     */
    public function faq($atts) {
        ob_start();
        include SHOPPATON_PLUGIN_DIR . 'templates/pages/faq.php';
        return ob_get_clean();
    }

    /**
     * Wishlist shortcode
     */
    public function wishlist($atts) {
        ob_start();
        include SHOPPATON_PLUGIN_DIR . 'templates/pages/wishlist.php';
        return ob_get_clean();
    }

    /**
     * Admin dashboard shortcode
     */
    public function admin_dashboard($atts) {
        if (!current_user_can('manage_options')) {
            return '<p>You do not have permission to access this page.</p>';
        }

        ob_start();
        include SHOPPATON_PLUGIN_DIR . 'templates/admin/dashboard.php';
        return ob_get_clean();
    }

    /**
     * User dashboard shortcode
     */
    public function dashboard($atts) {
        if (!is_user_logged_in()) {
            return '<p>Please <a href="' . esc_url(wp_login_url(get_permalink())) . '">log in</a> to view your dashboard.</p>';
        }

        ob_start();
        include SHOPPATON_PLUGIN_DIR . 'templates/pages/dashboard.php';
        return ob_get_clean();
    }

    /**
     * Products grid shortcode
     */
    public function products($atts) {
        $atts = shortcode_atts(array(
            'category' => '',
            'featured' => '',
            'best_seller' => '',
            'limit' => 8,
            'columns' => 4,
        ), $atts);

        $args = array(
            'limit' => intval($atts['limit']),
        );

        if ($atts['category']) {
            $args['category'] = intval($atts['category']);
        }

        if ($atts['featured'] === 'yes') {
            $args['featured'] = true;
        }

        if ($atts['best_seller'] === 'yes') {
            $args['best_seller'] = true;
        }

        $products = Shoppaton_Products::instance()->get_products($args);

        ob_start();
        ?>
        <div class="shoppaton-products-grid" style="grid-template-columns: repeat(<?php echo intval($atts['columns']); ?>, 1fr);">
            <?php foreach ($products as $product) : ?>
                <?php include SHOPPATON_PLUGIN_DIR . 'templates/partials/product-card.php'; ?>
            <?php endforeach; ?>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Hero shortcode
     */
    public function hero($atts) {
        ob_start();
        include SHOPPATON_PLUGIN_DIR . 'templates/partials/hero.php';
        return ob_get_clean();
    }

    /**
     * Trust signals shortcode
     */
    public function trust_signals($atts) {
        ob_start();
        ?>
        <div class="shoppaton-trust-signals">
            <div class="shoppaton-trust-item">
                <div class="shoppaton-trust-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/>
                    </svg>
                </div>
                <span>SSL Secured</span>
            </div>
            <div class="shoppaton-trust-item">
                <div class="shoppaton-trust-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/>
                    </svg>
                </div>
                <span>Paystack Secure</span>
            </div>
            <div class="shoppaton-trust-item">
                <div class="shoppaton-trust-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M18 6h-2c0-2.21-1.79-4-4-4S8 3.79 8 6H6c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm-6-2c1.1 0 2 .9 2 2h-4c0-1.1.9-2 2-2zm6 16H6V8h2v2c0 .55.45 1 1 1s1-.45 1-1V8h4v2c0 .55.45 1 1 1s1-.45 1-1V8h2v12z"/>
                    </svg>
                </div>
                <span>Verified Products</span>
            </div>
            <div class="shoppaton-trust-item">
                <div class="shoppaton-trust-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M20 8h-3V4H3c-1.1 0-2 .9-2 2v11h2c0 1.66 1.34 3 3 3s3-1.34 3-3h6c0 1.66 1.34 3 3 3s3-1.34 3-3h2v-5l-3-4zM6 18.5c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm13.5-9l1.96 2.5H17V9.5h2.5zm-1.5 9c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/>
                    </svg>
                </div>
                <span>Fast Delivery</span>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Reviews shortcode
     */
    public function reviews($atts) {
        $atts = shortcode_atts(array(
            'limit' => 6,
        ), $atts);

        // Sample reviews - in production, these would come from database or Google Reviews API
        $reviews = array(
            array(
                'author' => 'Blessing A.',
                'rating' => 5,
                'text' => 'Amazing products! My skin has never looked better. The customer service is top-notch too.',
                'verified' => true,
            ),
            array(
                'author' => 'Chioma O.',
                'rating' => 5,
                'text' => 'Fast delivery and original products. Will definitely order again. Love the packaging!',
                'verified' => true,
            ),
            array(
                'author' => 'Funke M.',
                'rating' => 5,
                'text' => 'Best skincare store in Lagos! The recommendations were perfect for my skin type.',
                'verified' => true,
            ),
        );

        ob_start();
        ?>
        <div class="shoppaton-reviews-slider">
            <?php foreach ($reviews as $review) : ?>
            <div class="shoppaton-review-card">
                <div class="shoppaton-review-stars">
                    <?php for ($i = 0; $i < $review['rating']; $i++) : ?>
                    <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" fill="currentColor"/></svg>
                    <?php endfor; ?>
                </div>
                <p class="shoppaton-review-text">"<?php echo esc_html($review['text']); ?>"</p>
                <div class="shoppaton-review-author">
                    <div class="shoppaton-review-avatar" style="background: linear-gradient(135deg, #D4AF37, #B8860B); display: flex; align-items: center; justify-content: center; color: #0a0a0a; font-weight: bold;">
                        <?php echo esc_html(substr($review['author'], 0, 1)); ?>
                    </div>
                    <div>
                        <div class="shoppaton-review-name"><?php echo esc_html($review['author']); ?></div>
                        <?php if ($review['verified']) : ?>
                        <span class="shoppaton-review-badge">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                            Verified Purchase
                        </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Contact form shortcode
     */
    public function contact_form($atts) {
        ob_start();
        ?>
        <form class="shoppaton-contact-form">
            <div class="shoppaton-form-group">
                <label class="shoppaton-form-label">Your Name *</label>
                <input type="text" name="name" class="shoppaton-form-input" placeholder="Enter your name" required>
            </div>
            <div class="shoppaton-form-group">
                <label class="shoppaton-form-label">Email Address *</label>
                <input type="email" name="email" class="shoppaton-form-input" placeholder="Enter your email" required>
            </div>
            <div class="shoppaton-form-group">
                <label class="shoppaton-form-label">Phone Number</label>
                <input type="tel" name="phone" class="shoppaton-form-input" placeholder="Enter your phone number">
            </div>
            <div class="shoppaton-form-group">
                <label class="shoppaton-form-label">Subject</label>
                <input type="text" name="subject" class="shoppaton-form-input" placeholder="What is this about?">
            </div>
            <div class="shoppaton-form-group">
                <label class="shoppaton-form-label">Message *</label>
                <textarea name="message" class="shoppaton-form-textarea" placeholder="How can we help you?" required></textarea>
            </div>
            <button type="submit" class="shoppaton-btn shoppaton-btn-primary" style="width: 100%;">
                Send Message
            </button>
        </form>
        <?php
        return ob_get_clean();
    }

    /**
     * Categories shortcode
     */
    public function categories($atts) {
        ob_start();
        include SHOPPATON_PLUGIN_DIR . 'templates/pages/categories.php';
        return ob_get_clean();
    }
}
