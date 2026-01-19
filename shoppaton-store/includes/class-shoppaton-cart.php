<?php
/**
 * Cart Class
 *
 * @package ShoppatonStore
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Shoppaton Cart Class
 */
class Shoppaton_Cart {

    /**
     * Single instance
     *
     * @var Shoppaton_Cart
     */
    private static $instance = null;

    /**
     * Cart items
     *
     * @var array
     */
    private $items = array();

    /**
     * Session ID
     *
     * @var string
     */
    private $session_id;

    /**
     * Get instance
     *
     * @return Shoppaton_Cart
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
        $this->session_id = Shoppaton_Store::get_session_id();
        $this->load_cart();
    }

    /**
     * Load cart from database
     */
    private function load_cart() {
        global $wpdb;
        $table = $wpdb->prefix . 'shoppaton_cart';
        $products_table = $wpdb->prefix . 'shoppaton_products';

        $user_id = get_current_user_id();

        $where = $user_id ? "c.user_id = %d" : "c.session_id = %s";
        $value = $user_id ?: $this->session_id;

        $results = $wpdb->get_results($wpdb->prepare(
            "SELECT c.*, p.name, p.price, p.sale_price, p.images, p.stock_quantity, p.stock_status
             FROM {$table} c
             LEFT JOIN {$products_table} p ON c.product_id = p.id
             WHERE {$where}",
            $value
        ));

        $this->items = array();
        foreach ($results as $item) {
            $images = json_decode($item->images, true) ?: array();
            $this->items[] = array(
                'id' => $item->id,
                'product_id' => $item->product_id,
                'name' => $item->name,
                'price' => floatval($item->sale_price ?: $item->price),
                'regular_price' => floatval($item->price),
                'quantity' => intval($item->quantity),
                'image' => !empty($images) ? $images[0] : '',
                'stock_quantity' => intval($item->stock_quantity),
                'stock_status' => $item->stock_status,
            );
        }
    }

    /**
     * Add item to cart
     *
     * @param int $product_id Product ID
     * @param int $quantity Quantity
     * @return bool
     */
    public function add_item($product_id, $quantity = 1) {
        global $wpdb;
        $table = $wpdb->prefix . 'shoppaton_cart';

        $product = Shoppaton_Products::instance()->get($product_id);
        if (!$product) {
            return false;
        }

        // Check stock
        if ($product->stock_status !== 'instock') {
            return false;
        }

        $user_id = get_current_user_id();

        // Check if item already exists
        $existing = $this->get_item_by_product($product_id);
        
        if ($existing) {
            $new_quantity = $existing['quantity'] + $quantity;
            
            // Check stock limit
            if ($product->stock_quantity > 0 && $new_quantity > $product->stock_quantity) {
                $new_quantity = $product->stock_quantity;
            }

            $wpdb->update(
                $table,
                array('quantity' => $new_quantity, 'updated_at' => current_time('mysql')),
                array('id' => $existing['id'])
            );
        } else {
            // Check stock limit
            if ($product->stock_quantity > 0 && $quantity > $product->stock_quantity) {
                $quantity = $product->stock_quantity;
            }

            $wpdb->insert($table, array(
                'session_id' => $this->session_id,
                'user_id' => $user_id ?: null,
                'product_id' => $product_id,
                'quantity' => $quantity,
            ));
        }

        $this->load_cart();
        $this->save_abandoned_cart();

        return true;
    }

    /**
     * Update item quantity
     *
     * @param int $product_id Product ID
     * @param int $quantity New quantity
     * @return bool
     */
    public function update_item($product_id, $quantity) {
        global $wpdb;
        $table = $wpdb->prefix . 'shoppaton_cart';

        if ($quantity < 1) {
            return $this->remove_item($product_id);
        }

        $product = Shoppaton_Products::instance()->get($product_id);
        if (!$product) {
            return false;
        }

        // Check stock limit
        if ($product->stock_quantity > 0 && $quantity > $product->stock_quantity) {
            $quantity = $product->stock_quantity;
        }

        $existing = $this->get_item_by_product($product_id);
        if (!$existing) {
            return false;
        }

        $wpdb->update(
            $table,
            array('quantity' => $quantity, 'updated_at' => current_time('mysql')),
            array('id' => $existing['id'])
        );

        $this->load_cart();
        $this->save_abandoned_cart();

        return true;
    }

    /**
     * Remove item from cart
     *
     * @param int $product_id Product ID
     * @return bool
     */
    public function remove_item($product_id) {
        global $wpdb;
        $table = $wpdb->prefix . 'shoppaton_cart';

        $existing = $this->get_item_by_product($product_id);
        if (!$existing) {
            return false;
        }

        $wpdb->delete($table, array('id' => $existing['id']));
        $this->load_cart();
        $this->save_abandoned_cart();

        return true;
    }

    /**
     * Clear cart
     *
     * @return bool
     */
    public function clear() {
        global $wpdb;
        $table = $wpdb->prefix . 'shoppaton_cart';

        $user_id = get_current_user_id();
        if ($user_id) {
            $wpdb->delete($table, array('user_id' => $user_id));
        } else {
            $wpdb->delete($table, array('session_id' => $this->session_id));
        }

        $this->items = array();
        return true;
    }

    /**
     * Get cart items
     *
     * @return array
     */
    public function get_items() {
        return $this->items;
    }

    /**
     * Get cart item by product ID
     *
     * @param int $product_id Product ID
     * @return array|null
     */
    private function get_item_by_product($product_id) {
        foreach ($this->items as $item) {
            if ($item['product_id'] == $product_id) {
                return $item;
            }
        }
        return null;
    }

    /**
     * Get cart count
     *
     * @return int
     */
    public function get_count() {
        return array_sum(array_column($this->items, 'quantity'));
    }

    /**
     * Get subtotal
     *
     * @return float
     */
    public function get_subtotal() {
        $subtotal = 0;
        foreach ($this->items as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        return $subtotal;
    }

    /**
     * Get shipping cost
     *
     * @param string $state Shipping state
     * @return float
     */
    public function get_shipping_cost($state = 'lagos') {
        if (empty($this->items)) {
            return 0;
        }
        return Shoppaton_Settings::instance()->get_shipping_rate($state);
    }

    /**
     * Get total
     *
     * @param string $state Shipping state
     * @return float
     */
    public function get_total($state = 'lagos') {
        return $this->get_subtotal() + $this->get_shipping_cost($state);
    }

    /**
     * Sync cart from offline storage
     *
     * @param array $offline_cart Cart items from client
     * @return array Updated cart
     */
    public function sync_from_offline($offline_cart) {
        if (!is_array($offline_cart)) {
            return $this->get_items();
        }

        foreach ($offline_cart as $item) {
            if (isset($item['pending']) && $item['pending']) {
                $this->add_item($item['product_id'], $item['quantity']);
            }
        }

        return $this->get_items();
    }

    /**
     * Save cart as abandoned cart
     */
    private function save_abandoned_cart() {
        global $wpdb;
        $table = $wpdb->prefix . 'shoppaton_abandoned_carts';

        if (empty($this->items)) {
            // Delete abandoned cart record
            $wpdb->delete($table, array('session_id' => $this->session_id));
            return;
        }

        $user_id = get_current_user_id();
        $cart_data = wp_json_encode($this->items);
        $cart_total = $this->get_subtotal();

        // Check if record exists
        $existing = $wpdb->get_var($wpdb->prepare(
            "SELECT id FROM {$table} WHERE session_id = %s AND recovered = 0",
            $this->session_id
        ));

        if ($existing) {
            $wpdb->update(
                $table,
                array(
                    'cart_data' => $cart_data,
                    'cart_total' => $cart_total,
                    'updated_at' => current_time('mysql'),
                ),
                array('id' => $existing)
            );
        } else {
            $wpdb->insert($table, array(
                'session_id' => $this->session_id,
                'user_id' => $user_id ?: null,
                'cart_data' => $cart_data,
                'cart_total' => $cart_total,
            ));
        }
    }

    /**
     * Merge guest cart to user cart
     *
     * @param int $user_id User ID
     */
    public function merge_to_user($user_id) {
        global $wpdb;
        $table = $wpdb->prefix . 'shoppaton_cart';

        // Update session cart to user
        $wpdb->update(
            $table,
            array('user_id' => $user_id),
            array('session_id' => $this->session_id)
        );

        $this->load_cart();
    }

    /**
     * Get cart data for JavaScript
     *
     * @return array
     */
    public function get_cart_data() {
        return array(
            'items' => $this->items,
            'count' => $this->get_count(),
            'subtotal' => $this->get_subtotal(),
            'subtotal_formatted' => Shoppaton_Settings::format_price($this->get_subtotal()),
        );
    }
}
