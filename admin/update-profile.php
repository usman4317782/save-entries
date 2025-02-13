<?php require_once "pages/header.php"; ?>

<?php require_once "pages/sidenav.php"; ?>

<?php require_once "pages/topnav.php"; ?>


<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            Update Profile &nbsp;
            <a href="update-profile.php" title="Refresh Page">
                <i class="fa fa-refresh" aria-hidden="true"></i>
            </a>
        </h1>
    </div>

    <!-- user profile section -->

    <div class="row">
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Profile Pictures</h6>
                </div>
                <div class="card-body">
                    <?php
                    if (isset($updateProfile->msg)) {
                        echo $updateProfile->msg;
                    }
                    ?>
                    <form action="" method="post" enctype="multipart/form-data">
                        <input type="file" name="profile_images[]" multiple class="form-control mb-3" accept="image/*">
                        <button type="submit" name="upload_image" class="btn btn-primary">Upload Images</button>
                    </form>
                    <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img class="img-profile rounded-circle profile-picture" src="<?php echo $profilePicture; ?>" alt="User Profile Picture">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <form action="" method="post">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Bio</h6>
                    </div>
                    <div class="card-body">
                        <textarea id="bio" class="form-control" rows="3" name="bio"><?= htmlspecialchars_decode(trim($userProfileData['bio'] ?? 'No Biography Found')); ?></textarea>
                    </div>
                </div>
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Affiliations</h6>
                    </div>
                    <div class="card-body">
                        <textarea id="affiliations" class="form-control" rows="3" name="affiliations"><?= htmlspecialchars_decode(trim($userProfileData['affiliations'] ?? 'No Affiliations Found')); ?></textarea>
                    </div>
                </div>
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Research Interests</h6>
                    </div>
                    <div class="card-body">
                        <textarea id="research-interests" class="form-control" rows="3" name="research_interests"><?= htmlspecialchars_decode(trim($userProfileData['research_interests'] ?? 'No Research Interests Found')); ?></textarea>
                    </div>
                </div>
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <button type="submit" name="update_profile" class="btn btn-primary btn-user btn-block">Update Profile</button>
                    </div>
                </div>
            </form>
        </div>
    </div>





</div>


<?php require_once "pages/footer.php"; ?>