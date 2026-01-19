<?php
/**
 * Service Worker Class
 *
 * @package ShoppatonStore
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Shoppaton Service Worker Class
 */
class Shoppaton_Service_Worker {

    /**
     * Single instance
     *
     * @var Shoppaton_Service_Worker
     */
    private static $instance = null;

    /**
     * Get instance
     *
     * @return Shoppaton_Service_Worker
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
        add_action('wp_head', array($this, 'add_manifest'));
        add_action('wp_head', array($this, 'add_theme_color'));
    }

    /**
     * Add web app manifest link
     */
    public function add_manifest() {
        ?>
        <link rel="manifest" href="<?php echo esc_url(SHOPPATON_ASSETS_URL . 'manifest.json'); ?>">
        <link rel="apple-touch-icon" href="<?php echo esc_url(SHOPPATON_ASSETS_URL . 'images/logo.png'); ?>">
        <?php
    }

    /**
     * Add theme color meta tag
     */
    public function add_theme_color() {
        ?>
        <meta name="theme-color" content="#0a0a0a">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <?php
    }
}
