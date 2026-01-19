<?php
/**
 * Cart Page Template
 *
 * @package ShoppatonStore
 */

if (!defined('ABSPATH')) {
    exit;
}

$cart = Shoppaton_Cart::instance();
$cart_items = $cart->get_items();
$subtotal = $cart->get_subtotal();
$shipping = $cart->get_shipping_cost('lagos');
$total = $subtotal + $shipping;
?>

<?php include SHOPPATON_PLUGIN_DIR . 'templates/partials/header.php'; ?>

<div class="shoppaton-cart-page" style="padding-top: 100px; padding-bottom: 80px; min-height: 100vh;">
    <div class="shoppaton-container">
        <h1 class="shoppaton-gold-text-animated" style="text-align: center; margin-bottom: 40px;">Shopping Cart</h1>

        <?php if (!empty($cart_items)) : ?>
        <div class="shoppaton-cart-layout">
            <!-- Cart Items -->
            <div class="shoppaton-cart-items">
                <?php foreach ($cart_items as $item) : ?>
                <div class="shoppaton-cart-item" data-product-id="<?php echo esc_attr($item['product_id']); ?>">
                    <div class="shoppaton-cart-item-image">
                        <?php if ($item['image']) : ?>
                        <img src="<?php echo esc_url($item['image']); ?>" alt="<?php echo esc_attr($item['name']); ?>">
                        <?php else : ?>
                        <div class="shoppaton-image-placeholder" style="width: 100%; aspect-ratio: 1;">
                            <span>📦</span>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="shoppaton-cart-item-info">
                        <h4 class="shoppaton-cart-item-title"><?php echo esc_html($item['name']); ?></h4>
                        <span class="shoppaton-cart-item-price"><?php echo esc_html(Shoppaton_Settings::format_price($item['price'])); ?></span>
                    </div>
                    
                    <div class="shoppaton-quantity-control">
                        <button class="shoppaton-quantity-btn minus">−</button>
                        <input type="number" class="shoppaton-quantity-input" value="<?php echo esc_attr($item['quantity']); ?>" min="1" max="<?php echo esc_attr($item['stock_quantity'] ?: 99); ?>">
                        <button class="shoppaton-quantity-btn plus">+</button>
                    </div>
                    
                    <div class="shoppaton-cart-item-total" style="text-align: right;">
                        <strong class="shoppaton-item-total" style="color: var(--shoppaton-gold);"><?php echo esc_html(Shoppaton_Settings::format_price($item['price'] * $item['quantity'])); ?></strong>
                        <button class="shoppaton-cart-item-remove" title="Remove">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="3 6 5 6 21 6"/>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>

                <div style="margin-top: 30px;">
                    <a href="<?php echo esc_url(get_permalink(get_page_by_path('shop'))); ?>" class="shoppaton-btn shoppaton-btn-glass">
                        ← Continue Shopping
                    </a>
                </div>
            </div>

            <!-- Cart Summary -->
            <div class="shoppaton-cart-summary">
                <h3 class="shoppaton-cart-summary-title">Order Summary</h3>
                
                <div class="shoppaton-cart-summary-row">
                    <span>Subtotal</span>
                    <span id="cart-subtotal"><?php echo esc_html(Shoppaton_Settings::format_price($subtotal)); ?></span>
                </div>
                
                <div class="shoppaton-cart-summary-row">
                    <span>Shipping (Lagos)</span>
                    <span id="cart-shipping"><?php echo esc_html(Shoppaton_Settings::format_price($shipping)); ?></span>
                </div>
                
                <div class="shoppaton-cart-summary-row total">
                    <span>Total</span>
                    <span id="cart-total"><?php echo esc_html(Shoppaton_Settings::format_price($total)); ?></span>
                </div>

                <p style="font-size: 12px; color: var(--shoppaton-text-muted); margin: 20px 0;">
                    Shipping calculated based on delivery location. Final amount may vary.
                </p>

                <!-- Trust Signals -->
                <div style="border-top: 1px solid var(--shoppaton-glass-border); padding-top: 20px; margin-top: 20px;">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="var(--shoppaton-gold)">
                            <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/>
                        </svg>
                        <span style="font-size: 12px;">SSL Encrypted Checkout</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="var(--shoppaton-gold)">
                            <path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2z"/>
                        </svg>
                        <span style="font-size: 12px;">Secured by Paystack</span>
                    </div>
                </div>

                <a href="<?php echo esc_url(get_permalink(get_page_by_path('checkout'))); ?>" class="shoppaton-btn shoppaton-btn-primary" style="width: 100%; margin-top: 20px;">
                    Proceed to Checkout
                </a>

                <p style="font-size: 11px; color: var(--shoppaton-text-muted); text-align: center; margin-top: 15px;">
                    By proceeding, you agree to our Terms of Service and Privacy Policy.
                </p>
            </div>
        </div>
        
        <?php else : ?>
        
        <div style="text-align: center; padding: 80px 20px;">
            <div style="width: 100px; height: 100px; background: var(--shoppaton-glass); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 30px; font-size: 40px;">
                🛒
            </div>
            <h2 style="color: var(--shoppaton-white); -webkit-text-fill-color: var(--shoppaton-white); margin-bottom: 15px;">Your cart is empty</h2>
            <p style="color: var(--shoppaton-text-muted); margin-bottom: 30px;">
                Looks like you haven't added any products to your cart yet.
            </p>
            <a href="<?php echo esc_url(get_permalink(get_page_by_path('shop'))); ?>" class="shoppaton-btn shoppaton-btn-primary">
                Start Shopping
            </a>
        </div>
        
        <?php endif; ?>
    </div>
</div>

<?php include SHOPPATON_PLUGIN_DIR . 'templates/partials/footer.php'; ?>
