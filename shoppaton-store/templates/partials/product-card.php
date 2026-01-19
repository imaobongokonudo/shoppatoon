<?php
/**
 * Product Card Template
 *
 * @package ShoppatonStore
 */

if (!defined('ABSPATH')) {
    exit;
}

// $product variable should be set before including this template
$products_instance = Shoppaton_Products::instance();
$image = $products_instance->get_product_image($product);
$url = $products_instance->get_product_url($product);
$in_wishlist = Shoppaton_User::instance()->is_in_wishlist($product->id);
$category = $product->category_id ? $products_instance->get_category($product->category_id) : null;
?>
<div class="shoppaton-product-card shoppaton-animate animate-on-scroll shoppaton-fade-in-up" data-product-id="<?php echo esc_attr($product->id); ?>">
    <div class="shoppaton-product-image">
        <?php if ($image) : ?>
        <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($product->name); ?>" loading="lazy">
        <?php else : ?>
        <div class="shoppaton-image-placeholder" style="aspect-ratio: 1; min-height: 200px;">
            <span>Product Image</span>
        </div>
        <?php endif; ?>
        
        <?php if ($product->sale_price) : ?>
        <span class="shoppaton-product-badge">Sale</span>
        <?php elseif ($product->featured) : ?>
        <span class="shoppaton-product-badge">Featured</span>
        <?php elseif ($product->best_seller) : ?>
        <span class="shoppaton-product-badge">Best Seller</span>
        <?php endif; ?>
        
        <div class="shoppaton-product-overlay">
            <button class="shoppaton-product-action shoppaton-add-to-cart" title="Add to Cart">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="9" cy="21" r="1"/>
                    <circle cx="20" cy="21" r="1"/>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                </svg>
            </button>
            <button class="shoppaton-product-action shoppaton-add-to-wishlist <?php echo $in_wishlist ? 'active' : ''; ?>" title="Add to Wishlist">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="<?php echo $in_wishlist ? 'currentColor' : 'none'; ?>" stroke="currentColor" stroke-width="2">
                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                </svg>
            </button>
            <button class="shoppaton-product-action shoppaton-quick-view" title="Quick View">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                    <circle cx="12" cy="12" r="3"/>
                </svg>
            </button>
        </div>
    </div>
    
    <div class="shoppaton-product-info">
        <?php if ($category) : ?>
        <span class="shoppaton-product-category"><?php echo esc_html($category->name); ?></span>
        <?php endif; ?>
        
        <a href="<?php echo esc_url($url); ?>">
            <h3 class="shoppaton-product-title"><?php echo esc_html($product->name); ?></h3>
        </a>
        
        <?php if ($product->skin_type || $product->target_user) : ?>
        <p style="font-size: 12px; color: var(--shoppaton-text-muted); margin-bottom: 10px;">
            <?php 
            $details = array();
            if ($product->skin_type) $details[] = $product->skin_type . ' Skin';
            if ($product->target_user) $details[] = 'For ' . $product->target_user;
            echo esc_html(implode(' • ', $details));
            ?>
        </p>
        <?php endif; ?>
        
        <div class="shoppaton-product-price">
            <span class="current"><?php echo esc_html(Shoppaton_Settings::format_price($product->sale_price ?: $product->price)); ?></span>
            <?php if ($product->sale_price) : ?>
            <span class="original"><?php echo esc_html(Shoppaton_Settings::format_price($product->price)); ?></span>
            <?php endif; ?>
        </div>
    </div>
</div>
