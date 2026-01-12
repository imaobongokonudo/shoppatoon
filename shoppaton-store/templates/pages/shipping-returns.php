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

<div class="shoppaton-shipping-page" style="padding-top: 120px; min-height: 100vh;">
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
                        <div style="width: 70px; height: 70px; background: var(--shoppaton-gold); color: var(--shoppaton-black); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 30px;">
                            🚚
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
                        <div style="width: 70px; height: 70px; background: var(--shoppaton-gold); color: var(--shoppaton-black); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 30px;">
                            📦
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
                        <h4 style="color: var(--shoppaton-gold); margin-bottom: 15px;">✓ We Accept Returns When:</h4>
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
                    📧 Email Us
                </a>
                <a href="<?php echo esc_url($settings->get_whatsapp_link('Hello, I have a question about shipping/returns.')); ?>" target="_blank" class="shoppaton-btn shoppaton-btn-primary">
                    💬 WhatsApp Us
                </a>
            </div>
        </section>
    </div>
</div>

<?php include SHOPPATON_PLUGIN_DIR . 'templates/partials/footer.php'; ?>
