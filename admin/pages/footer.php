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


<script>
    tinymce.init({
        selector: 'textarea', // Apply TinyMCE to all textareas
        plugins: 'lists link image charmap print preview anchor',
        toolbar: 'undo redo | styleselect | bold italic underline | bullist numlist | link image | removeformat',
        menubar: false,
        branding: false,
        height: 300
    });
</script>


<script>
    function deleteImage(imageName) {
        // Implement the logic to delete the image
        // This could involve an AJAX request to a server-side script
        // that handles the deletion of the image from the server
        console.log("Delete image: " + imageName);
    }
</script>

<script>
    $(document).ready(function() {
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
</script>

<!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->
<script>
    var ctx = document.getElementById('salesChart').getContext('2d');
    var salesChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Total Sales', 'Total Purchases', 'Total Revenue', 'Total Orders'],
            datasets: [{
                label: 'Amount ($)',
                data: [<?php echo $total_sales; ?>, <?php echo $total_purchases; ?>, <?php echo $total_revenue; ?>, <?php echo $total_orders; ?>],
                backgroundColor: [
                    'rgba(40, 167, 69, 0.6)',
                    'rgba(220, 53, 69, 0.6)',
                    'rgba(0, 123, 255, 0.6)',
                    'rgba(255, 193, 7, 0.6)'
                ],
                borderColor: [
                    'rgba(40, 167, 69, 1)',
                    'rgba(220, 53, 69, 1)',
                    'rgba(0, 123, 255, 1)',
                    'rgba(255, 193, 7, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
</body>

</html>