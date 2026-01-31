
// Initialize Tooltips
document.addEventListener('DOMContentLoaded', function () {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

// Settings navigation pane switching
document.addEventListener('DOMContentLoaded', function () {
    var settingsLinks = document.querySelectorAll('#settings-nav a');
    settingsLinks.forEach(function (link) {
        link.addEventListener('click', function (event) {
            event.preventDefault();
            // remove active state from all nav links
            settingsLinks.forEach(function (l) { l.classList.remove('active'); });
            // hide all panes
            document.querySelectorAll('.settings-pane').forEach(function (pane) {
                pane.classList.add('d-none');
            });
            // activate clicked link
            this.classList.add('active');
            // show targeted pane
            var targetId = this.getAttribute('data-target');
            if (targetId) {
                var targetPane = document.getElementById(targetId);
                if (targetPane) {
                    targetPane.classList.remove('d-none');
                }
            }
        });
    });
});


document.addEventListener('DOMContentLoaded', function () {
    var sidebarToggle = document.getElementById('sidebar-toggle');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function () {
            var sidebar = document.querySelector('.sidebar');
            var mainContent = document.querySelector('.main-content');

            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('collapsed');

            if (sidebar.classList.contains('collapsed')) {
                this.innerHTML = '&raquo;';
            } else {
                this.innerHTML = '&laquo;';
            }
        });
    }

    // Hamburger menu for mobile
    var hamburger = document.querySelector('.hamburger-menu');
    if (hamburger) {
        hamburger.addEventListener('click', function () {
            document.querySelector('.sidebar').classList.toggle('active');
        });
    }
});

// Add credential button functionality
document.addEventListener('DOMContentLoaded', function () {
    var addCredentialBtn = document.getElementById('add-credential-btn');
    if (addCredentialBtn) {
        let credentialCount = 1;
        addCredentialBtn.addEventListener('click', function () {
            credentialCount++;
            const newCredentialSet = `
                <div class="row g-3 credential-set mt-3">
                    <div class="col-md-6">
                        <label for="admin-username-${credentialCount}" class="form-label">Admin username ${credentialCount}</label>
                        <input type="text" class="form-control" id="admin-username-${credentialCount}">
                    </div>
                    <div class="col-md-6">
                        <label for="admin-password-${credentialCount}" class="form-label">Admin password ${credentialCount}</label>
                        <input type="password" class="form-control" id="admin-password-${credentialCount}">
                    </div>
                </div>`;
            document.getElementById('credentials-container').insertAdjacentHTML('beforeend', newCredentialSet);
        });
    }
});

// Site details navigation
document.addEventListener('DOMContentLoaded', function () {
    var backToSitesBtn = document.getElementById('back-to-sites');
    if (backToSitesBtn) {
        backToSitesBtn.addEventListener('click', function () {
            window.location.href = siteUrl + 'admin/sites';
        });
    }
});

// Equipment data loading
document.addEventListener('DOMContentLoaded', function () {
    // Dynamic model search in inspection workflow step 2
    var modelSearch = document.getElementById('model-search');
    var modelResults = document.querySelector('#inspection-step-2 .list-group');

    if (modelSearch && modelResults) {
        modelSearch.addEventListener('input', function () {
            var term = modelSearch.value.trim().toLowerCase();
            modelResults.innerHTML = '';

            if (!term || !window.equipmentData) return;

            // Filter matches
            var matches = window.equipmentData.filter(function (item) {
                var name = (item.manufacturer + ' - ' + item.model).toLowerCase();
                return name.includes(term);
            }).slice(0, 10);

            matches.forEach(function (item) {
                var name = (item.manufacturer + ' - ' + item.model);
                var a = document.createElement('a');
                a.href = '#';
                a.className = 'list-group-item list-group-item-action';
                a.textContent = name;
                a.addEventListener('click', function (e) {
                    e.preventDefault();
                    // Fill in manufacturer, model and description fields
                    var mfgField = document.getElementById('new-manufacturer');
                    var modelField = document.getElementById('new-model');
                    var typeField = document.getElementById('new-model-type');
                    if (mfgField) mfgField.value = item.manufacturer;
                    if (modelField) modelField.value = item.model;
                    if (typeField) typeField.value = item.description || '';
                    // Move to next step automatically
                    goToStep(3);
                });
                modelResults.appendChild(a);
            });
        });
    }

    // Populate site selection options
    var siteSelect = document.getElementById('inspection-site');
    if (siteSelect) {
        var sites = [
            'AFC Urgent Care - Phoenixville',
            'Mercy Hospital',
            'Clinic A',
            'City Hospital',
            'Dental Plus'
        ];
        sites.forEach(function (site) {
            var option = document.createElement('option');
            option.value = site;
            option.textContent = site;
            siteSelect.appendChild(option);
        });
    }

    // Sync the selected site with the customer field in step 3
    var customerField = document.getElementById('inspection-customer');
    if (siteSelect && customerField) {
        siteSelect.addEventListener('change', function () {
            customerField.value = siteSelect.value;
        });
    }
});

// Global base URL for JavaScript
var siteUrl = window.location.origin + '/';

// Admin logout (AJAX)
document.addEventListener('DOMContentLoaded', function () {
    var logoutBtn = document.getElementById('logout-btn');
    console.log('Admin logout script loaded.');
    if (!logoutBtn) return;

    logoutBtn.addEventListener('click', function (e) {
        e.preventDefault();

        fetch(siteUrl + 'api/admin/logout', {
            method: 'POST',
            headers: {
                'Accept': 'application/json'
            },
            credentials: 'include'
        })
            .then(function (response) {
                return response.json();
            })
            .then(function (data) {
                if (data.status) {
                    deleteCookie('access_token');
                    deleteCookie('refresh_token');

                    localStorage.removeItem('user');

                    window.location.href = siteUrl;
                } else {
                    alert('Logout failed. Please try again.');
                }
            })
            .catch(function (error) {
                console.error('Logout error:', error);
                alert('Something went wrong while logging out.');
            });
    });

    function deleteCookie(name) {
        document.cookie = name + '=; Max-Age=0; path=/; SameSite=Lax';
    }
});
