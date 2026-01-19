<?php
/**
 * Checkout Page Template
 *
 * @package ShoppatonStore
 */

if (!defined('ABSPATH')) {
    exit;
}

$cart = Shoppaton_Cart::instance();
$cart_items = $cart->get_items();

if (empty($cart_items)) {
    wp_redirect(get_permalink(get_page_by_path('cart')));
    exit;
}

$subtotal = $cart->get_subtotal();
$checkout = Shoppaton_Checkout::instance();
$fields = $checkout->get_checkout_fields();
$states = Shoppaton_Settings::get_nigeria_states();
$user = is_user_logged_in() ? wp_get_current_user() : null;
$saved_addresses = $user ? Shoppaton_User::instance()->get_saved_addresses($user->ID) : array();
?>

<?php include SHOPPATON_PLUGIN_DIR . 'templates/partials/header.php'; ?>

<div class="shoppaton-checkout-page" style="padding-top: 120px; padding-bottom: 80px; min-height: 100vh;">
    <div class="shoppaton-container">
        <h1 class="shoppaton-gold-text-animated" style="text-align: center; margin-bottom: 40px;">Checkout</h1>

        <form id="shoppaton-checkout-form">
            <div class="shoppaton-cart-layout">
                <!-- Checkout Form -->
                <div class="shoppaton-cart-items">
                    <div class="shoppaton-glass-card" style="padding: 30px; margin-bottom: 30px;">
                        <h3 style="color: var(--shoppaton-white); -webkit-text-fill-color: var(--shoppaton-white); margin-bottom: 25px;">
                            Billing & Delivery Details
                        </h3>
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div class="shoppaton-form-group" style="grid-column: 1 / -1;">
                                <label class="shoppaton-form-label">Full Name *</label>
                                <input type="text" name="billing_name" class="shoppaton-form-input" 
                                       value="<?php echo $user ? esc_attr($user->display_name) : ''; ?>" required>
                            </div>
                            
                            <div class="shoppaton-form-group">
                                <label class="shoppaton-form-label">Email Address *</label>
                                <input type="email" name="billing_email" class="shoppaton-form-input" 
                                       value="<?php echo $user ? esc_attr($user->user_email) : ''; ?>" required>
                            </div>
                            
                            <div class="shoppaton-form-group">
                                <label class="shoppaton-form-label">Phone Number *</label>
                                <input type="tel" name="billing_phone" class="shoppaton-form-input" 
                                       value="<?php echo $saved_addresses['billing']['phone'] ?? ''; ?>" 
                                       placeholder="e.g., 08012345678" required>
                            </div>
                            
                            <div class="shoppaton-form-group" style="grid-column: 1 / -1;">
                                <label class="shoppaton-form-label">Delivery Address *</label>
                                <textarea name="billing_address" class="shoppaton-form-textarea" rows="3" 
                                          placeholder="Enter your full delivery address" required><?php echo esc_textarea($saved_addresses['billing']['address'] ?? ''); ?></textarea>
                            </div>
                            
                            <div class="shoppaton-form-group">
                                <label class="shoppaton-form-label">City *</label>
                                <input type="text" name="billing_city" class="shoppaton-form-input" 
                                       value="<?php echo esc_attr($saved_addresses['billing']['city'] ?? ''); ?>" required>
                            </div>
                            
                            <div class="shoppaton-form-group">
                                <label class="shoppaton-form-label">State *</label>
                                <select name="billing_state" class="shoppaton-form-select" id="billing-state" required>
                                    <option value="">Select State</option>
                                    <?php foreach ($states as $value => $label) : ?>
                                    <option value="<?php echo esc_attr($value); ?>" <?php selected($saved_addresses['billing']['state'] ?? 'lagos', $value); ?>>
                                        <?php echo esc_html($label); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="shoppaton-glass-card" style="padding: 30px;">
                        <h3 style="color: var(--shoppaton-white); -webkit-text-fill-color: var(--shoppaton-white); margin-bottom: 25px;">
                            Order Notes (Optional)
                        </h3>
                        
                        <div class="shoppaton-form-group">
                            <textarea name="order_notes" class="shoppaton-form-textarea" rows="3" 
                                      placeholder="Any special instructions for your order or delivery?"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="shoppaton-cart-summary">
                    <h3 class="shoppaton-cart-summary-title">Your Order</h3>
                    
                    <!-- Order Items -->
                    <div style="margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid var(--shoppaton-glass-border);">
                        <?php foreach ($cart_items as $item) : ?>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 15px; font-size: 14px;">
                            <span style="color: var(--shoppaton-text);">
                                <?php echo esc_html($item['name']); ?> × <?php echo esc_html($item['quantity']); ?>
                            </span>
                            <span style="color: var(--shoppaton-gold);">
                                <?php echo esc_html(Shoppaton_Settings::format_price($item['price'] * $item['quantity'])); ?>
                            </span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="shoppaton-cart-summary-row">
                        <span>Subtotal</span>
                        <span><?php echo esc_html(Shoppaton_Settings::format_price($subtotal)); ?></span>
                    </div>
                    
                    <div class="shoppaton-cart-summary-row">
                        <span>Shipping</span>
                        <span id="checkout-shipping"><?php echo esc_html(Shoppaton_Settings::format_price($cart->get_shipping_cost('lagos'))); ?></span>
                    </div>
                    
                    <div class="shoppaton-cart-summary-row total">
                        <span>Total</span>
                        <span id="checkout-total"><?php echo esc_html(Shoppaton_Settings::format_price($subtotal + $cart->get_shipping_cost('lagos'))); ?></span>
                    </div>

                    <!-- Payment Method -->
                    <div style="margin: 25px 0; padding: 20px; background: var(--shoppaton-black-light); border-radius: var(--border-radius);">
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <input type="radio" name="payment_method" value="paystack" checked id="payment-paystack">
                            <label for="payment-paystack" style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI4MCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDgwIDI0Ij48dGV4dCB4PSIwIiB5PSIxNyIgZmlsbD0iIzAwQzNGNyIgZm9udC1mYW1pbHk9IkFyaWFsIiBmb250LXNpemU9IjE0IiBmb250LXdlaWdodD0iYm9sZCI+UGF5c3RhY2s8L3RleHQ+PC9zdmc+" alt="Paystack" style="height: 24px;">
                                <span style="color: var(--shoppaton-text);">Pay with Card, Bank, or USSD</span>
                            </label>
                        </div>
                    </div>

                    <!-- Trust Signals -->
                    <div style="border-top: 1px solid var(--shoppaton-glass-border); padding-top: 20px; margin-top: 20px;">
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="var(--shoppaton-gold)">
                                <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/>
                            </svg>
                            <span style="font-size: 12px;">SSL Encrypted Checkout</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="var(--shoppaton-gold)">
                                <path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2z"/>
                            </svg>
                            <span style="font-size: 12px;">Secured by Paystack</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="var(--shoppaton-gold)">
                                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                            </svg>
                            <span style="font-size: 12px;">100% Secure Payment</span>
                        </div>
                    </div>

                    <button type="submit" class="shoppaton-btn shoppaton-btn-primary" style="width: 100%; margin-top: 25px; padding: 18px;">
                        Place Order & Pay
                    </button>

                    <p style="font-size: 11px; color: var(--shoppaton-text-muted); text-align: center; margin-top: 15px;">
                        By placing your order, you agree to our Terms of Service and Privacy Policy.
                    </p>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Update shipping on state change
    $('#billing-state').on('change', function() {
        var state = $(this).val();
        $.ajax({
            url: shoppatonData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'shoppaton_get_shipping_rate',
                nonce: shoppatonData.nonce,
                state: state
            },
            success: function(response) {
                if (response.success) {
                    $('#checkout-shipping').text(response.data.formatted);
                    var subtotal = <?php echo floatval($subtotal); ?>;
                    var total = subtotal + response.data.rate;
                    $('#checkout-total').text('₦' + total.toLocaleString('en-NG', {minimumFractionDigits: 2}));
                }
            }
        });
    });

    // Handle checkout form
    $('#shoppaton-checkout-form').on('submit', function(e) {
        e.preventDefault();
        
        var btn = $(this).find('button[type="submit"]');
        btn.prop('disabled', true).text('Processing...');

        $.ajax({
            url: shoppatonData.ajaxUrl,
            type: 'POST',
            data: $(this).serialize() + '&action=shoppaton_process_checkout&nonce=' + shoppatonData.nonce,
            success: function(response) {
                if (response.success) {
                    // Initialize Paystack payment
                    ShoppatonPayment.init(response.data.payment);
                } else {
                    Shoppaton.toast(response.data.message || 'Checkout failed', 'error');
                    btn.prop('disabled', false).text('Place Order & Pay');
                }
            },
            error: function() {
                Shoppaton.toast('An error occurred. Please try again.', 'error');
                btn.prop('disabled', false).text('Place Order & Pay');
            }
        });
    });
});
</script>

<?php include SHOPPATON_PLUGIN_DIR . 'templates/partials/footer.php'; ?>
