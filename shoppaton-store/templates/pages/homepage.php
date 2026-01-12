<?php
/**
 * Homepage Template
 *
 * @package ShoppatonStore
 */

if (!defined('ABSPATH')) {
    exit;
}

$products = Shoppaton_Products::instance();
$featured_products = $products->get_products(array('featured' => true, 'limit' => 8));
$best_sellers = $products->get_products(array('best_seller' => true, 'limit' => 8));
$all_products = $products->get_products(array('limit' => 8));
$logo_url = SHOPPATON_ASSETS_URL . 'images/logo.png';
?>

<?php include SHOPPATON_PLUGIN_DIR . 'templates/partials/header.php'; ?>

<!-- Hero Section -->
<?php include SHOPPATON_PLUGIN_DIR . 'templates/partials/hero.php'; ?>

<!-- About Section -->
<section class="shoppaton-section" data-section="About">
    <div class="shoppaton-container">
        <div class="shoppaton-about-grid">
            <div class="shoppaton-about-image shoppaton-animate animate-on-scroll shoppaton-fade-in-left">
                <div class="shoppaton-image-placeholder" style="aspect-ratio: 4/3;">
                    <span>About Us Image<br>Upload in Admin</span>
                </div>
            </div>
            <div class="shoppaton-about-content shoppaton-animate animate-on-scroll shoppaton-fade-in-right">
                <span class="shoppaton-section-subtitle">About Shoppaton</span>
                <h2 class="shoppaton-section-title">Your Trusted Skincare Partner Since 2022</h2>
                <p>
                    At Shoppaton Store, we believe everyone deserves to feel confident in their skin. 
                    Based in Lagos, Nigeria, we've been curating premium skincare essentials that deliver 
                    real results for our valued customers.
                </p>
                <ul class="shoppaton-about-list">
                    <li>100% Authentic Products</li>
                    <li>Expert Skincare Recommendations</li>
                    <li>Fast & Reliable Delivery Across Nigeria</li>
                    <li>Dedicated Customer Support</li>
                </ul>
                <a href="<?php echo esc_url(get_permalink(get_page_by_path('about-us'))); ?>" class="shoppaton-btn shoppaton-btn-primary">
                    Learn More About Us
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products Section -->
<section class="shoppaton-section" data-section="Featured Products">
    <div class="shoppaton-container">
        <div class="shoppaton-section-header">
            <span class="shoppaton-section-subtitle">Handpicked For You</span>
            <h2 class="shoppaton-section-title">Featured Products</h2>
            <p class="shoppaton-section-desc">
                Discover our carefully selected premium skincare products loved by our customers.
            </p>
        </div>
        
        <div class="shoppaton-products-grid">
            <?php 
            $display_products = !empty($featured_products) ? $featured_products : $all_products;
            foreach ($display_products as $product) : 
            ?>
                <?php include SHOPPATON_PLUGIN_DIR . 'templates/partials/product-card.php'; ?>
            <?php endforeach; ?>
        </div>
        
        <div style="text-align: center; margin-top: 40px;">
            <a href="<?php echo esc_url(get_permalink(get_page_by_path('shop'))); ?>" class="shoppaton-btn shoppaton-btn-secondary">
                View All Products
            </a>
        </div>
    </div>
</section>

<!-- Best Sellers Section -->
<section class="shoppaton-section" data-section="Best Sellers" style="background: var(--shoppaton-glass);">
    <div class="shoppaton-container">
        <div class="shoppaton-section-header">
            <span class="shoppaton-section-subtitle">Customer Favorites</span>
            <h2 class="shoppaton-section-title">Best Sellers</h2>
            <p class="shoppaton-section-desc">
                Our most loved products that have helped thousands achieve their skincare goals.
            </p>
        </div>
        
        <div class="shoppaton-products-grid">
            <?php 
            $display_products = !empty($best_sellers) ? $best_sellers : $all_products;
            foreach (array_slice($display_products, 0, 4) as $product) : 
            ?>
                <?php include SHOPPATON_PLUGIN_DIR . 'templates/partials/product-card.php'; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Trust Signals -->
<section class="shoppaton-section" data-section="Trust">
    <div class="shoppaton-container">
        <?php echo do_shortcode('[shoppaton_trust_signals]'); ?>
    </div>
</section>

<!-- Reviews Section -->
<section class="shoppaton-section" data-section="Reviews">
    <div class="shoppaton-container">
        <div class="shoppaton-section-header">
            <span class="shoppaton-section-subtitle">What Our Customers Say</span>
            <h2 class="shoppaton-section-title">Reviews from Happy Customers</h2>
            <p class="shoppaton-section-desc">
                Real feedback from our valued customers across Nigeria.
            </p>
        </div>
        
        <?php echo do_shortcode('[shoppaton_reviews]'); ?>
        
        <div style="text-align: center; margin-top: 30px;">
            <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMjAiIGhlaWdodD0iMzAiIHZpZXdCb3g9IjAgMCAxMjAgMzAiPjx0ZXh0IHg9IjAiIHk9IjIwIiBmaWxsPSIjRkZGRkZGIiBmb250LWZhbWlseT0iQXJpYWwiIGZvbnQtc2l6ZT0iMTIiIG9wYWNpdHk9IjAuNiI+Q2VydGlmaWVkIFJldmlld3M8L3RleHQ+PC9zdmc+" alt="Certified Reviews" style="opacity: 0.6;">
        </div>
    </div>
</section>

<!-- Shipping & Delivery Info -->
<section class="shoppaton-section" data-section="Shipping" style="background: var(--shoppaton-glass);">
    <div class="shoppaton-container">
        <div class="shoppaton-section-header">
            <span class="shoppaton-section-subtitle">Fast & Reliable</span>
            <h2 class="shoppaton-section-title">Shipping & Returns</h2>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px;">
            <div class="shoppaton-glass-card" style="padding: 30px; text-align: center;">
                <div style="width: 60px; height: 60px; background: var(--shoppaton-gold); color: var(--shoppaton-black); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 24px;">
                    🚚
                </div>
                <h4 style="margin-bottom: 10px; color: var(--shoppaton-white); -webkit-text-fill-color: var(--shoppaton-white);">Fast Delivery</h4>
                <p style="color: var(--shoppaton-text-muted); font-size: 14px;">
                    Same-day delivery in Lagos. 2-5 days nationwide delivery across Nigeria.
                </p>
            </div>
            
            <div class="shoppaton-glass-card" style="padding: 30px; text-align: center;">
                <div style="width: 60px; height: 60px; background: var(--shoppaton-gold); color: var(--shoppaton-black); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 24px;">
                    ↩️
                </div>
                <h4 style="margin-bottom: 10px; color: var(--shoppaton-white); -webkit-text-fill-color: var(--shoppaton-white);">Easy Returns</h4>
                <p style="color: var(--shoppaton-text-muted); font-size: 14px;">
                    3-day return policy. Submit complaints within 3 days of purchase for hassle-free returns.
                </p>
            </div>
            
            <div class="shoppaton-glass-card" style="padding: 30px; text-align: center;">
                <div style="width: 60px; height: 60px; background: var(--shoppaton-gold); color: var(--shoppaton-black); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 24px;">
                    📦
                </div>
                <h4 style="margin-bottom: 10px; color: var(--shoppaton-white); -webkit-text-fill-color: var(--shoppaton-white);">Order Tracking</h4>
                <p style="color: var(--shoppaton-text-muted); font-size: 14px;">
                    Track your order in real-time. Get updates via email and WhatsApp.
                </p>
            </div>
        </div>
        
        <div style="text-align: center; margin-top: 40px;">
            <a href="<?php echo esc_url(get_permalink(get_page_by_path('shipping-returns'))); ?>" class="shoppaton-btn shoppaton-btn-secondary">
                View Shipping & Return Policy
            </a>
        </div>
    </div>
</section>

<!-- Track Order Section -->
<section class="shoppaton-section" data-section="Track">
    <div class="shoppaton-container">
        <div class="shoppaton-section-header">
            <span class="shoppaton-section-subtitle">Order Status</span>
            <h2 class="shoppaton-section-title">Track Your Order</h2>
            <p class="shoppaton-section-desc">
                Enter your order number and email to check the status of your order.
            </p>
        </div>
        
        <form class="shoppaton-contact-form" id="track-order-form" style="max-width: 500px;">
            <div class="shoppaton-form-group">
                <input type="text" name="order_number" class="shoppaton-form-input" placeholder="Order Number (e.g., SHP20240112XXXXX)" required>
            </div>
            <div class="shoppaton-form-group">
                <input type="text" name="identifier" class="shoppaton-form-input" placeholder="Email or Phone Number" required>
            </div>
            <button type="submit" class="shoppaton-btn shoppaton-btn-primary" style="width: 100%;">
                Track Order
            </button>
        </form>
    </div>
</section>

<!-- Contact Section -->
<section class="shoppaton-section" data-section="Contact" style="background: var(--shoppaton-glass);">
    <div class="shoppaton-container">
        <div class="shoppaton-section-header">
            <span class="shoppaton-section-subtitle">Get In Touch</span>
            <h2 class="shoppaton-section-title">Contact Us</h2>
            <p class="shoppaton-section-desc">
                Have a question? We'd love to hear from you. Send us a message and we'll respond as soon as possible.
            </p>
        </div>
        
        <?php echo do_shortcode('[shoppaton_contact_form]'); ?>
    </div>
</section>

<!-- Section Indicator -->
<div class="shoppaton-section-indicator">
    <div class="shoppaton-section-dots"></div>
</div>

<?php include SHOPPATON_PLUGIN_DIR . 'templates/partials/footer.php'; ?>
