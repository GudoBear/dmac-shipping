<?php
/**
 * Reusable DMAC UI helpers.
 * These keep repeated frontend pieces in one place while existing pages can still work normally.
 */
function dmac_h($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function dmac_status_badge($status) {
    $statusText = strtoupper(trim((string)$status));
    $class = 'badge-processing';
    if ($statusText === 'PENDING REVIEW') $class = 'badge-pending-review';
    elseif ($statusText === 'FOR PICK-UP') $class = 'badge-for-pick-up';
    elseif ($statusText === 'PREPARING FOR TRANSIT') $class = 'badge-preparing-for-transit';
    elseif ($statusText === 'IN TRANSIT') $class = 'badge-in-transit';
    elseif (in_array($statusText, ['DELIVERED / SHIPPED','DELIVERED/SHIPPED','DELIVERED','SHIPPED','COMPLETED'], true)) $class = 'badge-delivered-shipped';
    elseif ($statusText === 'CANCELLED') $class = 'badge-cancelled';
    return '<span class="status-badge ' . dmac_h($class) . '">' . dmac_h($status) . '</span>';
}

function dmac_money($amount) {
    return '₱' . number_format((float)$amount, 2);
}

function dmac_date($date) {
    if (empty($date)) return 'Not set';
    $ts = strtotime((string)$date);
    return $ts ? date('M d, Y', $ts) : 'Not set';
}

function dmac_empty_state($icon, $message) {
    return '<div class="empty-state"><i class="' . dmac_h($icon) . '"></i><p>' . dmac_h($message) . '</p></div>';
}
?>
