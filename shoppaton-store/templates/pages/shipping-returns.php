<?php
/**
 * Shipping & Returns Page Template
 *
 * @package ShoppatonStore
 */

if (!defined('ABSPATH')) {
    exit;
}

$states = Shoppaton_Settings::get_nigeria_states();
$settings = Shoppaton_Settings::instance();
?>

<?php include SHOPPATON_PLUGIN_DIR . 'templates/partials/header.php'; ?>

<div class="shoppaton-shipping-page" style="padding-top: 120px; padding-bottom: 80px; min-height: 100vh;">
    <div class="shoppaton-container">
        <!-- Page Header -->
        <div class="shoppaton-page-header" style="text-align: center; margin-bottom: 60px;">
            <span class="shoppaton-section-subtitle">Delivery Information</span>
            <h1 class="shoppaton-gold-text-animated">Shipping & Returns</h1>
            <p style="color: var(--shoppaton-text-muted); max-width: 600px; margin: 20px auto 0;">
                Fast, reliable delivery across Nigeria with hassle-free returns.
            </p>
        </div>

        <!-- Shipping Information -->
        <section class="shoppaton-section" style="padding-top: 0;">
            <div class="shoppaton-glass-card" style="padding: 40px; margin-bottom: 40px;">
                <h2 style="margin-bottom: 30px;">Shipping Information</h2>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px; margin-bottom: 40px;">
                    <div style="text-align: center; padding: 30px;">
                        <div style="width: 70px; height: 70px; background: var(--shoppaton-gold); color: var(--shoppaton-black); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M20 8h-3V4H3c-1.1 0-2 .9-2 2v11h2c0 1.66 1.34 3 3 3s3-1.34 3-3h6c0 1.66 1.34 3 3 3s3-1.34 3-3h2v-5l-3-4zM6 18.5c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm13.5-9l1.96 2.5H17V9.5h2.5zm-1.5 9c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/>
                            </svg>
                        </div>
                        <h4 style="color: var(--shoppaton-white); -webkit-text-fill-color: var(--shoppaton-white); margin-bottom: 10px;">Lagos Delivery</h4>
                        <p style="color: var(--shoppaton-text-muted); font-size: 14px;">
                            Same-day to next-day delivery within Lagos State
                        </p>
                        <p style="color: var(--shoppaton-gold); font-size: 20px; font-weight: bold; margin-top: 10px;">
                            ₦1,500
                        </p>
                    </div>
                    
                    <div style="text-align: center; padding: 30px;">
                        <div style="width: 70px; height: 70px; background: var(--shoppaton-gold); color: var(--shoppaton-black); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M18 6h-2c0-2.21-1.79-4-4-4S8 3.79 8 6H6c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm-6-2c1.1 0 2 .9 2 2h-4c0-1.1.9-2 2-2zm6 16H6V8h2v2c0 .55.45 1 1 1s1-.45 1-1V8h4v2c0 .55.45 1 1 1s1-.45 1-1V8h2v12z"/>
                            </svg>
                        </div>
                        <h4 style="color: var(--shoppaton-white); -webkit-text-fill-color: var(--shoppaton-white); margin-bottom: 10px;">Southwest Region</h4>
                        <p style="color: var(--shoppaton-text-muted); font-size: 14px;">
                            2-3 business days to Ogun, Oyo, Osun, Ondo, Ekiti, Kwara
                        </p>
                        <p style="color: var(--shoppaton-gold); font-size: 20px; font-weight: bold; margin-top: 10px;">
                            ₦2,500 - ₦4,000
                        </p>
                    </div>
                    
                    <div style="text-align: center; padding: 30px;">
                        <div style="width: 70px; height: 70px; background: var(--shoppaton-gold); color: var(--shoppaton-black); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 30px;">
                            🇳🇬
                        </div>
                        <h4 style="color: var(--shoppaton-white); -webkit-text-fill-color: var(--shoppaton-white); margin-bottom: 10px;">Other States</h4>
                        <p style="color: var(--shoppaton-text-muted); font-size: 14px;">
                            3-5 business days nationwide delivery
                        </p>
                        <p style="color: var(--shoppaton-gold); font-size: 20px; font-weight: bold; margin-top: 10px;">
                            ₦4,500 - ₦5,500
                        </p>
                    </div>
                </div>

                <div style="background: var(--shoppaton-black-light); padding: 25px; border-radius: var(--border-radius); margin-top: 30px;">
                    <h4 style="color: var(--shoppaton-white); -webkit-text-fill-color: var(--shoppaton-white); margin-bottom: 15px;">
                        📋 Important Shipping Notes
                    </h4>
                    <ul style="color: var(--shoppaton-text-muted); padding-left: 20px;">
                        <li style="margin-bottom: 10px;">Shipping costs are calculated at checkout based on your delivery address.</li>
                        <li style="margin-bottom: 10px;">Orders placed before 12 PM (noon) are processed same day.</li>
                        <li style="margin-bottom: 10px;">You will receive a tracking number via email and WhatsApp once your order ships.</li>
                        <li style="margin-bottom: 10px;">Delivery times are estimates and may vary due to unforeseen circumstances.</li>
                        <li>Please ensure someone is available to receive your package at the delivery address.</li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- Return Policy -->
        <section class="shoppaton-section" style="padding-top: 0;">
            <div class="shoppaton-glass-card" style="padding: 40px; margin-bottom: 40px;">
                <h2 style="margin-bottom: 30px;">Return Policy</h2>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px;">
                    <div>
                        <h4 style="color: var(--shoppaton-gold); margin-bottom: 15px;"><svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" style="vertical-align: middle; margin-right: 5px;"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg> We Accept Returns When:</h4>
                        <ul style="color: var(--shoppaton-text); padding-left: 20px;">
                            <li style="margin-bottom: 10px;">Product is damaged upon delivery</li>
                            <li style="margin-bottom: 10px;">Wrong product was delivered</li>
                            <li style="margin-bottom: 10px;">Product is expired or near expiry</li>
                            <li style="margin-bottom: 10px;">Product seal is broken before delivery</li>
                            <li>Quality issues with the product</li>
                        </ul>
                    </div>
                    
                    <div>
                        <h4 style="color: #ff6b6b; margin-bottom: 15px;">✗ Returns Not Accepted When:</h4>
                        <ul style="color: var(--shoppaton-text); padding-left: 20px;">
                            <li style="margin-bottom: 10px;">Product has been opened or used</li>
                            <li style="margin-bottom: 10px;">Return request is after 3 days</li>
                            <li style="margin-bottom: 10px;">Product was damaged by customer</li>
                            <li style="margin-bottom: 10px;">Customer simply changed their mind</li>
                            <li>Product was purchased on sale/clearance</li>
                        </ul>
                    </div>
                </div>

                <div style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.1), rgba(212, 175, 55, 0.05)); padding: 30px; border-radius: var(--border-radius); border: 1px solid var(--shoppaton-gold); margin-top: 40px;">
                    <h4 style="color: var(--shoppaton-gold); margin-bottom: 15px;">
                        ⚡ 3-Day Return Window
                    </h4>
                    <p style="color: var(--shoppaton-text);">
                        All return requests must be submitted within <strong>3 days</strong> of receiving your order. 
                        Please include photos of the product and packaging when submitting your complaint.
                    </p>
                </div>
            </div>
        </section>

        <!-- Return Request Form -->
        <section class="shoppaton-section" style="padding-top: 0;">
            <div class="shoppaton-glass-card" style="padding: 40px;">
                <h2 style="margin-bottom: 30px;">Submit a Return Request</h2>
                
                <form class="shoppaton-contact-form" style="max-width: 100%;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="shoppaton-form-group">
                            <label class="shoppaton-form-label">Order Number *</label>
                            <input type="text" name="order_number" class="shoppaton-form-input" placeholder="e.g., SHP20240112XXXXX" required>
                        </div>
                        
                        <div class="shoppaton-form-group">
                            <label class="shoppaton-form-label">Email Address *</label>
                            <input type="email" name="email" class="shoppaton-form-input" placeholder="Your email address" required>
                        </div>
                        
                        <div class="shoppaton-form-group">
                            <label class="shoppaton-form-label">Phone Number *</label>
                            <input type="tel" name="phone" class="shoppaton-form-input" placeholder="Your phone number" required>
                        </div>
                        
                        <div class="shoppaton-form-group">
                            <label class="shoppaton-form-label">Reason for Return *</label>
                            <select name="reason" class="shoppaton-form-select" required>
                                <option value="">Select a reason</option>
                                <option value="damaged">Product damaged on delivery</option>
                                <option value="wrong">Wrong product delivered</option>
                                <option value="expired">Product expired or near expiry</option>
                                <option value="quality">Quality issues</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        
                        <div class="shoppaton-form-group" style="grid-column: 1 / -1;">
                            <label class="shoppaton-form-label">Describe the Issue *</label>
                            <textarea name="description" class="shoppaton-form-textarea" rows="4" placeholder="Please provide details about the issue with your order" required></textarea>
                        </div>
                    </div>
                    
                    <button type="submit" class="shoppaton-btn shoppaton-btn-primary" style="margin-top: 20px;">
                        Submit Return Request
                    </button>
                </form>
            </div>
        </section>

        <!-- Contact Section -->
        <section class="shoppaton-section" style="text-align: center;">
            <h2 style="margin-bottom: 20px;">Need Help?</h2>
            <p style="color: var(--shoppaton-text-muted); max-width: 500px; margin: 0 auto 30px;">
                Have questions about shipping or returns? Our customer service team is here to help.
            </p>
            <div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap;">
                <a href="mailto:<?php echo esc_attr($settings->get('contact_email')); ?>" class="shoppaton-btn shoppaton-btn-secondary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                    Email Us
                </a>
                <a href="<?php echo esc_url($settings->get_whatsapp_link('Hello, I have a question about shipping/returns.')); ?>" target="_blank" class="shoppaton-btn shoppaton-btn-primary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    WhatsApp Us
                </a>
            </div>
        </section>
    </div>
</div>

<?php include SHOPPATON_PLUGIN_DIR . 'templates/partials/footer.php'; ?>
