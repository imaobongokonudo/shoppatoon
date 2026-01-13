<?php
/**
 * Admin Frontend Dashboard Template
 *
 * @package ShoppatonStore
 */

if (!defined('ABSPATH')) {
    exit;
}

// Check if user is admin
if (!current_user_can('manage_options')) {
    wp_redirect(home_url());
    exit;
}

$active_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'dashboard';
$products = Shoppaton_Products::instance();
$orders = Shoppaton_Orders::instance();
$analytics = Shoppaton_Analytics::instance();
$settings = Shoppaton_Settings::instance();

$logo_url = SHOPPATON_ASSETS_URL . 'images/logo.png';
?>

<?php include SHOPPATON_PLUGIN_DIR . 'templates/partials/header.php'; ?>

<style>
/* Admin Dashboard Specific Styles */
.shoppaton-admin-page {
    padding: 80px 15px 40px !important;
}
.shoppaton-admin-page h1, .shoppaton-admin-page h2, .shoppaton-admin-page h3 {
    font-size: 18px !important;
    margin-bottom: 15px;
}
.shoppaton-admin-page h1 {
    font-size: 22px !important;
}
.shoppaton-admin-page p, .shoppaton-admin-page label, .shoppaton-admin-page td, .shoppaton-admin-page th {
    font-size: 12px !important;
}
.shoppaton-admin-page .shoppaton-form-input,
.shoppaton-admin-page .shoppaton-form-select,
.shoppaton-admin-page .shoppaton-form-textarea {
    font-size: 12px !important;
    padding: 10px 12px !important;
    color: #fff !important;
    background: rgba(255,255,255,0.08) !important;
}
.shoppaton-admin-page .shoppaton-form-input::placeholder,
.shoppaton-admin-page .shoppaton-form-textarea::placeholder {
    color: var(--shoppaton-gold) !important;
    opacity: 0.8 !important;
}
.shoppaton-admin-page .shoppaton-form-label {
    font-size: 11px !important;
    margin-bottom: 6px !important;
    color: var(--shoppaton-gold) !important;
}
.shoppaton-admin-page .shoppaton-btn {
    font-size: 11px !important;
    padding: 8px 16px !important;
}
.shoppaton-admin-page .shoppaton-glass-card {
    padding: 20px !important;
}
.shoppaton-admin-page .shoppaton-admin-nav-item {
    font-size: 12px !important;
    padding: 10px 12px !important;
}
.shoppaton-admin-page .shoppaton-container {
    max-width: 1200px;
    padding: 0 15px;
}
.shoppaton-admin-page select option {
    color: #000 !important;
    background: #fff !important;
}
@media (max-width: 768px) {
    .shoppaton-admin-page {
        padding: 70px 10px 30px !important;
    }
    .shoppaton-admin-page h1 {
        font-size: 18px !important;
    }
}
</style>

<div class="shoppaton-admin-page">
    <div class="shoppaton-container">
        <!-- Admin Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px;">
            <div>
                <h1 style="margin-bottom: 5px;">Admin Dashboard</h1>
                <p style="color: var(--shoppaton-text-muted); margin: 0;">Manage your Shoppaton Store</p>
            </div>
            <a href="<?php echo esc_url(admin_url('admin.php?page=shoppaton-settings')); ?>" class="shoppaton-btn shoppaton-btn-secondary">
                WP Admin
            </a>
        </div>

        <!-- Admin Layout -->
        <div class="shoppaton-admin-layout" style="display: grid; grid-template-columns: 180px 1fr; gap: 20px;">
            <!-- Sidebar -->
            <div class="shoppaton-admin-sidebar">
                <div class="shoppaton-glass-card" style="padding: 15px;">
                    <nav class="shoppaton-admin-nav">
                        <a href="?tab=dashboard" class="shoppaton-admin-nav-item <?php echo $active_tab === 'dashboard' ? 'active' : ''; ?>">
                            Dashboard
                        </a>
                        <a href="?tab=products" class="shoppaton-admin-nav-item <?php echo $active_tab === 'products' ? 'active' : ''; ?>">
                            Products
                        </a>
                        <a href="?tab=categories" class="shoppaton-admin-nav-item <?php echo $active_tab === 'categories' ? 'active' : ''; ?>">
                            Categories
                        </a>
                        <a href="?tab=orders" class="shoppaton-admin-nav-item <?php echo $active_tab === 'orders' ? 'active' : ''; ?>">
                            Orders
                        </a>
                        <a href="?tab=customers" class="shoppaton-admin-nav-item <?php echo $active_tab === 'customers' ? 'active' : ''; ?>">
                            Customers
                        </a>
                        <a href="?tab=analytics" class="shoppaton-admin-nav-item <?php echo $active_tab === 'analytics' ? 'active' : ''; ?>">
                            Analytics
                        </a>
                        <a href="?tab=media" class="shoppaton-admin-nav-item <?php echo $active_tab === 'media' ? 'active' : ''; ?>">
                            Media
                        </a>
                        <a href="?tab=settings" class="shoppaton-admin-nav-item <?php echo $active_tab === 'settings' ? 'active' : ''; ?>">
                            Settings
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="shoppaton-admin-content">
                <?php if ($active_tab === 'dashboard') : ?>
                <!-- Dashboard Overview -->
                <div class="shoppaton-admin-dashboard">
                    <!-- Quick Stats -->
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px; margin-bottom: 20px;">
                        <div class="shoppaton-glass-card" style="padding: 15px;">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                <div>
                                    <p style="color: var(--shoppaton-text-muted); margin: 0 0 5px;">Today's Sales</p>
                                    <h3 style="margin: 0; color: var(--shoppaton-gold); font-size: 20px;" id="stat-today-sales">₦0</h3>
                                </div>
                            </div>
                        </div>
                        <div class="shoppaton-glass-card" style="padding: 15px;">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                <div>
                                    <p style="color: var(--shoppaton-text-muted); margin: 0 0 5px;">Pending Orders</p>
                                    <h3 style="margin: 0; font-size: 20px;" id="stat-pending-orders">0</h3>
                                </div>
                            </div>
                        </div>
                        <div class="shoppaton-glass-card" style="padding: 15px;">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                <div>
                                    <p style="color: var(--shoppaton-text-muted); margin: 0 0 5px;">Total Products</p>
                                    <h3 style="margin: 0; font-size: 20px;" id="stat-products">0</h3>
                                </div>
                            </div>
                        </div>
                        <div class="shoppaton-glass-card" style="padding: 15px;">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                <div>
                                    <p style="color: var(--shoppaton-text-muted); margin: 0 0 5px;">Customers</p>
                                    <h3 style="margin: 0; font-size: 20px;" id="stat-customers">0</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts Row -->
                    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 15px; margin-bottom: 20px;">
                        <div class="shoppaton-glass-card" style="padding: 25px;">
                            <h3 style="margin: 0 0 20px;">Sales Overview</h3>
                            <canvas id="sales-chart" height="200"></canvas>
                        </div>
                        <div class="shoppaton-glass-card" style="padding: 25px;">
                            <h3 style="margin: 0 0 20px;">Top Products</h3>
                            <div id="top-products-list"></div>
                        </div>
                    </div>

                    <!-- Recent Orders -->
                    <div class="shoppaton-glass-card" style="padding: 25px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                            <h3 style="margin: 0;">Recent Orders</h3>
                            <a href="?tab=orders" style="color: var(--shoppaton-gold);">View All →</a>
                        </div>
                        <div id="recent-orders-table"></div>
                    </div>
                </div>

                <?php elseif ($active_tab === 'products') : ?>
                <!-- Products Management -->
                <div class="shoppaton-admin-products">
                    <div class="shoppaton-glass-card" style="padding: 25px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
                            <h2 style="margin: 0;">Products</h2>
                            <button type="button" class="shoppaton-btn shoppaton-btn-primary" id="add-new-product">
                                + Add Product
                            </button>
                        </div>

                        <!-- Search & Filters -->
                        <div style="display: flex; gap: 15px; margin-bottom: 25px; flex-wrap: wrap;">
                            <input type="text" id="product-search" class="shoppaton-form-input" placeholder="Search products..." style="flex: 1; min-width: 200px;">
                            <select id="product-category-filter" class="shoppaton-form-select" style="width: auto;">
                                <option value="">All Categories</option>
                            </select>
                            <select id="product-status-filter" class="shoppaton-form-select" style="width: auto;">
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>

                        <!-- Products Table -->
                        <div class="shoppaton-table-responsive">
                            <table class="shoppaton-admin-table">
                                <thead>
                                    <tr>
                                        <th style="width: 60px;">Image</th>
                                        <th>Product Name</th>
                                        <th>Category</th>
                                        <th>Price</th>
                                        <th>Stock</th>
                                        <th>Status</th>
                                        <th style="width: 120px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="products-table-body">
                                    <!-- Products will be loaded here -->
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div id="products-pagination" style="margin-top: 20px; display: flex; justify-content: center; gap: 10px;"></div>
                    </div>
                </div>

                <?php elseif ($active_tab === 'orders') : ?>
                <!-- Orders Management -->
                <div class="shoppaton-admin-orders">
                    <div class="shoppaton-glass-card" style="padding: 25px;">
                        <h2 style="margin: 0 0 25px;">Orders</h2>

                        <!-- Filters -->
                        <div style="display: flex; gap: 15px; margin-bottom: 25px; flex-wrap: wrap;">
                            <input type="text" id="order-search" class="shoppaton-form-input" placeholder="Search by order # or customer..." style="flex: 1; min-width: 200px;">
                            <select id="order-status-filter" class="shoppaton-form-select" style="width: auto;">
                                <option value="">All Status</option>
                                <option value="pending">Pending</option>
                                <option value="processing">Processing</option>
                                <option value="shipped">Shipped</option>
                                <option value="delivered">Delivered</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                            <input type="date" id="order-date-filter" class="shoppaton-form-input" style="width: auto;">
                        </div>

                        <!-- Orders Table -->
                        <div class="shoppaton-table-responsive">
                            <table class="shoppaton-admin-table">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Customer</th>
                                        <th>Date</th>
                                        <th>Items</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th style="width: 120px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="orders-table-body">
                                    <!-- Orders will be loaded here -->
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div id="orders-pagination" style="margin-top: 20px; display: flex; justify-content: center; gap: 10px;"></div>
                    </div>
                </div>

                <?php elseif ($active_tab === 'categories') : ?>
                <!-- Categories Management -->
                <div class="shoppaton-admin-categories">
                    <div class="shoppaton-glass-card" style="padding: 25px; margin-bottom: 20px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
                            <h3 style="margin: 0;">Categories</h3>
                            <button type="button" class="shoppaton-btn shoppaton-btn-primary" onclick="showCategoryModal()">
                                Add Category
                            </button>
                        </div>

                        <!-- Categories Grid -->
                        <div id="categories-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px;">
                            <!-- Categories will be loaded here -->
                        </div>
                    </div>

                    <!-- Category Modal -->
                    <div id="category-modal" class="shoppaton-modal" style="display: none;">
                        <div class="shoppaton-modal-content shoppaton-glass-card" style="padding: 25px; max-width: 500px; margin: 50px auto;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                                <h3 style="margin: 0;" id="category-modal-title">Add Category</h3>
                                <button type="button" onclick="closeCategoryModal()" style="background: none; border: none; color: var(--shoppaton-text-muted); font-size: 24px; cursor: pointer;">&times;</button>
                            </div>
                            <form id="category-form" action="javascript:void(0);" method="post" onsubmit="return false;">
                                <input type="hidden" id="category-id" value="">
                                <div class="shoppaton-form-group" style="margin-bottom: 15px;">
                                    <label class="shoppaton-form-label">Category Name *</label>
                                    <input type="text" id="category-name" class="shoppaton-form-input" required placeholder="e.g. Face Care">
                                </div>
                                <div class="shoppaton-form-group" style="margin-bottom: 15px;">
                                    <label class="shoppaton-form-label">Description</label>
                                    <textarea id="category-description" class="shoppaton-form-textarea" rows="3" placeholder="Category description"></textarea>
                                </div>
                                <div class="shoppaton-form-group" style="margin-bottom: 20px;">
                                    <label class="shoppaton-form-label">Category Image</label>
                                    <div id="category-image-preview" style="width: 100px; height: 100px; border: 2px dashed var(--shoppaton-gold); border-radius: var(--border-radius); display: flex; align-items: center; justify-content: center; margin-bottom: 10px; overflow: hidden;">
                                        <span style="color: var(--shoppaton-text-muted); font-size: 11px; text-align: center;">No Image</span>
                                    </div>
                                    <input type="text" id="category-image-url" class="shoppaton-form-input" placeholder="Image URL or upload below">
                                    <input type="file" id="category-image-upload" accept="image/*" style="margin-top: 10px;">
                                </div>
                                <div style="display: flex; gap: 10px; justify-content: flex-end;">
                                    <button type="button" class="shoppaton-btn shoppaton-btn-secondary" onclick="closeCategoryModal()">Cancel</button>
                                    <button type="submit" class="shoppaton-btn shoppaton-btn-primary" id="save-category-btn">Save Category</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <script>
                // Categories management
                function loadCategories() {
                    fetch('<?php echo admin_url('admin-ajax.php'); ?>?action=shoppaton_get_categories&_wpnonce=<?php echo wp_create_nonce('shoppaton_admin'); ?>')
                        .then(r => r.json())
                        .then(data => {
                            if (data.success) {
                                renderCategories(data.data);
                            }
                        });
                }

                function renderCategories(categories) {
                    const grid = document.getElementById('categories-grid');
                    if (!categories.length) {
                        grid.innerHTML = '<p style="color: var(--shoppaton-text-muted); grid-column: 1/-1; text-align: center;">No categories yet. Add your first category!</p>';
                        return;
                    }
                    grid.innerHTML = categories.map(cat => `
                        <div class="shoppaton-glass-card" style="padding: 20px; text-align: center;">
                            <div style="width: 80px; height: 80px; margin: 0 auto 15px; border-radius: 50%; overflow: hidden; background: var(--shoppaton-black-light);">
                                ${cat.image ? `<img src="${cat.image}" style="width: 100%; height: 100%; object-fit: cover;">` : '<div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: var(--shoppaton-text-muted); font-size: 11px;">No Image</div>'}
                            </div>
                            <h4 style="margin: 0 0 5px; color: var(--shoppaton-white); font-size: 14px;">${cat.name}</h4>
                            <p style="color: var(--shoppaton-text-muted); margin: 0 0 15px; font-size: 11px;">${cat.description || ''}</p>
                            <div style="display: flex; gap: 10px; justify-content: center;">
                                <button onclick="editCategory(${cat.id})" class="shoppaton-btn shoppaton-btn-secondary" style="padding: 6px 12px; font-size: 10px;">Edit</button>
                                <button onclick="deleteCategory(${cat.id})" class="shoppaton-btn" style="padding: 6px 12px; font-size: 10px; background: #ff4444; color: #fff;">Delete</button>
                            </div>
                        </div>
                    `).join('');
                }

                function showCategoryModal() {
                    document.getElementById('category-modal').style.display = 'block';
                    document.getElementById('category-modal-title').textContent = 'Add Category';
                    document.getElementById('category-form').reset();
                    document.getElementById('category-id').value = '';
                    document.getElementById('category-image-preview').innerHTML = '<span style="color: var(--shoppaton-text-muted); font-size: 11px; text-align: center;">No Image</span>';
                }

                function closeCategoryModal() {
                    document.getElementById('category-modal').style.display = 'none';
                }

                function editCategory(id) {
                    fetch('<?php echo admin_url('admin-ajax.php'); ?>?action=shoppaton_get_category&id=' + id + '&_wpnonce=<?php echo wp_create_nonce('shoppaton_admin'); ?>')
                        .then(r => r.json())
                        .then(data => {
                            if (data.success) {
                                const cat = data.data;
                                document.getElementById('category-id').value = cat.id;
                                document.getElementById('category-name').value = cat.name;
                                document.getElementById('category-description').value = cat.description || '';
                                document.getElementById('category-image-url').value = cat.image || '';
                                if (cat.image) {
                                    document.getElementById('category-image-preview').innerHTML = `<img src="${cat.image}" style="width: 100%; height: 100%; object-fit: cover;">`;
                                }
                                document.getElementById('category-modal-title').textContent = 'Edit Category';
                                document.getElementById('category-modal').style.display = 'block';
                            }
                        });
                }

                function deleteCategory(id) {
                    if (!confirm('Are you sure you want to delete this category?')) return;
                    fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                        body: 'action=shoppaton_delete_category&id=' + id + '&_wpnonce=<?php echo wp_create_nonce('shoppaton_admin'); ?>'
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            loadCategories();
                        } else {
                            alert('Error: ' + (data.data || 'Could not delete category'));
                        }
                    });
                }

                document.getElementById('category-form').addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData();
                    formData.append('action', 'shoppaton_save_category');
                    formData.append('_wpnonce', '<?php echo wp_create_nonce('shoppaton_admin'); ?>');
                    formData.append('id', document.getElementById('category-id').value);
                    formData.append('name', document.getElementById('category-name').value);
                    formData.append('description', document.getElementById('category-description').value);
                    formData.append('image', document.getElementById('category-image-url').value);

                    fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                        method: 'POST',
                        body: formData
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            closeCategoryModal();
                            loadCategories();
                        } else {
                            alert('Error: ' + (data.data || 'Could not save category'));
                        }
                    });
                });

                // Image preview
                document.getElementById('category-image-url').addEventListener('input', function() {
                    if (this.value) {
                        document.getElementById('category-image-preview').innerHTML = `<img src="${this.value}" style="width: 100%; height: 100%; object-fit: cover;">`;
                    }
                });

                // Load categories on page load
                if (document.querySelector('.shoppaton-admin-categories')) {
                    loadCategories();
                }
                </script>

                <?php elseif ($active_tab === 'analytics') : ?>
                <!-- Analytics -->
                <div class="shoppaton-admin-analytics">
                    <!-- Date Range Filter -->
                    <div class="shoppaton-glass-card" style="padding: 20px; margin-bottom: 20px;">
                        <div style="display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">
                            <span style="color: var(--shoppaton-text-muted);">Date Range:</span>
                            <input type="date" id="analytics-start-date" class="shoppaton-form-input" style="width: auto;">
                            <span style="color: var(--shoppaton-text-muted);">to</span>
                            <input type="date" id="analytics-end-date" class="shoppaton-form-input" style="width: auto;">
                            <button type="button" class="shoppaton-btn shoppaton-btn-primary" id="apply-date-range">
                                Apply
                            </button>
                        </div>
                    </div>

                    <!-- Key Metrics -->
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 20px; margin-bottom: 30px;">
                        <div class="shoppaton-glass-card" style="padding: 20px; text-align: center;">
                            <p style="color: var(--shoppaton-text-muted); margin: 0 0 5px; font-size: 13px;">Total Revenue</p>
                            <h3 style="margin: 0; color: var(--shoppaton-gold);" id="metric-revenue">₦0</h3>
                        </div>
                        <div class="shoppaton-glass-card" style="padding: 20px; text-align: center;">
                            <p style="color: var(--shoppaton-text-muted); margin: 0 0 5px; font-size: 13px;">Orders</p>
                            <h3 style="margin: 0;" id="metric-orders">0</h3>
                        </div>
                        <div class="shoppaton-glass-card" style="padding: 20px; text-align: center;">
                            <p style="color: var(--shoppaton-text-muted); margin: 0 0 5px; font-size: 13px;">Avg. Order Value</p>
                            <h3 style="margin: 0;" id="metric-aov">₦0</h3>
                        </div>
                        <div class="shoppaton-glass-card" style="padding: 20px; text-align: center;">
                            <p style="color: var(--shoppaton-text-muted); margin: 0 0 5px; font-size: 13px;">Conversion Rate</p>
                            <h3 style="margin: 0;" id="metric-conversion">0%</h3>
                        </div>
                    </div>

                    <!-- Charts -->
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 20px;">
                        <div class="shoppaton-glass-card" style="padding: 25px;">
                            <h3 style="margin: 0 0 20px;">Revenue Over Time</h3>
                            <canvas id="revenue-chart" height="250"></canvas>
                        </div>
                        <div class="shoppaton-glass-card" style="padding: 25px;">
                            <h3 style="margin: 0 0 20px;">Orders by Status</h3>
                            <canvas id="orders-status-chart" height="250"></canvas>
                        </div>
                        <div class="shoppaton-glass-card" style="padding: 25px;">
                            <h3 style="margin: 0 0 20px;">Top Products by Sales</h3>
                            <canvas id="top-products-chart" height="250"></canvas>
                        </div>
                        <div class="shoppaton-glass-card" style="padding: 25px;">
                            <h3 style="margin: 0 0 20px;">Traffic Sources</h3>
                            <canvas id="traffic-sources-chart" height="250"></canvas>
                        </div>
                    </div>

                    <!-- Customer Behavior -->
                    <div class="shoppaton-glass-card" style="padding: 25px; margin-top: 20px;">
                        <h3 style="margin: 0 0 20px;">Customer Behavior</h3>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                            <div style="background: var(--shoppaton-black-light); padding: 20px; border-radius: var(--border-radius);">
                                <p style="color: var(--shoppaton-text-muted); margin: 0 0 5px; font-size: 13px;">Cart Abandonment Rate</p>
                                <h4 style="margin: 0; color: #ff6b6b;" id="behavior-abandonment">0%</h4>
                            </div>
                            <div style="background: var(--shoppaton-black-light); padding: 20px; border-radius: var(--border-radius);">
                                <p style="color: var(--shoppaton-text-muted); margin: 0 0 5px; font-size: 13px;">Returning Customers</p>
                                <h4 style="margin: 0; color: #4caf50;" id="behavior-returning">0%</h4>
                            </div>
                            <div style="background: var(--shoppaton-black-light); padding: 20px; border-radius: var(--border-radius);">
                                <p style="color: var(--shoppaton-text-muted); margin: 0 0 5px; font-size: 13px;">Avg. Session Duration</p>
                                <h4 style="margin: 0;" id="behavior-duration">0m</h4>
                            </div>
                            <div style="background: var(--shoppaton-black-light); padding: 20px; border-radius: var(--border-radius);">
                                <p style="color: var(--shoppaton-text-muted); margin: 0 0 5px; font-size: 13px;">Page Views / Session</p>
                                <h4 style="margin: 0;" id="behavior-pageviews">0</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <?php elseif ($active_tab === 'settings') : ?>
                <!-- Settings -->
                <div class="shoppaton-admin-settings">
                    <form id="admin-settings-form">
                        <!-- Payment Settings -->
                        <div class="shoppaton-glass-card" style="padding: 25px; margin-bottom: 20px;">
                            <h2 style="margin: 0 0 25px;">Payment Settings</h2>
                            
                            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                                <div class="shoppaton-form-group">
                                    <label class="shoppaton-form-label">Paystack Test Public Key</label>
                                    <input type="text" name="paystack_test_public" class="shoppaton-form-input" value="<?php echo esc_attr($settings->get('paystack_test_public')); ?>">
                                </div>
                                <div class="shoppaton-form-group">
                                    <label class="shoppaton-form-label">Paystack Test Secret Key</label>
                                    <input type="password" name="paystack_test_secret" class="shoppaton-form-input" value="<?php echo esc_attr($settings->get('paystack_test_secret')); ?>">
                                </div>
                                <div class="shoppaton-form-group">
                                    <label class="shoppaton-form-label">Paystack Live Public Key</label>
                                    <input type="text" name="paystack_live_public" class="shoppaton-form-input" value="<?php echo esc_attr($settings->get('paystack_live_public')); ?>">
                                </div>
                                <div class="shoppaton-form-group">
                                    <label class="shoppaton-form-label">Paystack Live Secret Key</label>
                                    <input type="password" name="paystack_live_secret" class="shoppaton-form-input" value="<?php echo esc_attr($settings->get('paystack_live_secret')); ?>">
                                </div>
                            </div>
                            
                            <div class="shoppaton-form-group" style="margin-top: 15px;">
                                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                    <input type="checkbox" name="paystack_live_mode" value="1" <?php checked($settings->get('paystack_live_mode'), '1'); ?>>
                                    <span>Enable Live Mode</span>
                                </label>
                            </div>
                        </div>

                        <!-- Delivery Settings -->
                        <div class="shoppaton-glass-card" style="padding: 25px; margin-bottom: 20px;">
                            <h2 style="margin: 0 0 25px;">Delivery Integration</h2>
                            
                            <div class="shoppaton-form-group">
                                <label class="shoppaton-form-label">Delivery Company</label>
                                <select name="delivery_company" class="shoppaton-form-select">
                                    <option value="custom" <?php selected($settings->get('delivery_company'), 'custom'); ?>>Custom / Manual</option>
                                    <option value="gig" <?php selected($settings->get('delivery_company'), 'gig'); ?>>GIG Logistics</option>
                                    <option value="dhl" <?php selected($settings->get('delivery_company'), 'dhl'); ?>>DHL</option>
                                    <option value="kwik" <?php selected($settings->get('delivery_company'), 'kwik'); ?>>Kwik Delivery</option>
                                </select>
                            </div>
                            
                            <div class="shoppaton-form-group">
                                <label class="shoppaton-form-label">Delivery API Key</label>
                                <input type="text" name="delivery_api_key" class="shoppaton-form-input" value="<?php echo esc_attr($settings->get('delivery_api_key')); ?>">
                            </div>
                        </div>

                        <!-- Contact Settings -->
                        <div class="shoppaton-glass-card" style="padding: 25px; margin-bottom: 20px;">
                            <h2 style="margin: 0 0 25px;">Contact Information</h2>
                            
                            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                                <div class="shoppaton-form-group">
                                    <label class="shoppaton-form-label">WhatsApp Number</label>
                                    <input type="text" name="whatsapp_number" class="shoppaton-form-input" value="<?php echo esc_attr($settings->get('whatsapp_number')); ?>">
                                </div>
                                <div class="shoppaton-form-group">
                                    <label class="shoppaton-form-label">Contact Email</label>
                                    <input type="email" name="contact_email" class="shoppaton-form-input" value="<?php echo esc_attr($settings->get('contact_email')); ?>">
                                </div>
                                <div class="shoppaton-form-group">
                                    <label class="shoppaton-form-label">Instagram URL</label>
                                    <input type="url" name="instagram_url" class="shoppaton-form-input" value="<?php echo esc_attr($settings->get('instagram_url')); ?>">
                                </div>
                                <div class="shoppaton-form-group">
                                    <label class="shoppaton-form-label">Facebook URL</label>
                                    <input type="url" name="facebook_url" class="shoppaton-form-input" value="<?php echo esc_attr($settings->get('facebook_url')); ?>">
                                </div>
                                <div class="shoppaton-form-group">
                                    <label class="shoppaton-form-label">TikTok URL</label>
                                    <input type="url" name="tiktok_url" class="shoppaton-form-input" value="<?php echo esc_attr($settings->get('tiktok_url')); ?>">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="shoppaton-btn shoppaton-btn-primary">
                            Save Settings
                        </button>
                    </form>
                </div>

                <?php elseif ($active_tab === 'media') : ?>
                <!-- Media Management -->
                <div class="shoppaton-admin-media">
                    <div class="shoppaton-glass-card" style="padding: 25px;">
                        <h2 style="margin: 0 0 25px;">Site Images</h2>
                        <p style="color: var(--shoppaton-text-muted); margin-bottom: 30px;">
                            Upload and manage images for different sections of your website.
                        </p>

                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 25px;">
                            <!-- Hero Slider Images -->
                            <div style="background: var(--shoppaton-black-light); padding: 20px; border-radius: var(--border-radius);">
                                <h4 style="color: var(--shoppaton-white); -webkit-text-fill-color: var(--shoppaton-white); margin-bottom: 15px;">Hero Slider</h4>
                                <div class="shoppaton-media-uploader" data-field="hero_slides">
                                    <div class="shoppaton-media-preview" id="hero-slides-preview"></div>
                                    <button type="button" class="shoppaton-btn shoppaton-btn-secondary shoppaton-upload-btn" data-field="hero_slides" style="width: 100%; margin-top: 10px;">
                                        + Add Images
                                    </button>
                                </div>
                            </div>

                            <!-- About Section Image -->
                            <div style="background: var(--shoppaton-black-light); padding: 20px; border-radius: var(--border-radius);">
                                <h4 style="color: var(--shoppaton-white); -webkit-text-fill-color: var(--shoppaton-white); margin-bottom: 15px;">About Section</h4>
                                <div class="shoppaton-media-uploader" data-field="about_image">
                                    <div class="shoppaton-media-preview" id="about-image-preview"></div>
                                    <button type="button" class="shoppaton-btn shoppaton-btn-secondary shoppaton-upload-btn" data-field="about_image" style="width: 100%; margin-top: 10px;">
                                        + Upload Image
                                    </button>
                                </div>
                            </div>

                            <!-- Logo -->
                            <div style="background: var(--shoppaton-black-light); padding: 20px; border-radius: var(--border-radius);">
                                <h4 style="color: var(--shoppaton-white); -webkit-text-fill-color: var(--shoppaton-white); margin-bottom: 15px;">Logo</h4>
                                <div class="shoppaton-media-uploader" data-field="logo">
                                    <div class="shoppaton-media-preview" id="logo-preview"></div>
                                    <button type="button" class="shoppaton-btn shoppaton-btn-secondary shoppaton-upload-btn" data-field="logo" style="width: 100%; margin-top: 10px;">
                                        + Upload Logo
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php elseif ($active_tab === 'customers') : ?>
                <!-- Customers Management -->
                <div class="shoppaton-admin-customers">
                    <div class="shoppaton-glass-card" style="padding: 25px;">
                        <h2 style="margin: 0 0 25px;">Customers</h2>

                        <!-- Search -->
                        <div style="margin-bottom: 25px;">
                            <input type="text" id="customer-search" class="shoppaton-form-input" placeholder="Search by name, email, or phone..." style="max-width: 400px;">
                        </div>

                        <!-- Customers Table -->
                        <div class="shoppaton-table-responsive">
                            <table class="shoppaton-admin-table">
                                <thead>
                                    <tr>
                                        <th>Customer</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Orders</th>
                                        <th>Total Spent</th>
                                        <th>Joined</th>
                                    </tr>
                                </thead>
                                <tbody id="customers-table-body">
                                    <!-- Customers will be loaded here -->
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div id="customers-pagination" style="margin-top: 20px; display: flex; justify-content: center; gap: 10px;"></div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Product Modal -->
<div id="product-modal" class="shoppaton-modal" style="display: none;">
    <div class="shoppaton-modal-content" style="max-width: 800px;">
        <div class="shoppaton-modal-header">
            <h2 id="product-modal-title">Add New Product</h2>
            <button type="button" class="shoppaton-modal-close">&times;</button>
        </div>
        <form id="product-form" action="javascript:void(0);" method="post" onsubmit="return false;">
            <div class="shoppaton-modal-body">
                <input type="hidden" name="product_id" id="product-id">
                
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                    <div class="shoppaton-form-group" style="grid-column: 1 / -1;">
                        <label class="shoppaton-form-label">Product Name *</label>
                        <input type="text" name="name" id="product-name" class="shoppaton-form-input" required>
                    </div>
                    
                    <div class="shoppaton-form-group">
                        <label class="shoppaton-form-label">Price (₦) *</label>
                        <input type="number" name="price" id="product-price" class="shoppaton-form-input" step="0.01" required>
                    </div>
                    
                    <div class="shoppaton-form-group">
                        <label class="shoppaton-form-label">Compare Price (₦)</label>
                        <input type="number" name="compare_price" id="product-compare-price" class="shoppaton-form-input" step="0.01">
                    </div>
                    
                    <div class="shoppaton-form-group">
                        <label class="shoppaton-form-label">Category</label>
                        <select name="category" id="product-category" class="shoppaton-form-select">
                            <option value="">Select Category</option>
                        </select>
                    </div>
                    
                    <div class="shoppaton-form-group">
                        <label class="shoppaton-form-label">Stock Quantity</label>
                        <input type="number" name="stock" id="product-stock" class="shoppaton-form-input" value="0">
                    </div>
                    
                    <div class="shoppaton-form-group" style="grid-column: 1 / -1;">
                        <label class="shoppaton-form-label">Description</label>
                        <textarea name="description" id="product-description" class="shoppaton-form-textarea" rows="4"></textarea>
                    </div>
                    
                    <div class="shoppaton-form-group">
                        <label class="shoppaton-form-label">Skin Type</label>
                        <select name="skin_type" id="product-skin-type" class="shoppaton-form-select">
                            <option value="">All Skin Types</option>
                            <option value="oily">Oily Skin</option>
                            <option value="dry">Dry Skin</option>
                            <option value="combination">Combination Skin</option>
                            <option value="sensitive">Sensitive Skin</option>
                            <option value="normal">Normal Skin</option>
                        </select>
                    </div>
                    
                    <div class="shoppaton-form-group">
                        <label class="shoppaton-form-label">Target User</label>
                        <select name="target_user" id="product-target-user" class="shoppaton-form-select">
                            <option value="">Everyone</option>
                            <option value="women">Women</option>
                            <option value="men">Men</option>
                            <option value="unisex">Unisex</option>
                        </select>
                    </div>
                    
                    <div class="shoppaton-form-group" style="grid-column: 1 / -1;">
                        <label class="shoppaton-form-label">Product Images</label>
                        <div class="shoppaton-product-images-uploader">
                            <div id="product-images-preview" style="display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 10px;"></div>
                            <button type="button" class="shoppaton-btn shoppaton-btn-secondary shoppaton-upload-product-images">
                                + Add Images
                            </button>
                        </div>
                    </div>
                    
                    <div class="shoppaton-form-group">
                        <label class="shoppaton-form-label">Usage Guide Image</label>
                        <div id="usage-guide-image-preview" style="width: 100%; max-width: 200px; height: 100px; border: 2px dashed var(--shoppaton-glass-border); border-radius: var(--border-radius); display: flex; align-items: center; justify-content: center; margin-bottom: 10px; overflow: hidden;">
                            <span style="color: var(--shoppaton-text-muted); font-size: 11px; text-align: center;">No Guide Image</span>
                        </div>
                        <input type="hidden" name="usage_guide_image" id="product-usage-guide-image">
                        <button type="button" class="shoppaton-btn shoppaton-btn-secondary shoppaton-upload-guide-image" style="width: 100%;">
                            Upload Guide Image
                        </button>
                    </div>
                    
                    <div class="shoppaton-form-group">
                        <label class="shoppaton-form-label">Status</label>
                        <select name="status" id="product-status" class="shoppaton-form-select">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="shoppaton-modal-footer">
                <button type="button" class="shoppaton-btn shoppaton-btn-ghost shoppaton-modal-close">Cancel</button>
                <button type="submit" class="shoppaton-btn shoppaton-btn-primary">Save Product</button>
            </div>
        </form>
    </div>
</div>

<!-- Order Details Modal -->
<div id="order-modal" class="shoppaton-modal" style="display: none;">
    <div class="shoppaton-modal-content" style="max-width: 700px;">
        <div class="shoppaton-modal-header">
            <h2>Order Details</h2>
            <button type="button" class="shoppaton-modal-close">&times;</button>
        </div>
        <div class="shoppaton-modal-body" id="order-details-content">
            <!-- Order details will be loaded here -->
        </div>
        <div class="shoppaton-modal-footer">
            <button type="button" class="shoppaton-btn shoppaton-btn-ghost shoppaton-modal-close">Close</button>
            <button type="button" class="shoppaton-btn shoppaton-btn-primary" id="update-order-btn">Update Order</button>
        </div>
    </div>
</div>

<style>
.shoppaton-admin-nav-item {
    display: block;
    padding: 12px 15px;
    color: var(--shoppaton-text-muted);
    text-decoration: none;
    border-radius: var(--border-radius-sm);
    transition: all 0.3s ease;
    margin-bottom: 5px;
}

.shoppaton-admin-nav-item:hover {
    background: var(--shoppaton-glass);
    color: var(--shoppaton-white);
}

.shoppaton-admin-nav-item.active {
    background: var(--shoppaton-gold-gradient);
    color: var(--shoppaton-black);
}

.shoppaton-admin-table {
    width: 100%;
    border-collapse: collapse;
}

.shoppaton-admin-table th,
.shoppaton-admin-table td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid var(--shoppaton-glass-border);
}

.shoppaton-admin-table th {
    color: var(--shoppaton-text-muted);
    font-weight: 500;
    font-size: 13px;
    text-transform: uppercase;
}

.shoppaton-admin-table tbody tr:hover {
    background: var(--shoppaton-glass);
}

.shoppaton-table-responsive {
    overflow-x: auto;
}

.shoppaton-modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.8);
    z-index: 10000;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.shoppaton-modal-content {
    background: var(--shoppaton-black);
    border-radius: var(--border-radius-xl);
    border: 1px solid var(--shoppaton-glass-border);
    width: 100%;
    max-height: 90vh;
    overflow-y: auto;
}

.shoppaton-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 25px;
    border-bottom: 1px solid var(--shoppaton-glass-border);
}

.shoppaton-modal-header h2 {
    margin: 0;
    font-size: 20px;
}

.shoppaton-modal-close {
    background: none;
    border: none;
    color: var(--shoppaton-text-muted);
    font-size: 28px;
    cursor: pointer;
    line-height: 1;
}

.shoppaton-modal-close:hover {
    color: var(--shoppaton-white);
}

.shoppaton-modal-body {
    padding: 25px;
}

.shoppaton-modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 15px;
    padding: 20px 25px;
    border-top: 1px solid var(--shoppaton-glass-border);
}

@media (max-width: 768px) {
    .shoppaton-admin-layout {
        grid-template-columns: 1fr !important;
    }
    
    .shoppaton-admin-sidebar {
        order: 2;
    }
    
    .shoppaton-admin-nav {
        display: flex;
        overflow-x: auto;
        gap: 5px;
    }
    
    .shoppaton-admin-nav-item {
        white-space: nowrap;
        margin-bottom: 0;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Ensure shoppatonData is available for the admin dashboard
if (typeof shoppatonData === 'undefined') {
    var shoppatonData = {
        ajaxUrl: '<?php echo admin_url('admin-ajax.php'); ?>',
        nonce: '<?php echo wp_create_nonce('shoppaton_nonce'); ?>',
        homeUrl: '<?php echo home_url(); ?>',
        assetsUrl: '<?php echo SHOPPATON_ASSETS_URL; ?>',
        currency: '₦'
    };
}
console.log('Admin Dashboard: shoppatonData available', shoppatonData);

jQuery(document).ready(function($) {
    // Load dashboard stats
    if ($('#stat-today-sales').length) {
        loadDashboardStats();
    }
    
    // Load products table
    if ($('#products-table-body').length) {
        loadProducts();
    }
    
    // Load orders table
    if ($('#orders-table-body').length) {
        loadOrders();
    }
    
    // Load analytics
    if ($('#revenue-chart').length) {
        loadAnalytics();
    }
    
    // Load customers
    if ($('#customers-table-body').length) {
        loadCustomers();
    }
    
    // Add product button
    $('#add-new-product').on('click', function() {
        $('#product-modal-title').text('Add New Product');
        $('#product-form')[0].reset();
        $('#product-id').val('');
        $('#product-modal').show();
    });
    
    // Close modal
    $('.shoppaton-modal-close').on('click', function() {
        $(this).closest('.shoppaton-modal').hide();
    });
    
    // Product form submission - using event delegation and multiple prevention methods
    $(document).on('submit', '#product-form', function(e) {
        e.preventDefault();
        e.stopPropagation();
        console.log('Product form submitted via AJAX');
        saveProduct($(this));
        return false;
    });
    
    // Also prevent the form's default submit action via button click
    $(document).on('click', '#product-form button[type="submit"]', function(e) {
        e.preventDefault();
        e.stopPropagation();
        console.log('Product save button clicked');
        saveProduct($('#product-form'));
        return false;
    });
    
    // Settings form submission
    $(document).on('submit', '#admin-settings-form', function(e) {
        e.preventDefault();
        saveSettings($(this));
        return false;
    });
    
    function loadDashboardStats() {
        $.ajax({
            url: shoppatonData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'shoppaton_admin_get_stats',
                nonce: shoppatonData.nonce
            },
            success: function(response) {
                if (response.success) {
                    var stats = response.data;
                    $('#stat-today-sales').text('₦' + formatNumber(stats.today_sales));
                    $('#stat-pending-orders').text(stats.pending_orders);
                    $('#stat-products').text(stats.total_products);
                    $('#stat-customers').text(stats.total_customers);
                    
                    // Load sales chart
                    if (stats.sales_data) {
                        initSalesChart(stats.sales_data);
                    }
                    
                    // Load top products
                    if (stats.top_products) {
                        renderTopProducts(stats.top_products);
                    }
                    
                    // Load recent orders
                    if (stats.recent_orders) {
                        renderRecentOrders(stats.recent_orders);
                    }
                }
            }
        });
    }
    
    function loadProducts(page = 1) {
        $.ajax({
            url: shoppatonData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'shoppaton_admin_get_products',
                nonce: shoppatonData.nonce,
                page: page,
                search: $('#product-search').val(),
                category: $('#product-category-filter').val(),
                status: $('#product-status-filter').val()
            },
            success: function(response) {
                if (response.success) {
                    renderProductsTable(response.data.products);
                    renderPagination('#products-pagination', response.data.pages, page, loadProducts);
                }
            }
        });
    }
    
    function loadOrders(page = 1) {
        $.ajax({
            url: shoppatonData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'shoppaton_admin_get_orders',
                nonce: shoppatonData.nonce,
                page: page,
                search: $('#order-search').val(),
                status: $('#order-status-filter').val(),
                date: $('#order-date-filter').val()
            },
            success: function(response) {
                if (response.success) {
                    renderOrdersTable(response.data.orders);
                    renderPagination('#orders-pagination', response.data.pages, page, loadOrders);
                }
            }
        });
    }
    
    function loadCustomers(page = 1) {
        $.ajax({
            url: shoppatonData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'shoppaton_admin_get_customers',
                nonce: shoppatonData.nonce,
                page: page,
                search: $('#customer-search').val()
            },
            success: function(response) {
                if (response.success) {
                    renderCustomersTable(response.data.customers);
                    renderPagination('#customers-pagination', response.data.pages, page, loadCustomers);
                }
            }
        });
    }
    
    function loadAnalytics() {
        var startDate = $('#analytics-start-date').val() || '';
        var endDate = $('#analytics-end-date').val() || '';
        
        $.ajax({
            url: shoppatonData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'shoppaton_admin_get_analytics',
                nonce: shoppatonData.nonce,
                start_date: startDate,
                end_date: endDate
            },
            success: function(response) {
                if (response.success) {
                    var data = response.data;
                    
                    // Update metrics
                    $('#metric-revenue').text('₦' + formatNumber(data.revenue));
                    $('#metric-orders').text(data.orders);
                    $('#metric-aov').text('₦' + formatNumber(data.aov));
                    $('#metric-conversion').text(data.conversion + '%');
                    
                    // Update behavior metrics
                    $('#behavior-abandonment').text(data.abandonment_rate + '%');
                    $('#behavior-returning').text(data.returning_customers + '%');
                    $('#behavior-duration').text(data.avg_duration);
                    $('#behavior-pageviews').text(data.avg_pageviews);
                    
                    // Initialize charts
                    initAnalyticsCharts(data);
                }
            }
        });
    }
    
    function saveProduct($form) {
        console.log('saveProduct called');
        console.log('AJAX URL:', shoppatonData.ajaxUrl);
        console.log('Nonce:', shoppatonData.nonce);
        
        var btn = $form.find('button[type="submit"]');
        btn.prop('disabled', true).text('Saving...');
        
        var formData = $form.serialize();
        console.log('Form data:', formData);
        
        $.ajax({
            url: shoppatonData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'shoppaton_admin_save_product',
                nonce: shoppatonData.nonce,
                product: formData
            },
            success: function(response) {
                console.log('AJAX response:', response);
                if (response.success) {
                    if (typeof Shoppaton !== 'undefined' && Shoppaton.toast) {
                        Shoppaton.toast('Product saved successfully!', 'success');
                    } else {
                        alert('Product saved successfully!');
                    }
                    $('#product-modal').hide();
                    loadProducts();
                } else {
                    var msg = response.data && response.data.message ? response.data.message : 'Error saving product';
                    if (typeof Shoppaton !== 'undefined' && Shoppaton.toast) {
                        Shoppaton.toast(msg, 'error');
                    } else {
                        alert(msg);
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', status, error);
                console.error('Response:', xhr.responseText);
                if (typeof Shoppaton !== 'undefined' && Shoppaton.toast) {
                    Shoppaton.toast('Error saving product. Please try again.', 'error');
                } else {
                    alert('Error saving product. Please try again.');
                }
            },
            complete: function() {
                btn.prop('disabled', false).text('Save Product');
            }
        });
    }
    
    function saveSettings($form) {
        var btn = $form.find('button[type="submit"]');
        btn.prop('disabled', true).text('Saving...');
        
        $.ajax({
            url: shoppatonData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'shoppaton_admin_save_settings',
                nonce: shoppatonData.nonce,
                settings: $form.serialize()
            },
            success: function(response) {
                if (response.success) {
                    Shoppaton.toast('Settings saved successfully!', 'success');
                } else {
                    Shoppaton.toast(response.data.message || 'Error saving settings', 'error');
                }
            },
            error: function() {
                Shoppaton.toast('Error saving settings. Please try again.', 'error');
            },
            complete: function() {
                btn.prop('disabled', false).text('Save Settings');
            }
        });
    }
    
    function renderProductsTable(products) {
        var html = '';
        products.forEach(function(product) {
            html += '<tr>' +
                '<td><img src="' + product.image + '" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;"></td>' +
                '<td>' + product.name + '</td>' +
                '<td>' + (product.category || '-') + '</td>' +
                '<td>₦' + formatNumber(product.price) + '</td>' +
                '<td>' + product.stock + '</td>' +
                '<td><span class="shoppaton-status shoppaton-status-' + product.status + '">' + product.status + '</span></td>' +
                '<td>' +
                    '<button class="shoppaton-btn shoppaton-btn-ghost edit-product" data-id="' + product.id + '" style="padding: 5px 10px; font-size: 12px;">Edit</button> ' +
                    '<button class="shoppaton-btn shoppaton-btn-ghost delete-product" data-id="' + product.id + '" style="padding: 5px 10px; font-size: 12px; color: #ff6b6b;">Delete</button>' +
                '</td>' +
            '</tr>';
        });
        $('#products-table-body').html(html || '<tr><td colspan="7" style="text-align: center; padding: 40px;">No products found</td></tr>');
    }
    
    function renderOrdersTable(orders) {
        var html = '';
        orders.forEach(function(order) {
            html += '<tr>' +
                '<td style="color: var(--shoppaton-gold);">#' + order.order_number + '</td>' +
                '<td>' + order.customer_name + '</td>' +
                '<td>' + order.date + '</td>' +
                '<td>' + order.items_count + ' item(s)</td>' +
                '<td>₦' + formatNumber(order.total) + '</td>' +
                '<td><span class="shoppaton-status shoppaton-status-' + order.status + '">' + order.status + '</span></td>' +
                '<td>' +
                    '<button class="shoppaton-btn shoppaton-btn-ghost view-order" data-id="' + order.id + '" style="padding: 5px 10px; font-size: 12px;">View</button>' +
                '</td>' +
            '</tr>';
        });
        $('#orders-table-body').html(html || '<tr><td colspan="7" style="text-align: center; padding: 40px;">No orders found</td></tr>');
    }
    
    function renderCustomersTable(customers) {
        var html = '';
        customers.forEach(function(customer) {
            html += '<tr>' +
                '<td>' + customer.name + '</td>' +
                '<td>' + customer.email + '</td>' +
                '<td>' + (customer.phone || '-') + '</td>' +
                '<td>' + customer.orders_count + '</td>' +
                '<td>₦' + formatNumber(customer.total_spent) + '</td>' +
                '<td>' + customer.joined + '</td>' +
            '</tr>';
        });
        $('#customers-table-body').html(html || '<tr><td colspan="6" style="text-align: center; padding: 40px;">No customers found</td></tr>');
    }
    
    function renderTopProducts(products) {
        var html = '';
        products.forEach(function(product, index) {
            html += '<div style="display: flex; align-items: center; gap: 15px; padding: 10px 0; border-bottom: 1px solid var(--shoppaton-glass-border);">' +
                '<span style="color: var(--shoppaton-gold); font-weight: bold;">' + (index + 1) + '</span>' +
                '<img src="' + product.image + '" style="width: 40px; height: 40px; object-fit: cover; border-radius: 8px;">' +
                '<div style="flex: 1;">' +
                    '<p style="margin: 0; font-size: 14px;">' + product.name + '</p>' +
                    '<p style="margin: 0; color: var(--shoppaton-text-muted); font-size: 12px;">' + product.sales + ' sold</p>' +
                '</div>' +
            '</div>';
        });
        $('#top-products-list').html(html || '<p style="color: var(--shoppaton-text-muted);">No data available</p>');
    }
    
    function renderRecentOrders(orders) {
        var html = '<table class="shoppaton-admin-table"><thead><tr><th>Order</th><th>Customer</th><th>Total</th><th>Status</th></tr></thead><tbody>';
        orders.forEach(function(order) {
            html += '<tr>' +
                '<td style="color: var(--shoppaton-gold);">#' + order.order_number + '</td>' +
                '<td>' + order.customer_name + '</td>' +
                '<td>₦' + formatNumber(order.total) + '</td>' +
                '<td><span class="shoppaton-status shoppaton-status-' + order.status + '">' + order.status + '</span></td>' +
            '</tr>';
        });
        html += '</tbody></table>';
        $('#recent-orders-table').html(html);
    }
    
    function renderPagination(selector, totalPages, currentPage, loadFunction) {
        if (totalPages <= 1) {
            $(selector).html('');
            return;
        }
        
        var html = '';
        for (var i = 1; i <= totalPages; i++) {
            html += '<button class="shoppaton-btn ' + (i === currentPage ? 'shoppaton-btn-primary' : 'shoppaton-btn-ghost') + ' pagination-btn" data-page="' + i + '" style="padding: 8px 15px;">' + i + '</button>';
        }
        $(selector).html(html);
        
        $(selector).find('.pagination-btn').on('click', function() {
            loadFunction(parseInt($(this).data('page')));
        });
    }
    
    function initSalesChart(data) {
        var ctx = document.getElementById('sales-chart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Sales',
                    data: data.values,
                    borderColor: '#d4af37',
                    backgroundColor: 'rgba(212, 175, 55, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(255, 255, 255, 0.1)' },
                        ticks: { color: 'rgba(255, 255, 255, 0.6)' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: 'rgba(255, 255, 255, 0.6)' }
                    }
                }
            }
        });
    }
    
    function initAnalyticsCharts(data) {
        // Revenue chart
        if (data.revenue_data) {
            var revenueCtx = document.getElementById('revenue-chart').getContext('2d');
            new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: data.revenue_data.labels,
                    datasets: [{
                        label: 'Revenue',
                        data: data.revenue_data.values,
                        borderColor: '#d4af37',
                        backgroundColor: 'rgba(212, 175, 55, 0.1)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { grid: { color: 'rgba(255, 255, 255, 0.1)' }, ticks: { color: 'rgba(255, 255, 255, 0.6)' } },
                        x: { grid: { display: false }, ticks: { color: 'rgba(255, 255, 255, 0.6)' } }
                    }
                }
            });
        }
        
        // Orders status chart
        if (data.orders_by_status) {
            var statusCtx = document.getElementById('orders-status-chart').getContext('2d');
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: data.orders_by_status.labels,
                    datasets: [{
                        data: data.orders_by_status.values,
                        backgroundColor: ['#ffc107', '#2196f3', '#9c27b0', '#4caf50', '#f44336']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { position: 'right', labels: { color: 'rgba(255, 255, 255, 0.8)' } } }
                }
            });
        }
    }
    
    function formatNumber(num) {
        return parseFloat(num).toLocaleString('en-NG', { minimumFractionDigits: 0, maximumFractionDigits: 2 });
    }
    
    // Search debounce
    var searchTimeout;
    $('#product-search, #order-search, #customer-search').on('input', function() {
        clearTimeout(searchTimeout);
        var $this = $(this);
        searchTimeout = setTimeout(function() {
            if ($this.attr('id') === 'product-search') loadProducts();
            else if ($this.attr('id') === 'order-search') loadOrders();
            else if ($this.attr('id') === 'customer-search') loadCustomers();
        }, 500);
    });
    
    // Filter changes
    $('#product-category-filter, #product-status-filter').on('change', function() {
        loadProducts();
    });
    
    $('#order-status-filter, #order-date-filter').on('change', function() {
        loadOrders();
    });
    
    $('#apply-date-range').on('click', function() {
        loadAnalytics();
    });
    
    // WordPress Media Uploader for Product Images
    var productImages = [];
    
    $('.shoppaton-upload-product-images').on('click', function(e) {
        e.preventDefault();
        
        // If the media frame already exists, reopen it.
        if (typeof wp !== 'undefined' && wp.media) {
            var mediaUploader = wp.media({
                title: 'Select Product Images',
                button: {
                    text: 'Add Images'
                },
                multiple: true
            });
            
            mediaUploader.on('select', function() {
                var attachments = mediaUploader.state().get('selection').toJSON();
                attachments.forEach(function(attachment) {
                    productImages.push(attachment.url);
                    $('#product-images-preview').append(
                        '<div class="product-image-item" style="position: relative; display: inline-block;">' +
                        '<img src="' + attachment.url + '" style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;">' +
                        '<input type="hidden" name="images[]" value="' + attachment.url + '">' +
                        '<button type="button" class="remove-product-image" style="position: absolute; top: -5px; right: -5px; background: #ff4444; color: #fff; border: none; border-radius: 50%; width: 20px; height: 20px; cursor: pointer;">&times;</button>' +
                        '</div>'
                    );
                });
            });
            
            mediaUploader.open();
        } else {
            // Fallback: prompt for image URL
            var imageUrl = prompt('Enter image URL:');
            if (imageUrl) {
                productImages.push(imageUrl);
                $('#product-images-preview').append(
                    '<div class="product-image-item" style="position: relative; display: inline-block;">' +
                    '<img src="' + imageUrl + '" style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;">' +
                    '<input type="hidden" name="images[]" value="' + imageUrl + '">' +
                    '<button type="button" class="remove-product-image" style="position: absolute; top: -5px; right: -5px; background: #ff4444; color: #fff; border: none; border-radius: 50%; width: 20px; height: 20px; cursor: pointer;">&times;</button>' +
                    '</div>'
                );
            }
        }
    });
    
    // Usage guide image upload
    $('.shoppaton-upload-guide-image').on('click', function(e) {
        e.preventDefault();
        
        if (typeof wp !== 'undefined' && wp.media) {
            var mediaUploader = wp.media({
                title: 'Select Usage Guide Image',
                button: {
                    text: 'Use This Image'
                },
                multiple: false
            });
            
            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                $('#product-usage-guide-image').val(attachment.url);
                $('#usage-guide-image-preview').html(
                    '<img src="' + attachment.url + '" style="width: 100%; height: 100%; object-fit: cover;">'
                );
            });
            
            mediaUploader.open();
        } else {
            // Fallback: prompt for image URL
            var imageUrl = prompt('Enter usage guide image URL:');
            if (imageUrl) {
                $('#product-usage-guide-image').val(imageUrl);
                $('#usage-guide-image-preview').html(
                    '<img src="' + imageUrl + '" style="width: 100%; height: 100%; object-fit: cover;">'
                );
            }
        }
    });
    
    // Remove product image
    $(document).on('click', '.remove-product-image', function() {
        $(this).closest('.product-image-item').remove();
    });
    
    // Clear images when modal opens for new product
    $('#add-new-product').on('click', function() {
        productImages = [];
        $('#product-images-preview').empty();
        $('#usage-guide-image-preview').html('<span style="color: var(--shoppaton-text-muted); font-size: 11px; text-align: center;">No Guide Image</span>');
        $('#product-usage-guide-image').val('');
    });
});
</script>

<?php 
// Enqueue WordPress media uploader
wp_enqueue_media();
?>

<?php include SHOPPATON_PLUGIN_DIR . 'templates/partials/footer.php'; ?>
