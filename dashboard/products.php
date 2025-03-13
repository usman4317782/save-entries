<?php include "pages/header.php"; ?>
<?php include "pages/spinner.php"; ?>
<?php include "pages/sidenav.php"; ?>
<!-- Content Start -->
<div class="content">
    <?php include "pages/topnav.php"; ?>
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Product Management</h2>
            <div>
                <button id="addProductBtn" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Add Product
                </button>
                <button id="bulkDeleteBtn" class="btn btn-danger ms-2">
                    <i class="bi bi-trash"></i> Bulk Delete
                </button>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <!-- Filters Section -->
                <div class="row g-3 mb-4">
                    <!-- Search -->
                    <div class="col-md-12">
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                            <input type="text" id="searchInput" class="form-control table-search" placeholder="Search products...">
                        </div>
                    </div>

                    <!-- Category Filter -->
                    <div class="col-md-3">
                        <label class="form-label">Category</label>
                        <select id="categoryFilter" class="form-select">
                            <option value="">All Categories</option>
                            <!-- Populate with categories from the database -->
                        </select>
                    </div>

                    <!-- Brand Filter -->
                    <div class="col-md-3">
                        <label class="form-label">Brand</label>
                        <select id="brandFilter" class="form-select">
                            <option value="">All Brands</option>
                            <!-- Populate with brands from the database -->
                        </select>
                    </div>

                    <!-- Stock Status Filter -->
                    <div class="col-md-3">
                        <label class="form-label">Stock Status</label>
                        <select id="stockStatusFilter" class="form-select">
                            <option value="">All Status</option>
                            <option value="Stock">In Stock</option>
                            <option value="Non Stock">Non Stock</option>
                        </select>
                    </div>

                    <!-- Date Range -->
                    <div class="col-md-3">
                        <label class="form-label">Date Range</label>
                        <input type="text" id="dateRange" class="form-control" placeholder="Select date range">
                    </div>

                    <!-- Price Range -->
                    <div class="col-md-3">
                        <label class="form-label">Min Price</label>
                        <input type="number" id="minPrice" class="form-control" placeholder="Min price">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Max Price</label>
                        <input type="number" id="maxPrice" class="form-control" placeholder="Max price">
                    </div>

                    <!-- Cost Range -->
                    <div class="col-md-3">
                        <label class="form-label">Min Cost</label>
                        <input type="number" id="minCost" class="form-control" placeholder="Min cost">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Max Cost</label>
                        <input type="number" id="maxCost" class="form-control" placeholder="Max cost">
                    </div>

                    <!-- Clear Filters -->
                    <div class="col-md-6 d-flex align-items-end">
                        <button id="clearFilters" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Clear Filters
                        </button>
                    </div>
                </div>

                <!-- Table Section -->
                <div class="table-responsive">
                    <table id="productsTable" class="table table-striped table-hover">
                        <thead class="table-light">
                            <tr>
                                <th><input type="checkbox" id="selectAll"></th>
                                <th>Sr. No.</th>
                                <th>ID</th>
                                <th>SKU</th>
                                <th>Unique ID</th>
                                <th>Product Name</th>
                                <th>Description</th>
                                <th>Category</th>
                                <th>Brand</th>
                                <th>Price</th>
                                <th>Cost</th>
                                <th>Stock Quantity</th>
                                <th>Stock Status</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Product Modal -->
    <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productModalLabel">Add/Edit Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="productForm">
                        <input type="hidden" name="id" id="productId">
                        <div class="mb-3">
                            <label class="form-label">Product Name</label>
                            <input type="text" class="form-control" name="product_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">SKU</label>
                            <input type="text" class="form-control" name="sku">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Unique ID</label>
                            <input type="text" class="form-control" name="unique_id">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Stock Status</label>
                            <select class="form-control" name="stock_status">
                                <option value="Stock">Stock</option>
                                <option value="Non Stock">Non Stock</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <div class="input-group">
                                <select class="form-control" name="category_id">
                                    <option value="">Select Category</option>
                                </select>
                                <button type="button" class="btn btn-success" id="addNewCategory">
                                    <i class="bi bi-plus-circle"></i> New
                                </button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Brand</label>
                            <div class="input-group">
                                <select class="form-control" name="brand_id">
                                    <option value="">Select Brand</option>
                                </select>
                                <button type="button" class="btn btn-success" id="addNewBrand">
                                    <i class="bi bi-plus-circle"></i> New
                                </button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Price</label>
                            <input type="number" class="form-control" name="price" step="0.01" min="0">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Cost</label>
                            <input type="number" class="form-control" name="cost" step="0.01" min="0">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Stock Quantity</label>
                            <input type="number" class="form-control" name="stock_quantity" min="0">
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

    <!-- Add Category Modal -->
    <div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="categoryModalLabel">Add New Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="categoryForm">
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
                            <input type="text" class="form-control" name="type">
                        </div>
                        <button type="submit" class="btn btn-success">Save Category</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Brand Modal -->
    <div class="modal fade" id="brandModal" tabindex="-1" aria-labelledby="brandModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="brandModalLabel">Add New Brand</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="brandForm">
                        <div class="mb-3">
                            <label class="form-label">Brand Name</label>
                            <input type="text" class="form-control" name="brand_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description"></textarea>
                        </div>
                        <button type="submit" class="btn btn-success">Save Brand</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php include "pages/footer.php"; ?>
</div>

<style>
    /* Responsive styles */
    @media (max-width: 768px) {
        .dataTables_wrapper {
            overflow-x: auto;
        }
        
        #productsTable {
            min-width: 1000px;
        }

        .btn {
            padding: 0.375rem 0.75rem;
        }

        .btn i {
            margin-right: 0.25rem;
        }
    }

    /* Table styling */
    .table {
        margin-bottom: 0;
    }

    .table th {
        font-weight: 600;
        white-space: nowrap;
        background-color: #f8f9fa;
    }

    .table td {
        vertical-align: middle;
    }

    /* Card styling */
    .card {
        border: none;
        border-radius: 0.5rem;
    }

    .card-body {
        padding: 1.5rem;
    }

    /* Form controls */
    .form-control, .form-select {
        border-color: #dee2e6;
    }

    .form-control:focus, .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    .input-group-text {
        border-color: #dee2e6;
    }

    /* Button styling */
    .btn {
        font-weight: 500;
    }

    .btn i {
        font-size: 0.875rem;
    }

    /* DataTables custom styling */
    .dataTables_wrapper .dataTables_length select {
        min-width: 4rem;
    }

    .dataTables_wrapper .dataTables_filter input {
        min-width: 15rem;
    }

    .dataTables_wrapper .dataTables_info {
        padding-top: 1rem;
    }

    .dataTables_wrapper .dataTables_paginate {
        padding-top: 1rem;
    }
</style>

<script>
    $(document).ready(function() {
        // Populate Category Filter
        function fetchCategories() {
            $.get('product_actions.php?action=fetch_categories', {}, function(result) {
                try {
                    if (Array.isArray(result.data)) {
                        let options = '<option value="">All Categories</option>';
                        let modalOptions = '<option value="">Select Category</option>';
                        result.data.forEach(category => {
                            options += `<option value="${category.id}">${category.category_name}</option>`;
                            modalOptions += `<option value="${category.id}">${category.category_name}</option>`;
                        });
                        $('#categoryFilter').html(options);
                        $('select[name="category_id"]').html(modalOptions);
                    } else {
                        console.error('Expected an array of categories, but got:', result.data);
                    }
                } catch (e) {
                    console.error('Error handling categories:', e);
                }
            }, 'json').fail(function(jqXHR, textStatus, errorThrown) {
                console.error('Error fetching categories:', textStatus, errorThrown);
            });
        }

        // Populate Brand Filter
        function fetchBrands() {
            $.get('product_actions.php?action=fetch_brands', {}, function(result) {
                try {
                    if (Array.isArray(result.data)) {
                        let options = '<option value="">All Brands</option>';
                        let modalOptions = '<option value="">Select Brand</option>';
                        result.data.forEach(brand => {
                            options += `<option value="${brand.id}">${brand.brand_name}</option>`;
                            modalOptions += `<option value="${brand.id}">${brand.brand_name}</option>`;
                        });
                        $('#brandFilter').html(options);
                        $('select[name="brand_id"]').html(modalOptions);
                    } else {
                        console.error('Expected an array of brands, but got:', result.data);
                    }
                } catch (e) {
                    console.error('Error handling brands:', e);
                }
            }, 'json').fail(function(jqXHR, textStatus, errorThrown) {
                console.error('Error fetching brands:', textStatus, errorThrown);
            });
        }

        // Fetch categories and brands on page load
        fetchCategories();
        fetchBrands();

        // Initialize DataTable
        var table = $('#productsTable').DataTable({
            "ajax": {
                "url": "product_actions.php?action=fetch",
                "dataSrc": "data",
                "data": function(d) {
                    return {
                        search: $('#searchInput').val(),
                        category_id: $('#categoryFilter').val(),
                        brand_id: $('#brandFilter').val(),
                        stock_status: $('#stockStatusFilter').val(),
                        minPrice: $('#minPrice').val(),
                        maxPrice: $('#maxPrice').val(),
                        minCost: $('#minCost').val(),
                        maxCost: $('#maxCost').val(),
                        startDate: $('#dateRange').val().split(' - ')[0],
                        endDate: $('#dateRange').val().split(' - ')[1]
                    };
                },
                "error": function(xhr, error, thrown) {
                    console.error("DataTable AJAX error:", error, thrown);
                }
            },
            "pageLength": 10,
            "lengthMenu": [10, 50, 100, -1],
            "columns": [{
                    "data": "id",
                    render: function(data) {
                        return '<input type="checkbox" class="selectProduct" value="' + data + '">';
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return meta.row + 1; // Add 1 because DataTables uses zero-based indexing
                    }
                },
                {
                    "data": "id"
                },
                {
                    "data": "sku"
                },
                {
                    "data": "unique_id"
                },
                {
                    "data": "product_name"
                },
                {
                    "data": "description"
                },
                {
                    "data": "category_id",
                    render: function(data, type, row) {
                        return row.category_name;
                    }
                },
                {
                    "data": "brand_id",
                    render: function(data, type, row) {
                        return row.brand_name;
                    }
                },
                {
                    "data": "price",
                    render: function(data) {
                        return data ? parseFloat(data).toFixed(2) : '';
                    }
                },
                {
                    "data": "cost",
                    render: function(data) {
                        return data ? parseFloat(data).toFixed(2) : '';
                    }
                },
                {
                    "data": "stock_quantity",
                    render: function(data) {
                        return data !== null ? data : '';
                    }
                },
                {
                    "data": "stock_status"
                },
                {
                    "data": "created_at"
                },
                {
                    "data": "updated_at"
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

        // Show modal when Add Product button is clicked
        $('#addProductBtn').click(function() {
            $('#productModalLabel').text('Add Product');
            $('#productForm')[0].reset();
            $('#productId').val('');
            $('#productModal').modal('show');

            // Fetch categories and brands again to ensure the dropdowns are up-to-date
            fetchCategories();
            fetchBrands();
        });

        // Edit Product
        $(document).on('click', '.editBtn', function() {
            let id = $(this).data('id');
            $.get('product_actions.php?action=fetch_single&id=' + id, function(product) {
                $('#productId').val(product.id);
                $('input[name="product_name"]').val(product.product_name);
                $('input[name="sku"]').val(product.sku);
                $('input[name="unique_id"]').val(product.unique_id);
                $('select[name="stock_status"]').val(product.stock_status);
                $('textarea[name="description"]').val(product.description);
                $('select[name="category_id"]').val(product.category_id);
                $('select[name="brand_id"]').val(product.brand_id);
                $('input[name="price"]').val(product.price);
                $('input[name="cost"]').val(product.cost);
                $('input[name="stock_quantity"]').val(product.stock_quantity);
                $('#productModalLabel').text('Edit Product');
                $('#productModal').modal('show');
            }, 'json');
        });

        // Submit Add/Edit Product Form
        $('#productForm').submit(function(e) {
            e.preventDefault();
            let action = $('#productId').val() ? 'update' : 'add';
            $.post('product_actions.php?action=' + action, $(this).serialize(), function(response) {
                if (response.success) {
                    $('#productModal').modal('hide');
                    table.ajax.reload();
                } else {
                    alert(response.message);
                }
            }, 'json');
        });

        // Bulk Delete Products
        $('#bulkDeleteBtn').click(function() {
            var ids = [];
            $('.selectProduct:checked').each(function() {
                ids.push($(this).val());
            });
            if (ids.length === 0) {
                alert("Please select products to delete.");
                return;
            }
            if (confirm("Are you sure you want to delete selected products?")) {
                $.post("product_actions.php?action=bulk_delete", {
                    ids: ids
                }, function(response) {
                    alert(response.message);
                    table.ajax.reload();
                }, "json");
            }
        });

        // Delete Single Product
        $(document).on('click', '.deleteBtn', function() {
            let id = $(this).data('id');
            if (confirm('Are you sure you want to delete this product?')) {
                $.post('product_actions.php?action=bulk_delete', {
                    ids: [id]
                }, function(response) {
                    alert(response.message);
                    table.ajax.reload();
                }, 'json');
            }
        });

        // Select/Deselect All Checkboxes
        $('#selectAll').on('change', function() {
            $('.selectProduct').prop('checked', this.checked);
        });

        // Dynamic Filters
        let filterTimeout;
        $('#searchInput, #categoryFilter, #brandFilter, #stockStatusFilter, #minPrice, #maxPrice, #minCost, #maxCost, #dateRange').on('input change', function() {
            clearTimeout(filterTimeout);
            filterTimeout = setTimeout(function() {
                table.ajax.reload();
            }, 300); // Wait 300ms after last input
        });

        // Clear Filters
        $('#clearFilters').click(function() {
            $('#searchInput').val('');
            $('#categoryFilter').val('').trigger('change'); // Trigger Select2 change
            $('#brandFilter').val('').trigger('change'); // Trigger Select2 change
            $('#stockStatusFilter').val('').trigger('change'); // Trigger Select2 change
            $('#minPrice').val('');
            $('#maxPrice').val('');
            $('#minCost').val('');
            $('#maxCost').val('');
            $('#dateRange').val('');
            table.ajax.reload();
        });

        // Initialize Datepicker
        flatpickr('#dateRange', {
            mode: 'range',
            dateFormat: 'Y-m-d'
        });

        // Add New Category
        $('#addNewCategory').click(function() {
            $('#categoryModal').modal('show');
        });

        $('#categoryForm').submit(function(e) {
            e.preventDefault();
            $.post('category_actions.php?action=add', $(this).serialize(), function(response) {
                if (response.success) {
                    $('#categoryModal').modal('hide');
                    fetchCategories();
                }
                alert(response.message);
            }, 'json');
        });

        // Add New Brand
        $('#addNewBrand').click(function() {
            $('#brandModal').modal('show');
        });

        $('#brandForm').submit(function(e) {
            e.preventDefault();
            $.post('brand_actions.php?action=add', $(this).serialize(), function(response) {
                if (response.success) {
                    $('#brandModal').modal('hide');
                    fetchBrands();
                }
                alert(response.message);
            }, 'json');
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('.search-dropdown').select2();
    });
</script>