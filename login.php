<?php

require_once "config.php";
session_start();
if (isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = 'admin/index.php';</script>";
    exit;
}

require_once 'classes/Login.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $loginInput = $_POST['loginInput']; // Accepts either username or email
    $password = $_POST['password'];
    $login = new Login();

    $login_result = $login->login($loginInput, $password); // Adjusted method

    if ($login_result === true) {
        header('Location: admin/');
        exit;
    } else {
        $error_message = $login_result;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales & Purchase - Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background: url('https://source.unsplash.com/1600x900/?business,commerce') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
        }

        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-form {
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }

        .login-form h2 {
            margin-bottom: 30px;
            font-weight: bold;
            color: #28a745; /* Green for sales theme */
        }

        .form-control {
            border-radius: 10px;
            padding: 12px 20px;
        }

        .btn-login {
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: bold;
            background-color: #28a745;
            border: none;
        }

        .btn-login:hover {
            background-color: #218838;
        }

        .forgot-password {
            text-align: right;
            margin-top: 10px;
        }

        .error-message {
            color: #dc3545;
            font-size: 0.9em;
            margin-top: 5px;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <form class="login-form" method="post" action="login.php" novalidate>
            <h2 class="text-center">Login to Sales & Purchase</h2>
            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>
            <div class="form-group">
                <input type="text" name="loginInput" class="form-control" placeholder="Email or Username" required value="<?php echo isset($loginInput) ? htmlspecialchars($loginInput) : ''; ?>">
            </div>
            <div class="form-group">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <button type="submit" class="btn btn-success btn-block btn-login">Log In</button>
            <!-- <div class="forgot-password">
                <a href="forgot-password.php">Forgot Password?</a>
            </div> -->
            <hr>
            <div class="text-center mt-3">
                Don't have an account? <a href="register.php">Sign up</a>
            </div>
        </form>
    </div>
</body>

</html>
