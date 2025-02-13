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
                        $fetchUserActivities = new UserActivity();
                        $activities = $fetchUserActivities->fetchUserActivities();
                        ?>
                        <table class="table table-bordered" id="usersTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Login Time</th>
                                    <th>Logout Time</th>
                                    <th>IP Address</th>
                                    <th>User Agent</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Login Time</th>
                                    <th>Logout Time</th>
                                    <th>IP Address</th>
                                    <th>User Agent</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                <?php $i = 1; foreach ($activities as $activity): ?>
                                    <tr>
                                        <td><?php echo $i++; ?></td>
                                        <td><?php echo $activity['name']; ?></td>
                                        <td><?php echo $activity['email']; ?></td>
                                        <td><?php echo date('d-m-Y h:i:s A', strtotime($activity['login_time'])); ?></td>
                                        <td><?php echo $activity['logout_time'] ? date('d-m-Y h:i:s A', strtotime($activity['logout_time'])) : 'N/A'; ?></td>
                                        <td><?php echo $activity['ip_address']; ?></td>
                                        <td><?php echo $activity['user_agent']; ?></td>
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
