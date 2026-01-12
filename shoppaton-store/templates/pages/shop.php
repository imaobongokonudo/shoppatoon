<?php
/**
 * Shop Page Template
 *
 * @package ShoppatonStore
 */

if (!defined('ABSPATH')) {
    exit;
}

$products_instance = Shoppaton_Products::instance();
$categories = $products_instance->get_categories();

// Get filters from URL
$search = isset($_GET['search']) ? sanitize_text_field($_GET['search']) : '';
$category_filter = isset($_GET['category']) ? intval($_GET['category']) : '';
$sort = isset($_GET['sort']) ? sanitize_text_field($_GET['sort']) : 'newest';
$skin_type = isset($_GET['skin_type']) ? sanitize_text_field($_GET['skin_type']) : '';
$page = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
$per_page = 12;

// Build query args
$args = array(
    'limit' => $per_page,
    'offset' => ($page - 1) * $per_page,
);

if ($search) {
    $args['search'] = $search;
}

if ($category_filter) {
    $args['category'] = $category_filter;
}

if ($skin_type) {
    $args['skin_type'] = $skin_type;
}

switch ($sort) {
    case 'price-low':
        $args['orderby'] = 'price';
        $args['order'] = 'ASC';
        break;
    case 'price-high':
        $args['orderby'] = 'price';
        $args['order'] = 'DESC';
        break;
    case 'name':
        $args['orderby'] = 'name';
        $args['order'] = 'ASC';
        break;
    default:
        $args['orderby'] = 'created_at';
        $args['order'] = 'DESC';
}

$products = $products_instance->get_products($args);
$total_products = $products_instance->get_count(array(
    'category' => $category_filter,
    'search' => $search,
));
$total_pages = ceil($total_products / $per_page);
?>

<?php include SHOPPATON_PLUGIN_DIR . 'templates/partials/header.php'; ?>

<div class="shoppaton-shop-page" style="padding-top: 120px; min-height: 100vh;">
    <div class="shoppaton-container">
        <!-- Page Header -->
        <div class="shoppaton-page-header" style="text-align: center; margin-bottom: 40px;">
            <h1 class="shoppaton-gold-text-animated">Shop</h1>
            <p style="color: var(--shoppaton-text-muted);">
                Discover our collection of premium skincare essentials
            </p>
        </div>

        <div class="shoppaton-shop-layout">
            <!-- Sidebar Filters -->
            <aside class="shoppaton-shop-sidebar">
                <form method="get" action="">
                    <!-- Search -->
                    <div class="shoppaton-filter-group">
                        <h4 class="shoppaton-filter-title">Search</h4>
                        <input type="search" name="search" class="shoppaton-form-input" placeholder="Search products..." value="<?php echo esc_attr($search); ?>">
                    </div>

                    <!-- Categories -->
                    <div class="shoppaton-filter-group">
                        <h4 class="shoppaton-filter-title">Categories</h4>
                        <ul class="shoppaton-filter-options">
                            <li>
                                <label class="shoppaton-filter-checkbox">
                                    <input type="radio" name="category" value="" <?php checked($category_filter, ''); ?>>
                                    All Categories
                                </label>
                            </li>
                            <?php foreach ($categories as $category) : ?>
                            <li>
                                <label class="shoppaton-filter-checkbox">
                                    <input type="radio" name="category" value="<?php echo esc_attr($category->id); ?>" <?php checked($category_filter, $category->id); ?>>
                                    <?php echo esc_html($category->name); ?>
                                </label>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <!-- Skin Type -->
                    <div class="shoppaton-filter-group">
                        <h4 class="shoppaton-filter-title">Skin Type</h4>
                        <ul class="shoppaton-filter-options">
                            <li>
                                <label class="shoppaton-filter-checkbox">
                                    <input type="radio" name="skin_type" value="" <?php checked($skin_type, ''); ?>>
                                    All Types
                                </label>
                            </li>
                            <li>
                                <label class="shoppaton-filter-checkbox">
                                    <input type="radio" name="skin_type" value="dry" <?php checked($skin_type, 'dry'); ?>>
                                    Dry Skin
                                </label>
                            </li>
                            <li>
                                <label class="shoppaton-filter-checkbox">
                                    <input type="radio" name="skin_type" value="oily" <?php checked($skin_type, 'oily'); ?>>
                                    Oily Skin
                                </label>
                            </li>
                            <li>
                                <label class="shoppaton-filter-checkbox">
                                    <input type="radio" name="skin_type" value="combination" <?php checked($skin_type, 'combination'); ?>>
                                    Combination
                                </label>
                            </li>
                            <li>
                                <label class="shoppaton-filter-checkbox">
                                    <input type="radio" name="skin_type" value="sensitive" <?php checked($skin_type, 'sensitive'); ?>>
                                    Sensitive
                                </label>
                            </li>
                            <li>
                                <label class="shoppaton-filter-checkbox">
                                    <input type="radio" name="skin_type" value="all" <?php checked($skin_type, 'all'); ?>>
                                    All Skin Types
                                </label>
                            </li>
                        </ul>
                    </div>

                    <!-- Sort -->
                    <div class="shoppaton-filter-group">
                        <h4 class="shoppaton-filter-title">Sort By</h4>
                        <select name="sort" class="shoppaton-form-select">
                            <option value="newest" <?php selected($sort, 'newest'); ?>>Newest</option>
                            <option value="price-low" <?php selected($sort, 'price-low'); ?>>Price: Low to High</option>
                            <option value="price-high" <?php selected($sort, 'price-high'); ?>>Price: High to Low</option>
                            <option value="name" <?php selected($sort, 'name'); ?>>Name: A-Z</option>
                        </select>
                    </div>

                    <button type="submit" class="shoppaton-btn shoppaton-btn-primary" style="width: 100%;">
                        Apply Filters
                    </button>

                    <?php if ($search || $category_filter || $skin_type) : ?>
                    <a href="<?php echo esc_url(get_permalink(get_page_by_path('shop'))); ?>" class="shoppaton-btn shoppaton-btn-glass" style="width: 100%; margin-top: 10px; text-align: center;">
                        Clear Filters
                    </a>
                    <?php endif; ?>
                </form>
            </aside>

            <!-- Products Grid -->
            <div class="shoppaton-shop-main">
                <!-- Results info -->
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
                    <p style="color: var(--shoppaton-text-muted); margin: 0;">
                        Showing <?php echo count($products); ?> of <?php echo esc_html($total_products); ?> products
                        <?php if ($search) : ?>
                            for "<?php echo esc_html($search); ?>"
                        <?php endif; ?>
                    </p>
                </div>

                <?php if (!empty($products)) : ?>
                <div class="shoppaton-products-grid">
                    <?php foreach ($products as $product) : ?>
                        <?php include SHOPPATON_PLUGIN_DIR . 'templates/partials/product-card.php'; ?>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <?php if ($total_pages > 1) : ?>
                <div style="display: flex; justify-content: center; gap: 10px; margin-top: 50px;">
                    <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                    <a href="<?php echo esc_url(add_query_arg('paged', $i)); ?>" 
                       class="shoppaton-btn <?php echo $i === $page ? 'shoppaton-btn-primary' : 'shoppaton-btn-glass'; ?>"
                       style="min-width: 45px;">
                        <?php echo esc_html($i); ?>
                    </a>
                    <?php endfor; ?>
                </div>
                <?php endif; ?>

                <?php else : ?>
                <div style="text-align: center; padding: 60px 20px;">
                    <h3 style="color: var(--shoppaton-white); -webkit-text-fill-color: var(--shoppaton-white);">No products found</h3>
                    <p style="color: var(--shoppaton-text-muted);">
                        Try adjusting your search or filter criteria.
                    </p>
                    <a href="<?php echo esc_url(get_permalink(get_page_by_path('shop'))); ?>" class="shoppaton-btn shoppaton-btn-primary" style="margin-top: 20px;">
                        View All Products
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include SHOPPATON_PLUGIN_DIR . 'templates/partials/footer.php'; ?>
