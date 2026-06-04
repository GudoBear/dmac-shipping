<?php
require_once __DIR__ . '/../../helpers/auth.php';
requireAnyPermission(['bookings_view','bookings_approve','bookings_assign','shipments_view','shipments_update']);
require_once __DIR__ . '/../../helpers/staff_nav.php';
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../models/Employee.php';

$db = (new Database())->getConnection();
$model = new Employee($db);
$bookings = $model->getBookingsForCoordinatorAssignments();
$employees = $model->getAssignableEmployees();
$bookingIds = array_column($bookings, 'booking_ID');
$assignmentHistory = $model->getAssignmentsForBookings($bookingIds);
$currentAssignments = $model->getCurrentStageAssignments($bookingIds);

function h($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function stageLabel($stage) {
    return [
        'PICKUP' => 'Pickup',
        'PROCESSING' => 'Processing',
        'IN_TRANSIT' => 'In-Transit',
        'ARRIVAL' => 'Arrival',
        'DELIVERED' => 'Delivered'
    ][$stage] ?? $stage;
}

function statusClass($status) {
    $status = strtoupper((string)$status);
    if (strpos($status, 'PENDING') !== false) return 'status-pending';
    if (strpos($status, 'PROCESS') !== false) return 'status-processing';
    if (strpos($status, 'TRANSIT') !== false) return 'status-transit';
    if (strpos($status, 'DELIVER') !== false || strpos($status, 'ARRIVAL') !== false) return 'status-done';
    return 'status-default';
}

function assignmentShipmentTypeLabel($booking) {
    $id = !empty($booking['transport_ID']) ? (int)$booking['transport_ID'] : 0;
    $type = strtoupper(trim((string)($booking['shipment_type'] ?? '')));

    if ($id === 1 || $type === 'AIR') return 'Air Travel';
    if ($id === 2 || $type === 'LAND') return 'Land Travel';
    return 'Not set';
}

function assignmentCourierDetails($booking) {
    $id = !empty($booking['transport_ID']) ? (int)$booking['transport_ID'] : 0;
    $type = strtoupper(trim((string)($booking['shipment_type'] ?? '')));
    $items = [];

    if ($id === 1 || $type === 'AIR') {
        if (!empty($booking['shipper_agent'])) {
            $items[] = '<strong>Shipper Agent:</strong> ' . h($booking['shipper_agent']);
        }
        if (!empty($booking['airline_name'])) {
            $items[] = '<strong>Airline:</strong> ' . h($booking['airline_name']);
        }
        if (!empty($booking['flight_reference_number'])) {
            $items[] = '<strong>Flight Ref:</strong> ' . h($booking['flight_reference_number']);
        }
    } elseif ($id === 2 || $type === 'LAND') {
        $driverName = trim(($booking['driver_firstname'] ?? '') . ' ' . ($booking['driver_lastname'] ?? ''));
        if ($driverName !== '') {
            $items[] = '<strong>Driver:</strong> ' . h($driverName);
        }
        if (!empty($booking['vehicle_type'])) {
            $items[] = '<strong>Vehicle:</strong> ' . h($booking['vehicle_type']);
        }
        if (!empty($booking['vehicle_plate_number'])) {
            $items[] = '<strong>Plate:</strong> ' . h($booking['vehicle_plate_number']);
        }
        if (!empty($booking['vehicle_license_permit'])) {
            $items[] = '<strong>Permit:</strong> ' . h($booking['vehicle_license_permit']);
        }
    }

    if (!$items) {
        return '<span class="muted">Travel details not set</span>';
    }

    return implode('<br>', $items);
}

$stages = ['PICKUP', 'PROCESSING', 'IN_TRANSIT', 'ARRIVAL', 'DELIVERED'];
$displayStages = ['PICKUP', 'PROCESSING', 'IN_TRANSIT', 'ARRIVAL'];
$canAssign = hasAnyPermission(['bookings_assign','shipments_update']);
$totalBookings = count($bookings);
$totalAssignedStages = 0;
foreach ($currentAssignments as $rows) {
    $totalAssignedStages += count($rows);
}
?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Coordinator Assignments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../../../public/css/all.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="dashboard-body">
<?php showStaffSidebar(); ?>

<div class="main-content">
    <header>
        <div class="header-title">
            <h1>Coordinator Assignments</h1>
            <small>Assign employees per booking stage without losing past assignment records.</small>
        </div>
        <div class="page-actions">
            <a href="active-shipments.php" class="btn-primary-soft"><i class="fa-solid fa-truck-ramp-box"></i> For Pick-up Bookings</a>
            <a href="booking-records.php" class="btn-outline"><i class="fa-solid fa-box-archive"></i> Booking Records</a>
        </div>
    </header>

    <main>

        <?php if ($canAssign && empty($employees)): ?>
            <div class="alert-success alert-error" style="background:#fff7ed;color:#9a3412;border-color:#fed7aa;">
                No coordinators found. Go to <b>Manage Employees</b>, edit an employee, and check <b>Set this employee as Coordinator</b>.
            </div>
        <?php endif; ?>

        <?php if (($_GET['status'] ?? '') === 'assigned'): ?>
            <div class="alert-success"><i class="fa-solid fa-circle-check"></i> Coordinator assignment saved successfully.</div>
        <?php elseif (($_GET['status'] ?? '') === 'status_updated'): ?>
            <div class="alert-success"><i class="fa-solid fa-circle-check"></i> Booking status updated successfully.</div>
        <?php elseif (isset($_GET['status'])): ?>
            <div class="alert-error"><i class="fa-solid fa-triangle-exclamation"></i> Assignment failed. Please check the booking, employee, and stage.</div>
        <?php endif; ?>

        <div class="summary-grid">
            <div class="summary-card"><div class="summary-icon"><i class="fa-solid fa-boxes-stacked"></i></div><div><h3><?= h($totalBookings) ?></h3><p>Bookings available</p></div></div>
            <div class="summary-card"><div class="summary-icon"><i class="fa-solid fa-user-check"></i></div><div><h3><?= h($totalAssignedStages) ?></h3><p>Assigned stages</p></div></div>
            <div class="summary-card"><div class="summary-icon"><i class="fa-solid fa-layer-group"></i></div><div><h3><?= h(count($stages)) ?></h3><p>Tracked stages</p></div></div>
        </div>

        <section class="assignment-panel">
            <div class="panel-head">
                <div>
                    <h2>Booking Assignment Records</h2>
                    <p>Use Manage to assign coordinators and update the booking status. Delivered/Shipped bookings move to Booking Records.</p>
                </div>
                <span class="status-badge status-default">History Enabled</span>
            </div>

            <?php if (empty($bookings)): ?>
                <div class="empty-state"><i class="fa-regular fa-folder-open"></i><br>No bookings available for assignment.</div>
            <?php else: ?>
                <div class="assign-table-wrap">
                    <table class="assign-table">
                        <thead>
                            <tr>
                                <th>Booking</th>
                                <th>Client / Route</th>
                                <th>Status</th>
                                <th>Transport / Courier</th>
                                <th>Current Coordinators</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($bookings as $booking): ?>
                                <?php
                                    $bookingId = (int)$booking['booking_ID'];
                                    $historyRows = $assignmentHistory[$bookingId] ?? [];
                                    $currentRows = $currentAssignments[$bookingId] ?? [];
                                ?>
                                <tr>
                                    <td>
                                        <div class="booking-title">#<?= h($bookingId) ?></div>
                                        <div class="meta-line">Request: <?= h($booking['booking_requestdate']) ?></div>
                                        <div class="meta-line">Heads: <?= h($booking['total_heads']) ?></div>
                                    </td>
                                    <td>
                                        <div><strong><?= h($booking['client_firstname'] . ' ' . $booking['client_lastname']) ?></strong></div>
                                        <div class="route-text"><?= h($booking['pickup_municipality'] . ' → ' . $booking['receiver_municipality']) ?></div>
                                    </td>
                                    <td><span class="status-badge <?= h(statusClass($booking['booking_status'])) ?>"><?= h($booking['booking_status']) ?></span></td>
                                    <td>
                                        <?php
                                            $type = strtoupper(trim((string)($booking['shipment_type'] ?? '')));
                                            $typeClass = $type === 'AIR' ? 'air' : ($type === 'LAND' ? '' : 'notset');
                                        ?>
                                        <div class="transport-mini">
                                            <span class="pill <?= h($typeClass) ?>"><i class="fa-solid fa-route"></i> <?= h(assignmentShipmentTypeLabel($booking)) ?></span>
                                            <div class="transport-details"><?= assignmentCourierDetails($booking) ?></div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="stage-chips">
                                            <?php foreach ($displayStages as $stage): ?>
                                                <?php $current = $currentRows[$stage] ?? null; ?>
                                                <span class="stage-chip <?= $current ? 'assigned' : '' ?>">
                                                    <strong><?= h(stageLabel($stage)) ?>:</strong>
                                                    <?= $current ? h($current['emp_firstname'] . ' ' . $current['emp_lastname']) : 'None' ?>
                                                </span>
                                            <?php endforeach; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <a class="btn-green" href="#assign-<?= h($bookingId) ?>"><i class="fa-solid fa-user-pen"></i> Manage</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </section>
    </main>
</div>

<?php foreach ($bookings as $booking): ?>
    <?php
        $bookingId = (int)$booking['booking_ID'];
        $historyRows = $assignmentHistory[$bookingId] ?? [];
        $currentRows = $currentAssignments[$bookingId] ?? [];
    ?>
    <div class="modal" id="assign-<?= h($bookingId) ?>">
        <div class="modal-card">
            <div class="modal-head">
                <div>
                    <h2>Manage Booking #<?= h($bookingId) ?></h2>
                    <p><?= h($booking['client_firstname'] . ' ' . $booking['client_lastname']) ?> · <?= h($booking['pickup_municipality'] . ' → ' . $booking['receiver_municipality']) ?></p>
                </div>
                <a href="#" class="close-x"><i class="fa-solid fa-xmark"></i> Close</a>
            </div>
            <div class="modal-body">
                <div class="transport-details" style="margin-bottom:18px;">
                    <strong>Travel Details:</strong> <?= h(assignmentShipmentTypeLabel($booking)) ?><br>
                    <?= assignmentCourierDetails($booking) ?>
                </div>

                <?php if ($canAssign): ?>
                    <form class="status-update-form" method="POST" action="process-assignment.php">
                        <input type="hidden" name="action" value="update_status">
                        <input type="hidden" name="booking_id" value="<?= h($bookingId) ?>">
                        <div class="form-group">
                            <label>Update Booking Status</label>
                            <select name="booking_status" required>
                                <?php
                                    $statusOptions = [
                                        'FOR PICK-UP' => 'For Pick Up',
                                        'PROCESSING' => 'Processing',
                                        'PREPARING FOR TRANSIT' => 'Preparing for Transit',
                                        'IN TRANSIT' => 'In-Transit',
                                        'DELIVERED/SHIPPED' => 'Delivered / Shipped'
                                    ];
                                ?>
                                <?php foreach ($statusOptions as $value => $label): ?>
                                    <option value="<?= h($value) ?>" <?= $booking['booking_status'] === $value ? 'selected' : '' ?>><?= h($label) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button class="btn-save" type="submit"><i class="fa-solid fa-arrows-rotate"></i> Save Status</button>
                    </form>
                <?php endif; ?>

                <div class="stage-grid">
                    <?php foreach ($stages as $stage): ?>
                        <?php $current = $currentRows[$stage] ?? null; ?>
                        <div class="stage-card">
                            <div class="stage-card-title">
                                <h3><?= h(stageLabel($stage)) ?></h3>
                                <div class="current-small">
                                    Current:<br>
                                    <strong><?= $current ? h($current['emp_firstname'] . ' ' . $current['emp_lastname']) : 'Not assigned' ?></strong>
                                </div>
                            </div>
                            <?php if ($canAssign): ?>
                                <form class="assign-form" method="POST" action="process-assignment.php">
                                    <input type="hidden" name="booking_id" value="<?= h($bookingId) ?>">
                                    <input type="hidden" name="process_stage" value="<?= h($stage) ?>">
                                    <select name="emp_id" required>
                                        <option value="">Choose coordinator</option>
                                        <?php foreach ($employees as $employee): ?>
                                            <option value="<?= h($employee['emp_ID']) ?>" <?= $current && (int)$current['emp_ID'] === (int)$employee['emp_ID'] ? 'selected' : '' ?>>
                                                <?= h($employee['emp_firstname'] . ' ' . $employee['emp_lastname'] . ' — Coordinator') ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <textarea name="notes" placeholder="Optional notes for this assignment"></textarea>
                                    <button class="btn-save" type="submit"><i class="fa-solid fa-floppy-disk"></i> Save <?= h(stageLabel($stage)) ?></button>
                                </form>
                            <?php else: ?>
                                <p class="muted">You can view assignments, but you do not have permission to update them.</p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="history-section">
                    <h3>Assignment History</h3>
                    <?php if (empty($historyRows)): ?>
                        <div class="history-item">No assignment history yet.</div>
                    <?php else: ?>
                        <div class="history-list">
                            <?php foreach ($historyRows as $row): ?>
                                <div class="history-item">
                                    <strong><?= h(stageLabel($row['process_stage'])) ?>:</strong>
                                    <?= h($row['emp_firstname'] . ' ' . $row['emp_lastname']) ?>
                                    <br>
                                    Assigned by <?= h(trim(($row['assigned_by_firstname'] ?? '') . ' ' . ($row['assigned_by_lastname'] ?? '')) ?: 'System') ?>
                                    · <?= h($row['assigned_at']) ?>
                                    <?php if (!empty($row['notes'])): ?>
                                        <div class="history-note">Note: <?= h($row['notes']) ?></div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../../public/js/all.js"></script>
</body>
</html>
