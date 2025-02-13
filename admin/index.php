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
    $fetchSalesData = new FetchSalesData();
    $total_sales = $fetchSalesData->fetchTotalSales();
    $total_purchases = $fetchSalesData->fetchTotalPurchases();
    $total_revenue = $fetchSalesData->fetchTotalRevenue();
    $total_orders = $fetchSalesData->fetchTotalOrders();
    ?>

    <!-- Sales & Purchases Overview -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2" style="background: linear-gradient(to right, #28a745, #78c2ad);">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-white text-uppercase mb-1">Total Sales</div>
                            <div class="h5 mb-0 font-weight-bold text-white">$<?php echo number_format($total_sales, 2); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2" style="background: linear-gradient(to right, #dc3545, #f09397);">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-white text-uppercase mb-1">Total Purchases</div>
                            <div class="h5 mb-0 font-weight-bold text-white">$<?php echo number_format($total_purchases, 2); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2" style="background: linear-gradient(to right, #007bff, #66b2ff);">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-white text-uppercase mb-1">Total Revenue</div>
                            <div class="h5 mb-0 font-weight-bold text-white">$<?php echo number_format($total_revenue, 2); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2" style="background: linear-gradient(to right, #ffc107, #ffd966);">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-white text-uppercase mb-1">Total Orders</div>
                            <div class="h5 mb-0 font-weight-bold text-white"><?php echo $total_orders; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-box fa-2x text-white"></i>
                        </div>
                    </div>
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
