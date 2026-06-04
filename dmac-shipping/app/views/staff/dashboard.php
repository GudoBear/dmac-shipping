<?php
require_once __DIR__ . '/../../helpers/auth.php';
requireEmployeeLogin();

require_once __DIR__ . '/../../helpers/staff_nav.php';
require_once '../../../config/database.php';
require_once '../../models/Employee.php';

$db = (new Database())->getConnection();
$employeeModel = new Employee($db);

$stats = $employeeModel->getDashboardStats();
$shipments = $employeeModel->getAllShipments(6);
$feedbackList = $employeeModel->getFeedback(8);

function h($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function dashboardStatusClass($status) {
    $status = strtoupper(trim((string)$status));

    switch ($status) {
        case 'PENDING REVIEW':
            return 'status-pending';
        case 'FOR PICK-UP':
            return 'status-pickup';
        case 'PROCESSING':
            return 'status-processing';
        case 'PREPARING FOR TRANSIT':
            return 'status-preparing';
        case 'IN TRANSIT':
            return 'status-transit';
        case 'COMPLETED':
        case 'DELIVERED':
        case 'SHIPPED':
            return 'status-delivered';
        default:
            return 'status-default';
    }
}
?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Employee Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../../../public/css/all.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="dashboard-body">
<?php showStaffSidebar(); ?>

<div class="main-content">
    <header>
        <div class="header-title">
            <h1>Employee Dashboard</h1>
            <small>Booking status overview and client feedback summary</small>
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
        <?php if (isset($_GET['unauthorized'])): ?>
            <div class="alert-success alert-error">You do not have permission to open that page.</div>
        <?php endif; ?>

        <section class="dash-hero">
            <div>
                <h2>Shipment Operations Overview</h2>
                <p>Track booking progress by status and monitor recent client feedback in one clean dashboard.</p>
            </div>
            <div class="dash-hero-badges">
                <span class="dash-pill"><i class="fa-solid fa-calendar-day"></i> Today</span>
                <span class="dash-pill"><i class="fa-solid fa-location-dot"></i> DMAC Shipping</span>
            </div>
        </section>

        <section class="status-grid">
            <div class="status-card">
                <div class="status-card-top">
                    <span>Pending Bookings</span>
                    <div class="icon"><i class="fa-solid fa-clock"></i></div>
                </div>
                <h3><?= h($stats['pending_bookings'] ?? 0) ?></h3>
            </div>

            <div class="status-card">
                <div class="status-card-top">
                    <span>For Pick Up</span>
                    <div class="icon"><i class="fa-solid fa-box-open"></i></div>
                </div>
                <h3><?= h($stats['for_pickup'] ?? 0) ?></h3>
            </div>

            <div class="status-card">
                <div class="status-card-top">
                    <span>Processing</span>
                    <div class="icon"><i class="fa-solid fa-gears"></i></div>
                </div>
                <h3><?= h($stats['processing'] ?? 0) ?></h3>
            </div>

            <div class="status-card">
                <div class="status-card-top">
                    <span>Preparing for Transit</span>
                    <div class="icon"><i class="fa-solid fa-boxes-packing"></i></div>
                </div>
                <h3><?= h($stats['preparing_for_transit'] ?? 0) ?></h3>
            </div>

            <div class="status-card">
                <div class="status-card-top">
                    <span>In-Transit</span>
                    <div class="icon"><i class="fa-solid fa-truck-fast"></i></div>
                </div>
                <h3><?= h($stats['in_transit'] ?? 0) ?></h3>
            </div>

            <div class="status-card">
                <div class="status-card-top">
                    <span>Delivered / Shipped</span>
                    <div class="icon"><i class="fa-solid fa-circle-check"></i></div>
                </div>
                <h3><?= h($stats['delivered'] ?? 0) ?></h3>
            </div>
        </section>

        <section class="insight-grid">
            <div class="insight-card">
                <div class="icon"><i class="fa-solid fa-comments"></i></div>
                <div>
                    <h3><?= h($stats['feedback_count'] ?? 0) ?></h3>
                    <span>Total Client Feedback</span>
                </div>
            </div>

            <div class="insight-card">
                <div class="icon"><i class="fa-solid fa-star"></i></div>
                <div>
                    <h3><?= h($stats['average_rating'] ?? '0.0') ?></h3>
                    <span>Average Rating</span>
                </div>
            </div>
        </section>

        <section class="dashboard-panels">
            <div class="dashboard-card">
                <div class="section-header">
                    <div>
                        <h3>Recent Shipments</h3>
                        <small>Latest bookings and shipment status updates</small>
                    </div>
                    <a class="btn-primary-sm" href="active-shipments.php">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i> View Active
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Client</th>
                                <th>Route</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (empty($shipments)): ?>
                                <tr>
                                    <td class="empty-state" colspan="5">No shipments found.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($shipments as $shipment): ?>
                                    <tr>
                                        <td><strong>#<?= h($shipment['booking_ID']) ?></strong></td>
                                        <td>
                                            <strong><?= h($shipment['client_firstname'] . ' ' . $shipment['client_lastname']) ?></strong>
                                            <div class="muted-small">Client booking</div>
                                        </td>
                                        <td>
                                            <div class="route-text"><?= h($shipment['pickup_municipality']) ?> → <?= h($shipment['receiver_municipality']) ?></div>
                                        </td>
                                        <td><?= h($shipment['booking_requestdate']) ?></td>
                                        <td>
                                            <span class="status-badge <?= h(dashboardStatusClass($shipment['booking_status'])) ?>">
                                                <?= h($shipment['booking_status']) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="dashboard-card">
                <div class="section-header">
                    <div>
                        <h3>Client Feedback Summary</h3>
                        <small>Recent booking comments and ratings</small>
                    </div>
                    <a class="btn-primary-sm" href="feedback.php">
                        <i class="fa-solid fa-list"></i> View All
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="modern-table feedback-table">
                        <thead>
                            <tr>
                                <th>Booking</th>
                                <th>Client</th>
                                <th>Comment</th>
                                <th>Rate</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (empty($feedbackList)): ?>
                                <tr>
                                    <td class="empty-state" colspan="4">No client feedback yet.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($feedbackList as $feedback): ?>
                                    <tr>
                                        <td><strong>#<?= h($feedback['booking_ID']) ?></strong></td>
                                        <td><?= h($feedback['client_firstname'] . ' ' . $feedback['client_lastname']) ?></td>
                                        <td class="feedback-comment"><?= h($feedback['feed_comment']) ?></td>
                                        <td>
                                            <span class="rating-pill">
                                                <i class="fa-solid fa-star"></i>
                                                <?= h($feedback['feed_rate']) ?>/5
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../../public/js/all.js"></script>
</body>
</html>
