<?php include "pages/header.php"; ?>
<?php include "pages/spinner.php"; ?>
<?php include "pages/sidenav.php"; ?>

<!-- Content Start -->
<div class="content">
    <?php include "pages/topnav.php"; ?>
    <div class="container-fluid pt-4 px-4">
        <div class="bg-light rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h5 class="mb-0">Invoice Details</h5>
                <div>
                    <div class="form-check form-switch d-inline-block me-2">
                        <input class="form-check-input" type="checkbox" id="showCustomerDetails">
                        <label class="form-check-label" for="showCustomerDetails">Show Customer Details</label>
                    </div>
                    <div class="form-check form-switch d-inline-block me-2">
                        <input class="form-check-input" type="checkbox" id="showPaymentHistory">
                        <label class="form-check-label" for="showPaymentHistory">Show Payment History</label>
                    </div>
                    <div class="dropdown d-inline-block me-2">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="printOptionsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-printer me-2"></i>Print
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="printOptionsDropdown">
                            <li><a class="dropdown-item" href="#" id="printInvoiceA4">A4 Format</a></li>
                            <li><a class="dropdown-item" href="#" id="printInvoice80mm">80mm Receipt</a></li>
                        </ul>
                    </div>
                    <button id="editInvoice" class="btn btn-warning me-2">
                        <i class="bi bi-pencil me-2"></i>Edit
                    </button>
                    <button id="deleteInvoice" class="btn btn-danger">
                        <i class="bi bi-trash me-2"></i>Delete
                    </button>
                </div>
            </div>

            <div id="invoiceContent" class="invoice-wrapper">
                <!-- Invoice header with company info and logo -->
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
                        <h2 class="invoice-title mb-2">INVOICE</h2>
                        <div class="invoice-number-wrapper">
                            <strong>Invoice #:</strong>
                            <span id="invoiceNumber" class="ms-2"></span>
                        </div>
                        <div class="invoice-date-wrapper">
                            <strong>Date:</strong>
                            <span id="createdDate" class="ms-2"></span>
                        </div>
                    </div>
                </div>

                <!-- Customer and Payment Info -->
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

                <!-- Notes and Payment History Container -->
                <div class="row mb-4 notes-payment-container">
                    <!-- Notes -->
                    <div class="col-md-6 mb-4 notes-box-container">
                        <div class="notes-box p-3 bg-white rounded shadow-sm">
                            <h6 class="border-bottom pb-2 mb-3">Notes</h6>
                            <p id="notes" class="mb-0 text-muted fst-italic"></p>
                        </div>
                    </div>

                    <!-- Payment history -->
                    <div class="col-md-6 mb-4 payment-history-section" style="display: none;">
                        <div class="payment-history-box p-3 bg-white rounded shadow-sm">
                            <h6 class="border-bottom pb-2 mb-3">
                                <i class="bi bi-clock-history text-primary"></i> Payment History
                                <span class="float-end text-muted small">Total Payments: <span id="totalPayments">0</span></span>
                            </h6>
                            <div class="table-responsive">
                                <table class="table table-sm table-hover payment-history-table">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 20%">Date</th>
                                            <th style="width: 20%" class="text-end">Amount</th>
                                            <th style="width: 15%">Method</th>
                                            <th style="width: 20%">Transaction ID</th>
                                            <th style="width: 25%">Notes</th>
                                        </tr>
                                    </thead>
                                    <tbody id="paymentsTableBody"></tbody>
                                    <tfoot class="table-light" id="paymentSummaryFoot">
                                        <tr>
                                            <td colspan="5" class="text-end">
                                                <span class="text-muted me-3">Total Paid: <strong class="text-success" id="totalPaidAmount">0.00</strong></span>
                                                <span class="text-muted">Balance: <strong class="text-danger" id="balanceAmount">0.00</strong></span>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="clearfix"></div>
            </div>
        </div>
    </div>

    <?php include "pages/footer.php"; ?>
</div>

<style>
.invoice-wrapper {
    background-color: #f8f9fa;
    padding: 2rem;
    border-radius: 0.5rem;
}

.company-name {
    color: #2b2d42;
    font-weight: 600;
}

.company-details {
    color: #6c757d;
    font-size: 0.9rem;
}

.invoice-title {
    color: #2b2d42;
    font-weight: 700;
    letter-spacing: 1px;
}

.customer-info-box,
.payment-info-box,
.payment-history-box,
.notes-box {
    background-color: #ffffff;
    border: 1px solid rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.customer-info-box:hover,
.payment-info-box:hover,
.payment-history-box:hover,
.notes-box:hover {
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.1);
}

.table > :not(caption) > * > * {
    padding: 0.75rem;
}

.table > thead {
    background-color: #4e73df;
    color: #ffffff;
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

    .invoice-wrapper {
        padding: 0 !important;
        margin: 0 !important;
        background: none !important;
        box-shadow: none !important;
    }

    /* Company header styling - more compact */
    .row.border-bottom {
        border-bottom: 1px solid #ddd !important;
        margin-bottom: 5px !important;
        padding-bottom: 5px !important;
    }

    /* Make company details and invoice details in same row more compact */
    .col-md-6 {
        width: 50% !important;
        float: left !important;
        padding: 0 5px !important;
    }

    #company-logo {
        max-height: 50px !important;
        max-width: 100px !important;
    }

    .company-name {
        font-size: 1rem !important;
        margin-bottom: 2px !important;
    }

    .company-details-compact {
        font-size: 0.7rem !important;
        margin: 0 !important;
        line-height: 1.1 !important;
    }

    .company-details-compact i {
        width: 12px !important;
        font-size: 0.7rem !important;
    }

    .invoice-title {
        font-size: 1.2rem !important;
        margin-bottom: 2px !important;
    }

    .invoice-number-wrapper,
    .invoice-date-wrapper {
        font-size: 0.8rem !important;
        margin-bottom: 2px !important;
    }

    /* Customer and payment info boxes in same row */
    .customer-section {
        display: flex !important;
        flex-wrap: wrap !important;
        margin-bottom: 5px !important;
    }

    .customer-section .col-md-6 {
        width: 50% !important;
        padding: 0 5px !important;
    }

    .customer-info-box,
    .payment-info-box {
        padding: 5px !important;
        margin-bottom: 5px !important;
        border: 1px solid #ddd !important;
        background: #f9f9f9 !important;
        height: 100% !important;
    }

    .customer-info-box h6,
    .payment-info-box h6 {
        font-size: 0.8rem !important;
        margin-bottom: 3px !important;
        padding-bottom: 3px !important;
        border-bottom: 1px solid #ddd !important;
    }

    .customer-info-box .mb-2,
    .payment-info-box .mb-2 {
        margin-bottom: 2px !important;
        font-size: 0.75rem !important;
    }

    /* Table styling */
    .table {
        font-size: 0.75rem !important;
        margin-bottom: 5px !important;
        border: 1px solid #ddd !important;
    }

    .table thead th {
        background-color: #f3f3f3 !important;
        color: #333 !important;
        font-weight: 600 !important;
        padding: 3px !important;
        border-bottom: 1px solid #ddd !important;
    }

    .table td {
        padding: 3px !important;
        border-color: #ddd !important;
    }

    .table-light {
        background-color: #f9f9f9 !important;
    }

    /* Notes and Payment history in same row */
    .notes-payment-container {
        display: flex !important;
        flex-wrap: wrap !important;
        margin-bottom: 5px !important;
    }
    
    .notes-box-container,
    .payment-history-section {
        width: 50% !important;
        float: left !important;
        padding: 0 5px !important;
    }
    
    /* When payment history is not shown, notes should take full width */
    .notes-box-container:not(.print-with-payment-history) {
        width: 100% !important;
    }
    
    /* When payment history is shown, ensure notes are side by side */
    .payment-history-section.show-in-print + .notes-box-container {
        width: 50% !important;
    }

    .payment-history-box,
    .notes-box {
        padding: 5px !important;
        margin-bottom: 5px !important;
        border: 1px solid #ddd !important;
        background: #f9f9f9 !important;
        height: 100% !important;
    }

    .payment-history-box h6,
    .notes-box h6 {
        font-size: 0.8rem !important;
        margin-bottom: 3px !important;
        padding-bottom: 3px !important;
        border-bottom: 1px solid #ddd !important;
    }

    .payment-history-table {
        font-size: 0.7rem !important;
    }

    .payment-history-table th,
    .payment-history-table td {
        padding: 2px !important;
    }

    #notes {
        font-size: 0.75rem !important;
    }

    /* Spacing adjustments */
    .mb-4 {
        margin-bottom: 5px !important;
    }

    .pb-4 {
        padding-bottom: 5px !important;
    }

    .pt-4 {
        padding-top: 5px !important;
    }

    /* Customer section visibility */
    .customer-section {
        display: none !important;
    }

    .customer-section.show-in-print {
        display: flex !important;
        page-break-inside: avoid !important;
    }

    /* Payment history section visibility */
    .payment-history-section {
        display: none !important;
    }

    .payment-history-section.show-in-print {
        display: block !important;
        page-break-inside: avoid !important;
        width: 50% !important;
        float: left !important;
    }

    /* Prevent unwanted page breaks */
    .table-responsive,
    .payment-history-box,
    .notes-box {
        page-break-inside: avoid !important;
    }

    /* Page settings */
    @page {
        margin: 0.5cm;
        size: A4;
    }

    /* Make text sharper */
    * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        color-adjust: exact !important;
    }

    /* Ensure table columns have proper width */
    .table th:nth-child(1) { width: 40% !important; }
    .table th:nth-child(2) { width: 15% !important; }
    .table th:nth-child(3) { width: 15% !important; }
    .table th:nth-child(4) { width: 15% !important; }
    .table th:nth-child(5) { width: 15% !important; }

    /* Totals section */
    tfoot tr td {
        padding: 2px 5px !important;
        font-weight: 500 !important;
        font-size: 0.75rem !important;
    }

    tfoot tr:last-child td {
        font-weight: 700 !important;
        border-top: 1px solid #ddd !important;
    }

    /* Print styles */
    body * {
        visibility: hidden;
    }
    #invoiceContent, #invoiceContent * {
        visibility: visible;
    }
    #invoiceContent {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        padding: 10px;
    }
    .content {
        margin-left: 0 !important;
        padding: 0 !important;
    }
    .container-fluid {
        padding: 0 !important;
    }
    .bg-light {
        background-color: white !important;
        box-shadow: none !important;
    }
    
    .no-print {
        display: none !important;
    }

    /* Clear floats */
    .clearfix::after {
        content: "";
        clear: both;
        display: table;
    }
}

/* 80mm Receipt Print Styles */
@media print and (max-width: 80mm) {
    body {
        width: 80mm !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    
    #invoiceContent {
        width: 72mm !important; /* 80mm - margins */
        padding: 2mm !important;
    }
    
    /* Company header for 80mm */
    .row.border-bottom {
        display: block !important;
        text-align: center !important;
    }
    
    .col-md-6 {
        width: 100% !important;
        float: none !important;
        text-align: center !important;
        padding: 0 !important;
        margin-bottom: 3mm !important;
    }
    
    .col-md-6.text-md-end {
        text-align: center !important;
    }
    
    #company-logo-container {
        display: flex !important;
        justify-content: center !important;
        margin-bottom: 2mm !important;
    }
    
    #company-logo {
        max-height: 30px !important;
        max-width: 60px !important;
    }
    
    .company-name {
        font-size: 0.9rem !important;
        margin-bottom: 1mm !important;
    }
    
    .company-details-compact {
        font-size: 0.6rem !important;
        line-height: 1 !important;
    }
    
    .invoice-title {
        font-size: 1rem !important;
        margin: 1mm 0 !important;
    }
    
    .invoice-number-wrapper,
    .invoice-date-wrapper {
        font-size: 0.7rem !important;
        margin-bottom: 1mm !important;
    }
    
    /* Customer section for 80mm */
    .customer-section .col-md-6 {
        width: 100% !important;
        margin-bottom: 2mm !important;
    }
    
    /* Table for 80mm */
    .table {
        font-size: 0.6rem !important;
        width: 100% !important;
    }
    
    .table th {
        padding: 1mm !important;
        font-size: 0.6rem !important;
    }
    
    .table td {
        padding: 1mm !important;
        font-size: 0.6rem !important;
    }
    
    /* Hide some columns in 80mm mode */
    .table th:nth-child(4),
    .table td:nth-child(4) {
        display: none !important;
    }
    
    /* Adjust column widths for 80mm */
    .table th:nth-child(1) { width: 50% !important; }
    .table th:nth-child(2) { width: 15% !important; }
    .table th:nth-child(3) { width: 15% !important; }
    .table th:nth-child(5) { width: 20% !important; }
    
    /* Notes and Payment History for 80mm */
    .notes-box-container,
    .payment-history-section {
        width: 100% !important;
        float: none !important;
        margin-bottom: 2mm !important;
    }
    
    .payment-history-table th:nth-child(3),
    .payment-history-table td:nth-child(3),
    .payment-history-table th:nth-child(4),
    .payment-history-table td:nth-child(4) {
        display: none !important;
    }
    
    .payment-history-table th:nth-child(1) { width: 40% !important; }
    .payment-history-table th:nth-child(2) { width: 30% !important; }
    .payment-history-table th:nth-child(5) { width: 30% !important; }
    
    /* Adjust font sizes for 80mm */
    h6 {
        font-size: 0.7rem !important;
    }
    
    #notes {
        font-size: 0.6rem !important;
    }
    
    tfoot tr td {
        font-size: 0.65rem !important;
    }
}

.company-details-compact {
    font-size: 0.9rem;
    line-height: 1.5;
    color: #6c757d;
}

.company-details-compact i {
    width: 20px;
    display: inline-block;
}

.payment-history-table {
    font-size: 0.875rem;
    margin-bottom: 0;
}

.payment-history-table th {
    font-weight: 600;
    white-space: nowrap;
}

.payment-history-table td {
    vertical-align: middle;
}

.payment-history-box {
    background: #fff;
    border: 1px solid rgba(0,0,0,.125);
    box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,.075);
}

/* Notes and Payment History Layout */
.notes-payment-container {
    display: flex;
    flex-wrap: wrap;
}

.notes-box-container,
.payment-history-section {
    margin-bottom: 1rem;
}

@media (min-width: 768px) {
    .notes-box-container,
    .payment-history-section {
        padding: 0 0.5rem;
    }
}
</style>

<script>
$(document).ready(function() {
    // Get sale ID from URL
    const urlParams = new URLSearchParams(window.location.search);
    const saleId = urlParams.get('id');

    if (!saleId) {
        alert('Invalid sale ID');
        window.location.href = 'sales.php';
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

    // Load sale details
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
                
                // Fill invoice details
                $('#invoiceNumber').text(sale.invoice_number);
                $('#createdDate').text(moment(sale.created_at).format('DD-MM-YYYY'));
                $('#customerName').text(sale.customer_name);
                $('#customerPhone').text(sale.customer_phone || 'N/A');
                $('#customerAddress').text(sale.customer_address || 'N/A');
                $('#paymentMethod').text(sale.payment_method.replace('_', ' ').toUpperCase());
                
                // Payment status badge
                const badges = {
                    'paid': 'success',
                    'partially_paid': 'warning',
                    'pending': 'danger'
                };
                $('#paymentStatus').html(
                    `<span class="badge bg-${badges[sale.payment_status]}">
                        ${sale.payment_status.replace('_', ' ').toUpperCase()}
                    </span>`
                );

                // Fill items table
                let itemsHtml = '';
                sale.items.forEach(function(item) {
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
                $('#subTotal').text(parseFloat(sale.total_amount).toFixed(2));
                $('#tax').text(parseFloat(sale.tax).toFixed(2));
                $('#finalAmount').text(parseFloat(sale.final_amount).toFixed(2));

                // Fill payments table and calculate totals
                let paymentsHtml = '';
                let totalPaid = 0;
                if (sale.payments && sale.payments.length > 0) {
                    sale.payments.forEach(function(payment) {
                        totalPaid += parseFloat(payment.amount);
                        paymentsHtml += `
                            <tr>
                                <td>${moment(payment.payment_date).format('DD-MM-YYYY')}</td>
                                <td class="text-end">${parseFloat(payment.amount).toFixed(2)}</td>
                                <td>${payment.payment_method.replace('_', ' ').toUpperCase()}</td>
                                <td>${payment.transaction_id || '-'}</td>
                                <td class="text-muted">${payment.notes || '-'}</td>
                            </tr>
                        `;
                    });
                    $('#totalPayments').text(sale.payments.length);
                    $('#totalPaidAmount').text(totalPaid.toFixed(2));
                    const balance = parseFloat(sale.final_amount) - totalPaid;
                    $('#balanceAmount').text(balance.toFixed(2));
                } else {
                    paymentsHtml = '<tr><td colspan="5" class="text-center text-muted">No payment records found</td></tr>';
                    $('#totalPayments').text('0');
                    $('#totalPaidAmount').text('0.00');
                    $('#balanceAmount').text(parseFloat(sale.final_amount).toFixed(2));
                }
                $('#paymentsTableBody').html(paymentsHtml);

                // Fill notes
                $('#notes').text(sale.notes || 'No notes available');
            } else {
                alert('Failed to load invoice details');
                window.location.href = 'sales.php';
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
            alert('Failed to load invoice details');
            window.location.href = 'sales.php';
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

    // Toggle payment history
    $('#showPaymentHistory').change(function() {
        if ($(this).is(':checked')) {
            $('.payment-history-section').show().addClass('show-in-print');
        } else {
            $('.payment-history-section').hide().removeClass('show-in-print');
        }
    });

    // Print invoice - A4 format
    $('#printInvoiceA4').click(function() {
        // For print preview, ensure notes are visible alongside payment history
        if ($('#showPaymentHistory').is(':checked')) {
            $('.notes-box-container').addClass('print-with-payment-history');
        } else {
            $('.notes-box-container').removeClass('print-with-payment-history');
        }
        
        // Ensure customer details are properly displayed
        if ($('#showCustomerDetails').is(':checked')) {
            $('.customer-section').addClass('show-in-print');
        }
        
        // Set to A4 print mode
        $('body').removeClass('print-80mm');
        
        window.print();
    });
    
    // Print invoice - 80mm receipt format
    $('#printInvoice80mm').click(function() {
        // Only show payment history in 80mm mode if checkbox is checked
        if ($('#showPaymentHistory').is(':checked')) {
            $('.payment-history-section').show().addClass('show-in-print');
            $('.notes-box-container').addClass('print-with-payment-history');
        } else {
            $('.payment-history-section').hide().removeClass('show-in-print');
            $('.notes-box-container').removeClass('print-with-payment-history');
        }
        
        // Only show customer details in 80mm mode if checkbox is checked
        if ($('#showCustomerDetails').is(':checked')) {
            $('.customer-section').show().addClass('show-in-print');
        } else {
            $('.customer-section').hide().removeClass('show-in-print');
        }
        
        // Set to 80mm print mode
        $('body').addClass('print-80mm');
        
        window.print();
        
        // Reset after printing
        $('body').removeClass('print-80mm');
        
        // Reset visibility based on checkboxes (in case they were changed during print preview)
        if (!$('#showPaymentHistory').is(':checked')) {
            $('.payment-history-section').hide().removeClass('show-in-print');
            $('.notes-box-container').removeClass('print-with-payment-history');
        } else {
            $('.payment-history-section').show().addClass('show-in-print');
        }
        
        if (!$('#showCustomerDetails').is(':checked')) {
            $('.customer-section').hide().removeClass('show-in-print');
        } else {
            $('.customer-section').show().addClass('show-in-print');
        }
    });

    // Edit invoice
    $('#editInvoice').click(function() {
        window.location.href = `edit_invoice.php?id=${saleId}`;
    });

    // Delete invoice
    $('#deleteInvoice').click(function() {
        if (confirm('Are you sure you want to delete this invoice? This action cannot be undone.')) {
            $.ajax({
                url: 'sale_actions.php',
                type: 'POST',
                data: {
                    action: 'delete_sale',
                    sale_id: saleId
                },
                success: function(response) {
                    if (response.status === 'success') {
                        alert('Invoice deleted successfully');
                        window.location.href = 'sales.php';
                    } else {
                        alert('Failed to delete invoice: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    alert('Failed to delete invoice');
                }
            });
        }
    });
});
</script>
