<?php
/**
 * Assets Class
 *
 * @package ShoppatonStore
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Shoppaton Assets Class
 */
class Shoppaton_Assets {

    /**
     * Single instance
     *
     * @var Shoppaton_Assets
     */
    private static $instance = null;

    /**
     * Get instance
     *
     * @return Shoppaton_Assets
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
        add_action('wp_enqueue_scripts', array($this, 'enqueue'), 10);
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue'));
    }

    /**
     * Enqueue frontend assets
     */
    public function enqueue() {
        // Google Fonts - Playfair Display for luxury feel
        wp_enqueue_style(
            'shoppaton-fonts',
            'https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600;700&display=swap',
            array(),
            null
        );

        // Main stylesheet
        wp_enqueue_style(
            'shoppaton-style',
            SHOPPATON_ASSETS_URL . 'css/shoppaton-style.css',
            array(),
            SHOPPATON_VERSION
        );

        // Animations stylesheet
        wp_enqueue_style(
            'shoppaton-animations',
            SHOPPATON_ASSETS_URL . 'css/shoppaton-animations.css',
            array('shoppaton-style'),
            SHOPPATON_VERSION
        );

        // Paystack SDK
        wp_enqueue_script(
            'paystack-inline',
            'https://js.paystack.co/v1/inline.js',
            array(),
            null,
            true
        );

        // Main JavaScript
        wp_enqueue_script(
            'shoppaton-main',
            SHOPPATON_ASSETS_URL . 'js/shoppaton-main.js',
            array('jquery'),
            SHOPPATON_VERSION,
            true
        );

        // Service Worker registration
        wp_enqueue_script(
            'shoppaton-sw-register',
            SHOPPATON_ASSETS_URL . 'js/sw-register.js',
            array(),
            SHOPPATON_VERSION,
            true
        );

        // Localize script
        wp_localize_script('shoppaton-main', 'shoppatonData', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('shoppaton_nonce'),
            'cartUrl' => get_permalink(get_page_by_path('cart')),
            'checkoutUrl' => get_permalink(get_page_by_path('checkout')),
            'shopUrl' => get_permalink(get_page_by_path('shop')),
            'accountUrl' => get_permalink(get_page_by_path('my-account')),
            'homeUrl' => home_url(),
            'assetsUrl' => SHOPPATON_ASSETS_URL,
            'paystackKey' => Shoppaton_Settings::instance()->get_paystack_public_key(),
            'whatsappNumber' => Shoppaton_Settings::instance()->get('whatsapp_number'),
            'currency' => '₦',
            'isLoggedIn' => is_user_logged_in(),
            'userId' => get_current_user_id(),
            'sessionId' => Shoppaton_Store::get_session_id(),
        ));
    }

    /**
     * Enqueue admin assets
     */
    public function admin_enqueue($hook) {
        // Only on plugin admin pages
        if (strpos($hook, 'shoppaton') === false) {
            return;
        }

        wp_enqueue_media();

        wp_enqueue_style(
            'shoppaton-admin',
            SHOPPATON_ASSETS_URL . 'css/shoppaton-admin.css',
            array(),
            SHOPPATON_VERSION
        );

        wp_enqueue_script(
            'shoppaton-admin',
            SHOPPATON_ASSETS_URL . 'js/shoppaton-admin.js',
            array('jquery', 'wp-color-picker'),
            SHOPPATON_VERSION,
            true
        );

        wp_localize_script('shoppaton-admin', 'shoppatonAdmin', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('shoppaton_admin_nonce'),
        ));
    }
}
