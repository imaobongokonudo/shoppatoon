<?php
/**
 * FAQ Page Template
 *
 * @package ShoppatonStore
 */

if (!defined('ABSPATH')) {
    exit;
}

$faqs = array(
    array(
        'question' => 'How do I place an order?',
        'answer' => 'Placing an order is easy! Simply browse our shop, add your desired products to cart, and proceed to checkout. Fill in your delivery details and complete payment using Paystack (card, bank transfer, or USSD).'
    ),
    array(
        'question' => 'Do I need an account to order?',
        'answer' => 'No, you can place orders as a guest without creating an account. However, having an account allows you to track your orders, save your details for faster checkout, and view your order history.'
    ),
    array(
        'question' => 'What payment methods do you accept?',
        'answer' => 'We accept payments via Paystack, which supports debit/credit cards (Visa, Mastercard, Verve), bank transfers, and USSD. All payments are secure and encrypted.'
    ),
    array(
        'question' => 'How much is delivery?',
        'answer' => 'Delivery costs vary by location. Lagos deliveries start from ₦1,500, Southwest states from ₦2,500, and other states from ₦4,500. Exact costs are calculated at checkout based on your delivery address.'
    ),
    array(
        'question' => 'How long does delivery take?',
        'answer' => 'Lagos: Same-day to next-day delivery. Southwest states: 2-3 business days. Other states: 3-5 business days. Delivery times may vary during peak periods or due to unforeseen circumstances.'
    ),
    array(
        'question' => 'How do I track my order?',
        'answer' => 'Once your order ships, you\'ll receive a tracking number via email and WhatsApp. You can also track your order on our Track Order page using your order number and email/phone.'
    ),
    array(
        'question' => 'Are your products authentic?',
        'answer' => 'Yes! We source all our products directly from authorized distributors and official brand channels. Every product is 100% authentic and original. We do not sell counterfeit or expired products.'
    ),
    array(
        'question' => 'What is your return policy?',
        'answer' => 'We have a 3-day return policy. If you receive a damaged, wrong, or expired product, contact us within 3 days of delivery with photos of the issue. We\'ll arrange for a replacement or refund.'
    ),
    array(
        'question' => 'Can I return a product if I don\'t like it?',
        'answer' => 'Unfortunately, we cannot accept returns for products simply because you changed your mind or don\'t like them. Please review product descriptions carefully before ordering. Returns are only accepted for damaged, wrong, or expired products.'
    ),
    array(
        'question' => 'How do I choose the right products for my skin?',
        'answer' => 'Each product listing includes information about skin type suitability and target users. If you\'re unsure, feel free to contact us via WhatsApp and our team will help recommend the best products for your skin type.'
    ),
    array(
        'question' => 'Do you offer bulk or wholesale orders?',
        'answer' => 'Yes, we cater to bulk and wholesale orders. Please contact us via WhatsApp or email for wholesale pricing and minimum order quantities.'
    ),
    array(
        'question' => 'How can I contact customer support?',
        'answer' => 'You can reach us via WhatsApp at ' . Shoppaton_Settings::instance()->get('whatsapp_number') . ' or email at ' . Shoppaton_Settings::instance()->get('contact_email') . '. Our team is available Monday to Saturday, 9 AM to 6 PM.'
    ),
);
?>

<?php include SHOPPATON_PLUGIN_DIR . 'templates/partials/header.php'; ?>

<div class="shoppaton-faq-page" style="padding-top: 120px; padding-bottom: 80px; min-height: 100vh;">
    <div class="shoppaton-container">
        <!-- Page Header -->
        <div class="shoppaton-page-header" style="text-align: center; margin-bottom: 60px;">
            <span class="shoppaton-section-subtitle">Help Center</span>
            <h1 class="shoppaton-gold-text-animated">Frequently Asked Questions</h1>
            <p style="color: var(--shoppaton-text-muted); max-width: 600px; margin: 20px auto 0;">
                Find answers to common questions about our products, ordering, shipping, and more.
            </p>
        </div>

        <!-- FAQ Accordion -->
        <div style="max-width: 800px; margin: 0 auto;">
            <?php foreach ($faqs as $index => $faq) : ?>
            <div class="shoppaton-faq-item">
                <div class="shoppaton-faq-question">
                    <span><?php echo esc_html($faq['question']); ?></span>
                    <span class="shoppaton-faq-icon">+</span>
                </div>
                <div class="shoppaton-faq-answer">
                    <p><?php echo esc_html($faq['answer']); ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Still Have Questions -->
        <div style="text-align: center; margin-top: 60px; padding: 60px 20px; background: var(--shoppaton-glass); border-radius: var(--border-radius-xl);">
            <h2 style="margin-bottom: 15px;">Still Have Questions?</h2>
            <p style="color: var(--shoppaton-text-muted); max-width: 500px; margin: 0 auto 30px;">
                Couldn't find the answer you were looking for? Our customer support team is here to help!
            </p>
            <div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap;">
                <a href="<?php echo esc_url(Shoppaton_Settings::instance()->get_whatsapp_link('Hello, I have a question.')); ?>" target="_blank" class="shoppaton-btn shoppaton-btn-primary">
                    💬 Chat on WhatsApp
                </a>
                <a href="mailto:<?php echo esc_attr(Shoppaton_Settings::instance()->get('contact_email')); ?>" class="shoppaton-btn shoppaton-btn-secondary">
                    📧 Send an Email
                </a>
            </div>
        </div>
    </div>
</div>

<?php include SHOPPATON_PLUGIN_DIR . 'templates/partials/footer.php'; ?>
