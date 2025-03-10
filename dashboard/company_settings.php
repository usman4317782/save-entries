<?php include "pages/header.php"; ?>
<?php include "pages/spinner.php"; ?>
<?php include "pages/sidenav.php"; ?>

<!-- Content Start -->
<div class="content">
    <?php include "pages/topnav.php"; ?>
    <div class="container-fluid pt-4 px-4">
        <div class="bg-light rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h5 class="mb-0">Company Settings</h5>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Company Logo</h5>
                        </div>
                        <div class="card-body">
                            <form id="logoForm" enctype="multipart/form-data">
                                <input type="hidden" name="action" value="upload_logo">
                                
                                <div class="mb-3">
                                    <label for="logoFile" class="form-label">Upload Logo</label>
                                    <input type="file" class="form-control" id="logoFile" name="logo" accept="image/png, image/jpeg, image/jpg">
                                    <div class="form-text">Recommended size: 200x80 pixels. Max file size: 2MB.</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Current Logo</label>
                                    <div class="border p-3 text-center">
                                        <?php 
                                        $logoPath = "../assets/images/company-logo.png";
                                        if (file_exists($logoPath)) {
                                            echo '<img src="' . $logoPath . '?v=' . time() . '" alt="Company Logo" style="max-height: 80px; max-width: 200px;">';
                                        } else {
                                            echo '<p class="text-muted">No logo uploaded yet</p>';
                                        }
                                        ?>
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">Upload Logo</button>
                                <button type="button" id="removeLogo" class="btn btn-danger">Remove Logo</button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Company Information</h5>
                        </div>
                        <div class="card-body">
                            <form id="companyInfoForm">
                                <input type="hidden" name="action" value="update_company_info">
                                
                                <div class="mb-3">
                                    <label for="companyName" class="form-label">Company Name</label>
                                    <input type="text" class="form-control" id="companyName" name="company_name" value="Your Company Name">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="companyAddress" class="form-label">Address</label>
                                    <textarea class="form-control" id="companyAddress" name="company_address" rows="3">123 Business Street
City, Country</textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="companyPhone" class="form-label">Phone</label>
                                    <input type="text" class="form-control" id="companyPhone" name="company_phone" value="+1234567890">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="companyEmail" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="companyEmail" name="company_email" value="info@yourcompany.com">
                                </div>
                                
                                <button type="submit" class="btn btn-primary">Save Information</button>
                            </form>
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
    // Load existing company information
    $.ajax({
        url: 'company_actions.php',
        type: 'POST',
        data: {
            action: 'get_company_info'
        },
        success: function(response) {
            if (response.status === 'success' && response.data) {
                $('#companyName').val(response.data.company_name);
                $('#companyAddress').val(response.data.company_address);
                $('#companyPhone').val(response.data.company_phone);
                $('#companyEmail').val(response.data.company_email);
            }
        },
        error: function() {
            alert('Failed to load company information');
        }
    });

    // Logo upload form submission
    $('#logoForm').submit(function(e) {
        e.preventDefault();
        
        var formData = new FormData(this);
        
        $.ajax({
            url: 'company_actions.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.status === 'success') {
                    alert('Logo uploaded successfully');
                    // Reload the page to show the new logo
                    location.reload();
                } else {
                    alert(response.message || 'Failed to upload logo');
                }
            },
            error: function() {
                alert('An error occurred while uploading the logo');
            }
        });
    });
    
    // Remove logo
    $('#removeLogo').click(function() {
        if (confirm('Are you sure you want to remove the company logo?')) {
            $.ajax({
                url: 'company_actions.php',
                type: 'POST',
                data: {
                    action: 'remove_logo'
                },
                success: function(response) {
                    if (response.status === 'success') {
                        alert('Logo removed successfully');
                        // Reload the page to update the logo display
                        location.reload();
                    } else {
                        alert(response.message || 'Failed to remove logo');
                    }
                },
                error: function() {
                    alert('An error occurred while removing the logo');
                }
            });
        }
    });
    
    // Company info form submission
    $('#companyInfoForm').submit(function(e) {
        e.preventDefault();
        
        $.ajax({
            url: 'company_actions.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.status === 'success') {
                    alert('Company information updated successfully');
                } else {
                    alert(response.message || 'Failed to update company information');
                }
            },
            error: function() {
                alert('An error occurred while updating company information');
            }
        });
    });
});
</script>

