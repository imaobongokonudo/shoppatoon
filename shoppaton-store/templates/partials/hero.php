<?php
/**
 * Hero Section Template
 *
 * @package ShoppatonStore
 */

if (!defined('ABSPATH')) {
    exit;
}

global $wpdb;
$sliders = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}shoppaton_sliders WHERE status = 'active' ORDER BY sort_order ASC");
?>
<section class="shoppaton-hero" data-section="Hero">
    <div class="shoppaton-hero-slider">
        <?php if (!empty($sliders)) : ?>
            <?php foreach ($sliders as $index => $slide) : ?>
            <div class="shoppaton-hero-slide <?php echo $index === 0 ? 'active' : ''; ?>">
                <img src="<?php echo esc_url($slide->image); ?>" alt="<?php echo esc_attr($slide->title); ?>">
            </div>
            <?php endforeach; ?>
        <?php else : ?>
            <!-- Placeholder slide -->
            <div class="shoppaton-hero-slide active">
                <div class="shoppaton-image-placeholder" style="width: 100%; height: 100%; min-height: 100vh;">
                    <span>Hero Image Placeholder<br>Upload images in Admin > Sliders</span>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="shoppaton-hero-overlay"></div>
    
    <div class="shoppaton-container">
        <div class="shoppaton-hero-content shoppaton-animate shoppaton-fade-in-up">
            <span class="shoppaton-hero-subtitle">Premium Skincare Essentials</span>
            <h1 class="shoppaton-hero-title shoppaton-gold-text-animated">
                Discover Your <br>Perfect Glow
            </h1>
            <p class="shoppaton-hero-text">
                Transform your skincare routine with our carefully curated collection of premium products. 
                Experience luxury skincare that delivers real results.
            </p>
            <div class="shoppaton-hero-btns">
                <a href="<?php echo esc_url(get_permalink(get_page_by_path('shop'))); ?>" class="shoppaton-btn shoppaton-btn-primary shoppaton-btn-animated">
                    Shop Now
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </a>
                <a href="<?php echo esc_url(get_permalink(get_page_by_path('about-us'))); ?>" class="shoppaton-btn shoppaton-btn-secondary">
                    Learn More
                </a>
            </div>
        </div>
    </div>
</section>
