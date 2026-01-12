<?php
/**
 * Settings Class
 *
 * @package ShoppatonStore
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Shoppaton Settings Class
 */
class Shoppaton_Settings {

    /**
     * Single instance
     *
     * @var Shoppaton_Settings
     */
    private static $instance = null;

    /**
     * Settings cache
     *
     * @var array
     */
    private $settings = array();

    /**
     * Get instance
     *
     * @return Shoppaton_Settings
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
        $this->load_settings();
    }

    /**
     * Load settings
     */
    private function load_settings() {
        $this->settings = array(
            'paystack_test_public' => get_option('shoppaton_paystack_test_public', ''),
            'paystack_test_secret' => get_option('shoppaton_paystack_test_secret', ''),
            'paystack_live_public' => get_option('shoppaton_paystack_live_public', ''),
            'paystack_live_secret' => get_option('shoppaton_paystack_live_secret', ''),
            'paystack_mode' => get_option('shoppaton_paystack_mode', 'test'),
            'whatsapp_number' => get_option('shoppaton_whatsapp_number', '09018003719'),
            'contact_email' => get_option('shoppaton_contact_email', 'hello@shoppaton.com'),
            'instagram' => get_option('shoppaton_instagram', ''),
            'facebook' => get_option('shoppaton_facebook', ''),
            'tiktok' => get_option('shoppaton_tiktok', ''),
            'delivery_companies' => get_option('shoppaton_delivery_companies', array()),
            'shipping_rates' => get_option('shoppaton_shipping_rates', array()),
            'abandoned_cart_enabled' => get_option('shoppaton_abandoned_cart_enabled', true),
            'abandoned_cart_interval' => get_option('shoppaton_abandoned_cart_interval', 3),
        );
    }

    /**
     * Get setting
     *
     * @param string $key Setting key
     * @param mixed $default Default value
     * @return mixed
     */
    public function get($key, $default = '') {
        return isset($this->settings[$key]) ? $this->settings[$key] : $default;
    }

    /**
     * Update setting
     *
     * @param string $key Setting key
     * @param mixed $value Setting value
     * @return bool
     */
    public function update($key, $value) {
        $option_key = 'shoppaton_' . $key;
        $updated = update_option($option_key, $value);
        if ($updated) {
            $this->settings[$key] = $value;
        }
        return $updated;
    }

    /**
     * Get Paystack public key
     *
     * @return string
     */
    public function get_paystack_public_key() {
        $mode = $this->get('paystack_mode');
        return $mode === 'live' 
            ? $this->get('paystack_live_public') 
            : $this->get('paystack_test_public');
    }

    /**
     * Get Paystack secret key
     *
     * @return string
     */
    public function get_paystack_secret_key() {
        $mode = $this->get('paystack_mode');
        return $mode === 'live' 
            ? $this->get('paystack_live_secret') 
            : $this->get('paystack_test_secret');
    }

    /**
     * Get shipping rate for state
     *
     * @param string $state State name
     * @return float
     */
    public function get_shipping_rate($state) {
        $rates = $this->get('shipping_rates');
        $state = strtolower(trim($state));
        
        // Default Lagos shipping rates
        $default_rates = array(
            'lagos' => 1500,
            'ogun' => 2500,
            'oyo' => 3000,
            'osun' => 3500,
            'ondo' => 3500,
            'ekiti' => 4000,
            'kwara' => 4500,
            'kogi' => 5000,
            'default' => 5000
        );

        if (isset($rates[$state])) {
            return floatval($rates[$state]);
        }

        if (isset($default_rates[$state])) {
            return floatval($default_rates[$state]);
        }

        return floatval($default_rates['default']);
    }

    /**
     * Get all Nigerian states
     *
     * @return array
     */
    public static function get_nigeria_states() {
        return array(
            'abia' => 'Abia',
            'adamawa' => 'Adamawa',
            'akwa-ibom' => 'Akwa Ibom',
            'anambra' => 'Anambra',
            'bauchi' => 'Bauchi',
            'bayelsa' => 'Bayelsa',
            'benue' => 'Benue',
            'borno' => 'Borno',
            'cross-river' => 'Cross River',
            'delta' => 'Delta',
            'ebonyi' => 'Ebonyi',
            'edo' => 'Edo',
            'ekiti' => 'Ekiti',
            'enugu' => 'Enugu',
            'fct' => 'FCT - Abuja',
            'gombe' => 'Gombe',
            'imo' => 'Imo',
            'jigawa' => 'Jigawa',
            'kaduna' => 'Kaduna',
            'kano' => 'Kano',
            'katsina' => 'Katsina',
            'kebbi' => 'Kebbi',
            'kogi' => 'Kogi',
            'kwara' => 'Kwara',
            'lagos' => 'Lagos',
            'nasarawa' => 'Nasarawa',
            'niger' => 'Niger',
            'ogun' => 'Ogun',
            'ondo' => 'Ondo',
            'osun' => 'Osun',
            'oyo' => 'Oyo',
            'plateau' => 'Plateau',
            'rivers' => 'Rivers',
            'sokoto' => 'Sokoto',
            'taraba' => 'Taraba',
            'yobe' => 'Yobe',
            'zamfara' => 'Zamfara'
        );
    }

    /**
     * Format price
     *
     * @param float $price Price
     * @return string
     */
    public static function format_price($price) {
        return '₦' . number_format(floatval($price), 2);
    }

    /**
     * Get WhatsApp link
     *
     * @param string $message Optional message
     * @return string
     */
    public function get_whatsapp_link($message = '') {
        $number = $this->get('whatsapp_number');
        // Convert Nigerian number format
        $number = preg_replace('/^0/', '234', $number);
        $number = preg_replace('/[^0-9]/', '', $number);
        
        $url = 'https://wa.me/' . $number;
        if ($message) {
            $url .= '?text=' . rawurlencode($message);
        }
        return $url;
    }
}
