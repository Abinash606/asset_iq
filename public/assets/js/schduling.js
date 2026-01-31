// Initialize tooltips
const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
});

// Current view state
let currentMainView = 'timeline';
let currentRangeView = 'day';
let currentDate = new Date(2025, 9, 24); // Oct 24, 2025

// Calendar instance
let calendarInstance;

// Map instance
let mapInstance;
let mapInitialized = false;

// Schedule events data
const scheduleEvents = [
    {
        title: 'Mercy Hospital (MRI)',
        start: '2025-10-24T09:00:00',
        end: '2025-10-24T12:00:00',
        description: 'MRI Repair',
        technician: 'John Doe',
        color: '#2563eb'
    },
    {
        title: 'Clinic A (PM)',
        start: '2025-10-24T13:00:00',
        end: '2025-10-24T15:00:00',
        description: 'Routine Visit',
        technician: 'John Doe',
        color: '#10b981'
    },
    {
        title: 'City Hospital - EMERGENCY',
        start: '2025-10-24T08:30:00',
        end: '2025-10-24T18:00:00',
        description: 'Emergency Repair',
        technician: 'Sarah Lee',
        color: '#dc2626'
    },
    {
        title: 'Dental Plus (Install)',
        start: '2025-10-25T16:00:00',
        end: '2025-10-25T18:30:00',
        description: 'Installation',
        technician: 'Mike Ross',
        color: '#2563eb'
    }
];

// Initialize calendar
function initCalendar() {
    const calendarEl = document.getElementById('calendar');
    if (calendarEl && window.FullCalendar) {
        calendarInstance = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridDay',
            initialDate: '2025-10-24',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridDay,dayGridWeek,dayGridMonth'
            },
            height: 600,
            events: scheduleEvents,
            eventClick: function (info) {
                alert('Event: ' + info.event.title + '\nTechnician: ' + info.event.extendedProps.technician);
            }
        });
        calendarInstance.render();
    }
}

// Initialize map
function initMap() {
    if (mapInitialized || !document.getElementById('mapid')) return;
    mapInitialized = true;

    // Create Leaflet map
    mapInstance = L.map('mapid').setView([40.72, -74.0], 10);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(mapInstance);

    // Add markers for locations
    const markerData = [
        { coords: [40.713, -74.006], label: 'Mercy Hospital', color: 'blue' },
        { coords: [40.731, -73.935], label: 'Clinic A', color: 'green' },
        { coords: [40.779, -73.955], label: 'City Hospital', color: 'red' },
        { coords: [40.658, -73.949], label: 'Dental Plus', color: 'blue' }
    ];

    const latlngs = [];
    markerData.forEach(function (item) {
        const marker = L.marker(item.coords).addTo(mapInstance);
        marker.bindPopup(item.label);
        latlngs.push(item.coords);
    });

    // Draw route
    const polyline = L.polyline(latlngs, { color: 'blue', weight: 3 }).addTo(mapInstance);
    mapInstance.fitBounds(polyline.getBounds());
}

// Switch main view (Timeline/Calendar/Map)
function switchMainView(view) {
    currentMainView = view;

    // Update button states
    document.getElementById('btnScheduleTimeline').classList.toggle('active', view === 'timeline');
    document.getElementById('btnScheduleCalendar').classList.toggle('active', view === 'calendar');
    document.getElementById('btnScheduleMap').classList.toggle('active', view === 'map');

    // Update view visibility
    document.getElementById('timelineView').classList.toggle('active', view === 'timeline');
    document.getElementById('calendarView').classList.toggle('active', view === 'calendar');
    document.getElementById('mapView').classList.toggle('active', view === 'map');

    // Initialize calendar if switching to calendar view
    if (view === 'calendar' && !calendarInstance) {
        initCalendar();
    }

    // Initialize map if switching to map view
    if (view === 'map') {
        initMap();
        setTimeout(() => {
            if (mapInstance) {
                mapInstance.invalidateSize();
            }
        }, 100);
    }
}

// Switch range view (Day/Week/Month)
function switchRange(range) {
    currentRangeView = range;

    // Update button states
    document.getElementById('btnScheduleDay').classList.toggle('active', range === 'day');
    document.getElementById('btnScheduleWeek').classList.toggle('active', range === 'week');
    document.getElementById('btnScheduleMonth').classList.toggle('active', range === 'month');

    // Update timeline subviews
    document.getElementById('timelineDayView').classList.toggle('active', range === 'day');
    document.getElementById('timelineWeekView').classList.toggle('active', range === 'week');
    document.getElementById('timelineMonthView').classList.toggle('active', range === 'month');

    // Update calendar view if calendar is active
    if (calendarInstance) {
        if (range === 'day') {
            calendarInstance.changeView('dayGridDay');
        } else if (range === 'week') {
            calendarInstance.changeView('dayGridWeek');
        } else if (range === 'month') {
            calendarInstance.changeView('dayGridMonth');
        }
    }

    updateDateDisplay();
}

// Update date display
function updateDateDisplay() {
    const options = { weekday: 'long', year: 'numeric', month: 'short', day: 'numeric' };
    let displayText = '';

    if (currentRangeView === 'day') {
        displayText = currentDate.toLocaleDateString('en-US', options);
    } else if (currentRangeView === 'week') {
        displayText = `Week of ${currentDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}`;
    } else if (currentRangeView === 'month') {
        displayText = currentDate.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
    }

    document.getElementById('currentDateDisplay').textContent = displayText;
}

// Navigate dates
function navigateDate(direction) {
    if (currentRangeView === 'day') {
        currentDate.setDate(currentDate.getDate() + direction);
    } else if (currentRangeView === 'week') {
        currentDate.setDate(currentDate.getDate() + (direction * 7));
    } else if (currentRangeView === 'month') {
        currentDate.setMonth(currentDate.getMonth() + direction);
    }

    updateDateDisplay();

    if (calendarInstance) {
        calendarInstance.gotoDate(currentDate);
    }
}

// Create appointment
function createAppointment() {
    const form = document.getElementById('appointmentForm');

    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    // Gather appointment data
    const appointmentData = {
        customer: document.getElementById('appointmentCustomer').value,
        siteName: document.getElementById('appointmentSiteName').value,
        siteAddress: document.getElementById('appointmentSiteAddress').value,
        contact: document.getElementById('appointmentContact').value,
        status: document.getElementById('appointmentStatus').value,
        visitType: document.getElementById('appointmentVisitType').value,
        equipment: document.getElementById('appointmentEquipment').value,
        date: document.getElementById('appointmentDate').value,
        time: document.getElementById('appointmentTime').value,
        assignedTech: document.getElementById('appointmentAssignedTech').value,
        techEmail: document.getElementById('inviteTechEmail').value,
        customerEmail: document.getElementById('inviteCustomerEmail').value,
        notes: document.getElementById('appointmentNotes').value
    };

    // Send invites if emails provided
    const recipients = [];
    if (appointmentData.techEmail) recipients.push(appointmentData.techEmail);
    if (appointmentData.customerEmail) recipients.push(appointmentData.customerEmail);

    if (recipients.length > 0) {
        const subject = 'New Appointment Scheduled - ' + appointmentData.customer;
        const body = `Hello,

A new appointment has been scheduled:

Customer: ${appointmentData.customer}
Site: ${appointmentData.siteName}
Address: ${appointmentData.siteAddress}
Visit Type: ${appointmentData.visitType}
Equipment: ${appointmentData.equipment}
Date: ${appointmentData.date}
Time: ${appointmentData.time}
Assigned Technician: ${appointmentData.assignedTech}

Notes: ${appointmentData.notes}

Please confirm your availability.

Thank you.`;

        const mailtoLink = 'mailto:' + recipients.join(',') +
            '?subject=' + encodeURIComponent(subject) +
            '&body=' + encodeURIComponent(body);

        window.location.href = mailtoLink;
    }

    // Show success message
    alert('Appointment created successfully!');

    // Close modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('appointmentModal'));
    modal.hide();

    // Reset form
    form.reset();
}

// Event listeners
document.addEventListener('DOMContentLoaded', function () {
    // Main view buttons
    document.getElementById('btnScheduleTimeline').addEventListener('click', () => switchMainView('timeline'));
    document.getElementById('btnScheduleCalendar').addEventListener('click', () => switchMainView('calendar'));
    document.getElementById('btnScheduleMap').addEventListener('click', () => switchMainView('map'));

    // Range view buttons
    document.getElementById('btnScheduleDay').addEventListener('click', () => switchRange('day'));
    document.getElementById('btnScheduleWeek').addEventListener('click', () => switchRange('week'));
    document.getElementById('btnScheduleMonth').addEventListener('click', () => switchRange('month'));

    // Date navigation
    document.getElementById('btnPrevDate').addEventListener('click', () => navigateDate(-1));
    document.getElementById('btnNextDate').addEventListener('click', () => navigateDate(1));

    // Create appointment button
    document.getElementById('createAppointmentBtn').addEventListener('click', createAppointment);

    // Set default date
    const today = new Date();
    document.getElementById('appointmentDate').valueAsDate = today;

    // Initialize date display
    updateDateDisplay();
});