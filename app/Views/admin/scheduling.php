 <!-- Loading Overlay -->
 <?= view('layout/header') ?>
 <?= view('layout/sidebar') ?>
 <div class="loading-overlay" id="loadingOverlay">
     <div class="spinner-border text-primary" role="status">
         <span class="visually-hidden">Loading...</span>
     </div>
 </div>

 <!-- Page Header -->
 <div class="page-header">
     <div class="d-flex align-items-center gap-3">
         <i class="fa-solid fa-heart-pulse fa-2x text-primary"></i>
         <div>
             <h1 class="mb-0">Master Schedule</h1>
             <p class="text-muted">Manage appointments, routes, and technician availability</p>
         </div>
     </div>
 </div>

 <!-- Main Content -->
 <div class="glass-card">
     <!-- Toolbar -->
     <div class="toolbar">
         <div class="d-flex gap-2 flex-wrap">
             <div class="btn-group" role="group">
                 <button id="btnScheduleTimeline" class="btn btn-outline-secondary active">
                     <i class="fa-solid fa-bars-progress me-1"></i> Timeline
                 </button>
                 <button id="btnScheduleCalendar" class="btn btn-outline-secondary">
                     <i class="fa-solid fa-calendar me-1"></i> Calendar
                 </button>
                 <button id="btnScheduleMap" class="btn btn-outline-secondary">
                     <i class="fa-solid fa-map me-1"></i> Map Route
                 </button>
             </div>
             <div class="btn-group" role="group">
                 <button id="btnScheduleDay" class="btn btn-outline-secondary active">Day</button>
                 <button id="btnScheduleWeek" class="btn btn-outline-secondary">Week</button>
                 <button id="btnScheduleMonth" class="btn btn-outline-secondary">Month</button>
             </div>
         </div>
         <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#appointmentModal">
             <i class="fa-solid fa-plus me-2"></i> Add Appointment
         </button>
     </div>

     <!-- Date Navigation -->
     <div class="date-navigation mb-4 pb-3 border-bottom">
         <button class="btn btn-light btn-sm border" id="btnPrevDate">
             <i class="fa-solid fa-chevron-left"></i>
         </button>
         <span class="fw-bold" id="currentDateDisplay">Today, Oct 24, 2025</span>
         <button class="btn btn-light btn-sm border" id="btnNextDate">
             <i class="fa-solid fa-chevron-right"></i>
         </button>
     </div>

     <!-- Status Legend and Site Info -->
     <div class="d-flex justify-content-between align-items-start mb-3 flex-wrap gap-3">
         <div class="status-legend">
             <span class="badge bg-success">Ready</span>
             <span class="badge bg-warning text-dark">Needs Attention</span>
             <span class="badge bg-danger">Past Due</span>
         </div>
         <div class="site-info-card">
             <h6 class="fw-bold mb-1">Mercy Hospital</h6>
             <div class="small text-muted mb-1">123 Healthcare Blvd, New York, NY</div>
             <div class="small"><i class="fa-solid fa-phone me-1"></i> 555-0123</div>
         </div>
     </div>

     <!-- Schedule Views Container -->
     <div id="scheduleViews">
         <!-- Timeline View -->
         <div id="timelineView" class="schedule-view active">
             <!-- Day Timeline -->
             <div id="timelineDayView" class="timeline-view active">
                 <div class="gantt-container">
                     <div class="d-flex ms-5 ps-5 mb-2 text-muted small">
                         <div style="width: 12.5%">8 AM</div>
                         <div style="width: 12.5%">10 AM</div>
                         <div style="width: 12.5%">12 PM</div>
                         <div style="width: 12.5%">2 PM</div>
                         <div style="width: 12.5%">4 PM</div>
                         <div style="width: 12.5%">6 PM</div>
                     </div>

                     <div class="gantt-row">
                         <div class="gantt-label">
                             <img src="https://ui-avatars.com/api/?name=John+Doe" class="avatar-circle"> John D.
                         </div>
                         <div class="gantt-timeline">
                             <div class="gantt-bar bg-primary" style="left: 10%; width: 30%;" data-bs-toggle="tooltip"
                                 title="Mercy Hospital - MRI Repair">
                                 Mercy Hospital (MRI)
                             </div>
                             <div class="gantt-bar bg-success" style="left: 50%; width: 20%;" data-bs-toggle="tooltip"
                                 title="Clinic A - Routine">
                                 Clinic A (PM)
                             </div>
                         </div>
                     </div>

                     <div class="gantt-row">
                         <div class="gantt-label">
                             <img src="https://ui-avatars.com/api/?name=Sarah+Lee" class="avatar-circle"> Sarah L.
                         </div>
                         <div class="gantt-timeline">
                             <div class="gantt-bar bg-danger" style="left: 5%; width: 85%;" data-bs-toggle="tooltip"
                                 title="City Hospital - Emergency Repair">
                                 City Hospital - EMERGENCY
                             </div>
                         </div>
                     </div>

                     <div class="gantt-row">
                         <div class="gantt-label">
                             <img src="https://ui-avatars.com/api/?name=Mike+Ross" class="avatar-circle"> Mike R.
                         </div>
                         <div class="gantt-timeline">
                             <div class="gantt-bar bg-warning text-dark" style="left: 60%; width: 15%;"
                                 data-bs-toggle="tooltip" title="Travel">
                                 Travel
                             </div>
                             <div class="gantt-bar bg-primary" style="left: 75%; width: 20%;" data-bs-toggle="tooltip"
                                 title="Dental Plus - Install">
                                 Dental Plus
                             </div>
                         </div>
                     </div>
                 </div>
             </div>

             <!-- Week Timeline -->
             <div id="timelineWeekView" class="timeline-view">
                 <div class="list-group">
                     <div class="list-group-item">
                         <strong>Mon, Oct 20</strong>
                         <div class="small text-muted mt-1">John D. – Mercy Hospital (MRI Repair) – 9 AM – 12 PM</div>
                         <div class="small text-muted">Sarah L. – City Hospital (Emergency) – 8:30 AM – 6 PM</div>
                     </div>
                     <div class="list-group-item">
                         <strong>Tue, Oct 21</strong>
                         <div class="small text-muted mt-1">John D. – Clinic A (PM) – 1 PM – 3 PM</div>
                     </div>
                     <div class="list-group-item">
                         <strong>Wed, Oct 22</strong>
                         <div class="small text-muted mt-1">No appointments scheduled</div>
                     </div>
                     <div class="list-group-item">
                         <strong>Thu, Oct 23</strong>
                         <div class="small text-muted mt-1">Mike R. – Dental Plus (Install) – 4 PM – 6:30 PM</div>
                     </div>
                     <div class="list-group-item">
                         <strong>Fri, Oct 24</strong>
                         <div class="small text-muted mt-1">Sarah L. – Downtown Clinic (Calibration) – 10 AM – 12 PM
                         </div>
                     </div>
                 </div>
             </div>

             <!-- Month Timeline -->
             <div id="timelineMonthView" class="timeline-view">
                 <div class="list-group">
                     <div class="list-group-item">
                         <div class="d-flex justify-content-between align-items-center">
                             <div>
                                 <strong>October 2025</strong>
                                 <div class="small text-muted">Total appointments this month</div>
                             </div>
                             <span class="badge bg-primary rounded-pill">24</span>
                         </div>
                     </div>
                     <div class="list-group-item">
                         <div class="d-flex justify-content-between align-items-center">
                             <div>
                                 <strong>November 2025</strong>
                                 <div class="small text-muted">Scheduled appointments</div>
                             </div>
                             <span class="badge bg-secondary rounded-pill">12</span>
                         </div>
                     </div>
                     <div class="list-group-item">
                         <div class="d-flex justify-content-between align-items-center">
                             <div>
                                 <strong>December 2025</strong>
                                 <div class="small text-muted">Scheduled appointments</div>
                             </div>
                             <span class="badge bg-secondary rounded-pill">8</span>
                         </div>
                     </div>
                 </div>
             </div>
         </div>

         <!-- Calendar View -->
         <div id="calendarView" class="schedule-view">
             <div id="calendar"></div>
         </div>

         <!-- Map View -->
         <div id="mapView" class="schedule-view">
             <div id="mapid"></div>
         </div>
     </div>
 </div>

 <!-- Appointment Modal -->
 <div class="modal fade" id="appointmentModal" tabindex="-1">
     <div class="modal-dialog modal-lg">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title fw-bold">Schedule Service</h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
             </div>
             <div class="modal-body">
                 <form id="appointmentForm">
                     <div class="row g-3">
                         <div class="col-md-6">
                             <label class="form-label">Customer</label>
                             <select class="form-select" id="appointmentCustomer">
                                 <option value="" selected>Select Customer</option>
                                 <option value="Mercy Hospital">Mercy Hospital</option>
                                 <option value="Downtown Clinic">Downtown Clinic</option>
                                 <option value="Westside Imaging">Westside Imaging</option>
                                 <option value="City Hospital">City Hospital</option>
                             </select>
                         </div>
                         <div class="col-md-6">
                             <label class="form-label">Site Name</label>
                             <input type="text" class="form-control" id="appointmentSiteName"
                                 placeholder="Enter site name">
                         </div>
                         <div class="col-12">
                             <label class="form-label">Site Address</label>
                             <input type="text" class="form-control" id="appointmentSiteAddress"
                                 placeholder="Enter site address">
                         </div>
                         <div class="col-md-6">
                             <label class="form-label">Contact Info</label>
                             <input type="text" class="form-control" id="appointmentContact"
                                 placeholder="Phone or email">
                         </div>
                         <div class="col-md-6">
                             <label class="form-label">Status</label>
                             <select class="form-select" id="appointmentStatus">
                                 <option value="Ready" selected>Ready</option>
                                 <option value="Needs Attention">Needs Attention</option>
                                 <option value="Past Due">Past Due</option>
                             </select>
                         </div>
                         <div class="col-md-6">
                             <label class="form-label">Visit Type</label>
                             <select class="form-select" id="appointmentVisitType">
                                 <option value="Routine Inspection">Routine Inspection</option>
                                 <option value="Emergency Repair">Emergency Repair</option>
                                 <option value="Calibration">Calibration</option>
                                 <option value="Preventive Maintenance">Preventive Maintenance</option>
                             </select>
                         </div>
                         <div class="col-md-6">
                             <label class="form-label">Equipment</label>
                             <input type="text" class="form-control" id="appointmentEquipment"
                                 placeholder="Enter asset tag or description">
                         </div>
                         <div class="col-md-6">
                             <label class="form-label">Date</label>
                             <input type="date" class="form-control" id="appointmentDate" required>
                         </div>
                         <div class="col-md-6">
                             <label class="form-label">Time</label>
                             <input type="time" class="form-control" id="appointmentTime" required>
                         </div>
                         <div class="col-md-6">
                             <label class="form-label">Assign Technician</label>
                             <select class="form-select" id="appointmentAssignedTech">
                                 <option value="Auto-Assign">Auto-Assign</option>
                                 <option value="John Doe">John Doe</option>
                                 <option value="Sarah Lee">Sarah Lee</option>
                                 <option value="Mike Ross">Mike Ross</option>
                             </select>
                         </div>
                         <div class="col-md-6">
                             <label class="form-label">Invite Technician (Email)</label>
                             <input type="email" class="form-control" id="inviteTechEmail"
                                 placeholder="tech@example.com">
                         </div>
                         <div class="col-md-6">
                             <label class="form-label">Invite Customer (Email)</label>
                             <input type="email" class="form-control" id="inviteCustomerEmail"
                                 placeholder="customer@example.com">
                         </div>
                         <div class="col-12">
                             <label class="form-label">Notes</label>
                             <textarea class="form-control" id="appointmentNotes" rows="3"
                                 placeholder="Additional notes"></textarea>
                         </div>
                     </div>
                 </form>
             </div>
             <div class="modal-footer">
                 <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                 <button type="button" class="btn btn-primary" id="createAppointmentBtn">Create Appointment</button>
             </div>
         </div>
     </div>
 </div>

 <?= view('layout/footer') ?>