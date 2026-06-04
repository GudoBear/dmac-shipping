<?php
function activeClientNav($file) {
    return basename($_SERVER['PHP_SELF']) === $file ? 'active' : '';
}

function dmacClientNavItem($file, $icon, $label) {
    $active = activeClientNav($file);
    echo '<a href="' . htmlspecialchars($file, ENT_QUOTES, 'UTF-8') . '" class="nav-item ' . htmlspecialchars($active, ENT_QUOTES, 'UTF-8') . '">';
    echo '<i class="' . htmlspecialchars($icon, ENT_QUOTES, 'UTF-8') . '"></i>';
    echo '<span>' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '</span>';
    echo '</a>';
}

function dmacClientSidebarProfileData() {
    $data = [
        'name' => $_SESSION['client_name'] ?? 'Client User',
        'subtitle' => 'Client Account',
        'image' => ''
    ];

    try {
        if (!class_exists('Database')) {
            require_once __DIR__ . '/../../config/database.php';
        }

        $clientID = (int)($_SESSION['client_id'] ?? 0);
        if ($clientID > 0) {
            $db = (new Database())->getConnection();
            $stmt = $db->prepare("SELECT client_firstname, client_lastname, client_email, profile_image FROM client WHERE client_ID = ? LIMIT 1");
            $stmt->execute([$clientID]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $fullName = trim(($row['client_firstname'] ?? '') . ' ' . ($row['client_lastname'] ?? ''));
                if ($fullName !== '') {
                    $data['name'] = $fullName;
                }
                $data['image'] = $row['profile_image'] ?? '';
            }
        }
    } catch (Exception $e) {
        error_log('Client sidebar profile load error: ' . $e->getMessage());
    }

    return $data;
}

function dmacClientSidebarImageSrc($path) {
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

function dmacClientProfileCard() {
    $profile = dmacClientSidebarProfileData();
    $imageSrc = dmacClientSidebarImageSrc($profile['image'] ?? '');
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

function showClientSidebar($title = 'DMAC Client') {
?>
<aside class="sidebar client-sidebar">
    <div class="brand">
        <div class="brand-logo">D</div>
        <div>
            <h2><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h2>
        </div>
    </div>

    <div class="sidebar-nav">
        <p class="sidebar-label">MAIN MENU</p>
        <?php dmacClientNavItem('dashboard.php', 'fa-solid fa-chart-line', 'Dashboard'); ?>
        <?php dmacClientNavItem('booking-wizard.php', 'fa-solid fa-calendar-check', 'Book a Shipment'); ?>
        <?php dmacClientNavItem('my-shipments.php', 'fa-solid fa-box', 'Active Orders'); ?>
        <?php dmacClientNavItem('billing.php', 'fa-solid fa-wallet', 'Billing'); ?>

        <p class="sidebar-label">APP</p>
        <?php dmacClientNavItem('feedback.php', 'fa-solid fa-star', 'Feedback'); ?>

        <p class="sidebar-label">GENERAL</p>
        <?php dmacClientNavItem('settings.php', 'fa-solid fa-gear', 'Settings'); ?>
    </div>

    <?php dmacClientProfileCard(); ?>
</aside>
<?php
}
?>
