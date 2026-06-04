<?php
require_once __DIR__ . '/auth.php';

function navActive($file) {
    return basename($_SERVER['PHP_SELF']) === $file ? 'active' : '';
}

function dmacStaffNavItem($file, $icon, $label) {
    $active = navActive($file);
    echo '<a href="' . htmlspecialchars($file, ENT_QUOTES, 'UTF-8') . '" class="nav-item ' . htmlspecialchars($active, ENT_QUOTES, 'UTF-8') . '">';
    echo '<i class="' . htmlspecialchars($icon, ENT_QUOTES, 'UTF-8') . '"></i>';
    echo '<span>' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '</span>';
    echo '</a>';
}

function dmacStaffSidebarProfileData() {
    $data = [
        'name' => $_SESSION['employee_name'] ?? 'Admin User',
        'subtitle' => $_SESSION['employee_role'] ?? 'Staff Account',
        'image' => ''
    ];

    try {
        if (!class_exists('Database')) {
            require_once __DIR__ . '/../../config/database.php';
        }

        $empID = (int)($_SESSION['employee_id'] ?? 0);
        if ($empID > 0) {
            $db = (new Database())->getConnection();
            $stmt = $db->prepare("SELECT emp_firstname, emp_lastname, emp_email, profile_image FROM employee WHERE emp_ID = ? LIMIT 1");
            $stmt->execute([$empID]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $fullName = trim(($row['emp_firstname'] ?? '') . ' ' . ($row['emp_lastname'] ?? ''));
                if ($fullName !== '') {
                    $data['name'] = $fullName;
                }
                if (!empty($row['emp_email']) && empty($data['subtitle'])) {
                    $data['subtitle'] = $row['emp_email'];
                }
                $data['image'] = $row['profile_image'] ?? '';
            }
        }
    } catch (Exception $e) {
        error_log('Staff sidebar profile load error: ' . $e->getMessage());
    }

    return $data;
}

function dmacSidebarImageSrc($path) {
    $path = trim((string)$path);
    if ($path === '') {
        return '';
    }

    if (preg_match('/^https?:\/\//i', $path)) {
        return $path;
    }

    $path = ltrim($path, '/');
    return '../../../' . $path;
}

function dmacStaffProfileCard() {
    $profile = dmacStaffSidebarProfileData();
    $imageSrc = dmacSidebarImageSrc($profile['image'] ?? '');
?>
    <div class="sidebar-profile">
        <button type="button" class="sidebar-profile-toggle" aria-expanded="false">
            <span class="sidebar-profile-avatar">
                <?php if ($imageSrc): ?>
                    <img src="<?= htmlspecialchars($imageSrc, ENT_QUOTES, 'UTF-8') ?>" alt="Profile Picture">
                <?php else: ?>
                    <i class="fa-solid fa-circle-user"></i>
                <?php endif; ?>
            </span>
            <span class="sidebar-profile-text">
                <strong><?= htmlspecialchars($profile['name'], ENT_QUOTES, 'UTF-8') ?></strong>
                <small><?= htmlspecialchars($profile['subtitle'], ENT_QUOTES, 'UTF-8') ?></small>
            </span>
            <i class="fa-solid fa-chevron-down sidebar-profile-arrow"></i>
        </button>

        <div class="sidebar-profile-menu">
            <a href="settings.php"><i class="fa-solid fa-gear"></i><span>Settings</span></a>
            <a href="logout.php" class="logout-link"><i class="fa-solid fa-right-from-bracket"></i><span>Logout</span></a>
        </div>
    </div>
<?php
}

function showStaffSidebar($title = 'DMAC Admin') {
    $canEmployees = hasAnyPermission(['employees_view','employees_create','employees_edit','employees_permissions','employees_deactivate']);
    $canBookings = hasAnyPermission(['bookings_view','bookings_approve']);
    $canAssignments = hasAnyPermission(['bookings_assign']);
    $canShipments = hasAnyPermission(['shipments_view','shipments_update']);
    $canAccounting = hasAnyPermission(['accounting_view','accounting_manage']);
    $isAdminLike = isSuperAdmin() || strtolower((string)($_SESSION['employee_role'] ?? '')) === 'admin' || strpos(strtolower((string)($_SESSION['employee_role'] ?? '')), 'admin') !== false;
?>
<aside class="sidebar staff-sidebar">
    <div class="brand">
        <div class="brand-logo">D</div>
        <div>
            <h2><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h2>
        </div>
    </div>

    <div class="sidebar-nav">
        <p class="sidebar-label">MAIN MENU</p>
        <?php if (hasPermission('dashboard_view')) dmacStaffNavItem('dashboard.php', 'fa-solid fa-chart-line', 'Dashboard'); ?>

        <?php if ($canBookings || $canShipments || $canAssignments || hasAnyPermission(['shipments_view','bookings_view'])): ?>
            <p class="sidebar-label">OPERATIONS</p>
            <?php if ($canBookings) dmacStaffNavItem('pending-bookings.php', 'fa-solid fa-calendar-check', 'Pending Bookings'); ?>
            <?php if ($canShipments) dmacStaffNavItem('active-shipments.php', 'fa-solid fa-truck-ramp-box', 'For Pick-up Bookings'); ?>
            <?php if ($canAssignments) dmacStaffNavItem('assignments.php', 'fa-solid fa-user-check', 'Coordinator Assignments'); ?>
            <?php if (hasAnyPermission(['shipments_view','bookings_view'])) dmacStaffNavItem('booking-records.php', 'fa-solid fa-box-archive', 'Booking Records'); ?>
        <?php endif; ?>

        <?php if ($canAccounting || $isAdminLike): ?>
            <p class="sidebar-label">ACCOUNTING</p>
            <?php if ($canAccounting) dmacStaffNavItem('finances.php', 'fa-solid fa-wallet', 'Billing'); ?>
            <?php if ($isAdminLike) dmacStaffNavItem('expenses.php', 'fa-solid fa-chart-line', 'Expenses'); ?>
        <?php endif; ?>

        <?php if ($canEmployees): ?>
            <p class="sidebar-label">MANAGEMENT</p>
            <?php dmacStaffNavItem('manage-employees.php', 'fa-solid fa-users', 'Employees'); ?>
        <?php endif; ?>

        <p class="sidebar-label">GENERAL</p>
        <?php if (hasPermission('feedback_view')) dmacStaffNavItem('feedback.php', 'fa-solid fa-star', 'Recent Feedback'); ?>
        <?php if (hasPermission('activity_logs_view')) dmacStaffNavItem('activity-logs.php', 'fa-solid fa-clock-rotate-left', 'Activity Logs'); ?>
        <?php dmacStaffNavItem('settings.php', 'fa-solid fa-gear', 'Settings'); ?>
    </div>

    <?php dmacStaffProfileCard(); ?>
</aside>
<?php
}
?>
