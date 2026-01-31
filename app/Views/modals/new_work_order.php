<div class="modal fade" id="newWorkOrderModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Create New Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Request Type</label>
                            <select class="form-select">
                                <option selected>Work Order</option>
                                <option>Inspection</option>
                                <option>Service</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Customer</label>
                            <select class="form-select">
                                <option selected>Select Customer</option>
                                <option>Mercy Hospital</option>
                                <option>Downtown Clinic</option>
                                <option>Westside Imaging</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Site</label>
                            <input type="text" class="form-control" placeholder="Enter site name">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Equipment</label>
                            <input type="text" class="form-control" placeholder="Enter asset tag or description">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" rows="3"
                                placeholder="Describe the issue or request"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Priority</label>
                            <select class="form-select">
                                <option>Low</option>
                                <option>Medium</option>
                                <option>High</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Assign Technician</label>
                            <select class="form-select">
                                <option>Auto-Assign</option>
                                <option>John Doe</option>
                                <option>Sarah Lee</option>
                                <option>Mike Ross</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Preferred Date</label>
                            <input type="date" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Preferred Time</label>
                            <input type="time" class="form-control">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary">Create Request</button>
            </div>
        </div>
    </div>
</div>