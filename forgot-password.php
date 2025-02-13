<?php
require_once 'classes/ForgotPassword.php';

$forgotPassword = new ForgotPassword();
$message = '';
$error = '';
$showSpinner = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    
    $result = $forgotPassword->sendResetLink($email);
    
    if ($result['success']) {
        $message = $result['message'];
        $showSpinner = true;
    } else {
        $error = $result['error'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ResearchHub - Forgot Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
        body {
            background: url('https://source.unsplash.com/1600x900/?library,study') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
        }
        .forgot-password-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .forgot-password-form {
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }
        .forgot-password-form h2 {
            margin-bottom: 20px;
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
        .btn-reset {
            border-radius: 20px;
            padding: 10px 20px;
            font-weight: bold;
            background-color: #007bff;
            border: none;
        }
        .btn-reset:hover {
            background-color: #0056b3;
        }
        .back-to-login {
            text-align: center;
            margin-top: 20px;
        }
        .alert {
            border-radius: 20px;
            margin-bottom: 20px;
        }
        
        /* Add these new styles for the spinner */
        .spinner-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
        .spinner {
            border: 5px solid #f3f3f3;
            border-top: 5px solid #007bff;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }
        .spinner-message {
            color: white;
            margin-top: 20px;
            font-size: 18px;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="spinner-overlay">
        <div class="spinner"></div>
        <div class="spinner-message">Sending reset link to your email...</div>
    </div>

    <div class="forgot-password-container">
        <form class="forgot-password-form" method="POST" action="" id="forgotPasswordForm">
            <h2 class="text-center">Forgot Password</h2>
            <p class="text-center mb-4">Enter your email address and we'll send you a link to reset your password.</p>
            
            <?php if ($message): ?>
                <div class="alert alert-success" role="alert">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <div class="form-group">
                <input type="email" name="email" class="form-control" placeholder="Email Address" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block btn-reset">Reset Password</button>
            <div class="back-to-login">
                <a href="login.php">Back to Login</a>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        <?php if ($showSpinner): ?>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('.spinner-overlay').style.display = 'flex';
            
            // Simulate email sending delay (remove this in production)
            setTimeout(function() {
                document.querySelector('.spinner-overlay').style.display = 'none';
            }, 3000);
        });
        <?php endif; ?>

        document.getElementById('forgotPasswordForm').addEventListener('submit', function(e) {
            // Show spinner immediately on form submission
            document.querySelector('.spinner-overlay').style.display = 'flex';
        });
    </script>
</body>
</html>
