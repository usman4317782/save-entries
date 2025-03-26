<?php include "pages/header.php"; ?>
<?php include "pages/spinner.php"; ?>
<?php include "pages/sidenav.php"; ?>

<!-- Content Start -->
<div class="content">
    <?php include "pages/topnav.php"; ?>
    <div class="container-fluid pt-4 px-4">
        <div class="bg-light rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h5 class="mb-0">Return Details</h5>
                <div>
                    <div class="form-check form-switch d-inline-block me-2">
                        <input class="form-check-input" type="checkbox" id="showCustomerDetails">
                        <label class="form-check-label" for="showCustomerDetails">Show Customer Details</label>
                    </div>
                    <div class="dropdown d-inline-block me-2">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="printOptionsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-printer me-2"></i>Print
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="printOptionsDropdown">
                            <li><a class="dropdown-item" href="#" id="printReturnA4">A4 Format</a></li>
                            <li><a class="dropdown-item" href="#" id="printReturn80mm">80mm Receipt</a></li>
                        </ul>
                    </div>
                    <button id="editReturn" class="btn btn-warning me-2">
                        <i class="bi bi-pencil me-2"></i>Edit
                    </button>
                    <button id="deleteReturn" class="btn btn-danger">
                        <i class="bi bi-trash me-2"></i>Delete
                    </button>
                </div>
            </div>

            <div id="returnContent" class="return-wrapper">
                <!-- Return header with company info and logo -->
                <div class="row border-bottom pb-4 mb-4">
                    <div class="col-md-6 d-flex align-items-center">
                        <div id="company-logo-container" class="me-3">
                            <?php 
                            try {
                                $db = new PDO(
                                    "mysql:host=" . DB_HOST . ";dbname=" . DB_DATABASE . ";charset=utf8mb4",
                                    DB_USERNAME,
                                    DB_PASSWORD,
                                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
                                );
                                
                                $stmt = $db->query("SELECT * FROM company_settings LIMIT 1");
                                $companyInfo = $stmt->fetch(PDO::FETCH_ASSOC);
                                
                                if ($companyInfo && $companyInfo['logo_path']) {
                                    echo '<img id="company-logo" src="..' . $companyInfo['logo_path'] . '?v=' . time() . '" alt="Company Logo" style="max-height: 80px; max-width: 200px;">';
                                }
                            } catch (PDOException $e) {
                                error_log("Error loading company info: " . $e->getMessage());
                            }
                            ?>
                        </div>
                        <div>
                            <h4 class="company-name mb-1" id="companyName"></h4>
                            <div class="company-details-compact">
                                <i class="bi bi-geo-alt text-primary"></i> <span id="companyAddress"></span><br>
                                <i class="bi bi-telephone text-primary"></i> <span id="companyPhone"></span><br>
                                <i class="bi bi-envelope text-primary"></i> <span id="companyEmail"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <h2 class="return-title mb-2">SALES RETURN</h2>
                        <div class="return-number-wrapper">
                            <strong>Return #:</strong>
                            <span id="returnNumber" class="ms-2"></span>
                        </div>
                        <div class="return-date-wrapper">
                            <strong>Date:</strong>
                            <span id="createdDate" class="ms-2"></span>
                        </div>
                    </div>
                </div>

                <!-- Customer Info -->
                <div class="row mb-4 customer-section" style="display: none;">
                    <div class="col-md-6">
                        <div class="customer-info-box p-3 bg-white rounded shadow-sm">
                            <h6 class="border-bottom pb-2 mb-3">Customer Information</h6>
                            <div class="mb-2">
                                <strong class="text-muted">Name:</strong>
                                <span id="customerName" class="ms-2"></span>
                            </div>
                            <div class="mb-2">
                                <strong class="text-muted">Phone:</strong>
                                <span id="customerPhone" class="ms-2"></span>
                            </div>
                            <div class="mb-2">
                                <strong class="text-muted">Address:</strong>
                                <span id="customerAddress" class="ms-2"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="payment-info-box p-3 bg-white rounded shadow-sm">
                            <h6 class="border-bottom pb-2 mb-3">Payment Information</h6>
                            <div class="mb-2">
                                <strong class="text-muted">Status:</strong>
                                <span id="paymentStatus" class="ms-2"></span>
                            </div>
                            <div class="mb-2">
                                <strong class="text-muted">Method:</strong>
                                <span id="paymentMethod" class="ms-2"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Items table -->
                <div class="table-responsive mb-4">
                    <table class="table table-bordered table-hover">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th>Product</th>
                                <th class="text-center" style="width: 100px;">Quantity</th>
                                <th class="text-end" style="width: 150px;">Unit Price</th>
                                <th class="text-end" style="width: 120px;">Discount</th>
                                <th class="text-end" style="width: 150px;">Total</th>
                            </tr>
                        </thead>
                        <tbody id="itemsTableBody"></tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="4" class="text-end"><strong>Sub Total:</strong></td>
                                <td class="text-end" id="subTotal"></td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end"><strong>Tax:</strong></td>
                                <td class="text-end" id="tax"></td>
                            </tr>
                            <tr class="table-primary">
                                <td colspan="4" class="text-end"><strong>Final Amount:</strong></td>
                                <td class="text-end"><strong id="finalAmount"></strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Notes -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="notes-box p-3 bg-white rounded shadow-sm">
                            <h6 class="border-bottom pb-2 mb-3">Notes</h6>
                            <p id="notes" class="mb-0 text-muted fst-italic"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include "pages/footer.php"; ?>
</div>

<script>
$(document).ready(function() {
    // Get return ID from URL
    const urlParams = new URLSearchParams(window.location.search);
    const returnId = urlParams.get('id');

    if (!returnId) {
        alert('Invalid return ID');
        window.location.href = 'returns.php';
        return;
    }

    // Load company information
    $.ajax({
        url: 'company_actions.php',
        type: 'POST',
        data: {
            action: 'get_company_info'
        },
        success: function(response) {
            if (response.status === 'success' && response.data) {
                $('#companyName').text(response.data.company_name);
                $('#companyAddress').text(response.data.company_address);
                $('#companyPhone').text('Phone: ' + response.data.company_phone);
                $('#companyEmail').text('Email: ' + response.data.company_email);
            }
        },
        error: function() {
            console.error('Failed to load company information');
        }
    });

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
                
                // Fill return details
                $('#returnNumber').text(returnData.invoice_number);
                $('#createdDate').text(moment(returnData.created_at).format('DD-MM-YYYY'));
                $('#customerName').text(returnData.customer_name);
                $('#customerPhone').text(returnData.customer_phone || 'N/A');
                $('#customerAddress').text(returnData.customer_address || 'N/A');
                $('#paymentMethod').text(returnData.payment_method.replace('_', ' ').toUpperCase());
                
                // Payment status badge
                const badges = {
                    'paid': 'success',
                    'partially_paid': 'warning',
                    'pending': 'danger'
                };
                $('#paymentStatus').html(
                    `<span class="badge bg-${badges[returnData.payment_status]}">
                        ${returnData.payment_status.replace('_', ' ').toUpperCase()}
                    </span>`
                );

                // Fill items table
                let itemsHtml = '';
                returnData.items.forEach(function(item) {
                    itemsHtml += `
                        <tr>
                            <td>${item.product_name}</td>
                            <td class="text-center">${item.quantity}</td>
                            <td class="text-end">${parseFloat(item.unit_price).toFixed(2)}</td>
                            <td class="text-end">${parseFloat(item.discount).toFixed(2)}</td>
                            <td class="text-end">${parseFloat(item.total_price).toFixed(2)}</td>
                        </tr>
                    `;
                });
                $('#itemsTableBody').html(itemsHtml);

                // Fill totals
                $('#subTotal').text(parseFloat(returnData.total_amount).toFixed(2));
                $('#tax').text(parseFloat(returnData.tax).toFixed(2));
                $('#finalAmount').text(parseFloat(returnData.final_amount).toFixed(2));

                // Fill notes
                $('#notes').text(returnData.notes || 'No notes available');
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

    // Toggle customer details
    $('#showCustomerDetails').change(function() {
        if ($(this).is(':checked')) {
            $('.customer-section').show().addClass('show-in-print');
        } else {
            $('.customer-section').hide().removeClass('show-in-print');
        }
    });

    // Print return - A4 format
    $('#printReturnA4').click(function() {
        window.print();
    });
    
    // Print return - 80mm receipt format
    $('#printReturn80mm').click(function() {
        $('body').addClass('print-80mm');
        window.print();
        $('body').removeClass('print-80mm');
    });

    // Edit return
    $('#editReturn').click(function() {
        window.location.href = `edit_return.php?id=${returnId}`;
    });

    // Delete return
    $('#deleteReturn').click(function() {
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
                        window.location.href = 'returns.php';
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
.return-wrapper {
    background-color: #f8f9fa;
    padding: 2rem;
    border-radius: 0.5rem;
}

.company-name {
    color: #2b2d42;
    font-weight: 600;
}

.company-details-compact {
    color: #6c757d;
    font-size: 0.9rem;
}

.return-title {
    color: #2b2d42;
    font-weight: 700;
    letter-spacing: 1px;
}

.customer-info-box,
.payment-info-box,
.notes-box {
    background-color: #ffffff;
    border: 1px solid rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.customer-info-box:hover,
.payment-info-box:hover,
.notes-box:hover {
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.1);
}

@media print {
    /* Hide non-printable elements */
    .navbar,
    .sidebar,
    .btn,
    footer,
    .form-check,
    .dropdown {
        display: none !important;
    }
    
    /* Reset page margins and padding */
    body {
        margin: 0;
        padding: 0;
        background: #fff !important;
    }
    
    .content {
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
    }

    .container-fluid {
        padding: 5px !important;
        margin: 0 !important;
        width: 100% !important;
    }

    .return-wrapper {
        padding: 0 !important;
        margin: 0 !important;
        background: none !important;
        box-shadow: none !important;
    }

    /* Print styles for A4 */
    @page {
        size: A4;
        margin: 0.5cm;
    }

    /* Print styles for 80mm receipt */
    @media (max-width: 80mm) {
        @page {
            size: 80mm auto;
            margin: 0;
        }

        body {
            width: 80mm;
        }

        .return-wrapper {
            width: 76mm !important;
            padding: 2mm !important;
        }

        /* Adjust font sizes for receipt */
        body {
            font-size: 10px !important;
        }

        h1, h2, h3, h4, h5, h6 {
            font-size: 12px !important;
        }

        .table th,
        .table td {
            padding: 2px !important;
            font-size: 10px !important;
        }
    }
}
</style> 