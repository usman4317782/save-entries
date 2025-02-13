<?php
require_once 'config.php';
require_once 'classes/EmailVerification.php';

$emailVerification = new EmailVerification();
$isVerified = false;
$message = '';

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $isVerified = $emailVerification->verifyEmail($token);

    if ($isVerified) {
        $message = "Your email has been successfully verified!";
    } else {
        $message = "Email verification failed. The token may be invalid or expired.";
    }
} else {
    $message = "No verification token provided.";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <!-- Bootstrap 4 CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <?php if ($isVerified): ?>
                            <i class="fas fa-check-circle text-success fa-5x mb-3"></i>
                            <h2 class="card-title">Email Verified!</h2>
                            <p class="card-text"><?php echo $message; ?></p>
                            <p class="card-text">You can now enjoy all the features of our platform.</p>
                            <a href="login.php" class="btn btn-primary mt-3">Go to Login</a>
                        <?php else: ?>
                            <i class="fas fa-times-circle text-danger fa-5x mb-3"></i>
                            <h2 class="card-title">Email Verification Failed</h2>
                            <p class="card-text"><?php echo $message; ?></p>
                            <a href="index.php" class="btn btn-primary mt-3">Back to Home</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 4 JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
