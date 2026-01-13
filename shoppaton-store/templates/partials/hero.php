<?php
/**
 * Hero Section Template - Side by Side Layout
 *
 * @package ShoppatonStore
 */

if (!defined('ABSPATH')) {
    exit;
}

global $wpdb;
$sliders = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}shoppaton_sliders WHERE status = 'active' ORDER BY sort_order ASC");
?>
<style>
/* Hero Section - Side by Side Layout */
.shoppaton-hero {
    min-height: calc(100vh - 60px);
    display: flex;
    align-items: center;
    background: var(--shoppaton-black);
    position: relative;
    overflow: hidden;
    padding: 60px 0;
}

.shoppaton-hero-inner {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    align-items: center;
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 20px;
}

/* Hero Content - Left Side */
.shoppaton-hero-content {
    z-index: 2;
}

.shoppaton-hero-subtitle {
    display: inline-block;
    color: #D4AF37;
    font-size: 13px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 2px;
    margin-bottom: 15px;
    padding: 8px 16px;
    background: rgba(212, 175, 55, 0.1);
    border: 1px solid rgba(212, 175, 55, 0.3);
    border-radius: 20px;
}

.shoppaton-hero-title {
    font-size: clamp(2rem, 5vw, 3.5rem);
    font-weight: 700;
    line-height: 1.2;
    margin-bottom: 20px;
    background: linear-gradient(135deg, #D4AF37 0%, #F5E6C8 50%, #D4AF37 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.shoppaton-hero-text {
    font-size: 1rem;
    color: rgba(255, 255, 255, 0.8);
    line-height: 1.7;
    margin-bottom: 30px;
    max-width: 500px;
}

.shoppaton-hero-btns {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

/* Hero Slider - Right Side */
.shoppaton-hero-slider-wrapper {
    position: relative;
    border-radius: 20px;
    overflow: hidden;
    background: rgba(20, 20, 20, 0.7);
    border: 1px solid rgba(212, 175, 55, 0.3);
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
}

.shoppaton-hero-slider {
    position: relative;
    width: 100%;
    aspect-ratio: 4/3;
}

.shoppaton-hero-slide {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    transition: opacity 1s ease;
}

.shoppaton-hero-slide.active {
    opacity: 1;
}

.shoppaton-hero-slide img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Slider Dots */
.shoppaton-hero-dots {
    position: absolute;
    bottom: 15px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 8px;
    z-index: 3;
}

.shoppaton-hero-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.4);
    border: none;
    cursor: pointer;
    transition: 0.3s;
}

.shoppaton-hero-dot.active {
    background: #D4AF37;
    transform: scale(1.2);
}

/* Mobile Responsive */
@media (max-width: 991px) {
    .shoppaton-hero-inner {
        grid-template-columns: 1fr;
        gap: 40px;
        text-align: center;
    }
    .shoppaton-hero-content {
        order: 1;
    }
    .shoppaton-hero-slider-wrapper {
        order: 2;
    }
    .shoppaton-hero-text {
        margin-left: auto;
        margin-right: auto;
    }
    .shoppaton-hero-btns {
        justify-content: center;
    }
}

@media (max-width: 576px) {
    .shoppaton-hero {
        padding: 40px 0;
    }
    .shoppaton-hero-title {
        font-size: 1.75rem;
    }
}
</style>

<section class="shoppaton-hero" data-section="Hero">
    <div class="shoppaton-hero-inner">
        <!-- Left: Content -->
        <div class="shoppaton-hero-content shoppaton-animate shoppaton-fade-in-up">
            <span class="shoppaton-hero-subtitle">Premium Skincare Essentials</span>
            <h1 class="shoppaton-hero-title">
                Discover Your<br>Perfect Glow
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

        <!-- Right: Image Slider -->
        <div class="shoppaton-hero-slider-wrapper shoppaton-animate shoppaton-fade-in-right">
            <div class="shoppaton-hero-slider">
                <?php if (!empty($sliders)) : ?>
                    <?php foreach ($sliders as $index => $slide) : ?>
                    <div class="shoppaton-hero-slide <?php echo $index === 0 ? 'active' : ''; ?>">
                        <img src="<?php echo esc_url($slide->image); ?>" alt="<?php echo esc_attr($slide->title); ?>">
                    </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <div class="shoppaton-hero-slide active">
                        <div class="shoppaton-image-placeholder" style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: rgba(20,20,20,0.8);">
                            <span style="color: rgba(255,255,255,0.5); text-align: center;">Hero Image Placeholder<br><small>Upload in Admin > Sliders</small></span>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <?php if (!empty($sliders) && count($sliders) > 1) : ?>
            <div class="shoppaton-hero-dots">
                <?php foreach ($sliders as $index => $slide) : ?>
                <button class="shoppaton-hero-dot <?php echo $index === 0 ? 'active' : ''; ?>" data-slide="<?php echo $index; ?>"></button>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>
