<?php include "pages/header.php"; ?>
<?php include "pages/spinner.php"; ?>
<?php include "pages/sidenav.php"; ?>

<!-- Content Start -->
<div class="content">
    <?php include "pages/topnav.php"; ?>
    
    <div class="container-fluid pt-4 px-4">
        <div class="bg-light rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h5 class="mb-0">Sales Returns</h5>
                <a href="create_return.php" class="btn btn-primary">
                    <i class="bi bi-plus me-2"></i>Create Return
                </a>
            </div>

            <!-- Filters -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <select id="customerFilter" class="form-control">
                        <option value="">All Customers</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" id="startDate" class="form-control flatpickr" placeholder="Start Date">
                </div>
                <div class="col-md-3">
                    <input type="text" id="endDate" class="form-control flatpickr" placeholder="End Date">
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <input type="text" id="searchInput" class="form-control" placeholder="Search returns...">
                        <button class="btn btn-primary" id="searchBtn">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Bulk Actions -->
            <div id="bulkActionsContainer" class="mb-3" style="display: none;">
                <button id="deleteSelected" class="btn btn-danger">
                    <i class="bi bi-trash me-2"></i>Delete Selected
                </button>
            </div>

            <!-- Returns Table -->
            <div class="table-responsive">
                <table id="returnsTable" class="table table-hover">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" id="selectAll">
                            </th>
                            <th>Return Number</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th class="text-end">Total Amount</th>
                            <th class="text-end">Final Amount</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be loaded dynamically -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php include "pages/footer.php"; ?>
</div>

<script>
$(document).ready(function() {
    // Initialize flatpickr for date inputs
    flatpickr('.flatpickr', {
        dateFormat: 'Y-m-d'
    });

    // Initialize Select2 for customer filter
    $('#customerFilter').select2({
        width: '100%',
        placeholder: 'Select Customer',
        allowClear: true,
        ajax: {
            url: 'return_actions.php',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    action: 'get_customers',
                    search: params.term
                };
            },
            processResults: function(data) {
                return {
                    results: data.data.map(function(customer) {
                        return {
                            id: customer.customer_id,
                            text: customer.name + (customer.contact_number ? ' - ' + customer.contact_number : '')
                        };
                    })
                };
            },
            cache: true
        }
    });

    // Initialize DataTable
    var table = $('#returnsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: 'return_actions.php',
            type: 'GET',
            data: function(d) {
                d.action = 'fetch';
                d.customer_id = $('#customerFilter').val();
                d.start_date = $('#startDate').val();
                d.end_date = $('#endDate').val();
                return d;
            },
            error: function(xhr, error, thrown) {
                console.error('DataTables error:', error, thrown);
                console.log('Server response:', xhr.responseText);
                alert('Error loading returns data. See console for details.');
            }
        },
        columns: [
            { data: 0, orderable: false, searchable: false },
            { data: 1, name: 'invoice_number' },
            { data: 2, name: 'customer_name' },
            { data: 3, name: 'return_date' },
            { data: 4, name: 'total_amount', className: 'text-end' },
            { data: 5, name: 'final_amount', className: 'text-end' },
            { data: 6, orderable: false, searchable: false }
        ],
        order: [[3, 'desc']], // Sort by date by default
        pageLength: 10,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'collection',
                text: '<i class="bi bi-download"></i> Export',
                buttons: [
                    'copy',
                    'excel',
                    'csv',
                    'pdf',
                    'print'
                ]
            }
        ],
        language: {
            processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
            emptyTable: 'No returns found',
            zeroRecords: 'No matching returns found'
        }
    });

    // Apply filters
    $('#customerFilter, #startDate, #endDate').change(function() {
        table.ajax.reload();
    });

    $('#searchBtn').click(function() {
        table.search($('#searchInput').val()).draw();
    });

    $('#searchInput').keypress(function(e) {
        if (e.which == 13) {
            table.search($(this).val()).draw();
        }
    });

    // Handle select all checkbox
    $('#selectAll').change(function() {
        $('.return-checkbox').prop('checked', $(this).prop('checked'));
        updateBulkActionsVisibility();
    });

    // Handle individual checkboxes
    $(document).on('change', '.return-checkbox', function() {
        updateBulkActionsVisibility();
    });

    // Update bulk actions container visibility
    function updateBulkActionsVisibility() {
        const checkedCount = $('.return-checkbox:checked').length;
        $('#bulkActionsContainer').toggle(checkedCount > 0);
        if (checkedCount === 0) {
            $('#selectAll').prop('checked', false);
        }
    }

    // Handle bulk delete
    $('#deleteSelected').click(function() {
        const selectedIds = $('.return-checkbox:checked').map(function() {
            return $(this).val();
        }).get();

        if (selectedIds.length === 0) {
            alert('Please select returns to delete');
            return;
        }

        if (confirm('Are you sure you want to delete the selected returns? This action cannot be undone.')) {
            $.ajax({
                url: 'return_actions.php',
                type: 'POST',
                data: {
                    action: 'bulk_delete',
                    return_ids: selectedIds
                },
                success: function(response) {
                    if (response.status === 'success') {
                        alert('Selected returns deleted successfully');
                        table.ajax.reload();
                        updateBulkActionsVisibility();
                    } else {
                        alert('Failed to delete returns: ' + response.message);
                    }
                },
                error: function() {
                    alert('Failed to delete returns');
                }
            });
        }
    });

    // Handle individual delete
    $(document).on('click', '.deleteBtn', function() {
        const returnId = $(this).data('id');
        if (confirm('Are you sure you want to delete this return? This action cannot be undone.')) {
            $.ajax({
                url: 'return_actions.php',
                type: 'POST',
                data: {
                    action: 'delete_return',
                    return_id: returnId
                },
                success: function(response) {
                    if (response.status === 'success') {
                        alert('Return deleted successfully');
                        table.ajax.reload();
                    } else {
                        alert('Failed to delete return: ' + response.message);
                    }
                },
                error: function() {
                    alert('Failed to delete return');
                }
            });
        }
    });
});
</script>

<style>
.select2-container {
    z-index: 9999;
}
.dt-buttons {
    margin-bottom: 15px;
}
.dt-button {
    background-color: #007bff !important;
    color: white !important;
    border: none !important;
    padding: 5px 10px !important;
    border-radius: 4px !important;
}
.dt-button:hover {
    background-color: #0056b3 !important;
}
</style> 