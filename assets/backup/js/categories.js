$(document).ready(function () {
    let table = $('#categoriesTable').DataTable({
        ajax: 'category_actions.php?action=fetch',
        columns: [
            { data: 'checkbox' },
            { data: 'id' },
            { data: 'category_name' },
            { data: 'description' },
            { data: 'type' },
            { data: 'actions' }
        ]
    });

    $('#categoryForm').submit(function (e) {
        e.preventDefault();
        $.post('category_actions.php?action=save', $(this).serialize(), function () {
            $('#categoryModal').modal('hide');
            table.ajax.reload();
        });
    });

    $(document).on('click', '.delete', function () {
        let id = $(this).data('id');
        $.post('category_actions.php?action=delete', { id: id }, function () {
            table.ajax.reload();
        });
    });

    $('#bulkDelete').click(function () {
        let ids = $('.checkbox:checked').map(function () { return this.value; }).get();
        $.post('category_actions.php?action=bulk_delete', { ids: ids }, function () {
            table.ajax.reload();
        });
    });
});
