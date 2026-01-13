/**
 * Shoppaton Store - Main JavaScript
 * Premium E-commerce functionality
 */

(function($) {
    'use strict';

    // Main Shoppaton object
    window.Shoppaton = {
        init: function() {
            this.header();
            this.mobileMenu();
            this.heroSlider();
            this.scrollAnimations();
            this.scrollToTop();
            this.sectionIndicator();
            this.productActions();
            this.cart();
            this.wishlist();
            this.search();
            this.faq();
            this.forms();
            this.analytics();
            this.offlineSync();
            this.pageTransitions();
            this.lazyLoad();
        },

        // Header functionality
        header: function() {
            const header = $('.shoppaton-header');
            let lastScroll = 0;

            $(window).on('scroll', function() {
                const currentScroll = $(this).scrollTop();

                if (currentScroll > 100) {
                    header.addClass('scrolled');
                } else {
                    header.removeClass('scrolled');
                }

                lastScroll = currentScroll;
            });

            // Search toggle
            const searchOverlay = $(`
                <div class="shoppaton-search-overlay">
                    <button class="shoppaton-search-overlay-close">&times;</button>
                    <div class="shoppaton-search-overlay-content">
                        <form class="shoppaton-search-overlay-form" action="${$('.shoppaton-header').length ? window.location.origin + '/shop' : '/shop'}">
                            <input type="search" name="search" placeholder="Search products..." class="shoppaton-search-input" autocomplete="off">
                            <button type="submit">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="11" cy="11" r="8"/>
                                    <path d="M21 21l-4.35-4.35"/>
                                </svg>
                            </button>
                        </form>
                        <div class="shoppaton-search-results-overlay"></div>
                    </div>
                </div>
            `);
            $('body').append(searchOverlay);

            $('.shoppaton-search-toggle').on('click', function() {
                searchOverlay.addClass('active');
                searchOverlay.find('input').focus();
            });

            searchOverlay.find('.shoppaton-search-overlay-close').on('click', function() {
                searchOverlay.removeClass('active');
            });

            searchOverlay.on('click', function(e) {
                if ($(e.target).hasClass('shoppaton-search-overlay')) {
                    searchOverlay.removeClass('active');
                }
            });

            $(document).on('keydown', function(e) {
                if (e.key === 'Escape') {
                    searchOverlay.removeClass('active');
                }
            });
        },

        // Mobile menu
        mobileMenu: function() {
            const hamburger = $('.shoppaton-hamburger');
            const mobileMenu = $('.shoppaton-mobile-menu');
            const overlay = $('<div class="shoppaton-menu-overlay"></div>');
            
            $('body').append(overlay);

            hamburger.on('click', function() {
                $(this).toggleClass('active');
                mobileMenu.toggleClass('active');
                overlay.toggleClass('active');
                $('body').toggleClass('menu-open');
            });

            overlay.on('click', function() {
                hamburger.removeClass('active');
                mobileMenu.removeClass('active');
                overlay.removeClass('active');
                $('body').removeClass('menu-open');
            });

            // Close menu on link click
            $('.shoppaton-mobile-menu-list a').on('click', function() {
                hamburger.removeClass('active');
                mobileMenu.removeClass('active');
                overlay.removeClass('active');
                $('body').removeClass('menu-open');
            });
        },

        // Hero slider
        heroSlider: function() {
            const slider = $('.shoppaton-hero-slider');
            if (!slider.length) return;

            const slides = slider.find('.shoppaton-hero-slide');
            let currentSlide = 0;
            const totalSlides = slides.length;

            if (totalSlides <= 1) return;

            function showSlide(index) {
                slides.removeClass('active');
                slides.eq(index).addClass('active');
            }

            function nextSlide() {
                currentSlide = (currentSlide + 1) % totalSlides;
                showSlide(currentSlide);
            }

            // Auto-advance every 5 seconds
            setInterval(nextSlide, 5000);
        },

        // Scroll animations
        scrollAnimations: function() {
            const animatedElements = $('.shoppaton-animate.animate-on-scroll');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animated');
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            });

            animatedElements.each(function() {
                observer.observe(this);
            });
        },

        // Scroll to top with progress
        scrollToTop: function() {
            const scrollTop = $('.shoppaton-scroll-top');
            const progressBar = scrollTop.find('.progress-bar');

            $(window).on('scroll', function() {
                const scrollHeight = $(document).height() - $(window).height();
                const scrolled = $(window).scrollTop();
                const progress = (scrolled / scrollHeight) * 126;

                if (scrolled > 300) {
                    scrollTop.addClass('visible');
                } else {
                    scrollTop.removeClass('visible');
                }

                progressBar.css('stroke-dashoffset', 126 - progress);
            });

            scrollTop.on('click', function() {
                $('html, body').animate({ scrollTop: 0 }, 600);
            });
        },

        // Section indicator
        sectionIndicator: function() {
            const sections = $('[data-section]');
            const indicator = $('.shoppaton-section-indicator');
            
            if (!sections.length) return;

            // Create dots
            let dots = '';
            sections.each(function(i) {
                const name = $(this).data('section');
                dots += `<div class="shoppaton-section-dot" data-index="${i}" title="${name}"></div>`;
            });
            
            indicator.find('.shoppaton-section-dots').html(dots);

            // Update active dot on scroll
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const index = $(entry.target).index('[data-section]');
                        $('.shoppaton-section-dot').removeClass('active');
                        $(`.shoppaton-section-dot[data-index="${index}"]`).addClass('active');
                    }
                });
            }, {
                threshold: 0.5
            });

            sections.each(function() {
                observer.observe(this);
            });

            // Click to scroll
            $(document).on('click', '.shoppaton-section-dot', function() {
                const index = $(this).data('index');
                const target = $('[data-section]').eq(index);
                $('html, body').animate({
                    scrollTop: target.offset().top - 100
                }, 600);
            });
        },

        // Product actions
        productActions: function() {
            // Quick view
            $(document).on('click', '.shoppaton-quick-view', function(e) {
                e.preventDefault();
                const productId = $(this).closest('[data-product-id]').data('product-id');
                Shoppaton.showQuickView(productId);
            });

            // Image zoom on hover
            $('.shoppaton-product-image').each(function() {
                const img = $(this).find('img');
                $(this).on('mousemove', function(e) {
                    const x = (e.pageX - $(this).offset().left) / $(this).width() * 100;
                    const y = (e.pageY - $(this).offset().top) / $(this).height() * 100;
                    img.css('transform-origin', `${x}% ${y}%`);
                });
            });
        },

        // Cart functionality
        cart: {
            items: [],

            init: function() {
                this.loadCart();
                this.bindEvents();
            },

            loadCart: function() {
                // Try to load from localStorage first (offline support)
                const localCart = localStorage.getItem('shoppaton_cart');
                if (localCart) {
                    this.items = JSON.parse(localCart);
                    this.updateCount();
                }

                // Sync with server if online
                if (navigator.onLine) {
                    this.syncWithServer();
                }
            },

            saveCart: function() {
                localStorage.setItem('shoppaton_cart', JSON.stringify(this.items));
            },

            syncWithServer: function() {
                if (!navigator.onLine) return;

                $.ajax({
                    url: shoppatonData.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'shoppaton_sync_cart',
                        nonce: shoppatonData.nonce,
                        cart: JSON.stringify(this.items)
                    },
                    success: (response) => {
                        if (response.success && response.data.cart) {
                            this.items = response.data.cart;
                            this.saveCart();
                            this.updateCount();
                            this.updateCartUI();
                        }
                    }
                });
            },

            bindEvents: function() {
                const self = this;

                // Add to cart
                $(document).on('click', '.shoppaton-add-to-cart', function(e) {
                    e.preventDefault();
                    const productId = $(this).closest('[data-product-id]').data('product-id');
                    const quantity = parseInt($(this).closest('.shoppaton-product-actions').find('.shoppaton-quantity-input').val()) || 1;
                    self.addItem(productId, quantity);
                    
                    // Animation
                    $(this).addClass('shoppaton-cart-add');
                    setTimeout(() => $(this).removeClass('shoppaton-cart-add'), 500);
                });

                // Buy now
                $(document).on('click', '.shoppaton-buy-now', function(e) {
                    e.preventDefault();
                    const productId = $(this).closest('[data-product-id]').data('product-id');
                    self.addItem(productId, 1, true);
                });

                // Update quantity
                $(document).on('click', '.shoppaton-quantity-btn', function() {
                    const input = $(this).siblings('.shoppaton-quantity-input');
                    let value = parseInt(input.val()) || 1;
                    
                    if ($(this).hasClass('plus')) {
                        value++;
                    } else if ($(this).hasClass('minus') && value > 1) {
                        value--;
                    }
                    
                    input.val(value).trigger('change');
                });

                // Quantity change
                $(document).on('change', '.shoppaton-cart-item .shoppaton-quantity-input', function() {
                    const productId = $(this).closest('.shoppaton-cart-item').data('product-id');
                    const quantity = parseInt($(this).val()) || 1;
                    self.updateItem(productId, quantity);
                });

                // Remove item
                $(document).on('click', '.shoppaton-cart-item-remove', function() {
                    const productId = $(this).closest('.shoppaton-cart-item').data('product-id');
                    self.removeItem(productId);
                });
            },

            addItem: function(productId, quantity, redirect) {
                quantity = quantity || 1;

                $.ajax({
                    url: shoppatonData.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'shoppaton_add_to_cart',
                        nonce: shoppatonData.nonce,
                        product_id: productId,
                        quantity: quantity
                    },
                    success: (response) => {
                        if (response.success) {
                            this.items = response.data.cart;
                            this.saveCart();
                            this.updateCount();
                            Shoppaton.toast('Product added to cart!', 'success');
                            
                            if (redirect) {
                                window.location.href = shoppatonData.checkoutUrl;
                            }
                        } else {
                            Shoppaton.toast(response.data.message || 'Error adding to cart', 'error');
                        }
                    },
                    error: () => {
                        // Store offline
                        const existingItem = this.items.find(item => item.product_id === productId);
                        if (existingItem) {
                            existingItem.quantity += quantity;
                        } else {
                            this.items.push({ product_id: productId, quantity: quantity, pending: true });
                        }
                        this.saveCart();
                        this.updateCount();
                        Shoppaton.toast('Added to cart (will sync when online)', 'success');
                    }
                });
            },

            updateItem: function(productId, quantity) {
                $.ajax({
                    url: shoppatonData.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'shoppaton_update_cart_item',
                        nonce: shoppatonData.nonce,
                        product_id: productId,
                        quantity: quantity
                    },
                    success: (response) => {
                        if (response.success) {
                            this.items = response.data.cart;
                            this.saveCart();
                            this.updateCartUI();
                        }
                    }
                });
            },

            removeItem: function(productId) {
                $.ajax({
                    url: shoppatonData.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'shoppaton_remove_from_cart',
                        nonce: shoppatonData.nonce,
                        product_id: productId
                    },
                    success: (response) => {
                        if (response.success) {
                            this.items = response.data.cart;
                            this.saveCart();
                            this.updateCount();
                            this.updateCartUI();
                            Shoppaton.toast('Item removed from cart', 'success');
                        }
                    }
                });
            },

            updateCount: function() {
                const count = this.items.reduce((sum, item) => sum + item.quantity, 0);
                $('.shoppaton-cart-count').text(count);
                
                if (count > 0) {
                    $('.shoppaton-cart-count').addClass('shoppaton-notification-pop');
                    setTimeout(() => $('.shoppaton-cart-count').removeClass('shoppaton-notification-pop'), 300);
                }
            },

            updateCartUI: function() {
                // Trigger custom event for cart page to update
                $(document).trigger('shoppaton:cart:updated', [this.items]);
            },

            getTotal: function() {
                return this.items.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            }
        },

        // Wishlist functionality
        wishlist: {
            items: [],

            init: function() {
                const stored = localStorage.getItem('shoppaton_wishlist');
                if (stored) {
                    this.items = JSON.parse(stored);
                }
                this.bindEvents();
            },

            bindEvents: function() {
                const self = this;

                $(document).on('click', '.shoppaton-add-to-wishlist', function(e) {
                    e.preventDefault();
                    const productId = $(this).closest('[data-product-id]').data('product-id');
                    self.toggle(productId, $(this));
                });
            },

            toggle: function(productId, button) {
                const index = this.items.indexOf(productId);
                
                if (index > -1) {
                    this.items.splice(index, 1);
                    button.removeClass('active');
                    Shoppaton.toast('Removed from wishlist', 'success');
                } else {
                    this.items.push(productId);
                    button.addClass('active');
                    button.addClass('shoppaton-heartbeat');
                    setTimeout(() => button.removeClass('shoppaton-heartbeat'), 1500);
                    Shoppaton.toast('Added to wishlist', 'success');
                }

                localStorage.setItem('shoppaton_wishlist', JSON.stringify(this.items));

                // Sync with server
                $.ajax({
                    url: shoppatonData.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'shoppaton_toggle_wishlist',
                        nonce: shoppatonData.nonce,
                        product_id: productId
                    }
                });
            }
        },

        // Search functionality
        search: function() {
            const searchInput = $('.shoppaton-search-input');
            const searchResults = $('.shoppaton-search-results');
            let searchTimer;

            searchInput.on('input', function() {
                clearTimeout(searchTimer);
                const query = $(this).val().trim();

                if (query.length < 2) {
                    searchResults.hide();
                    return;
                }

                searchTimer = setTimeout(() => {
                    $.ajax({
                        url: shoppatonData.ajaxUrl,
                        type: 'POST',
                        data: {
                            action: 'shoppaton_search_products',
                            nonce: shoppatonData.nonce,
                            query: query
                        },
                        success: (response) => {
                            if (response.success && response.data.products.length) {
                                let html = '';
                                response.data.products.forEach(product => {
                                    html += `
                                        <a href="${product.url}" class="shoppaton-search-result-item">
                                            <img src="${product.image}" alt="${product.name}">
                                            <div class="shoppaton-search-result-info">
                                                <div class="shoppaton-search-result-name">${product.name}</div>
                                                <div class="shoppaton-search-result-price">${product.price}</div>
                                            </div>
                                        </a>
                                    `;
                                });
                                searchResults.html(html).show();
                            } else {
                                searchResults.html('<div class="shoppaton-search-no-results">No products found</div>').show();
                            }
                        }
                    });
                }, 300);
            });

            // Close on click outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.shoppaton-search-form').length) {
                    searchResults.hide();
                }
            });
        },

        // FAQ accordion
        faq: function() {
            $(document).on('click', '.shoppaton-faq-question', function() {
                const item = $(this).closest('.shoppaton-faq-item');
                const wasActive = item.hasClass('active');
                
                $('.shoppaton-faq-item').removeClass('active');
                
                if (!wasActive) {
                    item.addClass('active');
                }
            });
        },

        // Forms
        forms: function() {
            // Contact form
            $(document).on('submit', '.shoppaton-contact-form', function(e) {
                e.preventDefault();
                const form = $(this);
                const btn = form.find('button[type="submit"]');
                const originalText = btn.text();
                
                btn.prop('disabled', true).text('Sending...');

                $.ajax({
                    url: shoppatonData.ajaxUrl,
                    type: 'POST',
                    data: form.serialize() + '&action=shoppaton_contact_form&nonce=' + shoppatonData.nonce,
                    success: (response) => {
                        if (response.success) {
                            Shoppaton.toast('Message sent successfully!', 'success');
                            form[0].reset();
                        } else {
                            Shoppaton.toast(response.data.message || 'Error sending message', 'error');
                        }
                    },
                    error: () => {
                        Shoppaton.toast('Error sending message. Please try again.', 'error');
                    },
                    complete: () => {
                        btn.prop('disabled', false).text(originalText);
                    }
                });
            });

            // Newsletter form
            $(document).on('submit', '.shoppaton-newsletter-form', function(e) {
                e.preventDefault();
                const email = $(this).find('input[type="email"]').val();
                
                $.ajax({
                    url: shoppatonData.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'shoppaton_newsletter_subscribe',
                        nonce: shoppatonData.nonce,
                        email: email
                    },
                    success: (response) => {
                        if (response.success) {
                            Shoppaton.toast('Subscribed successfully!', 'success');
                            $(this)[0].reset();
                        } else {
                            Shoppaton.toast(response.data.message || 'Error subscribing', 'error');
                        }
                    }
                });
            });
        },

        // Analytics tracking
        analytics: function() {
            // Track page views
            this.trackEvent('page_view', {
                page: window.location.pathname,
                referrer: document.referrer
            });

            // Track product views
            $(document).on('click', '.shoppaton-product-card', function() {
                const productId = $(this).data('product-id');
                Shoppaton.trackEvent('product_view', { product_id: productId });
            });

            // Track add to cart
            $(document).on('click', '.shoppaton-add-to-cart', function() {
                const productId = $(this).closest('[data-product-id]').data('product-id');
                Shoppaton.trackEvent('add_to_cart', { product_id: productId });
            });

            // Track search
            $(document).on('submit', '.shoppaton-search-form', function() {
                const query = $(this).find('.shoppaton-search-input').val();
                Shoppaton.trackEvent('search', { query: query });
            });
        },

        trackEvent: function(eventType, data) {
            if (!navigator.onLine) {
                // Store for later
                let pendingEvents = JSON.parse(localStorage.getItem('shoppaton_pending_events') || '[]');
                pendingEvents.push({ type: eventType, data: data, timestamp: Date.now() });
                localStorage.setItem('shoppaton_pending_events', JSON.stringify(pendingEvents));
                return;
            }

            $.ajax({
                url: shoppatonData.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'shoppaton_track_event',
                    nonce: shoppatonData.nonce,
                    event_type: eventType,
                    event_data: JSON.stringify(data)
                }
            });
        },

        // Offline sync
        offlineSync: function() {
            window.addEventListener('online', () => {
                Shoppaton.toast('Back online! Syncing your data...', 'success');
                
                // Sync cart
                this.cart.syncWithServer();

                // Sync pending events
                const pendingEvents = JSON.parse(localStorage.getItem('shoppaton_pending_events') || '[]');
                if (pendingEvents.length) {
                    pendingEvents.forEach(event => {
                        this.trackEvent(event.type, event.data);
                    });
                    localStorage.removeItem('shoppaton_pending_events');
                }
            });

            window.addEventListener('offline', () => {
                Shoppaton.toast('You are offline. Your cart will sync when you\'re back online.', 'error');
            });
        },

        // Page transitions
        pageTransitions: function() {
            const transition = $('<div class="shoppaton-page-transition"><img src="' + shoppatonData.assetsUrl + 'images/logo.png" alt="Loading..." class="shoppaton-logo"></div>');
            $('body').append(transition);

            // Show transition on internal links (optional - can be resource intensive)
            // Uncomment if smooth page transitions are desired
            /*
            $('a').not('[href^="http"]').not('[href^="#"]').not('[target="_blank"]').on('click', function(e) {
                const href = $(this).attr('href');
                if (href && href !== '#') {
                    e.preventDefault();
                    transition.addClass('active');
                    setTimeout(() => {
                        window.location.href = href;
                    }, 500);
                }
            });
            */
        },

        // Lazy load images
        lazyLoad: function() {
            const images = document.querySelectorAll('img[data-src]');
            
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                        img.classList.add('loaded');
                        observer.unobserve(img);
                    }
                });
            }, {
                rootMargin: '50px 0px'
            });

            images.forEach(img => imageObserver.observe(img));
        },

        // Quick view modal
        showQuickView: function(productId) {
            $.ajax({
                url: shoppatonData.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'shoppaton_get_product',
                    nonce: shoppatonData.nonce,
                    product_id: productId
                },
                success: (response) => {
                    if (response.success) {
                        const product = response.data.product;
                        const modal = $(`
                            <div class="shoppaton-modal-overlay">
                                <div class="shoppaton-modal shoppaton-glass-card">
                                    <button class="shoppaton-modal-close">&times;</button>
                                    <div class="shoppaton-quick-view-content">
                                        <div class="shoppaton-quick-view-image">
                                            <img src="${product.image}" alt="${product.name}">
                                        </div>
                                        <div class="shoppaton-quick-view-info">
                                            <h3>${product.name}</h3>
                                            <div class="shoppaton-product-price">
                                                <span class="current">${product.price}</span>
                                                ${product.sale_price ? `<span class="original">${product.regular_price}</span>` : ''}
                                            </div>
                                            <p>${product.short_description}</p>
                                            ${product.skin_type ? `<p><strong>Skin Type:</strong> ${product.skin_type}</p>` : ''}
                                            ${product.target_user ? `<p><strong>Best For:</strong> ${product.target_user}</p>` : ''}
                                            <div class="shoppaton-product-actions" data-product-id="${product.id}">
                                                <div class="shoppaton-quantity-control">
                                                    <button class="shoppaton-quantity-btn minus">-</button>
                                                    <input type="number" class="shoppaton-quantity-input" value="1" min="1">
                                                    <button class="shoppaton-quantity-btn plus">+</button>
                                                </div>
                                                <button class="shoppaton-btn shoppaton-btn-primary shoppaton-add-to-cart">Add to Cart</button>
                                                <button class="shoppaton-btn shoppaton-btn-secondary shoppaton-buy-now">Buy Now</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `);

                        $('body').append(modal);
                        setTimeout(() => modal.addClass('active'), 10);

                        modal.on('click', '.shoppaton-modal-close, .shoppaton-modal-overlay', function(e) {
                            if (e.target === this) {
                                modal.removeClass('active');
                                setTimeout(() => modal.remove(), 300);
                            }
                        });
                    }
                }
            });
        },

        // Toast notification
        toast: function(message, type) {
            const toast = $(`<div class="shoppaton-toast ${type}"><span class="shoppaton-toast-message">${message}</span></div>`);
            $('body').append(toast);
            
            setTimeout(() => toast.addClass('show'), 10);
            setTimeout(() => {
                toast.removeClass('show');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        Shoppaton.init();
        Shoppaton.cart.init();
        Shoppaton.wishlist.init();
    });

    // Paystack Payment Handler
    window.ShoppatonPayment = {
        init: function(orderData) {
            if (!shoppatonData.paystackKey) {
                Shoppaton.toast('Payment configuration error', 'error');
                return;
            }

            const handler = PaystackPop.setup({
                key: shoppatonData.paystackKey,
                email: orderData.email,
                amount: orderData.amount * 100, // Paystack uses kobo
                currency: 'NGN',
                ref: orderData.reference,
                metadata: {
                    custom_fields: [
                        {
                            display_name: "Customer Name",
                            variable_name: "customer_name",
                            value: orderData.name
                        },
                        {
                            display_name: "Phone Number",
                            variable_name: "phone",
                            value: orderData.phone
                        }
                    ]
                },
                callback: function(response) {
                    ShoppatonPayment.verifyPayment(response.reference);
                },
                onClose: function() {
                    Shoppaton.toast('Payment cancelled', 'error');
                }
            });

            handler.openIframe();
        },

        verifyPayment: function(reference) {
            $.ajax({
                url: shoppatonData.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'shoppaton_verify_payment',
                    nonce: shoppatonData.nonce,
                    reference: reference
                },
                success: function(response) {
                    if (response.success) {
                        window.location.href = response.data.redirect;
                    } else {
                        Shoppaton.toast(response.data.message || 'Payment verification failed', 'error');
                    }
                },
                error: function() {
                    Shoppaton.toast('Error verifying payment', 'error');
                }
            });
        }
    };

})(jQuery);
