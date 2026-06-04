<?php
require_once __DIR__ . '/../../helpers/auth.php';
requireAnyPermission(['bookings_view', 'bookings_approve']);
require_once __DIR__ . '/../../helpers/staff_nav.php';
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../models/Employee.php';

$db = (new Database())->getConnection();
$model = new Employee($db);
$pendingBookings = $model->getPendingBookings();

function h($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function formatDateReadable($date) {
    if (empty($date)) {
        return 'No date set';
    }

    $time = strtotime($date);
    return $time ? date('M d, Y', $time) : $date;
}
?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Pending Bookings</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../../../public/css/all.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="dashboard-body">
<?php showStaffSidebar(); ?>

<div class="main-content">
    <header>
        <div class="header-title">
            <h1>Pending Bookings</h1>
            <small>Review client bookings that are waiting for admin approval.</small>
        </div>

        <div class="user-wrapper">
            <i class="fa-solid fa-user-shield"></i>
            <div>
                <h4><?= h($_SESSION['employee_name'] ?? 'Employee') ?></h4>
                <small><?= h($_SESSION['employee_role'] ?? 'Staff') ?></small>
            </div>
        </div>
    </header>

    <main>
        <?php if (isset($_GET['updated'])): ?>
            <div class="alert-success <?= $_GET['updated'] === '1' ? '' : 'alert-error' ?>">
                <?= $_GET['updated'] === '1' ? 'Booking review updated successfully.' : 'Unable to update booking review. Please try again.' ?>
            </div>
        <?php endif; ?>

        <section class="pending-hero">
            <div>
                <h2>Bookings for Review</h2>
                <p>These are client bookings with <strong>PENDING REVIEW</strong> status. They are not accepted yet.</p>
            </div>
            <div class="pending-count-pill">
                <strong><?= h(count($pendingBookings)) ?></strong>
                <span>Pending Review</span>
            </div>
        </section>

        <section class="pending-table-card">
            <div class="section-header">
                <div>
                    <h3>Pending Booking Records</h3>
                    <small>Booking ID, client, animal details, drop-off address, request date, and status.</small>
                </div>
            </div>

            <?php if (empty($pendingBookings)): ?>
                <div class="empty-panel">
                    <i class="fa-solid fa-circle-check"></i>
                    <h3>No pending bookings</h3>
                    <p>All client bookings have already been reviewed.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>Booking ID</th>
                                <th>Client Name</th>
                                <th>Animal Type / Quantity</th>
                                <th>Total Quantity</th>
                                <th>Drop-off Address</th>
                                <th>Request Date</th>
                                <th>Status</th>
                                <?php if (hasPermission('bookings_approve')): ?>
                                    <th>Action</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pendingBookings as $booking): ?>
                                <?php
                                    $dropOffAddress = trim(
                                        ($booking['receiver_street'] ?? '') . ', ' .
                                        ($booking['receiver_municipality'] ?? '') . ', ' .
                                        ($booking['receiver_province'] ?? '')
                                    );
                                ?>
                                <tr>
                                    <td>
                                        <span class="booking-id">#<?= h($booking['booking_ID']) ?></span>
                                        <span class="muted-small">Submitted: <?= h(formatDateReadable($booking['booking_startdate'] ?? '')) ?></span>
                                    </td>
                                    <td><?= h(trim(($booking['client_firstname'] ?? '') . ' ' . ($booking['client_lastname'] ?? ''))) ?></td>
                                    <td class="animal-list"><?= h($booking['animal_details'] ?? 'No animals listed') ?></td>
                                    <td><?= h((int)($booking['total_quantity'] ?? 0)) ?> head(s)</td>
                                    <td class="drop-address"><?= h($dropOffAddress) ?></td>
                                    <td><?= h(formatDateReadable($booking['booking_requestdate'] ?? '')) ?></td>
                                    <td>
                                        <span class="status-badge status-pending-review">
                                            <?= h($booking['booking_status']) ?>
                                        </span>
                                    </td>

                                    <?php if (hasPermission('bookings_approve')): ?>
                                        <td>
                                            <div class="action-buttons">
                                                <form method="POST" action="process-pending-booking.php" onsubmit="return confirm('Approve this booking and move it to Processing?');">
                                                    <input type="hidden" name="booking_id" value="<?= h($booking['booking_ID']) ?>">
                                                    <input type="hidden" name="action" value="approve">
                                                    <button class="approve-btn" type="submit">
                                                        <i class="fa-solid fa-check"></i> Approve
                                                    </button>
                                                </form>

                                                <form method="POST" action="process-pending-booking.php" onsubmit="return confirm('Reject this booking?');">
                                                    <input type="hidden" name="booking_id" value="<?= h($booking['booking_ID']) ?>">
                                                    <input type="hidden" name="action" value="reject">
                                                    <button class="reject-btn" type="submit">
                                                        <i class="fa-solid fa-xmark"></i> Reject
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </section>
    </main>
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../../public/js/all.js"></script>
</body>
</html>
