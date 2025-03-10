<?php include "pages/header.php"; ?>
<?php include "pages/spinner.php"; ?>
<?php include "pages/sidenav.php"; ?>
<!-- Content Start -->
<div class="content">
    <?php include "pages/topnav.php"; ?>

    <div class="container mt-4">
        <h2 class="mb-3">Category Management</h2>
        <div class="d-flex justify-content-between mb-3">
            <button id="addCategoryBtn" class="btn btn-primary btn-icon">
                <i class="bi bi-plus-circle"></i>
                <span class="btn-text">Add Category</span>
            </button>
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
                <select id="typeFilter" class="search-dropdown form-control">
                    <option value="">All Types</option>
                    <option value="stock">Stock</option>
                    <option value="non-stock">Non-Stock</option>
                </select>
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

        <div class="table-responsive">
            <table id="categoriesTable" class="table table-striped">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Type</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- Add/Edit Category Modal -->
    <div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="categoryModalLabel">Add/Edit Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="categoryForm">
                        <input type="hidden" name="id" id="categoryId">
                        <div class="mb-3">
                            <label class="form-label">Category Name</label>
                            <input type="text" class="form-control" name="category_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Type</label>
                            <select class="form-control" name="type">
                                <option value="stock">Stock</option>
                                <option value="non-stock">Non-Stock</option>
                            </select>
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
        var table = $('#categoriesTable').DataTable({
            "ajax": "category_actions.php?action=fetch",
            "pageLength": 10,
            "lengthMenu": [10, 50, 100, -1],
            "columns": [{
                    "data": "id",
                    render: function(data) {
                        return '<input type="checkbox" class="selectCategory" value="' + data + '">';
                    }
                },
                {
                    "data": "id"
                },
                {
                    "data": "category_name"
                },
                {
                    "data": "description"
                },
                {
                    "data": "type"
                },
                {
                    "data": "created_at"
                },
                {
                    "data": "id",
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

        // Show modal when Add Category button is clicked
        $('#addCategoryBtn').click(function() {
            $('#categoryModalLabel').text('Add Category');
            $('#categoryForm')[0].reset();
            $('#categoryId').val('');
            $('#categoryModal').modal('show');
        });

        // Edit Category
        $(document).on('click', '.editBtn', function() {
            let id = $(this).data('id');
            $.get('category_actions.php?action=fetch_single&id=' + id, function(response) {
                let category = JSON.parse(response);
                $('#categoryId').val(category.id);
                $('input[name="category_name"]').val(category.category_name);
                $('textarea[name="description"]').val(category.description);
                $('select[name="type"]').val(category.type);
                $('#categoryModalLabel').text('Edit Category');
                $('#categoryModal').modal('show');
            });
        });

        // Submit Add/Edit Category Form
        $('#categoryForm').submit(function(e) {
            e.preventDefault();
            let action = $('#categoryId').val() ? 'update' : 'add';
            $.post('category_actions.php?action=' + action, $(this).serialize(), function(response) {
                response = JSON.parse(response);
                if (response.success) {
                    $('#categoryModal').modal('hide');
                    table.ajax.reload();
                } else {
                    alert(response.message);
                }
            });
        });

        // Bulk Delete Categories
        $('#bulkDeleteBtn').click(function() {
            var ids = [];
            $('.selectCategory:checked').each(function() {
                ids.push($(this).val());
            });
            if (ids.length === 0) {
                alert("Please select categories to delete.");
                return;
            }
            if (confirm("Are you sure you want to delete selected categories?")) {
                $.post("category_actions.php?action=bulk_delete", {
                    ids: ids
                }, function(response) {
                    alert(response.message);
                    table.ajax.reload();
                }, "json");
            }
        });

        // Delete Single Category
        $(document).on('click', '.deleteBtn', function() {
            let id = $(this).data('id');
            if (confirm('Are you sure you want to delete this category?')) {
                $.post('category_actions.php?action=bulk_delete', {
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
            $('.selectCategory').prop('checked', this.checked);
        });

        // Dynamic Filters
        $('#searchInput, #typeFilter, #startDate, #endDate').on('input change', function() {
            var search = $('#searchInput').val();
            var type = $('#typeFilter').val();
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();
            table.ajax.url("category_actions.php?action=fetch&search=" + search + "&type=" + type + "&startDate=" + startDate + "&endDate=" + endDate).load();
        });

        // Clear Filters
        $('#clearFilters').click(function() {
            $('#searchInput').val('');
            $('#typeFilter').val('');
            $('#startDate').val('');
            $('#endDate').val('');
            table.ajax.url("category_actions.php?action=fetch").load();
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
<script>
    $(document).ready(function() {
        $('.search-dropdown').select2();
    });
</script>