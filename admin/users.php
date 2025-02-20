<?php
require_once "pages/header.php";
require_once "pages/sidenav.php";
require_once "pages/topnav.php";



$db = Database::getInstance()->getConnection();

// Fetch users excluding Admin role
$sql = "SELECT id, username, email, role, is_verified FROM users WHERE role != 'Admin'";
$stmt = $db->prepare($sql);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

//delete user 

if (isset($_GET['delete_id']) && isset($_GET['action']) && $_GET['action'] == 'delete_user') {
    $delete_id = htmlspecialchars($_GET['delete_id']);
    $delete_user = $profile->deleteProfile($delete_id);

    if ($delete_user) {
        $success_message = "User deleted successfully!";
    } else {
        $errors[] = "Failed to delete user!";
    }
}

?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Registered Users</h1>

        <!-- Add New User Button -->
        <a href="add-user.php" class="btn btn-primary btn-sm add-user-btn" title="Add New User">
            <i class="fas fa-user-plus"></i>
            <!-- <span class="btn-text">Add New User</span> -->
        </a>
    </div>

    <!-- Users Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">User List</h6>
        </div>
        <div class="card-body">
            <?php if (!empty($success_message)): ?>
                <div class="alert alert-success success-message"> <?= htmlspecialchars($success_message); ?> </div>
                <script>
                    setTimeout(function() {
                        window.location.href = 'users.php';
                    }, 1000);
                </script>
            <?php endif; ?>
            <div class="table-responsive">
                <table class="table table-bordered all-users-table">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>

            </div>
        </div>
    </div>

</div>

<?php require_once "pages/footer.php"; ?>

<!-- Custom CSS for Add User Button -->
