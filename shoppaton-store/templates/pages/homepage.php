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

// Get categories
global $wpdb;
$categories_table = $wpdb->prefix . 'shoppaton_categories';
$categories = $wpdb->get_results("SELECT * FROM {$categories_table} ORDER BY name ASC LIMIT 6");

// Get site media from options
$about_image = get_option('shoppaton_about_image', '');
$site_logo = get_option('shoppaton_logo', '');
?>

<?php include SHOPPATON_PLUGIN_DIR . 'templates/partials/header.php'; ?>

<!-- Hero Section -->
<?php include SHOPPATON_PLUGIN_DIR . 'templates/partials/hero.php'; ?>

<!-- About Section -->
<section class="shoppaton-section" data-section="About">
    <div class="shoppaton-container" style="max-width: 1200px; margin: 0 auto; padding: 0 20px; overflow-x: hidden;">
        <div class="shoppaton-section-header" style="text-align: center; margin-bottom: 40px;">
            <span class="shoppaton-section-subtitle">About Shoppaton</span>
            <h2 class="shoppaton-section-title">Your Trusted Skincare Partner Since 2022</h2>
        </div>
        
        <!-- Image placed directly under heading -->
        <div class="shoppaton-about-image-container shoppaton-animate animate-on-scroll shoppaton-fade-in-up" style="max-width: 600px; margin: 0 auto 40px; padding: 0 10px;">
            <?php if (!empty($about_image)) : ?>
            <img src="<?php echo esc_url($about_image); ?>" alt="About Shoppaton" style="width: 100%; aspect-ratio: 16/10; object-fit: cover; border-radius: var(--border-radius-lg);">
            <?php else : ?>
            <div class="shoppaton-image-placeholder" style="aspect-ratio: 16/10; max-width: 100%; border-radius: var(--border-radius-lg);">
                <span>About Us Image<br>Upload in Admin > Media</span>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Content below image -->
        <div class="shoppaton-about-content shoppaton-animate animate-on-scroll shoppaton-fade-in-up" style="max-width: 800px; margin: 0 auto; text-align: center; padding: 0 15px;">
            <p style="margin-bottom: 25px; font-size: 14px; line-height: 1.8; color: var(--shoppaton-text);">
                At Shoppaton Store, we believe everyone deserves to feel confident in their skin. 
                Based in Lagos, Nigeria, we've been curating premium skincare essentials that deliver 
                real results for our valued customers.
            </p>
            <ul class="shoppaton-about-list" style="display: inline-block; text-align: left; margin-bottom: 30px;">
                <li>100% Authentic Products</li>
                <li>Expert Skincare Recommendations</li>
                <li>Fast & Reliable Delivery Across Nigeria</li>
                <li>Dedicated Customer Support</li>
            </ul>
            <div>
                <a href="<?php echo esc_url(get_permalink(get_page_by_path('about-us'))); ?>" class="shoppaton-btn shoppaton-btn-primary">
                    Learn More About Us
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="shoppaton-section" data-section="Categories" style="background: var(--shoppaton-glass);">
    <div class="shoppaton-container">
        <div class="shoppaton-section-header">
            <span class="shoppaton-section-subtitle">Browse By Category</span>
            <h2 class="shoppaton-section-title">Shop By Categories</h2>
            <p class="shoppaton-section-desc">
                Find the perfect skincare products for your needs. Browse our carefully curated categories.
            </p>
        </div>
        
        <div class="shoppaton-categories-grid">
            <?php if (!empty($categories)) : ?>
                <?php foreach ($categories as $category) : ?>
                <a href="<?php echo esc_url(add_query_arg('category', $category->id, get_permalink(get_page_by_path('shop')))); ?>" class="shoppaton-category-card shoppaton-glass-card shoppaton-animate animate-on-scroll shoppaton-fade-in-up">
                    <div class="shoppaton-category-image">
                        <?php if (!empty($category->image)) : ?>
                            <img src="<?php echo esc_url($category->image); ?>" alt="<?php echo esc_attr($category->name); ?>">
                        <?php else : ?>
                            <div class="shoppaton-image-placeholder" style="aspect-ratio: 1/1; border-radius: 50%;">
                                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                                </svg>
                            </div>
                        <?php endif; ?>
                    </div>
                    <h3 class="shoppaton-category-name"><?php echo esc_html($category->name); ?></h3>
                </a>
                <?php endforeach; ?>
            <?php else : ?>
                <!-- Default categories when none exist -->
                <a href="<?php echo esc_url(get_permalink(get_page_by_path('shop'))); ?>" class="shoppaton-category-card shoppaton-glass-card shoppaton-animate animate-on-scroll shoppaton-fade-in-up">
                    <div class="shoppaton-category-image">
                        <div class="shoppaton-image-placeholder" style="aspect-ratio: 1/1; border-radius: 50%;">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <circle cx="12" cy="12" r="10"/>
                                <path d="M8 14s1.5 2 4 2 4-2 4-2"/>
                                <line x1="9" y1="9" x2="9.01" y2="9"/>
                                <line x1="15" y1="9" x2="15.01" y2="9"/>
                            </svg>
                        </div>
                    </div>
                    <h3 class="shoppaton-category-name">Face Care</h3>
                </a>
                <a href="<?php echo esc_url(get_permalink(get_page_by_path('shop'))); ?>" class="shoppaton-category-card shoppaton-glass-card shoppaton-animate animate-on-scroll shoppaton-fade-in-up">
                    <div class="shoppaton-category-image">
                        <div class="shoppaton-image-placeholder" style="aspect-ratio: 1/1; border-radius: 50%;">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2z"/>
                            </svg>
                        </div>
                    </div>
                    <h3 class="shoppaton-category-name">Body Care</h3>
                </a>
                <a href="<?php echo esc_url(get_permalink(get_page_by_path('shop'))); ?>" class="shoppaton-category-card shoppaton-glass-card shoppaton-animate animate-on-scroll shoppaton-fade-in-up">
                    <div class="shoppaton-category-image">
                        <div class="shoppaton-image-placeholder" style="aspect-ratio: 1/1; border-radius: 50%;">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M5 3a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2H5z"/>
                            </svg>
                        </div>
                    </div>
                    <h3 class="shoppaton-category-name">Serums</h3>
                </a>
                <a href="<?php echo esc_url(get_permalink(get_page_by_path('shop'))); ?>" class="shoppaton-category-card shoppaton-glass-card shoppaton-animate animate-on-scroll shoppaton-fade-in-up">
                    <div class="shoppaton-category-image">
                        <div class="shoppaton-image-placeholder" style="aspect-ratio: 1/1; border-radius: 50%;">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <circle cx="12" cy="12" r="5"/>
                                <line x1="12" y1="1" x2="12" y2="3"/>
                                <line x1="12" y1="21" x2="12" y2="23"/>
                                <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/>
                                <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
                            </svg>
                        </div>
                    </div>
                    <h3 class="shoppaton-category-name">Sunscreen</h3>
                </a>
                <a href="<?php echo esc_url(get_permalink(get_page_by_path('shop'))); ?>" class="shoppaton-category-card shoppaton-glass-card shoppaton-animate animate-on-scroll shoppaton-fade-in-up">
                    <div class="shoppaton-category-image">
                        <div class="shoppaton-image-placeholder" style="aspect-ratio: 1/1; border-radius: 50%;">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                        </div>
                    </div>
                    <h3 class="shoppaton-category-name">Moisturizers</h3>
                </a>
                <a href="<?php echo esc_url(get_permalink(get_page_by_path('shop'))); ?>" class="shoppaton-category-card shoppaton-glass-card shoppaton-animate animate-on-scroll shoppaton-fade-in-up">
                    <div class="shoppaton-category-image">
                        <div class="shoppaton-image-placeholder" style="aspect-ratio: 1/1; border-radius: 50%;">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/>
                            </svg>
                        </div>
                    </div>
                    <h3 class="shoppaton-category-name">Cleansers</h3>
                </a>
            <?php endif; ?>
        </div>
        
        <div style="text-align: center; margin-top: 40px;">
            <a href="<?php echo esc_url(get_permalink(get_page_by_path('categories'))); ?>" class="shoppaton-btn shoppaton-btn-secondary">
                View All Categories
            </a>
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
                <div style="width: 60px; height: 60px; background: var(--shoppaton-gold); color: var(--shoppaton-black); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M20 8h-3V4H3c-1.1 0-2 .9-2 2v11h2c0 1.66 1.34 3 3 3s3-1.34 3-3h6c0 1.66 1.34 3 3 3s3-1.34 3-3h2v-5l-3-4zM6 18.5c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm13.5-9l1.96 2.5H17V9.5h2.5zm-1.5 9c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/>
                    </svg>
                </div>
                <h4 style="margin-bottom: 10px; color: var(--shoppaton-white); -webkit-text-fill-color: var(--shoppaton-white);">Fast Delivery</h4>
                <p style="color: var(--shoppaton-text-muted); font-size: 14px;">
                    Same-day delivery in Lagos. 2-5 days nationwide delivery across Nigeria.
                </p>
            </div>
            
            <div class="shoppaton-glass-card" style="padding: 30px; text-align: center;">
                <div style="width: 60px; height: 60px; background: var(--shoppaton-gold); color: var(--shoppaton-black); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 5V1L7 6l5 5V7c3.31 0 6 2.69 6 6s-2.69 6-6 6-6-2.69-6-6H4c0 4.42 3.58 8 8 8s8-3.58 8-8-3.58-8-8-8z"/>
                    </svg>
                </div>
                <h4 style="margin-bottom: 10px; color: var(--shoppaton-white); -webkit-text-fill-color: var(--shoppaton-white);">Easy Returns</h4>
                <p style="color: var(--shoppaton-text-muted); font-size: 14px;">
                    3-day return policy. Submit complaints within 3 days of purchase for hassle-free returns.
                </p>
            </div>
            
            <div class="shoppaton-glass-card" style="padding: 30px; text-align: center;">
                <div style="width: 60px; height: 60px; background: var(--shoppaton-gold); color: var(--shoppaton-black); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                    </svg>
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
                <label class="shoppaton-form-label" style="color: var(--shoppaton-gold);">Order Number *</label>
                <input type="text" name="order_number" class="shoppaton-form-input" placeholder="e.g., SHP20240112XXXXX" required>
            </div>
            <div class="shoppaton-form-group">
                <label class="shoppaton-form-label" style="color: var(--shoppaton-gold);">Email or Phone Number *</label>
                <input type="text" name="identifier" class="shoppaton-form-input" placeholder="Email or phone used for order" required>
            </div>
            <button type="submit" class="shoppaton-btn shoppaton-btn-primary" style="width: 100%;">
                Track Order
            </button>
        </form>
    </div>
</section>

<!-- Contact Section -->
<section class="shoppaton-section" data-section="Contact" style="background: var(--shoppaton-glass); margin-bottom: 80px;">
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
