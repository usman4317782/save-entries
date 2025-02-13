<?php require_once "pages/header.php"; ?>

<?php require_once "pages/sidenav.php"; ?>

<?php require_once "pages/topnav.php"; ?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">All Registered Users</h1>
    </div>

    <!-- user profile section -->

    <div class="row">
        <div class="col-lg-4">
            <!-- Removed Profile Pictures Section -->
        </div>
        <div class="col-lg-12">
            <!-- Removed Bio Section -->
            <!-- Removed Affiliations Section -->
            <!-- Removed Research Interests Section -->

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Registered Users Record</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <?php
                        $fetchAllUsers = new FetchAllUsers();
                        $users = $fetchAllUsers->fetchAllUsers();
                        ?>
                        <?php
                        $deleteUser = new DeleteUser();
                        if (isset($_GET['action']) && $_GET['action'] == 'delete') {
                            if ($deleteUser->deleteUser($_GET['id'])) {
                                //use javascript to redirect to the same page after 1 second and also show the success message
                                echo "<script>setTimeout(function(){window.location.href='more_users.php';}, 1000);</script>";
                                echo "<div class='alert alert-success'>User deleted successfully</div>";
                                exit;
                            }
                        }
                        ?>
                        <?php
                        //active and inactive user
                        $activeInActiveUser = new ActiveInActiveUser();
                        if (isset($_GET['status']) && $_GET['status'] == 'active') {
                            if ($activeInActiveUser->activeUser($_GET['id'])) {
                                echo "<script>setTimeout(function(){window.location.href='more_users.php';}, 1000);</script>";
                                echo "<div class='alert alert-success'>User activated successfully</div>";
                                exit;
                            }
                        } elseif (isset($_GET['status']) && $_GET['status'] == 'inactive') {
                            if ($activeInActiveUser->inactiveUser($_GET['id'])) {
                                echo "<script>setTimeout(function(){window.location.href='more_users.php';}, 1000);</script>";
                                echo "<div class='alert alert-success'>User inactivated successfully</div>";
                                exit;
                            }
                        }

                        ?>
                        <table class="table table-bordered" id="usersTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Email Verified On</th>
                                    <th>Profile Picture</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Email Verified On</th>
                                    <th>Profile Picture</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                <?php $i = 1; foreach ($users as $user): ?>
                                    <tr>
                                        <td><?php echo $i++; ?></td>
                                        <td><?php echo $user['username']; ?></td>
                                        <td><?php echo $user['email']; ?></td>
                                        <td><?php echo date('d-m-Y h:i:s A', strtotime($user['email_verified_at'])); ?></td>
                                        <td><img src="<?php echo  "../uploads/profile_images/" . $user['profile_picture']; ?>" alt="Profile Picture" style="width: 100px; height: 100px; border-radius: 50%;"></td>
                                        <?php if ($user['is_verified'] == 1): ?>
                                            <td><span class="badge badge-success">Active</span></td>
                                        <?php else: ?>
                                            <td><span class="badge badge-danger">Inactive</span></td>
                                        <?php endif; ?>
                                        <td>
                                            <a href="show-user-profile.php?id=<?php echo $user['id']; ?>" title="Show Profile" class="text text-info"><i class="fas fa-eye"></i></a>
                                            <!-- <a href="update_user_profile.php?id=<?php echo $user['id']; ?>" title="Update Profile" class="text text-warning"><i class="fas fa-edit"></i></a> -->
                                            <a href="?id=<?php echo $user['id']; ?>&action=delete" title="Delete Profile" onclick="return confirm('Are you sure you want to delete this profile?');" class="text text-danger"><i class="fas fa-trash"></i></a>
                                            <?php if ($user['is_verified'] == 1): ?>
                                                <a href="?id=<?php echo $user['id']; ?>&status=inactive" title="Set Inactive" class="text text-danger">
                                                    <i class="fas fa-user-times" style="color: red;"></i>
                                                </a>
                                            <?php else: ?>
                                                <a href="?id=<?php echo $user['id']; ?>&status=active" title="Set Active" class="text text-success">
                                                    <i class="fas fa-user-check" style="color: green;"></i>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>





</div>


<?php require_once "pages/footer.php"; ?>
