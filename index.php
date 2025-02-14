<?php require_once "config.php";?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaveEntries - Buy & Sell with Ease</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }

        .hero-section {
            height: 90vh;
            background: url('https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTB8fHNhbGVzfGVufDB8fDB8fHww') no-repeat center center;
            background-size: cover;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 20px;
        }

        .hero-section h1 {
            font-size: 3rem;
            font-weight: bold;
        }

        .hero-section p {
            font-size: 1.3rem;
            margin-bottom: 20px;
        }

        .feature-section {
            padding: 60px 0;
        }

        .feature-box {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        .feature-box:hover {
            transform: translateY(-10px);
        }

        .feature-icon {
            font-size: 2.5rem;
            color: #007bff;
            margin-bottom: 15px;
        }

        .footer {
            background-color: #343a40;
            color: white;
            padding: 20px 0;
            text-align: center;
        }

        .navbar {
            background-color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
    <div class="container">
        <a class="navbar-brand text-primary" href="#">
            <i class="fas fa-store"></i> SaveEntries
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="fas fa-home"></i> Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#features"><i class="fas fa-star"></i> Features</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#products"><i class="fas fa-box-open"></i> Products</a>
                </li>
                <?php if (isset($_SESSION['user_id'])): ?>
                <li class="nav-item">
                    <a class="nav-link btn btn-primary text-white" href="admin/index.php">Go to Dashboard</a>
                </li>
                <!-- <li class="nav-item">
                    <a class="nav-link btn btn-secondary text-white" href="logout.php">Logout</a>
                </li> -->
                <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link btn btn-secondary text-white" href="login.php">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1>Buy & Sell Seamlessly with SaveEntries</h1>
            <p>Find the best deals on new and used products or list your own for sale.</p>
            <a href="#products" class="btn btn-light btn-lg">Browse Listings</a>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="feature-section text-center">
        <div class="container">
            <h2 class="mb-5">Why Choose SaveEntries?</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="feature-box">
                        <i class="feature-icon fas fa-shopping-cart"></i>
                        <h4>Easy Buying & Selling</h4>
                        <p>Post your items and find great deals with a simple interface.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-box">
                        <i class="feature-icon fas fa-shield-alt"></i>
                        <h4>Secure Transactions</h4>
                        <p>We ensure safe payments and verified sellers for your peace of mind.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-box">
                        <i class="feature-icon fas fa-users"></i>
                        <h4>Community Marketplace</h4>
                        <p>Connect with buyers and sellers in your local area or nationwide.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="text-center py-5 bg-light">
        <div class="container">
            <h2>Join SaveEntries Today</h2>
            <p class="lead">Start buying and selling instantly with our trusted marketplace.</p>
            <?php if (!isset($_SESSION['user_id'])): ?>
            <a href="register.php" class="btn btn-success btn-lg">Sign Up Now</a>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 SaveEntries. All Rights Reserved.</p>
            <p><a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a></p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
