// Sites Tab JavaScript

// Global variable for site inspections data
let siteInspectionsData = [];

// Initialize DataTable when document is ready
$(document).ready(function () {
    initializeSitesDataTable();
    setupEventHandlers();
});

/**
 * Initialize the Sites DataTable with sample data
 */
function initializeSitesDataTable() {
    const sitesTable = $('#sites-datatable').DataTable({
        data: [
            {
                siteId: '25557',
                siteName: 'Mercy General Hospital - Main Campus',
                customerName: 'Tiger Nixon',
                address: '123 Healthcare Blvd',
                city: 'New York',
                state: 'NY',
                zip: '10002',
                contactName: 'John Doe',
                phone: '555-1234',
                email: 'j.doe@mercy.com'
            },
            {
                siteId: '25558',
                siteName: 'Downtown Dental Group',
                customerName: 'Garrett Winters',
                address: '456 Main St',
                city: 'New York',
                state: 'NY',
                zip: '10001',
                contactName: 'Jane Smith',
                phone: '555-5678',
                email: 'j.smith@dental.com'
            }
        ],
        columns: [
            {
                data: 'siteName',
                title: 'Site Name',
                render: function (data, type, row) {
                    return '<a href="#" onclick="showSiteDetails(this)">' + (data || '') + '</a>';
                }
            },
            { data: 'customerName', title: 'Customer Name' },
            { data: 'address', title: 'Site Address' },
            { data: 'city', title: 'City' },
            { data: 'state', title: 'State' },
            { data: 'contactName', title: 'Site Contact Name' },
            { data: 'phone', title: 'Site Phone Number' },
            { data: 'email', title: 'Site Email' },
            {
                title: 'Actions',
                orderable: false,
                searchable: false,
                defaultContent: "<button class='btn btn-sm btn-outline-secondary btn-edit-site' data-bs-toggle='modal' data-bs-target='#siteModal'>Edit</button> <button class='btn btn-sm btn-outline-danger btn-delete-site'>Delete</button>"
            }
        ],
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
    });

    return sitesTable;
}

/**
 * Set up all event handlers for the Sites tab
 */
function setupEventHandlers() {
    // Edit site button handler
    $('#sites-datatable tbody').on('click', '.btn-edit-site', function () {
        const table = $('#sites-datatable').DataTable();
        const data = table.row($(this).parents('tr')).data();

        $('#site-name').val(data.siteName || '');
        $('#site-customer-name').val(data.customerName || '');
        $('#site-address').val(data.address || '');
        $('#site-state').val(data.state || '');
        $('#site-zip').val(data.zip || '');
        $('#site-contact-name').val(data.contactName || '');
        $('#site-phone').val(data.phone || '');
        $('#site-email').val(data.email || '');
    });

    // Delete site button handler
    $('#sites-datatable tbody').on('click', '.btn-delete-site', function () {
        if (confirm('Are you sure you want to delete this site?')) {
            const table = $('#sites-datatable').DataTable();
            table.row($(this).parents('tr')).remove().draw();
        }
    });

    // Customer filter change handler
    $('#customer-filter').on('change', function () {
        const table = $('#sites-datatable').DataTable();
        table.column(1).search(this.value).draw();
    });

    // Back to sites button handler
    document.getElementById('back-to-sites').addEventListener('click', function () {
        $('.view-section').removeClass('active').hide();
        $('#sites').addClass('active').show();
    });
}

/**
 * Show site details when a site name is clicked
 * @param {HTMLElement} element - The clicked element
 */
function showSiteDetails(element) {
    const table = $('#sites-datatable').DataTable();
    const row = $(element).closest('tr');
    const data = table.row(row).data();

    // Populate site details
    document.getElementById('site-details-name').textContent = data.siteName || '';
    document.getElementById('site-details-id').textContent = data.siteId || '25558';
    document.getElementById('site-details-contact-name').textContent = data.contactName || '';
    document.getElementById('site-details-email').textContent = data.email || '';
    document.getElementById('site-details-phone').textContent = data.phone || '';
    document.getElementById('site-details-customer-name').textContent = data.customerName || '';
    document.getElementById('site-details-address').textContent = (data.address || '') + (data.city ? ', ' + data.city : '');
    document.getElementById('site-details-state').textContent = data.state || '';
    document.getElementById('site-details-zip').textContent = data.zip || '';
    document.getElementById('site-details-logo').src = "https://ui-avatars.com/api/?name=" + encodeURIComponent(data.siteName || 'S');

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

    // Initialize Equipment DataTable
    initializeEquipmentDataTable();

    // Initialize Inspections DataTable
    initializeInspectionsDataTable();

    // Initialize Work Orders DataTable
    initializeWorkOrdersDataTable();
}

/**
 * Initialize the Equipment DataTable for a specific site
 */
function initializeEquipmentDataTable() {
    $('#equipment-datatable').DataTable({
        data: [
            ['A-123', 'Midmark', 'M9 Autoclave', 'SN12345', 'Autoclave', 'Room 101', 'Surgery', 'Active'],
            ['A-124', 'Health O Meter', '500KL Scale', 'SN67890', 'Scale', 'Hallway A', 'General', 'Active']
        ],
        columns: [
            { title: "Asset Tag" },
            { title: "Make" },
            { title: "Model" },
            { title: "Serial Number" },
            { title: "Device Type" },
            { title: "Location or Room" },
            { title: "Department" },
            { title: "Device Status" },
            {
                title: "Actions",
                defaultContent: "<button class='btn btn-sm btn-info'>View</button> <button class='btn btn-sm btn-secondary'>Edit</button>"
            }
        ],
        dom: 'Bfrtip',
        buttons: ['copy', 'excel', 'pdf']
    });
}

/**
 * Initialize the Inspections DataTable for a specific site
 */
function initializeInspectionsDataTable() {
    // Initialize the inspections data array with placeholders
    siteInspectionsData = [];
    const initialInspectionData = [
        ["INSP-001", "2024-01-15", "John Doe", "Pass"],
        ["INSP-002", "2024-02-20", "Jane Smith", "Fail"]
    ];

    // Populate placeholders for existing inspections
    initialInspectionData.forEach(function () {
        siteInspectionsData.push(null);
    });

    $('#inspections-datatable').DataTable({
        data: initialInspectionData,
        columns: [
            { title: "Inspection ID" },
            { title: "Date" },
            { title: "Technician" },
            { title: "Status" },
            {
                title: "Actions",
                defaultContent: "<button class='btn btn-sm btn-info btn-view-inspection'>View</button> <button class='btn btn-sm btn-secondary btn-edit-inspection'>Edit</button> <button class='btn btn-sm btn-danger btn-delete-inspection'>Delete</button>"
            }
        ],
        dom: 'Bfrtip',
        buttons: ['copy', 'excel', 'pdf']
    });

    // Add event handlers for inspection buttons
    $('#inspections-datatable tbody').on('click', '.btn-view-inspection', function () {
        alert('View inspection functionality would be implemented here');
    });

    $('#inspections-datatable tbody').on('click', '.btn-edit-inspection', function () {
        alert('Edit inspection functionality would be implemented here');
    });

    $('#inspections-datatable tbody').on('click', '.btn-delete-inspection', function () {
        if (confirm('Are you sure you want to delete this inspection?')) {
            const table = $('#inspections-datatable').DataTable();
            table.row($(this).parents('tr')).remove().draw();
        }
    });
}

/**
 * Initialize the Work Orders DataTable for a specific site
 */
function initializeWorkOrdersDataTable() {
    $('#work-orders-datatable').DataTable({
        data: [
            ["WO-001", "2024-03-01", "John Doe", "Completed"],
            ["WO-002", "2024-03-05", "Jane Smith", "In Progress"]
        ],
        columns: [
            { title: "Work Order ID" },
            { title: "Date" },
            { title: "Technician" },
            { title: "Status" },
            {
                title: "Actions",
                defaultContent: "<button class='btn btn-sm btn-info'>View</button> <button class='btn btn-sm btn-secondary'>Edit</button> <button class='btn btn-sm btn-danger'>Delete</button>"
            }
        ],
        dom: 'Bfrtip',
        buttons: ['copy', 'excel', 'pdf']
    });

    // Add event handlers for work order buttons
    $('#work-orders-datatable tbody').on('click', '.btn-info', function () {
        alert('View work order functionality would be implemented here');
    });

    $('#work-orders-datatable tbody').on('click', '.btn-secondary', function () {
        alert('Edit work order functionality would be implemented here');
    });

    $('#work-orders-datatable tbody').on('click', '.btn-danger', function () {
        if (confirm('Are you sure you want to delete this work order?')) {
            const table = $('#work-orders-datatable').DataTable();
            table.row($(this).parents('tr')).remove().draw();
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

        const sitesTable = $('#sites-datatable').DataTable();
        const nodes = sitesTable.rows({ search: 'applied' }).nodes();

        if (nodes && nodes.length) {
            const anchor = $(nodes[0]).find('a')[0];
            if (anchor) {
                showSiteDetails(anchor);
                return;
            }
        }

        alert('No sites found for ' + customerName);
    } catch (e) {
        console.error(e);
    }
}