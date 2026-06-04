<?php
require_once __DIR__ . '/../../helpers/auth.php';
requireAnyPermission(['accounting_view','accounting_manage']);
require_once __DIR__ . '/../../helpers/staff_nav.php';
require_once '../../../config/database.php';
require_once '../../models/Payment.php';

$db = (new Database())->getConnection();
$paymentModel = new Payment($db);
$summary = $paymentModel->getSummary();
$records = $paymentModel->getAllBillingRecords();
$methods = $paymentModel->getPaymentMethods();
$canManage = hasPermission('accounting_manage');

function h($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function money($n) { return '₱' . number_format((float)$n, 2); }

function statusBadge($status) {
    $status = strtoupper((string)$status);
    if ($status === 'PAID') return 'badge-paid';
    if ($status === 'OVERDUE') return 'badge-overdue';
    if ($status === 'NOT SET') return 'badge-not-set';
    return 'badge-pending';
}

function paymentStatusLabel($record) {
    if (empty($record['payment_ID'])) return 'NOT SET';
    return strtoupper((string)($record['payment_status'] ?? ((int)($record['is_paid'] ?? 0) === 1 ? 'PAID' : 'PENDING')));
}

function normalizedMethod($name) {
    $name = strtoupper(trim((string)$name));
    return strpos($name, 'ONLINE') !== false ? 'ONLINE' : $name;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Billing / Accounting</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../../../public/css/all.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<div class="dashboard-layout">
    <?php showStaffSidebar('finances.php'); ?>

    <main class="main-content">
        <div class="billing-header">
            <div>
                <h1>Billing / Accounting</h1>
                <p>Set the client billing breakdown here. Clients only see total amount, method, and payment status.</p>
            </div>
            <div class="billing-badge"><i class="fa-solid fa-calculator"></i> Auto-calculated Billing</div>
        </div>

        <?php if (isset($_GET['updated'])): ?>
            <div class="alert alert-success">Billing information updated successfully.</div>
        <?php elseif (isset($_GET['air_online'])): ?>
            <div class="alert alert-error">Air travel requires Online Payment only.</div>
        <?php elseif (isset($_GET['error'])): ?>
            <div class="alert alert-error">Unable to save billing information. Please check the values and try again.</div>
        <?php endif; ?>

        <div class="finance-grid">
            <div class="finance-card">
                <div class="finance-icon"><i class="fa-solid fa-circle-check"></i></div>
                <div><strong><?= h(money($summary['paid'] ?? 0)) ?> / <?= h(money($summary['total'] ?? 0)) ?></strong><span>Paid / Total Gross</span></div>
            </div>
            <div class="finance-card">
                <div class="finance-icon"><i class="fa-solid fa-clock"></i></div>
                <div><strong><?= h(money($summary['unpaid'] ?? 0)) ?></strong><span>Pending Amount</span></div>
            </div>
            <div class="finance-card">
                <div class="finance-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
                <div><strong><?= h(money($summary['overdue'] ?? 0)) ?></strong><span>Overdue Amount</span></div>
            </div>
        </div>

        <section class="panel">
            <div class="panel-title">
                <div>
                    <h2>Booking Billing Records</h2>
                    <p>Use the action button to set or edit billing for each booking.</p>
                </div>
                <?php if (!$canManage): ?>
                    <span class="billing-badge"><i class="fa-solid fa-eye"></i> View Only</span>
                <?php endif; ?>
            </div>

            <div class="table-wrap">
                <table class="billing-table">
                    <thead>
                    <tr>
                        <th>Booking</th>
                        <th>Client / Drop-off</th>
                        <th>Shipment</th>
                        <th>Total Amount</th>
                        <th>Method</th>
                        <th>Status</th>
                        <?php if ($canManage): ?><th>Action</th><?php endif; ?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($records)): ?>
                        <tr><td colspan="<?= $canManage ? 7 : 6 ?>" class="empty-state">No booking records found yet.</td></tr>
                    <?php endif; ?>

                    <?php foreach ($records as $record): ?>
                        <?php
                            $shipmentType = strtoupper((string)($record['shipment_type'] ?? 'NOT SET'));
                            if ($shipmentType === '') $shipmentType = 'NOT SET';
                            $heads = (int)($record['number_of_heads'] ?? 0);
                            if ($heads <= 0) $heads = (int)($record['total_animals'] ?? 0);
                            $status = paymentStatusLabel($record);
                            $totalAmount = (float)($record['total_amount'] ?? $record['pay_amount'] ?? 0);
                            $clientName = trim(($record['client_firstname'] ?? '') . ' ' . ($record['client_lastname'] ?? ''));
                            $dropOff = trim(($record['receiver_municipality'] ?? '') . ', ' . ($record['receiver_province'] ?? ''), ', ');
                            $payMethod = $record['pay_method'] ?: 'Not set';
                            $modalData = [
                                'bookingId' => (int)$record['booking_ID'],
                                'clientName' => $clientName,
                                'shipmentType' => $shipmentType,
                                'boxFee' => (float)($record['box_fee'] ?? 0),
                                'pickupFee' => (float)($record['pickup_fee'] ?? 0),
                                'shippingFee' => (float)($record['shipping_fee'] ?? 0),
                                'headPrice' => (float)($record['head_price'] ?? 0),
                                'numberOfHeads' => $heads,
                                'paymethodId' => (int)($record['paymethod_ID'] ?? 0),
                                'paymentStatus' => $status === 'NOT SET' ? 'PENDING' : $status,
                                'paymentReference' => (string)($record['payment_reference'] ?? ''),
                                'totalAmount' => $totalAmount,
                            ];
                        ?>
                        <tr>
                            <td>
                                <div class="booking-no">#<?= h($record['booking_ID']) ?></div>
                                <div class="mini"><?= h($record['booking_status'] ?? '') ?></div>
                            </td>
                            <td>
                                <strong><?= h($clientName ?: 'Client') ?></strong>
                                <div class="mini"><?= h($dropOff ?: 'No drop-off set') ?></div>
                            </td>
                            <td>
                                <span class="ship-pill"><i class="fa-solid <?= $shipmentType === 'AIR' ? 'fa-plane' : ($shipmentType === 'LAND' ? 'fa-truck' : 'fa-circle-question') ?>"></i> <?= h($shipmentType === 'NOT SET' ? 'NOT SET' : $shipmentType) ?></span>
                                <div class="mini"><?= h($heads) ?> head(s)</div>
                            </td>
                            <td><span class="amount-text"><?= h(money($totalAmount)) ?></span></td>
                            <td><?= h($payMethod) ?></td>
                            <td><span class="status-badge <?= h(statusBadge($status)) ?>"><?= h($status) ?></span></td>
                            <?php if ($canManage): ?>
                                <td>
                                    <button
                                        type="button"
                                        class="btn-edit"
                                        onclick='openBillingModal(<?= json_encode($modalData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)'>
                                        <i class="fa-solid fa-pen-to-square"></i>
                                        <?= empty($record['payment_ID']) ? 'Set Billing' : 'Edit Billing' ?>
                                    </button>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</div>

<?php if ($canManage): ?>
<div id="billingModal" class="modal-backdrop" onclick="closeBillingModal(event)">
    <div class="billing-modal" onclick="event.stopPropagation()">
        <div class="modal-head">
            <div>
                <h2 id="modalTitle">Edit Billing</h2>
                <p id="modalSubtitle">Set the breakdown. The client only sees the total.</p>
            </div>
            <button type="button" class="close-btn" onclick="hideBillingModal()">&times;</button>
        </div>

        <form class="billing-form" method="POST" action="process-payment.php" oninput="calcBillingTotal(this)">
            <input type="hidden" name="booking_id" id="booking_id">

            <label class="form-full">Transportation Mode
                <select name="shipment_type" id="shipment_type" required onchange="setMethodRules(this.value, document.getElementById('paymethod_id').value)">
                    <option value="">Select transportation mode</option>
                    <option value="LAND">Land Transport</option>
                    <option value="AIR">Air Transport</option>
                </select>
            </label>

            <label>Box Fee
                <input type="number" name="box_fee" id="box_fee" min="0" step="0.01" required>
            </label>

            <label>Pickup Fee
                <input type="number" name="pickup_fee" id="pickup_fee" min="0" step="0.01" required>
            </label>

            <label>Shipping Fee
                <input type="number" name="shipping_fee" id="shipping_fee" min="0" step="0.01" required>
            </label>

            <label>Head Price
                <input type="number" name="head_price" id="head_price" min="0" step="0.01" required>
            </label>

            <label>No. of Heads
                <input type="number" name="number_of_heads" id="number_of_heads" min="0" step="1" required>
            </label>

            <label>Payment Method
                <select name="paymethod_id" id="paymethod_id" required>
                    <option value="">Select method</option>
                    <?php foreach ($methods as $method): ?>
                        <?php $normalized = normalizedMethod($method['pay_method']); ?>
                        <option value="<?= h($method['paymethod_ID']) ?>" data-method="<?= h($normalized) ?>">
                            <?= h($method['pay_method']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>

            <label>Payment Status
                <select name="payment_status" id="payment_status" required>
                    <option value="PENDING">Pending</option>
                    <option value="PAID">Paid</option>
                    <option value="OVERDUE">Overdue</option>
                </select>
            </label>

            <label class="form-full">Reference / Note
                <input type="text" name="payment_reference" id="payment_reference" placeholder="Online payment reference or Cash on Delivery note">
            </label>

            <div id="airNote" class="air-note" style="display:none;">
                <i class="fa-solid fa-plane"></i> Air travel requires Online Payment only.
            </div>

            <div class="total-box">
                <span>Calculated Total</span>
                <strong id="totalPreview">₱0.00</strong>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-secondary" onclick="hideBillingModal()">Cancel</button>
                <button type="submit" class="btn-save">Save Billing</button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../../public/js/all.js"></script>
</body>
</html>
