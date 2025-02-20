</div>

<!-- Footer -->
<footer class="sticky-footer bg-white">
    <div class="container my-auto">
        <div class="copyright text-center my-auto">
            <span>Copyright &copy; SaveEntries <?php echo date('Y'); ?></span>
        </div>
    </div>
</footer>
<!-- End of Footer -->

</div>
<!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->
<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>



<!-- Bootstrap core JavaScript-->
<script src="includes/vendor/jquery/jquery.min.js"></script>
<script src="includes/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="includes/vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="includes/js/sb-admin-2.min.js"></script>

<!-- Page level plugins -->
<script src="includes/vendor/chart.js/Chart.min.js"></script>

<!-- Page level custom scripts -->
<script src="includes/js/demo/chart-area-demo.js"></script>
<script src="includes/js/demo/chart-pie-demo.js"></script>

<!-- Include TinyMCE -->
<script src="https://cdn.tiny.cloud/1/e4z0nvjqw3m108xzug1a2rsu9iqmlc88xscop5qd53u4ygfm/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>

<!-- Page level plugins -->
<script src="includes/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="includes/vendor/datatables/dataTables.bootstrap4.min.js"></script>

<!-- Page level custom scripts -->
<script src="includes/js/demo/datatables-demo.js"></script>

<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.colVis.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize DataTable with advanced options
        var table = $('.all-users-table').DataTable({
            ajax: {
                url: '<?= BASE_URL ?>/ajax/fetch-users.php',
                type: 'GET',
                dataType: 'json',
                dataSrc: '',
                error: function(xhr, error, thrown) {
                    console.log("AJAX Error:", error, "Details:", thrown);
                    console.log("Response Text:", xhr.responseText);
                    alert("Failed to fetch data. Check console for details.");
                }
            },
            columns: [{
                    data: 'id'
                },
                {
                    data: 'username'
                },
                {
                    data: 'email'
                },
                {
                    data: 'role'
                },
                {
                    data: 'is_verified',
                    render: function(data) {
                        return data == 1 ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-danger">Inactive</span>';
                    }
                },
                {
                    data: 'id',
                    render: function(data, type, row) {
                        return `
                        <a href="update-user.php?id=${data}" class="btn btn-info btn-sm" title="Update"><i class="fas fa-edit"></i></a>
                        <button class="btn btn-danger btn-sm delete-user" data-id="${data}" title="Delete"><i class="fas fa-trash"></i></button>
                        <a href="toggle-status.php?id=${data}" class="btn btn-${row.is_verified ? 'warning' : 'success'} btn-sm" title="${row.is_verified ? 'Deactivate' : 'Activate'}">
                            <i class="fas fa-${row.is_verified ? 'ban' : 'check'}"></i>
                        </a>
                    `;
                    }
                }
            ],
            dom: 'Bfrtip',
            buttons: [{
                    extend: 'copy',
                    className: 'btn btn-secondary btn-sm',
                    text: '<i class="fas fa-copy"></i> Copy'
                },
                {
                    extend: 'csv',
                    className: 'btn btn-primary btn-sm',
                    text: '<i class="fas fa-file-csv"></i> CSV'
                },
                {
                    extend: 'excel',
                    className: 'btn btn-success btn-sm',
                    text: '<i class="fas fa-file-excel"></i> Excel'
                },
                {
                    extend: 'pdf',
                    className: 'btn btn-danger btn-sm',
                    text: '<i class="fas fa-file-pdf"></i> PDF'
                },
                {
                    extend: 'print',
                    className: 'btn btn-info btn-sm',
                    text: '<i class="fas fa-print"></i> Print'
                }
            ],
            responsive: true,
            paging: true,
            searching: true,
            ordering: true,
            order: [
                [0, 'asc']
            ],
            select: true // Enables row selection for bulk actions
        });

        // Bulk delete users
        $(document).on("click", "#bulk-delete", function() {
            var selectedUsers = table.rows({
                selected: true
            }).data().toArray().map(row => row.id);
            if (selectedUsers.length === 0) {
                alert("No users selected.");
                return;
            }
            if (confirm("Are you sure you want to delete selected users?")) {
                $.post("<?= BASE_URL ?>/ajax/bulk-delete.php", {
                    user_ids: selectedUsers
                }, function(response) {
                    alert(response);
                    table.ajax.reload(null, false);
                }).fail(function(xhr) {
                    console.error("AJAX Error:", xhr.responseText);
                    alert("Failed to delete users.");
                });
            }
        });

        // Handle delete confirmation
        $(document).on("click", ".delete-user", function() {
            var userId = $(this).data("id");
            if (confirm("Are you sure you want to delete this user?")) {
                $.post("<?= BASE_URL ?>/ajax/delete-user.php", {
                    delete_id: userId
                }, function(response) {
                    alert(response);
                    table.ajax.reload(null, false);
                }).fail(function(xhr) {
                    console.error("AJAX Error:", xhr.responseText);
                    alert("Failed to delete user.");
                });
            }
        });
    });
</script>
<!-- <script>
$(document).ready(function () {
    $('#usersTable, #all-users-table').DataTable({
        dom: 'Bfrtip',  // Adds buttons for export features
        buttons: [
            {
                extend: 'copy',
                text: '<i class="fas fa-copy"></i> Copy',
                className: 'btn btn-secondary btn-sm'
            },
            {
                extend: 'csv',
                text: '<i class="fas fa-file-csv"></i> CSV',
                className: 'btn btn-primary btn-sm'
            },
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'btn btn-success btn-sm'
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                className: 'btn btn-danger btn-sm'
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Print',
                className: 'btn btn-info btn-sm'
            }
        ],
        responsive: true, // Makes the table mobile-friendly
        paging: true,      // Enables pagination
        searching: true,   // Enables search filter
        ordering: true,    // Enables sorting
        info: true         // Shows info (e.g., "Showing 1-10 of 50")
    });
});
</script> -->
<script>
    function deleteImage(imageName) {
        // Implement the logic to delete the image
        // This could involve an AJAX request to a server-side script
        // that handles the deletion of the image from the server
        console.log("Delete image: " + imageName);
    }
</script>


</body>

</html>