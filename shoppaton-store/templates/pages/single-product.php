<?php
/**
 * Single Product Page Template
 *
 * @package ShoppatonStore
 */

if (!defined('ABSPATH')) {
    exit;
}

$products_instance = Shoppaton_Products::instance();
$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;

if (!$product_id) {
    // Try to get from shortcode attribute
    $product_id = isset($atts['id']) ? intval($atts['id']) : 0;
}

$product = $products_instance->get_product($product_id);

if (!$product) {
    echo '<div class="shoppaton-container" style="padding: 100px 20px; text-align: center;">
        <h2>Product Not Found</h2>
        <p>Sorry, the product you are looking for could not be found.</p>
        <a href="' . home_url('/shop') . '" class="shoppaton-btn shoppaton-btn-primary">Back to Shop</a>
    </div>';
    return;
}

$images = $products_instance->get_product_images($product);
$main_image = $products_instance->get_product_image($product);
$category = $product->category_id ? $products_instance->get_category($product->category_id) : null;
$in_wishlist = Shoppaton_User::instance()->is_in_wishlist($product->id);
$related_products = $products_instance->get_products(array(
    'category' => $product->category_id,
    'limit' => 4,
    'exclude' => array($product->id)
));
?>

<?php include SHOPPATON_PLUGIN_DIR . 'templates/partials/header.php'; ?>

<div class="shoppaton-single-product" style="padding: 100px 20px 80px;">
    <div class="shoppaton-container">
        <!-- Breadcrumb -->
        <nav style="margin-bottom: 30px;">
            <span><a href="<?php echo home_url(); ?>" style="color: var(--shoppaton-text-muted);">Home</a></span>
            <span style="color: var(--shoppaton-text-muted); margin: 0 10px;">/</span>
            <span><a href="<?php echo home_url('/shop'); ?>" style="color: var(--shoppaton-text-muted);">Shop</a></span>
            <?php if ($category) : ?>
            <span style="color: var(--shoppaton-text-muted); margin: 0 10px;">/</span>
            <span><a href="<?php echo home_url('/shop?category=' . $category->id); ?>" style="color: var(--shoppaton-text-muted);"><?php echo esc_html($category->name); ?></a></span>
            <?php endif; ?>
            <span style="color: var(--shoppaton-text-muted); margin: 0 10px;">/</span>
            <span style="color: var(--shoppaton-gold);"><?php echo esc_html($product->name); ?></span>
        </nav>

        <div class="shoppaton-product-detail" style="display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: start;">
            <!-- Product Images -->
            <div class="shoppaton-product-gallery" style="position: sticky; top: 100px;">
                <!-- Main Image -->
                <div class="shoppaton-gallery-main" style="aspect-ratio: 1; border-radius: var(--border-radius-lg); overflow: hidden; margin-bottom: 20px; background: var(--shoppaton-glass);">
                    <?php if ($main_image) : ?>
                    <img id="main-product-image" src="<?php echo esc_url($main_image); ?>" alt="<?php echo esc_attr($product->name); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                    <?php else : ?>
                    <div class="shoppaton-image-placeholder" style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                        <span>Product Image</span>
                    </div>
                    <?php endif; ?>
                </div>
                
                <!-- Thumbnail Images -->
                <?php if (count($images) > 1) : ?>
                <div class="shoppaton-gallery-thumbs" style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <?php foreach ($images as $index => $img) : ?>
                    <div class="shoppaton-gallery-thumb <?php echo $index === 0 ? 'active' : ''; ?>" 
                         onclick="document.getElementById('main-product-image').src = '<?php echo esc_url($img); ?>'; document.querySelectorAll('.shoppaton-gallery-thumb').forEach(t => t.classList.remove('active')); this.classList.add('active');"
                         style="width: 80px; height: 80px; border-radius: var(--border-radius); overflow: hidden; cursor: pointer; border: 2px solid <?php echo $index === 0 ? 'var(--shoppaton-gold)' : 'transparent'; ?>; transition: border-color 0.3s;">
                        <img src="<?php echo esc_url($img); ?>" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Product Info -->
            <div class="shoppaton-product-info-detail" data-product-id="<?php echo esc_attr($product->id); ?>">
                <?php if ($category) : ?>
                <span style="color: var(--shoppaton-gold); font-size: 12px; text-transform: uppercase; letter-spacing: 2px;"><?php echo esc_html($category->name); ?></span>
                <?php endif; ?>
                
                <h1 style="font-size: 32px; margin: 10px 0 20px; font-family: var(--font-display); color: var(--shoppaton-white); -webkit-text-fill-color: var(--shoppaton-white);"><?php echo esc_html($product->name); ?></h1>
                
                <!-- Price -->
                <div class="shoppaton-product-price" style="margin-bottom: 25px; display: flex; align-items: center; gap: 15px;">
                    <span style="font-size: 28px; font-weight: 700; color: var(--shoppaton-gold);">
                        <?php echo esc_html(Shoppaton_Settings::format_price($product->sale_price ?: $product->price)); ?>
                    </span>
                    <?php if ($product->sale_price) : ?>
                    <span style="font-size: 18px; color: var(--shoppaton-text-muted); text-decoration: line-through;">
                        <?php echo esc_html(Shoppaton_Settings::format_price($product->price)); ?>
                    </span>
                    <span style="background: var(--shoppaton-gold); color: var(--shoppaton-black); padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                        <?php echo round((($product->price - $product->sale_price) / $product->price) * 100); ?>% OFF
                    </span>
                    <?php endif; ?>
                </div>

                <!-- Short Description -->
                <?php if ($product->short_description) : ?>
                <p style="color: var(--shoppaton-text); margin-bottom: 25px; line-height: 1.8;"><?php echo esc_html($product->short_description); ?></p>
                <?php endif; ?>

                <!-- Product Details -->
                <div class="shoppaton-product-meta" style="margin-bottom: 30px; padding: 20px; background: var(--shoppaton-glass); border-radius: var(--border-radius); border: 1px solid var(--shoppaton-glass-border);">
                    <?php if ($product->skin_type) : ?>
                    <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid var(--shoppaton-glass-border);">
                        <span style="color: var(--shoppaton-text-muted);">Skin Type</span>
                        <span style="color: var(--shoppaton-white);"><?php echo esc_html(ucfirst($product->skin_type)); ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if ($product->target_user) : ?>
                    <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid var(--shoppaton-glass-border);">
                        <span style="color: var(--shoppaton-text-muted);">Best For</span>
                        <span style="color: var(--shoppaton-white);"><?php echo esc_html(ucfirst($product->target_user)); ?></span>
                    </div>
                    <?php endif; ?>
                    <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid var(--shoppaton-glass-border);">
                        <span style="color: var(--shoppaton-text-muted);">Availability</span>
                        <span style="color: <?php echo $product->stock_quantity > 0 ? '#4caf50' : '#ff6b6b'; ?>;">
                            <?php echo $product->stock_quantity > 0 ? 'In Stock (' . $product->stock_quantity . ')' : 'Out of Stock'; ?>
                        </span>
                    </div>
                    <?php if ($product->sku) : ?>
                    <div style="display: flex; justify-content: space-between; padding: 10px 0;">
                        <span style="color: var(--shoppaton-text-muted);">SKU</span>
                        <span style="color: var(--shoppaton-white);"><?php echo esc_html($product->sku); ?></span>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Quantity & Add to Cart -->
                <div class="shoppaton-product-actions" style="display: flex; gap: 15px; align-items: center; margin-bottom: 20px;">
                    <div class="shoppaton-quantity" style="display: flex; align-items: center; border: 1px solid var(--shoppaton-glass-border); border-radius: var(--border-radius);">
                        <button type="button" class="shoppaton-quantity-btn minus" style="width: 45px; height: 45px; background: transparent; border: none; color: var(--shoppaton-white); cursor: pointer; font-size: 18px;">−</button>
                        <input type="number" class="shoppaton-quantity-input" value="1" min="1" max="<?php echo esc_attr($product->stock_quantity); ?>" style="width: 60px; height: 45px; text-align: center; background: transparent; border: none; color: var(--shoppaton-white); font-size: 16px;">
                        <button type="button" class="shoppaton-quantity-btn plus" style="width: 45px; height: 45px; background: transparent; border: none; color: var(--shoppaton-white); cursor: pointer; font-size: 18px;">+</button>
                    </div>
                    <button class="shoppaton-btn shoppaton-btn-primary shoppaton-add-to-cart" style="flex: 1; padding: 15px 30px; font-size: 14px;" <?php echo $product->stock_quantity < 1 ? 'disabled' : ''; ?>>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 10px;">
                            <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                        </svg>
                        Add to Cart
                    </button>
                    <button class="shoppaton-product-action shoppaton-add-to-wishlist <?php echo $in_wishlist ? 'active' : ''; ?>" title="Add to Wishlist" style="width: 50px; height: 50px; background: var(--shoppaton-glass); border: 1px solid var(--shoppaton-glass-border); color: <?php echo $in_wishlist ? 'var(--shoppaton-gold)' : 'var(--shoppaton-white)'; ?>;">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="<?php echo $in_wishlist ? 'currentColor' : 'none'; ?>" stroke="currentColor" stroke-width="2">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                        </svg>
                    </button>
                </div>

                <!-- Buy Now -->
                <button class="shoppaton-btn shoppaton-btn-secondary shoppaton-buy-now" style="width: 100%; padding: 15px 30px; margin-bottom: 30px;" <?php echo $product->stock_quantity < 1 ? 'disabled' : ''; ?>>
                    Buy Now
                </button>

                <!-- Usage Guide Image -->
                <?php if (!empty($product->usage_guide_image)) : ?>
                <div class="shoppaton-usage-guide" style="margin-bottom: 30px;">
                    <h3 style="font-size: 18px; margin-bottom: 15px; color: var(--shoppaton-gold);">Usage Guide</h3>
                    <div style="border-radius: var(--border-radius-lg); overflow: hidden;">
                        <img src="<?php echo esc_url($product->usage_guide_image); ?>" alt="Usage Guide" style="width: 100%; height: auto;">
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Product Description Tabs -->
        <div class="shoppaton-product-tabs" style="margin-top: 60px;">
            <div class="shoppaton-tabs-nav" style="display: flex; gap: 5px; border-bottom: 1px solid var(--shoppaton-glass-border); margin-bottom: 30px;">
                <button class="shoppaton-tab-btn active" data-tab="description" style="padding: 15px 30px; background: transparent; border: none; color: var(--shoppaton-gold); cursor: pointer; font-size: 14px; border-bottom: 2px solid var(--shoppaton-gold);">Description</button>
                <?php if ($product->usage_guide) : ?>
                <button class="shoppaton-tab-btn" data-tab="usage" style="padding: 15px 30px; background: transparent; border: none; color: var(--shoppaton-text-muted); cursor: pointer; font-size: 14px; border-bottom: 2px solid transparent;">How to Use</button>
                <?php endif; ?>
            </div>
            
            <div class="shoppaton-tabs-content">
                <div id="tab-description" class="shoppaton-tab-panel" style="display: block;">
                    <div class="shoppaton-glass-card" style="padding: 30px;">
                        <?php if ($product->description) : ?>
                        <div style="color: var(--shoppaton-text); line-height: 1.8;">
                            <?php echo wp_kses_post(nl2br($product->description)); ?>
                        </div>
                        <?php else : ?>
                        <p style="color: var(--shoppaton-text-muted);">No description available.</p>
                        <?php endif; ?>
                    </div>
                </div>
                
                <?php if ($product->usage_guide) : ?>
                <div id="tab-usage" class="shoppaton-tab-panel" style="display: none;">
                    <div class="shoppaton-glass-card" style="padding: 30px;">
                        <div style="color: var(--shoppaton-text); line-height: 1.8;">
                            <?php echo wp_kses_post(nl2br($product->usage_guide)); ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Related Products -->
        <?php if ($related_products) : ?>
        <div class="shoppaton-related-products" style="margin-top: 80px;">
            <h2 style="text-align: center; margin-bottom: 40px; font-family: var(--font-display);">
                <span class="shoppaton-gold-text-animated">Related Products</span>
            </h2>
            <div class="shoppaton-products-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 30px;">
                <?php foreach ($related_products as $product) : ?>
                    <?php include SHOPPATON_PLUGIN_DIR . 'templates/partials/product-card.php'; ?>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<style>
/* Single Product Page - Mobile Responsive */
@media (max-width: 992px) {
    .shoppaton-product-detail {
        grid-template-columns: 1fr !important;
        gap: 40px !important;
    }
    
    .shoppaton-product-gallery {
        position: static !important;
    }
}

@media (max-width: 768px) {
    .shoppaton-single-product {
        padding: 80px 15px 60px !important;
    }
    
    .shoppaton-product-info-detail h1 {
        font-size: 24px !important;
    }
    
    .shoppaton-product-price span:first-child {
        font-size: 24px !important;
    }
    
    .shoppaton-product-actions {
        flex-wrap: wrap;
    }
    
    .shoppaton-tabs-nav {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
}

/* Tab switching */
.shoppaton-tab-btn:hover {
    color: var(--shoppaton-gold) !important;
}

.shoppaton-tab-btn.active {
    color: var(--shoppaton-gold) !important;
    border-bottom-color: var(--shoppaton-gold) !important;
}

/* Gallery thumbnail hover */
.shoppaton-gallery-thumb:hover {
    border-color: var(--shoppaton-gold) !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab switching
    document.querySelectorAll('.shoppaton-tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active from all tabs
            document.querySelectorAll('.shoppaton-tab-btn').forEach(b => {
                b.classList.remove('active');
                b.style.color = 'var(--shoppaton-text-muted)';
                b.style.borderBottomColor = 'transparent';
            });
            
            // Add active to clicked tab
            this.classList.add('active');
            this.style.color = 'var(--shoppaton-gold)';
            this.style.borderBottomColor = 'var(--shoppaton-gold)';
            
            // Hide all panels
            document.querySelectorAll('.shoppaton-tab-panel').forEach(p => p.style.display = 'none');
            
            // Show target panel
            const target = this.dataset.tab;
            document.getElementById('tab-' + target).style.display = 'block';
        });
    });
});
</script>

<?php include SHOPPATON_PLUGIN_DIR . 'templates/partials/footer.php'; ?>
