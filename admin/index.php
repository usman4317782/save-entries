<?php require_once "pages/header.php"; ?>
<?php require_once "pages/sidenav.php"; ?>
<?php require_once "pages/topnav.php"; ?>

<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Admin Dashboard</h1>
    </div>

    <?php
    $dashboardData = new FetchSalesData();
    $totalSales = $dashboardData->fetchTotalSales();
    $totalPurchases = $dashboardData->fetchTotalPurchases();
    $totalRevenue = $dashboardData->fetchTotalRevenue();
    $totalOrders = $dashboardData->fetchTotalOrders();
    $totalProducts = $dashboardData->fetchTotalProducts();
    $totalCustomers = $dashboardData->fetchTotalCustomers();
    $totalSuppliers = $dashboardData->fetchTotalSuppliers();
    $totalCategories = $dashboardData->fetchTotalCategories();
    $totalStock = $dashboardData->fetchTotalStock();
    ?>

    <!-- Sales & Purchases Overview -->
    <div class="row">
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">Total Sales</div>
                <div class="card-body">
                    <h4 class="card-title">$<?php echo number_format($totalSales, 2); ?></h4>
                    <i class="fas fa-calculator fa-4x"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">Total Purchases</div>
                <div class="card-body">
                    <h4 class="card-title">$<?php echo number_format($totalPurchases, 2); ?></h4>
                    <i class="fas fa-calculator fa-4x"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-header">Total Revenue</div>
                <div class="card-body">
                    <h4 class="card-title">$<?php echo number_format($totalRevenue, 2); ?></h4>
                    <i class="fas fa-calculator fa-4x"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info mb-3">
                <div class="card-header">Total Orders</div>
                <div class="card-body">
                    <h4 class="card-title"><?php echo $totalOrders; ?></h4>
                    <i class="fas fa-shopping-cart fa-4x"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-dark mb-3">
                <div class="card-header">Total Products</div>
                <div class="card-body">
                    <h4 class="card-title"><?php echo $totalProducts; ?></h4>
                    <i class="fas fa-box fa-4x"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-secondary mb-3">
                <div class="card-header">Total Customers</div>
                <div class="card-body">
                    <h4 class="card-title"><?php echo $totalCustomers; ?></h4>
                    <i class="fas fa-users fa-4x"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger mb-3">
                <div class="card-header">Total Suppliers</div>
                <div class="card-body">
                    <h4 class="card-title"><?php echo $totalSuppliers; ?></h4>
                    <i class="fas fa-truck fa-4x"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">Total Categories</div>
                <div class="card-body">
                    <h4 class="card-title"><?php echo $totalCategories; ?></h4>
                    <i class="fas fa-list fa-4x"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">Total Stock Quantity</div>
                <div class="card-body">
                    <h4 class="card-title"><?php echo $totalStock; ?></h4>
                    <i class="fas fa-boxes fa-4x"></i>
                </div>
            </div>
        </div>
    </div>


    <!-- Sales & Purchases Chart -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Sales & Purchase Statistics</h6>
        </div>
        <div class="card-body">
            <canvas id="salesChart"></canvas>
        </div>
    </div>
</div>

<?php require_once "pages/footer.php"; ?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var ctx = document.getElementById("salesChart").getContext("2d");
        var salesChart = new Chart(ctx, {
            type: "bar",
            data: {
                labels: ["Total Sales", "Total Purchases", "Total Revenue", "Total Orders", "Total Products", "Total Customers", "Total Suppliers", "Total Categories", "Total Stock"],
                datasets: [{
                    label: "Amount ($)",
                    backgroundColor: ["#28a745", "#dc3545", "#007bff", "#ffc107", "#6c757d", "#17a2b8", "#28a745", "#007bff", "#6c757d"],
                    borderColor: ["#1e7e34", "#bd2130", "#0056b3", "#d39e00", "#343a40", "#138496", "#1e7e34", "#0056b3", "#343a40"],
                    borderWidth: 1,
                    data: [
                        <?php echo $totalSales; ?>,
                        <?php echo $totalPurchases; ?>,
                        <?php echo $totalRevenue; ?>,
                        <?php echo $totalOrders; ?>,
                        <?php echo $totalProducts; ?>,
                        <?php echo $totalCustomers; ?>,
                        <?php echo $totalSuppliers; ?>,
                        <?php echo $totalCategories; ?>,
                        <?php echo $totalStock; ?>
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>