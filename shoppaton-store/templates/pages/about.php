<?php
/**
 * About Us Page Template
 *
 * @package ShoppatonStore
 */

if (!defined('ABSPATH')) {
    exit;
}

$logo_url = SHOPPATON_ASSETS_URL . 'images/logo.png';
?>

<?php include SHOPPATON_PLUGIN_DIR . 'templates/partials/header.php'; ?>

<div class="shoppaton-about-page" style="padding-top: 120px; padding-bottom: 80px; min-height: 100vh; padding-left: 15px; padding-right: 15px;">
    <div class="shoppaton-container" style="max-width: 100%; overflow-x: hidden;">
        <!-- Page Header -->
        <div class="shoppaton-page-header" style="text-align: center; margin-bottom: 60px;">
            <span class="shoppaton-section-subtitle">Our Story</span>
            <h1 class="shoppaton-gold-text-animated">About Shoppaton</h1>
            <p style="color: var(--shoppaton-text-muted); max-width: 600px; margin: 20px auto 0;">
                Your trusted destination for premium skincare essentials in Lagos, Nigeria. 
                Helping you achieve radiant, healthy skin since 2022.
            </p>
        </div>

        <!-- About Section -->
        <section class="shoppaton-section" style="padding-top: 0;">
            <div class="shoppaton-about-grid" style="max-width: 100%; overflow-x: hidden;">
                <div class="shoppaton-about-image shoppaton-animate animate-on-scroll shoppaton-fade-in-left" style="max-width: 100%;">
                    <div class="shoppaton-image-placeholder" style="aspect-ratio: 4/3; max-width: 100%;">
                        <span>About Us Image<br>Upload in Admin</span>
                    </div>
                </div>
                <div class="shoppaton-about-content shoppaton-animate animate-on-scroll shoppaton-fade-in-right">
                    <h2>Why Choose Shoppaton?</h2>
                    <p style="margin-bottom: 20px;">
                        At Shoppaton Store, we believe everyone deserves to feel confident in their skin. 
                        Since our founding in <strong>2022</strong>, we've been on a mission to bring premium, 
                        authentic skincare products to Nigeria.
                    </p>
                    <p style="margin-bottom: 20px;">
                        We carefully curate our collection, selecting only products that deliver real results. 
                        Our team of skincare enthusiasts personally tests every product before adding it to our store.
                    </p>
                    <ul class="shoppaton-about-list">
                        <li>100% Authentic & Original Products</li>
                        <li>Expert Skincare Recommendations</li>
                        <li>Fast & Reliable Delivery Across Nigeria</li>
                        <li>Dedicated Customer Support Team</li>
                        <li>Secure Payment Options</li>
                        <li>3-Day Easy Return Policy</li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- Our Values -->
        <section class="shoppaton-section">
            <div class="shoppaton-section-header">
                <span class="shoppaton-section-subtitle">What We Stand For</span>
                <h2 class="shoppaton-section-title">Our Values</h2>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px;">
                <div class="shoppaton-glass-card shoppaton-animate animate-on-scroll shoppaton-fade-in-up" style="padding: 40px; text-align: center;">
                    <div style="width: 70px; height: 70px; background: var(--shoppaton-gold-gradient); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 25px;">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#0a0a0a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                            <path d="M9 12l2 2 4-4"/>
                        </svg>
                    </div>
                    <h3 style="color: var(--shoppaton-white); -webkit-text-fill-color: var(--shoppaton-white); margin-bottom: 15px;">Authenticity</h3>
                    <p style="color: var(--shoppaton-text-muted);">
                        We source only genuine products directly from authorized distributors. 
                        No fakes, no counterfeits - just authentic skincare that works.
                    </p>
                </div>
                
                <div class="shoppaton-glass-card shoppaton-animate animate-on-scroll shoppaton-fade-in-up shoppaton-delay-2" style="padding: 40px; text-align: center;">
                    <div style="width: 70px; height: 70px; background: var(--shoppaton-gold-gradient); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 25px;">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#0a0a0a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/>
                        </svg>
                    </div>
                    <h3 style="color: var(--shoppaton-white); -webkit-text-fill-color: var(--shoppaton-white); margin-bottom: 15px;">Quality</h3>
                    <p style="color: var(--shoppaton-text-muted);">
                        Every product in our store is carefully selected for its quality and effectiveness. 
                        We believe in offering only the best for your skin.
                    </p>
                </div>
                
                <div class="shoppaton-glass-card shoppaton-animate animate-on-scroll shoppaton-fade-in-up shoppaton-delay-4" style="padding: 40px; text-align: center;">
                    <div style="width: 70px; height: 70px; background: var(--shoppaton-gold-gradient); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 25px;">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#0a0a0a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                    </div>
                    <h3 style="color: var(--shoppaton-white); -webkit-text-fill-color: var(--shoppaton-white); margin-bottom: 15px;">Trust</h3>
                    <p style="color: var(--shoppaton-text-muted);">
                        Building lasting relationships with our customers through transparency, 
                        reliability, and exceptional service.
                    </p>
                </div>
            </div>
        </section>

        <!-- Journey Timeline -->
        <section class="shoppaton-section" style="background: var(--shoppaton-glass); border-radius: var(--border-radius-xl); padding: 60px 40px;">
            <div class="shoppaton-section-header">
                <span class="shoppaton-section-subtitle">Our Journey</span>
                <h2 class="shoppaton-section-title">The Shoppaton Story</h2>
            </div>
            
            <div style="max-width: 800px; margin: 0 auto;">
                <div style="display: flex; gap: 30px; margin-bottom: 40px;">
                    <div style="flex-shrink: 0;">
                        <div style="width: 60px; height: 60px; background: var(--shoppaton-gold); color: var(--shoppaton-black); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                            2022
                        </div>
                    </div>
                    <div>
                        <h4 style="color: var(--shoppaton-white); -webkit-text-fill-color: var(--shoppaton-white); margin-bottom: 10px;">The Beginning</h4>
                        <p style="color: var(--shoppaton-text-muted);">
                            Shoppaton Store was founded in Lagos with a simple mission: 
                            to make authentic, premium skincare accessible to everyone in Nigeria.
                        </p>
                    </div>
                </div>
                
                <div style="display: flex; gap: 30px; margin-bottom: 40px;">
                    <div style="flex-shrink: 0;">
                        <div style="width: 60px; height: 60px; background: var(--shoppaton-gold); color: var(--shoppaton-black); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                            2023
                        </div>
                    </div>
                    <div>
                        <h4 style="color: var(--shoppaton-white); -webkit-text-fill-color: var(--shoppaton-white); margin-bottom: 10px;">Growing Together</h4>
                        <p style="color: var(--shoppaton-text-muted);">
                            Expanded our product range and delivery coverage. Served thousands of happy customers 
                            across Lagos and other states in Nigeria.
                        </p>
                    </div>
                </div>
                
                <div style="display: flex; gap: 30px;">
                    <div style="flex-shrink: 0;">
                        <div style="width: 60px; height: 60px; background: var(--shoppaton-gold); color: var(--shoppaton-black); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                            Now
                        </div>
                    </div>
                    <div>
                        <h4 style="color: var(--shoppaton-white); -webkit-text-fill-color: var(--shoppaton-white); margin-bottom: 10px;">The Future</h4>
                        <p style="color: var(--shoppaton-text-muted);">
                            Continuing to grow and serve our amazing community. Our commitment to quality, 
                            authenticity, and customer satisfaction remains stronger than ever.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="shoppaton-section" style="text-align: center;">
            <h2 style="margin-bottom: 20px;">Ready to Transform Your Skin?</h2>
            <p style="color: var(--shoppaton-text-muted); max-width: 500px; margin: 0 auto 30px;">
                Explore our collection of premium skincare products and start your journey to radiant, healthy skin today.
            </p>
            <div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap;">
                <a href="<?php echo esc_url(get_permalink(get_page_by_path('shop'))); ?>" class="shoppaton-btn shoppaton-btn-primary">
                    Shop Now
                </a>
                <a href="<?php echo esc_url(Shoppaton_Settings::instance()->get_whatsapp_link('Hello, I would like to know more about Shoppaton Store.')); ?>" target="_blank" class="shoppaton-btn shoppaton-btn-secondary">
                    Chat With Us
                </a>
            </div>
        </section>
    </div>
</div>

<?php include SHOPPATON_PLUGIN_DIR . 'templates/partials/footer.php'; ?>
