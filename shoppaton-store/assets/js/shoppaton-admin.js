/**
 * Shoppaton Store - Admin JavaScript
 *
 * @package ShoppatonStore
 */

(function($) {
    'use strict';

    // Admin namespace
    window.ShoppatonAdmin = {
        
        /**
         * Initialize admin functionality
         */
        init: function() {
            this.initMediaUploader();
            this.initProductManager();
            this.initOrderManager();
            this.initCharts();
            this.initModalHandlers();
            this.initDragAndDrop();
        },

        /**
         * Initialize WordPress Media Uploader
         */
        initMediaUploader: function() {
            var self = this;

            // Single image upload
            $(document).on('click', '.shoppaton-upload-btn', function(e) {
                e.preventDefault();
                var $btn = $(this);
                var field = $btn.data('field');
                var multiple = $btn.data('multiple') || false;

                var frame = wp.media({
                    title: 'Select Image',
                    button: { text: 'Use Image' },
                    multiple: multiple
                });

                frame.on('select', function() {
                    var attachments = frame.state().get('selection').toJSON();
                    self.handleMediaSelection(field, attachments, multiple);
                });

                frame.open();
            });

            // Product images upload
            $(document).on('click', '.shoppaton-upload-product-images', function(e) {
                e.preventDefault();
                var frame = wp.media({
                    title: 'Select Product Images',
                    button: { text: 'Add Images' },
                    multiple: true
                });

                frame.on('select', function() {
                    var attachments = frame.state().get('selection').toJSON();
                    self.addProductImages(attachments);
                });

                frame.open();
            });

            // Remove image
            $(document).on('click', '.shoppaton-remove-image', function() {
                var $item = $(this).closest('.shoppaton-media-preview-item');
                var field = $item.closest('.shoppaton-media-uploader').data('field');
                $item.fadeOut(300, function() {
                    $(this).remove();
                    self.updateImageField(field);
                });
            });
        },

        /**
         * Handle media selection
         */
        handleMediaSelection: function(field, attachments, multiple) {
            var $preview = $('#' + field.replace(/_/g, '-') + '-preview');
            
            if (!multiple) {
                $preview.empty();
            }

            attachments.forEach(function(attachment) {
                var html = '<div class="shoppaton-media-preview-item" data-id="' + attachment.id + '">' +
                    '<img src="' + (attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url) + '" alt="">' +
                    '<button type="button" class="shoppaton-remove-image">&times;</button>' +
                    '</div>';
                $preview.append(html);
            });

            this.updateImageField(field);
        },

        /**
         * Add product images
         */
        addProductImages: function(attachments) {
            var $preview = $('#product-images-preview');
            
            attachments.forEach(function(attachment) {
                var html = '<div class="shoppaton-media-preview-item" data-id="' + attachment.id + '">' +
                    '<img src="' + (attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url) + '" alt="">' +
                    '<button type="button" class="shoppaton-remove-image">&times;</button>' +
                    '</div>';
                $preview.append(html);
            });
        },

        /**
         * Update hidden image field
         */
        updateImageField: function(field) {
            var $preview = $('#' + field.replace(/_/g, '-') + '-preview');
            var ids = [];
            
            $preview.find('.shoppaton-media-preview-item').each(function() {
                ids.push($(this).data('id'));
            });

            // Update or create hidden field
            var $input = $('input[name="' + field + '"]');
            if ($input.length === 0) {
                $input = $('<input type="hidden" name="' + field + '">');
                $preview.after($input);
            }
            $input.val(ids.join(','));
        },

        /**
         * Initialize Product Manager
         */
        initProductManager: function() {
            var self = this;

            // Edit product
            $(document).on('click', '.edit-product', function() {
                var productId = $(this).data('id');
                self.loadProduct(productId);
            });

            // Delete product
            $(document).on('click', '.delete-product', function() {
                var productId = $(this).data('id');
                if (confirm('Are you sure you want to delete this product?')) {
                    self.deleteProduct(productId);
                }
            });

            // Bulk actions
            $('#apply-bulk-action').on('click', function() {
                var action = $('#bulk-action-select').val();
                if (!action) return;

                var ids = [];
                $('.product-checkbox:checked').each(function() {
                    ids.push($(this).val());
                });

                if (ids.length === 0) {
                    Shoppaton.toast('Please select at least one product', 'warning');
                    return;
                }

                self.bulkProductAction(action, ids);
            });

            // Select all products
            $('#select-all-products').on('change', function() {
                $('.product-checkbox').prop('checked', $(this).prop('checked'));
            });
        },

        /**
         * Load product for editing
         */
        loadProduct: function(productId) {
            $.ajax({
                url: shoppatonData.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'shoppaton_admin_get_product',
                    nonce: shoppatonData.nonce,
                    product_id: productId
                },
                success: function(response) {
                    if (response.success) {
                        var product = response.data;
                        
                        $('#product-modal-title').text('Edit Product');
                        $('#product-id').val(product.id);
                        $('#product-name').val(product.name);
                        $('#product-price').val(product.price);
                        $('#product-compare-price').val(product.compare_price);
                        $('#product-category').val(product.category);
                        $('#product-stock').val(product.stock);
                        $('#product-description').val(product.description);
                        $('#product-skin-type').val(product.skin_type);
                        $('#product-target-user').val(product.target_user);
                        $('#product-status').val(product.status);

                        // Load images
                        var $preview = $('#product-images-preview').empty();
                        if (product.images && product.images.length) {
                            product.images.forEach(function(image) {
                                var html = '<div class="shoppaton-media-preview-item" data-id="' + image.id + '">' +
                                    '<img src="' + image.thumbnail + '" alt="">' +
                                    '<button type="button" class="shoppaton-remove-image">&times;</button>' +
                                    '</div>';
                                $preview.append(html);
                            });
                        }

                        $('#product-modal').show();
                    }
                }
            });
        },

        /**
         * Delete product
         */
        deleteProduct: function(productId) {
            $.ajax({
                url: shoppatonData.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'shoppaton_admin_delete_product',
                    nonce: shoppatonData.nonce,
                    product_id: productId
                },
                success: function(response) {
                    if (response.success) {
                        Shoppaton.toast('Product deleted successfully', 'success');
                        // Reload products list
                        if (typeof loadProducts === 'function') {
                            loadProducts();
                        }
                    } else {
                        Shoppaton.toast(response.data.message || 'Error deleting product', 'error');
                    }
                }
            });
        },

        /**
         * Bulk product action
         */
        bulkProductAction: function(action, ids) {
            $.ajax({
                url: shoppatonData.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'shoppaton_admin_bulk_product_action',
                    nonce: shoppatonData.nonce,
                    bulk_action: action,
                    product_ids: ids
                },
                success: function(response) {
                    if (response.success) {
                        Shoppaton.toast('Bulk action completed successfully', 'success');
                        if (typeof loadProducts === 'function') {
                            loadProducts();
                        }
                    } else {
                        Shoppaton.toast(response.data.message || 'Error performing bulk action', 'error');
                    }
                }
            });
        },

        /**
         * Initialize Order Manager
         */
        initOrderManager: function() {
            var self = this;

            // View order
            $(document).on('click', '.view-order', function() {
                var orderId = $(this).data('id');
                self.loadOrder(orderId);
            });

            // Update order status
            $(document).on('change', '#order-status-select', function() {
                var orderId = $(this).data('order-id');
                var status = $(this).val();
                self.updateOrderStatus(orderId, status);
            });
        },

        /**
         * Load order details
         */
        loadOrder: function(orderId) {
            $.ajax({
                url: shoppatonData.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'shoppaton_admin_get_order',
                    nonce: shoppatonData.nonce,
                    order_id: orderId
                },
                success: function(response) {
                    if (response.success) {
                        var order = response.data;
                        
                        var html = '<div class="shoppaton-order-details">' +
                            '<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">' +
                                '<div>' +
                                    '<h3 style="margin: 0; color: var(--shoppaton-gold);">#' + order.order_number + '</h3>' +
                                    '<p style="margin: 5px 0 0; color: var(--shoppaton-text-muted);">' + order.created_at + '</p>' +
                                '</div>' +
                                '<select id="order-status-select" class="shoppaton-form-select" data-order-id="' + order.id + '" style="width: auto;">' +
                                    '<option value="pending"' + (order.status === 'pending' ? ' selected' : '') + '>Pending</option>' +
                                    '<option value="processing"' + (order.status === 'processing' ? ' selected' : '') + '>Processing</option>' +
                                    '<option value="shipped"' + (order.status === 'shipped' ? ' selected' : '') + '>Shipped</option>' +
                                    '<option value="delivered"' + (order.status === 'delivered' ? ' selected' : '') + '>Delivered</option>' +
                                    '<option value="cancelled"' + (order.status === 'cancelled' ? ' selected' : '') + '>Cancelled</option>' +
                                '</select>' +
                            '</div>' +
                            
                            '<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">' +
                                '<div style="background: var(--shoppaton-black-light); padding: 15px; border-radius: var(--border-radius);">' +
                                    '<h4 style="margin: 0 0 10px; color: var(--shoppaton-white);">Customer</h4>' +
                                    '<p style="margin: 0; color: var(--shoppaton-text-muted);">' + order.customer.name + '</p>' +
                                    '<p style="margin: 0; color: var(--shoppaton-text-muted);">' + order.customer.email + '</p>' +
                                    '<p style="margin: 0; color: var(--shoppaton-text-muted);">' + order.customer.phone + '</p>' +
                                '</div>' +
                                '<div style="background: var(--shoppaton-black-light); padding: 15px; border-radius: var(--border-radius);">' +
                                    '<h4 style="margin: 0 0 10px; color: var(--shoppaton-white);">Shipping Address</h4>' +
                                    '<p style="margin: 0; color: var(--shoppaton-text-muted);">' + order.shipping.street + '</p>' +
                                    '<p style="margin: 0; color: var(--shoppaton-text-muted);">' + order.shipping.city + ', ' + order.shipping.state + '</p>' +
                                '</div>' +
                            '</div>' +
                            
                            '<h4 style="margin: 0 0 15px; color: var(--shoppaton-white);">Order Items</h4>' +
                            '<table class="shoppaton-admin-table">' +
                                '<thead><tr><th>Product</th><th>Price</th><th>Qty</th><th>Total</th></tr></thead>' +
                                '<tbody>';
                        
                        order.items.forEach(function(item) {
                            html += '<tr>' +
                                '<td>' + item.name + '</td>' +
                                '<td>₦' + parseFloat(item.price).toLocaleString() + '</td>' +
                                '<td>' + item.quantity + '</td>' +
                                '<td>₦' + parseFloat(item.total).toLocaleString() + '</td>' +
                            '</tr>';
                        });
                        
                        html += '</tbody></table>' +
                            
                            '<div style="margin-top: 20px; text-align: right;">' +
                                '<p style="margin: 5px 0; color: var(--shoppaton-text-muted);">Subtotal: ₦' + parseFloat(order.subtotal).toLocaleString() + '</p>' +
                                '<p style="margin: 5px 0; color: var(--shoppaton-text-muted);">Shipping: ₦' + parseFloat(order.shipping_cost).toLocaleString() + '</p>' +
                                '<p style="margin: 5px 0; font-size: 18px; color: var(--shoppaton-gold);">Total: ₦' + parseFloat(order.total).toLocaleString() + '</p>' +
                            '</div>';
                        
                        // Tracking info section
                        html += '<div style="margin-top: 20px; padding: 20px; background: var(--shoppaton-black-light); border-radius: var(--border-radius);">' +
                            '<h4 style="margin: 0 0 15px; color: var(--shoppaton-white);">Tracking Information</h4>' +
                            '<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">' +
                                '<div class="shoppaton-form-group">' +
                                    '<label class="shoppaton-form-label">Delivery Company</label>' +
                                    '<input type="text" id="tracking-company" class="shoppaton-form-input" value="' + (order.tracking.delivery_company || '') + '">' +
                                '</div>' +
                                '<div class="shoppaton-form-group">' +
                                    '<label class="shoppaton-form-label">Tracking Number</label>' +
                                    '<input type="text" id="tracking-number" class="shoppaton-form-input" value="' + (order.tracking.tracking_number || '') + '">' +
                                '</div>' +
                            '</div>' +
                            '<button type="button" class="shoppaton-btn shoppaton-btn-secondary" id="save-tracking-btn" data-order-id="' + order.id + '" style="margin-top: 10px;">Save Tracking Info</button>' +
                        '</div>' +
                        '</div>';
                        
                        $('#order-details-content').html(html);
                        $('#order-modal').show();
                    }
                }
            });
        },

        /**
         * Update order status
         */
        updateOrderStatus: function(orderId, status) {
            $.ajax({
                url: shoppatonData.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'shoppaton_admin_update_order_status',
                    nonce: shoppatonData.nonce,
                    order_id: orderId,
                    status: status
                },
                success: function(response) {
                    if (response.success) {
                        Shoppaton.toast('Order status updated', 'success');
                        if (typeof loadOrders === 'function') {
                            loadOrders();
                        }
                    } else {
                        Shoppaton.toast(response.data.message || 'Error updating order', 'error');
                    }
                }
            });
        },

        /**
         * Initialize Charts
         */
        initCharts: function() {
            // Charts are initialized inline in the admin dashboard template
            // This method can be extended for additional chart functionality
        },

        /**
         * Initialize Modal Handlers
         */
        initModalHandlers: function() {
            // Close modal on backdrop click
            $(document).on('click', '.shoppaton-modal', function(e) {
                if ($(e.target).hasClass('shoppaton-modal')) {
                    $(this).hide();
                }
            });

            // Close modal on escape key
            $(document).on('keydown', function(e) {
                if (e.key === 'Escape') {
                    $('.shoppaton-modal').hide();
                }
            });

            // Save tracking button
            $(document).on('click', '#save-tracking-btn', function() {
                var orderId = $(this).data('order-id');
                var company = $('#tracking-company').val();
                var number = $('#tracking-number').val();
                
                $.ajax({
                    url: shoppatonData.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'shoppaton_admin_update_tracking',
                        nonce: shoppatonData.nonce,
                        order_id: orderId,
                        delivery_company: company,
                        tracking_number: number
                    },
                    success: function(response) {
                        if (response.success) {
                            Shoppaton.toast('Tracking information saved', 'success');
                        } else {
                            Shoppaton.toast(response.data.message || 'Error saving tracking info', 'error');
                        }
                    }
                });
            });
        },

        /**
         * Initialize Drag and Drop
         */
        initDragAndDrop: function() {
            // Dropzone handling
            $(document).on('dragover', '.shoppaton-dropzone', function(e) {
                e.preventDefault();
                $(this).addClass('dragover');
            });

            $(document).on('dragleave', '.shoppaton-dropzone', function() {
                $(this).removeClass('dragover');
            });

            $(document).on('drop', '.shoppaton-dropzone', function(e) {
                e.preventDefault();
                $(this).removeClass('dragover');
                
                var files = e.originalEvent.dataTransfer.files;
                // Handle file upload
                // This would typically upload to WordPress media library
            });

            // Sortable images (if jQuery UI is available)
            if ($.fn.sortable) {
                $('#product-images-preview').sortable({
                    update: function() {
                        // Update image order
                    }
                });
            }
        },

        /**
         * Export data to CSV
         */
        exportToCSV: function(type) {
            $.ajax({
                url: shoppatonData.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'shoppaton_admin_export_csv',
                    nonce: shoppatonData.nonce,
                    type: type
                },
                success: function(response) {
                    if (response.success) {
                        // Download file
                        var link = document.createElement('a');
                        link.href = response.data.url;
                        link.download = response.data.filename;
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                    } else {
                        Shoppaton.toast('Error exporting data', 'error');
                    }
                }
            });
        },

        /**
         * Format currency
         */
        formatCurrency: function(amount) {
            return '₦' + parseFloat(amount).toLocaleString('en-NG', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 2
            });
        },

        /**
         * Format date
         */
        formatDate: function(dateString) {
            var date = new Date(dateString);
            return date.toLocaleDateString('en-NG', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        ShoppatonAdmin.init();
    });

})(jQuery);
