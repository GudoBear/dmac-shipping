<?php
require_once __DIR__ . '/../../helpers/auth.php';
requireAnyPermission(['shipments_view','bookings_view']);
require_once __DIR__ . '/../../helpers/staff_nav.php';
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../models/Employee.php';

$db = (new Database())->getConnection();
$model = new Employee($db);
$records = $model->getBookingRecords();

function h($value) { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }

function recordTransportLabel($record) {
    $id = (int)($record['transport_ID'] ?? 0);
    $type = strtoupper(trim((string)($record['shipment_type'] ?? '')));
    if ($id === 1 || $type === 'AIR') return 'Air Travel';
    if ($id === 2 || $type === 'LAND') return 'Land Travel';
    return 'Not set';
}

function recordCourierDetails($record) {
    $id = (int)($record['transport_ID'] ?? 0);
    $type = strtoupper(trim((string)($record['shipment_type'] ?? '')));
    $items = [];

    if ($id === 1 || $type === 'AIR') {
        if (!empty($record['shipper_agent'])) $items[] = '<strong>Agent:</strong> ' . h($record['shipper_agent']);
        if (!empty($record['airline_name'])) $items[] = '<strong>Airline:</strong> ' . h($record['airline_name']);
        if (!empty($record['flight_reference_number'])) $items[] = '<strong>Flight Ref:</strong> ' . h($record['flight_reference_number']);
    } elseif ($id === 2 || $type === 'LAND') {
        $driver = trim(($record['driver_firstname'] ?? '') . ' ' . ($record['driver_lastname'] ?? ''));
        if ($driver !== '') $items[] = '<strong>Driver:</strong> ' . h($driver);
        if (!empty($record['vehicle_plate_number'])) $items[] = '<strong>Plate:</strong> ' . h($record['vehicle_plate_number']);
        if (!empty($record['vehicle_license_permit'])) $items[] = '<strong>Permit:</strong> ' . h($record['vehicle_license_permit']);
    }

    return $items ? implode('<br>', $items) : '<span class="muted">Courier details not set</span>';
}
?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Booking Records</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../../../public/css/all.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="dashboard-body">
<?php showStaffSidebar(); ?>
<div class="main-content">
    <header>
        <div class="header-title">
            <h1>Booking Records</h1>
            <small>Completed and delivered/shipped bookings.</small>
        </div>
    </header>

    <main>
        <section class="records-hero">
            <h1>Delivered / Shipped Records</h1>
            <p>Bookings appear here after admin or coordinator updates the status to Delivered / Shipped.</p>
        </section>

        <section class="records-panel">
            <div class="panel-head">
                <h2>Completed Booking Records</h2>
                <p>Total records: <?= h(count($records)) ?></p>
            </div>

            <?php if (empty($records)): ?>
                <div class="empty-state"><i class="fa-regular fa-folder-open"></i><br>No delivered/shipped records yet.</div>
            <?php else: ?>
                <div class="records-table-wrap">
                    <table class="records-table">
                        <thead>
                            <tr>
                                <th>Booking</th>
                                <th>Client</th>
                                <th>Animal / Quantity</th>
                                <th>Pickup Address</th>
                                <th>Drop-off Address</th>
                                <th>Transport / Courier</th>
                                <th>Delivery Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($records as $record): ?>
                                <tr>
                                    <td>
                                        <div class="booking-title">#<?= h($record['booking_ID']) ?></div>
                                        <div class="meta-line">Request: <?= h($record['booking_requestdate']) ?></div>
                                    </td>
                                    <td><?= h($record['client_firstname'] . ' ' . $record['client_lastname']) ?></td>
                                    <td>
                                        <?= h($record['animal_details']) ?><br>
                                        <span class="muted">Total heads: <?= h($record['total_heads']) ?></span>
                                    </td>
                                    <td><?= h(trim(($record['pickup_street'] ?? '') . ', ' . ($record['pickup_municipality'] ?? '') . ', ' . ($record['pickup_province'] ?? ''), ', ')) ?></td>
                                    <td><?= h(trim(($record['receiver_street'] ?? '') . ', ' . ($record['receiver_municipality'] ?? '') . ', ' . ($record['receiver_province'] ?? ''), ', ')) ?></td>
                                    <td>
                                        <strong><?= h(recordTransportLabel($record)) ?></strong>
                                        <div class="transport-details"><?= recordCourierDetails($record) ?></div>
                                    </td>
                                    <td><?= h($record['booking_enddate'] ?: 'Not set') ?></td>
                                    <td><span class="status-badge"><?= h($record['booking_status']) ?></span></td>
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
