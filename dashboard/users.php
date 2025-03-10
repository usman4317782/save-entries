<?php include "pages/header.php"; ?>
<?php include "pages/spinner.php"; ?>
<?php include "pages/sidenav.php"; ?>
<!-- Content Start -->
<div class="content">
    <?php include "pages/topnav.php"; ?>
    <div class="container mt-4">
        <h2 class="mb-3">User Management</h2>
        <div class="d-flex justify-content-between mb-3">
            <button id="addUserBtn" class="btn btn-primary btn-icon">
                <i class="bi bi-plus-circle"></i>
                <span class="btn-text">Add User</span>
            </button>
            <button id="bulkDeleteBtn" class="btn btn-danger btn-icon">
                <i class="bi bi-trash"></i>
                <span class="btn-text">Bulk Delete</span>
            </button>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="usersTable" class="table table-striped">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="selectAll"></th>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Is Active</th>
                                <th>Is Verified</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit User Modal -->
    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel">Add User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="userForm">
                        <input type="hidden" name="id" id="userId">
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" name="username" required>
                            <small class="form-text text-muted">Only letters, numbers, and underscores allowed</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" id="password">
                            <small class="form-text text-muted">Leave empty to keep existing password when editing</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" name="confirm_password" id="confirm_password">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">First Name</label>
                            <input type="text" class="form-control" name="first_name">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Last Name</label>
                            <input type="text" class="form-control" name="last_name">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveUser">Save</button>
                </div>
            </div>
        </div>
    </div>
<?php include "pages/footer.php"; ?>
</div>

<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#usersTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: 'user_actions.php?action=fetch',
            type: 'POST',
            error: function(xhr, error, thrown) {
                console.error('DataTables error:', error);
                console.error('Server response:', xhr.responseText);
            }
        },
        columns: [
            {
                data: 'id',
                orderable: false,
                render: function(data) {
                    return '<input type="checkbox" class="selectUser" value="' + data + '">';
                }
            },
            { data: 'id' },
            { 
                data: 'username',
                render: function(data) { return data || ''; }
            },
            { 
                data: 'email',
                render: function(data) { return data || ''; }
            },
            { 
                data: 'first_name',
                render: function(data) { return data || ''; }
            },
            { 
                data: 'last_name',
                render: function(data) { return data || ''; }
            },
            { 
                data: 'is_active',
                render: function(data) { return data == 1 ? 'Yes' : 'No'; }
            },
            { 
                data: 'is_verified',
                render: function(data) { return data == 1 ? 'Yes' : 'No'; }
            },
            { 
                data: 'created_at',
                render: function(data) { return data || ''; }
            },
            {
                data: 'id',
                orderable: false,
                render: function(data) {
                    return '<button class="btn btn-warning btn-sm editBtn" data-id="' + data + '"><i class="bi bi-pencil"></i></button> ' +
                           '<button class="btn btn-danger btn-sm deleteBtn" data-id="' + data + '"><i class="bi bi-trash"></i></button>';
                }
            }
        ],
        order: [[1, 'desc']]
    });

    // Add User button click
    $('#addUserBtn').click(function() {
        $('#userForm')[0].reset();
        $('#userId').val('');
        $('#userModalLabel').text('Add User');
        $('#password').prop('required', true);
        $('#confirm_password').prop('required', true);
        $('#userModal').modal('show');
    });

    // Edit User button click
    $(document).on('click', '.editBtn', function() {
        var id = $(this).data('id');
        $('#userForm')[0].reset();
        $('#userModalLabel').text('Edit User');
        $('#password, #confirm_password').prop('required', false);
        
        $.ajax({
            url: 'user_actions.php?action=fetch_single',
            type: 'GET',
            data: { id: id },
            success: function(response) {
                try {
                    if (typeof response === 'string') {
                        response = JSON.parse(response);
                    }
                    $('#userId').val(response.id);
                    $('input[name="username"]').val(response.username);
                    $('input[name="email"]').val(response.email);
                    $('input[name="first_name"]').val(response.first_name);
                    $('input[name="last_name"]').val(response.last_name);
                    $('#userModal').modal('show');
                } catch (e) {
                    console.error('Error parsing response:', e);
                    alert('Error loading user data');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                alert('Error loading user data');
            }
        });
    });

    // Save User button click
    $('#saveUser').click(function() {
        var form = $('#userForm');
        
        // Validate required fields
        if (!form[0].checkValidity()) {
            form[0].reportValidity();
            return;
        }

        // Get form data
        var formData = form.serializeArray();
        var isNew = !$('#userId').val();
        
        // Validate passwords
        var password = $('#password').val();
        var confirmPassword = $('#confirm_password').val();
        
        if (isNew && !password) {
            alert('Password is required for new users');
            return;
        }
        
        if (password && password !== confirmPassword) {
            alert('Passwords do not match');
            return;
        }

        // Remove confirm password from form data
        formData = formData.filter(function(item) {
            return item.name !== 'confirm_password';
        });

        $.ajax({
            url: 'user_actions.php?action=' + (isNew ? 'add' : 'update'),
            type: 'POST',
            data: $.param(formData),
            success: function(response) {
                try {
                    if (typeof response === 'string') {
                        response = JSON.parse(response);
                    }
                    if (response.success) {
                        $('#userModal').modal('hide');
                        table.ajax.reload();
                        alert(response.message);
                    } else {
                        alert(response.message || 'Error occurred while saving user');
                    }
                } catch (e) {
                    console.error('Error parsing response:', e);
                    alert('Error occurred while saving user');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                console.error('Response:', xhr.responseText);
                alert('Error occurred while saving user');
            }
        });
    });

    // Delete User button click
    $(document).on('click', '.deleteBtn', function() {
        if (confirm('Are you sure you want to delete this user?')) {
            var id = $(this).data('id');
            $.ajax({
                url: 'user_actions.php?action=delete',
                type: 'POST',
                data: { id: id },
                success: function(response) {
                    try {
                        if (typeof response === 'string') {
                            response = JSON.parse(response);
                        }
                        alert(response.message);
                        if (response.success) {
                            table.ajax.reload();
                        }
                    } catch (e) {
                        console.error('Error parsing response:', e);
                        alert('Error occurred while deleting user');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    alert('Error occurred while deleting user');
                }
            });
        }
    });

    // Bulk Delete button click
    $('#bulkDeleteBtn').click(function() {
        var ids = [];
        $('.selectUser:checked').each(function() {
            ids.push($(this).val());
        });

        if (ids.length === 0) {
            alert('Please select users to delete');
            return;
        }

        if (confirm('Are you sure you want to delete selected users?')) {
            $.ajax({
                url: 'user_actions.php?action=bulk_delete',
                type: 'POST',
                data: { ids: ids },
                success: function(response) {
                    try {
                        if (typeof response === 'string') {
                            response = JSON.parse(response);
                        }
                        alert(response.message);
                        if (response.success) {
                            table.ajax.reload();
                            $('#selectAll').prop('checked', false);
                        }
                    } catch (e) {
                        console.error('Error parsing response:', e);
                        alert('Error occurred while deleting users');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    alert('Error occurred while deleting users');
                }
            });
        }
    });

    // Select All checkbox
    $('#selectAll').change(function() {
        $('.selectUser').prop('checked', this.checked);
    });

    // Update Select All state when individual checkboxes change
    $(document).on('change', '.selectUser', function() {
        var allChecked = $('.selectUser:not(:checked)').length === 0;
        $('#selectAll').prop('checked', allChecked);
    });
});
</script>