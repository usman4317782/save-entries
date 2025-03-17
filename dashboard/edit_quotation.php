<?php include "pages/header.php"; ?>
<?php include "pages/spinner.php"; ?>
<?php include "pages/sidenav.php"; ?>

<!-- Content Start -->
<div class="content">
    <?php include "pages/topnav.php"; ?>
    <div class="container-fluid pt-4 px-4">
        <div class="bg-light rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h5 class="mb-0">Edit Quotation</h5>
            </div>

            <form id="editQuotationForm">
                <input type="hidden" id="quotationId" name="quotation_id">
                
                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <label for="quotationNumber" class="form-label">Quotation Number</label>
                        <input type="text" class="form-control" id="quotationNumber" name="quotation_number" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="quotationDate" class="form-label">Quotation Date</label>
                        <input type="date" class="form-control" id="quotationDate" name="quotation_date" required>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <label for="customerId" class="form-label">Customer</label>
                        <select class="form-select" id="customerId" name="customer_id" required>
                            <option value="">Select Customer</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="validityPeriod" class="form-label">Validity Period (days)</label>
                        <input type="number" class="form-control" id="validityPeriod" name="validity_period" min="1" value="30" required>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                            <!-- <option value="converted">Converted to Sale</option> -->
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="1"></textarea>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0">Products</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="productsTable">
                                        <thead>
                                            <tr>
                                                <th style="width: 40%;">Product</th>
                                                <th style="width: 15%;">Quantity</th>
                                                <th style="width: 15%;">Unit Price</th>
                                                <th style="width: 15%;">Discount</th>
                                                <th style="width: 15%;">Total</th>
                                                <th style="width: 5%;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="productRows">
                                            <!-- Product rows will be added here -->
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="6">
                                                    <button type="button" class="btn btn-sm btn-success" id="addProductRow">
                                                        <i class="bi bi-plus-circle me-2"></i>Add Product
                                                    </button>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <!-- Empty column for spacing -->
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0">Totals</h6>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <label for="subTotal" class="col-sm-4 col-form-label">Sub Total</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="subTotal" name="sub_total" readonly>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="taxRate" class="col-sm-4 col-form-label">Tax Rate (%)</label>
                                    <div class="col-sm-8">
                                        <input type="number" class="form-control" id="taxRate" name="tax_rate" min="0" step="0.01" value="0">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="taxAmount" class="col-sm-4 col-form-label">Tax Amount</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="taxAmount" name="tax_amount" readonly>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="finalAmount" class="col-sm-4 col-form-label">Final Amount</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="finalAmount" name="final_amount" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-secondary me-2" id="cancelBtn">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="saveQuotationBtn">Save Changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php include "pages/footer.php"; ?>
</div>

<script>
$(document).ready(function() {
    // Get quotation ID from URL
    const urlParams = new URLSearchParams(window.location.search);
    const quotationId = urlParams.get('id');

    if (!quotationId) {
        alert('Invalid quotation ID');
        window.location.href = 'quotations.php';
        return;
    }

    $('#quotationId').val(quotationId);

    // Load customers
    $.ajax({
        url: 'quotation_actions.php',
        type: 'GET',
        data: {
            action: 'get_customers'
        },
        success: function(response) {
            if (response.status === 'success' && response.data) {
                let options = '<option value="">Select Customer</option>';
                response.data.forEach(function(customer) {
                    options += `<option value="${customer.customer_id}">${customer.name}</option>`;
                });
                $('#customerId').html(options);
                
                // Load quotation details after customers are loaded
                loadQuotationDetails();
            }
        },
        error: function() {
            console.error('Failed to load customers');
        }
    });

    // Load quotation details
    function loadQuotationDetails() {
        $.ajax({
            url: 'quotation_actions.php',
            type: 'GET',
            data: {
                action: 'get_quotation',
                id: quotationId
            },
            success: function(response) {
                if (response.status === 'success' && response.data) {
                    const quotation = response.data;
                    
                    // Fill quotation details
                    $('#quotationNumber').val(quotation.quotation_number);
                    $('#quotationDate').val(formatDateForInput(quotation.quotation_date));
                    $('#customerId').val(quotation.customer_id);
                    $('#validityPeriod').val(quotation.validity_period);
                    $('#status').val(quotation.status);
                    $('#notes').val(quotation.notes);
                    
                    // Set tax rate - calculate from total_amount and tax
                    const totalAmount = parseFloat(quotation.total_amount) || 0;
                    const tax = parseFloat(quotation.tax) || 0;
                    let taxRate = 0;
                    if (totalAmount > 0) {
                        taxRate = (tax / totalAmount) * 100;
                    }
                    $('#taxRate').val(taxRate.toFixed(2));
                    
                    // Fill products
                    $('#productRows').empty();
                    quotation.items.forEach(function(item) {
                        addProductRow(item);
                    });
                    
                    // Calculate totals
                    calculateTotals();
                    
                    // Disable status field if already converted
                    if (quotation.status === 'converted') {
                        $('#status').prop('disabled', true);
                        alert('This quotation has been converted to a sale. Some fields may be read-only.');
                    }
                } else {
                    alert('Failed to load quotation details');
                    window.location.href = 'quotations.php';
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading quotation details:', error);
                alert('Failed to load quotation details');
                window.location.href = 'quotations.php';
            }
        });
    }

    // Add product row
    function addProductRow(item = null) {
        const rowIndex = $('#productRows tr').length;
        const row = `
            <tr>
                <td>
                    <select class="form-select product-select" name="products[${rowIndex}][product_id]" required>
                        <option value="">Select Product</option>
                    </select>
                </td>
                <td>
                    <input type="number" class="form-control product-quantity" name="products[${rowIndex}][quantity]" min="0" value="${item ? item.quantity : '1'}" required>
                </td>
                <td>
                    <input type="number" class="form-control product-price" name="products[${rowIndex}][unit_price]" step="0.01" min="0" value="${item ? item.unit_price : '0.00'}" required>
                </td>
                <td>
                    <input type="number" class="form-control product-discount" name="products[${rowIndex}][discount]" step="0.01" min="0" value="${item ? item.discount : '0.00'}" required>
                </td>
                <td>
                    <input type="text" class="form-control product-total" name="products[${rowIndex}][total_price]" readonly value="${item ? item.total_price : '0.00'}">
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger remove-product">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        $('#productRows').append(row);
        
        // Load products for the new row
        const $productSelect = $('#productRows tr:last-child .product-select');
        loadProducts($productSelect, item ? item.product_id : null);
        
        // Bind events for the new row
        bindProductRowEvents();
    }
    
    // Load products for a select element
    function loadProducts($select, selectedProductId = null) {
        $.ajax({
            url: 'quotation_actions.php',
            type: 'GET',
            data: {
                action: 'get_products'
            },
            success: function(response) {
                if (response.status === 'success' && response.data) {
                    let options = '<option value="">Select Product</option>';
                    response.data.forEach(function(product) {
                        const selected = selectedProductId && selectedProductId == product.id ? 'selected' : '';
                        let description = product.description ? ` (${product.description})` : '';
                        let brandInfo = product.brand_name ? ` - ${product.brand_name}` : '';
                        let stockInfo = ` [${product.stock_quantity} in stock]`;
                        
                        options += `<option value="${product.id}" data-price="${product.price}" ${selected}>
                            ${product.product_name}${brandInfo}${description}${stockInfo}
                        </option>`;
                    });
                    $select.html(options);
                    
                    // If a product was selected, trigger change to update price
                    if (selectedProductId) {
                        $select.trigger('change');
                    }
                }
            },
            error: function() {
                console.error('Failed to load products');
            }
        });
    }
    
    // Bind events for product rows
    function bindProductRowEvents() {
        // Product selection change
        $('.product-select').off('change').on('change', function() {
            const $row = $(this).closest('tr');
            const $option = $(this).find('option:selected');
            const price = $option.data('price') || 0;
            
            $row.find('.product-price').val(price.toFixed(2));
            updateRowTotal($row);
        });
        
        // Quantity, price, or discount change
        $('.product-quantity, .product-price, .product-discount').off('input').on('input', function() {
            const $row = $(this).closest('tr');
            updateRowTotal($row);
        });
        
        // Remove product row
        $('.remove-product').off('click').on('click', function() {
            $(this).closest('tr').remove();
            calculateTotals();
        });
    }
    
    // Update row total
    function updateRowTotal($row) {
        const quantity = parseFloat($row.find('.product-quantity').val()) || 0;
        const price = parseFloat($row.find('.product-price').val()) || 0;
        const discount = parseFloat($row.find('.product-discount').val()) || 0;
        
        const total = (quantity * price) - discount;
        $row.find('.product-total').val(total.toFixed(2));
        
        calculateTotals();
    }
    
    // Calculate totals
    function calculateTotals() {
        let subTotal = 0;
        
        $('.product-total').each(function() {
            subTotal += parseFloat($(this).val()) || 0;
        });
        
        const taxRate = parseFloat($('#taxRate').val()) || 0;
        const taxAmount = (subTotal * taxRate) / 100;
        const finalAmount = subTotal + taxAmount;
        
        $('#subTotal').val(subTotal.toFixed(2));
        $('#taxAmount').val(taxAmount.toFixed(2));
        $('#finalAmount').val(finalAmount.toFixed(2));
    }
    
    // Add product row button
    $('#addProductRow').click(function() {
        addProductRow();
    });
    
    // Tax rate change
    $('#taxRate').on('input', function() {
        calculateTotals();
    });
    
    // Cancel button
    $('#cancelBtn').click(function() {
        window.location.href = `view_quotation.php?id=${quotationId}`;
    });
    
    // Form submission
    $('#editQuotationForm').submit(function(e) {
        e.preventDefault();
        
        // Validate form
        if (!validateForm()) {
            return;
        }
        
        // Calculate totals one last time to ensure accuracy
        calculateTotals();
        
        // Prepare data
        const data = {
            action: 'update_quotation',
            quotation_id: quotationId,
            customer_id: $('#customerId').val(),
            quotation_date: $('#quotationDate').val(),
            validity_period: $('#validityPeriod').val(),
            status: $('#status').val(),
            notes: $('#notes').val(),
            total_amount: $('#subTotal').val(),
            tax: $('#taxAmount').val(),
            final_amount: $('#finalAmount').val(),
            items: []
        };
        
        // Prepare items
        $('#productRows tr').each(function() {
            const $row = $(this);
            const productId = $row.find('.product-select').val();
            
            if (productId) {
                data.items.push({
                    product_id: productId,
                    quantity: $row.find('.product-quantity').val(),
                    unit_price: $row.find('.product-price').val(),
                    discount: $row.find('.product-discount').val(),
                    total_price: $row.find('.product-total').val()
                });
            }
        });
        
        // Send AJAX request
        $.ajax({
            url: 'quotation_actions.php',
            type: 'POST',
            data: {
                action: 'update_quotation',
                quotation_id: data.quotation_id,
                customer_id: data.customer_id,
                quotation_date: data.quotation_date,
                validity_period: data.validity_period,
                status: data.status,
                notes: data.notes,
                total_amount: data.total_amount,
                tax: data.tax,
                final_amount: data.final_amount,
                items: JSON.stringify(data.items)
            },
            success: function(response) {
                if (response.status === 'success') {
                    alert('Quotation updated successfully');
                    window.location.href = `view_quotation.php?id=${quotationId}`;
                } else {
                    alert('Failed to update quotation: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error updating quotation:', error);
                alert('Failed to update quotation');
            }
        });
    });
    
    // Validate form
    function validateForm() {
        // Check if at least one product is selected
        if ($('#productRows tr').length === 0) {
            alert('Please add at least one product');
            return false;
        }
        
        // Check if all products are selected
        let valid = true;
        $('#productRows tr').each(function() {
            const productId = $(this).find('.product-select').val();
            const quantity = parseFloat($(this).find('.product-quantity').val());
            
            if (!productId) {
                alert('Please select a product for all rows');
                valid = false;
                return false;
            }
            
            if (isNaN(quantity) || quantity < 0) {
                alert('Quantity must be 0 or greater');
                valid = false;
                return false;
            }
        });
        
        return valid;
    }
    
    // Helper function to format date for input
    function formatDateForInput(dateString) {
        const date = new Date(dateString);
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }
});
</script> 