<?php
/**
 * Plugin Name: Shoppaton Store
 * Plugin URI: https://shoppaton.com
 * Description: A luxury e-commerce WordPress plugin for Shoppaton Store - Premium Skincare Essentials in Lagos, Nigeria. Features glassmorphism design, Paystack payments, offline cart, and comprehensive admin management.
 * Version: 1.0.0
 * Author: Shoppaton
 * Author URI: https://shoppaton.com
 * Text Domain: shoppaton-store
 * Domain Path: /languages
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package ShoppatonStore
 */

if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('SHOPPATON_VERSION', '1.0.0');
define('SHOPPATON_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SHOPPATON_PLUGIN_URL', plugin_dir_url(__FILE__));
define('SHOPPATON_PLUGIN_BASENAME', plugin_basename(__FILE__));
define('SHOPPATON_ASSETS_URL', SHOPPATON_PLUGIN_URL . 'assets/');

/**
 * Main Shoppaton Store Class
 */
final class Shoppaton_Store {

    /**
     * Single instance of the class
     *
     * @var Shoppaton_Store
     */
    private static $instance = null;

    /**
     * Get single instance of the class
     *
     * @return Shoppaton_Store
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
        $this->includes();
        $this->init_hooks();
    }

    /**
     * Include required files
     */
    private function includes() {
        // Core includes
        require_once SHOPPATON_PLUGIN_DIR . 'includes/class-shoppaton-settings.php';
        require_once SHOPPATON_PLUGIN_DIR . 'includes/class-shoppaton-assets.php';
        require_once SHOPPATON_PLUGIN_DIR . 'includes/class-shoppaton-products.php';
        require_once SHOPPATON_PLUGIN_DIR . 'includes/class-shoppaton-cart.php';
        require_once SHOPPATON_PLUGIN_DIR . 'includes/class-shoppaton-checkout.php';
        require_once SHOPPATON_PLUGIN_DIR . 'includes/class-shoppaton-orders.php';
        require_once SHOPPATON_PLUGIN_DIR . 'includes/class-shoppaton-user.php';
        require_once SHOPPATON_PLUGIN_DIR . 'includes/class-shoppaton-emails.php';
        require_once SHOPPATON_PLUGIN_DIR . 'includes/class-shoppaton-analytics.php';
        require_once SHOPPATON_PLUGIN_DIR . 'includes/class-shoppaton-ajax.php';
        require_once SHOPPATON_PLUGIN_DIR . 'includes/class-shoppaton-shortcodes.php';
        require_once SHOPPATON_PLUGIN_DIR . 'includes/class-shoppaton-widgets.php';
        require_once SHOPPATON_PLUGIN_DIR . 'includes/class-shoppaton-service-worker.php';
        
        // Admin includes
        if (is_admin()) {
            require_once SHOPPATON_PLUGIN_DIR . 'includes/admin/class-shoppaton-admin.php';
        }
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
        
        add_action('init', array($this, 'init'));
        add_action('plugins_loaded', array($this, 'on_plugins_loaded'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
        add_action('wp_head', array($this, 'add_preconnects'));
        add_action('wp_footer', array($this, 'render_widgets'));
        add_action('wp_footer', array($this, 'render_mobile_nav'), 99);
        add_action('wp_footer', array($this, 'render_pwa_install'), 100);
    }

    /**
     * Plugin activation
     */
    public function activate() {
        // Create database tables
        $this->create_tables();
        
        // Create pages
        $this->create_pages();
        
        // Set default options
        $this->set_default_options();
        
        // Flush rewrite rules
        flush_rewrite_rules();
    }

    /**
     * Plugin deactivation
     */
    public function deactivate() {
        flush_rewrite_rules();
    }

    /**
     * Create database tables
     */
    private function create_tables() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        // Products table
        $products_table = $wpdb->prefix . 'shoppaton_products';
        $sql_products = "CREATE TABLE IF NOT EXISTS $products_table (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            slug varchar(255) NOT NULL,
            description longtext,
            short_description text,
            price decimal(10,2) NOT NULL DEFAULT 0,
            sale_price decimal(10,2) DEFAULT NULL,
            sku varchar(100) DEFAULT NULL,
            stock_quantity int(11) DEFAULT 0,
            stock_status varchar(50) DEFAULT 'instock',
            category_id bigint(20) unsigned DEFAULT NULL,
            images longtext,
            skin_type varchar(255) DEFAULT NULL,
            target_user varchar(255) DEFAULT NULL,
            usage_guide longtext,
            featured tinyint(1) DEFAULT 0,
            best_seller tinyint(1) DEFAULT 0,
            status varchar(20) DEFAULT 'publish',
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY slug (slug),
            KEY category_id (category_id),
            KEY status (status)
        ) $charset_collate;";

        // Categories table
        $categories_table = $wpdb->prefix . 'shoppaton_categories';
        $sql_categories = "CREATE TABLE IF NOT EXISTS $categories_table (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            slug varchar(255) NOT NULL,
            description text,
            image varchar(500) DEFAULT NULL,
            parent_id bigint(20) unsigned DEFAULT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY slug (slug)
        ) $charset_collate;";

        // Orders table
        $orders_table = $wpdb->prefix . 'shoppaton_orders';
        $sql_orders = "CREATE TABLE IF NOT EXISTS $orders_table (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            order_number varchar(50) NOT NULL,
            user_id bigint(20) unsigned DEFAULT NULL,
            status varchar(50) DEFAULT 'pending',
            total decimal(10,2) NOT NULL,
            subtotal decimal(10,2) NOT NULL,
            shipping_cost decimal(10,2) DEFAULT 0,
            discount decimal(10,2) DEFAULT 0,
            payment_method varchar(50) DEFAULT NULL,
            payment_status varchar(50) DEFAULT 'pending',
            payment_reference varchar(255) DEFAULT NULL,
            billing_name varchar(255) NOT NULL,
            billing_email varchar(255) NOT NULL,
            billing_phone varchar(50) NOT NULL,
            billing_address text NOT NULL,
            billing_city varchar(100) NOT NULL,
            billing_state varchar(100) NOT NULL,
            shipping_name varchar(255) DEFAULT NULL,
            shipping_phone varchar(50) DEFAULT NULL,
            shipping_address text DEFAULT NULL,
            shipping_city varchar(100) DEFAULT NULL,
            shipping_state varchar(100) DEFAULT NULL,
            tracking_number varchar(255) DEFAULT NULL,
            tracking_url varchar(500) DEFAULT NULL,
            delivery_company varchar(100) DEFAULT NULL,
            notes text,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY order_number (order_number),
            KEY user_id (user_id),
            KEY status (status)
        ) $charset_collate;";

        // Order items table
        $order_items_table = $wpdb->prefix . 'shoppaton_order_items';
        $sql_order_items = "CREATE TABLE IF NOT EXISTS $order_items_table (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            order_id bigint(20) unsigned NOT NULL,
            product_id bigint(20) unsigned NOT NULL,
            product_name varchar(255) NOT NULL,
            quantity int(11) NOT NULL DEFAULT 1,
            price decimal(10,2) NOT NULL,
            total decimal(10,2) NOT NULL,
            PRIMARY KEY (id),
            KEY order_id (order_id),
            KEY product_id (product_id)
        ) $charset_collate;";

        // Cart table (for offline sync)
        $cart_table = $wpdb->prefix . 'shoppaton_cart';
        $sql_cart = "CREATE TABLE IF NOT EXISTS $cart_table (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            session_id varchar(255) NOT NULL,
            user_id bigint(20) unsigned DEFAULT NULL,
            product_id bigint(20) unsigned NOT NULL,
            quantity int(11) NOT NULL DEFAULT 1,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY session_id (session_id),
            KEY user_id (user_id)
        ) $charset_collate;";

        // Wishlist table
        $wishlist_table = $wpdb->prefix . 'shoppaton_wishlist';
        $sql_wishlist = "CREATE TABLE IF NOT EXISTS $wishlist_table (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            user_id bigint(20) unsigned DEFAULT NULL,
            session_id varchar(255) DEFAULT NULL,
            product_id bigint(20) unsigned NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY user_id (user_id),
            KEY session_id (session_id)
        ) $charset_collate;";

        // Reviews table
        $reviews_table = $wpdb->prefix . 'shoppaton_reviews';
        $sql_reviews = "CREATE TABLE IF NOT EXISTS $reviews_table (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            product_id bigint(20) unsigned NOT NULL,
            user_id bigint(20) unsigned DEFAULT NULL,
            author_name varchar(255) NOT NULL,
            author_email varchar(255) NOT NULL,
            rating tinyint(1) NOT NULL DEFAULT 5,
            review text NOT NULL,
            status varchar(20) DEFAULT 'pending',
            verified_purchase tinyint(1) DEFAULT 0,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY product_id (product_id),
            KEY status (status)
        ) $charset_collate;";

        // Analytics table
        $analytics_table = $wpdb->prefix . 'shoppaton_analytics';
        $sql_analytics = "CREATE TABLE IF NOT EXISTS $analytics_table (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            event_type varchar(100) NOT NULL,
            event_data longtext,
            user_id bigint(20) unsigned DEFAULT NULL,
            session_id varchar(255) DEFAULT NULL,
            page_url varchar(500) DEFAULT NULL,
            referrer varchar(500) DEFAULT NULL,
            user_agent text,
            ip_address varchar(45) DEFAULT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY event_type (event_type),
            KEY created_at (created_at)
        ) $charset_collate;";

        // Abandoned carts table
        $abandoned_table = $wpdb->prefix . 'shoppaton_abandoned_carts';
        $sql_abandoned = "CREATE TABLE IF NOT EXISTS $abandoned_table (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            session_id varchar(255) NOT NULL,
            user_id bigint(20) unsigned DEFAULT NULL,
            email varchar(255) DEFAULT NULL,
            cart_data longtext NOT NULL,
            cart_total decimal(10,2) DEFAULT 0,
            email_sent_count int(11) DEFAULT 0,
            last_email_sent datetime DEFAULT NULL,
            recovered tinyint(1) DEFAULT 0,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY session_id (session_id),
            KEY email (email)
        ) $charset_collate;";

        // Slider images table
        $slider_table = $wpdb->prefix . 'shoppaton_sliders';
        $sql_slider = "CREATE TABLE IF NOT EXISTS $slider_table (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            title varchar(255) DEFAULT NULL,
            subtitle text DEFAULT NULL,
            image varchar(500) NOT NULL,
            button_text varchar(100) DEFAULT NULL,
            button_url varchar(500) DEFAULT NULL,
            sort_order int(11) DEFAULT 0,
            status varchar(20) DEFAULT 'active',
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql_products);
        dbDelta($sql_categories);
        dbDelta($sql_orders);
        dbDelta($sql_order_items);
        dbDelta($sql_cart);
        dbDelta($sql_wishlist);
        dbDelta($sql_reviews);
        dbDelta($sql_analytics);
        dbDelta($sql_abandoned);
        dbDelta($sql_slider);
    }

    /**
     * Create default pages
     */
    private function create_pages() {
        $pages = array(
            'shop' => array(
                'title' => 'Shop',
                'content' => '[shoppaton_shop]'
            ),
            'cart' => array(
                'title' => 'Cart',
                'content' => '[shoppaton_cart]'
            ),
            'checkout' => array(
                'title' => 'Checkout',
                'content' => '[shoppaton_checkout]'
            ),
            'my-account' => array(
                'title' => 'My Account',
                'content' => '[shoppaton_account]'
            ),
            'about-us' => array(
                'title' => 'About Us',
                'content' => '[shoppaton_about]'
            ),
            'shipping-returns' => array(
                'title' => 'Shipping & Returns',
                'content' => '[shoppaton_shipping_returns]'
            ),
            'track-order' => array(
                'title' => 'Track Order',
                'content' => '[shoppaton_track_order]'
            ),
            'faq' => array(
                'title' => 'FAQ',
                'content' => '[shoppaton_faq]'
            ),
            'wishlist' => array(
                'title' => 'Wishlist',
                'content' => '[shoppaton_wishlist]'
            ),
            'admin-dashboard' => array(
                'title' => 'Admin Dashboard',
                'content' => '[shoppaton_admin_dashboard]'
            )
        );

        foreach ($pages as $slug => $page) {
            $existing_page = get_page_by_path($slug);
            if (!$existing_page) {
                wp_insert_post(array(
                    'post_title' => $page['title'],
                    'post_name' => $slug,
                    'post_content' => $page['content'],
                    'post_status' => 'publish',
                    'post_type' => 'page'
                ));
            }
        }
    }

    /**
     * Set default options
     */
    private function set_default_options() {
        $defaults = array(
            'shoppaton_paystack_test_public' => '',
            'shoppaton_paystack_test_secret' => '',
            'shoppaton_paystack_live_public' => '',
            'shoppaton_paystack_live_secret' => '',
            'shoppaton_paystack_mode' => 'test',
            'shoppaton_whatsapp_number' => '09018003719',
            'shoppaton_contact_email' => 'hello@shoppaton.com',
            'shoppaton_instagram' => '',
            'shoppaton_facebook' => '',
            'shoppaton_tiktok' => '',
            'shoppaton_delivery_companies' => array(),
            'shoppaton_shipping_rates' => array(),
            'shoppaton_abandoned_cart_enabled' => true,
            'shoppaton_abandoned_cart_interval' => 3,
        );

        foreach ($defaults as $key => $value) {
            if (get_option($key) === false) {
                update_option($key, $value);
            }
        }
    }

    /**
     * Initialize plugin
     */
    public function init() {
        load_plugin_textdomain('shoppaton-store', false, dirname(SHOPPATON_PLUGIN_BASENAME) . '/languages');
        
        // Start session if not started
        if (!session_id()) {
            session_start();
        }
        
        // Generate session ID for guests
        if (!isset($_SESSION['shoppaton_session_id'])) {
            $_SESSION['shoppaton_session_id'] = wp_generate_uuid4();
        }
    }

    /**
     * Plugins loaded hook
     */
    public function on_plugins_loaded() {
        // Initialize components
        Shoppaton_Settings::instance();
        Shoppaton_Assets::instance();
        Shoppaton_Products::instance();
        Shoppaton_Cart::instance();
        Shoppaton_Checkout::instance();
        Shoppaton_Orders::instance();
        Shoppaton_User::instance();
        Shoppaton_Emails::instance();
        Shoppaton_Analytics::instance();
        Shoppaton_Ajax::instance();
        Shoppaton_Shortcodes::instance();
        Shoppaton_Widgets::instance();
        Shoppaton_Service_Worker::instance();
        
        if (is_admin()) {
            Shoppaton_Admin::instance();
        }
    }

    /**
     * Enqueue assets
     */
    public function enqueue_assets() {
        Shoppaton_Assets::instance()->enqueue();
    }

    /**
     * Add preconnects for performance
     */
    public function add_preconnects() {
        echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
        echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
        echo '<link rel="dns-prefetch" href="https://js.paystack.co">' . "\n";
    }

    /**
     * Render widgets (WhatsApp, Scroll to top)
     */
    public function render_widgets() {
        Shoppaton_Widgets::instance()->render();
    }

    /**
     * Render mobile navigation
     */
    public function render_mobile_nav() {
        // Always render mobile nav - CSS will handle visibility
        include SHOPPATON_PLUGIN_DIR . 'templates/partials/mobile-nav.php';
    }

    /**
     * Get session ID
     */
    public static function get_session_id() {
        if (isset($_SESSION['shoppaton_session_id'])) {
            return sanitize_text_field($_SESSION['shoppaton_session_id']);
        }
        return '';
    }

    /**
     * Render PWA install prompt
     */
    public function render_pwa_install() {
        ?>
        <!-- PWA Install Prompt -->
        <div id="shoppaton-pwa-install" class="shoppaton-pwa-install" style="display: none;">
            <div class="shoppaton-pwa-content">
                <button class="shoppaton-pwa-close" onclick="closePWAInstall()">&times;</button>
                <div class="shoppaton-pwa-icon">
                    <img src="<?php echo esc_url(SHOPPATON_ASSETS_URL . 'images/logo.png'); ?>" alt="Shoppaton" style="width: 50px; height: 50px; border-radius: 10px;">
                </div>
                <div class="shoppaton-pwa-text">
                    <h4 style="margin: 0 0 5px; color: var(--shoppaton-gold); font-size: 14px;">Install Shoppaton App</h4>
                    <p style="margin: 0; font-size: 11px; color: var(--shoppaton-text-muted);" id="pwa-instruction">Add to home screen for the best experience</p>
                </div>
                <button class="shoppaton-btn shoppaton-btn-primary shoppaton-btn-sm" onclick="installPWA()">Install</button>
            </div>
        </div>
        <style>
        .shoppaton-pwa-install {
            position: fixed;
            bottom: 80px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--shoppaton-glass);
            backdrop-filter: blur(20px);
            border: 1px solid var(--shoppaton-glass-border);
            border-radius: 12px;
            padding: 12px 15px;
            z-index: 9999;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            max-width: 340px;
            width: calc(100% - 30px);
        }
        .shoppaton-pwa-content {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .shoppaton-pwa-close {
            position: absolute;
            top: 5px;
            right: 8px;
            background: none;
            border: none;
            color: var(--shoppaton-text-muted);
            font-size: 18px;
            cursor: pointer;
            padding: 0;
            line-height: 1;
        }
        .shoppaton-pwa-text { flex: 1; }
        @media (min-width: 769px) {
            .shoppaton-pwa-install { bottom: 25px; }
        }
        </style>
        <script>
        let deferredPrompt;
        const pwaInstall = document.getElementById('shoppaton-pwa-install');
        const pwaInstruction = document.getElementById('pwa-instruction');
        
        // Detect browser and show instructions
        function detectBrowser() {
            const ua = navigator.userAgent;
            if (/iPad|iPhone|iPod/.test(ua)) {
                return 'ios';
            } else if (/Android/.test(ua)) {
                return 'android';
            } else if (/Chrome/.test(ua)) {
                return 'chrome';
            } else if (/Firefox/.test(ua)) {
                return 'firefox';
            } else if (/Safari/.test(ua)) {
                return 'safari';
            }
            return 'other';
        }
        
        function showPWAInstall() {
            const browser = detectBrowser();
            const dismissed = localStorage.getItem('pwa_dismissed');
            const installed = localStorage.getItem('pwa_installed');
            
            if (dismissed || installed) return;
            
            if (browser === 'ios') {
                pwaInstruction.innerHTML = 'Tap <svg style="width:14px;height:14px;vertical-align:middle" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l4 4h-3v9h-2V6H8l4-4zm6 9v9H6v-9H4v9c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2v-9h-2z"/></svg> then "Add to Home Screen"';
                pwaInstall.style.display = 'block';
            } else if (browser === 'safari') {
                pwaInstruction.innerHTML = 'Click Share then "Add to Dock"';
                pwaInstall.style.display = 'block';
            } else if (deferredPrompt || browser === 'android' || browser === 'chrome') {
                pwaInstruction.textContent = 'Install for offline access & faster loading';
                pwaInstall.style.display = 'block';
            }
        }
        
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            showPWAInstall();
        });
        
        window.addEventListener('appinstalled', () => {
            localStorage.setItem('pwa_installed', 'true');
            pwaInstall.style.display = 'none';
        });
        
        function installPWA() {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then((choice) => {
                    if (choice.outcome === 'accepted') {
                        localStorage.setItem('pwa_installed', 'true');
                    }
                    deferredPrompt = null;
                    pwaInstall.style.display = 'none';
                });
            } else {
                // For iOS/Safari - just close the prompt
                closePWAInstall();
            }
        }
        
        function closePWAInstall() {
            pwaInstall.style.display = 'none';
            localStorage.setItem('pwa_dismissed', Date.now());
        }
        
        // Show after 3 seconds
        setTimeout(showPWAInstall, 3000);
        </script>
        <?php
    }
}

/**
 * Initialize plugin
 */
function shoppaton_store() {
    return Shoppaton_Store::instance();
}

// Initialize
shoppaton_store();
