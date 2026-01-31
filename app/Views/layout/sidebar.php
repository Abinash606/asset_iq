<nav class="sidebar">
    <div class="d-flex align-items-center gap-2 p-4 text-primary fw-bold fs-4">
        <i class="fa-solid fa-heart-pulse"></i> <span class="sidebar-text">MedEquip</span>
    </div>

    <div class="d-flex flex-column">
        <div class="section-header sidebar-text">Core Operations</div>
        <a class="nav-link <?= ($page ?? '') == 'dashboard' ? 'active' : '' ?>"
            href="<?= base_url('api/admin/dashboard') ?>">
            <i class="fa-solid fa-gauge-high"></i> <span class="sidebar-text">Dashboard</span>
        </a>
        <a class="nav-link <?= ($page ?? '') == 'scheduling' ? 'active' : '' ?>"
            href="<?= base_url('api/admin/scheduling') ?>">
            <i class="fa-solid fa-calendar-check"></i> <span class="sidebar-text">Scheduling</span>
        </a>

        <div class="section-header sidebar-text">Assets & Clients</div>
        <a class="nav-link <?= ($page ?? '') == 'customers' ? 'active' : '' ?>"
            href="<?= base_url('api/admin/customers') ?>">
            <i class="fa-solid fa-users"></i> <span class="sidebar-text">Customers</span>
        </a>
        <a class="nav-link <?= ($page ?? '') == 'sites' ? 'active' : '' ?>" href="<?= base_url('api/admin/sites') ?>">
            <i class="fa-solid fa-sitemap"></i> <span class="sidebar-text">Sites</span>
        </a>
        <a class="nav-link <?= ($page ?? '') == 'equipmentDb' ? 'active' : '' ?>"
            href="<?= base_url('api/admin/equipment-db') ?>">
            <i class="fa-solid fa-boxes-stacked"></i> <span class="sidebar-text">Equipment DB</span>
        </a>
        <a class="nav-link <?= ($page ?? '') == 'inspection' ? 'active' : '' ?>"
            href="<?= base_url('api/admin/inspection') ?>">
            <i class="fa-solid fa-clipboard-list"></i> <span class="sidebar-text">Inspection Reports</span>
        </a>
        <a class="nav-link <?= ($page ?? '') == 'inventory' ? 'active' : '' ?>"
            href="<?= base_url('api/admin/inventory') ?>">
            <i class="fa-solid fa-box-open"></i> <span class="sidebar-text">Inventory</span>
        </a>
        <a class="nav-link <?= ($page ?? '') == 'technicians' ? 'active' : '' ?>"
            href="<?= base_url('api/admin/technicians') ?>">
            <i class="fa-solid fa-users-cog"></i> <span class="sidebar-text">Technicians</span>
        </a>

        <div class="section-header sidebar-text">Admin & Analytics</div>
        <a class="nav-link <?= ($page ?? '') == 'financials' ? 'active' : '' ?>"
            href="<?= base_url('api/admin/financials') ?>">
            <i class="fa-solid fa-chart-line"></i> <span class="sidebar-text">Financials</span>
        </a>
        <a class="nav-link <?= ($page ?? '') == 'dataops' ? 'active' : '' ?>"
            href="<?= base_url('api/admin/dataops') ?>">
            <i class="fa-solid fa-database"></i> <span class="sidebar-text">Data Ops</span>
        </a>
        <a class="nav-link <?= ($page ?? '') == 'settings' ? 'active' : '' ?>"
            href="<?= base_url('api/admin/settings') ?>">
            <i class="fa-solid fa-gears"></i> <span class="sidebar-text">Settings</span>
        </a>
    </div>

    <div class="mt-auto p-4 border-top">
        <div class="d-flex align-items-center gap-2">
            <img src="https://ui-avatars.com/api/?name=Admin+User" class="rounded-circle" width="35" id="admin-avatar">
            <div class="small lh-sm sidebar-text">
                <div class="fw-bold" id="admin-username">SysAdmin</div>
                <div class="text-muted">
                    <a href="#" id="logout-btn" class="text-decoration-none text-danger">
                        <i class="fa-solid fa-right-from-bracket"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </div>
    <button id="sidebar-toggle" class="btn btn-light">&laquo;</button>
</nav>

<main class="main-content">
    <button class="btn btn-primary hamburger-menu mb-3"><i class="fa-solid fa-bars"></i></button>