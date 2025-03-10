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
        <div class="row mt-3">
            <div class="col-md-4 mb-3">
                <input type="text" id="searchInput" placeholder="Search by username/email..." class="form-control">
            </div>
            <div class="col-md-4 mb-3">
                <input type="text" id="startDate" placeholder="Start Date" class="form-control">
            </div>
            <div class="col-md-4 mb-3">
                <input type="text" id="endDate" placeholder="End Date" class="form-control">
            </div>
        </div>
        <div class="text-right">
            <button id="clearFilters" class="btn btn-info btn-icon">
                <i class="bi bi-x-circle"></i>
                <span class="btn-text">Clear Filters</span>
            </button>
        </div>
        <br>
        <table id="usersTable" class="table table-striped">
            <thead>
                <tr>
                    <th><input type="checkbox" id="selectAll"></th>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>

    <!-- Add/Edit User Modal -->
    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel">Add/Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="userForm">
                        <input type="hidden" name="id" id="userId">
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" id="passwordField">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">First Name</label>
                            <input type="text" class="form-control" name="first_name">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Last Name</label>
                            <input type="text" class="form-control" name="last_name">
                        </div>
                        <button type="submit" class="btn btn-success btn-icon">
                            <i class="bi bi-save"></i>
                            <span class="btn-text">Save</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php include "pages/footer.php"; ?>
</div>

<script>
    $(document).ready(function() {
        var table = $('#usersTable').DataTable({
            "ajax": "user_actions.php?action=fetch",
            "pageLength": 10,
            "lengthMenu": [10, 50, 100, -1],
            "columns": [{
                    "data": "id",
                    render: function(data) {
                        return '<input type="checkbox" class="selectUser" value="' + data + '">';
                    }
                },
                {"data": "id"},
                {"data": "username"},
                {"data": "email"},
                {"data": "first_name"},
                {"data": "last_name"},
                {"data": "created_at"},
                {
                    "data": "id",
                    render: function(data) {
                        return '<button class="btn btn-warning btn-sm editBtn btn-icon" data-id="' + data + '"><i class="bi bi-pencil"></i><span class="btn-text"></span></button> ' +
                            '<button class="btn btn-danger btn-sm deleteBtn btn-icon" data-id="' + data + '"><i class="bi bi-trash"></i><span class="btn-text"></span></button>';
                    }
                }
            ],
            dom: 'Bfrtip',
            buttons: [{
                    extend: 'pdf',
                    text: '<i class="bi bi-file-pdf"></i><span class="btn-text"> Export PDF</span>',
                    className: 'btn-icon'
                },
                {
                    extend: 'csv',
                    text: '<i class="bi bi-file-earmark-spreadsheet"></i><span class="btn-text"> Export CSV</span>',
                    className: 'btn-icon'
                },
                {
                    extend: 'print',
                    text: '<i class="bi bi-printer"></i><span class="btn-text"> Print</span>',
                    className: 'btn-icon'
                }
            ]
        });

        $('#addUserBtn').click(function() {
            $('#userModalLabel').text('Add User');
            $('#userForm')[0].reset();
            $('#userId').val('');
            $('#passwordField').prop('required', true);
            $('#userModal').modal('show');
        });

        $(document).on('click', '.editBtn', function() {
            let id = $(this).data('id');
            $.get('user_actions.php?action=fetch_single&id=' + id, function(response) {
                let user = JSON.parse(response);
                $('#userId').val(user.id);
                $('input[name="username"]').val(user.username);
                $('input[name="email"]').val(user.email);
                $('input[name="first_name"]').val(user.first_name);
                $('input[name="last_name"]').val(user.last_name);
                $('#passwordField').prop('required', false);
                $('#userModalLabel').text('Edit User');
                $('#userModal').modal('show');
            });
        });

        $('#userForm').submit(function(e) {
            e.preventDefault();
            let action = $('#userId').val() ? 'update' : 'add';
            $.post('user_actions.php?action=' + action, $(this).serialize(), function(response) {
                response = JSON.parse(response);
                if (response.success) {
                    $('#userModal').modal('hide');
                    table.ajax.reload();
                } else {
                    alert(response.message);
                }
            });
        });

        $('#bulkDeleteBtn').click(function() {
            var ids = [];
            $('.selectUser:checked').each(function() {
                ids.push($(this).val());
            });
            if (ids.length === 0) {
                alert("Please select users to delete.");
                return;
            }
            if (confirm("Are you sure you want to delete selected users?")) {
                $.post("user_actions.php?action=bulk_delete", {ids: ids}, function(response) {
                    alert(response.message);
                    table.ajax.reload();
                }, "json");
            }
        });

        $(document).on('click', '.deleteBtn', function() {
            let id = $(this).data('id');
            if (confirm('Are you sure you want to delete this user?')) {
                $.post('user_actions.php?action=bulk_delete', {ids: [id]}, function(response) {
                    response = JSON.parse(response);
                    alert(response.message);
                    table.ajax.reload();
                });
            }
        });

        $('#selectAll').on('change', function() {
            $('.selectUser').prop('checked', this.checked);
        });

        $('#searchInput, #startDate, #endDate').on('input change', function() {
            var search = $('#searchInput').val();
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();
            table.ajax.url("user_actions.php?action=fetch&search=" + search + "&startDate=" + startDate + "&endDate=" + endDate).load();
        });

        $('#clearFilters').click(function() {
            $('#searchInput').val('');
            $('#startDate').val('');
            $('#endDate').val('');
            table.ajax.url("user_actions.php?action=fetch").load();
        });

        flatpickr('#startDate', {dateFormat: 'Y-m-d'});
        flatpickr('#endDate', {dateFormat: 'Y-m-d'});
    });
</script>