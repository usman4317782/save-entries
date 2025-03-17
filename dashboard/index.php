<?php include "pages/header.php"; ?>
<?php include "pages/spinner.php"; ?>
<?php include "pages/sidenav.php"; ?>

<!-- Content Start -->
<div class="content">
    <?php include "pages/topnav.php"; ?>

    <!-- Statistics Cards Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4">
            <!-- Total Brands Card -->
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 clickable-card" data-action="brands">
                    <i class="fa fa-tags fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total Brands</p>
                        <h6 class="mb-0" id="totalBrands">Loading...</h6>
                    </div>
                </div>
            </div>

            <!-- Total Categories Card -->
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 clickable-card" data-action="categories">
                    <i class="fa fa-folder fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total Categories</p>
                        <h6 class="mb-0" id="totalCategories">Loading...</h6>
                    </div>
                </div>
            </div>

            <!-- Total Products Card -->
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 clickable-card" data-action="products">
                    <i class="fa fa-box fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total Products</p>
                        <h6 class="mb-0" id="totalProducts">Loading...</h6>
                    </div>
                </div>
            </div>

            <!-- Total Customers Card -->
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 clickable-card" data-action="customers">
                    <i class="fa fa-users fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total Customers</p>
                        <h6 class="mb-0" id="totalCustomers">Loading...</h6>
                    </div>
                </div>
            </div>

            <!-- Total Vendors Card -->
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 clickable-card" data-action="vendors">
                    <i class="fa fa-truck fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total Vendors</p>
                        <h6 class="mb-0" id="totalVendors">Loading...</h6>
                    </div>
                </div>
            </div>

            <!-- Total Users Card -->
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 clickable-card" data-action="users">
                    <i class="fa fa-user fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total Users</p>
                        <h6 class="mb-0" id="totalUsers">Loading...</h6>
                    </div>
                </div>
            </div>

            <!-- Total Sales Card -->
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 clickable-card" data-action="sales">
                    <i class="fa fa-shopping-cart fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total Sales</p>
                        <h6 class="mb-0" id="totalSales">Loading...</h6>
                    </div>
                </div>
            </div>

            <!-- Total Quotations Card -->
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 clickable-card" data-action="quotations">
                    <i class="fa fa-file-invoice fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total Quotations</p>
                        <h6 class="mb-0" id="totalQuotations">Loading...</h6>
                    </div>
                </div>
            </div>

            <!-- Total Revenue Card -->
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 clickable-card" data-action="revenue">
                    <i class="fa fa-chart-line fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total Revenue</p>
                        <h6 class="mb-0" id="totalRevenue">Loading...</h6>
                    </div>
                </div>
            </div>

            <!-- Total Stock Quantity Card -->
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 clickable-card" data-action="stock">
                    <i class="fa fa-cubes fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total Stock Quantity</p>
                        <h6 class="mb-0" id="totalStockQuantity">Loading...</h6>
                    </div>
                </div>
            </div>

            <!-- Total Product Value Card -->
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 clickable-card" data-action="product_value">
                    <i class="fa fa-dollar-sign fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total Product Value</p>
                        <h6 class="mb-0" id="totalProductValue">Loading...</h6>
                    </div>
                </div>
            </div>

            <!-- Average Product Price Card -->
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 clickable-card" data-action="average_price">
                    <i class="fa fa-money-bill-wave fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Average Product Price</p>
                        <h6 class="mb-0" id="averageProductPrice">Loading...</h6>
                    </div>
                </div>
            </div>

            <!-- Active Users Card -->
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 clickable-card" data-action="active_users">
                    <i class="fa fa-user-check fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Active Users</p>
                        <h6 class="mb-0" id="activeUsers">Loading...</h6>
                    </div>
                </div>
            </div>

            <!-- Verified Users Card -->
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 clickable-card" data-action="verified_users">
                    <i class="fa fa-user-shield fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Verified Users</p>
                        <h6 class="mb-0" id="verifiedUsers">Loading...</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Statistics Cards End -->

    <!-- Charts Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4">
            <!-- Products Per Brand Chart -->
            <div class="col-sm-12 col-xl-6">
                <div class="bg-light text-center rounded p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h6 class="mb-0">Products Per Brand</h6>
                        <a href="">Show All</a>
                    </div>
                    <canvas id="productsPerBrandChart"></canvas>
                </div>
            </div>

            <!-- Products Per Category Chart -->
            <div class="col-sm-12 col-xl-6">
                <div class="bg-light text-center rounded p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h6 class="mb-0">Products Per Category</h6>
                        <a href="">Show All</a>
                    </div>
                    <canvas id="productsPerCategoryChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <!-- Charts End -->

    <!-- Recent Trends Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">Sales Trends</h6>
                <a href="">Show All</a>
            </div>
            <canvas id="salesTrendsChart"></canvas>
        </div>
    </div>
    <!-- Recent Trends End -->

    <?php include "pages/footer.php"; ?>
</div>

<!-- Scripts for Fetching Data and Rendering Charts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        // Fetch Statistics Data
        function fetchStatistics() {
            $.get('statistics_actions.php?action=fetch_statistics', function(response) {
                let stats = response.data;

                // Update Cards
                $('#totalBrands').text(stats.total_brands || 0);
                $('#totalCategories').text(stats.total_categories || 0);
                $('#totalProducts').text(stats.total_products || 0);
                $('#totalCustomers').text(stats.total_customers || 0);
                $('#totalVendors').text(stats.total_vendors || 0);
                $('#totalUsers').text(stats.total_users || 0);
                $('#totalSales').text(stats.total_sales || 0);
                $('#totalQuotations').text(stats.total_quotations || 0);

                // Ensure total_revenue is treated as a number
                $('#totalRevenue').text('$' + (parseFloat(stats.total_revenue) || 0).toFixed(2));

                // Ensure total_stock_quantity is treated as a number
                $('#totalStockQuantity').text(parseInt(stats.total_stock_quantity) || 0);

                // Ensure total_product_value is treated as a number
                $('#totalProductValue').text('$' + (parseFloat(stats.total_product_value) || 0).toFixed(2));

                // Ensure average_product_price is treated as a number
                $('#averageProductPrice').text('$' + (parseFloat(stats.average_product_price) || 0).toFixed(2));

                $('#activeUsers').text(stats.active_users || 0);
                $('#verifiedUsers').text(stats.verified_users || 0);

                // Render Products Per Brand Chart
                let brandLabels = stats.products_per_brand.map(item => item.brand_name);
                let brandData = stats.products_per_brand.map(item => item.total_products);
                renderChart('productsPerBrandChart', 'bar', brandLabels, brandData, 'Products Per Brand');

                // Render Products Per Category Chart
                let categoryLabels = stats.products_per_category.map(item => item.category_name);
                let categoryData = stats.products_per_category.map(item => item.total_products);
                renderChart('productsPerCategoryChart', 'bar', categoryLabels, categoryData, 'Products Per Category');

                // Render Sales Trends Chart
                let trendsLabels = stats.sales_trends.map(item => item.month);
                let trendsData = stats.sales_trends.map(item => item.total_sales);
                renderChart('salesTrendsChart', 'line', trendsLabels, trendsData, 'Sales Trends');
            }, 'json');
        }

        // Render Chart
        function renderChart(canvasId, type, labels, data, label) {
            let ctx = document.getElementById(canvasId).getContext('2d');
            new Chart(ctx, {
                type: type,
                data: {
                    labels: labels,
                    datasets: [{
                        label: label,
                        data: data,
                        backgroundColor: type === 'bar' ? 'rgba(54, 162, 235, 0.2)' : 'rgba(75, 192, 192, 0.2)',
                        borderColor: type === 'bar' ? 'rgba(54, 162, 235, 1)' : 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        // Clickable Cards - Redirect to Specific Pages
        $('.clickable-card').on('click', function() {
            let action = $(this).data('action');
            let pageUrl = '';

            // Map actions to their respective pages
            switch (action) {
                case 'brands':
                    pageUrl = 'brands.php';
                    break;
                case 'categories':
                    pageUrl = 'categories.php';
                    break;
                case 'products':
                    pageUrl = 'products.php';
                    break;
                case 'customers':
                    pageUrl = 'customers.php';
                    break;
                case 'vendors':
                    pageUrl = 'vendors.php';
                    break;
                case 'users':
                    pageUrl = 'users.php';
                    break;
                case 'sales':
                    pageUrl = 'sales.php';
                    break;
                case 'quotations':
                    pageUrl = 'quotations.php';
                    break;
                case 'revenue':
                    pageUrl = 'revenue.php';
                    break;
                case 'stock':
                    pageUrl = 'stock.php';
                    break;
                case 'product_value':
                    pageUrl = '';
                    break;
                case 'average_price':
                    pageUrl = '';
                    break;
                case 'active_users':
                    pageUrl = 'users.php';
                    break;
                case 'verified_users':
                    pageUrl = 'users.php';
                    break;
                default:
                    alert('Invalid action');
                    return;
            }

            // Redirect to the specific page
            window.location.href = pageUrl;
        });

        // Fetch data on page load
        fetchStatistics();
    });
</script>