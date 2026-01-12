<?php
/**
 * Order Confirmation Email Template
 *
 * @package ShoppatonStore
 */

if (!defined('ABSPATH')) {
    exit;
}

$logo_url = SHOPPATON_ASSETS_URL . 'images/logo.png';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - Shoppaton Store</title>
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
                            <h1 style="color: #d4af37; font-size: 28px; margin: 0 0 20px; text-align: center;">
                                Thank You for Your Order! 🎉
                            </h1>
                            
                            <p style="color: #ffffff; font-size: 16px; line-height: 1.6; margin: 0 0 20px;">
                                Hi <?php echo esc_html($order['customer_name']); ?>,
                            </p>
                            
                            <p style="color: rgba(255, 255, 255, 0.8); font-size: 16px; line-height: 1.6; margin: 0 0 30px;">
                                We've received your order and are getting it ready for you. We'll notify you when it ships!
                            </p>
                            
                            <!-- Order Details Box -->
                            <div style="background: rgba(0, 0, 0, 0.3); border-radius: 15px; padding: 25px; margin-bottom: 30px;">
                                <h2 style="color: #d4af37; font-size: 18px; margin: 0 0 15px;">Order Details</h2>
                                
                                <table width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td style="color: rgba(255, 255, 255, 0.7); padding: 8px 0;">Order Number:</td>
                                        <td style="color: #d4af37; font-weight: bold; text-align: right; padding: 8px 0;">
                                            <?php echo esc_html($order['order_number']); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="color: rgba(255, 255, 255, 0.7); padding: 8px 0;">Order Date:</td>
                                        <td style="color: #ffffff; text-align: right; padding: 8px 0;">
                                            <?php echo esc_html(date('F j, Y', strtotime($order['created_at']))); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="color: rgba(255, 255, 255, 0.7); padding: 8px 0;">Payment Method:</td>
                                        <td style="color: #ffffff; text-align: right; padding: 8px 0;">
                                            <?php echo esc_html($order['payment_method']); ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            
                            <!-- Order Items -->
                            <div style="background: rgba(0, 0, 0, 0.3); border-radius: 15px; padding: 25px; margin-bottom: 30px;">
                                <h2 style="color: #d4af37; font-size: 18px; margin: 0 0 20px;">Order Items</h2>
                                
                                <?php foreach ($order['items'] as $item) : ?>
                                <div style="display: flex; padding: 15px 0; border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
                                    <div style="flex: 1;">
                                        <p style="color: #ffffff; margin: 0 0 5px; font-weight: 500;">
                                            <?php echo esc_html($item['name']); ?>
                                        </p>
                                        <p style="color: rgba(255, 255, 255, 0.6); margin: 0; font-size: 14px;">
                                            Qty: <?php echo esc_html($item['quantity']); ?>
                                        </p>
                                    </div>
                                    <div style="text-align: right;">
                                        <p style="color: #d4af37; margin: 0; font-weight: bold;">
                                            ₦<?php echo esc_html(number_format($item['price'] * $item['quantity'], 2)); ?>
                                        </p>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                                
                                <!-- Totals -->
                                <table width="100%" cellpadding="0" cellspacing="0" style="margin-top: 20px;">
                                    <tr>
                                        <td style="color: rgba(255, 255, 255, 0.7); padding: 8px 0;">Subtotal:</td>
                                        <td style="color: #ffffff; text-align: right; padding: 8px 0;">
                                            ₦<?php echo esc_html(number_format($order['subtotal'], 2)); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="color: rgba(255, 255, 255, 0.7); padding: 8px 0;">Shipping:</td>
                                        <td style="color: #ffffff; text-align: right; padding: 8px 0;">
                                            ₦<?php echo esc_html(number_format($order['shipping'], 2)); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="border-top: 1px solid rgba(255, 255, 255, 0.1); padding-top: 15px;"></td>
                                    </tr>
                                    <tr>
                                        <td style="color: #ffffff; font-size: 18px; font-weight: bold; padding: 8px 0;">Total:</td>
                                        <td style="color: #d4af37; font-size: 22px; font-weight: bold; text-align: right; padding: 8px 0;">
                                            ₦<?php echo esc_html(number_format($order['total'], 2)); ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            
                            <!-- Shipping Address -->
                            <div style="background: rgba(0, 0, 0, 0.3); border-radius: 15px; padding: 25px; margin-bottom: 30px;">
                                <h2 style="color: #d4af37; font-size: 18px; margin: 0 0 15px;">📍 Shipping Address</h2>
                                <p style="color: rgba(255, 255, 255, 0.8); margin: 0; line-height: 1.6;">
                                    <?php echo esc_html($order['shipping_address']['name']); ?><br>
                                    <?php echo esc_html($order['shipping_address']['street']); ?><br>
                                    <?php echo esc_html($order['shipping_address']['city'] . ', ' . $order['shipping_address']['state']); ?><br>
                                    <?php echo esc_html($order['shipping_address']['phone']); ?>
                                </p>
                            </div>
                            
                            <!-- Track Order Button -->
                            <div style="text-align: center; margin: 40px 0;">
                                <a href="<?php echo esc_url($order['track_url']); ?>" style="display: inline-block; background: linear-gradient(135deg, #d4af37, #b8960c); color: #000000; text-decoration: none; padding: 15px 40px; border-radius: 10px; font-weight: bold; font-size: 16px;">
                                    Track Your Order
                                </a>
                            </div>
                            
                            <p style="color: rgba(255, 255, 255, 0.6); font-size: 14px; text-align: center; margin: 0;">
                                If you have any questions, reply to this email or contact us via WhatsApp.
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="padding: 30px 40px; background: rgba(0, 0, 0, 0.3); text-align: center; border-top: 1px solid rgba(255, 255, 255, 0.1);">
                            <p style="color: rgba(255, 255, 255, 0.5); font-size: 12px; margin: 0 0 15px;">
                                Shoppaton Store - Premium Skincare Essentials
                            </p>
                            <p style="color: rgba(255, 255, 255, 0.5); font-size: 12px; margin: 0;">
                                Lagos, Nigeria | hello@shoppaton.com
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
