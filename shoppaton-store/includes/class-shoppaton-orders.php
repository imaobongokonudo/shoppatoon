<?php
/**
 * Orders Class
 *
 * @package ShoppatonStore
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Shoppaton Orders Class
 */
class Shoppaton_Orders {

    /**
     * Single instance
     *
     * @var Shoppaton_Orders
     */
    private static $instance = null;

    /**
     * Orders table
     *
     * @var string
     */
    private $table;

    /**
     * Order items table
     *
     * @var string
     */
    private $items_table;

    /**
     * Get instance
     *
     * @return Shoppaton_Orders
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
        global $wpdb;
        $this->table = $wpdb->prefix . 'shoppaton_orders';
        $this->items_table = $wpdb->prefix . 'shoppaton_order_items';
    }

    /**
     * Create order
     *
     * @param array $data Order data
     * @param array $items Cart items
     * @return int|false Order ID or false on failure
     */
    public function create($data, $items) {
        global $wpdb;

        // Generate order number
        $order_number = $this->generate_order_number();

        $insert_data = array(
            'order_number' => $order_number,
            'user_id' => $data['user_id'] ?? null,
            'status' => $data['status'] ?? 'pending',
            'total' => floatval($data['total']),
            'subtotal' => floatval($data['subtotal']),
            'shipping_cost' => floatval($data['shipping_cost'] ?? 0),
            'discount' => floatval($data['discount'] ?? 0),
            'payment_method' => sanitize_text_field($data['payment_method'] ?? 'paystack'),
            'payment_status' => 'pending',
            'billing_name' => sanitize_text_field($data['billing_name']),
            'billing_email' => sanitize_email($data['billing_email']),
            'billing_phone' => sanitize_text_field($data['billing_phone']),
            'billing_address' => sanitize_textarea_field($data['billing_address']),
            'billing_city' => sanitize_text_field($data['billing_city']),
            'billing_state' => sanitize_text_field($data['billing_state']),
            'shipping_name' => sanitize_text_field($data['shipping_name'] ?? ''),
            'shipping_phone' => sanitize_text_field($data['shipping_phone'] ?? ''),
            'shipping_address' => sanitize_textarea_field($data['shipping_address'] ?? ''),
            'shipping_city' => sanitize_text_field($data['shipping_city'] ?? ''),
            'shipping_state' => sanitize_text_field($data['shipping_state'] ?? ''),
            'notes' => sanitize_textarea_field($data['notes'] ?? ''),
        );

        $inserted = $wpdb->insert($this->table, $insert_data);

        if (!$inserted) {
            return false;
        }

        $order_id = $wpdb->insert_id;

        // Insert order items
        foreach ($items as $item) {
            $wpdb->insert($this->items_table, array(
                'order_id' => $order_id,
                'product_id' => intval($item['product_id']),
                'product_name' => sanitize_text_field($item['name']),
                'quantity' => intval($item['quantity']),
                'price' => floatval($item['price']),
                'total' => floatval($item['price'] * $item['quantity']),
            ));
        }

        // Update product stock
        foreach ($items as $item) {
            $this->reduce_stock($item['product_id'], $item['quantity']);
        }

        return $order_id;
    }

    /**
     * Get order by ID
     *
     * @param int $id Order ID
     * @return object|null
     */
    public function get($id) {
        global $wpdb;
        
        $order = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$this->table} WHERE id = %d",
            $id
        ));

        if ($order) {
            $order->items = $this->get_order_items($id);
        }

        return $order;
    }

    /**
     * Get order by order number
     *
     * @param string $order_number Order number
     * @return object|null
     */
    public function get_by_order_number($order_number) {
        global $wpdb;
        
        $order = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$this->table} WHERE order_number = %s",
            $order_number
        ));

        if ($order) {
            $order->items = $this->get_order_items($order->id);
        }

        return $order;
    }

    /**
     * Get order by payment reference
     *
     * @param string $reference Payment reference
     * @return object|null
     */
    public function get_by_reference($reference) {
        global $wpdb;
        
        $order = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$this->table} WHERE payment_reference = %s",
            $reference
        ));

        if ($order) {
            $order->items = $this->get_order_items($order->id);
        }

        return $order;
    }

    /**
     * Get order items
     *
     * @param int $order_id Order ID
     * @return array
     */
    public function get_order_items($order_id) {
        global $wpdb;
        return $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$this->items_table} WHERE order_id = %d",
            $order_id
        ));
    }

    /**
     * Update order
     *
     * @param int $id Order ID
     * @param array $data Update data
     * @return bool
     */
    public function update($id, $data) {
        global $wpdb;

        $allowed_fields = array(
            'status', 'payment_status', 'payment_reference', 'tracking_number',
            'tracking_url', 'delivery_company', 'notes'
        );

        $update_data = array();
        foreach ($allowed_fields as $field) {
            if (isset($data[$field])) {
                $update_data[$field] = $data[$field];
            }
        }

        if (empty($update_data)) {
            return false;
        }

        $updated = $wpdb->update($this->table, $update_data, array('id' => $id));

        // If status changed, send notification
        if (isset($data['status'])) {
            $order = $this->get($id);
            $this->notify_status_change($order);
        }

        return $updated !== false;
    }

    /**
     * Get orders list
     *
     * @param array $args Query arguments
     * @return array
     */
    public function get_orders($args = array()) {
        global $wpdb;

        $defaults = array(
            'user_id' => null,
            'status' => null,
            'limit' => 20,
            'offset' => 0,
            'orderby' => 'created_at',
            'order' => 'DESC',
        );

        $args = wp_parse_args($args, $defaults);

        $where = array('1=1');
        $values = array();

        if ($args['user_id']) {
            $where[] = "user_id = %d";
            $values[] = $args['user_id'];
        }

        if ($args['status']) {
            $where[] = "status = %s";
            $values[] = $args['status'];
        }

        $where_clause = implode(' AND ', $where);
        $orderby = sanitize_sql_orderby($args['orderby'] . ' ' . $args['order']);

        $sql = "SELECT * FROM {$this->table} WHERE {$where_clause} ORDER BY {$orderby} LIMIT %d OFFSET %d";
        $values[] = $args['limit'];
        $values[] = $args['offset'];

        return $wpdb->get_results($wpdb->prepare($sql, $values));
    }

    /**
     * Get orders count
     *
     * @param array $args Query arguments
     * @return int
     */
    public function get_count($args = array()) {
        global $wpdb;

        $where = array('1=1');
        $values = array();

        if (!empty($args['user_id'])) {
            $where[] = "user_id = %d";
            $values[] = $args['user_id'];
        }

        if (!empty($args['status'])) {
            $where[] = "status = %s";
            $values[] = $args['status'];
        }

        $where_clause = implode(' AND ', $where);

        if (!empty($values)) {
            return (int) $wpdb->get_var($wpdb->prepare(
                "SELECT COUNT(*) FROM {$this->table} WHERE {$where_clause}",
                $values
            ));
        }

        return (int) $wpdb->get_var("SELECT COUNT(*) FROM {$this->table} WHERE {$where_clause}");
    }

    /**
     * Generate unique order number
     *
     * @return string
     */
    private function generate_order_number() {
        global $wpdb;

        do {
            $order_number = 'SHP' . date('Ymd') . strtoupper(substr(uniqid(), -5));
            $exists = $wpdb->get_var($wpdb->prepare(
                "SELECT id FROM {$this->table} WHERE order_number = %s",
                $order_number
            ));
        } while ($exists);

        return $order_number;
    }

    /**
     * Reduce product stock
     *
     * @param int $product_id Product ID
     * @param int $quantity Quantity to reduce
     */
    private function reduce_stock($product_id, $quantity) {
        global $wpdb;
        $products_table = $wpdb->prefix . 'shoppaton_products';

        $wpdb->query($wpdb->prepare(
            "UPDATE {$products_table} SET stock_quantity = GREATEST(0, stock_quantity - %d) WHERE id = %d",
            $quantity,
            $product_id
        ));

        // Check if out of stock
        $stock = $wpdb->get_var($wpdb->prepare(
            "SELECT stock_quantity FROM {$products_table} WHERE id = %d",
            $product_id
        ));

        if ($stock <= 0) {
            $wpdb->update(
                $products_table,
                array('stock_status' => 'outofstock'),
                array('id' => $product_id)
            );
        }
    }

    /**
     * Notify customer of status change
     *
     * @param object $order Order object
     */
    private function notify_status_change($order) {
        $status_messages = array(
            'processing' => 'Your order is being processed.',
            'shipped' => 'Your order has been shipped!',
            'delivered' => 'Your order has been delivered.',
            'cancelled' => 'Your order has been cancelled.',
        );

        if (!isset($status_messages[$order->status])) {
            return;
        }

        // Send email
        Shoppaton_Emails::instance()->send_order_status_update($order);

        // Send WhatsApp notification (optional)
        // $this->send_whatsapp_notification($order);
    }

    /**
     * Get order statuses
     *
     * @return array
     */
    public static function get_statuses() {
        return array(
            'pending' => 'Pending',
            'processing' => 'Processing',
            'shipped' => 'Shipped',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled',
            'refunded' => 'Refunded',
        );
    }

    /**
     * Get order tracking info
     *
     * @param int $order_id Order ID
     * @return array
     */
    public function get_tracking_info($order_id) {
        $order = $this->get($order_id);
        
        if (!$order) {
            return array();
        }

        $timeline = array(
            array(
                'status' => 'placed',
                'title' => 'Order Placed',
                'description' => 'Your order has been placed successfully.',
                'date' => $order->created_at,
                'completed' => true,
            ),
            array(
                'status' => 'processing',
                'title' => 'Processing',
                'description' => 'Your order is being prepared.',
                'date' => in_array($order->status, array('processing', 'shipped', 'delivered')) ? $order->updated_at : null,
                'completed' => in_array($order->status, array('processing', 'shipped', 'delivered')),
            ),
            array(
                'status' => 'shipped',
                'title' => 'Shipped',
                'description' => 'Your order is on its way.',
                'date' => in_array($order->status, array('shipped', 'delivered')) ? $order->updated_at : null,
                'completed' => in_array($order->status, array('shipped', 'delivered')),
            ),
            array(
                'status' => 'delivered',
                'title' => 'Delivered',
                'description' => 'Your order has been delivered.',
                'date' => $order->status === 'delivered' ? $order->updated_at : null,
                'completed' => $order->status === 'delivered',
            ),
        );

        return array(
            'order_number' => $order->order_number,
            'status' => $order->status,
            'tracking_number' => $order->tracking_number,
            'tracking_url' => $order->tracking_url,
            'delivery_company' => $order->delivery_company,
            'timeline' => $timeline,
        );
    }

    /**
     * Get orders for user
     *
     * @param int $user_id User ID
     * @return array
     */
    public function get_user_orders($user_id) {
        return $this->get_orders(array('user_id' => $user_id));
    }

    /**
     * Track order by order number and email/phone
     *
     * @param string $order_number Order number
     * @param string $identifier Email or phone
     * @return object|null
     */
    public function track_order($order_number, $identifier) {
        global $wpdb;

        $order = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$this->table} WHERE order_number = %s AND (billing_email = %s OR billing_phone = %s)",
            $order_number,
            $identifier,
            $identifier
        ));

        if ($order) {
            $order->items = $this->get_order_items($order->id);
            $order->tracking = $this->get_tracking_info($order->id);
        }

        return $order;
    }
}
