<?php include "pages/header.php"; ?>
<?php include "pages/spinner.php"; ?>
<?php include "pages/sidenav.php"; ?>

<!-- Content Start -->
<div class="content">
    <?php include "pages/topnav.php"; ?>
    <div class="container-fluid pt-4 px-4">
        <div class="bg-light rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h5 class="mb-0">Edit Invoice</h5>
                <button id="cancelEdit" class="btn btn-secondary">
                    <i class="bi bi-x-circle me-2"></i>Cancel
                </button>
            </div>

            <form id="editSaleForm">
                <input type="hidden" name="sale_id" id="saleId">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Invoice Number</label>
                        <input type="text" id="invoiceNumber" class="form-control" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Customer</label>
                        <select name="customer_id" class="form-control" required>
                            <option value="">Select Customer</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Sale Date</label>
                        <input type="text" name="sale_date" class="form-control flatpickr" required>
                    </div>
                </div>

                <div class="mb-3">
                    <h6>Products</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th width="35%">Product</th>
                                    <th width="15%">Quantity</th>
                                    <th width="15%">Unit Price</th>
                                    <th width="15%">Discount</th>
                                    <th width="20%">Total</th>
                                </tr>
                            </thead>
                            <tbody id="productsList">
                                <!-- Product rows will be added here -->
                            </tbody>
                        </table>
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
                        <input type="number" class="form-control" name="tax" value="0">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Final Amount</label>
                        <input type="number" class="form-control" name="final_amount" readonly>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Payment Method</label>
                        <select class="form-control" name="payment_method" required>
                            <option value="cash">Cash</option>
                            <option value="card">Card</option>
                            <option value="bank_transfer">Bank Transfer</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Already Paid Amount</label>
                        <input type="number" class="form-control" id="alreadyPaidAmount" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">New Payment Amount</label>
                        <div class="input-group">
                            <input type="number" class="form-control" name="payment_amount" min="0" value="0">
                            <button type="button" class="btn btn-outline-secondary" id="fillRemainingBtn" title="Fill with remaining balance">
                                <i class="bi bi-lightning-fill"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Remaining Balance</label>
                        <input type="number" class="form-control" id="remainingBalance" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Transaction ID</label>
                        <input type="text" class="form-control" name="transaction_id" placeholder="For card/bank transfer">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Payment Status</label>
                        <input type="text" class="form-control" id="paymentStatus" readonly>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label">Payment Notes</label>
                        <textarea class="form-control" name="payment_notes" rows="2"></textarea>
                    </div>
                </div>

                <div class="mb-3">
                    <h6>Payment History</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Transaction ID</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody id="paymentHistory">
                                <!-- Payment history will be added here -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Invoice Notes</label>
                    <textarea class="form-control" name="notes"></textarea>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-2"></i>Save Changes
                </button>
            </form>
        </div>
    </div>

    <?php include "pages/footer.php"; ?>
</div>

<script>
$(document).ready(function() {
    // Initialize Select2 and flatpickr
    $('select[name="customer_id"]').select2({
        width: '100%',
        placeholder: 'Select Customer'
    });

    flatpickr('.flatpickr', {
        dateFormat: 'Y-m-d'
    });

    // Get sale ID from URL
    const urlParams = new URLSearchParams(window.location.search);
    const saleId = urlParams.get('id');

    if (!saleId) {
        alert('Invalid sale ID');
        window.location.href = 'sales.php';
        return;
    }

    $('#saleId').val(saleId);

    // Load customers for dropdown
    $.ajax({
        url: 'sale_actions.php',
        type: 'GET',
        data: { action: 'get_customers' },
        success: function(response) {
            if (response.status === 'success') {
                let customerSelect = $('select[name="customer_id"]');
                response.data.forEach(function(customer) {
                    customerSelect.append(
                        `<option value="${customer.customer_id}">
                            ${customer.name} ${customer.contact_number ? ' - ' + customer.contact_number : ''}
                        </option>`
                    );
                });

                // Load sale details after customers are loaded
                loadSaleDetails();
            }
        }
    });

    // Load sale details
    function loadSaleDetails() {
        $.ajax({
            url: 'sale_actions.php',
            type: 'GET',
            data: {
                action: 'get_sale',
                id: saleId
            },
            success: function(response) {
                if (response.status === 'success' && response.data) {
                    const sale = response.data;
                    
                    // Fill form fields
                    $('#invoiceNumber').val(sale.invoice_number);
                    $('select[name="customer_id"]').val(sale.customer_id).trigger('change');
                    $('input[name="sale_date"]').val(sale.sale_date.split(' ')[0]); // Get only the date part
                    $('input[name="total_amount"]').val(parseFloat(sale.total_amount).toFixed(2));
                    $('input[name="tax"]').val(parseFloat(sale.tax).toFixed(2));
                    $('input[name="final_amount"]').val(parseFloat(sale.final_amount).toFixed(2));
                    $('select[name="payment_method"]').val(sale.payment_method);
                    $('textarea[name="notes"]').val(sale.notes);

                    // Load products and create rows
                    $('#productsList').empty();
                    if (sale.items && sale.items.length > 0) {
                        sale.items.forEach(function(item) {
                            addProductRow(item);
                        });
                    } else {
                        addProductRow(); // Add an empty row if no items
                    }

                    // Calculate total paid amount from all payments
                    let totalPaid = 0;
                    
                    // Populate payment history
                    $('#paymentHistory').empty();
                    if (sale.payments && sale.payments.length > 0) {
                        sale.payments.forEach(function(payment) {
                            totalPaid += parseFloat(payment.amount);
                            
                            // Add payment to history table
                            $('#paymentHistory').append(`
                                <tr>
                                    <td>${formatDate(payment.payment_date)}</td>
                                    <td>${parseFloat(payment.amount).toFixed(2)}</td>
                                    <td>${capitalizeFirstLetter(payment.payment_method)}</td>
                                    <td>${payment.transaction_id || '-'}</td>
                                    <td>${payment.notes || '-'}</td>
                                </tr>
                            `);
                        });
                        
                        // Set transaction ID and payment notes from the latest payment
                        const latestPayment = sale.payments[0]; // First payment is the latest
                        $('input[name="transaction_id"]').val(latestPayment.transaction_id || '');
                        $('textarea[name="payment_notes"]').val(latestPayment.notes || '');
                    } else {
                        $('#paymentHistory').append('<tr><td colspan="5" class="text-center">No payment history found</td></tr>');
                    }
                    
                    // Set already paid amount
                    $('#alreadyPaidAmount').val(totalPaid.toFixed(2));
                    
                    // Calculate and set remaining balance
                    const finalAmount = parseFloat(sale.final_amount);
                    const remainingBalance = finalAmount - totalPaid;
                    $('#remainingBalance').val(remainingBalance.toFixed(2));
                    
                    // Set payment status
                    $('#paymentStatus').val(sale.payment_status);
                    
                    // Set default new payment to 0
                    $('input[name="payment_amount"]').val('0');
                    
                    // Update payment fields when final amount changes
                    updatePaymentFields();
                } else {
                    console.error('Failed to load sale details:', response);
                    alert('Failed to load invoice details');
                    window.location.href = 'sales.php';
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading sale details:', error);
                alert('Failed to load invoice details');
                window.location.href = 'sales.php';
            }
        });
    }

    // Add product row
    function addProductRow(item = null) {
        const row = $(`
            <tr class="product-row">
                <td>
                    <select class="form-control product-select" name="product_id[]" required>
                        <option value="">Select Product</option>
                    </select>
                </td>
                <td>
                    <input type="number" class="form-control quantity" name="quantity[]" placeholder="Qty" min="0" required>
                </td>
                <td>
                    <input type="number" class="form-control price" name="price[]" placeholder="Price">
                </td>
                <td>
                    <input type="number" class="form-control discount" name="discount[]" placeholder="Discount" min="0" value="0">
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <input type="number" class="form-control total" name="total[]" placeholder="Total" readonly>
                        <a href="javascript:void(0)" class="text-danger deleteRowBtn ms-2">
                            <i class="fas fa-times-circle fa-lg"></i>
                        </a>
                    </div>
                </td>
            </tr>
        `);

        // Initialize product select with Select2
        const productSelect = row.find('.product-select');
        productSelect.select2({
            width: '100%',
            placeholder: 'Select Product',
            ajax: {
                url: 'sale_actions.php',
                type: 'GET',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        action: 'get_products',
                        search: params.term
                    };
                },
                processResults: function(response) {
                    if (response.status === 'success' && response.data) {
                        return {
                            results: response.data.map(function(product) {
                                return {
                                    id: product.id,
                                    text: product.product_name,
                                    price: product.price,
                                    stock: product.stock_quantity
                                };
                            })
                        };
                    }
                    return { results: [] };
                }
            }
        }).on('select2:select', function(e) {
            const data = e.params.data;
            const currentRow = $(this).closest('.product-row');
            
            // Set the price
            currentRow.find('.price').val(parseFloat(data.price).toFixed(2));
            
            // Set quantity and max based on stock
            const quantityInput = currentRow.find('.quantity');
            quantityInput.attr('max', data.stock); // Set max quantity based on stock
            
            // Only set quantity to 1 if it's empty
            if (!quantityInput.val()) {
                quantityInput.val(1);
            }
            
            calculateRowTotal(currentRow);
        });

        // If editing existing item
        if (item) {
            const option = new Option(item.product_name, item.product_id, true, true);
            productSelect.append(option).trigger('change');
            
            row.find('.quantity').val(parseFloat(item.quantity).toFixed(2));
            row.find('.price').val(parseFloat(item.unit_price).toFixed(2));
            row.find('.discount').val(parseFloat(item.discount).toFixed(2));
            row.find('.total').val(parseFloat(item.total_price).toFixed(2));
        }

        $('#productsList').append(row);
    }

    // Calculate row total
    function calculateRowTotal(row) {
        const quantity = parseFloat(row.find('.quantity').val()) || 0;
        const price = parseFloat(row.find('.price').val()) || 0;
        const discount = parseFloat(row.find('.discount').val()) || 0;
        
        const total = (quantity * price) - discount;
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
        
        const tax = parseFloat($('input[name="tax"]').val()) || 0;
        const finalAmount = subTotal + (subTotal * tax / 100);
        $('input[name="final_amount"]').val(finalAmount.toFixed(2));
        
        // Update payment fields when final amount changes
        updatePaymentFields();
    }
    
    // Update payment fields
    function updatePaymentFields() {
        const finalAmount = parseFloat($('input[name="final_amount"]').val()) || 0;
        const alreadyPaid = parseFloat($('#alreadyPaidAmount').val()) || 0;
        const newPayment = parseFloat($('input[name="payment_amount"]').val()) || 0;
        const totalPaid = alreadyPaid + newPayment;
        const remainingBalance = finalAmount - totalPaid;
        
        $('#remainingBalance').val(remainingBalance.toFixed(2));
        
        // Update payment status based on the amounts
        let status = 'pending';
        let statusClass = 'bg-warning text-dark';
        
        if (totalPaid >= finalAmount) {
            status = 'paid';
            statusClass = 'bg-success text-white';
        } else if (totalPaid > 0) {
            status = 'partially_paid';
            statusClass = 'bg-info text-white';
        }
        
        const $paymentStatus = $('#paymentStatus');
        $paymentStatus.val(status);
        
        // Apply visual styling
        $paymentStatus.removeClass('bg-warning bg-success bg-info text-white text-dark');
        $paymentStatus.addClass(statusClass);
    }

    // Initialize event handlers
    $(document).on('input', '.quantity, .discount', function() {
        calculateRowTotal($(this).closest('.product-row'));
    });
    
    // Add event listener for price changes
    $(document).on('input', '.price', function() {
        calculateRowTotal($(this).closest('.product-row'));
    });

    // Tax input event listener
    $('input[name="tax"]').on('input', calculateTotals);

    // Payment amount input event listener
    $('input[name="payment_amount"]').on('input', function() {
        updatePaymentFields();
    });
    
    // Fill remaining balance button
    $('#fillRemainingBtn').click(function() {
        const remainingBalance = parseFloat($('#remainingBalance').val()) || 0;
        $('input[name="payment_amount"]').val(remainingBalance.toFixed(2));
        updatePaymentFields();
    });

    // Add product row button
    $('#addProductRow').click(function() {
        addProductRow();
    });

    // Delete product row
    $(document).on('click', '.deleteRowBtn', function() {
        $(this).closest('.product-row').remove();
        calculateTotals();
    });

    // Handle form submission
    $('#editSaleForm').submit(function(e) {
        e.preventDefault();
        
        // Validate form
        let isValid = true;
        $('.product-row').each(function() {
            const row = $(this);
            const productId = row.find('.product-select').val();
            const quantity = parseFloat(row.find('.quantity').val());
            const maxQuantity = parseFloat(row.find('.quantity').attr('max'));
            
            if (!productId) {
                alert('Please select a product for all rows');
                isValid = false;
                return false;
            }
            
            if (quantity === '' || quantity < 0) {
                alert('Please enter a valid quantity for all products (0 or greater)');
                isValid = false;
                return false;
            }
            
            if (quantity > maxQuantity) {
                alert(`Maximum available quantity for selected product is ${maxQuantity}`);
                isValid = false;
                return false;
            }
        });
        
        if (!isValid) return;

        const newPaymentAmount = parseFloat($('input[name="payment_amount"]').val()) || 0;
        const finalAmount = parseFloat($('input[name="final_amount"]').val());
        const alreadyPaid = parseFloat($('#alreadyPaidAmount').val()) || 0;
        
        if (newPaymentAmount + alreadyPaid > finalAmount) {
            alert('Total payment amount cannot be greater than the final amount');
            return;
        }
        
        // Prepare items data
        let items = [];
        $('.product-row').each(function() {
            const row = $(this);
            items.push({
                product_id: row.find('.product-select').val(),
                quantity: row.find('.quantity').val(),
                unit_price: row.find('.price').val(),
                discount: row.find('.discount').val(),
                total_price: row.find('.total').val()
            });
        });

        // Send update request
        $.ajax({
            url: 'sale_actions.php',
            type: 'POST',
            data: {
                action: 'update_sale',
                sale_id: $('#saleId').val(),
                customer_id: $('select[name="customer_id"]').val(),
                sale_date: $('input[name="sale_date"]').val(),
                total_amount: $('input[name="total_amount"]').val(),
                tax: $('input[name="tax"]').val(),
                final_amount: $('input[name="final_amount"]').val(),
                payment_method: $('select[name="payment_method"]').val(),
                payment_amount: newPaymentAmount > 0 ? $('input[name="payment_amount"]').val() : '',
                transaction_id: $('input[name="transaction_id"]').val(),
                payment_notes: $('textarea[name="payment_notes"]').val(),
                notes: $('textarea[name="notes"]').val(),
                items: JSON.stringify(items)
            },
            success: function(response) {
                if (response.status === 'success') {
                    alert('Invoice updated successfully');
                    window.location.href = `view_invoice.php?id=${$('#saleId').val()}`;
                } else {
                    alert(response.message || 'Failed to update invoice');
                }
            },
            error: function() {
                alert('Failed to update invoice');
            }
        });
    });

    // Cancel button
    $('#cancelEdit').click(function() {
        window.location.href = `view_invoice.php?id=${saleId}`;
    });

    // Helper function to format date
    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleString();
    }
    
    // Helper function to capitalize first letter
    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1).replace('_', ' ');
    }
});
</script>

<style>
.select2-container {
    z-index: 9999;
}
</style>
