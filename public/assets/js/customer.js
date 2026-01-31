$(document).ready(function () {

    let editCustomerId = null;

    /* ================================
       DATATABLE INITIALIZATION
    ================================= */
    const customerTable = $('#customer-datatable').DataTable({
        processing: true,
        responsive: true,
        pageLength: 10,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'copy',
                text: 'Copy',
                className: 'btn btn-sm btn-outline-secondary'
            },
            {
                extend: 'excel',
                text: 'Excel',
                className: 'btn btn-sm btn-outline-secondary'
            },
            {
                extend: 'csv',
                text: 'CSV',
                className: 'btn btn-sm btn-outline-secondary'
            },
            {
                extend: 'pdf',
                text: 'PDF',
                className: 'btn btn-sm btn-outline-secondary'
            }
        ],
        ajax: {
            url: '/api/admin/customers/list',
            type: 'GET',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('access_token')
            },
            dataSrc: function (res) {
                return res.data || [];
            }
        },
        columns: [
            { data: 'name', title: 'Customer Name', defaultContent: '' },
            { data: 'address', title: 'Address', defaultContent: '' },
            { data: 'city', title: 'Billing City', defaultContent: '' },
            { data: 'state', title: 'State', defaultContent: '' },
            { data: 'zip', title: 'Zip', defaultContent: '' },
            { data: 'contact', title: 'Contact Name', defaultContent: '' },
            { data: 'email', title: 'Email', defaultContent: '' },
            { data: 'phone', title: 'Phone', defaultContent: '' },
            { data: 'fax', title: 'Fax', defaultContent: '' },
            { data: 'website', title: 'Website', defaultContent: '' },
            {
                data: 'id',
                title: 'Actions',
                orderable: false,
                searchable: false,
                render: function (id, type, row) {
                    return `
                        <div class="action-buttons">
                            <button class="btn btn-sm btn-outline-primary btn-edit" data-id="${id}" title="Edit">
                                Edit
                            </button>
                            <button class="btn btn-sm btn-outline-danger btn-delete" data-id="${id}" title="Delete">
                                Delete
                            </button>
                            <button class="btn btn-sm btn-outline-info btn-add-sites" data-id="${id}" title="Add Sites">
                                Add Sites
                            </button>
                        </div>
                    `;
                }
            }
        ],
        language: {
            search: "Search:",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            zeroRecords: "No customers found"
        }
    });

    /* ================================
       ADD CUSTOMER BUTTON
    ================================= */
    $('[data-bs-target="#customerModal"]').on('click', function () {
        editCustomerId = null;
        clearCustomerForm();
        $('.modal-title').text('Add Customer');
    });

    /* ================================
       SAVE CUSTOMER (ADD / UPDATE)
    ================================= */
    $('#save-customer-btn').on('click', function () {

        const payload = {
            name: $('#customer-name').val(),
            address: $('#customer-address').val(),
            city: $('#customer-city').val(),
            state: $('#customer-state').val(),
            zip: $('#customer-zip').val(),
            contact_name: $('#customer-contact').val(),
            email: $('#customer-email').val(),
            phone: $('#customer-phone').val(),
            fax: $('#customer-fax').val(),
            website: $('#customer-website').val()
        };

        if (!payload.name) {
            showToast('Customer name is required', 'error');
            return;
        }

        let url = '/api/admin/customers';
        let method = 'POST';

        if (editCustomerId) {
            url += '/' + editCustomerId;
            method = 'PUT';
        }

        $.ajax({
            url: url,
            type: method,
            contentType: 'application/json',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('access_token')
            },
            data: JSON.stringify(payload),
            success: function () {
                $('#customerModal').modal('hide');
                customerTable.ajax.reload(null, false);
                showToast('Customer saved successfully', 'success');
                editCustomerId = null;
            },
            error: function (xhr) {
                showToast(xhr.responseJSON?.message || 'Save failed', 'error');
            }
        });
    });

    /* ================================
       EDIT CUSTOMER
    ================================= */
    $('#customer-datatable').on('click', '.btn-edit', function () {

        editCustomerId = $(this).data('id');

        $.ajax({
            url: '/api/admin/customers/' + editCustomerId,
            type: 'GET',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('access_token')
            },
            success: function (res) {
                const c = res.data;

                $('#customer-name').val(c.name);
                $('#customer-address').val(c.billing_address || c.address);
                $('#customer-city').val(c.billing_city || c.city);
                $('#customer-state').val(c.billing_state || c.state);
                $('#customer-zip').val(c.billing_zip || c.zip);
                $('#customer-contact').val(c.contact_name);
                $('#customer-email').val(c.email);
                $('#customer-phone').val(c.phone);
                $('#customer-fax').val(c.fax);
                $('#customer-website').val(c.website);

                $('.modal-title').text('Edit Customer');
                $('#customerModal').modal('show');
            },
            error: function () {
                showToast('Failed to load customer', 'error');
            }
        });
    });

    /* ================================
       DELETE CUSTOMER
    ================================= */
    $('#customer-datatable').on('click', '.btn-delete', function () {

        const id = $(this).data('id');

        if (!confirm('Are you sure you want to delete this customer?')) return;

        $.ajax({
            url: '/api/admin/customers/' + id,
            type: 'DELETE',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('access_token')
            },
            success: function () {
                customerTable.ajax.reload(null, false);
                showToast('Customer deleted successfully', 'success');
            },
            error: function () {
                showToast('Delete failed', 'error');
            }
        });
    });

    /* ================================
       ADD SITES BUTTON
    ================================= */
    $('#customer-datatable').on('click', '.btn-add-sites', function () {
        const id = $(this).data('id');
        // TODO: Implement Add Sites functionality
        showToast('Add Sites functionality - Coming Soon', 'info');
    });

    /* ================================
       UTILITIES
    ================================= */
    function clearCustomerForm() {
        $('#customerModal input').val('');
    }

    function showToast(message, type) {
        const bgClass = type === 'success' ? 'bg-success' :
            type === 'error' ? 'bg-danger' :
                'bg-info';
        const toast = `
            <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index:9999">
                <div class="toast text-white ${bgClass} show">
                    <div class="d-flex">
                        <div class="toast-body">${message}</div>
                        <button class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                </div>
            </div>
        `;
        $('body').append(toast);
        setTimeout(() => $('.toast-container').remove(), 3000);
    }

});