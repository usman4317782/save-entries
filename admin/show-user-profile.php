<?php require_once "pages/header.php"; ?>

<?php require_once "pages/sidenav.php"; ?>

<?php require_once "pages/topnav.php"; ?>

<!-- below code fetches the user profile data -->
<?php
// require_once BASE_PATH . '/classes/Profile.php';
$getProfileData = new Profile();
$userProfileData = $getProfileData->getProfileDetails($_GET['id']);
// Check if a profile picture exists, if not, use a default image
$profilePicture = !empty($userProfileData['profile_picture']) ? '../uploads/profile_images/' . $userProfileData['profile_picture'] : 'includes/img/undraw_profile.svg';

?>

<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">User Profile</h1>
    </div>

    <!-- user profile section -->

    <div class="row">
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Profile Pictures</h6>
                </div>
                <div class="card-body">
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
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Bio</h6>
                </div>
                <div class="card-body">
                    <p id="bio"><?= htmlspecialchars_decode(trim($userProfileData['bio'] ?? 'No Biography Found')); ?></p> <!-- Check if bio exists -->
                </div>
            </div>
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Affiliations</h6>
                </div>
                <div class="card-body">
                    <p id="affiliations"><?= htmlspecialchars_decode(trim($userProfileData['affiliations'] ?? 'No Affiliations Found')); ?></p> <!-- Check if affiliations exist -->
                </div>
            </div>
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Research Interests</h6>
                </div>
                <div class="card-body">
                    <p id="research-interests"><?= htmlspecialchars_decode(trim($userProfileData['research_interests'] ?? 'No Research Interests Found')); ?></p> <!-- Check if research interests exist -->
                </div>

            </div>
            <?php
            $fetchUserProfileDetails = new FetchUserProfileDetails();
            $userProfileData = $fetchUserProfileDetails->fetchUserProfileDetails($_GET['id']);
            ?>
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Account Information</h6>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" readonly value="<?= htmlspecialchars($userProfileData['username'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" readonly class="form-control" id="email" name="email" value="<?= htmlspecialchars($userProfileData['email'] ?? ''); ?>" required>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php require_once "pages/footer.php"; ?>