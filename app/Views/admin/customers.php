<?= view('layout/header') ?>
<?= view('layout/sidebar') ?>
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0">Customer Directory</h3>
        <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#customerModal">
            <i class="fa-solid fa-building me-2"></i> Add Customer
        </button>
    </div>

    <div class="glass-card mb-4">
        <div class="input-group">
            <span class="input-group-text bg-white"><i class="fa-solid fa-search"></i></span>
            <input type="text" class="form-control border-start-0 ps-0"
                placeholder="Search for customers by name or address...">
            <button class="btn btn-outline-primary">Search</button>
        </div>
    </div>

    <div class="glass-card">
        <table id="customer-datatable" class="display table table-striped" style="width:100%">
            <thead>
                <tr>
                    <th>Customer Name</th>
                    <th>Address</th>
                    <th>Billing City</th>
                    <th>State</th>
                    <th>Zip</th>
                    <th>Contact Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Fax</th>
                    <th>Website</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<!-- Customer Modal -->
<div class="modal fade" id="customerModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add/Edit Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="customer-name" class="form-label">Customer Name</label>
                            <input type="text" class="form-control" id="customer-name">
                        </div>
                        <div class="col-md-6">
                            <label for="customer-address" class="form-label">Customer Address</label>
                            <input type="text" class="form-control" id="customer-address">
                        </div>
                        <div class="col-md-6">
                            <label for="customer-city" class="form-label">Billing City</label>
                            <input type="text" class="form-control" id="customer-city">
                        </div>
                        <div class="col-md-4">
                            <label for="customer-state" class="form-label">State</label>
                            <input type="text" class="form-control" id="customer-state">
                        </div>
                        <div class="col-md-2">
                            <label for="customer-zip" class="form-label">Zip</label>
                            <input type="text" class="form-control" id="customer-zip">
                        </div>
                        <div class="col-md-6">
                            <label for="customer-contact" class="form-label">Customer Contact Name</label>
                            <input type="text" class="form-control" id="customer-contact">
                        </div>
                        <div class="col-md-6">
                            <label for="customer-email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="customer-email">
                        </div>
                        <div class="col-md-6">
                            <label for="customer-phone" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="customer-phone">
                        </div>
                        <div class="col-md-6">
                            <label for="customer-fax" class="form-label">Fax</label>
                            <input type="text" class="form-control" id="customer-fax">
                        </div>
                        <div class="col-12">
                            <label for="customer-website" class="form-label">Website</label>
                            <input type="text" class="form-control" id="customer-website">
                        </div>
                        <div class="col-12">
                            <label for="customer-logo" class="form-label">Customer Logo</label>
                            <div class="input-group">
                                <input type="file" class="form-control" id="customer-logo" accept="image/*">
                            </div>
                        </div>
                    </div>
                    <hr class="my-4">
                    <h5 class="fw-bold mb-3">Credentials</h5>
                    <div id="credentials-container">
                        <div class="row g-3 credential-set">
                            <div class="col-md-6">
                                <label for="admin-username-1" class="form-label">Admin username 1</label>
                                <input type="text" class="form-control" id="admin-username-1">
                            </div>
                            <div class="col-md-6">
                                <label for="admin-password-1" class="form-label">Admin password 1</label>
                                <input type="password" class="form-control" id="admin-password-1">
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-link ps-0" id="add-credential-btn">Add More</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="save-customer-btn">Save changes</button>
            </div>
        </div>
    </div>
</div>
<?= view('layout/footer') ?>