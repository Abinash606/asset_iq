// Sites Tab JavaScript - Complete Dynamic Version with Fixed Customer Dropdown

// Global variables
let siteInspectionsData = [];
let sitesTable = null;
let currentCustomerId = null;
// Initialize DataTable when document is ready
$(document).ready(function () {
    initializeSitesDataTable();
    setupEventHandlers();
    loadCustomerFilter();
});

/**
 * Initialize the Sites DataTable with API data
 */
function initializeSitesDataTable() {
    sitesTable = $('#sites-datatable').DataTable({
        processing: true,
        serverSide: false,
        pageLength: 10,
        ajax: {
            url: '/api/admin/sites/list',
            type: 'GET',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('access_token')
            },
            data: function (d) {
                // If filtering by customer, add customer_id parameter
                if (currentCustomerId) {
                    d.customer_id = currentCustomerId;
                }
            },
            dataSrc: function (response) {
                console.log('Sites response:', response); // Debug log
                if (response.success) {
                    return response.data || [];
                }
                showToast('Failed to load sites', 'error');
                return [];
            },
            error: function (xhr, error, thrown) {
                console.error('Sites load error:', xhr, error, thrown);
                showToast('Error loading sites: ' + thrown, 'error');
            }
        },
        columns: [
            {
                data: 'name',
                title: 'Site Name',
                defaultContent: '',
                render: function (data, type, row) {
                    return '<a href="#" class="site-name-link" data-site-id="' + row.id + '">' + (data || '') + '</a>';
                }
            },
            { 
                data: 'customer_name',
                title: 'Customer Name',
                defaultContent: 'N/A'
            },
            { data: 'address', title: 'Site Address', defaultContent: '' },
            { data: 'city', title: 'City', defaultContent: '' },
            { data: 'state', title: 'State', defaultContent: '' },
            { data: 'contact_name', title: 'Site Contact Name', defaultContent: '' },
            { data: 'phone', title: 'Site Phone Number', defaultContent: '' },
            { data: 'email', title: 'Site Email', defaultContent: '' },
            {
                data: 'id',
                title: 'Actions',
                orderable: false,
                searchable: false,
                render: function (data, type, row) {
                    return `
                        <div class="action-buttons">
                            <button class='btn btn-sm btn-outline-primary btn-edit-site' data-id="${data}" data-bs-toggle='modal' data-bs-target='#siteModal' title="Edit">
                                Edit
                            </button> 
                            <button class='btn btn-sm btn-outline-danger btn-delete-site' data-id="${data}" title="Delete">
                                Delete
                            </button>
                        </div>
                    `;
                }
            }
        ],
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
        language: {
            search: "Search:",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            zeroRecords: "No sites found",
            processing: "Loading sites..."
        }
    });

    return sitesTable;
}

/**
 * Load customer filter dropdown
 */
function loadCustomerFilter() {
    $.ajax({
        url: '/api/admin/customers/list',
        type: 'GET',
        headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('access_token')
        },
        success: function (response) {
            console.log('Customer filter response:', response); // Debug log
            if (response.success && response.data) {
                const customers = response.data;
                const filterDropdown = $('#customer-filter');

                // Clear existing options except "All Customers"
                filterDropdown.find('option:not(:first)').remove();

                // Add customer options
                customers.forEach(function (customer) {
                    filterDropdown.append(`<option value="${customer.id}">${customer.name}</option>`);
                });

                console.log('Added ' + customers.length + ' customers to filter'); // Debug log
            } else {
                console.error('Invalid response format:', response);
                showToast('Failed to load customers for filter', 'error');
            }
        },
        error: function (xhr, status, error) {
            console.error('Failed to load customers for filter', { xhr, status, error });
            showToast('Error loading customers: ' + error, 'error');
        }
    });
}

/**
 * Set up all event handlers for the Sites tab
 */
function setupEventHandlers() {
    // Add Site button handler (clear form for new site)
    $('#add-site-btn, [data-bs-target="#siteModal"]:not(.btn-edit-site)').on('click', function () {
        clearSiteForm();
        $('#siteModal .modal-title').text('Add Site');
        $('#siteModal').removeData('site-id');
        loadCustomersDropdown();
    });

    // Site name link click handler
    $('#sites-datatable tbody').on('click', '.site-name-link', function (e) {
        e.preventDefault();
        const siteId = $(this).data('site-id');
        loadSiteDetails(siteId);
    });

    // Edit site button handler
    $('#sites-datatable tbody').on('click', '.btn-edit-site', function (e) {
        e.stopPropagation();
        const siteId = $(this).data('id');
        loadSiteForEdit(siteId);
    });

    // Save site button handler
    $('#save-site-btn').on('click', function () {
        saveSite();
    });

    // Delete site button handler
    $('#sites-datatable tbody').on('click', '.btn-delete-site', function (e) {
        e.stopPropagation();
        const siteId = $(this).data('id');
        deleteSite(siteId);
    });

    // Customer filter change handler
    $('#customer-filter').on('change', function () {
        currentCustomerId = this.value || null;
        console.log('Filter changed to customer ID:', currentCustomerId); // Debug log
        sitesTable.ajax.reload();
    });

    // Back to sites button handler
    $('#back-to-sites').on('click', function () {
        $('.view-section').removeClass('active').hide();
        $('#sites').addClass('active').show();
    });
}

/**
 * Load customers into dropdown (FIXED VERSION)
 */
function loadCustomersDropdown() {
    const dropdown = $('#site-customer-name');

    // Show loading state
    dropdown.prop('disabled', true);
    dropdown.html('<option value="">Loading customers...</option>');

    $.ajax({
        url: '/api/admin/customers/list',
        type: 'GET',
        headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('access_token')
        },
        success: function (response) {
            console.log('Customers dropdown response:', response); // Debug log

            // Clear dropdown
            dropdown.empty();

            // Check if response is valid
            if (!response) {
                console.error('Empty response from customers API');
                dropdown.html('<option value="">Error loading customers</option>');
                showToast('Failed to load customers - empty response', 'error');
                return;
            }

            if (!response.success) {
                console.error('API returned success=false:', response);
                dropdown.html('<option value="">Error loading customers</option>');
                showToast('Failed to load customers - ' + (response.message || 'unknown error'), 'error');
                return;
            }

            if (!response.data || !Array.isArray(response.data)) {
                console.error('Invalid data format:', response);
                dropdown.html('<option value="">Error loading customers</option>');
                showToast('Failed to load customers - invalid data format', 'error');
                return;
            }

            const customers = response.data;

            if (customers.length === 0) {
                dropdown.html('<option value="">No customers available</option>');
                showToast('No customers found. Please add customers first.', 'warning');
                return;
            }

            // Add default option
            dropdown.append('<option value="">Choose...</option>');

            // Add customer options
            customers.forEach(function (customer) {
                if (customer && customer.id && customer.name) {
                    dropdown.append(`<option value="${customer.id}">${customer.name}</option>`);
                } else {
                    console.warn('Invalid customer object:', customer);
                }
            });

            console.log('Successfully added ' + customers.length + ' customers to dropdown'); // Debug log

            // If currentCustomerId is set (from "Add Sites" button in customer page)
            if (currentCustomerId) {
                dropdown.val(currentCustomerId);
                dropdown.prop('disabled', true); // Disable if coming from customer page
            } else {
                dropdown.prop('disabled', false);
            }
        },
        error: function (xhr, status, error) {
            console.error('AJAX error loading customers:', { xhr, status, error });
            console.error('Response text:', xhr.responseText);
            console.error('Status code:', xhr.status);

            dropdown.empty();
            dropdown.html('<option value="">Error loading customers</option>');

            let errorMessage = 'Failed to load customers';
            if (xhr.status === 401) {
                errorMessage = 'Authentication error - please login again';
            } else if (xhr.status === 403) {
                errorMessage = 'Access denied';
            } else if (xhr.status === 404) {
                errorMessage = 'Customer API endpoint not found';
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }

            showToast(errorMessage, 'error');
            dropdown.prop('disabled', false);
        }
    });
}

/**
 * Load site data for editing
 */
function loadSiteForEdit(siteId) {
    $.ajax({
        url: `/api/admin/sites/${siteId}`,
        type: 'GET',
        headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('access_token')
        },
        success: function (response) {
            if (response.success && response.data) {
                const site = response.data;

                // Store site ID for update
                $('#siteModal').data('site-id', siteId);

                // Load customers dropdown first
                loadCustomersDropdown();

                // Wait a bit for dropdown to populate, then set values
                setTimeout(() => {
                    // Populate form fields
                    $('#site-name').val(site.name || '');
                    $('#site-customer-name').val(site.customer_id || '');
                    $('#site-address').val(site.address || '');
                    $('#site-city').val(site.city || '');
                    $('#site-state').val(site.state || '');
                    $('#site-zip').val(site.zip || '');
                    $('#site-contact-name').val(site.contact_name || '');
                    $('#site-phone').val(site.phone || '');
                    $('#site-email').val(site.email || '');

                    // Disable customer dropdown when editing
                    $('#site-customer-name').prop('disabled', true);
                }, 500); // Increased timeout to ensure dropdown is loaded

                // Update modal title
                $('#siteModal .modal-title').text('Edit Site');
            } else {
                showToast('Failed to load site data', 'error');
            }
        },
        error: function (xhr) {
            showToast(xhr.responseJSON?.message || 'Failed to load site', 'error');
        }
    });
}

/**
 * Save site (create or update)
 */
function saveSite() {
    const siteId = $('#siteModal').data('site-id');
    const isEdit = !!siteId;

    const payload = {
        name: $('#site-name').val().trim(),
        address: $('#site-address').val().trim(),
        city: $('#site-city').val().trim(),
        state: $('#site-state').val().trim(),
        zip: $('#site-zip').val().trim(),
        contact_name: $('#site-contact-name').val().trim(),
        phone: $('#site-phone').val().trim(),
        email: $('#site-email').val().trim()
    };

    // Get customer_id from dropdown for new sites
    if (!isEdit) {
        const customerId = $('#site-customer-name').val();
        if (!customerId) {
            showToast('Please select a customer', 'error');
            return;
        }
        payload.customer_id = customerId;
    }

    // Validation
    if (!payload.name) {
        showToast('Site name is required', 'error');
        return;
    }

    if (!payload.address) {
        showToast('Site address is required', 'error');
        return;
    }

    const url = isEdit ? `/api/admin/sites/${siteId}` : '/api/admin/sites';
    const method = isEdit ? 'PUT' : 'POST';

    $.ajax({
        url: url,
        type: method,
        contentType: 'application/json',
        headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('access_token')
        },
        data: JSON.stringify(payload),
        success: function (response) {
            if (response.success) {
                $('#siteModal').modal('hide');
                sitesTable.ajax.reload(null, false);
                showToast(isEdit ? 'Site updated successfully' : 'Site created successfully', 'success');

                // Clear modal data
                $('#siteModal').removeData('site-id');
                clearSiteForm();
                currentCustomerId = null; // Reset after adding from customer page
            } else {
                showToast(response.message || 'Failed to save site', 'error');
            }
        },
        error: function (xhr) {
            console.error('Save site error:', xhr);
            showToast(xhr.responseJSON?.message || 'Failed to save site', 'error');
        }
    });
}

/**
 * Delete site
 */
function deleteSite(siteId) {
    if (!confirm('Are you sure you want to delete this site?')) {
        return;
    }

    $.ajax({
        url: `/api/admin/sites/${siteId}`,
        type: 'DELETE',
        headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('access_token')
        },
        success: function (response) {
            if (response.success) {
                sitesTable.ajax.reload(null, false);
                showToast('Site deleted successfully', 'success');
            } else {
                showToast(response.message || 'Failed to delete site', 'error');
            }
        },
        error: function (xhr) {
            showToast(xhr.responseJSON?.message || 'Failed to delete site', 'error');
        }
    });
}

/**
 * Load and display site details
 */
function loadSiteDetails(siteId) {
    $.ajax({
        url: `/api/admin/sites/${siteId}`,
        type: 'GET',
        headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('access_token')
        },
        success: function (response) {
            if (response.success && response.data) {
                const site = response.data;
                displaySiteDetails(site);
            } else {
                showToast('Failed to load site details', 'error');
            }
        },
        error: function (xhr) {
            showToast(xhr.responseJSON?.message || 'Failed to load site details', 'error');
        }
    });
}

/**
 * Display site details in the detail view
 */
function displaySiteDetails(site) {
    // Populate site details
    document.getElementById('site-details-name').textContent = site.name || '-';
    document.getElementById('site-details-id').textContent = site.site_identifier || site.id || '-';
    document.getElementById('site-details-contact-name').textContent = site.contact_name || '-';
    document.getElementById('site-details-email').textContent = site.email || '-';
    document.getElementById('site-details-phone').textContent = site.phone || '-';
    document.getElementById('site-details-customer-name').textContent = site.customer_name || 'N/A';
    document.getElementById('site-details-address').textContent = (site.address || '') + (site.city ? ', ' + site.city : '');
    document.getElementById('site-details-state').textContent = site.state || '-';
    document.getElementById('site-details-zip').textContent = site.zip || '-';

    // Set logo (using UI Avatars or actual logo if available)
    const logoElement = document.getElementById('site-details-logo');
    if (logoElement) {
        logoElement.src = site.logo || "https://ui-avatars.com/api/?name=" + encodeURIComponent(site.name || 'S');
    }

    // Switch to site details view
    $('.view-section').removeClass('active').hide();
    $('#site-details').addClass('active').show();

    // Programmatically select the Equipment tab to ensure it's the default
    const equipmentTab = document.getElementById('equipment-tab');
    if (equipmentTab) {
        const tab = new bootstrap.Tab(equipmentTab);
        tab.show();
    }

    // Destroy existing tables if they exist to prevent reinitialization errors
    if ($.fn.DataTable.isDataTable('#inspections-datatable')) {
        $('#inspections-datatable').DataTable().destroy();
    }
    if ($.fn.DataTable.isDataTable('#work-orders-datatable')) {
        $('#work-orders-datatable').DataTable().destroy();
    }
    if ($.fn.DataTable.isDataTable('#equipment-datatable')) {
        $('#equipment-datatable').DataTable().destroy();
    }

    // Initialize Equipment DataTable with site ID
    initializeEquipmentDataTable(site.id);

    // Initialize Inspections DataTable with site ID
    initializeInspectionsDataTable(site.id);

    // Initialize Work Orders DataTable with site ID
    initializeWorkOrdersDataTable(site.id);
}

/**
 * Initialize the Equipment DataTable for a specific site
 */
function initializeEquipmentDataTable(siteId) {
    $('#equipment-datatable').DataTable({
        processing: true,
        ajax: {
            url: `/api/admin/equipment/list?site_id=${siteId}`,
            type: 'GET',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('access_token')
            },
            dataSrc: function (response) {
                if (response && response.success) {
                    return response.data || [];
                }
                return [];
            },
            error: function () {
                // If endpoint doesn't exist yet, use empty data
                console.log('Equipment endpoint not available yet');
                return [];
            }
        },
        columns: [
            { data: 'asset_tag', title: "Asset Tag", defaultContent: '-' },
            { data: 'make', title: "Make", defaultContent: '-' },
            { data: 'model', title: "Model", defaultContent: '-' },
            { data: 'serial_number', title: "Serial Number", defaultContent: '-' },
            { data: 'device_type', title: "Device Type", defaultContent: '-' },
            { data: 'location', title: "Location or Room", defaultContent: '-' },
            { data: 'department', title: "Department", defaultContent: '-' },
            { data: 'status', title: "Device Status", defaultContent: 'Active' },
            {
                data: 'id',
                title: "Actions",
                defaultContent: "-",
                render: function (data) {
                    if (!data) return '-';
                    return `
                        <button class='btn btn-sm btn-info btn-view-equipment' data-id="${data}">View</button> 
                        <button class='btn btn-sm btn-secondary btn-edit-equipment' data-id="${data}">Edit</button>
                    `;
                }
            }
        ],
        dom: 'Bfrtip',
        buttons: ['copy', 'excel', 'pdf'],
        language: {
            zeroRecords: "No equipment found for this site",
            emptyTable: "No equipment available"
        }
    });
}

/**
 * Initialize the Inspections DataTable for a specific site
 */
function initializeInspectionsDataTable(siteId) {
    siteInspectionsData = [];

    $('#inspections-datatable').DataTable({
        processing: true,
        ajax: {
            url: `/api/admin/inspections/list?site_id=${siteId}`,
            type: 'GET',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('access_token')
            },
            dataSrc: function (response) {
                if (response && response.success) {
                    return response.data || [];
                }
                return [];
            },
            error: function () {
                // If endpoint doesn't exist yet, use empty data
                console.log('Inspections endpoint not available yet');
                return [];
            }
        },
        columns: [
            { data: 'inspection_id', title: "Inspection ID", defaultContent: '-' },
            { 
                data: 'date',
                title: "Date",
                defaultContent: '-',
                render: function (data) {
                    return data ? new Date(data).toLocaleDateString() : '-';
                }
            },
            { data: 'technician_name', title: "Technician", defaultContent: '-' },
            { data: 'status', title: "Status", defaultContent: '-' },
            {
                data: 'id',
                title: "Actions",
                defaultContent: "-",
                render: function (data) {
                    if (!data) return '-';
                    return `
                        <button class='btn btn-sm btn-info btn-view-inspection' data-id="${data}">View</button> 
                        <button class='btn btn-sm btn-secondary btn-edit-inspection' data-id="${data}">Edit</button> 
                        <button class='btn btn-sm btn-danger btn-delete-inspection' data-id="${data}">Delete</button>
                    `;
                }
            }
        ],
        dom: 'Bfrtip',
        buttons: ['copy', 'excel', 'pdf'],
        language: {
            zeroRecords: "No inspections found for this site",
            emptyTable: "No inspections available"
        }
    });

    // Add event handlers for inspection buttons
    $('#inspections-datatable tbody').on('click', '.btn-view-inspection', function () {
        const id = $(this).data('id');
        showToast('View inspection ' + id + ' - functionality to be implemented', 'info');
    });

    $('#inspections-datatable tbody').on('click', '.btn-edit-inspection', function () {
        const id = $(this).data('id');
        showToast('Edit inspection ' + id + ' - functionality to be implemented', 'info');
    });

    $('#inspections-datatable tbody').on('click', '.btn-delete-inspection', function () {
        const id = $(this).data('id');
        if (confirm('Are you sure you want to delete this inspection?')) {
            showToast('Delete inspection ' + id + ' - API call to be implemented', 'info');
        }
    });
}

/**
 * Initialize the Work Orders DataTable for a specific site
 */
function initializeWorkOrdersDataTable(siteId) {
    $('#work-orders-datatable').DataTable({
        processing: true,
        ajax: {
            url: `/api/admin/work-orders/list?site_id=${siteId}`,
            type: 'GET',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('access_token')
            },
            dataSrc: function (response) {
                if (response && response.success) {
                    return response.data || [];
                }
                return [];
            },
            error: function () {
                // If endpoint doesn't exist yet, use empty data
                console.log('Work orders endpoint not available yet');
                return [];
            }
        },
        columns: [
            { data: 'work_order_id', title: "Work Order ID", defaultContent: '-' },
            { 
                data: 'date',
                title: "Date",
                defaultContent: '-',
                render: function (data) {
                    return data ? new Date(data).toLocaleDateString() : '-';
                }
            },
            { data: 'technician_name', title: "Technician", defaultContent: '-' },
            { data: 'status', title: "Status", defaultContent: '-' },
            {
                data: 'id',
                title: "Actions",
                defaultContent: "-",
                render: function (data) {
                    if (!data) return '-';
                    return `
                        <button class='btn btn-sm btn-info btn-view-wo' data-id="${data}">View</button> 
                        <button class='btn btn-sm btn-secondary btn-edit-wo' data-id="${data}">Edit</button> 
                        <button class='btn btn-sm btn-danger btn-delete-wo' data-id="${data}">Delete</button>
                    `;
                }
            }
        ],
        dom: 'Bfrtip',
        buttons: ['copy', 'excel', 'pdf'],
        language: {
            zeroRecords: "No work orders found for this site",
            emptyTable: "No work orders available"
        }
    });

    // Add event handlers for work order buttons
    $('#work-orders-datatable tbody').on('click', '.btn-view-wo', function () {
        const id = $(this).data('id');
        showToast('View work order ' + id + ' - functionality to be implemented', 'info');
    });

    $('#work-orders-datatable tbody').on('click', '.btn-edit-wo', function () {
        const id = $(this).data('id');
        showToast('Edit work order ' + id + ' - functionality to be implemented', 'info');
    });

    $('#work-orders-datatable tbody').on('click', '.btn-delete-wo', function () {
        const id = $(this).data('id');
        if (confirm('Are you sure you want to delete this work order?')) {
            showToast('Delete work order ' + id + ' - API call to be implemented', 'info');
        }
    });
}

/**
 * Open the first site for a specific customer
 * @param {string} customerName - The customer name to filter by
 */
function openFirstSiteForCustomer(customerName) {
    try {
        // Apply filter dropdown
        const filter = document.getElementById('customer-filter');
        if (filter) {
            filter.value = customerName;
            $('#customer-filter').trigger('change');
        }

        // Wait for table to reload, then get first site
        setTimeout(() => {
            const nodes = sitesTable.rows({ search: 'applied' }).nodes();

            if (nodes && nodes.length) {
                const anchor = $(nodes[0]).find('.site-name-link')[0];
                if (anchor) {
                    const siteId = $(anchor).data('site-id');
                    loadSiteDetails(siteId);
                    return;
                }
            }

            showToast('No sites found for ' + customerName, 'info');
        }, 500);
    } catch (e) {
        console.error('Error in openFirstSiteForCustomer:', e);
        showToast('Error loading sites', 'error');
    }
}

/**
 * Clear site form
 */
function clearSiteForm() {
    $('#siteModal input').val('');
    $('#site-customer-name').val('').prop('disabled', false);
    $('#siteModal').removeData('site-id');
    $('#siteModal .modal-title').text('Add Site');
}

/**
 * Show toast notification
 */
function showToast(message, type) {
    const bgClass = type === 'success' ? 'bg-success' :
        type === 'error' ? 'bg-danger' :
            type === 'warning' ? 'bg-warning' :
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