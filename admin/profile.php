<?php require_once "pages/header.php"; ?>

<?php require_once "pages/sidenav.php"; ?>

<?php require_once "pages/topnav.php"; ?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">User Profile</h1>
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
                    <h6 class="m-0 font-weight-bold text-primary">Update Profile</h6>
                </div>
                <div class="card-body">
                    <?php
                    if(isset($updateProfile->msg)){
                        echo $updateProfile->msg;
                    }
                    ?>
                    <form action="" method="POST">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($userProfileData['username'] ?? ''); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($userProfileData['email'] ?? ''); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="password">New Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Leave blank to keep current password">
                        </div>
                        <button type="submit" name="update_admin_profile" class="btn btn-primary btn-user btn-block">Update Profile</button>
                    </form>
                </div>
            </div>
        </div>
    </div>





</div>


<?php require_once "pages/footer.php"; ?>
