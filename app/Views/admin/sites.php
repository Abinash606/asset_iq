<?= view('layout/header') ?>
<?= view('layout/sidebar') ?>
<div id="sites" class="view-section active">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0">Site Directory</h3>
        <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#siteModal">
            <i class="fa-solid fa-sitemap me-2"></i> Add Site
        </button>
    </div>

    <div class="glass-card mb-4">
        <div class="input-group">
            <span class="input-group-text bg-white"><i class="fa-solid fa-search"></i></span>
            <input type="text" class="form-control border-start-0 ps-0"
                placeholder="Search for sites by name or address...">
            <button class="btn btn-outline-primary">Search</button>
        </div>
    </div>

    <div class="glass-card mb-4">
        <label for="customer-filter" class="form-label fw-bold">Filter by Customer</label>
        <select id="customer-filter" class="form-select" style="width: 25%;">
            <option value="">All Customers</option>
            <option value="Tiger Nixon">Tiger Nixon</option>
            <option value="Garrett Winters">Garrett Winters</option>
        </select>
    </div>

    <div class="glass-card">
        <table id="sites-datatable" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>Site Name</th>
                    <th>Customer Name</th>
                    <th>Site Address</th>
                    <th>City</th>
                    <th>State</th>
                    <th>Site Contact Name</th>
                    <th>Site Phone Number</th>
                    <th>Site Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<!-- Site Details Section -->
<div id="site-details" class="view-section">
    <button class="btn btn-secondary mb-3" id="back-to-sites">Back to Sites</button>

    <div class="glass-card mb-4">
        <div class="row align-items-center">
            <div class="col-md-auto me-4">
                <img id="site-details-logo" src="https://ui-avatars.com/api/?name=S" class="rounded-circle" width="80"
                    alt="Logo">
            </div>
            <div class="col-md">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Site Name:</strong> <span id="site-details-name"></span></p>
                        <p><strong>Site ID:</strong> <span id="site-details-id"></span></p>
                        <p><strong>Site Contact Name:</strong> <span id="site-details-contact-name"></span></p>
                        <p><strong>Site Email:</strong> <span id="site-details-email"></span></p>
                        <p><strong>Site Phone Number:</strong> <span id="site-details-phone"></span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Customer Name:</strong> <span id="site-details-customer-name"></span></p>
                        <p><strong>Site Address:</strong> <span id="site-details-address"></span></p>
                        <p><strong>State:</strong> <span id="site-details-state"></span></p>
                        <p><strong>Zip code:</strong> <span id="site-details-zip"></span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <ul class="nav nav-tabs" id="site-details-tabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="equipment-tab" data-bs-toggle="tab" data-bs-target="#equipment"
                type="button" role="tab" aria-controls="equipment" aria-selected="true">Equipment</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="inspections-tab" data-bs-toggle="tab" data-bs-target="#inspections"
                type="button" role="tab" aria-controls="inspections" aria-selected="false">Inspections</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="work-orders-tab" data-bs-toggle="tab" data-bs-target="#work-orders"
                type="button" role="tab" aria-controls="work-orders" aria-selected="false">Work Orders</button>
        </li>
    </ul>

    <div class="tab-content" id="site-details-tabs-content">
        <div class="tab-pane fade show active" id="equipment" role="tabpanel" aria-labelledby="equipment-tab">
            <div class="glass-card">
                <table id="equipment-datatable" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>Asset Tag</th>
                            <th>Make</th>
                            <th>Model</th>
                            <th>Serial Number</th>
                            <th>Device Type</th>
                            <th>Location or Room</th>
                            <th>Department</th>
                            <th>Device Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

        <div class="tab-pane fade" id="inspections" role="tabpanel" aria-labelledby="inspections-tab">
            <div class="glass-card">
                <table id="inspections-datatable" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>Inspection ID</th>
                            <th>Date</th>
                            <th>Technician</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

        <div class="tab-pane fade" id="work-orders" role="tabpanel" aria-labelledby="work-orders-tab">
            <div class="glass-card">
                <table id="work-orders-datatable" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>Work Order ID</th>
                            <th>Date</th>
                            <th>Technician</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Site Add/Edit Modal -->
<div class="modal fade" id="siteModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add/Edit Site</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="site-name" class="form-label">Site Name</label>
                            <input type="text" class="form-control" id="site-name">
                        </div>
                        <div class="col-md-6">
                            <label for="site-customer-name" class="form-label">Customer Name</label>
                            <select class="form-select" id="site-customer-name">
                                <option selected>Choose...</option>
                                <option>Tiger Nixon</option>
                                <option>Garrett Winters</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="site-address" class="form-label">Site Address</label>
                            <input type="text" class="form-control" id="site-address">
                        </div>
                        <div class="col-md-6">
                            <label for="site-state" class="form-label">State</label>
                            <input type="text" class="form-control" id="site-state" placeholder="e.g., NY">
                        </div>
                        <div class="col-md-6">
                            <label for="site-zip" class="form-label">Zip code</label>
                            <input type="text" class="form-control" id="site-zip" placeholder="e.g., 10001">
                        </div>
                        <div class="col-md-6">
                            <label for="site-contact-name" class="form-label">Site Contact Name</label>
                            <input type="text" class="form-control" id="site-contact-name">
                        </div>
                        <div class="col-md-6">
                            <label for="site-phone" class="form-label">Site Phone Number</label>
                            <input type="text" class="form-control" id="site-phone">
                        </div>
                        <div class="col-12">
                            <label for="site-email" class="form-label">Site Email</label>
                            <input type="email" class="form-control" id="site-email">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

<?= view('layout/footer') ?>