<?php
/**
 * Wishlist Page Template
 *
 * @package ShoppatonStore
 */

if (!defined('ABSPATH')) {
    exit;
}

$user = Shoppaton_User::instance();
$wishlist = $user->get_wishlist();
?>

<?php include SHOPPATON_PLUGIN_DIR . 'templates/partials/header.php'; ?>

<div class="shoppaton-wishlist-page" style="padding-top: 120px; min-height: 100vh;">
    <div class="shoppaton-container">
        <!-- Page Header -->
        <div class="shoppaton-page-header" style="text-align: center; margin-bottom: 60px;">
            <span class="shoppaton-section-subtitle">Your Favorites</span>
            <h1 class="shoppaton-gold-text-animated">My Wishlist</h1>
            <p style="color: var(--shoppaton-text-muted); max-width: 600px; margin: 20px auto 0;">
                Products you've saved for later. Don't miss out on your favorites!
            </p>
        </div>

        <!-- Wishlist Content -->
        <?php if (empty($wishlist)) : ?>
        <div class="shoppaton-glass-card" style="max-width: 600px; margin: 0 auto; text-align: center; padding: 60px 40px;">
            <div style="font-size: 80px; margin-bottom: 25px;">❤️</div>
            <h2 style="margin-bottom: 15px;">Your Wishlist is Empty</h2>
            <p style="color: var(--shoppaton-text-muted); margin-bottom: 30px;">
                Save products you love by clicking the heart icon on any product. They'll appear here for easy access later.
            </p>
            <a href="<?php echo esc_url(get_permalink(get_page_by_path('shop'))); ?>" class="shoppaton-btn shoppaton-btn-primary">
                Start Shopping
            </a>
        </div>
        <?php else : ?>
        <div class="shoppaton-glass-card" style="padding: 30px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
                <h3 style="margin: 0;"><?php echo count($wishlist); ?> item(s) in your wishlist</h3>
                <button type="button" class="shoppaton-btn shoppaton-btn-secondary" id="clear-wishlist">
                    Clear All
                </button>
            </div>

            <div class="shoppaton-products-grid" style="grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));">
                <?php foreach ($wishlist as $product) : ?>
                <div class="shoppaton-wishlist-item" data-product-id="<?php echo esc_attr($product['id']); ?>">
                    <div class="shoppaton-product-card shoppaton-glass-card">
                        <!-- Remove from wishlist button -->
                        <button type="button" class="shoppaton-wishlist-remove" data-product-id="<?php echo esc_attr($product['id']); ?>" style="position: absolute; top: 10px; right: 10px; z-index: 10; background: rgba(255, 107, 107, 0.9); color: white; border: none; width: 32px; height: 32px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                            ✕
                        </button>
                        
                        <a href="<?php echo esc_url($product['url']); ?>" class="shoppaton-product-image">
                            <?php if (!empty($product['image'])) : ?>
                            <img src="<?php echo esc_url($product['image']); ?>" alt="<?php echo esc_attr($product['name']); ?>" loading="lazy">
                            <?php else : ?>
                            <div class="shoppaton-image-placeholder">
                                <span>Product Image</span>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($product['sale_price'])) : ?>
                            <span class="shoppaton-product-badge shoppaton-badge-sale">Sale</span>
                            <?php endif; ?>
                        </a>
                        
                        <div class="shoppaton-product-info" style="padding: 15px;">
                            <h4 class="shoppaton-product-title">
                                <a href="<?php echo esc_url($product['url']); ?>">
                                    <?php echo esc_html($product['name']); ?>
                                </a>
                            </h4>
                            
                            <div class="shoppaton-product-price">
                                <?php if (!empty($product['sale_price'])) : ?>
                                <span class="shoppaton-price-current">₦<?php echo number_format($product['sale_price'], 0); ?></span>
                                <span class="shoppaton-price-old">₦<?php echo number_format($product['price'], 0); ?></span>
                                <?php else : ?>
                                <span class="shoppaton-price-current">₦<?php echo number_format($product['price'], 0); ?></span>
                                <?php endif; ?>
                            </div>
                            
                            <?php if ($product['stock_status'] === 'outofstock') : ?>
                            <p style="color: #ff6b6b; font-size: 13px; margin: 10px 0 0;">Out of Stock</p>
                            <?php endif; ?>
                            
                            <div style="display: flex; gap: 10px; margin-top: 15px;">
                                <button type="button" class="shoppaton-btn shoppaton-btn-primary shoppaton-add-to-cart" data-product-id="<?php echo esc_attr($product['id']); ?>" style="flex: 1; padding: 10px;" <?php echo $product['stock_status'] === 'outofstock' ? 'disabled' : ''; ?>>
                                    Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Continue Shopping -->
        <div style="text-align: center; margin-top: 40px;">
            <a href="<?php echo esc_url(get_permalink(get_page_by_path('shop'))); ?>" class="shoppaton-btn shoppaton-btn-secondary">
                ← Continue Shopping
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Remove from wishlist
    $('.shoppaton-wishlist-remove').on('click', function(e) {
        e.preventDefault();
        var productId = $(this).data('product-id');
        var $item = $(this).closest('.shoppaton-wishlist-item');
        
        $.ajax({
            url: shoppatonData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'shoppaton_remove_from_wishlist',
                nonce: shoppatonData.nonce,
                product_id: productId
            },
            success: function(response) {
                if (response.success) {
                    $item.fadeOut(300, function() {
                        $(this).remove();
                        
                        // Check if wishlist is now empty
                        if ($('.shoppaton-wishlist-item').length === 0) {
                            location.reload();
                        } else {
                            // Update count
                            var count = $('.shoppaton-wishlist-item').length;
                            $('h3:contains("item")').text(count + ' item(s) in your wishlist');
                        }
                    });
                    Shoppaton.toast('Removed from wishlist', 'success');
                }
            }
        });
    });
    
    // Clear all
    $('#clear-wishlist').on('click', function() {
        if (!confirm('Are you sure you want to clear your wishlist?')) return;
        
        $.ajax({
            url: shoppatonData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'shoppaton_clear_wishlist',
                nonce: shoppatonData.nonce
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                }
            }
        });
    });
    
    // Add to cart
    $('.shoppaton-add-to-cart').on('click', function() {
        var productId = $(this).data('product-id');
        var $btn = $(this);
        
        $btn.prop('disabled', true).text('Adding...');
        
        $.ajax({
            url: shoppatonData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'shoppaton_add_to_cart',
                nonce: shoppatonData.nonce,
                product_id: productId,
                quantity: 1
            },
            success: function(response) {
                if (response.success) {
                    Shoppaton.toast('Added to cart!', 'success');
                    Shoppaton.updateCartCount(response.data.cart_count);
                } else {
                    Shoppaton.toast(response.data.message || 'Error adding to cart', 'error');
                }
            },
            complete: function() {
                $btn.prop('disabled', false).text('Add to Cart');
            }
        });
    });
});
</script>

<?php include SHOPPATON_PLUGIN_DIR . 'templates/partials/footer.php'; ?>
