<?php
/**
 * Reusable DMAC page header.
 * Optional use in future pages:
 *   require_once __DIR__ . '/../templates/header.php';
 *   dmac_header('Page Title');
 */
function dmac_header($title = 'DMAC Shipping', $extraCss = []) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../../../public/css/all.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <?php foreach ((array)$extraCss as $css): ?>
        <link rel="stylesheet" href="<?= htmlspecialchars($css, ENT_QUOTES, 'UTF-8') ?>">
    <?php endforeach; ?>
</head>
<body class="dashboard-body">
<?php
}
?>
