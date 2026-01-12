<?php
/**
 * Track Order Page Template
 *
 * @package ShoppatonStore
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<?php include SHOPPATON_PLUGIN_DIR . 'templates/partials/header.php'; ?>

<div class="shoppaton-track-page" style="padding-top: 120px; min-height: 100vh;">
    <div class="shoppaton-container">
        <!-- Page Header -->
        <div class="shoppaton-page-header" style="text-align: center; margin-bottom: 60px;">
            <span class="shoppaton-section-subtitle">Order Status</span>
            <h1 class="shoppaton-gold-text-animated">Track Your Order</h1>
            <p style="color: var(--shoppaton-text-muted); max-width: 600px; margin: 20px auto 0;">
                Enter your order details below to check the status of your delivery.
            </p>
        </div>

        <!-- Track Form -->
        <div class="shoppaton-glass-card" style="max-width: 600px; margin: 0 auto 40px; padding: 40px;">
            <form id="shoppaton-track-form">
                <div class="shoppaton-form-group">
                    <label class="shoppaton-form-label">Order Number *</label>
                    <input type="text" name="order_number" id="track-order-number" class="shoppaton-form-input" placeholder="e.g., SHP20240112XXXXX" required>
                    <small style="color: var(--shoppaton-text-muted); font-size: 12px;">
                        Your order number can be found in your confirmation email.
                    </small>
                </div>
                
                <div class="shoppaton-form-group">
                    <label class="shoppaton-form-label">Email or Phone Number *</label>
                    <input type="text" name="identifier" id="track-identifier" class="shoppaton-form-input" placeholder="Email or phone used for order" required>
                </div>
                
                <button type="submit" class="shoppaton-btn shoppaton-btn-primary" style="width: 100%;">
                    Track Order
                </button>
            </form>
        </div>

        <!-- Track Results (initially hidden) -->
        <div id="track-results" style="display: none;">
            <div class="shoppaton-glass-card" style="max-width: 800px; margin: 0 auto; padding: 40px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 20px;">
                    <div>
                        <h2 style="margin-bottom: 5px;">Order <span id="result-order-number" style="color: var(--shoppaton-gold);"></span></h2>
                        <p style="color: var(--shoppaton-text-muted); margin: 0;">Placed on <span id="result-order-date"></span></p>
                    </div>
                    <span id="result-order-status" class="shoppaton-status"></span>
                </div>

                <!-- Order Timeline -->
                <div class="shoppaton-order-timeline" style="margin-bottom: 40px;">
                    <div class="timeline-step" data-status="placed">
                        <div class="timeline-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                            </svg>
                        </div>
                        <div class="timeline-content">
                            <h4>Order Placed</h4>
                            <p>Your order has been placed successfully.</p>
                            <span class="timeline-date"></span>
                        </div>
                    </div>
                    
                    <div class="timeline-step" data-status="processing">
                        <div class="timeline-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M18 6h-2c0-2.21-1.79-4-4-4S8 3.79 8 6H6c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2z"/>
                            </svg>
                        </div>
                        <div class="timeline-content">
                            <h4>Processing</h4>
                            <p>Your order is being prepared.</p>
                            <span class="timeline-date"></span>
                        </div>
                    </div>
                    
                    <div class="timeline-step" data-status="shipped">
                        <div class="timeline-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M20 8h-3V4H3c-1.1 0-2 .9-2 2v11h2c0 1.66 1.34 3 3 3s3-1.34 3-3h6c0 1.66 1.34 3 3 3s3-1.34 3-3h2v-5l-3-4z"/>
                            </svg>
                        </div>
                        <div class="timeline-content">
                            <h4>Shipped</h4>
                            <p>Your order is on its way.</p>
                            <span class="timeline-date"></span>
                        </div>
                    </div>
                    
                    <div class="timeline-step" data-status="delivered">
                        <div class="timeline-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/>
                            </svg>
                        </div>
                        <div class="timeline-content">
                            <h4>Delivered</h4>
                            <p>Your order has been delivered.</p>
                            <span class="timeline-date"></span>
                        </div>
                    </div>
                </div>

                <!-- Tracking Details -->
                <div id="tracking-details" style="display: none; background: var(--shoppaton-black-light); padding: 25px; border-radius: var(--border-radius); margin-bottom: 30px;">
                    <h4 style="color: var(--shoppaton-white); -webkit-text-fill-color: var(--shoppaton-white); margin-bottom: 15px;">
                        📦 Tracking Information
                    </h4>
                    <p style="color: var(--shoppaton-text-muted); margin-bottom: 10px;">
                        <strong>Delivery Company:</strong> <span id="delivery-company"></span>
                    </p>
                    <p style="color: var(--shoppaton-text-muted); margin-bottom: 10px;">
                        <strong>Tracking Number:</strong> <span id="tracking-number" style="color: var(--shoppaton-gold);"></span>
                    </p>
                    <a id="tracking-url" href="#" target="_blank" class="shoppaton-btn shoppaton-btn-secondary" style="margin-top: 10px;">
                        Track on Carrier Website
                    </a>
                </div>

                <!-- Order Items -->
                <div style="border-top: 1px solid var(--shoppaton-glass-border); padding-top: 30px;">
                    <h3 style="color: var(--shoppaton-white); -webkit-text-fill-color: var(--shoppaton-white); margin-bottom: 20px;">Order Items</h3>
                    <div id="order-items-list"></div>
                    
                    <div style="border-top: 1px solid var(--shoppaton-glass-border); padding-top: 20px; margin-top: 20px;">
                        <div style="display: flex; justify-content: space-between; font-size: 18px; font-weight: bold;">
                            <span>Total</span>
                            <span id="order-total" style="color: var(--shoppaton-gold);"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Help Section -->
        <div style="text-align: center; margin-top: 60px;">
            <h3 style="margin-bottom: 15px;">Need Help With Your Order?</h3>
            <p style="color: var(--shoppaton-text-muted); margin-bottom: 30px;">
                If you have any questions or concerns about your order, please don't hesitate to contact us.
            </p>
            <a href="<?php echo esc_url(Shoppaton_Settings::instance()->get_whatsapp_link('Hello, I need help with my order.')); ?>" target="_blank" class="shoppaton-btn shoppaton-btn-primary">
                💬 Contact Support
            </a>
        </div>
    </div>
</div>

<style>
.shoppaton-order-timeline {
    position: relative;
    padding-left: 30px;
}

.shoppaton-order-timeline::before {
    content: '';
    position: absolute;
    left: 10px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: var(--shoppaton-glass-border);
}

.timeline-step {
    position: relative;
    padding-bottom: 30px;
    padding-left: 30px;
}

.timeline-step:last-child {
    padding-bottom: 0;
}

.timeline-step .timeline-icon {
    position: absolute;
    left: -30px;
    width: 40px;
    height: 40px;
    background: var(--shoppaton-glass);
    border: 2px solid var(--shoppaton-glass-border);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--shoppaton-text-muted);
}

.timeline-step.completed .timeline-icon {
    background: var(--shoppaton-gold);
    border-color: var(--shoppaton-gold);
    color: var(--shoppaton-black);
}

.timeline-step.current .timeline-icon {
    background: var(--shoppaton-gold);
    border-color: var(--shoppaton-gold);
    color: var(--shoppaton-black);
    animation: pulse 2s infinite;
}

.timeline-step h4 {
    color: var(--shoppaton-white);
    -webkit-text-fill-color: var(--shoppaton-white);
    margin-bottom: 5px;
}

.timeline-step p {
    color: var(--shoppaton-text-muted);
    font-size: 14px;
    margin-bottom: 5px;
}

.timeline-step .timeline-date {
    color: var(--shoppaton-gold);
    font-size: 12px;
}
</style>

<script>
jQuery(document).ready(function($) {
    $('#shoppaton-track-form').on('submit', function(e) {
        e.preventDefault();
        
        var btn = $(this).find('button[type="submit"]');
        btn.prop('disabled', true).text('Tracking...');
        
        $.ajax({
            url: shoppatonData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'shoppaton_track_order',
                nonce: shoppatonData.nonce,
                order_number: $('#track-order-number').val(),
                identifier: $('#track-identifier').val()
            },
            success: function(response) {
                if (response.success) {
                    var order = response.data.order;
                    
                    // Update order info
                    $('#result-order-number').text('#' + order.order_number);
                    $('#result-order-date').text(order.created_at);
                    $('#result-order-status').text(order.status.charAt(0).toUpperCase() + order.status.slice(1))
                        .removeClass().addClass('shoppaton-status shoppaton-status-' + order.status);
                    $('#order-total').text(order.total);
                    
                    // Update timeline
                    var tracking = order.tracking;
                    $('.timeline-step').removeClass('completed current');
                    tracking.timeline.forEach(function(step, index) {
                        var $step = $('.timeline-step[data-status="' + step.status + '"]');
                        if (step.completed) {
                            $step.addClass('completed');
                            $step.find('.timeline-date').text(step.date || '');
                        }
                    });
                    
                    // Mark current status
                    $('.timeline-step.completed').last().addClass('current');
                    
                    // Show tracking info if available
                    if (tracking.tracking_number) {
                        $('#tracking-details').show();
                        $('#delivery-company').text(tracking.delivery_company || 'N/A');
                        $('#tracking-number').text(tracking.tracking_number);
                        if (tracking.tracking_url) {
                            $('#tracking-url').attr('href', tracking.tracking_url).show();
                        } else {
                            $('#tracking-url').hide();
                        }
                    } else {
                        $('#tracking-details').hide();
                    }
                    
                    // Show order items
                    var itemsHtml = '';
                    order.items.forEach(function(item) {
                        itemsHtml += '<div style="display: flex; justify-content: space-between; margin-bottom: 15px;">' +
                            '<span style="color: var(--shoppaton-text);">' + item.name + ' × ' + item.quantity + '</span>' +
                            '<span style="color: var(--shoppaton-gold);">' + item.price + '</span>' +
                            '</div>';
                    });
                    $('#order-items-list').html(itemsHtml);
                    
                    // Show results
                    $('#track-results').slideDown();
                    
                    // Scroll to results
                    $('html, body').animate({
                        scrollTop: $('#track-results').offset().top - 100
                    }, 600);
                } else {
                    Shoppaton.toast(response.data.message || 'Order not found', 'error');
                }
            },
            error: function() {
                Shoppaton.toast('Error tracking order. Please try again.', 'error');
            },
            complete: function() {
                btn.prop('disabled', false).text('Track Order');
            }
        });
    });
});
</script>

<?php include SHOPPATON_PLUGIN_DIR . 'templates/partials/footer.php'; ?>
