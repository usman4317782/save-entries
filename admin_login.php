<?php
require_once "config.php";
session_start();
if (isset($_SESSION['user_id'])) {
?>
    <script>
        window.location.href = 'researcher/';
    </script>
<?php
}
require_once 'classes/AdminLogin.php';

// ... existing code ...

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $login = new AdminLogin();

    $login_result = $login->login($email, $password);

    if ($login_result === true) {
        // Redirect to a protected page or dashboard
        header('Location: admin/');
        exit;
    } else {
        $error_message = $login_result; // Set the error message based on login result
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
        body {
            background: url('https://source.unsplash.com/1600x900/?research,library') no-repeat center center fixed;
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
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }

        .login-form h2 {
            margin-bottom: 30px;
            font-weight: bold;
            color: #333;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-control {
            border-radius: 20px;
            padding: 12px 20px;
        }

        .btn-login {
            border-radius: 20px;
            padding: 10px 20px;
            font-weight: bold;
            background-color: #007bff;
            border: none;
        }

        .btn-login:hover {
            background-color: #0056b3;
        }

        .forgot-password {
            text-align: right;
            margin-top: 10px;
        }

        .social-login {
            margin-top: 20px;
        }

        .social-btn {
            width: 100%;
            margin-bottom: 10px;
            font-weight: bold;
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


        <form class="login-form" method="post" action="admin_login.php" novalidate>
            <h2 class="text-center">Login to Admin</h2>
            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>
            <div class="form-group">
                <input type="email" name="email" class="form-control" placeholder="Email Address" required value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
            </div>
            <div class="form-group">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block btn-login">Log In</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>