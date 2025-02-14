<?php
require_once "pages/header.php";
require_once "pages/sidenav.php";
require_once "pages/topnav.php";
session_start();
// Generate a CSRF token if one isn't already set
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$register = new Register();
$errors = [];
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Invalid CSRF token!");
    }

    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];

    $result = $register->registerUser($username, $email, $password, $confirm_password, $role);

    if ($result['success'] === true) {
        $success_message = $result['message'];
        unset($_SESSION['csrf_token']); // Regenerate token after successful registration
    } else {
        $errors = $result['errors'];
    }
}
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Add New User</h1>
    </div>

    <!-- Users Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Add New</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <form class="register-form" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                    
                    <!-- CSRF Token -->
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">

                    <?php if (!empty($success_message)): ?>
                        <div class="alert alert-success success-message"> <?= htmlspecialchars($success_message); ?> </div>
                    <?php endif; ?>

                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul>
                                <?php foreach ($errors as $error): ?>
                                    <li><?= htmlspecialchars($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="username">User Name</label>
                                <input type="text" class="form-control" id="username" name="username" placeholder="User Name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Email Address" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="confirm_password">Confirm Password</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
                            </div>
                        </div>
                    </div>

                    <!-- Role Dropdown -->
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

                    <button type="submit" class="btn btn-success btn-block btn-register">Add</button>
                </form>
            </div>
        </div>
    </div>

</div>

<?php require_once "pages/footer.php"; ?>

<script>
    document.querySelector('.register-form').addEventListener('submit', function(e) {
        document.querySelector('.btn-register').innerHTML = 'Processing...';
    });
</script>
