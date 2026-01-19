<?php
/**
 * User Class
 *
 * @package ShoppatonStore
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Shoppaton User Class
 */
class Shoppaton_User {

    /**
     * Single instance
     *
     * @var Shoppaton_User
     */
    private static $instance = null;

    /**
     * Get instance
     *
     * @return Shoppaton_User
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
        add_action('wp_login', array($this, 'on_login'), 10, 2);
        add_action('user_register', array($this, 'on_register'));
    }

    /**
     * Handle user login
     *
     * @param string $user_login Username
     * @param WP_User $user User object
     */
    public function on_login($user_login, $user) {
        // Merge guest cart to user cart
        Shoppaton_Cart::instance()->merge_to_user($user->ID);
    }

    /**
     * Handle user registration
     *
     * @param int $user_id User ID
     */
    public function on_register($user_id) {
        // Merge guest cart to user cart
        Shoppaton_Cart::instance()->merge_to_user($user_id);
    }

    /**
     * Get user dashboard data
     *
     * @param int $user_id User ID
     * @return array
     */
    public function get_dashboard_data($user_id) {
        $user = get_userdata($user_id);
        
        if (!$user) {
            return array();
        }

        $orders = Shoppaton_Orders::instance()->get_user_orders($user_id);
        $wishlist = $this->get_wishlist($user_id);

        // Calculate stats
        $total_orders = count($orders);
        $total_spent = array_sum(array_column($orders, 'total'));
        
        return array(
            'user' => array(
                'id' => $user->ID,
                'name' => $user->display_name,
                'email' => $user->user_email,
                'registered' => $user->user_registered,
            ),
            'stats' => array(
                'total_orders' => $total_orders,
                'total_spent' => $total_spent,
                'total_spent_formatted' => Shoppaton_Settings::format_price($total_spent),
            ),
            'recent_orders' => array_slice($orders, 0, 5),
            'wishlist' => $wishlist,
        );
    }

    /**
     * Get user wishlist
     *
     * @param int $user_id User ID
     * @return array
     */
    public function get_wishlist($user_id) {
        global $wpdb;
        $wishlist_table = $wpdb->prefix . 'shoppaton_wishlist';
        $products_table = $wpdb->prefix . 'shoppaton_products';

        $results = $wpdb->get_results($wpdb->prepare(
            "SELECT p.* FROM {$wishlist_table} w 
             JOIN {$products_table} p ON w.product_id = p.id 
             WHERE w.user_id = %d AND p.status = 'publish'
             ORDER BY w.created_at DESC",
            $user_id
        ));

        foreach ($results as &$product) {
            $product->images = json_decode($product->images, true) ?: array();
        }

        return $results;
    }

    /**
     * Toggle wishlist item
     *
     * @param int $product_id Product ID
     * @return bool Added (true) or removed (false)
     */
    public function toggle_wishlist($product_id) {
        global $wpdb;
        $table = $wpdb->prefix . 'shoppaton_wishlist';

        $user_id = get_current_user_id();
        $session_id = Shoppaton_Store::get_session_id();

        $where = $user_id ? array('user_id' => $user_id, 'product_id' => $product_id)
                         : array('session_id' => $session_id, 'product_id' => $product_id);

        $existing = $wpdb->get_var($wpdb->prepare(
            "SELECT id FROM {$table} WHERE " . ($user_id ? "user_id = %d" : "session_id = %s") . " AND product_id = %d",
            $user_id ?: $session_id,
            $product_id
        ));

        if ($existing) {
            $wpdb->delete($table, array('id' => $existing));
            return false;
        } else {
            $result = $wpdb->insert($table, array(
                'user_id' => $user_id ?: null,
                'session_id' => $session_id,
                'product_id' => $product_id,
            ));
            return $result !== false;
        }
    }

    /**
     * Is product in wishlist
     *
     * @param int $product_id Product ID
     * @return bool
     */
    public function is_in_wishlist($product_id) {
        global $wpdb;
        $table = $wpdb->prefix . 'shoppaton_wishlist';

        $user_id = get_current_user_id();
        $session_id = Shoppaton_Store::get_session_id();

        $exists = $wpdb->get_var($wpdb->prepare(
            "SELECT id FROM {$table} WHERE " . ($user_id ? "user_id = %d" : "session_id = %s") . " AND product_id = %d",
            $user_id ?: $session_id,
            $product_id
        ));

        return (bool) $exists;
    }

    /**
     * Update user profile
     *
     * @param int $user_id User ID
     * @param array $data Profile data
     * @return bool|WP_Error
     */
    public function update_profile($user_id, $data) {
        $update_data = array('ID' => $user_id);

        if (isset($data['display_name'])) {
            $update_data['display_name'] = sanitize_text_field($data['display_name']);
        }

        if (isset($data['email'])) {
            $update_data['user_email'] = sanitize_email($data['email']);
        }

        $result = wp_update_user($update_data);

        if (is_wp_error($result)) {
            return $result;
        }

        // Update user meta
        if (isset($data['phone'])) {
            update_user_meta($user_id, 'shoppaton_phone', sanitize_text_field($data['phone']));
        }
        if (isset($data['address'])) {
            update_user_meta($user_id, 'shoppaton_address', sanitize_textarea_field($data['address']));
        }
        if (isset($data['city'])) {
            update_user_meta($user_id, 'shoppaton_city', sanitize_text_field($data['city']));
        }
        if (isset($data['state'])) {
            update_user_meta($user_id, 'shoppaton_state', sanitize_text_field($data['state']));
        }

        return true;
    }

    /**
     * Get user saved addresses
     *
     * @param int $user_id User ID
     * @return array
     */
    public function get_saved_addresses($user_id) {
        return array(
            'billing' => array(
                'name' => get_user_meta($user_id, 'shoppaton_billing_name', true) ?: get_userdata($user_id)->display_name,
                'phone' => get_user_meta($user_id, 'shoppaton_phone', true),
                'address' => get_user_meta($user_id, 'shoppaton_address', true),
                'city' => get_user_meta($user_id, 'shoppaton_city', true),
                'state' => get_user_meta($user_id, 'shoppaton_state', true),
            )
        );
    }

    /**
     * Check if current user is admin
     *
     * @return bool
     */
    public static function is_admin() {
        return current_user_can('manage_options');
    }
}
