$(document).ready(function () {
    let table = $('#productsTable').DataTable({
        ajax: 'product_actions.php?action=fetch',
        columns: [
            { data: 'checkbox' },
            { data: 'id' },
            { data: 'product_name' },
            { data: 'category_name' }, 
            { data: 'brand_name' },
            { data: 'price' },
            { data: 'stock_quantity' },
            { data: 'actions' }
        ]
    });

    // Fetch categories and brands for the form
    function loadCategoriesAndBrands() {
        $.getJSON('product_actions.php?action=fetch_categories_brands', function (data) {
            let categoryOptions = '<option value="">Select Category</option>';
            let brandOptions = '<option value="">Select Brand</option>';

            $.each(data.categories, function (index, category) {
                categoryOptions += `<option value="${category.id}">${category.category_name}</option>`;
            });

            $.each(data.brands, function (index, brand) {
                brandOptions += `<option value="${brand.id}">${brand.brand_name}</option>`;
            });

            $('#category_id').html(categoryOptions);
            $('#brand_id').html(brandOptions);
        });
    }

    // Open modal for adding a new product
    $('#addProductBtn').click(function () {
        $('#productForm')[0].reset();
        $('#productModal').modal('show');
        loadCategoriesAndBrands();
    });

    // Handle form submission for adding/updating a product
    $('#productForm').submit(function (e) {
        e.preventDefault();
        let action = $('#product_id').val() ? 'update' : 'add';
        $.post(`product_actions.php?action=${action}`, $(this).serialize(), function (response) {
            $('#productModal').modal('hide');
            table.ajax.reload();
        }, 'json');
    });

    // Edit product
    $(document).on('click', '.edit', function () {
        let id = $(this).data('id');
        $.getJSON(`product_actions.php?action=fetch_single&id=${id}`, function (data) {
            $('#product_id').val(data.id);
            $('#product_name').val(data.product_name);
            $('#category_id').val(data.category_id);
            $('#brand_id').val(data.brand_id);
            $('#description').val(data.description);
            $('#price').val(data.price);
            $('#stock_quantity').val(data.stock_quantity);
            $('#productModal').modal('show');
        });
    });

    // Delete a single product
    $(document).on('click', '.delete', function () {
        let id = $(this).data('id');
        if (confirm("Are you sure you want to delete this product?")) {
            $.post('product_actions.php?action=delete', { id: id }, function () {
                table.ajax.reload();
            });
        }
    });

    // Bulk delete selected products
    $('#bulkDelete').click(function () {
        let ids = $('.checkbox:checked').map(function () { return this.value; }).get();
        if (ids.length > 0 && confirm("Are you sure you want to delete selected products?")) {
            $.post('product_actions.php?action=bulk_delete', { ids: ids }, function () {
                table.ajax.reload();
            });
        }
    });
});
