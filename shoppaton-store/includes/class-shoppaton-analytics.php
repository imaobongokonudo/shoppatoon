<?php
/**
 * Analytics Class
 *
 * @package ShoppatonStore
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Shoppaton Analytics Class
 */
class Shoppaton_Analytics {

    /**
     * Single instance
     *
     * @var Shoppaton_Analytics
     */
    private static $instance = null;

    /**
     * Table name
     *
     * @var string
     */
    private $table;

    /**
     * Get instance
     *
     * @return Shoppaton_Analytics
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
        $this->table = $wpdb->prefix . 'shoppaton_analytics';
    }

    /**
     * Track event
     *
     * @param string $event_type Event type
     * @param array $event_data Event data
     */
    public function track($event_type, $event_data = array()) {
        global $wpdb;

        $wpdb->insert($this->table, array(
            'event_type' => sanitize_text_field($event_type),
            'event_data' => wp_json_encode($event_data),
            'user_id' => get_current_user_id() ?: null,
            'session_id' => Shoppaton_Store::get_session_id(),
            'page_url' => esc_url_raw(isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''),
            'referrer' => esc_url_raw(isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''),
            'user_agent' => sanitize_text_field(isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : ''),
            'ip_address' => $this->get_client_ip(),
        ));
    }

    /**
     * Get client IP
     *
     * @return string
     */
    private function get_client_ip() {
        $ip = '';
        
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = sanitize_text_field($_SERVER['HTTP_CLIENT_IP']);
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = sanitize_text_field(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0]);
        } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
            $ip = sanitize_text_field($_SERVER['REMOTE_ADDR']);
        }
        
        return $ip;
    }

    /**
     * Get dashboard analytics
     *
     * @param string $period Period (today, week, month, year)
     * @return array
     */
    public function get_dashboard_analytics($period = 'month') {
        global $wpdb;
        $orders_table = $wpdb->prefix . 'shoppaton_orders';

        $date_where = $this->get_date_where($period);

        // Total revenue
        $total_revenue = $wpdb->get_var(
            "SELECT SUM(total) FROM {$orders_table} WHERE payment_status = 'paid' {$date_where}"
        ) ?: 0;

        // Total orders
        $total_orders = $wpdb->get_var(
            "SELECT COUNT(*) FROM {$orders_table} WHERE 1=1 {$date_where}"
        ) ?: 0;

        // Paid orders
        $paid_orders = $wpdb->get_var(
            "SELECT COUNT(*) FROM {$orders_table} WHERE payment_status = 'paid' {$date_where}"
        ) ?: 0;

        // Average order value
        $avg_order_value = $total_orders > 0 ? $total_revenue / $paid_orders : 0;

        // Page views
        $page_views = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$this->table} WHERE event_type = 'page_view' {$date_where}",
            array()
        )) ?: 0;

        // Add to cart events
        $add_to_cart = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$this->table} WHERE event_type = 'add_to_cart' {$date_where}",
            array()
        )) ?: 0;

        // Conversion rate
        $conversion_rate = $page_views > 0 ? ($paid_orders / $page_views) * 100 : 0;

        return array(
            'total_revenue' => floatval($total_revenue),
            'total_revenue_formatted' => Shoppaton_Settings::format_price($total_revenue),
            'total_orders' => intval($total_orders),
            'paid_orders' => intval($paid_orders),
            'avg_order_value' => floatval($avg_order_value),
            'avg_order_value_formatted' => Shoppaton_Settings::format_price($avg_order_value),
            'page_views' => intval($page_views),
            'add_to_cart' => intval($add_to_cart),
            'conversion_rate' => round($conversion_rate, 2),
        );
    }

    /**
     * Get revenue chart data
     *
     * @param string $period Period
     * @return array
     */
    public function get_revenue_chart($period = 'month') {
        global $wpdb;
        $orders_table = $wpdb->prefix . 'shoppaton_orders';

        $group_by = $period === 'year' ? 'MONTH(created_at)' : 'DATE(created_at)';
        $date_format = $period === 'year' ? '%b' : '%d %b';

        $date_where = $this->get_date_where($period);

        $results = $wpdb->get_results(
            "SELECT DATE_FORMAT(created_at, '{$date_format}') as label, 
                    SUM(total) as revenue, 
                    COUNT(*) as orders
             FROM {$orders_table} 
             WHERE payment_status = 'paid' {$date_where}
             GROUP BY {$group_by}
             ORDER BY created_at ASC"
        );

        $labels = array();
        $revenue = array();
        $orders = array();

        foreach ($results as $row) {
            $labels[] = $row->label;
            $revenue[] = floatval($row->revenue);
            $orders[] = intval($row->orders);
        }

        return array(
            'labels' => $labels,
            'revenue' => $revenue,
            'orders' => $orders,
        );
    }

    /**
     * Get top products
     *
     * @param int $limit Number of products
     * @param string $period Period
     * @return array
     */
    public function get_top_products($limit = 10, $period = 'month') {
        global $wpdb;
        $order_items_table = $wpdb->prefix . 'shoppaton_order_items';
        $orders_table = $wpdb->prefix . 'shoppaton_orders';

        $date_where = $this->get_date_where($period, 'o.');

        $results = $wpdb->get_results($wpdb->prepare(
            "SELECT oi.product_id, oi.product_name, 
                    SUM(oi.quantity) as total_sold,
                    SUM(oi.total) as total_revenue
             FROM {$order_items_table} oi
             JOIN {$orders_table} o ON oi.order_id = o.id
             WHERE o.payment_status = 'paid' {$date_where}
             GROUP BY oi.product_id
             ORDER BY total_sold DESC
             LIMIT %d",
            $limit
        ));

        return $results;
    }

    /**
     * Get customer behavior data
     *
     * @param string $period Period
     * @return array
     */
    public function get_customer_behavior($period = 'month') {
        global $wpdb;

        $date_where = $this->get_date_where($period);

        // Most viewed products
        $viewed_products = $wpdb->get_results(
            "SELECT JSON_UNQUOTE(JSON_EXTRACT(event_data, '$.product_id')) as product_id,
                    COUNT(*) as views
             FROM {$this->table}
             WHERE event_type = 'product_view' {$date_where}
             GROUP BY product_id
             ORDER BY views DESC
             LIMIT 10"
        );

        // Search queries
        $searches = $wpdb->get_results(
            "SELECT JSON_UNQUOTE(JSON_EXTRACT(event_data, '$.query')) as query,
                    COUNT(*) as count
             FROM {$this->table}
             WHERE event_type = 'search' {$date_where}
             GROUP BY query
             ORDER BY count DESC
             LIMIT 10"
        );

        // Traffic sources
        $sources = $wpdb->get_results(
            "SELECT 
                CASE 
                    WHEN referrer LIKE '%google%' THEN 'Google'
                    WHEN referrer LIKE '%facebook%' THEN 'Facebook'
                    WHEN referrer LIKE '%instagram%' THEN 'Instagram'
                    WHEN referrer LIKE '%twitter%' THEN 'Twitter'
                    WHEN referrer = '' THEN 'Direct'
                    ELSE 'Other'
                END as source,
                COUNT(*) as visits
             FROM {$this->table}
             WHERE event_type = 'page_view' {$date_where}
             GROUP BY source
             ORDER BY visits DESC"
        );

        return array(
            'viewed_products' => $viewed_products,
            'searches' => $searches,
            'traffic_sources' => $sources,
        );
    }

    /**
     * Get date where clause
     *
     * @param string $period Period
     * @param string $table_alias Table alias
     * @return string
     */
    private function get_date_where($period, $table_alias = '') {
        $column = $table_alias ? $table_alias . 'created_at' : 'created_at';
        
        switch ($period) {
            case 'today':
                return " AND DATE({$column}) = CURDATE()";
            case 'week':
                return " AND {$column} >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
            case 'month':
                return " AND {$column} >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
            case 'year':
                return " AND {$column} >= DATE_SUB(NOW(), INTERVAL 1 YEAR)";
            default:
                return '';
        }
    }

    /**
     * Get orders by state
     *
     * @param string $period Period
     * @return array
     */
    public function get_orders_by_state($period = 'month') {
        global $wpdb;
        $orders_table = $wpdb->prefix . 'shoppaton_orders';

        $date_where = $this->get_date_where($period);

        return $wpdb->get_results(
            "SELECT billing_state as state, 
                    COUNT(*) as orders,
                    SUM(total) as revenue
             FROM {$orders_table}
             WHERE payment_status = 'paid' {$date_where}
             GROUP BY billing_state
             ORDER BY orders DESC"
        );
    }

    /**
     * Get abandoned cart stats
     *
     * @param string $period Period
     * @return array
     */
    public function get_abandoned_cart_stats($period = 'month') {
        global $wpdb;
        $abandoned_table = $wpdb->prefix . 'shoppaton_abandoned_carts';

        $date_where = str_replace('created_at', 'a.created_at', $this->get_date_where($period));

        $total = $wpdb->get_var(
            "SELECT COUNT(*) FROM {$abandoned_table} a WHERE 1=1 {$date_where}"
        ) ?: 0;

        $recovered = $wpdb->get_var(
            "SELECT COUNT(*) FROM {$abandoned_table} a WHERE recovered = 1 {$date_where}"
        ) ?: 0;

        $total_value = $wpdb->get_var(
            "SELECT SUM(cart_total) FROM {$abandoned_table} a WHERE 1=1 {$date_where}"
        ) ?: 0;

        $recovered_value = $wpdb->get_var(
            "SELECT SUM(cart_total) FROM {$abandoned_table} a WHERE recovered = 1 {$date_where}"
        ) ?: 0;

        return array(
            'total_abandoned' => intval($total),
            'recovered' => intval($recovered),
            'recovery_rate' => $total > 0 ? round(($recovered / $total) * 100, 2) : 0,
            'total_value' => Shoppaton_Settings::format_price($total_value),
            'recovered_value' => Shoppaton_Settings::format_price($recovered_value),
        );
    }
}
