<?php
/**
 * Products Class
 *
 * @package ShoppatonStore
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Shoppaton Products Class
 */
class Shoppaton_Products {

    /**
     * Single instance
     *
     * @var Shoppaton_Products
     */
    private static $instance = null;

    /**
     * Database table name
     *
     * @var string
     */
    private $table;

    /**
     * Get instance
     *
     * @return Shoppaton_Products
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
        $this->table = $wpdb->prefix . 'shoppaton_products';
    }

    /**
     * Get product by ID
     *
     * @param int $id Product ID
     * @return object|null
     */
    public function get($id) {
        global $wpdb;
        $product = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$this->table} WHERE id = %d AND status = 'publish'",
            $id
        ));

        if ($product) {
            $product->images = json_decode($product->images, true) ?: array();
        }

        return $product;
    }

    /**
     * Get product by slug
     *
     * @param string $slug Product slug
     * @return object|null
     */
    public function get_by_slug($slug) {
        global $wpdb;
        $product = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$this->table} WHERE slug = %s AND status = 'publish'",
            $slug
        ));

        if ($product) {
            $product->images = json_decode($product->images, true) ?: array();
        }

        return $product;
    }

    /**
     * Get products
     *
     * @param array $args Query arguments
     * @return array
     */
    public function get_products($args = array()) {
        global $wpdb;

        $defaults = array(
            'limit' => 12,
            'offset' => 0,
            'category' => null,
            'featured' => null,
            'best_seller' => null,
            'search' => null,
            'orderby' => 'created_at',
            'order' => 'DESC',
            'skin_type' => null,
            'target_user' => null,
            'price_min' => null,
            'price_max' => null,
            'in_stock' => true,
        );

        $args = wp_parse_args($args, $defaults);

        $where = array("status = 'publish'");
        $values = array();

        if ($args['category']) {
            $where[] = "category_id = %d";
            $values[] = $args['category'];
        }

        if ($args['featured'] !== null) {
            $where[] = "featured = %d";
            $values[] = $args['featured'] ? 1 : 0;
        }

        if ($args['best_seller'] !== null) {
            $where[] = "best_seller = %d";
            $values[] = $args['best_seller'] ? 1 : 0;
        }

        if ($args['search']) {
            $where[] = "(name LIKE %s OR description LIKE %s)";
            $search = '%' . $wpdb->esc_like($args['search']) . '%';
            $values[] = $search;
            $values[] = $search;
        }

        if ($args['skin_type']) {
            $where[] = "skin_type LIKE %s";
            $values[] = '%' . $wpdb->esc_like($args['skin_type']) . '%';
        }

        if ($args['target_user']) {
            $where[] = "target_user LIKE %s";
            $values[] = '%' . $wpdb->esc_like($args['target_user']) . '%';
        }

        if ($args['price_min'] !== null) {
            $where[] = "(COALESCE(sale_price, price) >= %f)";
            $values[] = floatval($args['price_min']);
        }

        if ($args['price_max'] !== null) {
            $where[] = "(COALESCE(sale_price, price) <= %f)";
            $values[] = floatval($args['price_max']);
        }

        if ($args['in_stock']) {
            $where[] = "stock_status = 'instock'";
        }

        $where_clause = implode(' AND ', $where);
        $orderby = sanitize_sql_orderby($args['orderby'] . ' ' . $args['order']);

        $sql = "SELECT * FROM {$this->table} WHERE {$where_clause} ORDER BY {$orderby} LIMIT %d OFFSET %d";
        $values[] = $args['limit'];
        $values[] = $args['offset'];

        $results = $wpdb->get_results($wpdb->prepare($sql, $values));

        foreach ($results as &$product) {
            $product->images = json_decode($product->images, true) ?: array();
            $product->current_price = $product->sale_price ?: $product->price;
        }

        return $results;
    }

    /**
     * Get total products count
     *
     * @param array $args Query arguments
     * @return int
     */
    public function get_count($args = array()) {
        global $wpdb;

        $defaults = array(
            'category' => null,
            'featured' => null,
            'best_seller' => null,
            'search' => null,
            'in_stock' => true,
        );

        $args = wp_parse_args($args, $defaults);

        $where = array("status = 'publish'");
        $values = array();

        if ($args['category']) {
            $where[] = "category_id = %d";
            $values[] = $args['category'];
        }

        if ($args['featured'] !== null) {
            $where[] = "featured = %d";
            $values[] = $args['featured'] ? 1 : 0;
        }

        if ($args['best_seller'] !== null) {
            $where[] = "best_seller = %d";
            $values[] = $args['best_seller'] ? 1 : 0;
        }

        if ($args['search']) {
            $where[] = "(name LIKE %s OR description LIKE %s)";
            $search = '%' . $wpdb->esc_like($args['search']) . '%';
            $values[] = $search;
            $values[] = $search;
        }

        if ($args['in_stock']) {
            $where[] = "stock_status = 'instock'";
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
     * Create product
     *
     * @param array $data Product data
     * @return int|false Product ID or false on failure
     */
    public function create($data) {
        global $wpdb;

        $defaults = array(
            'name' => '',
            'slug' => '',
            'description' => '',
            'short_description' => '',
            'price' => 0,
            'sale_price' => null,
            'sku' => null,
            'stock_quantity' => 0,
            'stock_status' => 'instock',
            'category_id' => null,
            'images' => array(),
            'skin_type' => null,
            'target_user' => null,
            'usage_guide' => null,
            'featured' => 0,
            'best_seller' => 0,
            'status' => 'publish',
        );

        $data = wp_parse_args($data, $defaults);

        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = sanitize_title($data['name']);
        }

        // Ensure unique slug
        $data['slug'] = $this->get_unique_slug($data['slug']);

        // Encode images array
        if (is_array($data['images'])) {
            $data['images'] = wp_json_encode($data['images']);
        }

        $inserted = $wpdb->insert($this->table, array(
            'name' => sanitize_text_field($data['name']),
            'slug' => sanitize_title($data['slug']),
            'description' => wp_kses_post($data['description']),
            'short_description' => sanitize_textarea_field($data['short_description']),
            'price' => floatval($data['price']),
            'sale_price' => $data['sale_price'] ? floatval($data['sale_price']) : null,
            'sku' => sanitize_text_field($data['sku']),
            'stock_quantity' => intval($data['stock_quantity']),
            'stock_status' => sanitize_text_field($data['stock_status']),
            'category_id' => $data['category_id'] ? intval($data['category_id']) : null,
            'images' => $data['images'],
            'skin_type' => sanitize_text_field($data['skin_type']),
            'target_user' => sanitize_text_field($data['target_user']),
            'usage_guide' => wp_kses_post($data['usage_guide']),
            'featured' => $data['featured'] ? 1 : 0,
            'best_seller' => $data['best_seller'] ? 1 : 0,
            'status' => sanitize_text_field($data['status']),
        ));

        return $inserted ? $wpdb->insert_id : false;
    }

    /**
     * Update product
     *
     * @param int $id Product ID
     * @param array $data Product data
     * @return bool
     */
    public function update($id, $data) {
        global $wpdb;

        // Encode images array if present
        if (isset($data['images']) && is_array($data['images'])) {
            $data['images'] = wp_json_encode($data['images']);
        }

        $update_data = array();
        $allowed_fields = array(
            'name', 'slug', 'description', 'short_description', 'price', 'sale_price',
            'sku', 'stock_quantity', 'stock_status', 'category_id', 'images',
            'skin_type', 'target_user', 'usage_guide', 'featured', 'best_seller', 'status'
        );

        foreach ($allowed_fields as $field) {
            if (isset($data[$field])) {
                $update_data[$field] = $data[$field];
            }
        }

        if (empty($update_data)) {
            return false;
        }

        return (bool) $wpdb->update($this->table, $update_data, array('id' => $id));
    }

    /**
     * Delete product
     *
     * @param int $id Product ID
     * @return bool
     */
    public function delete($id) {
        global $wpdb;
        return (bool) $wpdb->delete($this->table, array('id' => $id));
    }

    /**
     * Get unique slug
     *
     * @param string $slug Base slug
     * @param int $exclude_id Exclude this product ID
     * @return string
     */
    private function get_unique_slug($slug, $exclude_id = 0) {
        global $wpdb;

        $original_slug = $slug;
        $counter = 1;

        while (true) {
            $existing = $wpdb->get_var($wpdb->prepare(
                "SELECT id FROM {$this->table} WHERE slug = %s AND id != %d",
                $slug,
                $exclude_id
            ));

            if (!$existing) {
                break;
            }

            $slug = $original_slug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Get categories
     *
     * @return array
     */
    public function get_categories() {
        global $wpdb;
        $table = $wpdb->prefix . 'shoppaton_categories';
        return $wpdb->get_results("SELECT * FROM {$table} ORDER BY name ASC");
    }

    /**
     * Get category by ID
     *
     * @param int $id Category ID
     * @return object|null
     */
    public function get_category($id) {
        global $wpdb;
        $table = $wpdb->prefix . 'shoppaton_categories';
        return $wpdb->get_row($wpdb->prepare("SELECT * FROM {$table} WHERE id = %d", $id));
    }

    /**
     * Create category
     *
     * @param array $data Category data
     * @return int|false
     */
    public function create_category($data) {
        global $wpdb;
        $table = $wpdb->prefix . 'shoppaton_categories';

        $slug = sanitize_title($data['name']);
        
        $inserted = $wpdb->insert($table, array(
            'name' => sanitize_text_field($data['name']),
            'slug' => $slug,
            'description' => sanitize_textarea_field($data['description'] ?? ''),
            'image' => esc_url_raw($data['image'] ?? ''),
            'parent_id' => isset($data['parent_id']) ? intval($data['parent_id']) : null,
        ));

        return $inserted ? $wpdb->insert_id : false;
    }

    /**
     * Update category
     *
     * @param int $id Category ID
     * @param array $data Category data
     * @return bool
     */
    public function update_category($id, $data) {
        global $wpdb;
        $table = $wpdb->prefix . 'shoppaton_categories';

        $update_data = array();
        
        if (isset($data['name'])) {
            $update_data['name'] = sanitize_text_field($data['name']);
            $update_data['slug'] = sanitize_title($data['name']);
        }
        if (isset($data['description'])) {
            $update_data['description'] = sanitize_textarea_field($data['description']);
        }
        if (isset($data['image'])) {
            $update_data['image'] = esc_url_raw($data['image']);
        }
        if (isset($data['parent_id'])) {
            $update_data['parent_id'] = intval($data['parent_id']) ?: null;
        }

        return (bool) $wpdb->update($table, $update_data, array('id' => $id));
    }

    /**
     * Delete category
     *
     * @param int $id Category ID
     * @return bool
     */
    public function delete_category($id) {
        global $wpdb;
        $table = $wpdb->prefix . 'shoppaton_categories';
        return (bool) $wpdb->delete($table, array('id' => $id));
    }

    /**
     * Get product URL
     *
     * @param object|int $product Product object or ID
     * @return string
     */
    public function get_product_url($product) {
        if (is_numeric($product)) {
            $product = $this->get($product);
        }

        if (!$product) {
            return '';
        }

        $shop_page = get_page_by_path('shop');
        if ($shop_page) {
            return get_permalink($shop_page) . $product->slug . '/';
        }

        return home_url('/shop/' . $product->slug . '/');
    }

    /**
     * Get product image
     *
     * @param object $product Product object
     * @param string $size Image size
     * @return string
     */
    public function get_product_image($product, $size = 'medium') {
        if (!empty($product->images) && is_array($product->images) && isset($product->images[0])) {
            return esc_url($product->images[0]);
        }

        return SHOPPATON_ASSETS_URL . 'images/placeholder-product.png';
    }

    /**
     * Get formatted price
     *
     * @param object $product Product object
     * @return string
     */
    public function get_formatted_price($product) {
        $price = $product->sale_price ?: $product->price;
        return Shoppaton_Settings::format_price($price);
    }

    /**
     * Search products
     *
     * @param string $query Search query
     * @param int $limit Number of results
     * @return array
     */
    public function search($query, $limit = 10) {
        return $this->get_products(array(
            'search' => $query,
            'limit' => $limit,
        ));
    }
}
