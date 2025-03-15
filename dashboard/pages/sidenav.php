<!-- Sidebar Start -->
<div class="sidebar pe-4 pb-3">
    <nav class="navbar bg-light navbar-light">
        <!-- Brand Logo and Name -->
        <a href="index.php" class="navbar-brand mx-4 mb-3">
            <h4 class="text-primary"><i class="fa fa-hashtag me-2"></i>SAVE-ENTRIES</h4>
        </a>

        <!-- User Profile Section -->
        <div class="d-flex align-items-center ms-4 mb-4">
            <div class="position-relative">
                <img class="rounded-circle" src="img/user.jpg" alt="" style="width: 40px; height: 40px;">
                <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
            </div>
            <div class="ms-3">
                <!-- <h6 class="mb-0">John Doe</h6> -->
                <span>Admin</span>
            </div>
        </div>

        <!-- Navigation Links -->
        <div class="navbar-nav w-100">
            <!-- Dashboard -->
            <a href="index.php" class="nav-item nav-link"><i class="fa fa-tachometer-alt me-2"></i>Dashboard</a>

           

            <!-- Products Management Dropdown -->
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-box me-2"></i>Products</a>
                <div class="dropdown-menu bg-transparent border-0">
                    <a href="products.php" class="dropdown-item">Products</a>
                    <a href="categories.php" class="dropdown-item">Categories</a>
                    <a href="brands.php" class="dropdown-item">Brands</a>
                </div>
            </div>

            <!-- Business Partners Dropdown -->
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-users me-2"></i>Traders</a>
                <div class="dropdown-menu bg-transparent border-0">
                    <a href="customers.php" class="dropdown-item">Customers</a>
                    <a href="vendors.php" class="dropdown-item">Vendors</a>
                </div>
            </div>

             <!-- Sales Management -->
             <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="fa fa-shopping-cart me-2"></i>Sales
                </a>
                <div class="dropdown-menu bg-transparent border-0">
                    <a href="sales.php" class="dropdown-item">New</a>
                    <a href="quotations.php" class="dropdown-item">Quotations</a>
                    <!-- <a href="sales.php?status=pending" class="dropdown-item">Pending Sales</a>
                    <a href="sales.php?status=paid" class="dropdown-item">Completed Sales</a>
                    <a href="sales.php?status=partially_paid" class="dropdown-item">Partial Payments</a>
                    <div class="dropdown-divider"></div>
                    <a href="sales_report.php" class="dropdown-item">Sales Report</a> -->
                </div>
            </div>
            
            <!-- Users Management -->
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-user me-2"></i>Users</a>
                <div class="dropdown-menu bg-transparent border-0">
                    <a href="users.php" class="dropdown-item">Manage Users</a>
                    <a href="roles.php" class="dropdown-item">Roles</a>
                </div>
            </div>

            <!-- Reports -->
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-chart-bar me-2"></i>Reports</a>
                <div class="dropdown-menu bg-transparent border-0">
                    <a href="sales_report.php" class="dropdown-item">Sales Report</a>
                    <a href="inventory_report.php" class="dropdown-item">Inventory Report</a>
                    <a href="customer_report.php" class="dropdown-item">Customer Report</a>
                </div>
            </div>

            <!-- Settings -->
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-cog me-2"></i>Settings</a>
                <div class="dropdown-menu bg-transparent border-0">
                    <a href="settings.php" class="dropdown-item">General Settings</a>
                    <a href="company_settings.php" class="dropdown-item">Company Settings</a>
                    <a href="profile.php" class="dropdown-item">Profile</a>
                </div>
            </div>
        </div>
    </nav>
</div>
<!-- Sidebar End -->