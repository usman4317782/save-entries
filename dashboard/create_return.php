<?php include "pages/header.php"; ?>
<?php include "pages/spinner.php"; ?>
<?php include "pages/sidenav.php"; ?>

<!-- Content Start -->
<div class="content">
    <?php include "pages/topnav.php"; ?>
    <div class="container-fluid pt-4 px-4">
        <div class="bg-light rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h5 class="mb-0">Create Sales Return</h5>
                <button id="cancelCreate" class="btn btn-secondary">
                    <i class="bi bi-x-circle me-2"></i>Cancel
                </button>
            </div>

            <form id="createReturnForm">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Customer</label>
                        <select name="customer_id" class="form-control" required>
                            <option value="">Select Customer</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Return Date</label>
                        <input type="text" name="return_date" class="form-control flatpickr" required>
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
                    <div class="col-md-6">
                        <label class="form-label">Payment Method</label>
                        <select class="form-control" name="payment_method" required>
                            <option value="cash">Cash</option>
                            <option value="card">Card</option>
                            <option value="bank_transfer">Bank Transfer</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Transaction ID</label>
                        <input type="text" class="form-control" name="transaction_id" placeholder="For card/bank transfer">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Notes</label>
                    <textarea class="form-control" name="notes" rows="3"></textarea>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-2"></i>Create Return
                </button>
            </form>
        </div>
    </div>

    <?php include "pages/footer.php"; ?>
</div>

<script>
$(document).ready(function() {
    // Initialize Select2 for customer selection
    $('select[name="customer_id"]').select2({
        width: '100%',
        placeholder: 'Select Customer',
        ajax: {
            url: 'return_actions.php',
            type: 'GET',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    action: 'get_customers',
                    search: params.term
                };
            },
            processResults: function(response) {
                if (response.status === 'success') {
                    return {
                        results: response.data.map(function(customer) {
                            return {
                                id: customer.customer_id,
                                text: customer.name + (customer.contact_number ? ' - ' + customer.contact_number : '')
                            };
                        })
                    };
                }
                return { results: [] };
            }
        }
    });

    // Initialize flatpickr
    flatpickr('.flatpickr', {
        dateFormat: 'Y-m-d',
        defaultDate: 'today'
    });

    // Add product row
    function addProductRow() {
        const row = $(`
            <tr class="product-row">
                <td>
                    <select class="form-control product-select" name="product_id[]" required>
                        <option value="">Select Product</option>
                    </select>
                </td>
                <td>
                    <input type="number" class="form-control quantity" name="quantity[]" min="1" required>
                </td>
                <td>
                    <input type="number" class="form-control price" name="price[]" step="0.01" required>
                </td>
                <td>
                    <input type="number" class="form-control discount" name="discount[]" min="0" value="0" step="0.01">
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <input type="number" class="form-control total" name="total[]" readonly>
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
                url: 'return_actions.php',
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
                    if (response.status === 'success') {
                        return {
                            results: response.data.map(function(product) {
                                return {
                                    id: product.id,
                                    text: product.product_name,
                                    price: product.price
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
            currentRow.find('.price').val(parseFloat(data.price).toFixed(2));
            currentRow.find('.quantity').val(1);
            calculateRowTotal(currentRow);
        });

        $('#productsList').append(row);
    }

    // Add initial product row
    addProductRow();

    // Add product row button
    $('#addProductRow').click(addProductRow);

    // Delete product row
    $(document).on('click', '.deleteRowBtn', function() {
        const productRows = $('.product-row');
        if (productRows.length > 1) {
            $(this).closest('.product-row').remove();
            calculateTotals();
        } else {
            alert('At least one product is required');
        }
    });

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
    }

    // Event listeners for calculations
    $(document).on('input', '.quantity, .price, .discount', function() {
        calculateRowTotal($(this).closest('.product-row'));
    });

    $('input[name="tax"]').on('input', calculateTotals);

    // Form submission
    $('#createReturnForm').submit(function(e) {
        e.preventDefault();
        
        // Validate form
        let isValid = true;
        $('.product-row').each(function() {
            const row = $(this);
            const productId = row.find('.product-select').val();
            const quantity = parseFloat(row.find('.quantity').val());
            
            if (!productId) {
                alert('Please select a product for all rows');
                isValid = false;
                return false;
            }
            
            if (!quantity || quantity < 1) {
                alert('Please enter a valid quantity for all products');
                isValid = false;
                return false;
            }
        });
        
        if (!isValid) return;

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

        // Send create request
        $.ajax({
            url: 'return_actions.php',
            type: 'POST',
            data: {
                action: 'create_return',
                customer_id: $('select[name="customer_id"]').val(),
                return_date: $('input[name="return_date"]').val(),
                total_amount: $('input[name="total_amount"]').val(),
                tax: $('input[name="tax"]').val(),
                final_amount: $('input[name="final_amount"]').val(),
                payment_method: $('select[name="payment_method"]').val(),
                transaction_id: $('input[name="transaction_id"]').val(),
                notes: $('textarea[name="notes"]').val(),
                items: JSON.stringify(items)
            },
            success: function(response) {
                if (response.status === 'success') {
                    alert('Return created successfully');
                    window.location.href = 'returns.php';
                } else {
                    alert(response.message || 'Failed to create return');
                }
            },
            error: function() {
                alert('Failed to create return');
            }
        });
    });

    // Cancel button
    $('#cancelCreate').click(function() {
        window.location.href = 'returns.php';
    });
});
</script>

<style>
.select2-container {
    z-index: 9999;
}
</style> 