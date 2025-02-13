<?php require_once "pages/header.php"; ?>

<?php require_once "pages/sidenav.php"; ?>

<?php require_once "pages/topnav.php"; ?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Community Guidelines</h1>
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
                    <h6 class="m-0 font-weight-bold text-primary">Community Guidelines</h6>
                </div>
                <div class="card-body">
                    <?php
                    // $guidelineId = $_GET['id'] ?? null; // Assuming you're passing the ID via a GET parameter
                    $guidelineId = 2; // Assuming you're passing the ID via a GET parameter
                    $communityGuidelines = new CommunityGuidelines();
                    $guidelines = $guidelineId ? $communityGuidelines->fetchGuideline($guidelineId) : [];

                    // If you want to handle the update after form submission
                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_guidelines'])) {
                        $communityGuidelines->updateGuideline($guidelineId, $_POST);
                        // Optionally redirect or show a success message
                    }
                    ?>
                    <form action="" method="post">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Title</h6>
                            </div>
                            <div class="card-body">
                                <textarea id="title" class="form-control" rows="3" name="title"><?= htmlspecialchars_decode(trim($guidelines['title'] ?? 'No Title Found')); ?></textarea>
                            </div>
                        </div>
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Description</h6>
                            </div>
                            <div class="card-body">
                                <textarea id="description" class="form-control" rows="3" name="description"><?= htmlspecialchars_decode(trim($guidelines['description'] ?? 'No Description Found')); ?></textarea>
                            </div>
                        </div>
                        <div class="card shadow mb-4">
                            <div class="card-body">
                                <button type="submit" name="update_guidelines" class="btn btn-primary btn-user btn-block">Update Guidelines</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<?php require_once "pages/footer.php"; ?>
