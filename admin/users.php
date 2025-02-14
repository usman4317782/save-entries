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
            <div class="table-responsive">
                <table class="table table-bordered" id="all-users-table">
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
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= htmlspecialchars($user['id']); ?></td>
                                <td><?= htmlspecialchars($user['username']); ?></td>
                                <td><?= htmlspecialchars($user['email']); ?></td>
                                <td><?= htmlspecialchars($user['role']); ?></td>
                                <td>
                                    <?php if ($user['status'] == 1): ?>
                                        <span class="badge badge-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="update-user.php?id=<?= $user['id']; ?>" class="btn btn-info btn-sm" title="Update"><i class="fas fa-edit"></i></a>
                                    <a href="delete-user.php?id=<?= $user['id']; ?>" class="btn btn-danger btn-sm" title="Delete" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></a>
                                    <a href="toggle-status.php?id=<?= $user['id']; ?>" class="btn btn-<?= $user['status'] ? 'warning' : 'success'; ?> btn-sm" title="<?= $user['status'] ? 'Deactivate' : 'Activate'; ?>">
                                        <i class="fas fa-<?= $user['status'] ? 'ban' : 'check'; ?>"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<?php require_once "pages/footer.php"; ?>

<!-- Custom CSS for Add User Button -->
