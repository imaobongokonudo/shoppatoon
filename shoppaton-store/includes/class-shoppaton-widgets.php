<?php
/**
 * Widgets Class
 *
 * @package ShoppatonStore
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Shoppaton Widgets Class
 */
class Shoppaton_Widgets {

    /**
     * Single instance
     *
     * @var Shoppaton_Widgets
     */
    private static $instance = null;

    /**
     * Get instance
     *
     * @return Shoppaton_Widgets
     */
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        // Register widgets
    }

    /**
     * Render widgets
     */
    public function render() {
        $this->render_whatsapp_widget();
        $this->render_scroll_to_top();
    }

    /**
     * Render WhatsApp widget
     */
    private function render_whatsapp_widget() {
        $whatsapp_link = Shoppaton_Settings::instance()->get_whatsapp_link('Hello, I would like to inquire about your products.');
        ?>
        <div class="shoppaton-whatsapp-widget">
            <a href="<?php echo esc_url($whatsapp_link); ?>" target="_blank" rel="noopener noreferrer" class="shoppaton-whatsapp-btn" title="Chat with us on WhatsApp">
                <svg viewBox="0 0 24 24" width="30" height="30" fill="currentColor">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
            </a>
        </div>
        <?php
    }

    /**
     * Render scroll to top with progress
     */
    private function render_scroll_to_top() {
        ?>
        <style>
        /* Scroll to Top - Single Circle with Gold Arrow */
        .shoppaton-scroll-top {
            position: fixed;
            bottom: 30px;
            left: 30px;
            width: 50px;
            height: 50px;
            background: rgba(20, 20, 20, 0.9);
            border: 2px solid #D4AF37;
            border-radius: 50%;
            cursor: pointer;
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 15px rgba(212, 175, 55, 0.3);
        }
        .shoppaton-scroll-top.visible {
            opacity: 1;
            visibility: visible;
        }
        .shoppaton-scroll-top:hover {
            background: #D4AF37;
            box-shadow: 0 0 25px rgba(212, 175, 55, 0.5);
            transform: translateY(-3px);
        }
        .shoppaton-scroll-top:hover .scroll-arrow {
            stroke: #0a0a0a;
        }
        /* Progress Ring */
        .shoppaton-scroll-progress {
            position: absolute;
            top: -3px;
            left: -3px;
            width: 56px;
            height: 56px;
            transform: rotate(-90deg);
        }
        .shoppaton-scroll-progress circle {
            fill: none;
            stroke-width: 3;
            stroke-linecap: round;
        }
        .progress-track {
            stroke: rgba(212, 175, 55, 0.2);
        }
        .progress-fill {
            stroke: #D4AF37;
            stroke-dasharray: 163;
            stroke-dashoffset: 163;
            transition: stroke-dashoffset 0.1s;
        }
        /* Arrow Icon */
        .scroll-arrow {
            width: 20px;
            height: 20px;
            stroke: #D4AF37;
            stroke-width: 2.5;
            fill: none;
            transition: stroke 0.3s;
        }
        </style>
        <button class="shoppaton-scroll-top" aria-label="Scroll to top">
            <svg class="shoppaton-scroll-progress" viewBox="0 0 56 56">
                <circle class="progress-track" cx="28" cy="28" r="26"/>
                <circle class="progress-fill" cx="28" cy="28" r="26"/>
            </svg>
            <svg class="scroll-arrow" viewBox="0 0 24 24">
                <polyline points="18 15 12 9 6 15"/>
            </svg>
        </button>
        <script>
        (function() {
            var btn = document.querySelector('.shoppaton-scroll-top');
            var progressCircle = document.querySelector('.progress-fill');
            if (!btn || !progressCircle) return;
            
            var circumference = 2 * Math.PI * 26;
            progressCircle.style.strokeDasharray = circumference;
            
            function updateProgress() {
                var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                var docHeight = document.documentElement.scrollHeight - window.innerHeight;
                var scrollPercent = docHeight > 0 ? scrollTop / docHeight : 0;
                var offset = circumference - (scrollPercent * circumference);
                progressCircle.style.strokeDashoffset = offset;
                
                if (scrollTop > 300) {
                    btn.classList.add('visible');
                } else {
                    btn.classList.remove('visible');
                }
            }
            
            window.addEventListener('scroll', updateProgress);
            btn.addEventListener('click', function() {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
            updateProgress();
        })();
        </script>
        <?php
    }
}
