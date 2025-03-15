<?php include "pages/header.php"; ?>
<?php include "pages/spinner.php"; ?>
<?php include "pages/sidenav.php"; ?>

<!-- Content Start -->
<div class="content">
    <?php include "pages/topnav.php"; ?>
    <div class="container-fluid pt-4 px-4">
        <div class="bg-light rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h5 class="mb-0">Quotations Management</h5>
                <div>
                    <button id="bulkDeleteBtn" class="btn btn-danger me-2" style="display: none;">
                        <i class="bi bi-trash me-2"></i>Delete Selected
                    </button>
                    <button id="exportCsvBtn" class="btn btn-success me-2">
                        <i class="bi bi-file-earmark-excel me-2"></i>Export CSV
                    </button>
                    <button id="printQuotationsBtn" class="btn btn-secondary me-2">
                        <i class="bi bi-printer me-2"></i>Print
                    </button>
                    <button id="addQuotationBtn" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>New Quotation
                    </button>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search Quotation...">
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
                <table id="quotationsTable" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" id="selectAll" class="form-check-input">
                            </th>
                            <th>Sr. No.</th>
                            <th>Quotation #</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Validity</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Quotation Modal -->
    <div class="modal fade" id="quotationModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Quotation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="quotationForm">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Customer</label>
                                <select name="customer_id" class="form-control" required>
                                    <option value="">Select Customer</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Quotation Date</label>
                                <input type="text" name="quotation_date" class="form-control flatpickr" required>
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
                                <label class="form-label">Status</label>
                                <select class="form-control" name="status">
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Validity Period (Days)</label>
                                <input type="number" class="form-control" name="validity_period" value="30" min="1">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Notes</label>
                            <textarea class="form-control" name="notes"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Save Quotation
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
            url: 'quotation_actions.php',
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
            url: 'quotation_actions.php',
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
    $('#addQuotationBtn').click(function() {
        // Reset the form first
        $('#quotationForm')[0].reset();
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
        $('#quotationModal').modal('show');
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
    $('#quotationForm').on('submit', function(e) {
        e.preventDefault();
        
        let formData = new FormData(this);
        formData.append('action', 'create_quotation');
        formData.append('items', JSON.stringify(getItemsData()));
        
        $.ajax({
            url: 'quotation_actions.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status === 'success') {
                    // Close the modal
                    $('#quotationModal').modal('hide');
                    
                    // Reset the form
                    $('#quotationForm')[0].reset();
                    
                    // Refresh the table data
                    quotationsTable.ajax.reload();
                    
                    // Show success message
                    showAlert('success', 'Quotation saved successfully');
                } else {
                    showAlert('error', response.message || 'Failed to save quotation');
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
    let selectedQuotations = new Set();
    
    // Handle select all checkbox
    $('#selectAll').change(function() {
        let isChecked = $(this).prop('checked');
        $('.quotation-checkbox').prop('checked', isChecked);
        if (isChecked) {
            $('.quotation-checkbox').each(function() {
                selectedQuotations.add($(this).val());
            });
        } else {
            selectedQuotations.clear();
        }
        updateBulkDeleteButton();
    });
    
    // Handle individual checkboxes
    $(document).on('change', '.quotation-checkbox', function() {
        let quotationId = $(this).val();
        if ($(this).prop('checked')) {
            selectedQuotations.add(quotationId);
        } else {
            selectedQuotations.delete(quotationId);
            $('#selectAll').prop('checked', false);
        }
        updateBulkDeleteButton();
    });
    
    function updateBulkDeleteButton() {
        $('#bulkDeleteBtn').toggle(selectedQuotations.size > 0);
    }
    
    // Bulk delete action
    $('#bulkDeleteBtn').click(function() {
        if (selectedQuotations.size === 0) return;
        
        if (confirm('Are you sure you want to delete the selected quotations? This action cannot be undone.')) {
            $.ajax({
                url: 'quotation_actions.php',
                type: 'POST',
                data: {
                    action: 'bulk_delete',
                    quotation_ids: Array.from(selectedQuotations)
                },
                success: function(response) {
                    if (response.status === 'success') {
                        // Clear selections first
                        selectedQuotations.clear();
                        $('#selectAll').prop('checked', false);
                        updateBulkDeleteButton();
                        
                        // Reload the DataTable
                        $('#quotationsTable').DataTable().ajax.reload(null, false);
                        
                        // Show success message after a short delay to ensure table is refreshed
                        setTimeout(function() {
                            showAlert('success', 'Selected quotations have been deleted successfully.');
                        }, 100);
                    } else {
                        showAlert('error', response.message || 'Failed to delete selected quotations.');
                    }
                },
                error: handleAjaxError
            });
        }
    });

    // Initialize DataTable
    let quotationsTable = $('#quotationsTable').DataTable({
        processing: true,
        serverSide: false, // We'll handle server-side processing manually
        ajax: {
            url: 'quotation_actions.php',
            type: 'GET',
            data: function(d) {
                return {
                    action: 'fetch',
                    draw: d.draw,
                    search: $('#searchInput').val(),
                    customer_id: $('#customerFilter').val(),
                    start_date: $('#startDate').val(),
                    end_date: $('#endDate').val()
                };
            }
        },
        columns: [
            { 
                data: 'quotation_id',
                orderable: false,
                render: function(data) {
                    return `<input type="checkbox" class="form-check-input quotation-checkbox" value="${data}">`;
                }
            },
            { 
                data: null,
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            { data: 'quotation_number' },
            { data: 'customer_name' },
            { 
                data: 'quotation_date',
                render: function(data) {
                    return data ? new Date(data).toLocaleDateString() : '';
                }
            },
            { 
                data: 'final_amount',
                render: function(data) {
                    return parseFloat(data).toLocaleString(undefined, {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                }
            },
            { 
                data: 'status',
                render: function(data) {
                    let badgeClass = 'bg-warning';
                    if (data === 'approved') badgeClass = 'bg-success';
                    if (data === 'rejected') badgeClass = 'bg-danger';
                    if (data === 'converted') badgeClass = 'bg-info';
                    return `<span class="badge ${badgeClass}">${data}</span>`;
                }
            },
            { 
                data: 'validity_period',
                render: function(data, type, row) {
                    const quotationDate = new Date(row.quotation_date);
                    const validUntil = new Date(quotationDate);
                    validUntil.setDate(validUntil.getDate() + parseInt(data));
                    return `${data} days (until ${validUntil.toLocaleDateString()})`;
                }
            },
            {
                data: null,
                orderable: false,
                render: function(data, type, row) {
                    return `
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-primary view-btn" data-id="${row.quotation_id}">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-info edit-btn" data-id="${row.quotation_id}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-success convert-btn" data-id="${row.quotation_id}" 
                                ${row.status === 'converted' ? 'disabled' : ''}>
                                <i class="bi bi-arrow-right-circle"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="${row.quotation_id}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    `;
                }
            }
        ],
        order: [[3, 'desc']], // Sort by date descending
        pageLength: 10,
        dom: 'rtip', // Hide default search box
        language: {
            emptyTable: "No quotations found",
            zeroRecords: "No matching quotations found"
        }
    });

    // Search input
    $('#searchInput').on('keyup', function() {
        quotationsTable.ajax.reload();
    });

    // Customer filter
    $('#customerFilter').on('change', function() {
        quotationsTable.ajax.reload();
    });

    // Date filters
    $('#startDate, #endDate').on('change', function() {
        quotationsTable.ajax.reload();
    });

    // Clear filters
    $('#clearFilters').click(function() {
        $('#searchInput').val('');
        $('#customerFilter').val('').trigger('change');
        $('#startDate, #endDate').val('');
        quotationsTable.ajax.reload();
    });

    // Export CSV
    $('#exportCsvBtn').click(function() {
        let filters = {
            search: $('#searchInput').val(),
            customer_id: $('#customerFilter').val(),
            start_date: $('#startDate').val(),
            end_date: $('#endDate').val()
        };
        
        window.location.href = `quotation_actions.php?action=export_csv&filters=${JSON.stringify(filters)}`;
    });

    // Print functionality
    $('#printQuotationsBtn').click(function() {
        // Add print header
        let companyName = $('title').text() || 'Company Name';
        let printHeader = `
            <div class="print-header">
                <h2>${companyName}</h2>
                <h3>Quotations Report</h3>
                <p>Generated on: ${new Date().toLocaleString()}</p>
            </div>
        `;
        
        // Temporarily add the header
        $('.bg-light').prepend(printHeader);
        
        // Print
        window.print();
        
        // Remove the header after printing
        setTimeout(function() {
            $('.print-header').remove();
        }, 100);
    });

    // View quotation
    $(document).on('click', '.view-btn', function() {
        let quotationId = $(this).data('id');
        window.location.href = `view_quotation.php?id=${quotationId}`;
    });

    // Edit quotation
    $(document).on('click', '.edit-btn', function() {
        let quotationId = $(this).data('id');
        window.location.href = `edit_quotation.php?id=${quotationId}`;
    });

    // Convert quotation to sale
    $(document).on('click', '.convert-btn', function() {
        let quotationId = $(this).data('id');
        
        if (confirm('Are you sure you want to convert this quotation to a sale?')) {
            $.ajax({
                url: 'quotation_actions.php',
                type: 'POST',
                data: {
                    action: 'convert_to_sale',
                    quotation_id: quotationId
                },
                success: function(response) {
                    if (response.status === 'success') {
                        quotationsTable.ajax.reload();
                        showAlert('success', 'Quotation converted to sale successfully');
                    } else {
                        showAlert('error', response.message || 'Failed to convert quotation');
                    }
                },
                error: handleAjaxError
            });
        }
    });

    // Delete quotation
    $(document).on('click', '.delete-btn', function() {
        let quotationId = $(this).data('id');
        
        if (confirm('Are you sure you want to delete this quotation? This action cannot be undone.')) {
            $.ajax({
                url: 'quotation_actions.php',
                type: 'POST',
                data: {
                    action: 'delete_quotation',
                    quotation_id: quotationId
                },
                success: function(response) {
                    if (response.status === 'success') {
                        quotationsTable.ajax.reload();
                        showAlert('success', 'Quotation deleted successfully');
                    } else {
                        showAlert('error', response.message || 'Failed to delete quotation');
                    }
                },
                error: handleAjaxError
            });
        }
    });

    // Calculate row total
    function calculateRowTotal(row) {
        let quantity = parseFloat(row.find('.quantity').val()) || 0;
        let price = parseFloat(row.find('.price').val()) || 0;
        let discount = parseFloat(row.find('.discount').val()) || 0;
        
        let total = quantity * price - discount;
        row.find('.total').val(total.toFixed(2));
        
        calculateTotals();
    }

    // Calculate totals
    function calculateTotals() {
        let subTotal = 0;
        $('.total').each(function() {
            subTotal += parseFloat($(this).val()) || 0;
        });
        
        $('input[name="total_amount"]').val(subTotal.toFixed(2));
        
        let taxRate = parseFloat($('input[name="tax"]').val()) || 0;
        let taxAmount = subTotal * (taxRate / 100);
        $('#taxAmount').text(`Tax amount: ${taxAmount.toFixed(2)}`);
        
        let finalAmount = subTotal + taxAmount;
        $('input[name="final_amount"]').val(finalAmount.toFixed(2));
    }

    // Handle quantity, price, discount changes
    $(document).on('input', '.quantity, .price, .discount', function() {
        calculateRowTotal($(this).closest('.product-row'));
    });

    // Handle tax change
    $(document).on('input', 'input[name="tax"]', function() {
        calculateTotals();
    });

    // Reset price button
    $(document).on('click', '.reset-price', function() {
        let row = $(this).closest('.product-row');
        let selectedOption = row.find('.product-select option:selected');
        let originalPrice = selectedOption.data('price') || 0;
        
        row.find('.price').val(originalPrice);
        calculateRowTotal(row);
    });

    // Delete row button
    $(document).on('click', '.deleteRowBtn', function() {
        // Don't delete if it's the only row
        if ($('.product-row').length > 1) {
            $(this).closest('.product-row').remove();
            calculateTotals();
        } else {
            // Just clear the values
            let row = $(this).closest('.product-row');
            row.find('input').val('');
            row.find('select').val('');
            calculateTotals();
        }
    });

    // Show alert function
    function showAlert(type, message) {
        if (type === 'success') {
            // Use JavaScript's default alert for success messages
            alert(message);
        } else {
            // Use Bootstrap alerts for error messages
            let alertClass = 'alert-danger';
            let alert = `
                <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
            
            // Remove any existing alerts
            $('.alert').remove();
            
            // Add the new alert at the top of the content
            $('.content').prepend(alert);
            
            // Auto dismiss after 5 seconds
            setTimeout(function() {
                $('.alert').alert('close');
            }, 5000);
        }
    }

    // Load initial data
    loadInitialData();
});
</script> 