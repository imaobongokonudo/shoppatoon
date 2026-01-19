<?php
/**
 * User Dashboard Page Template
 *
 * @package ShoppatonStore
 */

if (!defined('ABSPATH')) {
    exit;
}

$user = Shoppaton_User::instance();
$current_user = wp_get_current_user();
$orders = $user->get_user_orders();
$wishlist = $user->get_wishlist();
$logo_url = SHOPPATON_ASSETS_URL . 'images/logo.png';

// Get active tab
$active_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'orders';
?>

<?php include SHOPPATON_PLUGIN_DIR . 'templates/partials/header.php'; ?>

<div class="shoppaton-dashboard-page" style="padding-top: 120px; padding-bottom: 80px; min-height: 100vh;">
    <div class="shoppaton-container">
        <!-- Dashboard Header -->
        <div class="shoppaton-dashboard-header" style="margin-bottom: 40px;">
            <div style="display: flex; align-items: center; gap: 20px; flex-wrap: wrap;">
                <div class="shoppaton-avatar" style="width: 80px; height: 80px; background: var(--shoppaton-gold-gradient); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 36px; color: var(--shoppaton-black);">
                    <?php echo esc_html(strtoupper(substr($current_user->display_name ?: $current_user->user_login, 0, 1))); ?>
                </div>
                <div>
                    <h1 style="margin-bottom: 5px;">Welcome, <?php echo esc_html($current_user->display_name ?: $current_user->user_login); ?>!</h1>
                    <p style="color: var(--shoppaton-text-muted); margin: 0;"><?php echo esc_html($current_user->user_email); ?></p>
                </div>
            </div>
        </div>

        <!-- Dashboard Layout -->
        <div class="shoppaton-dashboard-layout" style="display: grid; grid-template-columns: 250px 1fr; gap: 30px;">
            <!-- Sidebar Navigation -->
            <div class="shoppaton-dashboard-sidebar">
                <div class="shoppaton-glass-card" style="padding: 20px;">
                    <nav class="shoppaton-dashboard-nav">
                        <a href="?tab=orders" class="shoppaton-dashboard-nav-item <?php echo $active_tab === 'orders' ? 'active' : ''; ?>">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M19 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 8H7v-2h5v2zm5-4H7V5h10v2z"/>
                            </svg>
                            <span>My Orders</span>
                        </a>
                        <a href="?tab=wishlist" class="shoppaton-dashboard-nav-item <?php echo $active_tab === 'wishlist' ? 'active' : ''; ?>">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                            </svg>
                            <span>Wishlist</span>
                        </a>
                        <a href="?tab=profile" class="shoppaton-dashboard-nav-item <?php echo $active_tab === 'profile' ? 'active' : ''; ?>">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                            </svg>
                            <span>Profile</span>
                        </a>
                        <a href="?tab=addresses" class="shoppaton-dashboard-nav-item <?php echo $active_tab === 'addresses' ? 'active' : ''; ?>">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                            </svg>
                            <span>Addresses</span>
                        </a>
                        <div style="border-top: 1px solid var(--shoppaton-glass-border); margin: 15px 0;"></div>
                        <a href="<?php echo esc_url(wp_logout_url(home_url())); ?>" class="shoppaton-dashboard-nav-item">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/>
                            </svg>
                            <span>Logout</span>
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="shoppaton-dashboard-content">
                <?php if ($active_tab === 'orders') : ?>
                <!-- Orders Tab -->
                <div class="shoppaton-glass-card" style="padding: 30px;">
                    <h2 style="margin-bottom: 25px;">My Orders</h2>
                    
                    <?php if (empty($orders)) : ?>
                    <div style="text-align: center; padding: 60px 20px;">
                        <div style="font-size: 60px; margin-bottom: 20px;">📦</div>
                        <h3 style="margin-bottom: 10px;">No Orders Yet</h3>
                        <p style="color: var(--shoppaton-text-muted); margin-bottom: 30px;">
                            Start shopping to see your order history here.
                        </p>
                        <a href="<?php echo esc_url(get_permalink(get_page_by_path('shop'))); ?>" class="shoppaton-btn shoppaton-btn-primary">
                            Start Shopping
                        </a>
                    </div>
                    <?php else : ?>
                    <div class="shoppaton-orders-list">
                        <?php foreach ($orders as $order) : ?>
                        <div class="shoppaton-order-card" style="background: var(--shoppaton-black-light); padding: 20px; border-radius: var(--border-radius); margin-bottom: 15px;">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 15px;">
                                <div>
                                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 10px;">
                                        <strong style="color: var(--shoppaton-gold);">#<?php echo esc_html($order['order_number']); ?></strong>
                                        <span class="shoppaton-status shoppaton-status-<?php echo esc_attr($order['status']); ?>">
                                            <?php echo esc_html(ucfirst($order['status'])); ?>
                                        </span>
                                    </div>
                                    <p style="color: var(--shoppaton-text-muted); font-size: 14px; margin: 0;">
                                        Placed on <?php echo esc_html(date('M j, Y', strtotime($order['created_at']))); ?>
                                    </p>
                                </div>
                                <div style="text-align: right;">
                                    <p style="font-size: 18px; font-weight: bold; color: var(--shoppaton-gold); margin: 0 0 10px;">
                                        ₦<?php echo esc_html(number_format($order['total'], 2)); ?>
                                    </p>
                                    <p style="color: var(--shoppaton-text-muted); font-size: 14px; margin: 0;">
                                        <?php echo esc_html($order['items_count']); ?> item(s)
                                    </p>
                                </div>
                            </div>
                            <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid var(--shoppaton-glass-border); display: flex; gap: 15px;">
                                <a href="<?php echo esc_url(add_query_arg('order', $order['order_number'], get_permalink(get_page_by_path('track-order')))); ?>" class="shoppaton-btn shoppaton-btn-secondary" style="padding: 8px 20px; font-size: 13px;">
                                    Track Order
                                </a>
                                <button type="button" class="shoppaton-btn shoppaton-btn-ghost shoppaton-view-order-details" data-order="<?php echo esc_attr($order['order_number']); ?>" style="padding: 8px 20px; font-size: 13px;">
                                    View Details
                                </button>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>

                <?php elseif ($active_tab === 'wishlist') : ?>
                <!-- Wishlist Tab -->
                <div class="shoppaton-glass-card" style="padding: 30px;">
                    <h2 style="margin-bottom: 25px;">My Wishlist</h2>
                    
                    <?php if (empty($wishlist)) : ?>
                    <div style="text-align: center; padding: 60px 20px;">
                        <div style="font-size: 60px; margin-bottom: 20px;">❤️</div>
                        <h3 style="margin-bottom: 10px;">Wishlist Empty</h3>
                        <p style="color: var(--shoppaton-text-muted); margin-bottom: 30px;">
                            Save products you love by clicking the heart icon on any product.
                        </p>
                        <a href="<?php echo esc_url(get_permalink(get_page_by_path('shop'))); ?>" class="shoppaton-btn shoppaton-btn-primary">
                            Explore Products
                        </a>
                    </div>
                    <?php else : ?>
                    <div class="shoppaton-products-grid" style="grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));">
                        <?php foreach ($wishlist as $product) : ?>
                            <?php include SHOPPATON_PLUGIN_DIR . 'templates/partials/product-card.php'; ?>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>

                <?php elseif ($active_tab === 'profile') : ?>
                <!-- Profile Tab -->
                <div class="shoppaton-glass-card" style="padding: 30px;">
                    <h2 style="margin-bottom: 25px;">Profile Settings</h2>
                    
                    <form id="shoppaton-profile-form">
                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                            <div class="shoppaton-form-group">
                                <label class="shoppaton-form-label">First Name</label>
                                <input type="text" name="first_name" class="shoppaton-form-input" value="<?php echo esc_attr($current_user->first_name); ?>">
                            </div>
                            <div class="shoppaton-form-group">
                                <label class="shoppaton-form-label">Last Name</label>
                                <input type="text" name="last_name" class="shoppaton-form-input" value="<?php echo esc_attr($current_user->last_name); ?>">
                            </div>
                            <div class="shoppaton-form-group" style="grid-column: 1 / -1;">
                                <label class="shoppaton-form-label">Email Address</label>
                                <input type="email" name="email" class="shoppaton-form-input" value="<?php echo esc_attr($current_user->user_email); ?>" readonly>
                                <small style="color: var(--shoppaton-text-muted);">Email cannot be changed</small>
                            </div>
                            <div class="shoppaton-form-group" style="grid-column: 1 / -1;">
                                <label class="shoppaton-form-label">Phone Number</label>
                                <input type="tel" name="phone" class="shoppaton-form-input" value="<?php echo esc_attr(get_user_meta($current_user->ID, 'phone', true)); ?>">
                            </div>
                        </div>
                        
                        <button type="submit" class="shoppaton-btn shoppaton-btn-primary" style="margin-top: 20px;">
                            Save Changes
                        </button>
                    </form>

                    <div style="border-top: 1px solid var(--shoppaton-glass-border); margin-top: 40px; padding-top: 40px;">
                        <h3 style="margin-bottom: 20px;">Change Password</h3>
                        <form id="shoppaton-password-form">
                            <div class="shoppaton-form-group">
                                <label class="shoppaton-form-label">Current Password</label>
                                <input type="password" name="current_password" class="shoppaton-form-input" required>
                            </div>
                            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                                <div class="shoppaton-form-group">
                                    <label class="shoppaton-form-label">New Password</label>
                                    <input type="password" name="new_password" class="shoppaton-form-input" required>
                                </div>
                                <div class="shoppaton-form-group">
                                    <label class="shoppaton-form-label">Confirm New Password</label>
                                    <input type="password" name="confirm_password" class="shoppaton-form-input" required>
                                </div>
                            </div>
                            <button type="submit" class="shoppaton-btn shoppaton-btn-secondary" style="margin-top: 20px;">
                                Update Password
                            </button>
                        </form>
                    </div>
                </div>

                <?php elseif ($active_tab === 'addresses') : ?>
                <!-- Addresses Tab -->
                <div class="shoppaton-glass-card" style="padding: 30px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
                        <h2 style="margin: 0;">Saved Addresses</h2>
                        <button type="button" class="shoppaton-btn shoppaton-btn-primary" id="add-new-address" style="padding: 10px 20px;">
                            + Add Address
                        </button>
                    </div>
                    
                    <?php 
                    $addresses = get_user_meta($current_user->ID, 'shoppaton_addresses', true) ?: array();
                    if (empty($addresses)) : 
                    ?>
                    <div style="text-align: center; padding: 60px 20px;">
                        <div style="font-size: 60px; margin-bottom: 20px;">📍</div>
                        <h3 style="margin-bottom: 10px;">No Saved Addresses</h3>
                        <p style="color: var(--shoppaton-text-muted); margin-bottom: 30px;">
                            Save your delivery addresses for faster checkout.
                        </p>
                    </div>
                    <?php else : ?>
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
                        <?php foreach ($addresses as $index => $address) : ?>
                        <div class="shoppaton-address-card" style="background: var(--shoppaton-black-light); padding: 20px; border-radius: var(--border-radius); position: relative;">
                            <?php if (!empty($address['is_default'])) : ?>
                            <span style="position: absolute; top: 10px; right: 10px; background: var(--shoppaton-gold); color: var(--shoppaton-black); font-size: 11px; padding: 3px 8px; border-radius: 10px;">
                                Default
                            </span>
                            <?php endif; ?>
                            <h4 style="color: var(--shoppaton-white); -webkit-text-fill-color: var(--shoppaton-white); margin-bottom: 10px;"><?php echo esc_html($address['label'] ?: 'Address ' . ($index + 1)); ?></h4>
                            <p style="color: var(--shoppaton-text-muted); font-size: 14px; line-height: 1.6; margin: 0;">
                                <?php echo esc_html($address['street']); ?><br>
                                <?php echo esc_html($address['city'] . ', ' . $address['state']); ?><br>
                                <?php echo esc_html($address['phone']); ?>
                            </p>
                            <div style="margin-top: 15px; display: flex; gap: 10px;">
                                <button type="button" class="shoppaton-btn shoppaton-btn-ghost shoppaton-edit-address" data-index="<?php echo esc_attr($index); ?>" style="padding: 6px 15px; font-size: 12px;">
                                    Edit
                                </button>
                                <button type="button" class="shoppaton-btn shoppaton-btn-ghost shoppaton-delete-address" data-index="<?php echo esc_attr($index); ?>" style="padding: 6px 15px; font-size: 12px; color: #ff6b6b;">
                                    Delete
                                </button>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
.shoppaton-dashboard-nav-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 15px;
    color: var(--shoppaton-text-muted);
    text-decoration: none;
    border-radius: var(--border-radius-sm);
    transition: all 0.3s ease;
    margin-bottom: 5px;
}

.shoppaton-dashboard-nav-item:hover {
    background: var(--shoppaton-glass);
    color: var(--shoppaton-white);
}

.shoppaton-dashboard-nav-item.active {
    background: var(--shoppaton-gold-gradient);
    color: var(--shoppaton-black);
}

.shoppaton-status {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}

.shoppaton-status-pending {
    background: rgba(255, 193, 7, 0.2);
    color: #ffc107;
}

.shoppaton-status-processing {
    background: rgba(33, 150, 243, 0.2);
    color: #2196f3;
}

.shoppaton-status-shipped {
    background: rgba(156, 39, 176, 0.2);
    color: #9c27b0;
}

.shoppaton-status-delivered {
    background: rgba(76, 175, 80, 0.2);
    color: #4caf50;
}

.shoppaton-status-cancelled {
    background: rgba(244, 67, 54, 0.2);
    color: #f44336;
}

@media (max-width: 768px) {
    .shoppaton-dashboard-layout {
        grid-template-columns: 1fr !important;
    }
    
    .shoppaton-dashboard-sidebar {
        order: 2;
    }
    
    .shoppaton-dashboard-nav {
        display: flex;
        overflow-x: auto;
        gap: 10px;
        padding-bottom: 10px;
    }
    
    .shoppaton-dashboard-nav-item {
        white-space: nowrap;
        margin-bottom: 0;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    // Profile form submission
    $('#shoppaton-profile-form').on('submit', function(e) {
        e.preventDefault();
        
        var btn = $(this).find('button[type="submit"]');
        btn.prop('disabled', true).text('Saving...');
        
        $.ajax({
            url: shoppatonData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'shoppaton_update_profile',
                nonce: shoppatonData.nonce,
                first_name: $(this).find('[name="first_name"]').val(),
                last_name: $(this).find('[name="last_name"]').val(),
                phone: $(this).find('[name="phone"]').val()
            },
            success: function(response) {
                if (response.success) {
                    Shoppaton.toast('Profile updated successfully!', 'success');
                } else {
                    Shoppaton.toast(response.data.message || 'Error updating profile', 'error');
                }
            },
            error: function() {
                Shoppaton.toast('Error updating profile. Please try again.', 'error');
            },
            complete: function() {
                btn.prop('disabled', false).text('Save Changes');
            }
        });
    });

    // Password form submission
    $('#shoppaton-password-form').on('submit', function(e) {
        e.preventDefault();
        
        var newPassword = $(this).find('[name="new_password"]').val();
        var confirmPassword = $(this).find('[name="confirm_password"]').val();
        
        if (newPassword !== confirmPassword) {
            Shoppaton.toast('Passwords do not match', 'error');
            return;
        }
        
        var btn = $(this).find('button[type="submit"]');
        btn.prop('disabled', true).text('Updating...');
        
        $.ajax({
            url: shoppatonData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'shoppaton_update_password',
                nonce: shoppatonData.nonce,
                current_password: $(this).find('[name="current_password"]').val(),
                new_password: newPassword
            },
            success: function(response) {
                if (response.success) {
                    Shoppaton.toast('Password updated successfully!', 'success');
                    $('#shoppaton-password-form')[0].reset();
                } else {
                    Shoppaton.toast(response.data.message || 'Error updating password', 'error');
                }
            },
            error: function() {
                Shoppaton.toast('Error updating password. Please try again.', 'error');
            },
            complete: function() {
                btn.prop('disabled', false).text('Update Password');
            }
        });
    });
});
</script>

<?php include SHOPPATON_PLUGIN_DIR . 'templates/partials/footer.php'; ?>
