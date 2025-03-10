<?php
require_once "classes/Auth.php";

// require_once "config/config.php";
// session_start();
// Check if user is already logged in
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    echo "<script>window.location.href='dashboard/index.php';</script>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $auth = new Auth();
    $username = $_POST["username"];
    $password = $_POST["password"];

    $loginStatus = $auth->login($username, $password);

    if ($loginStatus === true) {
        header("Location: dashboard/index.php");
        exit;
    } else {
        $msg = "<p style='color:red;'>$loginStatus</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <!-- Bootstrap 5 CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Custom CSS -->
   <link href="assets/css/index.css" rel="stylesheet">
</head>

<body>
    <div class="login-card">
        <h2>Welcome SaveEntries!</h2>
        <?php
        if(isset($msg)){
            echo $msg;
        }
        ?>
        <form action="" method="post">
            <div class="mb-3">
                <input type="text" class="form-control" placeholder="Username" required name="username" value="<?php echo isset($_POST['username']) ? $_POST['username'] : ''; ?>">
            </div>
            <div class="mb-3">
                <input type="password" class="form-control" placeholder="Password" required name="password">
            </div>
            <button type="submit" class="btn btn-login">Login</button>
        </form>
        <!-- <div class="social-login">
            <p class="text-muted">Or login with</p>
            <button class="btn btn-outline-primary">
                <i class="material-icons">facebook</i>
            </button>
            <button class="btn btn-outline-danger">
                <i class="material-icons">google</i>
            </button>
            <button class="btn btn-outline-dark">
                <i class="material-icons">apple</i>
            </button>
        </div> -->
        <div class="footer-links">
            <!-- <a href="#">Forgot Password?</a> -->
            <!-- <a href="#">Create Account</a> -->
        </div>
    </div>

    <!-- Bootstrap 5 JS (Optional) -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>