<?php
/**
 * Login/Register Page Template
 *
 * @package ShoppatonStore
 */

if (!defined('ABSPATH')) {
    exit;
}

$logo_url = SHOPPATON_ASSETS_URL . 'images/logo.png';
$active_form = isset($_GET['action']) && $_GET['action'] === 'register' ? 'register' : 'login';
?>

<?php include SHOPPATON_PLUGIN_DIR . 'templates/partials/header.php'; ?>

<div class="shoppaton-auth-page" style="padding-top: 120px; padding-bottom: 80px; min-height: 100vh; display: flex; align-items: center;">
    <div class="shoppaton-container">
        <div class="shoppaton-auth-wrapper" style="max-width: 450px; margin: 0 auto;">
            <div class="shoppaton-glass-card" style="padding: 40px;">
                <!-- Logo -->
                <div style="text-align: center; margin-bottom: 30px;">
                    <img src="<?php echo esc_url($logo_url); ?>" alt="Shoppaton Store" class="shoppaton-logo-animated" style="max-width: 180px; height: auto;">
                </div>

                <!-- Tab Switcher -->
                <div class="shoppaton-auth-tabs" style="display: flex; margin-bottom: 30px; background: var(--shoppaton-glass); border-radius: var(--border-radius); padding: 5px;">
                    <button type="button" class="shoppaton-auth-tab <?php echo $active_form === 'login' ? 'active' : ''; ?>" data-tab="login" style="flex: 1; padding: 12px; background: <?php echo $active_form === 'login' ? 'var(--shoppaton-gold)' : 'transparent'; ?>; border: none; border-radius: var(--border-radius-sm); color: <?php echo $active_form === 'login' ? 'var(--shoppaton-black)' : 'var(--shoppaton-text-muted)'; ?>; cursor: pointer; font-weight: 600; transition: all 0.3s ease;">
                        Login
                    </button>
                    <button type="button" class="shoppaton-auth-tab <?php echo $active_form === 'register' ? 'active' : ''; ?>" data-tab="register" style="flex: 1; padding: 12px; background: <?php echo $active_form === 'register' ? 'var(--shoppaton-gold)' : 'transparent'; ?>; border: none; border-radius: var(--border-radius-sm); color: <?php echo $active_form === 'register' ? 'var(--shoppaton-black)' : 'var(--shoppaton-text-muted)'; ?>; cursor: pointer; font-weight: 600; transition: all 0.3s ease;">
                        Register
                    </button>
                </div>

                <!-- Login Form -->
                <div id="login-form" class="shoppaton-auth-form" style="display: <?php echo $active_form === 'login' ? 'block' : 'none'; ?>;">
                    <h2 style="text-align: center; margin-bottom: 25px;">Welcome Back!</h2>
                    
                    <form id="shoppaton-login-form">
                        <div class="shoppaton-form-group">
                            <label class="shoppaton-form-label">Email Address *</label>
                            <input type="email" name="email" class="shoppaton-form-input" placeholder="Enter your email" required>
                        </div>
                        
                        <div class="shoppaton-form-group">
                            <label class="shoppaton-form-label">Password *</label>
                            <div style="position: relative;">
                                <input type="password" name="password" class="shoppaton-form-input" placeholder="Enter your password" required>
                                <button type="button" class="shoppaton-toggle-password" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--shoppaton-text-muted); cursor: pointer;">
                                    👁️
                                </button>
                            </div>
                        </div>
                        
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; color: var(--shoppaton-text-muted); font-size: 14px;">
                                <input type="checkbox" name="remember" value="1">
                                Remember me
                            </label>
                            <a href="#" id="forgot-password-link" style="color: var(--shoppaton-gold); font-size: 14px;">Forgot password?</a>
                        </div>
                        
                        <button type="submit" class="shoppaton-btn shoppaton-btn-primary" style="width: 100%; padding: 15px;">
                            Login
                        </button>
                    </form>
                    
                    <div style="text-align: center; margin-top: 25px; color: var(--shoppaton-text-muted);">
                        <p>Don't have an account? <a href="#" class="shoppaton-switch-auth" data-tab="register" style="color: var(--shoppaton-gold);">Register now</a></p>
                    </div>
                </div>

                <!-- Register Form -->
                <div id="register-form" class="shoppaton-auth-form" style="display: <?php echo $active_form === 'register' ? 'block' : 'none'; ?>;">
                    <h2 style="text-align: center; margin-bottom: 25px;">Create Account</h2>
                    
                    <form id="shoppaton-register-form">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                            <div class="shoppaton-form-group">
                                <label class="shoppaton-form-label">First Name *</label>
                                <input type="text" name="first_name" class="shoppaton-form-input" placeholder="First name" required>
                            </div>
                            <div class="shoppaton-form-group">
                                <label class="shoppaton-form-label">Last Name *</label>
                                <input type="text" name="last_name" class="shoppaton-form-input" placeholder="Last name" required>
                            </div>
                        </div>
                        
                        <div class="shoppaton-form-group">
                            <label class="shoppaton-form-label">Email Address *</label>
                            <input type="email" name="email" class="shoppaton-form-input" placeholder="Enter your email" required>
                        </div>
                        
                        <div class="shoppaton-form-group">
                            <label class="shoppaton-form-label">Phone Number *</label>
                            <input type="tel" name="phone" class="shoppaton-form-input" placeholder="Enter your phone number" required>
                        </div>
                        
                        <div class="shoppaton-form-group">
                            <label class="shoppaton-form-label">Password *</label>
                            <div style="position: relative;">
                                <input type="password" name="password" class="shoppaton-form-input" placeholder="Create a password" required>
                                <button type="button" class="shoppaton-toggle-password" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--shoppaton-text-muted); cursor: pointer;">
                                    👁️
                                </button>
                            </div>
                            <small style="color: var(--shoppaton-text-muted); font-size: 12px;">Minimum 8 characters</small>
                        </div>
                        
                        <div class="shoppaton-form-group">
                            <label style="display: flex; align-items: flex-start; gap: 10px; cursor: pointer; color: var(--shoppaton-text-muted); font-size: 14px;">
                                <input type="checkbox" name="terms" value="1" required style="margin-top: 3px;">
                                <span>I agree to the <a href="#" style="color: var(--shoppaton-gold);">Terms of Service</a> and <a href="#" style="color: var(--shoppaton-gold);">Privacy Policy</a></span>
                            </label>
                        </div>
                        
                        <button type="submit" class="shoppaton-btn shoppaton-btn-primary" style="width: 100%; padding: 15px;">
                            Create Account
                        </button>
                    </form>
                    
                    <div style="text-align: center; margin-top: 25px; color: var(--shoppaton-text-muted);">
                        <p>Already have an account? <a href="#" class="shoppaton-switch-auth" data-tab="login" style="color: var(--shoppaton-gold);">Login</a></p>
                    </div>
                </div>

                <!-- Forgot Password Form -->
                <div id="forgot-form" class="shoppaton-auth-form" style="display: none;">
                    <h2 style="text-align: center; margin-bottom: 15px;">Reset Password</h2>
                    <p style="text-align: center; color: var(--shoppaton-text-muted); margin-bottom: 25px;">
                        Enter your email address and we'll send you a link to reset your password.
                    </p>
                    
                    <form id="shoppaton-forgot-form">
                        <div class="shoppaton-form-group">
                            <label class="shoppaton-form-label">Email Address *</label>
                            <input type="email" name="email" class="shoppaton-form-input" placeholder="Enter your email" required>
                        </div>
                        
                        <button type="submit" class="shoppaton-btn shoppaton-btn-primary" style="width: 100%; padding: 15px;">
                            Send Reset Link
                        </button>
                    </form>
                    
                    <div style="text-align: center; margin-top: 25px; color: var(--shoppaton-text-muted);">
                        <p><a href="#" class="shoppaton-switch-auth" data-tab="login" style="color: var(--shoppaton-gold);">← Back to Login</a></p>
                    </div>
                </div>

                <!-- Guest Checkout Note -->
                <div style="margin-top: 30px; padding-top: 30px; border-top: 1px solid var(--shoppaton-glass-border); text-align: center;">
                    <p style="color: var(--shoppaton-text-muted); font-size: 14px; margin-bottom: 15px;">
                        Want to checkout without an account?
                    </p>
                    <a href="<?php echo esc_url(get_permalink(get_page_by_path('checkout'))); ?>" class="shoppaton-btn shoppaton-btn-secondary" style="width: 100%;">
                        Continue as Guest
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Tab switching
    $('.shoppaton-auth-tab, .shoppaton-switch-auth').on('click', function(e) {
        e.preventDefault();
        var tab = $(this).data('tab');
        
        $('.shoppaton-auth-tab').removeClass('active').css({
            'background': 'transparent',
            'color': 'var(--shoppaton-text-muted)'
        });
        $('.shoppaton-auth-tab[data-tab="' + tab + '"]').addClass('active').css({
            'background': 'var(--shoppaton-gold)',
            'color': 'var(--shoppaton-black)'
        });
        
        $('.shoppaton-auth-form').hide();
        $('#' + tab + '-form').fadeIn(300);
    });
    
    // Forgot password link
    $('#forgot-password-link').on('click', function(e) {
        e.preventDefault();
        $('.shoppaton-auth-form').hide();
        $('#forgot-form').fadeIn(300);
    });
    
    // Toggle password visibility
    $('.shoppaton-toggle-password').on('click', function() {
        var input = $(this).siblings('input');
        var type = input.attr('type') === 'password' ? 'text' : 'password';
        input.attr('type', type);
        $(this).text(type === 'password' ? '👁️' : '🙈');
    });
    
    // Login form submission
    $('#shoppaton-login-form').on('submit', function(e) {
        e.preventDefault();
        
        var btn = $(this).find('button[type="submit"]');
        btn.prop('disabled', true).text('Logging in...');
        
        $.ajax({
            url: shoppatonData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'shoppaton_login',
                nonce: shoppatonData.nonce,
                email: $(this).find('[name="email"]').val(),
                password: $(this).find('[name="password"]').val(),
                remember: $(this).find('[name="remember"]').is(':checked') ? 1 : 0
            },
            success: function(response) {
                if (response.success) {
                    Shoppaton.toast('Login successful! Redirecting...', 'success');
                    setTimeout(function() {
                        window.location.href = response.data.redirect || window.location.href;
                    }, 1000);
                } else {
                    Shoppaton.toast(response.data.message || 'Login failed', 'error');
                }
            },
            error: function() {
                Shoppaton.toast('Error logging in. Please try again.', 'error');
            },
            complete: function() {
                btn.prop('disabled', false).text('Login');
            }
        });
    });
    
    // Register form submission
    $('#shoppaton-register-form').on('submit', function(e) {
        e.preventDefault();
        
        var btn = $(this).find('button[type="submit"]');
        btn.prop('disabled', true).text('Creating account...');
        
        $.ajax({
            url: shoppatonData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'shoppaton_register',
                nonce: shoppatonData.nonce,
                first_name: $(this).find('[name="first_name"]').val(),
                last_name: $(this).find('[name="last_name"]').val(),
                email: $(this).find('[name="email"]').val(),
                phone: $(this).find('[name="phone"]').val(),
                password: $(this).find('[name="password"]').val()
            },
            success: function(response) {
                if (response.success) {
                    Shoppaton.toast('Account created! Redirecting...', 'success');
                    setTimeout(function() {
                        window.location.href = response.data.redirect || window.location.href;
                    }, 1000);
                } else {
                    Shoppaton.toast(response.data.message || 'Registration failed', 'error');
                }
            },
            error: function() {
                Shoppaton.toast('Error creating account. Please try again.', 'error');
            },
            complete: function() {
                btn.prop('disabled', false).text('Create Account');
            }
        });
    });
    
    // Forgot password form submission
    $('#shoppaton-forgot-form').on('submit', function(e) {
        e.preventDefault();
        
        var btn = $(this).find('button[type="submit"]');
        btn.prop('disabled', true).text('Sending...');
        
        $.ajax({
            url: shoppatonData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'shoppaton_forgot_password',
                nonce: shoppatonData.nonce,
                email: $(this).find('[name="email"]').val()
            },
            success: function(response) {
                if (response.success) {
                    Shoppaton.toast('Reset link sent! Check your email.', 'success');
                } else {
                    Shoppaton.toast(response.data.message || 'Error sending reset link', 'error');
                }
            },
            error: function() {
                Shoppaton.toast('Error sending reset link. Please try again.', 'error');
            },
            complete: function() {
                btn.prop('disabled', false).text('Send Reset Link');
            }
        });
    });
});
</script>

<?php include SHOPPATON_PLUGIN_DIR . 'templates/partials/footer.php'; ?>
