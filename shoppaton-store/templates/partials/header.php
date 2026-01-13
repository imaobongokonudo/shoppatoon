<?php
/**
 * Header Template - Rebuilt from scratch
 *
 * @package ShoppatonStore
 */

if (!defined('ABSPATH')) {
    exit;
}

$cart_count = Shoppaton_Cart::instance()->get_count();
$logo_url = SHOPPATON_ASSETS_URL . 'images/logo.png';
?>
<style>
/* Clean Fixed Header - 60px height */
.shoppaton-header {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 60px;
    background: rgba(10, 10, 10, 0.98);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border-bottom: 1px solid rgba(212, 175, 55, 0.2);
    z-index: 1000;
}
body { padding-top: 60px; }

.shoppaton-header-inner {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 20px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

/* Logo - Compact */
.shoppaton-header .shoppaton-logo img {
    height: 36px;
    width: auto;
}

/* Desktop Nav */
.shoppaton-nav-desktop {
    display: flex;
    align-items: center;
    gap: 25px;
}
.shoppaton-nav-desktop a {
    color: #D4AF37;
    font-size: 13px;
    font-weight: 500;
    text-decoration: none !important;
    transition: 0.2s;
}
.shoppaton-nav-desktop a:hover,
.shoppaton-nav-desktop a.active {
    color: #F5E6C8;
}

/* Nav Icons */
.shoppaton-nav-actions {
    display: flex;
    align-items: center;
    gap: 15px;
}
.shoppaton-icon-btn {
    background: none;
    border: none;
    padding: 5px;
    cursor: pointer;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: 0.2s;
}
.shoppaton-icon-btn:hover { color: #D4AF37; }
.shoppaton-icon-btn svg { width: 18px; height: 18px; }

/* Cart Badge */
.shoppaton-cart-badge {
    position: relative;
}
.shoppaton-cart-badge .badge {
    position: absolute;
    top: -6px;
    right: -6px;
    background: #D4AF37;
    color: #0a0a0a;
    font-size: 9px;
    font-weight: 700;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Shop Button */
.shoppaton-header .shop-btn {
    background: linear-gradient(135deg, #D4AF37 0%, #F5E6C8 50%, #D4AF37 100%);
    color: #0a0a0a;
    padding: 8px 16px;
    border-radius: 10px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    text-decoration: none !important;
    transition: 0.3s;
}
.shoppaton-header .shop-btn:hover {
    box-shadow: 0 0 20px rgba(212, 175, 55, 0.5);
    transform: translateY(-1px);
}

/* Hamburger */
.shoppaton-hamburger {
    display: none;
    flex-direction: column;
    gap: 4px;
    padding: 8px;
    cursor: pointer;
    background: none;
    border: none;
}
.shoppaton-hamburger span {
    width: 22px;
    height: 2px;
    background: #D4AF37;
    transition: 0.2s;
}

/* Mobile */
@media (max-width: 991px) {
    .shoppaton-nav-desktop { display: none; }
    .shoppaton-hamburger { display: flex; }
}
@media (max-width: 768px) {
    .shoppaton-header .shop-btn { display: none; }
}
</style>

<header class="shoppaton-header">
    <div class="shoppaton-header-inner">
        <!-- Logo -->
        <a href="<?php echo esc_url(home_url('/')); ?>" class="shoppaton-logo">
            <img src="<?php echo esc_url($logo_url); ?>" alt="Shoppaton Store">
        </a>

        <!-- Desktop Navigation -->
        <nav class="shoppaton-nav-desktop">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="<?php echo is_front_page() ? 'active' : ''; ?>">Home</a>
            <a href="<?php echo esc_url(get_permalink(get_page_by_path('shop'))); ?>" class="<?php echo is_page('shop') ? 'active' : ''; ?>">Shop</a>
            <a href="<?php echo esc_url(get_permalink(get_page_by_path('about-us'))); ?>" class="<?php echo is_page('about-us') ? 'active' : ''; ?>">About</a>
            <a href="<?php echo esc_url(get_permalink(get_page_by_path('faq'))); ?>" class="<?php echo is_page('faq') ? 'active' : ''; ?>">FAQ</a>
            <a href="<?php echo esc_url(get_permalink(get_page_by_path('track-order'))); ?>" class="<?php echo is_page('track-order') ? 'active' : ''; ?>">Track Order</a>
        </nav>

        <!-- Actions -->
        <div class="shoppaton-nav-actions">
            <!-- Search Icon -->
            <button class="shoppaton-icon-btn shoppaton-search-toggle" aria-label="Search">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
                </svg>
            </button>

            <!-- Wishlist -->
            <a href="<?php echo esc_url(get_permalink(get_page_by_path('wishlist'))); ?>" class="shoppaton-icon-btn" aria-label="Wishlist">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                </svg>
            </a>

            <!-- Cart -->
            <a href="<?php echo esc_url(get_permalink(get_page_by_path('cart'))); ?>" class="shoppaton-icon-btn shoppaton-cart-badge" aria-label="Cart">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                </svg>
                <?php if ($cart_count > 0) : ?>
                <span class="badge"><?php echo esc_html($cart_count); ?></span>
                <?php endif; ?>
            </a>

            <!-- Account -->
            <a href="<?php echo esc_url(get_permalink(get_page_by_path('my-account'))); ?>" class="shoppaton-icon-btn" aria-label="Account">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                </svg>
            </a>

            <!-- Shop Now Button -->
            <a href="<?php echo esc_url(get_permalink(get_page_by_path('shop'))); ?>" class="shop-btn">Shop Now</a>

            <!-- Hamburger for Mobile -->
            <button class="shoppaton-hamburger" aria-label="Menu">
                <span></span><span></span><span></span>
            </button>
        </div>
    </div>
</header>

<!-- Search Overlay -->
<div class="shoppaton-search-overlay">
    <button class="shoppaton-search-overlay-close">&times;</button>
    <div class="shoppaton-search-overlay-content">
        <form class="shoppaton-search-overlay-form" action="<?php echo esc_url(home_url('/')); ?>" method="get">
            <input type="text" name="s" placeholder="Search products..." autocomplete="off">
            <input type="hidden" name="post_type" value="product">
            <button type="submit">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
                </svg>
            </button>
        </form>
    </div>
</div>

<!-- Mobile Menu -->
<div class="shoppaton-mobile-menu">
    <!-- Mobile menu icons -->
    <div style="display: flex; justify-content: space-around; padding: 15px 0 25px; border-bottom: 1px solid rgba(212, 175, 55, 0.2); margin-bottom: 15px;">
        <a href="<?php echo esc_url(get_permalink(get_page_by_path('shop'))); ?>" style="display: flex; flex-direction: column; align-items: center; gap: 5px; color: #D4AF37; text-decoration: none; font-size: 10px;">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
            Shop
        </a>
        <button class="shoppaton-search-toggle" style="display: flex; flex-direction: column; align-items: center; gap: 5px; color: #D4AF37; background: none; border: none; font-size: 10px; cursor: pointer;">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
            Search
        </button>
        <a href="<?php echo esc_url(get_permalink(get_page_by_path('wishlist'))); ?>" style="display: flex; flex-direction: column; align-items: center; gap: 5px; color: #D4AF37; text-decoration: none; font-size: 10px;">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
            Wishlist
        </a>
        <a href="<?php echo esc_url(get_permalink(get_page_by_path('my-account'))); ?>" style="display: flex; flex-direction: column; align-items: center; gap: 5px; color: #D4AF37; text-decoration: none; font-size: 10px;">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            Account
        </a>
    </div>
    <ul class="shoppaton-mobile-menu-list">
        <li><a href="<?php echo esc_url(home_url('/')); ?>">Home</a></li>
        <li><a href="<?php echo esc_url(get_permalink(get_page_by_path('shop'))); ?>">Shop</a></li>
        <li><a href="<?php echo esc_url(get_permalink(get_page_by_path('about-us'))); ?>">About Us</a></li>
        <li><a href="<?php echo esc_url(get_permalink(get_page_by_path('faq'))); ?>">FAQ</a></li>
        <li><a href="<?php echo esc_url(get_permalink(get_page_by_path('track-order'))); ?>">Track Order</a></li>
        <li><a href="<?php echo esc_url(get_permalink(get_page_by_path('shipping-returns'))); ?>">Shipping & Returns</a></li>
        <li><a href="<?php echo esc_url(get_permalink(get_page_by_path('cart'))); ?>">Cart</a></li>
        <li><a href="<?php echo esc_url(get_permalink(get_page_by_path('my-account'))); ?>">My Account</a></li>
    </ul>
    <!-- Shop Now Button in Mobile Menu -->
    <div style="padding: 20px 0;">
        <a href="<?php echo esc_url(get_permalink(get_page_by_path('shop'))); ?>" style="display: block; text-align: center; background: linear-gradient(135deg, #D4AF37 0%, #F5E6C8 50%, #D4AF37 100%); color: #0a0a0a; padding: 12px 20px; border-radius: 10px; font-size: 12px; font-weight: 600; text-transform: uppercase; text-decoration: none;">Shop Now</a>
    </div>
</div>
