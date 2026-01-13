<?php
/**
 * Header Template
 *
 * @package ShoppatonStore
 */

if (!defined('ABSPATH')) {
    exit;
}

$cart_count = Shoppaton_Cart::instance()->get_count();
$logo_url = SHOPPATON_ASSETS_URL . 'images/logo.png';
?>
<header class="shoppaton-header">
    <div class="shoppaton-container">
        <div class="shoppaton-header-inner">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="shoppaton-logo">
                <img src="<?php echo esc_url($logo_url); ?>" alt="Shoppaton Store">
            </a>

            <nav class="shoppaton-nav">
                <ul class="shoppaton-nav-menu">
                    <li><a href="<?php echo esc_url(home_url('/')); ?>" class="<?php echo is_front_page() ? 'active' : ''; ?>">Home</a></li>
                    <li><a href="<?php echo esc_url(get_permalink(get_page_by_path('shop'))); ?>" class="<?php echo is_page('shop') ? 'active' : ''; ?>">Shop</a></li>
                    <li><a href="<?php echo esc_url(get_permalink(get_page_by_path('about-us'))); ?>" class="<?php echo is_page('about-us') ? 'active' : ''; ?>">About Us</a></li>
                    <li><a href="<?php echo esc_url(get_permalink(get_page_by_path('faq'))); ?>" class="<?php echo is_page('faq') ? 'active' : ''; ?>">FAQ</a></li>
                    <li><a href="<?php echo esc_url(get_permalink(get_page_by_path('track-order'))); ?>" class="<?php echo is_page('track-order') ? 'active' : ''; ?>">Track Order</a></li>
                </ul>

                <div class="shoppaton-nav-icons">
                    <!-- Search -->
                    <div class="shoppaton-search-wrapper">
                        <button class="shoppaton-nav-icon shoppaton-search-toggle" aria-label="Search">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"/>
                                <path d="M21 21l-4.35-4.35"/>
                            </svg>
                        </button>
                        <div class="shoppaton-search-dropdown">
                            <form class="shoppaton-search-form" action="<?php echo esc_url(get_permalink(get_page_by_path('shop'))); ?>">
                                <input type="search" name="search" class="shoppaton-search-input" placeholder="Search products...">
                                <button type="submit" class="shoppaton-search-btn">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="11" cy="11" r="8"/>
                                        <path d="M21 21l-4.35-4.35"/>
                                    </svg>
                                </button>
                            </form>
                            <div class="shoppaton-search-results"></div>
                        </div>
                    </div>

                    <!-- Wishlist -->
                    <a href="<?php echo esc_url(get_permalink(get_page_by_path('wishlist'))); ?>" class="shoppaton-nav-icon" aria-label="Wishlist">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                        </svg>
                    </a>

                    <!-- Cart -->
                    <a href="<?php echo esc_url(get_permalink(get_page_by_path('cart'))); ?>" class="shoppaton-nav-icon" aria-label="Cart">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="9" cy="21" r="1"/>
                            <circle cx="20" cy="21" r="1"/>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                        </svg>
                        <span class="shoppaton-cart-count"><?php echo esc_html($cart_count); ?></span>
                    </a>

                    <!-- Account -->
                    <a href="<?php echo esc_url(get_permalink(get_page_by_path('my-account'))); ?>" class="shoppaton-nav-icon" aria-label="Account">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                    </a>

                    <!-- Shop Now Button -->
                    <a href="<?php echo esc_url(get_permalink(get_page_by_path('shop'))); ?>" class="shoppaton-btn shoppaton-btn-primary shoppaton-btn-animated">
                        Shop Now
                    </a>
                </div>

                <!-- Mobile Icons -->
                <div class="shoppaton-mobile-icons" style="display: none;">
                    <button class="shoppaton-nav-icon shoppaton-search-toggle-mobile" aria-label="Search">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"/>
                            <path d="M21 21l-4.35-4.35"/>
                        </svg>
                    </button>
                </div>

                <!-- Hamburger Menu -->
                <div class="shoppaton-hamburger">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </nav>
        </div>
    </div>
</header>

<!-- Mobile Menu -->
<div class="shoppaton-mobile-menu">
    <ul class="shoppaton-mobile-menu-list">
        <li><a href="<?php echo esc_url(home_url('/')); ?>">Home</a></li>
        <li><a href="<?php echo esc_url(get_permalink(get_page_by_path('shop'))); ?>">Shop</a></li>
        <li><a href="<?php echo esc_url(get_permalink(get_page_by_path('about-us'))); ?>">About Us</a></li>
        <li><a href="<?php echo esc_url(get_permalink(get_page_by_path('faq'))); ?>">FAQ</a></li>
        <li><a href="<?php echo esc_url(get_permalink(get_page_by_path('track-order'))); ?>">Track Order</a></li>
        <li><a href="<?php echo esc_url(get_permalink(get_page_by_path('shipping-returns'))); ?>">Shipping & Returns</a></li>
        <li><a href="<?php echo esc_url(get_permalink(get_page_by_path('cart'))); ?>">Cart (<?php echo esc_html($cart_count); ?>)</a></li>
        <li><a href="<?php echo esc_url(get_permalink(get_page_by_path('my-account'))); ?>">My Account</a></li>
    </ul>
</div>
