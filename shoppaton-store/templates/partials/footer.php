<?php
/**
 * Footer Template
 *
 * @package ShoppatonStore
 */

if (!defined('ABSPATH')) {
    exit;
}

$settings = Shoppaton_Settings::instance();
$logo_url = SHOPPATON_ASSETS_URL . 'images/logo.png';
?>
<footer class="shoppaton-footer">
    <div class="shoppaton-container">
        <div class="shoppaton-footer-grid">
            <!-- About Column -->
            <div class="shoppaton-footer-about">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="shoppaton-footer-logo shoppaton-logo-sparkle-enabled">
                    <img src="<?php echo esc_url($logo_url); ?>" alt="Shoppaton Store">
                </a>
                <p class="shoppaton-footer-desc">
                    Shoppaton Store is your premier destination for premium skincare essentials in Lagos, Nigeria. 
                    We've been helping customers achieve radiant, healthy skin since 2022.
                </p>
                <div class="shoppaton-footer-social">
                    <?php if ($settings->get('instagram')) : ?>
                    <a href="<?php echo esc_url($settings->get('instagram')); ?>" target="_blank" rel="noopener" class="shoppaton-social-link" aria-label="Instagram">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                    </a>
                    <?php endif; ?>
                    <?php if ($settings->get('facebook')) : ?>
                    <a href="<?php echo esc_url($settings->get('facebook')); ?>" target="_blank" rel="noopener" class="shoppaton-social-link" aria-label="Facebook">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/>
                        </svg>
                    </a>
                    <?php endif; ?>
                    <?php if ($settings->get('tiktok')) : ?>
                    <a href="<?php echo esc_url($settings->get('tiktok')); ?>" target="_blank" rel="noopener" class="shoppaton-social-link" aria-label="TikTok">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/>
                        </svg>
                    </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="shoppaton-footer-links-column">
                <h4 class="shoppaton-footer-title">Quick Links</h4>
                <ul class="shoppaton-footer-links">
                    <li><a href="<?php echo esc_url(home_url('/')); ?>">Home</a></li>
                    <li><a href="<?php echo esc_url(get_permalink(get_page_by_path('shop'))); ?>">Shop</a></li>
                    <li><a href="<?php echo esc_url(get_permalink(get_page_by_path('about-us'))); ?>">About Us</a></li>
                    <li><a href="<?php echo esc_url(get_permalink(get_page_by_path('faq'))); ?>">FAQ</a></li>
                    <li><a href="<?php echo esc_url(get_permalink(get_page_by_path('track-order'))); ?>">Track Order</a></li>
                </ul>
            </div>

            <!-- Customer Service -->
            <div class="shoppaton-footer-links-column">
                <h4 class="shoppaton-footer-title">Customer Service</h4>
                <ul class="shoppaton-footer-links">
                    <li><a href="<?php echo esc_url(get_permalink(get_page_by_path('shipping-returns'))); ?>">Shipping & Returns</a></li>
                    <li><a href="<?php echo esc_url(get_permalink(get_page_by_path('cart'))); ?>">Shopping Cart</a></li>
                    <li><a href="<?php echo esc_url(get_permalink(get_page_by_path('wishlist'))); ?>">Wishlist</a></li>
                    <li><a href="<?php echo esc_url(get_permalink(get_page_by_path('my-account'))); ?>">My Account</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div class="shoppaton-footer-contact-column">
                <h4 class="shoppaton-footer-title">Contact Us</h4>
                <ul class="shoppaton-footer-contact">
                    <li>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                        </svg>
                        <span><?php echo esc_html($settings->get('contact_email')); ?></span>
                    </li>
                    <li>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                        </svg>
                        <span><?php echo esc_html($settings->get('whatsapp_number')); ?></span>
                    </li>
                    <li>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                        </svg>
                        <span>Lagos, Nigeria</span>
                    </li>
                </ul>

                <!-- Footer Search -->
                <div class="shoppaton-footer-search">
                    <form class="shoppaton-search-form" action="<?php echo esc_url(get_permalink(get_page_by_path('shop'))); ?>">
                        <input type="search" name="search" class="shoppaton-search-input" placeholder="Search products...">
                        <button type="submit" class="shoppaton-search-btn">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"/>
                                <path d="M21 21l-4.35-4.35"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="shoppaton-footer-bottom">
            <p class="shoppaton-footer-copyright">
                &copy; <?php echo date('Y'); ?> Shoppaton Store. All rights reserved.
            </p>
            <div class="shoppaton-footer-payments">
                <span style="color: var(--shoppaton-text-muted); font-size: 12px;">Secured by</span>
                <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI4MCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDgwIDI0Ij48dGV4dCB4PSIwIiB5PSIxNyIgZmlsbD0iIzAwQzNGNyIgZm9udC1mYW1pbHk9IkFyaWFsIiBmb250LXNpemU9IjE0IiBmb250LXdlaWdodD0iYm9sZCI+UGF5c3RhY2s8L3RleHQ+PC9zdmc+" alt="Paystack">
            </div>
        </div>
    </div>
</footer>
