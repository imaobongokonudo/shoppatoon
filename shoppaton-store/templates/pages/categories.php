<?php
/**
 * Categories Page Template
 *
 * @package ShoppatonStore
 */

if (!defined('ABSPATH')) {
    exit;
}

global $wpdb;
$categories_table = $wpdb->prefix . 'shoppaton_categories';
$categories = $wpdb->get_results("SELECT * FROM {$categories_table} ORDER BY name ASC");

$products = Shoppaton_Products::instance();
?>

<?php include SHOPPATON_PLUGIN_DIR . 'templates/partials/header.php'; ?>

<div class="shoppaton-page-wrapper" style="padding-top: 100px; padding-bottom: 80px;">
    <div class="shoppaton-container">
        <!-- Page Header -->
        <div class="shoppaton-page-header" style="text-align: center; margin-bottom: 50px;">
            <span class="shoppaton-section-subtitle">Browse By Category</span>
            <h1 class="shoppaton-section-title">All Categories</h1>
            <p class="shoppaton-section-desc">
                Explore our complete collection of skincare categories and find the perfect products for your needs.
            </p>
        </div>

        <!-- Categories Grid -->
        <div class="shoppaton-categories-full-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 30px;">
            <?php if (!empty($categories)) : ?>
                <?php foreach ($categories as $category) : 
                    // Get product count for category
                    $product_count = $products->get_count(array('category' => $category->id));
                ?>
                <a href="<?php echo esc_url(add_query_arg('category', $category->id, get_permalink(get_page_by_path('shop')))); ?>" 
                   class="shoppaton-category-full-card shoppaton-glass-card shoppaton-animate animate-on-scroll shoppaton-fade-in-up">
                    <div class="shoppaton-category-full-image">
                        <?php if (!empty($category->image)) : ?>
                            <img src="<?php echo esc_url($category->image); ?>" alt="<?php echo esc_attr($category->name); ?>">
                        <?php else : ?>
                            <div class="shoppaton-image-placeholder">
                                <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                                </svg>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="shoppaton-category-full-content">
                        <h3 class="shoppaton-category-full-name"><?php echo esc_html($category->name); ?></h3>
                        <?php if (!empty($category->description)) : ?>
                            <p class="shoppaton-category-full-desc"><?php echo esc_html($category->description); ?></p>
                        <?php endif; ?>
                        <span class="shoppaton-category-product-count"><?php echo intval($product_count); ?> Products</span>
                    </div>
                </a>
                <?php endforeach; ?>
            <?php else : ?>
                <!-- Default categories when none exist -->
                <?php
                $default_categories = array(
                    array('name' => 'Face Care', 'desc' => 'Cleansers, moisturizers, and treatments for your face'),
                    array('name' => 'Body Care', 'desc' => 'Lotions, body washes, and exfoliators'),
                    array('name' => 'Serums', 'desc' => 'Concentrated treatments for targeted results'),
                    array('name' => 'Sunscreen', 'desc' => 'Protection from harmful UV rays'),
                    array('name' => 'Moisturizers', 'desc' => 'Hydrating creams and lotions'),
                    array('name' => 'Cleansers', 'desc' => 'Gentle and effective cleansing products'),
                );
                foreach ($default_categories as $cat) : ?>
                <a href="<?php echo esc_url(get_permalink(get_page_by_path('shop'))); ?>" 
                   class="shoppaton-category-full-card shoppaton-glass-card shoppaton-animate animate-on-scroll shoppaton-fade-in-up">
                    <div class="shoppaton-category-full-image">
                        <div class="shoppaton-image-placeholder">
                            <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="shoppaton-category-full-content">
                        <h3 class="shoppaton-category-full-name"><?php echo esc_html($cat['name']); ?></h3>
                        <p class="shoppaton-category-full-desc"><?php echo esc_html($cat['desc']); ?></p>
                        <span class="shoppaton-category-product-count">0 Products</span>
                    </div>
                </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.shoppaton-category-full-card {
    display: block;
    text-decoration: none !important;
    overflow: hidden;
    transition: var(--transition-normal);
}

.shoppaton-category-full-card:hover {
    transform: translateY(-10px);
    border-color: var(--shoppaton-gold);
    box-shadow: var(--shadow-gold);
}

.shoppaton-category-full-image {
    height: 180px;
    overflow: hidden;
    background: var(--shoppaton-black-light);
}

.shoppaton-category-full-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: var(--transition-slow);
}

.shoppaton-category-full-card:hover .shoppaton-category-full-image img {
    transform: scale(1.1);
}

.shoppaton-category-full-image .shoppaton-image-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--shoppaton-gold);
    border: none;
}

.shoppaton-category-full-content {
    padding: 20px;
}

.shoppaton-category-full-name {
    font-size: 1.1rem;
    color: var(--shoppaton-white);
    -webkit-text-fill-color: var(--shoppaton-white);
    margin: 0 0 8px;
}

.shoppaton-category-full-desc {
    color: var(--shoppaton-text-muted);
    font-size: 0.8rem;
    margin: 0 0 12px;
    line-height: 1.5;
}

.shoppaton-category-product-count {
    display: inline-block;
    background: var(--shoppaton-gold-gradient);
    color: var(--shoppaton-black);
    font-size: 0.7rem;
    font-weight: 600;
    padding: 4px 12px;
    border-radius: 20px;
}

@media (max-width: 768px) {
    .shoppaton-categories-full-grid {
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 15px !important;
    }
    
    .shoppaton-category-full-image {
        height: 120px;
    }
    
    .shoppaton-category-full-content {
        padding: 12px;
    }
    
    .shoppaton-category-full-name {
        font-size: 0.9rem;
    }
    
    .shoppaton-category-full-desc {
        display: none;
    }
}
</style>

<?php include SHOPPATON_PLUGIN_DIR . 'templates/partials/footer.php'; ?>
