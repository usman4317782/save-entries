<?php include "pages/header.php"; ?>
<?php include "pages/spinner.php"; ?>
<?php include "pages/sidenav.php"; ?>

<!-- Content Start -->
<div class="content">
    <?php include "pages/topnav.php"; ?>
    <div class="container-fluid pt-4 px-4">
        <div class="bg-light rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h5 class="mb-0">Edit Return</h5>
                <button id="cancelEdit" class="btn btn-secondary">
                    <i class="bi bi-x-circle me-2"></i>Cancel
                </button>
            </div>

            <form id="editReturnForm">
                <input type="hidden" name="return_id" id="returnId">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Return Number</label>
                        <input type="text" id="returnNumber" class="form-control" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Customer</label>
                        <select name="customer_id" class="form-control" required>
                            <option value="">Select Customer</option>
                        </select>
                    </div>
                    <div class="col-md-4">
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
                    <div class="col-md-4">
                        <label class="form-label">Payment Method</label>
                        <select class="form-control" name="payment_method" required>
                            <option value="cash">Cash</option>
                            <option value="card">Card</option>
                            <option value="bank_transfer">Bank Transfer</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Payment Status</label>
                        <input type="text" class="form-control" id="paymentStatus" readonly>
                    </div>
                    <div class="col-md-4">
                        <!-- Additional field if needed -->
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Return Notes</label>
                    <textarea class="form-control" name="notes" rows="3"></textarea>
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

    flatpickr('.flatpickr', {
        dateFormat: 'Y-m-d'
    });

    // Get return ID from URL
    const urlParams = new URLSearchParams(window.location.search);
    const returnId = urlParams.get('id');

    if (!returnId) {
        alert('Invalid return ID');
        window.location.href = 'returns.php';
        return;
    }

    $('#returnId').val(returnId);

    // Load return details
    $.ajax({
        url: 'return_actions.php',
        type: 'GET',
        data: {
            action: 'get_return',
            id: returnId
        },
        success: function(response) {
            if (response.status === 'success' && response.data) {
                const returnData = response.data;
                
                // Fill form fields
                $('#returnNumber').val(returnData.invoice_number);
                $('select[name="customer_id"]').append(new Option(returnData.customer_name, returnData.customer_id, true, true)).trigger('change');
                $('input[name="return_date"]').val(returnData.return_date.split(' ')[0]);
                $('input[name="total_amount"]').val(parseFloat(returnData.total_amount).toFixed(2));
                $('input[name="tax"]').val(parseFloat(returnData.tax).toFixed(2));
                $('input[name="final_amount"]').val(parseFloat(returnData.final_amount).toFixed(2));
                $('select[name="payment_method"]').val(returnData.payment_method);
                $('#paymentStatus').val(returnData.payment_status.replace('_', ' ').toUpperCase());
                $('textarea[name="notes"]').val(returnData.notes);

                // Load products
                $('#productsList').empty();
                if (returnData.items && returnData.items.length > 0) {
                    returnData.items.forEach(function(item) {
                        addProductRow(item);
                    });
                } else {
                    addProductRow();
                }
            } else {
                alert('Failed to load return details');
                window.location.href = 'returns.php';
            }
        },
        error: function() {
            alert('Failed to load return details');
            window.location.href = 'returns.php';
        }
    });

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
                    <input type="number" class="form-control quantity" name="quantity[]" min="0" required>
                </td>
                <td>
                    <input type="number" class="form-control price" name="price[]" readonly>
                </td>
                <td>
                    <input type="number" class="form-control discount" name="discount[]" min="0" value="0">
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
            
            // Only set quantity to 1 if it's empty
            if (!currentRow.find('.quantity').val()) {
                currentRow.find('.quantity').val(1);
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
    }

    // Event handlers
    $(document).on('input', '.quantity, .discount', function() {
        calculateRowTotal($(this).closest('.product-row'));
    });

    $('input[name="tax"]').on('input', calculateTotals);

    $('#addProductRow').click(function() {
        addProductRow();
    });

    $(document).on('click', '.deleteRowBtn', function() {
        $(this).closest('.product-row').remove();
        calculateTotals();
    });

    // Handle form submission
    $('#editReturnForm').submit(function(e) {
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
        });
        
        if (!isValid) return;

        // Prepare items data
        let items = [];
        $('.product-row').each(function() {
            const row = $(this);
            items.push({
                product_id: row.find('.product-select').val(),
                quantity: parseFloat(row.find('.quantity').val()),
                unit_price: parseFloat(row.find('.price').val()),
                discount: parseFloat(row.find('.discount').val()),
                total_price: parseFloat(row.find('.total').val())
            });
        });

        // Prepare form data
        const formData = {
            action: 'update_return',
            return_id: $('#returnId').val(),
            customer_id: $('select[name="customer_id"]').val(),
            return_date: $('input[name="return_date"]').val(),
            total_amount: parseFloat($('input[name="total_amount"]').val()),
            tax: parseFloat($('input[name="tax"]').val() || 0),
            final_amount: parseFloat($('input[name="final_amount"]').val()),
            payment_method: $('select[name="payment_method"]').val(),
            notes: $('textarea[name="notes"]').val(),
            items: JSON.stringify(items)
        };

        // Log the data being sent
        console.log('Sending data:', formData);

        // Send update request
        $.ajax({
            url: 'return_actions.php',
            type: 'POST',
            data: formData,
            success: function(response) {
                console.log('Response:', response);
                if (response.status === 'success') {
                    alert('Return updated successfully');
                    window.location.href = `view_return.php?id=${$('#returnId').val()}`;
                } else {
                    alert(response.message || 'Failed to update return');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error details:', {
                    status: status,
                    error: error,
                    response: xhr.responseText
                });
                alert('Failed to update return. Please check the console for details.');
            }
        });
    });

    // Cancel button
    $('#cancelEdit').click(function() {
        window.location.href = `view_return.php?id=${returnId}`;
    });
});
</script>

<style>
.select2-container {
    z-index: 9999;
}
</style> 