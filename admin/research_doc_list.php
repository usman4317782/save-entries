<?php require_once "pages/header.php"; ?>

<?php require_once "pages/sidenav.php"; ?>

<?php require_once "pages/topnav.php"; ?>

<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Resource Upload and Sharing History </h1>
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
                    <h6 class="m-0 font-weight-bold text-primary">All Uploaded Resources</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <?php
                        $fetcher = new FetchResearchRecord();
                        $records = $fetcher->fetchAllResearchRecordForAdmin();

                        // Initialize $resources to avoid undefined variable warning
                        $resources = $records; // Assuming $records contains the data you want to iterate over

                        ?>

                        <table class="table table-bordered" id="usersTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Title</th>
                                    <th>File Name</th>
                                    <th>Upload Date</th>
                                    <th>Modified Date</th>
                                    <th>File Size</th>
                                    <th>Category</th>
                                    <th>Visibility</th>
                                    <th>Keywords</th>
                                    <th>Authors</th>
                                    <th>Publication Date</th>
                                    <th>Citation Metrics</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Title</th>
                                    <th>File Name</th>
                                    <th>Upload Date</th>
                                    <th>Modified Date</th>
                                    <th>File Size</th>
                                    <th>Category</th>
                                    <th>Visibility</th>
                                    <th>Keywords</th>
                                    <th>Authors</th>
                                    <th>Publication Date</th>
                                    <th>Citation Metrics</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                <?php $i = 1;
                                foreach ($resources as $resource): ?>
                                    <tr>
                                        <td><?php echo $i++; ?></td>
                                        <td><?php echo ucwords($resource['title']); ?></td>
                                        <td><?php echo $resource['file_name_for_user']; ?></td>
                                        <td><?php echo date('d-m-y h:i:s A', strtotime($resource['upload_date'])); ?></td>
                                        <td><?php echo date('d-m-y h:i:s A', strtotime($resource['modified_date'])); ?></td>
                                        <td><?php echo $resource['file_size']; ?> KB</td>
                                        <td><?php echo ucfirst($resource['category']); ?></td>
                                        <td><?php echo ucfirst($resource['visibility']); ?></td>
                                        <td><?php echo $resource['keywords']; ?></td>
                                        <td><?php echo $resource['authors']; ?></td>
                                        <td><?php echo date('d-m-y', strtotime($resource['publication_date'])); ?></td>
                                        <td><?php echo $resource['citation_metrics']; ?></td>
                                        <td>
                                           
                                            <!-- Download Resource Link -->
                                            <a href="download.php?file=<?php echo urlencode($resource['file_name']); ?>"
                                                title="Download Resource"
                                                class="text text-success">
                                                <i class="fas fa-download"></i>
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
    </div>
</div>


<?php require_once "pages/footer.php"; ?>