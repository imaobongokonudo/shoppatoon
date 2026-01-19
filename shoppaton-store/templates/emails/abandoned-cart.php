<?php
/**
 * Abandoned Cart Email Template
 *
 * @package ShoppatonStore
 */

if (!defined('ABSPATH')) {
    exit;
}

$logo_url = SHOPPATON_ASSETS_URL . 'images/logo.png';

// Define different message variations for abandoned cart emails
$messages = array(
    1 => array(
        'subject' => 'You Left Something Behind! 🛒',
        'headline' => 'Your Cart Misses You!',
        'intro' => 'We noticed you left some amazing skincare products in your cart. Don\'t let them slip away - your radiant skin is waiting!',
        'cta' => 'Complete Your Order',
    ),
    2 => array(
        'subject' => 'Still Thinking About It? We Saved Your Cart! 💫',
        'headline' => 'Your Skincare Essentials Are Waiting',
        'intro' => 'Your cart is still here, patiently waiting. These products are perfect for your skincare journey - don\'t miss out!',
        'cta' => 'Continue Shopping',
    ),
    3 => array(
        'subject' => 'Last Chance! Your Cart is About to Expire ⏰',
        'headline' => 'Don\'t Miss Out!',
        'intro' => 'Your cart items are still available, but they\'re popular and might sell out soon. Complete your purchase now and start your glow-up journey!',
        'cta' => 'Checkout Now',
    ),
);

$message = $messages[$reminder_number] ?? $messages[1];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo esc_html($message['subject']); ?> - Shoppaton Store</title>
</head>
<body style="margin: 0; padding: 0; background-color: #0a0a0a; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #0a0a0a; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="max-width: 600px; background: linear-gradient(135deg, rgba(30, 30, 30, 0.95), rgba(20, 20, 20, 0.98)); border-radius: 20px; overflow: hidden; border: 1px solid rgba(212, 175, 55, 0.2);">
                    <!-- Header -->
                    <tr>
                        <td style="padding: 40px; text-align: center; border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
                            <img src="<?php echo esc_url($logo_url); ?>" alt="Shoppaton Store" style="max-width: 200px; height: auto;">
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px;">
                            <div style="text-align: center; margin-bottom: 30px;">
                                <span style="font-size: 60px;">🛒</span>
                            </div>
                            
                            <h1 style="color: #d4af37; font-size: 28px; margin: 0 0 20px; text-align: center;">
                                <?php echo esc_html($message['headline']); ?>
                            </h1>
                            
                            <p style="color: #ffffff; font-size: 16px; line-height: 1.6; margin: 0 0 20px;">
                                Hi <?php echo esc_html($cart['customer_name']); ?>,
                            </p>
                            
                            <p style="color: rgba(255, 255, 255, 0.8); font-size: 16px; line-height: 1.6; margin: 0 0 30px;">
                                <?php echo esc_html($message['intro']); ?>
                            </p>
                            
                            <!-- Cart Items -->
                            <div style="background: rgba(0, 0, 0, 0.3); border-radius: 15px; padding: 25px; margin-bottom: 30px;">
                                <h2 style="color: #d4af37; font-size: 18px; margin: 0 0 20px;">Items in Your Cart</h2>
                                
                                <?php foreach ($cart['items'] as $item) : ?>
                                <table width="100%" cellpadding="0" cellspacing="0" style="border-bottom: 1px solid rgba(255, 255, 255, 0.1); margin-bottom: 15px; padding-bottom: 15px;">
                                    <tr>
                                        <td width="70" style="vertical-align: top;">
                                            <img src="<?php echo esc_url($item['image']); ?>" alt="<?php echo esc_attr($item['name']); ?>" style="width: 60px; height: 60px; object-fit: cover; border-radius: 10px;">
                                        </td>
                                        <td style="vertical-align: top; padding-left: 15px;">
                                            <p style="color: #ffffff; margin: 0 0 5px; font-weight: 500;">
                                                <?php echo esc_html($item['name']); ?>
                                            </p>
                                            <p style="color: rgba(255, 255, 255, 0.6); margin: 0; font-size: 14px;">
                                                Qty: <?php echo esc_html($item['quantity']); ?>
                                            </p>
                                        </td>
                                        <td style="vertical-align: top; text-align: right;">
                                            <p style="color: #d4af37; margin: 0; font-weight: bold;">
                                                ₦<?php echo esc_html(number_format($item['price'], 2)); ?>
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                                <?php endforeach; ?>
                                
                                <table width="100%" cellpadding="0" cellspacing="0" style="margin-top: 20px;">
                                    <tr>
                                        <td style="color: #ffffff; font-size: 18px; font-weight: bold;">Cart Total:</td>
                                        <td style="color: #d4af37; font-size: 22px; font-weight: bold; text-align: right;">
                                            ₦<?php echo esc_html(number_format($cart['total'], 2)); ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            
                            <!-- CTA Button -->
                            <div style="text-align: center; margin: 40px 0;">
                                <a href="<?php echo esc_url($cart['recovery_url']); ?>" style="display: inline-block; background: linear-gradient(135deg, #d4af37, #b8960c); color: #000000; text-decoration: none; padding: 18px 50px; border-radius: 10px; font-weight: bold; font-size: 18px;">
                                    <?php echo esc_html($message['cta']); ?>
                                </a>
                            </div>
                            
                            <!-- Benefits Reminder -->
                            <div style="background: rgba(0, 0, 0, 0.3); border-radius: 15px; padding: 25px; margin-bottom: 30px;">
                                <h3 style="color: #ffffff; font-size: 16px; margin: 0 0 15px;">Why Shop With Shoppaton?</h3>
                                <table width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td style="color: rgba(255, 255, 255, 0.8); padding: 8px 0; font-size: 14px;">
                                            ✅ 100% Authentic Products
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="color: rgba(255, 255, 255, 0.8); padding: 8px 0; font-size: 14px;">
                                            ✅ Fast Nationwide Delivery
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="color: rgba(255, 255, 255, 0.8); padding: 8px 0; font-size: 14px;">
                                            ✅ Secure Payment via Paystack
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="color: rgba(255, 255, 255, 0.8); padding: 8px 0; font-size: 14px;">
                                            ✅ 3-Day Easy Returns
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            
                            <p style="color: rgba(255, 255, 255, 0.6); font-size: 14px; text-align: center; margin: 0;">
                                Questions? Reply to this email or chat with us on WhatsApp!
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="padding: 30px 40px; background: rgba(0, 0, 0, 0.3); text-align: center; border-top: 1px solid rgba(255, 255, 255, 0.1);">
                            <p style="color: rgba(255, 255, 255, 0.5); font-size: 12px; margin: 0 0 15px;">
                                Shoppaton Store - Premium Skincare Essentials
                            </p>
                            <p style="color: rgba(255, 255, 255, 0.5); font-size: 12px; margin: 0 0 15px;">
                                Lagos, Nigeria | hello@shoppaton.com
                            </p>
                            <p style="color: rgba(255, 255, 255, 0.4); font-size: 11px; margin: 0;">
                                <a href="<?php echo esc_url($cart['unsubscribe_url']); ?>" style="color: rgba(255, 255, 255, 0.4);">Unsubscribe</a> from abandoned cart emails
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
