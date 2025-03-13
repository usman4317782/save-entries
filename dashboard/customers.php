<?php include "pages/header.php"; ?>
<?php include "pages/spinner.php"; ?>
<?php include "pages/sidenav.php"; ?>
<!-- Content Start -->
<div class="content">
    <?php include "pages/topnav.php"; ?>
    <div class="container mt-4">
        <h2 class="mb-3">Customer Management</h2>
        <div class="d-flex justify-content-between mb-3">
            <div>
                <button id="addCustomerBtn" class="btn btn-primary btn-icon">
                    <i class="bi bi-plus-circle"></i>
                    <span class="btn-text">Add Customer</span>
                </button>
                <button id="updateBalancesBtn" class="btn btn-success btn-icon ms-2">
                    <i class="bi bi-arrow-repeat"></i>
                    <span class="btn-text">Update Balances</span>
                </button>
            </div>
            <button id="bulkDeleteBtn" class="btn btn-danger btn-icon">
                <i class="bi bi-trash"></i>
                <span class="btn-text">Bulk Delete</span>
            </button>
        </div>
        <div class="row mt-3">
            <div class="col-md-4 mb-3">
                <input type="text" id="searchInput" placeholder="Search by name..." class="form-control">
            </div>
            <div class="col-md-4 mb-3">
                <input type="text" id="startDate" placeholder="Start Date" class="form-control">
            </div>
            <div class="col-md-4 mb-3">
                <input type="text" id="endDate" placeholder="End Date" class="form-control">
            </div>
        </div>
        <div class="text-right">
            <button id="clearFilters" class="btn btn-info btn-icon">
                <i class="bi bi-x-circle"></i>
                <span class="btn-text">Clear Filters</span>
            </button>
        </div>
        <br>
        <table id="customersTable" class="table table-striped">
            <thead>
                <tr>
                    <th><input type="checkbox" id="selectAll"></th>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Contact Number</th>
                    <th>Closing Balance</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>

    <!-- Add/Edit Customer Modal -->
    <div class="modal fade" id="customerModal" tabindex="-1" aria-labelledby="customerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="customerModalLabel">Add/Edit Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="customerForm">
                        <input type="hidden" name="id" id="customerId">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <input type="text" class="form-control" name="address">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contact Number</label>
                            <input type="text" class="form-control" name="contact_number" required>
                        </div>
                        <div class="mb-3" id="balanceContainer" style="display: none;">
                            <label class="form-label">Closing Balance</label>
                            <input type="text" class="form-control" id="closingBalance" readonly>
                        </div>
                        <button type="submit" class="btn btn-success btn-icon">
                            <i class="bi bi-save"></i>
                            <span class="btn-text">Save</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php include "pages/footer.php"; ?>
</div>
<script>
    $(document).ready(function() {
        var table = $('#customersTable').DataTable({
            "ajax": "customer_actions.php?action=fetch",
            "pageLength": 10,
            "lengthMenu": [10, 50, 100, -1],
            "columns": [{
                    "data": "customer_id",
                    render: function(data) {
                        return '<input type="checkbox" class="selectCustomer" value="' + data + '">';
                    }
                },
                {
                    "data": "customer_id"
                },
                {
                    "data": "name"
                },
                {
                    "data": "address"
                },
                {
                    "data": "contact_number"
                },
                {
                    "data": "closing_balance",
                    render: function(data) {
                        return parseFloat(data).toFixed(2);
                    }
                },
                {
                    "data": "created_at"
                },
                {
                    "data": "customer_id",
                    render: function(data) {
                        return '<button class="btn btn-warning btn-sm editBtn btn-icon" data-id="' + data + '"><i class="bi bi-pencil"></i><span class="btn-text"></span></button> ' +
                            '<button class="btn btn-danger btn-sm deleteBtn btn-icon" data-id="' + data + '"><i class="bi bi-trash"></i><span class="btn-text"></span></button>';
                    }
                }
            ],
            dom: 'Bfrtip',
            buttons: [{
                    extend: 'pdf',
                    text: '<i class="bi bi-file-pdf"></i><span class="btn-text"> Export PDF</span>',
                    className: 'btn-icon'
                },
                {
                    extend: 'csv',
                    text: '<i class="bi bi-file-earmark-spreadsheet"></i><span class="btn-text"> Export CSV</span>',
                    className: 'btn-icon'
                },
                {
                    extend: 'print',
                    text: '<i class="bi bi-printer"></i><span class="btn-text"> Print</span>',
                    className: 'btn-icon'
                }
            ]
        });

        // Show modal when Add Customer button is clicked
        $('#addCustomerBtn').click(function() {
            $('#customerModalLabel').text('Add Customer');
            $('#customerForm')[0].reset();
            $('#customerId').val('');
            $('#balanceContainer').hide();
            $('#customerModal').modal('show');
        });

        // Update all customer balances
        $('#updateBalancesBtn').click(function() {
            if (confirm('Are you sure you want to update all customer balances?')) {
                $.get('customer_actions.php?action=update_balances', function(response) {
                    response = JSON.parse(response);
                    alert(response.message);
                    table.ajax.reload();
                });
            }
        });

        // Edit Customer
        $(document).on('click', '.editBtn', function() {
            let id = $(this).data('id');
            $.get('customer_actions.php?action=fetch_single&id=' + id, function(response) {
                let customer = JSON.parse(response);
                $('#customerId').val(customer.customer_id);
                $('input[name="name"]').val(customer.name);
                $('input[name="address"]').val(customer.address);
                $('input[name="contact_number"]').val(customer.contact_number);
                $('#closingBalance').val(parseFloat(customer.closing_balance).toFixed(2));
                $('#balanceContainer').show();
                $('#customerModalLabel').text('Edit Customer');
                $('#customerModal').modal('show');
            });
        });

        // Submit Add/Edit Customer Form
        $('#customerForm').submit(function(e) {
            e.preventDefault();
            let action = $('#customerId').val() ? 'update' : 'add';
            $.post('customer_actions.php?action=' + action, $(this).serialize(), function(response) {
                response = JSON.parse(response);
                if (response.success) {
                    $('#customerModal').modal('hide');
                    table.ajax.reload();
                } else {
                    alert(response.message);
                }
            });
        });

        // Bulk Delete Customers
        $('#bulkDeleteBtn').click(function() {
            var ids = [];
            $('.selectCustomer:checked').each(function() {
                ids.push($(this).val());
            });
            if (ids.length === 0) {
                alert("Please select customers to delete.");
                return;
            }
            if (confirm("Are you sure you want to delete selected customers?")) {
                $.post("customer_actions.php?action=bulk_delete", {
                    ids: ids
                }, function(response) {
                    alert(response.message);
                    table.ajax.reload();
                }, "json");
            }
        });

        // Delete Single Customer
        $(document).on('click', '.deleteBtn', function() {
            let id = $(this).data('id');
            if (confirm('Are you sure you want to delete this customer?')) {
                $.post('customer_actions.php?action=bulk_delete', {
                    ids: [id]
                }, function(response) {
                    response = JSON.parse(response);
                    alert(response.message);
                    table.ajax.reload();
                });
            }
        });

        // Select/Deselect All Checkboxes
        $('#selectAll').on('change', function() {
            $('.selectCustomer').prop('checked', this.checked);
        });

        // Dynamic Filters
        $('#searchInput, #startDate, #endDate').on('input change', function() {
            var search = $('#searchInput').val();
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();
            table.ajax.url("customer_actions.php?action=fetch&search=" + search + "&startDate=" + startDate + "&endDate=" + endDate).load();
        });

        // Clear Filters
        $('#clearFilters').click(function() {
            $('#searchInput').val('');
            $('#startDate').val('');
            $('#endDate').val('');
            table.ajax.url("customer_actions.php?action=fetch").load();
        });

        // Initialize Datepicker
        flatpickr('#startDate', {
            dateFormat: 'Y-m-d'
        });
        flatpickr('#endDate', {
            dateFormat: 'Y-m-d'
        });
    });
</script>