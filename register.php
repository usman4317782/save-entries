<?php
require_once 'config.php';
require_once 'classes/Register.php';

$register = new Register();
$errors = [];
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Invalid CSRF token!");
    }
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    $result = $register->registerUser($username, $email, $password, $confirm_password, $role);

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

        .success-message,
        .error-message {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="register-container">
        <form class="register-form" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <?php if (!empty($success_message)): ?>
                <div class="alert alert-success success-message"> <?= htmlspecialchars($success_message); ?> </div>
            <?php endif; ?>

            <div class="row">
                <!-- User Name -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="username">User Name</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="User Name" required>
                    </div>
                </div>

                <!-- Email Address -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email Address" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Password -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
                    </div>
                </div>
            </div>

            <!-- Role Dropdown (Full Width) -->
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select class="form-control" id="role" name="role" required>
                            <option value="" disabled selected>Select Role</option>
                            <option value="Manager">Manager</option>
                            <option value="Salesperson">Salesperson</option>
                            <option value="Purchaser">Purchaser</option>
                            <option value="Supplier">Supplier</option>
                            <option value="Customer" selected>Customer</option>
                            <option value="Accountant">Accountant</option>
                            <option value="Warehouse Manager">Warehouse Manager</option>
                            <option value="Support">Support</option>
                            <option value="Viewer">Viewer</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="row">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-success btn-block btn-register">Add</button>
                </div>
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