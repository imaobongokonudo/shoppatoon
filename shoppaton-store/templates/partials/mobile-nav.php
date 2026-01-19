<?php
/**
 * Mobile Navigation Template
 *
 * @package ShoppatonStore
 */

if (!defined('ABSPATH')) {
    exit;
}

$cart_count = Shoppaton_Cart::instance()->get_count();
$current_page = get_query_var('pagename');
?>
<nav class="shoppaton-mobile-bottom-nav">
    <div class="shoppaton-mobile-nav-items">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="shoppaton-mobile-nav-item <?php echo is_front_page() ? 'active' : ''; ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
            <span>Home</span>
        </a>
        
        <a href="<?php echo esc_url(get_permalink(get_page_by_path('shop'))); ?>" class="shoppaton-mobile-nav-item <?php echo is_page('shop') ? 'active' : ''; ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                <line x1="3" y1="6" x2="21" y2="6"/>
                <path d="M16 10a4 4 0 0 1-8 0"/>
            </svg>
            <span>Shop</span>
        </a>
        
        <a href="<?php echo esc_url(get_permalink(get_page_by_path('cart'))); ?>" class="shoppaton-mobile-nav-item <?php echo is_page('cart') ? 'active' : ''; ?>" style="position: relative;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="9" cy="21" r="1"/>
                <circle cx="20" cy="21" r="1"/>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
            </svg>
            <?php if ($cart_count > 0) : ?>
            <span class="shoppaton-cart-count" style="position: absolute; top: -5px; right: 50%; transform: translateX(15px);"><?php echo esc_html($cart_count); ?></span>
            <?php endif; ?>
            <span>Cart</span>
        </a>
        
        <a href="<?php echo esc_url(get_permalink(get_page_by_path('my-account'))); ?>" class="shoppaton-mobile-nav-item <?php echo is_page('my-account') ? 'active' : ''; ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                <circle cx="12" cy="7" r="4"/>
            </svg>
            <span>Profile</span>
        </a>
    </div>
</nav>
