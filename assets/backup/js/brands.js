$(document).ready(function () {
    let table = $('#brandsTable').DataTable({
        ajax: 'brand_actions.php?action=fetch',
        columns: [
            { data: 'checkbox' },
            { data: 'id' },
            { data: 'brand_name' },
            { data: 'description' },
            { data: 'actions' }
        ]
    });

    $('#brandForm').submit(function (e) {
        e.preventDefault();
        $.post('brand_actions.php?action=save', $(this).serialize(), function () {
            $('#brandModal').modal('hide');
            table.ajax.reload();
        });
    });

    $(document).on('click', '.delete', function () {
        let id = $(this).data('id');
        $.post('brand_actions.php?action=delete', { id: id }, function () {
            table.ajax.reload();
        });
    });

    $('#bulkDelete').click(function () {
        let ids = $('.checkbox:checked').map(function () { return this.value; }).get();
        $.post('brand_actions.php?action=bulk_delete', { ids: ids }, function () {
            table.ajax.reload();
        });
    });
});
