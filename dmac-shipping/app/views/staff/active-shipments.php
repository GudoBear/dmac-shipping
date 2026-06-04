<?php
require_once __DIR__ . '/../../helpers/auth.php';
requireAnyPermission(['shipments_view','shipments_update']);
require_once __DIR__ . '/../../helpers/staff_nav.php';
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../models/Employee.php';

$db = (new Database())->getConnection();
$model = new Employee($db);
$activeShipments = $model->getActiveShipments();
$drivers = method_exists($model, 'getLandDrivers') ? $model->getLandDrivers() : [];
$airAgents = method_exists($model, 'getAirShipperAgents') ? $model->getAirShipperAgents() : [];
$vehicles = method_exists($model, 'getVehicles') ? $model->getVehicles() : [];

function h($value) { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }

function transportIdFromShipment($shipment) {
    if (!empty($shipment['transport_ID'])) {
        return (int)$shipment['transport_ID'];
    }

    $type = strtoupper(trim((string)($shipment['shipment_type'] ?? '')));
    if ($type === 'AIR') return 1;
    if ($type === 'LAND') return 2;
    return 0;
}

function transportLabel($shipment) {
    $id = transportIdFromShipment($shipment);
    if ($id === 1) return 'Air Travel';
    if ($id === 2) return 'Land Travel';
    return 'Not set';
}

function transportClass($shipment) {
    $id = transportIdFromShipment($shipment);
    if ($id === 1) return 'air';
    if ($id === 2) return 'land';
    return 'notset';
}

function statusClass($status) {
    $status = strtoupper(trim((string)$status));
    switch ($status) {
        case 'FOR PICK-UP':
            return 'status-pickup';
        case 'PROCESSING':
            return 'status-processing';
        case 'PREPARING FOR TRANSIT':
            return 'status-preparing';
        case 'IN TRANSIT':
            return 'status-transit';
        case 'DELIVERED/SHIPPED':
        case 'DELIVERED':
        case 'SHIPPED':
            return 'status-delivered';
        case 'CANCELLED':
            return 'status-cancelled';
        default:
            return 'status-default';
    }
}

function travelDetails($shipment) {
    $transportId = transportIdFromShipment($shipment);
    $items = [];

    if ($transportId === 1) {
        if (!empty($shipment['shipper_agent'])) {
            $items[] = '<strong>Shipper Agent:</strong> ' . h($shipment['shipper_agent']);
        }
        if (!empty($shipment['airline_name'])) {
            $items[] = '<strong>Airline:</strong> ' . h($shipment['airline_name']);
        }
        if (!empty($shipment['flight_reference_number'])) {
            $items[] = '<strong>Flight Ref:</strong> ' . h($shipment['flight_reference_number']);
        }
    } elseif ($transportId === 2) {
        $driverName = trim(($shipment['driver_firstname'] ?? '') . ' ' . ($shipment['driver_lastname'] ?? ''));
        if ($driverName !== '') {
            $items[] = '<strong>Driver:</strong> ' . h($driverName);
        }
        if (!empty($shipment['vehicle_type'])) {
            $items[] = '<strong>Vehicle:</strong> ' . h($shipment['vehicle_type']);
        }
        if (!empty($shipment['vehicle_plate_number'])) {
            $items[] = '<strong>Plate:</strong> ' . h($shipment['vehicle_plate_number']);
        }
        if (!empty($shipment['vehicle_license_permit'])) {
            $items[] = '<strong>Permit:</strong> ' . h($shipment['vehicle_license_permit']);
        }
    }

    if (!$items) {
        return '<span class="muted-text">Travel details not set</span>';
    }

    return implode('<br>', $items);
}

$statuses = [
    'FOR PICK-UP' => 'For Pick Up',
    'PROCESSING' => 'Processing',
    'PREPARING FOR TRANSIT' => 'Preparing for Transit',
    'IN TRANSIT' => 'In-Transit',
    'DELIVERED/SHIPPED' => 'Delivered / Shipped',
    'CANCELLED' => 'Cancelled'
];

$airlineOptions = [
    'Cebu Pacific',
    'Philippine Airlines'
];
?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>For Pick-up Bookings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../../../public/css/all.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="dashboard-body">
<?php showStaffSidebar(); ?>

<div class="main-content">
    <header>
        <div class="header-title">
            <h1>For Pick-up Bookings</h1>
            <small>Set transportation mode and travel details for approved bookings.</small>
        </div>
    </header>

    <main>
        <section class="shipments-hero">
            <div>
                <h1>For Pick-up Booking Setup</h1>
                <p>Choose Air or Land travel, then fill the proper travel details. After saving, the booking moves to Coordinator Assignments.</p>
            </div>
            <div class="hero-chip"><i class="fa-solid fa-truck-fast"></i> <?= h(count($activeShipments)) ?> For Pick-up Bookings</div>
        </section>

        <?php if(isset($_GET['updated'])): ?>
            <?php if ($_GET['updated'] === '1'): ?>
                <div class="alert-success"><i class="fa-solid fa-circle-check"></i> Shipment details updated successfully.</div>
            <?php else: ?>
                <div class="alert-success alert-error"><i class="fa-solid fa-triangle-exclamation"></i> Shipment update failed. Please check transport mode, required travel details, and status.</div>
            <?php endif; ?>
        <?php endif; ?>

        <section class="clean-panel">
            <div class="panel-head">
                <div>
                    <h2>For Pick-up Booking Records</h2>
                    <p>Air requires shipper agent, airline, and flight reference number. Land requires driver and vehicle plate number.</p>
                </div>
                <?php if (hasAnyPermission(['bookings_assign','shipments_update'])): ?>
                    <a class="close-x" href="assignments.php"><i class="fa-solid fa-user-pen"></i> Coordinator Assignments</a>
                <?php endif; ?>
            </div>

            <?php if(empty($activeShipments)): ?>
                <div class="empty-state"><i class="fa-regular fa-folder-open"></i><br>No for pick-up bookings need shipment setup.</div>
            <?php else: ?>
                <div class="ship-table-wrap">
                    <table class="ship-table">
                        <thead>
                            <tr>
                                <th>Booking</th>
                                <th>Client / Route</th>
                                <th>Delivery Date</th>
                                <th>Transport / Travel Details</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($activeShipments as $shipment): ?>
                                <?php $bookingId = (int)$shipment['booking_ID']; ?>
                                <tr>
                                    <td>
                                        <div class="booking-title">#<?= h($bookingId) ?></div>
                                        <div class="meta-line">Heads: <?= h($shipment['total_heads']) ?></div>
                                    </td>
                                    <td>
                                        <div><strong><?= h($shipment['client_firstname'].' '.$shipment['client_lastname']) ?></strong></div>
                                        <div class="route-text"><?= h($shipment['pickup_municipality'].' → '.$shipment['receiver_municipality']) ?></div>
                                        <div class="meta-line"><?= h(trim(($shipment['receiver_street'] ?? '') . ', ' . ($shipment['receiver_province'] ?? ''), ', ')) ?></div>
                                    </td>
                                    <td><?= h($shipment['booking_enddate'] ?: 'Not set') ?></td>
                                    <td>
                                        <div class="transport-box">
                                            <span class="transport-pill <?= h(transportClass($shipment)) ?>"><i class="fa-solid fa-route"></i> <?= h(transportLabel($shipment)) ?></span>
                                            <div class="courier-details"><?= travelDetails($shipment) ?></div>
                                        </div>
                                    </td>
                                    <td><span class="status-badge <?= h(statusClass($shipment['booking_status'])) ?>"><?= h($shipment['booking_status']) ?></span></td>
                                    <td>
                                        <?php if (hasPermission('shipments_update')): ?>
                                            <a class="btn-edit" href="#edit-shipment-<?= h($bookingId) ?>"><i class="fa-solid fa-pen-to-square"></i> Set Transport</a>
                                        <?php else: ?>
                                            <span class="muted">View only</span>
                                        <?php endif; ?>
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

<?php foreach($activeShipments as $shipment): ?>
    <?php
        $bookingId = (int)$shipment['booking_ID'];
        $currentTransportId = transportIdFromShipment($shipment);
        if (!in_array($currentTransportId, [1,2], true)) $currentTransportId = 2;
    ?>
    <div class="modal" id="edit-shipment-<?= h($bookingId) ?>">
        <div class="modal-card">
            <div class="modal-head">
                <div>
                    <h2>Set Transport for Booking #<?= h($bookingId) ?></h2>
                    <p><?= h($shipment['client_firstname'].' '.$shipment['client_lastname']) ?> · <?= h($shipment['pickup_municipality'].' → '.$shipment['receiver_municipality']) ?></p>
                </div>
                <a href="#" class="close-x"><i class="fa-solid fa-xmark"></i> Close</a>
            </div>
            <div class="modal-body">
                <form method="POST" action="process-status-update.php">
                    <input type="hidden" name="booking_id" value="<?= h($bookingId) ?>">

                    <div class="form-grid">
                        <div class="form-group">
                            <label>Transportation Mode</label>
                            <select name="transport_id" class="transport-mode-select" data-booking="<?= h($bookingId) ?>" required>
                                <option value="1" <?= $currentTransportId === 1 ? 'selected' : '' ?>>Air Travel</option>
                                <option value="2" <?= $currentTransportId === 2 ? 'selected' : '' ?>>Land Travel</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Shipment Status</label>
                            <select name="booking_status" required>
                                <option value="FOR PICK-UP" selected>For Pick Up</option>
                            </select>
                        </div>

                        <section class="detail-section air-box travel-section" data-type="air" data-booking="<?= h($bookingId) ?>">
                            <h3><i class="fa-solid fa-plane"></i> Air Details</h3>
                            <div class="form-grid">
                                <div class="form-group">
                                    <label>Shipper Agent</label>
                                    <select name="air_emp_ID" required>
                                        <option value="">Select shipper agent</option>
                                        <?php foreach ($airAgents as $agent): ?>
                                            <option value="<?= h($agent['emp_ID']) ?>" <?= (int)($shipment['air_emp_ID'] ?? 0) === (int)$agent['emp_ID'] ? 'selected' : '' ?>>
                                                <?= h($agent['emp_firstname'].' '.$agent['emp_lastname'].' · '.$agent['role']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php if (empty($airAgents)): ?>
                                        <small class="muted-text">No shipper agents found. Add employees with the Shipper Agent role first.</small>
                                    <?php endif; ?>
                                </div>
                                <div class="form-group">
                                    <label>Airline</label>
                                    <select name="airline" required>
                                        <option value="">Select airline</option>
                                        <?php foreach ($airlineOptions as $airline): ?>
                                            <option value="<?= h($airline) ?>" <?= ($shipment['airline_name'] ?? '') === $airline ? 'selected' : '' ?>>
                                                <?= h($airline) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group full">
                                    <label>Flight Reference Number</label>
                                    <input type="text" name="flight_reference_number" value="<?= h($shipment['flight_reference_number'] ?? '') ?>" placeholder="Example: PR-1234 / AWB-00001" required>
                                </div>
                            </div>
                        </section>

                        <section class="detail-section land-box travel-section" data-type="land" data-booking="<?= h($bookingId) ?>">
                            <h3><i class="fa-solid fa-truck"></i> Land Details</h3>
                            <div class="form-grid">
                                <div class="form-group">
                                    <label>Driver</label>
                                    <select name="driver_emp_ID" required>
                                        <option value="">Select driver</option>
                                        <?php foreach ($drivers as $driver): ?>
                                            <option value="<?= h($driver['emp_ID']) ?>" <?= (int)($shipment['driver_emp_ID'] ?? 0) === (int)$driver['emp_ID'] ? 'selected' : '' ?>>
                                                <?= h($driver['emp_firstname'].' '.$driver['emp_lastname'].' · '.$driver['role']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Vehicle Plate Number</label>
                                    <select name="vehicle_ID" required>
                                        <option value="">Select a vehicle plate number</option>
                                        <?php foreach ($vehicles as $vehicle): ?>
                                            <option value="<?= h($vehicle['vehicle_ID']) ?>" <?= (int)($shipment['vehicle_ID'] ?? 0) === (int)$vehicle['vehicle_ID'] ? 'selected' : '' ?>>
                                                <?= h(($vehicle['vehicle_plate_number'] ? $vehicle['vehicle_plate_number'] : 'Vehicle #'.$vehicle['vehicle_ID'])) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php if (empty($vehicles)): ?>
                                        <small class="muted-text">No vehicles are currently available. Add vehicles to the vehicle table before saving land shipment details.</small>
                                    <?php else: ?>
                                        <small>Vehicle plate number is loaded from the vehicle table.</small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </section>
                    </div>

                    <div class="form-actions">
                        <a href="#" class="close-x">Cancel</a>
                        <button class="btn-save" type="submit"><i class="fa-solid fa-floppy-disk"></i> Save Transport Setup</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../../public/js/all.js"></script>
</body>
</html>
