<?php
/**
 * Admin Class
 *
 * @package ShoppatonStore
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Shoppaton Admin Class
 */
class Shoppaton_Admin {

    /**
     * Single instance
     *
     * @var Shoppaton_Admin
     */
    private static $instance = null;

    /**
     * Get instance
     *
     * @return Shoppaton_Admin
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
        add_action('admin_menu', array($this, 'add_admin_menu'));
        
        // Backend admin AJAX handlers (use admin nonce)
        add_action('wp_ajax_shoppaton_admin_save_product', array($this, 'save_product_backend'));
        add_action('wp_ajax_shoppaton_admin_delete_product', array($this, 'delete_product_backend'));
        add_action('wp_ajax_shoppaton_admin_save_settings', array($this, 'save_settings_backend'));
        add_action('wp_ajax_shoppaton_admin_save_slider', array($this, 'save_slider'));
        add_action('wp_ajax_shoppaton_admin_delete_slider', array($this, 'delete_slider'));
        add_action('wp_ajax_shoppaton_admin_update_order', array($this, 'update_order'));
        add_action('wp_ajax_shoppaton_admin_save_category', array($this, 'save_category'));
        add_action('wp_ajax_shoppaton_admin_delete_category', array($this, 'delete_category'));
        
        // Frontend admin dashboard AJAX handlers (use frontend nonce)
        add_action('wp_ajax_shoppaton_admin_get_stats', array($this, 'get_stats_frontend'));
        add_action('wp_ajax_shoppaton_admin_get_products', array($this, 'get_products_frontend'));
        add_action('wp_ajax_shoppaton_admin_get_orders', array($this, 'get_orders_frontend'));
        add_action('wp_ajax_shoppaton_admin_get_customers', array($this, 'get_customers_frontend'));
        add_action('wp_ajax_shoppaton_admin_get_analytics', array($this, 'get_analytics_frontend'));
        add_action('wp_ajax_shoppaton_admin_save_product', array($this, 'save_product_frontend'));
        add_action('wp_ajax_shoppaton_admin_save_settings', array($this, 'save_settings_frontend'));
        add_action('wp_ajax_shoppaton_admin_get_product', array($this, 'get_product_frontend'));
        add_action('wp_ajax_shoppaton_admin_update_order_status', array($this, 'update_order_status_frontend'));
        add_action('wp_ajax_shoppaton_admin_update_tracking', array($this, 'update_tracking_frontend'));
    }

    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_menu_page(
            'Shoppaton Store',
            'Shoppaton',
            'manage_options',
            'shoppaton',
            array($this, 'dashboard_page'),
            'dashicons-store',
            30
        );

        add_submenu_page(
            'shoppaton',
            'Dashboard',
            'Dashboard',
            'manage_options',
            'shoppaton',
            array($this, 'dashboard_page')
        );

        add_submenu_page(
            'shoppaton',
            'Products',
            'Products',
            'manage_options',
            'shoppaton-products',
            array($this, 'products_page')
        );

        add_submenu_page(
            'shoppaton',
            'Categories',
            'Categories',
            'manage_options',
            'shoppaton-categories',
            array($this, 'categories_page')
        );

        add_submenu_page(
            'shoppaton',
            'Orders',
            'Orders',
            'manage_options',
            'shoppaton-orders',
            array($this, 'orders_page')
        );

        add_submenu_page(
            'shoppaton',
            'Sliders',
            'Sliders',
            'manage_options',
            'shoppaton-sliders',
            array($this, 'sliders_page')
        );

        add_submenu_page(
            'shoppaton',
            'Analytics',
            'Analytics',
            'manage_options',
            'shoppaton-analytics',
            array($this, 'analytics_page')
        );

        add_submenu_page(
            'shoppaton',
            'Settings',
            'Settings',
            'manage_options',
            'shoppaton-settings',
            array($this, 'settings_page')
        );
    }

    /**
     * Dashboard page
     */
    public function dashboard_page() {
        $analytics = Shoppaton_Analytics::instance()->get_dashboard_analytics('month');
        $recent_orders = Shoppaton_Orders::instance()->get_orders(array('limit' => 10));
        $abandoned_stats = Shoppaton_Analytics::instance()->get_abandoned_cart_stats('month');
        ?>
        <div class="wrap shoppaton-admin-wrap">
            <h1 class="shoppaton-admin-title">
                <img src="<?php echo esc_url(SHOPPATON_ASSETS_URL . 'images/logo.png'); ?>" alt="Shoppaton" style="height: 50px; vertical-align: middle;">
                Dashboard
            </h1>

            <div class="shoppaton-admin-stats">
                <div class="shoppaton-stat-card">
                    <div class="shoppaton-stat-icon revenue">₦</div>
                    <div class="shoppaton-stat-info">
                        <div class="shoppaton-stat-value"><?php echo esc_html($analytics['total_revenue_formatted']); ?></div>
                        <div class="shoppaton-stat-label">Total Revenue (30 days)</div>
                    </div>
                </div>
                <div class="shoppaton-stat-card">
                    <div class="shoppaton-stat-icon orders">📦</div>
                    <div class="shoppaton-stat-info">
                        <div class="shoppaton-stat-value"><?php echo esc_html($analytics['total_orders']); ?></div>
                        <div class="shoppaton-stat-label">Total Orders</div>
                    </div>
                </div>
                <div class="shoppaton-stat-card">
                    <div class="shoppaton-stat-icon avg">📊</div>
                    <div class="shoppaton-stat-info">
                        <div class="shoppaton-stat-value"><?php echo esc_html($analytics['avg_order_value_formatted']); ?></div>
                        <div class="shoppaton-stat-label">Avg Order Value</div>
                    </div>
                </div>
                <div class="shoppaton-stat-card">
                    <div class="shoppaton-stat-icon conversion">📈</div>
                    <div class="shoppaton-stat-info">
                        <div class="shoppaton-stat-value"><?php echo esc_html($analytics['conversion_rate']); ?>%</div>
                        <div class="shoppaton-stat-label">Conversion Rate</div>
                    </div>
                </div>
            </div>

            <div class="shoppaton-admin-grid">
                <div class="shoppaton-admin-card">
                    <h2>Recent Orders</h2>
                    <table class="widefat striped">
                        <thead>
                            <tr>
                                <th>Order</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_orders as $order) : ?>
                            <tr>
                                <td><strong>#<?php echo esc_html($order->order_number); ?></strong></td>
                                <td><?php echo esc_html($order->billing_name); ?></td>
                                <td><?php echo esc_html(Shoppaton_Settings::format_price($order->total)); ?></td>
                                <td><span class="shoppaton-status shoppaton-status-<?php echo esc_attr($order->status); ?>"><?php echo esc_html(ucfirst($order->status)); ?></span></td>
                                <td><?php echo esc_html(date('M j, Y', strtotime($order->created_at))); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="shoppaton-admin-card">
                    <h2>Abandoned Cart Recovery</h2>
                    <div class="shoppaton-abandoned-stats">
                        <div class="shoppaton-abandoned-stat">
                            <span class="label">Total Abandoned</span>
                            <span class="value"><?php echo esc_html($abandoned_stats['total_abandoned']); ?></span>
                        </div>
                        <div class="shoppaton-abandoned-stat">
                            <span class="label">Recovered</span>
                            <span class="value"><?php echo esc_html($abandoned_stats['recovered']); ?></span>
                        </div>
                        <div class="shoppaton-abandoned-stat">
                            <span class="label">Recovery Rate</span>
                            <span class="value"><?php echo esc_html($abandoned_stats['recovery_rate']); ?>%</span>
                        </div>
                        <div class="shoppaton-abandoned-stat">
                            <span class="label">Recovered Value</span>
                            <span class="value"><?php echo esc_html($abandoned_stats['recovered_value']); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Products page
     */
    public function products_page() {
        $action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : 'list';
        $product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;

        ?>
        <div class="wrap shoppaton-admin-wrap">
            <h1 class="shoppaton-admin-title">Products</h1>

            <?php if ($action === 'edit' || $action === 'add') : ?>
                <?php $this->render_product_form($product_id); ?>
            <?php else : ?>
                <a href="<?php echo esc_url(admin_url('admin.php?page=shoppaton-products&action=add')); ?>" class="button button-primary">Add New Product</a>
                
                <?php $this->render_products_list(); ?>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Render product form
     *
     * @param int $product_id Product ID
     */
    private function render_product_form($product_id = 0) {
        $product = $product_id ? Shoppaton_Products::instance()->get($product_id) : null;
        $categories = Shoppaton_Products::instance()->get_categories();
        ?>
        <form id="shoppaton-product-form" class="shoppaton-admin-form">
            <input type="hidden" name="product_id" value="<?php echo esc_attr($product_id); ?>">
            
            <div class="shoppaton-form-grid">
                <div class="shoppaton-form-main">
                    <div class="shoppaton-admin-card">
                        <h2>Product Information</h2>
                        
                        <div class="shoppaton-form-row">
                            <label>Product Name *</label>
                            <input type="text" name="name" value="<?php echo esc_attr($product ? $product->name : ''); ?>" required>
                        </div>
                        
                        <div class="shoppaton-form-row">
                            <label>Short Description</label>
                            <textarea name="short_description" rows="3"><?php echo esc_textarea($product ? $product->short_description : ''); ?></textarea>
                        </div>
                        
                        <div class="shoppaton-form-row">
                            <label>Full Description</label>
                            <?php
                            wp_editor(
                                $product ? $product->description : '',
                                'product_description',
                                array('textarea_name' => 'description', 'textarea_rows' => 10)
                            );
                            ?>
                        </div>
                    </div>

                    <div class="shoppaton-admin-card">
                        <h2>Product Details</h2>
                        
                        <div class="shoppaton-form-row">
                            <label>Skin Type (e.g., Dry, Oily, Combination, All)</label>
                            <input type="text" name="skin_type" value="<?php echo esc_attr($product ? $product->skin_type : ''); ?>">
                        </div>
                        
                        <div class="shoppaton-form-row">
                            <label>Target User (e.g., Women, Men, All)</label>
                            <input type="text" name="target_user" value="<?php echo esc_attr($product ? $product->target_user : ''); ?>">
                        </div>
                        
                        <div class="shoppaton-form-row">
                            <label>Usage Guide</label>
                            <textarea name="usage_guide" rows="4"><?php echo esc_textarea($product ? $product->usage_guide : ''); ?></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="shoppaton-form-sidebar">
                    <div class="shoppaton-admin-card">
                        <h2>Pricing</h2>
                        
                        <div class="shoppaton-form-row">
                            <label>Regular Price (₦) *</label>
                            <input type="number" name="price" step="0.01" value="<?php echo esc_attr($product ? $product->price : ''); ?>" required>
                        </div>
                        
                        <div class="shoppaton-form-row">
                            <label>Sale Price (₦)</label>
                            <input type="number" name="sale_price" step="0.01" value="<?php echo esc_attr($product ? $product->sale_price : ''); ?>">
                        </div>
                    </div>
                    
                    <div class="shoppaton-admin-card">
                        <h2>Inventory</h2>
                        
                        <div class="shoppaton-form-row">
                            <label>SKU</label>
                            <input type="text" name="sku" value="<?php echo esc_attr($product ? $product->sku : ''); ?>">
                        </div>
                        
                        <div class="shoppaton-form-row">
                            <label>Stock Quantity</label>
                            <input type="number" name="stock_quantity" value="<?php echo esc_attr($product ? $product->stock_quantity : 0); ?>">
                        </div>
                        
                        <div class="shoppaton-form-row">
                            <label>Stock Status</label>
                            <select name="stock_status">
                                <option value="instock" <?php selected($product ? $product->stock_status : 'instock', 'instock'); ?>>In Stock</option>
                                <option value="outofstock" <?php selected($product ? $product->stock_status : '', 'outofstock'); ?>>Out of Stock</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="shoppaton-admin-card">
                        <h2>Category</h2>
                        
                        <div class="shoppaton-form-row">
                            <select name="category_id">
                                <option value="">Select Category</option>
                                <?php foreach ($categories as $category) : ?>
                                <option value="<?php echo esc_attr($category->id); ?>" <?php selected($product ? $product->category_id : '', $category->id); ?>><?php echo esc_html($category->name); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="shoppaton-admin-card">
                        <h2>Product Images</h2>
                        
                        <div class="shoppaton-form-row">
                            <div id="product-images-container">
                                <?php
                                $images = $product && is_array($product->images) ? $product->images : array();
                                foreach ($images as $image) :
                                ?>
                                <div class="shoppaton-image-preview">
                                    <img src="<?php echo esc_url($image); ?>">
                                    <input type="hidden" name="images[]" value="<?php echo esc_url($image); ?>">
                                    <button type="button" class="remove-image">&times;</button>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <button type="button" id="add-product-image" class="button">Add Image</button>
                        </div>
                    </div>
                    
                    <div class="shoppaton-admin-card">
                        <h2>Visibility</h2>
                        
                        <div class="shoppaton-form-row">
                            <label>
                                <input type="checkbox" name="featured" value="1" <?php checked($product ? $product->featured : 0, 1); ?>>
                                Featured Product
                            </label>
                        </div>
                        
                        <div class="shoppaton-form-row">
                            <label>
                                <input type="checkbox" name="best_seller" value="1" <?php checked($product ? $product->best_seller : 0, 1); ?>>
                                Best Seller
                            </label>
                        </div>
                        
                        <div class="shoppaton-form-row">
                            <label>Status</label>
                            <select name="status">
                                <option value="publish" <?php selected($product ? $product->status : 'publish', 'publish'); ?>>Published</option>
                                <option value="draft" <?php selected($product ? $product->status : '', 'draft'); ?>>Draft</option>
                            </select>
                        </div>
                    </div>
                    
                    <button type="submit" class="button button-primary button-large" style="width: 100%;">
                        <?php echo $product_id ? 'Update Product' : 'Create Product'; ?>
                    </button>
                </div>
            </div>
        </form>
        <?php
    }

    /**
     * Render products list
     */
    private function render_products_list() {
        $products = Shoppaton_Products::instance()->get_products(array('limit' => 100));
        ?>
        <table class="widefat striped" style="margin-top: 20px;">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Category</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product) : 
                    $category = $product->category_id ? Shoppaton_Products::instance()->get_category($product->category_id) : null;
                ?>
                <tr>
                    <td>
                        <?php if (!empty($product->images)) : ?>
                        <img src="<?php echo esc_url($product->images[0]); ?>" style="width: 50px; height: 50px; object-fit: cover;">
                        <?php else : ?>
                        <span style="color: #999;">No image</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <strong><?php echo esc_html($product->name); ?></strong>
                        <?php if ($product->featured) : ?><br><span class="shoppaton-badge">Featured</span><?php endif; ?>
                        <?php if ($product->best_seller) : ?><span class="shoppaton-badge">Best Seller</span><?php endif; ?>
                    </td>
                    <td>
                        <?php if ($product->sale_price) : ?>
                        <del><?php echo esc_html(Shoppaton_Settings::format_price($product->price)); ?></del><br>
                        <strong><?php echo esc_html(Shoppaton_Settings::format_price($product->sale_price)); ?></strong>
                        <?php else : ?>
                        <?php echo esc_html(Shoppaton_Settings::format_price($product->price)); ?>
                        <?php endif; ?>
                    </td>
                    <td><?php echo $category ? esc_html($category->name) : '-'; ?></td>
                    <td>
                        <span class="shoppaton-stock-<?php echo esc_attr($product->stock_status); ?>">
                            <?php echo $product->stock_status === 'instock' ? 'In Stock (' . $product->stock_quantity . ')' : 'Out of Stock'; ?>
                        </span>
                    </td>
                    <td><span class="shoppaton-status shoppaton-status-<?php echo esc_attr($product->status); ?>"><?php echo esc_html(ucfirst($product->status)); ?></span></td>
                    <td>
                        <a href="<?php echo esc_url(admin_url('admin.php?page=shoppaton-products&action=edit&product_id=' . $product->id)); ?>" class="button button-small">Edit</a>
                        <button type="button" class="button button-small button-link-delete shoppaton-delete-product" data-id="<?php echo esc_attr($product->id); ?>">Delete</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php
    }

    /**
     * Categories page
     */
    public function categories_page() {
        $categories = Shoppaton_Products::instance()->get_categories();
        ?>
        <div class="wrap shoppaton-admin-wrap">
            <h1 class="shoppaton-admin-title">Categories</h1>

            <div class="shoppaton-admin-grid">
                <div class="shoppaton-admin-card">
                    <h2>Add New Category</h2>
                    <form id="shoppaton-category-form" class="shoppaton-admin-form">
                        <input type="hidden" name="category_id" value="0">
                        
                        <div class="shoppaton-form-row">
                            <label>Name *</label>
                            <input type="text" name="name" required>
                        </div>
                        
                        <div class="shoppaton-form-row">
                            <label>Description</label>
                            <textarea name="description" rows="3"></textarea>
                        </div>
                        
                        <div class="shoppaton-form-row">
                            <label>Image URL</label>
                            <input type="url" name="image">
                        </div>
                        
                        <button type="submit" class="button button-primary">Add Category</button>
                    </form>
                </div>

                <div class="shoppaton-admin-card">
                    <h2>Categories</h2>
                    <table class="widefat striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Slug</th>
                                <th>Products</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categories as $category) : 
                                $product_count = Shoppaton_Products::instance()->get_count(array('category' => $category->id));
                            ?>
                            <tr>
                                <td><strong><?php echo esc_html($category->name); ?></strong></td>
                                <td><?php echo esc_html($category->slug); ?></td>
                                <td><?php echo esc_html($product_count); ?></td>
                                <td>
                                    <button type="button" class="button button-small button-link-delete shoppaton-delete-category" data-id="<?php echo esc_attr($category->id); ?>">Delete</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Orders page
     */
    public function orders_page() {
        $orders = Shoppaton_Orders::instance()->get_orders(array('limit' => 50));
        $statuses = Shoppaton_Orders::get_statuses();
        ?>
        <div class="wrap shoppaton-admin-wrap">
            <h1 class="shoppaton-admin-title">Orders</h1>

            <table class="widefat striped">
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Customer</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order) : 
                        $items = Shoppaton_Orders::instance()->get_order_items($order->id);
                    ?>
                    <tr>
                        <td>
                            <strong>#<?php echo esc_html($order->order_number); ?></strong>
                        </td>
                        <td>
                            <?php echo esc_html($order->billing_name); ?><br>
                            <small><?php echo esc_html($order->billing_email); ?></small><br>
                            <small><?php echo esc_html($order->billing_phone); ?></small>
                        </td>
                        <td>
                            <?php foreach ($items as $item) : ?>
                            <?php echo esc_html($item->product_name); ?> × <?php echo esc_html($item->quantity); ?><br>
                            <?php endforeach; ?>
                        </td>
                        <td><?php echo esc_html(Shoppaton_Settings::format_price($order->total)); ?></td>
                        <td>
                            <span class="shoppaton-status shoppaton-status-<?php echo esc_attr($order->payment_status); ?>">
                                <?php echo esc_html(ucfirst($order->payment_status)); ?>
                            </span>
                        </td>
                        <td>
                            <select class="shoppaton-order-status" data-order-id="<?php echo esc_attr($order->id); ?>">
                                <?php foreach ($statuses as $value => $label) : ?>
                                <option value="<?php echo esc_attr($value); ?>" <?php selected($order->status, $value); ?>><?php echo esc_html($label); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td><?php echo esc_html(date('M j, Y H:i', strtotime($order->created_at))); ?></td>
                        <td>
                            <a href="#" class="button button-small shoppaton-view-order" data-order='<?php echo esc_attr(wp_json_encode($order)); ?>'>View</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
    }

    /**
     * Sliders page
     */
    public function sliders_page() {
        global $wpdb;
        $sliders = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}shoppaton_sliders ORDER BY sort_order ASC");
        ?>
        <div class="wrap shoppaton-admin-wrap">
            <h1 class="shoppaton-admin-title">Hero Sliders</h1>

            <div class="shoppaton-admin-grid">
                <div class="shoppaton-admin-card">
                    <h2>Add New Slide</h2>
                    <form id="shoppaton-slider-form" class="shoppaton-admin-form">
                        <input type="hidden" name="slider_id" value="0">
                        
                        <div class="shoppaton-form-row">
                            <label>Title</label>
                            <input type="text" name="title">
                        </div>
                        
                        <div class="shoppaton-form-row">
                            <label>Subtitle</label>
                            <textarea name="subtitle" rows="2"></textarea>
                        </div>
                        
                        <div class="shoppaton-form-row">
                            <label>Image URL *</label>
                            <input type="url" name="image" required>
                            <button type="button" id="upload-slider-image" class="button">Upload Image</button>
                        </div>
                        
                        <div class="shoppaton-form-row">
                            <label>Button Text</label>
                            <input type="text" name="button_text" value="Shop Now">
                        </div>
                        
                        <div class="shoppaton-form-row">
                            <label>Button URL</label>
                            <input type="url" name="button_url" value="<?php echo esc_url(home_url('/shop/')); ?>">
                        </div>
                        
                        <div class="shoppaton-form-row">
                            <label>Sort Order</label>
                            <input type="number" name="sort_order" value="0">
                        </div>
                        
                        <button type="submit" class="button button-primary">Add Slide</button>
                    </form>
                </div>

                <div class="shoppaton-admin-card">
                    <h2>Slider Images</h2>
                    <div class="shoppaton-sliders-grid">
                        <?php foreach ($sliders as $slider) : ?>
                        <div class="shoppaton-slider-item">
                            <img src="<?php echo esc_url($slider->image); ?>" alt="">
                            <div class="shoppaton-slider-info">
                                <strong><?php echo esc_html($slider->title ?: 'No Title'); ?></strong>
                                <button type="button" class="button button-small button-link-delete shoppaton-delete-slider" data-id="<?php echo esc_attr($slider->id); ?>">Delete</button>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Analytics page
     */
    public function analytics_page() {
        $period = isset($_GET['period']) ? sanitize_text_field($_GET['period']) : 'month';
        $analytics = Shoppaton_Analytics::instance()->get_dashboard_analytics($period);
        $top_products = Shoppaton_Analytics::instance()->get_top_products(10, $period);
        $behavior = Shoppaton_Analytics::instance()->get_customer_behavior($period);
        $orders_by_state = Shoppaton_Analytics::instance()->get_orders_by_state($period);
        ?>
        <div class="wrap shoppaton-admin-wrap">
            <h1 class="shoppaton-admin-title">Analytics</h1>

            <div class="shoppaton-period-selector">
                <a href="<?php echo esc_url(admin_url('admin.php?page=shoppaton-analytics&period=today')); ?>" class="button <?php echo $period === 'today' ? 'button-primary' : ''; ?>">Today</a>
                <a href="<?php echo esc_url(admin_url('admin.php?page=shoppaton-analytics&period=week')); ?>" class="button <?php echo $period === 'week' ? 'button-primary' : ''; ?>">Last 7 Days</a>
                <a href="<?php echo esc_url(admin_url('admin.php?page=shoppaton-analytics&period=month')); ?>" class="button <?php echo $period === 'month' ? 'button-primary' : ''; ?>">Last 30 Days</a>
                <a href="<?php echo esc_url(admin_url('admin.php?page=shoppaton-analytics&period=year')); ?>" class="button <?php echo $period === 'year' ? 'button-primary' : ''; ?>">This Year</a>
            </div>

            <div class="shoppaton-admin-stats">
                <div class="shoppaton-stat-card">
                    <div class="shoppaton-stat-info">
                        <div class="shoppaton-stat-value"><?php echo esc_html($analytics['total_revenue_formatted']); ?></div>
                        <div class="shoppaton-stat-label">Revenue</div>
                    </div>
                </div>
                <div class="shoppaton-stat-card">
                    <div class="shoppaton-stat-info">
                        <div class="shoppaton-stat-value"><?php echo esc_html($analytics['paid_orders']); ?></div>
                        <div class="shoppaton-stat-label">Paid Orders</div>
                    </div>
                </div>
                <div class="shoppaton-stat-card">
                    <div class="shoppaton-stat-info">
                        <div class="shoppaton-stat-value"><?php echo esc_html($analytics['page_views']); ?></div>
                        <div class="shoppaton-stat-label">Page Views</div>
                    </div>
                </div>
                <div class="shoppaton-stat-card">
                    <div class="shoppaton-stat-info">
                        <div class="shoppaton-stat-value"><?php echo esc_html($analytics['add_to_cart']); ?></div>
                        <div class="shoppaton-stat-label">Add to Cart</div>
                    </div>
                </div>
            </div>

            <div class="shoppaton-admin-grid">
                <div class="shoppaton-admin-card">
                    <h2>Top Products</h2>
                    <table class="widefat striped">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Units Sold</th>
                                <th>Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($top_products as $product) : ?>
                            <tr>
                                <td><?php echo esc_html($product->product_name); ?></td>
                                <td><?php echo esc_html($product->total_sold); ?></td>
                                <td><?php echo esc_html(Shoppaton_Settings::format_price($product->total_revenue)); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="shoppaton-admin-card">
                    <h2>Orders by State</h2>
                    <table class="widefat striped">
                        <thead>
                            <tr>
                                <th>State</th>
                                <th>Orders</th>
                                <th>Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders_by_state as $state) : ?>
                            <tr>
                                <td><?php echo esc_html(ucfirst($state->state)); ?></td>
                                <td><?php echo esc_html($state->orders); ?></td>
                                <td><?php echo esc_html(Shoppaton_Settings::format_price($state->revenue)); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="shoppaton-admin-card">
                    <h2>Traffic Sources</h2>
                    <table class="widefat striped">
                        <thead>
                            <tr>
                                <th>Source</th>
                                <th>Visits</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($behavior['traffic_sources'] as $source) : ?>
                            <tr>
                                <td><?php echo esc_html($source->source); ?></td>
                                <td><?php echo esc_html($source->visits); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="shoppaton-admin-card">
                    <h2>Popular Searches</h2>
                    <table class="widefat striped">
                        <thead>
                            <tr>
                                <th>Search Query</th>
                                <th>Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($behavior['searches'] as $search) : ?>
                            <tr>
                                <td><?php echo esc_html($search->query); ?></td>
                                <td><?php echo esc_html($search->count); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Settings page
     */
    public function settings_page() {
        $settings = Shoppaton_Settings::instance();
        ?>
        <div class="wrap shoppaton-admin-wrap">
            <h1 class="shoppaton-admin-title">Settings</h1>

            <form id="shoppaton-settings-form" class="shoppaton-admin-form">
                <div class="shoppaton-admin-grid">
                    <div class="shoppaton-admin-card">
                        <h2>Paystack Settings</h2>
                        
                        <div class="shoppaton-form-row">
                            <label>Mode</label>
                            <select name="paystack_mode">
                                <option value="test" <?php selected($settings->get('paystack_mode'), 'test'); ?>>Test</option>
                                <option value="live" <?php selected($settings->get('paystack_mode'), 'live'); ?>>Live</option>
                            </select>
                        </div>
                        
                        <div class="shoppaton-form-row">
                            <label>Test Public Key</label>
                            <input type="text" name="paystack_test_public" value="<?php echo esc_attr($settings->get('paystack_test_public')); ?>">
                        </div>
                        
                        <div class="shoppaton-form-row">
                            <label>Test Secret Key</label>
                            <input type="password" name="paystack_test_secret" value="<?php echo esc_attr($settings->get('paystack_test_secret')); ?>">
                        </div>
                        
                        <div class="shoppaton-form-row">
                            <label>Live Public Key</label>
                            <input type="text" name="paystack_live_public" value="<?php echo esc_attr($settings->get('paystack_live_public')); ?>">
                        </div>
                        
                        <div class="shoppaton-form-row">
                            <label>Live Secret Key</label>
                            <input type="password" name="paystack_live_secret" value="<?php echo esc_attr($settings->get('paystack_live_secret')); ?>">
                        </div>
                    </div>

                    <div class="shoppaton-admin-card">
                        <h2>Contact Information</h2>
                        
                        <div class="shoppaton-form-row">
                            <label>WhatsApp Number</label>
                            <input type="text" name="whatsapp_number" value="<?php echo esc_attr($settings->get('whatsapp_number')); ?>">
                        </div>
                        
                        <div class="shoppaton-form-row">
                            <label>Contact Email</label>
                            <input type="email" name="contact_email" value="<?php echo esc_attr($settings->get('contact_email')); ?>">
                        </div>
                        
                        <div class="shoppaton-form-row">
                            <label>Instagram URL</label>
                            <input type="url" name="instagram" value="<?php echo esc_attr($settings->get('instagram')); ?>">
                        </div>
                        
                        <div class="shoppaton-form-row">
                            <label>Facebook URL</label>
                            <input type="url" name="facebook" value="<?php echo esc_attr($settings->get('facebook')); ?>">
                        </div>
                        
                        <div class="shoppaton-form-row">
                            <label>TikTok URL</label>
                            <input type="url" name="tiktok" value="<?php echo esc_attr($settings->get('tiktok')); ?>">
                        </div>
                    </div>

                    <div class="shoppaton-admin-card">
                        <h2>Abandoned Cart Emails</h2>
                        
                        <div class="shoppaton-form-row">
                            <label>
                                <input type="checkbox" name="abandoned_cart_enabled" value="1" <?php checked($settings->get('abandoned_cart_enabled'), true); ?>>
                                Enable Abandoned Cart Emails
                            </label>
                        </div>
                        
                        <div class="shoppaton-form-row">
                            <label>Days Between Emails</label>
                            <input type="number" name="abandoned_cart_interval" value="<?php echo esc_attr($settings->get('abandoned_cart_interval')); ?>" min="1" max="7">
                        </div>
                    </div>
                </div>

                <button type="submit" class="button button-primary button-large">Save Settings</button>
            </form>
        </div>
        <?php
    }

    /**
     * Save product
     */
    public function save_product() {
        check_ajax_referer('shoppaton_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Unauthorized'));
        }

        $product_id = intval($_POST['product_id'] ?? 0);
        
        $data = array(
            'name' => sanitize_text_field($_POST['name'] ?? ''),
            'description' => wp_kses_post($_POST['description'] ?? ''),
            'short_description' => sanitize_textarea_field($_POST['short_description'] ?? ''),
            'price' => floatval($_POST['price'] ?? 0),
            'sale_price' => $_POST['sale_price'] ? floatval($_POST['sale_price']) : null,
            'sku' => sanitize_text_field($_POST['sku'] ?? ''),
            'stock_quantity' => intval($_POST['stock_quantity'] ?? 0),
            'stock_status' => sanitize_text_field($_POST['stock_status'] ?? 'instock'),
            'category_id' => intval($_POST['category_id'] ?? 0) ?: null,
            'images' => array_map('esc_url_raw', $_POST['images'] ?? array()),
            'skin_type' => sanitize_text_field($_POST['skin_type'] ?? ''),
            'target_user' => sanitize_text_field($_POST['target_user'] ?? ''),
            'usage_guide' => wp_kses_post($_POST['usage_guide'] ?? ''),
            'featured' => isset($_POST['featured']) ? 1 : 0,
            'best_seller' => isset($_POST['best_seller']) ? 1 : 0,
            'status' => sanitize_text_field($_POST['status'] ?? 'publish'),
        );

        if ($product_id) {
            $result = Shoppaton_Products::instance()->update($product_id, $data);
        } else {
            $result = Shoppaton_Products::instance()->create($data);
        }

        if ($result) {
            wp_send_json_success(array('message' => 'Product saved successfully'));
        } else {
            wp_send_json_error(array('message' => 'Failed to save product'));
        }
    }

    /**
     * Delete product
     */
    public function delete_product() {
        check_ajax_referer('shoppaton_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Unauthorized'));
        }

        $product_id = intval($_POST['product_id'] ?? 0);

        if (Shoppaton_Products::instance()->delete($product_id)) {
            wp_send_json_success(array('message' => 'Product deleted'));
        } else {
            wp_send_json_error(array('message' => 'Failed to delete product'));
        }
    }

    /**
     * Save settings
     */
    public function save_settings() {
        check_ajax_referer('shoppaton_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Unauthorized'));
        }

        $settings = Shoppaton_Settings::instance();
        $fields = array(
            'paystack_mode', 'paystack_test_public', 'paystack_test_secret',
            'paystack_live_public', 'paystack_live_secret', 'whatsapp_number',
            'contact_email', 'instagram', 'facebook', 'tiktok',
            'abandoned_cart_enabled', 'abandoned_cart_interval'
        );

        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                $value = sanitize_text_field($_POST[$field]);
                if ($field === 'abandoned_cart_enabled') {
                    $value = (bool) $value;
                }
                $settings->update($field, $value);
            }
        }

        wp_send_json_success(array('message' => 'Settings saved'));
    }

    /**
     * Save slider
     */
    public function save_slider() {
        check_ajax_referer('shoppaton_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Unauthorized'));
        }

        global $wpdb;
        $table = $wpdb->prefix . 'shoppaton_sliders';

        $data = array(
            'title' => sanitize_text_field($_POST['title'] ?? ''),
            'subtitle' => sanitize_textarea_field($_POST['subtitle'] ?? ''),
            'image' => esc_url_raw($_POST['image'] ?? ''),
            'button_text' => sanitize_text_field($_POST['button_text'] ?? ''),
            'button_url' => esc_url_raw($_POST['button_url'] ?? ''),
            'sort_order' => intval($_POST['sort_order'] ?? 0),
        );

        $wpdb->insert($table, $data);

        wp_send_json_success(array('message' => 'Slide added'));
    }

    /**
     * Delete slider
     */
    public function delete_slider() {
        check_ajax_referer('shoppaton_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Unauthorized'));
        }

        global $wpdb;
        $table = $wpdb->prefix . 'shoppaton_sliders';
        $slider_id = intval($_POST['slider_id'] ?? 0);

        $wpdb->delete($table, array('id' => $slider_id));

        wp_send_json_success(array('message' => 'Slide deleted'));
    }

    /**
     * Update order
     */
    public function update_order() {
        check_ajax_referer('shoppaton_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Unauthorized'));
        }

        $order_id = intval($_POST['order_id'] ?? 0);
        $status = sanitize_text_field($_POST['status'] ?? '');

        if (Shoppaton_Orders::instance()->update($order_id, array('status' => $status))) {
            wp_send_json_success(array('message' => 'Order updated'));
        } else {
            wp_send_json_error(array('message' => 'Failed to update order'));
        }
    }

    /**
     * Save category
     */
    public function save_category() {
        check_ajax_referer('shoppaton_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Unauthorized'));
        }

        $data = array(
            'name' => sanitize_text_field($_POST['name'] ?? ''),
            'description' => sanitize_textarea_field($_POST['description'] ?? ''),
            'image' => esc_url_raw($_POST['image'] ?? ''),
        );

        if (Shoppaton_Products::instance()->create_category($data)) {
            wp_send_json_success(array('message' => 'Category created'));
        } else {
            wp_send_json_error(array('message' => 'Failed to create category'));
        }
    }

    /**
     * Delete category
     */
    public function delete_category() {
        check_ajax_referer('shoppaton_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Unauthorized'));
        }

        $category_id = intval($_POST['category_id'] ?? 0);

        if (Shoppaton_Products::instance()->delete_category($category_id)) {
            wp_send_json_success(array('message' => 'Category deleted'));
        } else {
            wp_send_json_error(array('message' => 'Failed to delete category'));
        }
    }

    // ================================
    // Frontend Admin Dashboard Methods
    // ================================

    /**
     * Get stats for frontend dashboard
     */
    public function get_stats_frontend() {
        check_ajax_referer('shoppaton_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Unauthorized'));
        }

        global $wpdb;
        $products_table = $wpdb->prefix . 'shoppaton_products';
        $orders_table = $wpdb->prefix . 'shoppaton_orders';

        // Today's sales
        $today = date('Y-m-d');
        $today_sales = $wpdb->get_var($wpdb->prepare(
            "SELECT COALESCE(SUM(total), 0) FROM {$orders_table} WHERE DATE(created_at) = %s AND payment_status = 'paid'",
            $today
        ));

        // Pending orders
        $pending_orders = $wpdb->get_var(
            "SELECT COUNT(*) FROM {$orders_table} WHERE status = 'pending'"
        );

        // Total products
        $total_products = $wpdb->get_var(
            "SELECT COUNT(*) FROM {$products_table} WHERE status = 'publish'"
        );

        // Total customers (unique emails in orders)
        $total_customers = $wpdb->get_var(
            "SELECT COUNT(DISTINCT billing_email) FROM {$orders_table}"
        );

        // Sales data for chart (last 7 days)
        $sales_data = array(
            'labels' => array(),
            'values' => array()
        );
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-{$i} days"));
            $sales_data['labels'][] = date('M j', strtotime($date));
            $sales = $wpdb->get_var($wpdb->prepare(
                "SELECT COALESCE(SUM(total), 0) FROM {$orders_table} WHERE DATE(created_at) = %s AND payment_status = 'paid'",
                $date
            ));
            $sales_data['values'][] = floatval($sales);
        }

        // Top products
        $top_products = $wpdb->get_results(
            "SELECT p.id, p.name, p.images, COUNT(oi.id) as sales
             FROM {$wpdb->prefix}shoppaton_order_items oi
             JOIN {$products_table} p ON oi.product_id = p.id
             GROUP BY p.id
             ORDER BY sales DESC
             LIMIT 5"
        );

        $top_products_formatted = array();
        foreach ($top_products as $product) {
            $images = maybe_unserialize($product->images);
            $top_products_formatted[] = array(
                'id' => $product->id,
                'name' => $product->name,
                'image' => !empty($images) ? $images[0] : SHOPPATON_ASSETS_URL . 'images/placeholder-product.png',
                'sales' => $product->sales
            );
        }

        // Recent orders
        $recent_orders = $wpdb->get_results(
            "SELECT id, order_number, billing_name as customer_name, total, status, created_at
             FROM {$orders_table}
             ORDER BY created_at DESC
             LIMIT 5"
        );

        $recent_orders_formatted = array();
        foreach ($recent_orders as $order) {
            $recent_orders_formatted[] = array(
                'id' => $order->id,
                'order_number' => $order->order_number,
                'customer_name' => $order->customer_name,
                'total' => floatval($order->total),
                'status' => $order->status
            );
        }

        wp_send_json_success(array(
            'today_sales' => floatval($today_sales),
            'pending_orders' => intval($pending_orders),
            'total_products' => intval($total_products),
            'total_customers' => intval($total_customers),
            'sales_data' => $sales_data,
            'top_products' => $top_products_formatted,
            'recent_orders' => $recent_orders_formatted
        ));
    }

    /**
     * Get products for frontend dashboard
     */
    public function get_products_frontend() {
        check_ajax_referer('shoppaton_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Unauthorized'));
        }

        global $wpdb;
        $table = $wpdb->prefix . 'shoppaton_products';
        $page = intval($_POST['page'] ?? 1);
        $per_page = 10;
        $offset = ($page - 1) * $per_page;

        $search = sanitize_text_field($_POST['search'] ?? '');
        $category = sanitize_text_field($_POST['category'] ?? '');
        $status = sanitize_text_field($_POST['status'] ?? '');

        $where = "WHERE 1=1";
        if ($search) {
            $where .= $wpdb->prepare(" AND name LIKE %s", '%' . $wpdb->esc_like($search) . '%');
        }
        if ($category) {
            $where .= $wpdb->prepare(" AND category_id = %d", $category);
        }
        if ($status) {
            $where .= $wpdb->prepare(" AND status = %s", $status === 'active' ? 'publish' : 'draft');
        }

        $total = $wpdb->get_var("SELECT COUNT(*) FROM {$table} {$where}");
        $products = $wpdb->get_results(
            "SELECT * FROM {$table} {$where} ORDER BY created_at DESC LIMIT {$per_page} OFFSET {$offset}"
        );

        $products_formatted = array();
        foreach ($products as $product) {
            $images = maybe_unserialize($product->images);
            $products_formatted[] = array(
                'id' => $product->id,
                'name' => $product->name,
                'image' => !empty($images) ? $images[0] : SHOPPATON_ASSETS_URL . 'images/placeholder-product.png',
                'category' => $product->category_id ? 'Category' : '-',
                'price' => floatval($product->sale_price ?: $product->price),
                'stock' => $product->stock_quantity,
                'status' => $product->status === 'publish' ? 'active' : 'inactive'
            );
        }

        wp_send_json_success(array(
            'products' => $products_formatted,
            'pages' => ceil($total / $per_page),
            'total' => $total
        ));
    }

    /**
     * Get orders for frontend dashboard
     */
    public function get_orders_frontend() {
        check_ajax_referer('shoppaton_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Unauthorized'));
        }

        global $wpdb;
        $table = $wpdb->prefix . 'shoppaton_orders';
        $page = intval($_POST['page'] ?? 1);
        $per_page = 10;
        $offset = ($page - 1) * $per_page;

        $search = sanitize_text_field($_POST['search'] ?? '');
        $status = sanitize_text_field($_POST['status'] ?? '');
        $date = sanitize_text_field($_POST['date'] ?? '');

        $where = "WHERE 1=1";
        if ($search) {
            $where .= $wpdb->prepare(" AND (order_number LIKE %s OR billing_name LIKE %s)", 
                '%' . $wpdb->esc_like($search) . '%',
                '%' . $wpdb->esc_like($search) . '%'
            );
        }
        if ($status) {
            $where .= $wpdb->prepare(" AND status = %s", $status);
        }
        if ($date) {
            $where .= $wpdb->prepare(" AND DATE(created_at) = %s", $date);
        }

        $total = $wpdb->get_var("SELECT COUNT(*) FROM {$table} {$where}");
        $orders = $wpdb->get_results(
            "SELECT * FROM {$table} {$where} ORDER BY created_at DESC LIMIT {$per_page} OFFSET {$offset}"
        );

        $orders_formatted = array();
        foreach ($orders as $order) {
            $items_count = $wpdb->get_var($wpdb->prepare(
                "SELECT COUNT(*) FROM {$wpdb->prefix}shoppaton_order_items WHERE order_id = %d",
                $order->id
            ));
            $orders_formatted[] = array(
                'id' => $order->id,
                'order_number' => $order->order_number,
                'customer_name' => $order->billing_name,
                'date' => date('M j, Y', strtotime($order->created_at)),
                'items_count' => $items_count,
                'total' => floatval($order->total),
                'status' => $order->status
            );
        }

        wp_send_json_success(array(
            'orders' => $orders_formatted,
            'pages' => ceil($total / $per_page),
            'total' => $total
        ));
    }

    /**
     * Get customers for frontend dashboard
     */
    public function get_customers_frontend() {
        check_ajax_referer('shoppaton_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Unauthorized'));
        }

        global $wpdb;
        $table = $wpdb->prefix . 'shoppaton_orders';
        $page = intval($_POST['page'] ?? 1);
        $per_page = 10;
        $offset = ($page - 1) * $per_page;

        $search = sanitize_text_field($_POST['search'] ?? '');

        $where = "";
        if ($search) {
            $where = $wpdb->prepare(" HAVING name LIKE %s OR email LIKE %s", 
                '%' . $wpdb->esc_like($search) . '%',
                '%' . $wpdb->esc_like($search) . '%'
            );
        }

        $customers = $wpdb->get_results(
            "SELECT billing_name as name, billing_email as email, billing_phone as phone,
                    COUNT(*) as orders_count, SUM(total) as total_spent, MIN(created_at) as joined
             FROM {$table}
             GROUP BY billing_email
             {$where}
             ORDER BY total_spent DESC
             LIMIT {$per_page} OFFSET {$offset}"
        );

        $total = $wpdb->get_var(
            "SELECT COUNT(DISTINCT billing_email) FROM {$table}"
        );

        $customers_formatted = array();
        foreach ($customers as $customer) {
            $customers_formatted[] = array(
                'name' => $customer->name,
                'email' => $customer->email,
                'phone' => $customer->phone,
                'orders_count' => $customer->orders_count,
                'total_spent' => floatval($customer->total_spent),
                'joined' => date('M j, Y', strtotime($customer->joined))
            );
        }

        wp_send_json_success(array(
            'customers' => $customers_formatted,
            'pages' => ceil($total / $per_page),
            'total' => $total
        ));
    }

    /**
     * Get analytics for frontend dashboard
     */
    public function get_analytics_frontend() {
        check_ajax_referer('shoppaton_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Unauthorized'));
        }

        global $wpdb;
        $orders_table = $wpdb->prefix . 'shoppaton_orders';
        $analytics_table = $wpdb->prefix . 'shoppaton_analytics';

        $start_date = sanitize_text_field($_POST['start_date'] ?? date('Y-m-d', strtotime('-30 days')));
        $end_date = sanitize_text_field($_POST['end_date'] ?? date('Y-m-d'));

        // Revenue
        $revenue = $wpdb->get_var($wpdb->prepare(
            "SELECT COALESCE(SUM(total), 0) FROM {$orders_table} 
             WHERE created_at BETWEEN %s AND %s AND payment_status = 'paid'",
            $start_date, $end_date . ' 23:59:59'
        ));

        // Orders count
        $orders = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$orders_table} 
             WHERE created_at BETWEEN %s AND %s",
            $start_date, $end_date . ' 23:59:59'
        ));

        // AOV
        $aov = $orders > 0 ? $revenue / $orders : 0;

        // Conversion (page views to orders)
        $page_views = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$analytics_table} 
             WHERE event_type = 'page_view' AND created_at BETWEEN %s AND %s",
            $start_date, $end_date . ' 23:59:59'
        )) ?: 1;
        $conversion = round(($orders / $page_views) * 100, 2);

        // Revenue data for chart
        $revenue_data = array('labels' => array(), 'values' => array());
        $current = strtotime($start_date);
        $end = strtotime($end_date);
        while ($current <= $end) {
            $date = date('Y-m-d', $current);
            $revenue_data['labels'][] = date('M j', $current);
            $daily_revenue = $wpdb->get_var($wpdb->prepare(
                "SELECT COALESCE(SUM(total), 0) FROM {$orders_table} 
                 WHERE DATE(created_at) = %s AND payment_status = 'paid'",
                $date
            ));
            $revenue_data['values'][] = floatval($daily_revenue);
            $current = strtotime('+1 day', $current);
        }

        // Orders by status
        $statuses = $wpdb->get_results($wpdb->prepare(
            "SELECT status, COUNT(*) as count FROM {$orders_table} 
             WHERE created_at BETWEEN %s AND %s
             GROUP BY status",
            $start_date, $end_date . ' 23:59:59'
        ));
        $orders_by_status = array('labels' => array(), 'values' => array());
        foreach ($statuses as $s) {
            $orders_by_status['labels'][] = ucfirst($s->status);
            $orders_by_status['values'][] = intval($s->count);
        }

        // Behavior metrics
        $cart_adds = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$analytics_table} 
             WHERE event_type = 'add_to_cart' AND created_at BETWEEN %s AND %s",
            $start_date, $end_date . ' 23:59:59'
        )) ?: 0;
        $abandonment_rate = $cart_adds > 0 ? round((1 - ($orders / $cart_adds)) * 100, 1) : 0;

        wp_send_json_success(array(
            'revenue' => floatval($revenue),
            'orders' => intval($orders),
            'aov' => floatval($aov),
            'conversion' => $conversion,
            'abandonment_rate' => $abandonment_rate,
            'returning_customers' => 0,
            'avg_duration' => '0m',
            'avg_pageviews' => 0,
            'revenue_data' => $revenue_data,
            'orders_by_status' => $orders_by_status
        ));
    }

    /**
     * Save product from frontend dashboard
     */
    public function save_product_frontend() {
        check_ajax_referer('shoppaton_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Unauthorized'));
        }

        // Parse the serialized product data
        parse_str($_POST['product'] ?? '', $product_data);

        $product_id = intval($product_data['product_id'] ?? 0);
        
        $data = array(
            'name' => sanitize_text_field($product_data['name'] ?? ''),
            'description' => wp_kses_post($product_data['description'] ?? ''),
            'price' => floatval($product_data['price'] ?? 0),
            'sale_price' => !empty($product_data['compare_price']) ? floatval($product_data['compare_price']) : null,
            'stock_quantity' => intval($product_data['stock'] ?? 0),
            'stock_status' => intval($product_data['stock'] ?? 0) > 0 ? 'instock' : 'outofstock',
            'category_id' => intval($product_data['category'] ?? 0) ?: null,
            'skin_type' => sanitize_text_field($product_data['skin_type'] ?? ''),
            'target_user' => sanitize_text_field($product_data['target_user'] ?? ''),
            'status' => ($product_data['status'] ?? 'active') === 'active' ? 'publish' : 'draft',
        );

        // Validate required fields
        if (empty($data['name'])) {
            wp_send_json_error(array('message' => 'Product name is required'));
        }
        if ($data['price'] <= 0) {
            wp_send_json_error(array('message' => 'Product price must be greater than 0'));
        }

        global $wpdb;
        $table = $wpdb->prefix . 'shoppaton_products';

        if ($product_id) {
            $data['updated_at'] = current_time('mysql');
            $result = $wpdb->update($table, $data, array('id' => $product_id));
        } else {
            $data['created_at'] = current_time('mysql');
            $data['updated_at'] = current_time('mysql');
            $data['slug'] = sanitize_title($data['name']);
            $result = $wpdb->insert($table, $data);
        }

        if ($result !== false) {
            wp_send_json_success(array('message' => 'Product saved successfully'));
        } else {
            wp_send_json_error(array('message' => 'Database error: ' . $wpdb->last_error));
        }
    }

    /**
     * Save settings from frontend dashboard
     */
    public function save_settings_frontend() {
        check_ajax_referer('shoppaton_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Unauthorized'));
        }

        parse_str($_POST['settings'] ?? '', $settings_data);

        $settings = Shoppaton_Settings::instance();
        $fields = array(
            'paystack_test_public', 'paystack_test_secret',
            'paystack_live_public', 'paystack_live_secret', 'paystack_live_mode',
            'delivery_company', 'delivery_api_key',
            'whatsapp_number', 'contact_email', 
            'instagram_url', 'facebook_url', 'tiktok_url'
        );

        foreach ($fields as $field) {
            if (isset($settings_data[$field])) {
                $value = sanitize_text_field($settings_data[$field]);
                $settings->update($field, $value);
            }
        }

        wp_send_json_success(array('message' => 'Settings saved successfully'));
    }

    /**
     * Get single product for frontend dashboard
     */
    public function get_product_frontend() {
        check_ajax_referer('shoppaton_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Unauthorized'));
        }

        $product_id = intval($_POST['product_id'] ?? 0);
        if (!$product_id) {
            wp_send_json_error(array('message' => 'Invalid product ID'));
        }

        $product = Shoppaton_Products::instance()->get($product_id);
        if (!$product) {
            wp_send_json_error(array('message' => 'Product not found'));
        }

        $images = maybe_unserialize($product->images);
        $images_formatted = array();
        if (!empty($images)) {
            foreach ($images as $image) {
                $images_formatted[] = array(
                    'id' => 0,
                    'url' => $image,
                    'thumbnail' => $image
                );
            }
        }

        wp_send_json_success(array(
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->sale_price ?: $product->price,
            'compare_price' => $product->sale_price ? $product->price : '',
            'category' => $product->category_id,
            'stock' => $product->stock_quantity,
            'description' => $product->description,
            'skin_type' => $product->skin_type,
            'target_user' => $product->target_user,
            'status' => $product->status === 'publish' ? 'active' : 'inactive',
            'images' => $images_formatted
        ));
    }

    /**
     * Update order status from frontend dashboard
     */
    public function update_order_status_frontend() {
        check_ajax_referer('shoppaton_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Unauthorized'));
        }

        $order_id = intval($_POST['order_id'] ?? 0);
        $status = sanitize_text_field($_POST['status'] ?? '');

        if (!$order_id || !$status) {
            wp_send_json_error(array('message' => 'Invalid request'));
        }

        if (Shoppaton_Orders::instance()->update($order_id, array('status' => $status))) {
            wp_send_json_success(array('message' => 'Order status updated'));
        } else {
            wp_send_json_error(array('message' => 'Failed to update order'));
        }
    }

    /**
     * Update tracking info from frontend dashboard
     */
    public function update_tracking_frontend() {
        check_ajax_referer('shoppaton_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Unauthorized'));
        }

        $order_id = intval($_POST['order_id'] ?? 0);
        $delivery_company = sanitize_text_field($_POST['delivery_company'] ?? '');
        $tracking_number = sanitize_text_field($_POST['tracking_number'] ?? '');

        if (!$order_id) {
            wp_send_json_error(array('message' => 'Invalid order ID'));
        }

        global $wpdb;
        $result = $wpdb->update(
            $wpdb->prefix . 'shoppaton_orders',
            array(
                'delivery_company' => $delivery_company,
                'tracking_number' => $tracking_number
            ),
            array('id' => $order_id)
        );

        if ($result !== false) {
            wp_send_json_success(array('message' => 'Tracking info saved'));
        } else {
            wp_send_json_error(array('message' => 'Failed to save tracking info'));
        }
    }

    /**
     * Save product (backend)
     */
    public function save_product_backend() {
        check_ajax_referer('shoppaton_admin_nonce', 'nonce');
        $this->save_product_common();
    }

    /**
     * Delete product (backend)
     */
    public function delete_product_backend() {
        check_ajax_referer('shoppaton_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Unauthorized'));
        }

        $product_id = intval($_POST['product_id'] ?? 0);

        if (Shoppaton_Products::instance()->delete($product_id)) {
            wp_send_json_success(array('message' => 'Product deleted'));
        } else {
            wp_send_json_error(array('message' => 'Failed to delete product'));
        }
    }

    /**
     * Save settings (backend)
     */
    public function save_settings_backend() {
        check_ajax_referer('shoppaton_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Unauthorized'));
        }

        $settings = Shoppaton_Settings::instance();
        $fields = array(
            'paystack_mode', 'paystack_test_public', 'paystack_test_secret',
            'paystack_live_public', 'paystack_live_secret', 'whatsapp_number',
            'contact_email', 'instagram', 'facebook', 'tiktok',
            'abandoned_cart_enabled', 'abandoned_cart_interval'
        );

        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                $value = sanitize_text_field($_POST[$field]);
                if ($field === 'abandoned_cart_enabled') {
                    $value = (bool) $value;
                }
                $settings->update($field, $value);
            }
        }

        wp_send_json_success(array('message' => 'Settings saved'));
    }

    /**
     * Common save product logic
     */
    private function save_product_common() {
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Unauthorized'));
        }

        $product_id = intval($_POST['product_id'] ?? 0);
        
        $data = array(
            'name' => sanitize_text_field($_POST['name'] ?? ''),
            'description' => wp_kses_post($_POST['description'] ?? ''),
            'short_description' => sanitize_textarea_field($_POST['short_description'] ?? ''),
            'price' => floatval($_POST['price'] ?? 0),
            'sale_price' => $_POST['sale_price'] ? floatval($_POST['sale_price']) : null,
            'sku' => sanitize_text_field($_POST['sku'] ?? ''),
            'stock_quantity' => intval($_POST['stock_quantity'] ?? 0),
            'stock_status' => sanitize_text_field($_POST['stock_status'] ?? 'instock'),
            'category_id' => intval($_POST['category_id'] ?? 0) ?: null,
            'images' => array_map('esc_url_raw', $_POST['images'] ?? array()),
            'skin_type' => sanitize_text_field($_POST['skin_type'] ?? ''),
            'target_user' => sanitize_text_field($_POST['target_user'] ?? ''),
            'usage_guide' => wp_kses_post($_POST['usage_guide'] ?? ''),
            'featured' => isset($_POST['featured']) ? 1 : 0,
            'best_seller' => isset($_POST['best_seller']) ? 1 : 0,
            'status' => sanitize_text_field($_POST['status'] ?? 'publish'),
        );

        if ($product_id) {
            $result = Shoppaton_Products::instance()->update($product_id, $data);
        } else {
            $result = Shoppaton_Products::instance()->create($data);
        }

        if ($result) {
            wp_send_json_success(array('message' => 'Product saved successfully'));
        } else {
            wp_send_json_error(array('message' => 'Failed to save product'));
        }
    }
}
