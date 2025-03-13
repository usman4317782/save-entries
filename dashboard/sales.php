<?php include "pages/header.php"; ?>
<?php include "pages/spinner.php"; ?>
<?php include "pages/sidenav.php"; ?>

<!-- Content Start -->
<div class="content">
    <?php include "pages/topnav.php"; ?>
    <div class="container-fluid pt-4 px-4">
        <div class="bg-light rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h5 class="mb-0">Sales Management</h5>
                <div>
                    <button id="bulkDeleteBtn" class="btn btn-danger me-2" style="display: none;">
                        <i class="bi bi-trash me-2"></i>Delete Selected
                    </button>
                    <button id="exportCsvBtn" class="btn btn-success me-2">
                        <i class="bi bi-file-earmark-excel me-2"></i>Export CSV
                    </button>
                    <button id="printSalesBtn" class="btn btn-secondary me-2">
                        <i class="bi bi-printer me-2"></i>Print
                    </button>
                    <button id="addSaleBtn" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>New Sale
                    </button>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search Invoice...">
                </div>
                <div class="col-md-3">
                    <select id="customerFilter" class="form-control">
                        <option value="">All Customers</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="text" id="startDate" class="form-control flatpickr" placeholder="Start Date">
                </div>
                <div class="col-md-2">
                    <input type="text" id="endDate" class="form-control flatpickr" placeholder="End Date">
                </div>
                <div class="col-md-2">
                    <button id="clearFilters" class="btn btn-secondary w-100">Clear Filters</button>
                </div>
            </div>

            <div class="table-responsive">
                <table id="salesTable" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" id="selectAll" class="form-check-input">
                            </th>
                            <th>Invoice #</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Total Amount</th>
                            <th>Remaining Balance</th>
                            <th>Payment Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Sale Modal -->
    <div class="modal fade" id="saleModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Sale</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="saleForm">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Customer</label>
                                <select name="customer_id" class="form-control" required>
                                    <option value="">Select Customer</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Sale Date</label>
                                <input type="text" name="sale_date" class="form-control flatpickr" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <h6>Products</h6>
                            <div id="productsList">
                                <div class="row product-row mb-2 align-items-center">
                                    <div class="col-md-4">
                                        <select class="form-control product-select" name="product_id[]" required>
                                            <option value="">Select Product</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" class="form-control quantity" name="quantity[]" placeholder="Qty" required>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="input-group">
                                            <input type="number" class="form-control price" name="price[]" placeholder="Price" step="0.01" required>
                                            <button type="button" class="btn btn-outline-secondary reset-price" title="Reset to original price">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" class="form-control discount" name="discount[]" placeholder="Discount" value="0" min="0">
                                    </div>
                                    <div class="col-md-2">
                                        <div class="d-flex align-items-center">
                                            <input type="number" class="form-control total" name="total[]" placeholder="Total" readonly>
                                            <a href="javascript:void(0)" class="text-danger deleteRowBtn ms-2">
                                                <i class="fas fa-times-circle fa-lg"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" id="addProductRow" class="btn btn-info btn-sm mt-2">
                                <i class="bi bi-plus"></i> Add Product
                            </button>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Sub Total</label>
                                <input type="number" class="form-control" name="total_amount" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Tax (%)</label>
                                <input type="number" class="form-control" name="tax" value="0" min="0" max="100" step="0.01">
                                <div id="taxAmount" class="text-muted small mt-1"></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Final Amount</label>
                                <input type="number" class="form-control bg-light fw-bold" name="final_amount" readonly>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Payment Method</label>
                                <select class="form-control" name="payment_method" required>
                                    <option value="cash">Cash</option>
                                    <option value="card">Card</option>
                                    <option value="bank_transfer">Bank Transfer</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Payment Amount</label>
                                <input type="number" class="form-control" name="payment_amount">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Notes</label>
                            <textarea class="form-control" name="notes"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Save Sale
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include "pages/footer.php"; ?>
</div>

<style>
@media print {
    .navbar,
    .sidebar,
    .btn,
    footer,
    .modal,
    .form-control,
    .select2,
    #clearFilters {
        display: none !important;
    }
    
    .content {
        margin: 0 !important;
        padding: 0 !important;
    }
    
    .container-fluid {
        padding: 0 !important;
    }
    
    .bg-light {
        background-color: white !important;
    }

    .table {
        width: 100% !important;
    }

    .table td, .table th {
        padding: 0.5rem !important;
    }
}

/* Print-specific styles */
.print-header {
    display: none;
}

@media print {
    .print-header {
        display: block;
        text-align: center;
        margin-bottom: 20px;
    }
}
</style>

<script>
$(document).ready(function() {
    // Debug function
    function handleAjaxError(xhr, status, error) {
        console.error('AJAX Error:', {
            status: status,
            error: error,
            response: xhr.responseText
        });
        
        // Try to parse the response as JSON
        let errorMessage = 'An error occurred while processing your request.';
        try {
            const jsonResponse = JSON.parse(xhr.responseText);
            if (jsonResponse && jsonResponse.message) {
                errorMessage = jsonResponse.message;
            }
        } catch (e) {
            // If parsing fails, use a generic error message
            console.error('Failed to parse error response:', e);
        }
        
        // Show error message to the user
        showAlert('error', errorMessage);
    }

    // Initialize only customerFilter with Select2
    $('#customerFilter').select2({
        width: '100%',
        placeholder: 'All Customers',
        allowClear: true
    });

    // Load initial data for dropdowns
    function loadInitialData() {
        // Load customers
        $.ajax({
            url: 'sale_actions.php',
            type: 'GET',
            data: { action: 'get_customers' },
            success: function(response) {
                if (response.status === 'success' && response.data) {
                    let customerSelect = $('select[name="customer_id"]');
                    customerSelect.empty().append('<option value="">Select Customer</option>');
                    response.data.forEach(function(customer) {
                        customerSelect.append(
                            `<option value="${customer.customer_id}">
                                ${customer.name} ${customer.contact_number ? ' - ' + customer.contact_number : ''}
                            </option>`
                        );
                    });

                    // Also update the customerFilter
                    let customerFilter = $('#customerFilter');
                    customerFilter.empty().append('<option value="">All Customers</option>');
                    response.data.forEach(function(customer) {
                        customerFilter.append(
                            `<option value="${customer.customer_id}">
                                ${customer.name} ${customer.contact_number ? ' - ' + customer.contact_number : ''}
                            </option>`
                        );
                    });
                    customerFilter.trigger('change'); // Refresh Select2
                }
            }
        });

        // Load products
        $.ajax({
            url: 'sale_actions.php',
            type: 'GET',
            data: { action: 'get_products' },
            success: function(response) {
                if (response.status === 'success' && response.data) {
                    let productSelects = $('.product-select');
                    productSelects.empty().append('<option value="">Select Product</option>');
                    response.data.forEach(function(product) {
                        let description = product.description ? ` (${product.description})` : '';
                        let brandInfo = product.brand_name ? ` - ${product.brand_name}` : '';
                        let stockInfo = ` [${product.stock_quantity} in stock]`;
                        
                        productSelects.append(
                            `<option value="${product.id}" 
                                     data-price="${product.price}"
                                     data-stock="${product.stock_quantity}">
                                ${product.product_name}${brandInfo}${description}${stockInfo}
                            </option>`
                        );
                    });
                }
            }
        });
    }

    // Handle product selection change
    $(document).on('change', '.product-select', function() {
        let row = $(this).closest('.product-row');
        let selectedOption = $(this).find(':selected');
        let price = selectedOption.data('price') || 0;
        let stock = selectedOption.data('stock') || 0;
        
        row.find('.price').val(price);
        row.find('.quantity')
            .attr('max', stock)
            .attr('placeholder', `Max: ${stock}`);
        calculateRowTotal(row);
    });

    // Add Product Row
    $('#addProductRow').click(function() {
        let newRow = $('#productsList .product-row').first().clone();
        newRow.find('input').val('');
        let newSelect = newRow.find('select');
        newSelect.val('');
        
        // Copy options from first select
        let firstSelect = $('.product-select').first();
        newSelect.html(firstSelect.html());
        
        // Add delete functionality
        newRow.find('.deleteRowBtn').click(function() {
            newRow.remove();
            calculateTotals();
        });
        
        $('#productsList').append(newRow);
    });

    // Show modal and load data
    $('#addSaleBtn').click(function() {
        // Reset the form first
        $('#saleForm')[0].reset();
        // Clear all product rows except the first one
        let firstRow = $('#productsList .product-row').first();
        $('#productsList .product-row').not(':first').remove();
        firstRow.find('input').val('');
        firstRow.find('select').val('');
        // Reset totals
        $('input[name="total_amount"]').val('');
        $('input[name="final_amount"]').val('');
        // Load fresh data
        loadInitialData();
        // Show modal
        $('#saleModal').modal('show');
    });

    // Helper function to get items data
    function getItemsData() {
        let items = [];
        $('.product-row').each(function() {
            let productId = $(this).find('.product-select').val();
            if (productId) {
                items.push({
                    product_id: productId,
                    quantity: $(this).find('.quantity').val(),
                    unit_price: $(this).find('.price').val(),
                    discount: $(this).find('.discount').val() || 0,
                    total_price: $(this).find('.total').val()
                });
            }
        });
        return items;
    }

    // Handle form submission
    $('#saleForm').on('submit', function(e) {
        e.preventDefault();
        
        let formData = new FormData(this);
        formData.append('action', 'create_sale');
        formData.append('items', JSON.stringify(getItemsData()));
        
        $.ajax({
            url: 'sale_actions.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status === 'success') {
                    // Close the modal
                    $('#saleModal').modal('hide');
                    
                    // Reset the form
                    $('#saleForm')[0].reset();
                    
                    // Refresh the table data
                    salesTable.ajax.reload();
                    
                    // Show success message
                    showAlert('success', 'Sale saved successfully');
                } else {
                    showAlert('error', response.message || 'Failed to save sale');
                }
            },
            error: handleAjaxError
        });
    });

    // Initialize Flatpickr
    $('.flatpickr').flatpickr({
        dateFormat: 'Y-m-d'
    });

    // Bulk delete functionality
    let selectedSales = new Set();
    
    // Handle select all checkbox
    $('#selectAll').change(function() {
        let isChecked = $(this).prop('checked');
        $('.sale-checkbox').prop('checked', isChecked);
        if (isChecked) {
            $('.sale-checkbox').each(function() {
                selectedSales.add($(this).val());
            });
        } else {
            selectedSales.clear();
        }
        updateBulkDeleteButton();
    });
    
    // Handle individual checkboxes
    $(document).on('change', '.sale-checkbox', function() {
        let saleId = $(this).val();
        if ($(this).prop('checked')) {
            selectedSales.add(saleId);
        } else {
            selectedSales.delete(saleId);
            $('#selectAll').prop('checked', false);
        }
        updateBulkDeleteButton();
    });
    
    function updateBulkDeleteButton() {
        $('#bulkDeleteBtn').toggle(selectedSales.size > 0);
    }
    
    // Bulk delete action
    $('#bulkDeleteBtn').click(function() {
        if (selectedSales.size === 0) return;
        
        if (confirm('Are you sure you want to delete the selected sales? This action cannot be undone.')) {
            $.ajax({
                url: 'sale_actions.php',
                type: 'POST',
                data: {
                    action: 'bulk_delete',
                    sale_ids: Array.from(selectedSales)
                },
                success: function(response) {
                    if (response.status === 'success') {
                        // Clear selections first
                        selectedSales.clear();
                        $('#selectAll').prop('checked', false);
                        updateBulkDeleteButton();
                        
                        // Reload the DataTable
                        $('#salesTable').DataTable().ajax.reload(null, false);
                        
                        // Show success message after a short delay to ensure table is refreshed
                        setTimeout(function() {
                            showAlert('success', 'Selected sales have been deleted successfully.');
                        }, 100);
                    } else {
                        showAlert('error', response.message || 'Failed to delete selected sales.');
                    }
                },
                error: handleAjaxError
            });
        }
    });

    // Initialize DataTable
    let salesTable = $('#salesTable').DataTable({
        serverSide: true,
        processing: true,
        ajax: {
            url: 'sale_actions.php?action=fetch',
            type: 'GET',
            data: function(d) {
                return {
                    ...d,
                    search: $('#searchInput').val(),
                    customer_id: $('#customerFilter').val(),
                    start_date: $('#startDate').val(),
                    end_date: $('#endDate').val()
                };
            }
        },
        columns: [
            {
                data: 'id',
                orderable: false,
                render: function(data) {
                    return `<input type="checkbox" class="form-check-input sale-checkbox" value="${data}">`;
                }
            },
            { data: 'invoice_number' },
            { data: 'customer_name' },
            { 
                data: 'sale_date',
                render: function(data) {
                    return moment(data).format('DD-MM-YYYY');
                }
            },
            { 
                data: 'final_amount',
                render: function(data) {
                    return parseFloat(data).toFixed(2);
                }
            },
            { 
                data: 'remaining_balance',
                render: function(data) {
                    if (parseFloat(data) <= 0) {
                        return '<span class="text-success">0.00</span>';
                    } else {
                        return '<span class="text-danger">' + parseFloat(data).toFixed(2) + '</span>';
                    }
                }
            },
            { 
                data: 'payment_status',
                render: function(data) {
                    let badges = {
                        'paid': 'success',
                        'partially_paid': 'warning',
                        'pending': 'danger'
                    };
                    return `<span class="badge bg-${badges[data]}">${data.replace('_', ' ').toUpperCase()}</span>`;
                }
            },
            {
                data: null,
                render: function(data) {
                    return `
                        <div class="btn-group">
                            <button class="btn btn-sm btn-info view-invoice" data-id="${data.id || data.sale_id}">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-primary edit-invoice" data-id="${data.id || data.sale_id}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-danger delete-invoice" data-id="${data.id || data.sale_id}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    `;
                }
            }
        ],
        order: [[3, 'desc']]
    });

    // Filter handling
    let filterTimeout;
    function applyFilters() {
        clearTimeout(filterTimeout);
        filterTimeout = setTimeout(() => {
            salesTable.ajax.reload();
        }, 500);
    }

    $('#searchInput, #customerFilter, #startDate, #endDate').on('change', applyFilters);
    $('#searchInput').on('keyup', applyFilters);

    // Clear filters
    $('#clearFilters').click(function() {
        $('#searchInput').val('');
        $('#customerFilter').val('').trigger('change');
        $('#startDate, #endDate').val('');
        applyFilters();
    });

    // Function to calculate totals
    function calculateTotals() {
        let subTotal = 0;
        $('.product-row').each(function() {
            let total = parseFloat($(this).find('.total').val()) || 0;
            subTotal += total;
        });
        
        let tax = parseFloat($('input[name="tax"]').val()) || 0;
        let taxAmount = (subTotal * tax) / 100;
        let finalAmount = subTotal + taxAmount;
        
        // Update all relevant fields
        $('input[name="total_amount"]').val(subTotal.toFixed(2));
        $('input[name="final_amount"]').val(finalAmount.toFixed(2));
        
        // Display tax amount for better visibility
        if ($('#taxAmount').length === 0) {
            $('input[name="tax"]').parent().append('<div id="taxAmount" class="text-muted small mt-1"></div>');
        }
        $('#taxAmount').text(`Tax Amount: ${taxAmount.toFixed(2)}`);
    }

    // Ensure tax is calculated on form load
    $('#saleModal').on('shown.bs.modal', function() {
        calculateTotals();
    });

    // Handle tax input change with validation
    $('input[name="tax"]').on('input', function() {
        let tax = parseFloat($(this).val()) || 0;
        // Ensure tax is not negative and not too high
        if (tax < 0) {
            $(this).val(0);
            tax = 0;
        } else if (tax > 100) {
            $(this).val(100);
            tax = 100;
        }
        calculateTotals();
    });

    // Handle product row changes
    $(document).on('input', '.quantity, .price, .discount', function() {
        let row = $(this).closest('.product-row');
        let quantity = parseFloat(row.find('.quantity').val()) || 0;
        let price = parseFloat(row.find('.price').val()) || 0;
        let discount = parseFloat(row.find('.discount').val()) || 0;
        
        let total = (quantity * price) - discount;
        row.find('.total').val(total.toFixed(2));
        
        calculateTotals();
    });

    // Calculate Row Total
    function calculateRowTotal(row) {
        let price = parseFloat(row.find('.price').val()) || 0;
        let quantity = parseFloat(row.find('.quantity').val()) || 0;
        let discount = parseFloat(row.find('.discount').val()) || 0;
        let total = (price * quantity) - discount;
        row.find('.total').val(total.toFixed(2));
        calculateTotals();
    }

    // View Invoice
    $(document).on('click', '.view-invoice', function() {
        let saleId = $(this).data('id');
        window.location.href = `view_invoice.php?id=${saleId}`;
    });

    // Edit Invoice
    $(document).on('click', '.edit-invoice', function() {
        let saleId = $(this).data('id');
        window.location.href = `edit_invoice.php?id=${saleId}`;
    });

    // Delete Invoice
    $(document).on('click', '.delete-invoice', function() {
        let saleId = $(this).data('id');
        if(confirm('Are you sure you want to delete this invoice? This action cannot be undone.')) {
            $.ajax({
                url: 'sale_actions.php',
                type: 'POST',
                data: {
                    action: 'delete_sale',
                    sale_id: saleId
                },
                success: function(response) {
                    if(response.status === 'success') {
                        salesTable.ajax.reload();
                        alert('Invoice deleted successfully!');
                    } else {
                        alert('Error: ' + response.message || 'Failed to delete invoice');
                    }
                },
                error: function() {
                    alert('Failed to delete invoice');
                }
            });
        }
    });

    // Print functionality
    $('#printSalesBtn').click(function() {
        // Add print header
        let printHeader = $('<div class="print-header">')
            .append('<h2>Sales Report</h2>')
            .append('<p>Generated: ' + moment().format('DD-MM-YYYY HH:mm:ss') + '</p>');
        
        // Add filters info if any are active
        let activeFilters = [];
        if ($('#searchInput').val()) activeFilters.push('Search: ' + $('#searchInput').val());
        if ($('#customerFilter').val()) activeFilters.push('Customer: ' + $('#customerFilter option:selected').text());
        if ($('#startDate').val()) activeFilters.push('From: ' + $('#startDate').val());
        if ($('#endDate').val()) activeFilters.push('To: ' + $('#endDate').val());
        
        if (activeFilters.length > 0) {
            printHeader.append('<p>Filters: ' + activeFilters.join(' | ') + '</p>');
        }
        
        // Insert header before table
        printHeader.insertBefore('#salesTable');
        
        // Print
        window.print();
        
        // Remove print header after printing
        $('.print-header').remove();
    });

    // Export CSV functionality
    $('#exportCsvBtn').click(function() {
        // Get current filter values
        const filters = {
            search: $('#searchInput').val(),
            customer_id: $('#customerFilter').val(),
            start_date: $('#startDate').val(),
            end_date: $('#endDate').val()
        };

        // Create form and submit it
        const form = $('<form>')
            .attr('method', 'POST')
            .attr('action', 'sale_actions.php')
            .append($('<input>')
                .attr('type', 'hidden')
                .attr('name', 'action')
                .attr('value', 'export_csv'))
            .append($('<input>')
                .attr('type', 'hidden')
                .attr('name', 'filters')
                .attr('value', JSON.stringify(filters)));

        $('body').append(form);
        form.submit();
        form.remove();
    });

    // Initial Load
    loadInitialData();
});
</script>
