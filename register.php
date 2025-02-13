<?php
require_once 'config.php';
require_once 'classes/Register.php';

$register = new Register();
$errors = [];
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    $result = $register->registerUser($username, $email, $password, $confirm_password);
    
    if ($result['success'] === true) {
        $success_message = $result['message'];
    } else {
        $errors = $result['errors'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SalesPro - Sign Up & Boost Your Sales</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background: url('https://source.unsplash.com/1600x900/?sales,business') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
        }
        .register-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        .register-form {
            background: rgba(255, 255, 255, 0.95);
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.15);
            max-width: 500px;
            width: 100%;
        }
        .register-form h2 {
            text-align: center;
            font-weight: bold;
            color: #333;
        }
        .btn-register {
            background-color: #28a745;
            border-radius: 25px;
            padding: 12px;
            font-weight: bold;
            transition: 0.3s;
        }
        .btn-register:hover {
            background-color: #218838;
        }
        .success-message, .error-message {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <form class="register-form" action="register.php" method="POST">
            <h2>Join SalesPro & Skyrocket Your Business!</h2>
            <?php if ($success_message): ?>
                <div class="alert alert-success success-message"> <?php echo $success_message; ?> </div>
            <?php endif; ?>
            <div class="form-group">
                <input type="text" class="form-control" name="username" placeholder="Business Name" required>
            </div>
            <div class="form-group">
                <input type="email" class="form-control" name="email" placeholder="Email Address" required>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Password" required>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password" required>
            </div>
            <button type="submit" class="btn btn-success btn-block btn-register">Start Selling Today</button>
            <div class="text-center mt-3">
                Already have an account? <a href="login.php">Log in</a>
            </div>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        document.querySelector('.register-form').addEventListener('submit', function(e) {
            document.querySelector('.btn-register').innerHTML = 'Processing...';
        });
    </script>
</body>
</html>