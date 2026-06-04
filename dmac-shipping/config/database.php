<?php
class Database {
    private $host = "localhost";
    private $db_name = "dmac_shipping_optimized";
    private $username = "root";
    private $password = "";
    private $charset = "utf8mb4";
    public $conn = null;
    private $columnCache = [];

    public function __construct(array $config = []) {
        if (!empty($config)) {
            $this->host = $config['host'] ?? $this->host;
            $this->db_name = $config['dbname'] ?? $this->db_name;
            $this->username = $config['username'] ?? $this->username;
            $this->password = $config['password'] ?? $this->password;
            $this->charset = $config['charset'] ?? $this->charset;
        }
    }

    public function getConnection() {
        if ($this->conn === null) {
            $this->connect();
        }
        return $this->conn;
    }

    private function connect() {
        try {
            $dsn = $this->buildDsn();
            $this->conn = new PDO($dsn, $this->username, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $exception) {
            die("Database connection failed. Please import dmac_shipping_optimized.sql and check config/database.php.");
        }
    }

    public function isConnected() {
        return $this->conn !== null;
    }

    public function disconnect() {
        $this->conn = null;
    }

    public function prepare($sql) {
        return $this->getConnection()->prepare($sql);
    }

    public function query($sql) {
        return $this->getConnection()->query($sql);
    }

    public function execute($sql, array $params = []) {
        $stmt = $this->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function fetchAll($sql, array $params = [], $fetchMode = PDO::FETCH_ASSOC) {
        return $this->execute($sql, $params)->fetchAll($fetchMode);
    }

    public function fetchOne($sql, array $params = [], $fetchMode = PDO::FETCH_ASSOC) {
        return $this->execute($sql, $params)->fetch($fetchMode);
    }

    public function beginTransaction() {
        return $this->getConnection()->beginTransaction();
    }

    public function commit() {
        return $this->getConnection()->commit();
    }

    public function rollBack() {
        return $this->getConnection()->rollBack();
    }

    public function inTransaction() {
        return $this->getConnection()->inTransaction();
    }

    public function lastInsertId($name = null) {
        return $this->getConnection()->lastInsertId($name);
    }

    private function buildDsn() {
        return "mysql:host={$this->host};dbname={$this->db_name};charset={$this->charset}";
    }

    private function tableHasColumn($table, $column) {
        $key = $table . '.' . $column;
        if (array_key_exists($key, $this->columnCache)) {
            return $this->columnCache[$key];
        }

        $stmt = $this->prepare("
            SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = :table_name
                AND COLUMN_NAME = :column_name
            LIMIT 1
        ");
        $stmt->execute(['table_name' => $table, 'column_name' => $column]);
        $this->columnCache[$key] = (bool)$stmt->fetch(PDO::FETCH_ASSOC);
        return $this->columnCache[$key];
    }

    // =====================================================================
    // CLIENT FUNCTIONS
    // =====================================================================

    public function findClientByEmail($email) {
        $stmt = $this->prepare("SELECT * FROM client WHERE client_email = :email AND deleted_at IS NULL LIMIT 1");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findClientById($clientId) {
        $stmt = $this->prepare("SELECT * FROM client WHERE client_ID = :id AND deleted_at IS NULL LIMIT 1");
        $stmt->execute(['id' => $clientId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function registerClient($data) {
        $sql = "INSERT INTO client (client_firstname, client_lastname, client_contact, client_email, client_password)
                VALUES (:firstname, :lastname, :contact, :email, :password)";
        $stmt = $this->prepare($sql);
        return $stmt->execute([
            'firstname' => $data['firstname'],
            'lastname'  => $data['lastname'],
            'contact'   => $data['contact'],
            'email'     => $data['email'],
            'password'  => $data['password']
        ]);
    }

    public function updateClientProfile($clientId, $data) {
        $sql = "UPDATE client SET client_firstname=:firstname, client_lastname=:lastname, client_contact=:contact, client_email=:email WHERE client_ID=:id";
        $stmt = $this->prepare($sql);
        return $stmt->execute([
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'contact' => $data['contact'],
            'email' => $data['email'],
            'id' => $clientId
        ]);
    }

    public function updateClientPassword($clientId, $hashedPassword) {
        $stmt = $this->prepare("UPDATE client SET client_password=:password WHERE client_ID=:id");
        return $stmt->execute(['password' => $hashedPassword, 'id' => $clientId]);
    }

    // =====================================================================
    // BOOKING FUNCTIONS
    // =====================================================================

    public function createBooking($clientId, $data) {
        try {
            $this->beginTransaction();

            $pickupSql = "INSERT INTO pickup (
                            contact_firstname,
                            contact_lastname,
                            contact_number,
                            pickup_street,
                            pickup_municipality,
                            pickup_province
                          ) VALUES (
                            :fn,
                            :ln,
                            :num,
                            :street,
                            :mun,
                            :prov
                          )";
            $this->prepare($pickupSql)->execute([
                'fn'     => $data['pickup_firstname'],
                'ln'     => $data['pickup_lastname'],
                'num'    => $data['pickup_number'],
                'street' => $data['pickup_street'],
                'mun'    => $data['pickup_municipality'],
                'prov'   => $data['pickup_province']
            ]);
            $pickupId = $this->lastInsertId();

            $receiverSql = "INSERT INTO receiver (
                                receiver_firstname,
                                receiver_lastname,
                                receiver_contact,
                                receiver_street,
                                receiver_municipality,
                                receiver_province
                            ) VALUES (
                                :fn,
                                :ln,
                                :num,
                                :street,
                                :mun,
                                :prov
                            )";
            $this->prepare($receiverSql)->execute([
                'fn'     => $data['receiver_firstname'],
                'ln'     => $data['receiver_lastname'],
                'num'    => $data['receiver_contact'],
                'street' => $data['receiver_street'],
                'mun'    => $data['receiver_municipality'],
                'prov'   => $data['receiver_province']
            ]);
            $receiverId = $this->lastInsertId();

            $bookingColumns = ['client_ID', 'pickup_ID', 'receiver_ID', 'booking_requestdate', 'booking_status'];
            $bookingValues = [':client_id', ':pickup_id', ':receiver_id', ':req_date', ':status'];
            $bookingParams = [
                'client_id'   => $clientId,
                'pickup_id'   => $pickupId,
                'receiver_id' => $receiverId,
                'req_date'    => $data['booking_requestdate'],
                'status'      => 'PENDING REVIEW'
            ];

            if ($this->tableHasColumn('booking', 'shipment_type')) {
                $bookingColumns[] = 'shipment_type';
                $bookingValues[] = 'NULL';
            }

            if ($this->tableHasColumn('booking', 'terms_accepted')) {
                $bookingColumns[] = 'terms_accepted';
                $bookingValues[] = ':terms_accepted';
                $bookingParams['terms_accepted'] = (int)($data['terms_accepted'] ?? 0);
            }

            if ($this->tableHasColumn('booking', 'insurance_accepted')) {
                $bookingColumns[] = 'insurance_accepted';
                $bookingValues[] = ':insurance_accepted';
                $bookingParams['insurance_accepted'] = (int)($data['insurance_accepted'] ?? 0);
            }

            if ($this->tableHasColumn('booking', 'agreement_accepted_at')) {
                $bookingColumns[] = 'agreement_accepted_at';
                $bookingValues[] = 'CURRENT_TIMESTAMP';
            }

            $bookingSql = "INSERT INTO booking (" . implode(', ', $bookingColumns) . ") VALUES (" . implode(', ', $bookingValues) . ")";
            $this->prepare($bookingSql)->execute($bookingParams);
            $bookingId = $this->lastInsertId();

            $batchStmt = $this->prepare("INSERT INTO animalbatch (booking_ID, animal_ID, animalbatch_quantity) VALUES (:booking_id, :animal_id, :qty)");
            $types = $data['animal_types'] ?? [];
            $qtys  = $data['animal_quantities'] ?? [];

            for ($i = 0; $i < count($types); $i++) {
                $animalId = (int)$types[$i];
                $qty = max(1, (int)($qtys[$i] ?? 1));
                if ($animalId <= 0) {
                    throw new Exception('Invalid animal type.');
                }
                $batchStmt->execute([
                    'booking_id' => $bookingId,
                    'animal_id'  => $animalId,
                    'qty'        => $qty
                ]);
            }

            $this->commit();
            return $bookingId;
        } catch (Exception $e) {
            if ($this->inTransaction()) {
                $this->rollBack();
            }
            error_log("Booking Error: " . $e->getMessage());
            return false;
        }
    }

    public function getBookingStatusCounts($clientId) {
        $stmt = $this->prepare("SELECT booking_status, COUNT(*) total FROM booking WHERE client_ID = :client_id GROUP BY booking_status");
        $stmt->execute(['client_id' => $clientId]);

        $counts = [
            'PENDING REVIEW' => 0,
            'PROCESSING' => 0,
            'FOR PICK-UP' => 0,
            'IN TRANSIT' => 0,
            'PREPARING FOR TRANSIT' => 0,
            'DELIVERED/SHIPPED' => 0,
            'CANCELLED' => 0
        ];

        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            if (isset($counts[$row['booking_status']])) {
                $counts[$row['booking_status']] = (int)$row['total'];
            }
        }
        return $counts;
    }

    public function getRecentClientShipments($clientId, $limit = 5) {
        return $this->getClientShipments($clientId, $limit);
    }

    public function getClientShipments($clientId, $limit = 100) {
        $agreementSelect = '';
        if ($this->tableHasColumn('booking', 'terms_accepted')) {
            $agreementSelect .= ", b.terms_accepted";
        } else {
            $agreementSelect .= ", 0 AS terms_accepted";
        }
        if ($this->tableHasColumn('booking', 'insurance_accepted')) {
            $agreementSelect .= ", b.insurance_accepted";
        } else {
            $agreementSelect .= ", 0 AS insurance_accepted";
        }
        if ($this->tableHasColumn('booking', 'agreement_accepted_at')) {
            $agreementSelect .= ", b.agreement_accepted_at";
        } else {
            $agreementSelect .= ", NULL AS agreement_accepted_at";
        }
        if ($this->tableHasColumn('booking', 'shipment_type')) {
            $agreementSelect .= ", COALESCE(b.shipment_type, 'NOT SET') AS shipment_type";
        } else {
            $agreementSelect .= ", 'NOT SET' AS shipment_type";
        }

        $sql = "SELECT b.booking_ID,
                       b.booking_status,
                       b.booking_requestdate,
                       b.booking_startdate,
                       b.booking_enddate,
                       p.contact_firstname,
                       p.contact_lastname,
                       p.contact_number,
                       p.pickup_street,
                       p.pickup_municipality,
                       p.pickup_province,
                       r.receiver_firstname,
                       r.receiver_lastname,
                       r.receiver_contact,
                       r.receiver_street,
                       r.receiver_municipality,
                       r.receiver_province,
                       COALESCE(animals.total_animals, 0) AS total_animals,
                       COALESCE(animals.animal_summary, 'No animals listed') AS animal_summary,
                       f.feedback_ID,
                       f.feed_rate,
                       f.feed_comment
                       $agreementSelect
                FROM booking b
                JOIN pickup p ON b.pickup_ID = p.pickup_ID
                JOIN receiver r ON b.receiver_ID = r.receiver_ID
                LEFT JOIN (
                    SELECT ab.booking_ID,
                           SUM(ab.animalbatch_quantity) AS total_animals,
                           GROUP_CONCAT(CONCAT(a.animal_type, ' - ', ab.animalbatch_quantity, ' head(s)') ORDER BY a.animal_type SEPARATOR ', ') AS animal_summary
                    FROM animalbatch ab
                    JOIN animal a ON ab.animal_ID = a.animal_ID
                    GROUP BY ab.booking_ID
                ) animals ON b.booking_ID = animals.booking_ID
                LEFT JOIN feedback f ON b.booking_ID = f.booking_ID
                WHERE b.client_ID = :client_id
                ORDER BY b.booking_startdate DESC
                LIMIT :limit";
        $stmt = $this->prepare($sql);
        $stmt->bindValue(':client_id', $clientId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function submitBookingFeedback($clientId, $bookingId, $rating, $comment) {
        $clientId = (int)$clientId;
        $bookingId = (int)$bookingId;
        $rating = max(1, min(5, (int)$rating));
        $comment = trim((string)$comment);

        if ($clientId <= 0 || $bookingId <= 0 || $comment === '') {
            return false;
        }

        $check = $this->prepare("
            SELECT booking_ID
            FROM booking
            WHERE booking_ID = :booking_id
              AND client_ID = :client_id
              AND booking_status IN (
                    'DELIVERED/SHIPPED',
                    'DELIVERED / SHIPPED',
                    'COMPLETED',
                    'DELIVERED',
                    'SHIPPED'
              )
            LIMIT 1
        ");

        $check->execute([
            'booking_id' => $bookingId,
            'client_id' => $clientId
        ]);

        if (!$check->fetch(PDO::FETCH_ASSOC)) {
            return false;
        }

        $existing = $this->prepare("
            SELECT feedback_ID
            FROM feedback
            WHERE booking_ID = :booking_id
            LIMIT 1
        ");
        $existing->execute(['booking_id' => $bookingId]);
        $feedbackId = $existing->fetchColumn();

        if ($feedbackId) {
            $stmt = $this->prepare("
                UPDATE feedback
                SET feed_rate = :rate,
                    feed_comment = :comment,
                    feed_submitted = CURRENT_TIMESTAMP
                WHERE feedback_ID = :feedback_id
            ");

            return $stmt->execute([
                'rate' => $rating,
                'comment' => $comment,
                'feedback_id' => (int)$feedbackId
            ]);
        }

        $stmt = $this->prepare("
            INSERT INTO feedback (booking_ID, feed_rate, feed_comment, feed_submitted)
            VALUES (:booking_id, :rate, :comment, CURRENT_TIMESTAMP)
        ");

        return $stmt->execute([
            'booking_id' => $bookingId,
            'rate' => $rating,
            'comment' => $comment
        ]);
    }

    public function getClientFeedbackHistory($clientId) {
        $stmt = $this->prepare("
            SELECT
                f.feedback_ID,
                f.feed_rate,
                f.feed_comment,
                f.feed_submitted,
                b.booking_ID,
                b.booking_status,
                b.booking_requestdate,
                p.pickup_municipality,
                r.receiver_municipality,
                r.receiver_province
            FROM feedback f
            JOIN booking b ON f.booking_ID = b.booking_ID
            LEFT JOIN pickup p ON b.pickup_ID = p.pickup_ID
            LEFT JOIN receiver r ON b.receiver_ID = r.receiver_ID
            WHERE b.client_ID = :client_id
            ORDER BY f.feed_submitted DESC
        ");

        $stmt->execute([
            'client_id' => (int)$clientId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // =====================================================================
    // PAYMENT FUNCTIONS
    // =====================================================================

    public function getPaymentMethods() {
        $this->ensurePaymentMethods();
        $stmt = $this->query("
            SELECT paymethod_ID, pay_method
            FROM paymethod
            WHERE pay_method IN ('Cash on Delivery', 'Online Payment')
            ORDER BY FIELD(pay_method, 'Cash on Delivery', 'Online Payment')
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function ensurePaymentMethods() {
        $this->normalizePayMethod('Cash on Delivery', "UPPER(TRIM(pay_method)) IN ('COD', 'CASH', 'CASH ON DELIVERY')");
        $this->normalizePayMethod('Online Payment', "UPPER(TRIM(pay_method)) IN ('ONLINE', 'ONLINE PAYMENT', 'ONLINE BANKING', 'GCASH', 'BANK TRANSFER') OR UPPER(pay_method) LIKE '%ONLINE%'");

        $required = ['Cash on Delivery', 'Online Payment'];
        $check = $this->prepare("SELECT paymethod_ID FROM paymethod WHERE pay_method = ? LIMIT 1");
        $insert = $this->prepare("INSERT INTO paymethod (pay_method) VALUES (?)");

        foreach ($required as $method) {
            $check->execute([$method]);
            if (!$check->fetchColumn()) {
                $insert->execute([$method]);
            }
        }
    }

    private function normalizePayMethod($canonical, $condition) {
        $check = $this->prepare("SELECT paymethod_ID FROM paymethod WHERE pay_method = ? LIMIT 1");
        $check->execute([$canonical]);
        if ($check->fetchColumn()) {
            return;
        }

        $query = "SELECT paymethod_ID FROM paymethod WHERE {$condition} ORDER BY paymethod_ID LIMIT 1";
        $stmt = $this->query($query);
        $paymethodId = $stmt->fetchColumn();

        if ($paymethodId) {
            $update = $this->prepare("UPDATE paymethod SET pay_method = ? WHERE paymethod_ID = ?");
            $update->execute([$canonical, $paymethodId]);
        }
    }

    public function getClientBilling($clientId) {
        $shipmentSelect = $this->tableHasColumn('booking', 'shipment_type') ? "COALESCE(b.shipment_type, 'NOT SET') AS shipment_type" : "'NOT SET' AS shipment_type";
        $extraSelect = $this->paymentExtraSelect();

        $sql = "SELECT b.booking_ID,
                       b.booking_status,
                       b.booking_requestdate,
                       b.booking_startdate,
                       b.booking_enddate,
                       $shipmentSelect,
                       r.receiver_street,
                       r.receiver_municipality,
                       r.receiver_province,
                       COALESCE(animals.total_animals, 0) AS total_animals,
                       p.payment_ID,
                       p.pay_amount,
                       p.pay_date,
                       CASE WHEN UPPER(COALESCE(p.payment_status, 'PENDING')) = 'PAID' THEN 1 ELSE 0 END AS is_paid,
                       pm.pay_method
                       $extraSelect
                FROM booking b
                JOIN receiver r ON b.receiver_ID = r.receiver_ID
                LEFT JOIN payment p ON b.booking_ID = p.booking_ID
                LEFT JOIN paymethod pm ON p.paymethod_ID = pm.paymethod_ID
                LEFT JOIN (
                    SELECT booking_ID, SUM(animalbatch_quantity) AS total_animals
                    FROM animalbatch
                    GROUP BY booking_ID
                ) animals ON b.booking_ID = animals.booking_ID
                WHERE b.client_ID = :client_id
                ORDER BY b.booking_startdate DESC";
        $stmt = $this->prepare($sql);
        $stmt->execute(['client_id' => (int)$clientId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllBillingRecords() {
        $shipmentSelect = $this->tableHasColumn('booking', 'shipment_type') ? "COALESCE(b.shipment_type, 'NOT SET') AS shipment_type" : "'NOT SET' AS shipment_type";
        $extraSelect = $this->paymentExtraSelect();

        $sql = "SELECT b.booking_ID,
                       b.booking_status,
                       b.booking_requestdate,
                       b.booking_startdate,
                       b.booking_enddate,
                       $shipmentSelect,
                       c.client_firstname,
                       c.client_lastname,
                       r.receiver_municipality,
                       r.receiver_province,
                       COALESCE(animals.total_animals, 0) AS total_animals,
                       p.payment_ID,
                       p.paymethod_ID,
                       p.pay_amount,
                       p.pay_date,
                       CASE WHEN UPPER(COALESCE(p.payment_status, 'PENDING')) = 'PAID' THEN 1 ELSE 0 END AS is_paid,
                       pm.pay_method
                       $extraSelect
                FROM booking b
                JOIN client c ON b.client_ID = c.client_ID
                JOIN receiver r ON b.receiver_ID = r.receiver_ID
                LEFT JOIN payment p ON b.booking_ID = p.booking_ID
                LEFT JOIN paymethod pm ON p.paymethod_ID = pm.paymethod_ID
                LEFT JOIN (
                    SELECT booking_ID, SUM(animalbatch_quantity) AS total_animals
                    FROM animalbatch
                    GROUP BY booking_ID
                ) animals ON b.booking_ID = animals.booking_ID
                ORDER BY b.booking_startdate DESC";
        return $this->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPaymentSummary() {
        $summary = [
            'total' => 0,
            'paid' => 0,
            'unpaid' => 0,
            'overdue' => 0,
            'paid_count' => 0,
            'pending_count' => 0,
            'overdue_count' => 0,
            'not_set_count' => 0
        ];

        $stmt = $this->query("SELECT COUNT(*) FROM booking b LEFT JOIN payment p ON b.booking_ID = p.booking_ID WHERE p.payment_ID IS NULL");
        $summary['not_set_count'] = (int)$stmt->fetchColumn();

        if ($this->tableHasColumn('payment', 'payment_status')) {
            $amountExpr = $this->tableHasColumn('payment', 'total_amount') ? 'total_amount' : 'pay_amount';
            $rows = $this->query("SELECT payment_status, COUNT(*) total_count, COALESCE(SUM($amountExpr),0) total_amount FROM payment GROUP BY payment_status")->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                $status = strtoupper((string)$row['payment_status']);
                $amount = (float)$row['total_amount'];
                if ($status === 'PAID') {
                    $summary['paid'] += $amount;
                    $summary['total'] += $amount;
                    $summary['paid_count'] += (int)$row['total_count'];
                } elseif ($status === 'OVERDUE') {
                    $summary['overdue'] += $amount;
                    $summary['overdue_count'] += (int)$row['total_count'];
                } else {
                    $summary['unpaid'] += $amount;
                    $summary['pending_count'] += (int)$row['total_count'];
                }
            }
        } else {
            $rows = $this->query("SELECT is_paid, COUNT(*) total_count, COALESCE(SUM(pay_amount),0) total_amount FROM payment GROUP BY is_paid")->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                $amount = (float)$row['total_amount'];
                if ((int)$row['is_paid'] === 1) {
                    $summary['paid'] += $amount;
                    $summary['total'] += $amount;
                    $summary['paid_count'] += (int)$row['total_count'];
                } else {
                    $summary['unpaid'] += $amount;
                    $summary['pending_count'] += (int)$row['total_count'];
                }
            }
        }

        return $summary;
    }

    private function paymentExtraSelect() {
        $selects = [];
        foreach (['box_fee','pickup_fee','shipping_fee','head_price','number_of_heads','total_amount','payment_status','payment_reference','updated_at'] as $column) {
            if ($this->tableHasColumn('payment', $column)) {
                $selects[] = "p.$column";
            } else {
                if (in_array($column, ['box_fee','pickup_fee','shipping_fee','head_price','number_of_heads'], true)) {
                    $selects[] = "0 AS $column";
                } elseif ($column === 'total_amount') {
                    $selects[] = "p.pay_amount AS total_amount";
                } elseif ($column === 'payment_status') {
                    if ($this->tableHasColumn('payment', 'payment_status')) {
                        $selects[] = "p.payment_status";
                    } elseif ($this->tableHasColumn('payment', 'is_paid')) {
                        $selects[] = "CASE WHEN p.payment_ID IS NULL THEN 'NOT SET' WHEN p.is_paid = 1 THEN 'PAID' ELSE 'PENDING' END AS payment_status";
                    } else {
                        $selects[] = "CASE WHEN p.payment_ID IS NULL THEN 'NOT SET' ELSE 'PENDING' END AS payment_status";
                    }
                } else {
                    $selects[] = "NULL AS $column";
                }
            }
        }
        return ', ' . implode(', ', $selects);
    }

    public function savePaymentBreakdown($bookingId, $boxFee, $pickupFee, $shippingFee, $headPrice, $numberOfHeads, $paymethodId, $paymentStatus, $paymentReference = '', $shipmentType = null) {
        $bookingId = (int)$bookingId;
        $paymethodId = (int)$paymethodId;
        $boxFee = max(0, (float)$boxFee);
        $pickupFee = max(0, (float)$pickupFee);
        $shippingFee = max(0, (float)$shippingFee);
        $headPrice = max(0, (float)$headPrice);
        $numberOfHeads = max(0, (int)$numberOfHeads);
        $paymentStatus = strtoupper(trim((string)$paymentStatus));
        $paymentReference = trim((string)$paymentReference);
        $shipmentType = strtoupper(trim((string)$shipmentType));

        if (!in_array($shipmentType, ['LAND', 'AIR'], true)) {
            return false;
        }

        if ($bookingId <= 0 || $paymethodId <= 0 || !in_array($paymentStatus, ['PENDING','PAID','OVERDUE'], true)) {
            return false;
        }

        $booking = $this->getBookingInfo($bookingId);
        if (!$booking) {
            return false;
        }

        $methodName = $this->getMethodName($paymethodId);
        if ($shipmentType === 'AIR' && strpos($methodName, 'ONLINE') === false) {
            return false;
        }

        $totalAmount = $boxFee + $pickupFee + $shippingFee + ($numberOfHeads * $headPrice);
        $isPaid = $paymentStatus === 'PAID' ? 1 : 0;

        try {
            $this->beginTransaction();

            if ($this->tableHasColumn('booking', 'shipment_type')) {
                $updateBooking = $this->prepare("UPDATE booking SET shipment_type = :shipment_type WHERE booking_ID = :booking_id");
                $updateBooking->execute([
                    'shipment_type' => $shipmentType,
                    'booking_id' => $bookingId
                ]);
            }

            $existing = $this->prepare("SELECT payment_ID FROM payment WHERE booking_ID = :booking_id LIMIT 1");
            $existing->execute(['booking_id' => $bookingId]);
            $paymentId = $existing->fetchColumn();

            $fields = [
                'paymethod_ID' => $paymethodId,
                'pay_amount' => $totalAmount,
                'is_paid' => $isPaid,
            ];

            if ($this->tableHasColumn('payment', 'box_fee')) $fields['box_fee'] = $boxFee;
            if ($this->tableHasColumn('payment', 'pickup_fee')) $fields['pickup_fee'] = $pickupFee;
            if ($this->tableHasColumn('payment', 'shipping_fee')) $fields['shipping_fee'] = $shippingFee;
            if ($this->tableHasColumn('payment', 'head_price')) $fields['head_price'] = $headPrice;
            if ($this->tableHasColumn('payment', 'number_of_heads')) $fields['number_of_heads'] = $numberOfHeads;
            if ($this->tableHasColumn('payment', 'total_amount')) $fields['total_amount'] = $totalAmount;
            if ($this->tableHasColumn('payment', 'payment_status')) $fields['payment_status'] = $paymentStatus;
            if ($this->tableHasColumn('payment', 'payment_reference')) $fields['payment_reference'] = $paymentReference;
            if ($this->tableHasColumn('payment', 'updated_at')) $fields['updated_at'] = date('Y-m-d H:i:s');

            if ($paymentStatus === 'PAID') {
                $fields['pay_date'] = date('Y-m-d');
            } else {
                $fields['pay_date'] = null;
            }

            if ($paymentId) {
                $sets = [];
                foreach ($fields as $column => $value) {
                    $sets[] = "$column = :$column";
                }
                $fields['payment_ID'] = $paymentId;
                $sql = "UPDATE payment SET " . implode(', ', $sets) . " WHERE payment_ID = :payment_ID";
                $stmt = $this->prepare($sql);
                $stmt->execute($fields);
            } else {
                $fields['booking_ID'] = $bookingId;
                $columns = array_keys($fields);
                $placeholders = array_map(fn($c) => ':' . $c, $columns);
                $sql = "INSERT INTO payment (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $placeholders) . ")";
                $stmt = $this->prepare($sql);
                $stmt->execute($fields);
            }

            $this->commit();
            return true;
        } catch (Exception $e) {
            if ($this->inTransaction()) {
                $this->rollBack();
            }
            error_log('Payment save error: ' . $e->getMessage());
            return false;
        }
    }

    private function getBookingInfo($bookingId) {
        $shipmentSelect = $this->tableHasColumn('booking', 'shipment_type') ? "COALESCE(shipment_type, 'NOT SET') AS shipment_type" : "'NOT SET' AS shipment_type";
        $stmt = $this->prepare("SELECT booking_ID, $shipmentSelect FROM booking WHERE booking_ID = :booking_id LIMIT 1");
        $stmt->execute(['booking_id' => (int)$bookingId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function getMethodName($paymethodId) {
        $stmt = $this->prepare("SELECT pay_method FROM paymethod WHERE paymethod_ID = :id LIMIT 1");
        $stmt->execute(['id' => (int)$paymethodId]);
        return strtoupper(trim((string)$stmt->fetchColumn()));
    }

    public function ensureExpenseCategories() {
        $categories = [
            'Airline Expenses',
            'Consignee Per Destination',
            'Farm Expenses',
            'Land Trip Expenses',
            'Fuel Expenses',
            'Meal Expenses',
            'Cebu Pac Expenses',
            'Miscellaneous Fee',
            'Salary',
            'Employee Salary',
            'Insurance Fee'
        ];

        $check = $this->prepare("SELECT expensecategory_ID FROM expensecategory WHERE LOWER(categoryname) = LOWER(?) LIMIT 1");
        $insert = $this->prepare("INSERT INTO expensecategory (categoryname) VALUES (?)");

        foreach ($categories as $category) {
            $check->execute([$category]);
            if (!$check->fetchColumn()) {
                $insert->execute([$category]);
            }
        }
    }

    public function getExpenseCategories() {
        $this->ensureExpenseCategories();

        $stmt = $this->query("
            SELECT expensecategory_ID, categoryname
            FROM expensecategory
            ORDER BY categoryname ASC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function saveExpense($expenseCategoryId, $processedByEmpId, $amount, $expenseDate, $description = '') {
        $expenseCategoryId = (int)$expenseCategoryId;
        $processedByEmpId = (int)$processedByEmpId;
        $amount = (float)$amount;
        $expenseDate = trim((string)$expenseDate);
        $description = trim((string)$description);

        if ($expenseCategoryId <= 0 || $processedByEmpId <= 0 || $amount <= 0 || $expenseDate === '') {
            return false;
        }

        $dateObj = DateTime::createFromFormat('Y-m-d', $expenseDate);
        if (!$dateObj || $dateObj->format('Y-m-d') !== $expenseDate) {
            return false;
        }

        $checkCategory = $this->prepare("SELECT COUNT(*) FROM expensecategory WHERE expensecategory_ID = ?");
        $checkCategory->execute([$expenseCategoryId]);
        if ((int)$checkCategory->fetchColumn() === 0) {
            return false;
        }

        $stmt = $this->prepare("
            INSERT INTO expense (
                expensecategory_ID,
                processed_by_emp_ID,
                expense_amount,
                expense_description,
                expense_date
            ) VALUES (
                :expensecategory_ID,
                :processed_by_emp_ID,
                :expense_amount,
                :expense_description,
                :expense_date
            )
        ");

        return $stmt->execute([
            'expensecategory_ID' => $expenseCategoryId,
            'processed_by_emp_ID' => $processedByEmpId,
            'expense_amount' => $amount,
            'expense_description' => $description !== '' ? $description : null,
            'expense_date' => $expenseDate
        ]);
    }

    public function getIncomeExpenseSummary($year, $month = null) {
        $year = (int)$year;
        $month = $month !== null && $month !== '' ? (int)$month : null;

        if ($year <= 0) {
            $year = (int)date('Y');
        }

        $paymentDateExpr = $this->getPaymentDateExpression('p');
        $dateWherePayment = "YEAR($paymentDateExpr) = :payment_year";
        $dateWhereExpense = "YEAR(e.expense_date) = :expense_year";
        $paramsPayment = ['payment_year' => $year];
        $paramsExpense = ['expense_year' => $year];

        if ($month !== null && $month >= 1 && $month <= 12) {
            $dateWherePayment .= " AND MONTH($paymentDateExpr) = :payment_month";
            $dateWhereExpense .= " AND MONTH(e.expense_date) = :expense_month";
            $paramsPayment['payment_month'] = $month;
            $paramsExpense['expense_month'] = $month;
        }

        $amountExpr = $this->tableHasColumn('payment', 'total_amount') ? 'p.total_amount' : 'p.pay_amount';
        $paymentStatusExpr = $this->getPaymentStatusExpression('p');

        $grossStmt = $this->prepare("
            SELECT COALESCE(SUM($amountExpr), 0)
            FROM payment p
            WHERE UPPER(COALESCE($paymentStatusExpr, 'PENDING')) = 'PAID'
              AND $dateWherePayment
        ");
        $grossStmt->execute($paramsPayment);
        $grossIncome = (float)$grossStmt->fetchColumn();

        $expenseStmt = $this->prepare("
            SELECT COALESCE(SUM(e.expense_amount), 0)
            FROM expense e
            WHERE $dateWhereExpense
        ");
        $expenseStmt->execute($paramsExpense);
        $totalExpenses = (float)$expenseStmt->fetchColumn();

        return [
            'gross_income' => $grossIncome,
            'total_expenses' => $totalExpenses,
            'net_income' => $grossIncome - $totalExpenses
        ];
    }

    public function getMonthlyIncomeExpenseReport($year) {
        $year = (int)$year;
        if ($year <= 0) {
            $year = (int)date('Y');
        }

        $amountExpr = $this->tableHasColumn('payment', 'total_amount') ? 'p.total_amount' : 'p.pay_amount';
        $paymentStatusExpr = $this->getPaymentStatusExpression('p');

        $paymentDateExpr = $this->getPaymentDateExpression('p');
        $grossRows = $this->prepare("
            SELECT 
                MONTH($paymentDateExpr) AS report_month,
                COALESCE(SUM($amountExpr), 0) AS gross_income
            FROM payment p
            WHERE UPPER(COALESCE($paymentStatusExpr, 'PENDING')) = 'PAID'
              AND YEAR($paymentDateExpr) = :year
            GROUP BY MONTH($paymentDateExpr)
        ");
        $grossRows->execute(['year' => $year]);
        $grossByMonth = [];
        foreach ($grossRows->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $grossByMonth[(int)$row['report_month']] = (float)$row['gross_income'];
        }

        $expenseRows = $this->prepare("
            SELECT MONTH(expense_date) AS report_month,
                   COALESCE(SUM(expense_amount), 0) AS total_expenses
            FROM expense
            WHERE YEAR(expense_date) = :year
            GROUP BY MONTH(expense_date)
        ");
        $expenseRows->execute(['year' => $year]);
        $expensesByMonth = [];
        foreach ($expenseRows->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $expensesByMonth[(int)$row['report_month']] = (float)$row['total_expenses'];
        }

        $report = [];
        for ($month = 1; $month <= 12; $month++) {
            $gross = $grossByMonth[$month] ?? 0;
            $expenses = $expensesByMonth[$month] ?? 0;

            $report[] = [
                'month_number' => $month,
                'month_name' => date('F', mktime(0, 0, 0, $month, 1)),
                'gross_income' => $gross,
                'total_expenses' => $expenses,
                'net_income' => $gross - $expenses
            ];
        }

        return $report;
    }

    public function getExpenseDateCategoryRows($year, $month = null) {
        $year = (int)$year;
        $month = $month !== null && $month !== '' ? (int)$month : null;

        if ($year <= 0) {
            $year = (int)date('Y');
        }

        $where = "YEAR(e.expense_date) = :year";
        $params = ['year' => $year];

        if ($month !== null && $month >= 1 && $month <= 12) {
            $where .= " AND MONTH(e.expense_date) = :month";
            $params['month'] = $month;
        }

        $stmt = $this->prepare("
            SELECT 
                e.expense_date,
                ec.categoryname,
                COALESCE(SUM(e.expense_amount), 0) AS amount
            FROM expense e
            JOIN expensecategory ec ON e.expensecategory_ID = ec.expensecategory_ID
            WHERE $where
            GROUP BY e.expense_date, ec.categoryname
            ORDER BY e.expense_date DESC, ec.categoryname ASC
        ");
        $stmt->execute($params);
        $rawRows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $rows = [];
        foreach ($rawRows as $row) {
            $date = $row['expense_date'];
            if (!isset($rows[$date])) {
                $rows[$date] = [
                    'expense_date' => $date,
                    'categories' => [],
                    'total' => 0
                ];
            }

            $amount = (float)$row['amount'];
            $rows[$date]['categories'][$row['categoryname']] = $amount;
            $rows[$date]['total'] += $amount;
        }

        return array_values($rows);
    }

    public function getRecentExpenses($limit = 10) {
        $limit = max(1, (int)$limit);

        $stmt = $this->prepare("
            SELECT 
                e.expense_ID,
                e.expense_amount,
                e.expense_description,
                e.expense_date,
                e.created_at,
                ec.categoryname,
                emp.emp_firstname,
                emp.emp_lastname
            FROM expense e
            JOIN expensecategory ec ON e.expensecategory_ID = ec.expensecategory_ID
            LEFT JOIN employee emp ON e.processed_by_emp_ID = emp.emp_ID
            ORDER BY e.expense_date DESC, e.created_at DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getPaymentStatusExpression($alias = 'p') {
        if ($this->tableHasColumn('payment', 'payment_status')) {
            return "$alias.payment_status";
        }

        if ($this->tableHasColumn('payment', 'is_paid')) {
            return "CASE WHEN $alias.is_paid = 1 THEN 'PAID' ELSE 'PENDING' END";
        }

        return "CASE WHEN $alias.payment_ID IS NULL THEN 'NOT SET' ELSE 'PENDING' END";
    }

    private function getPaymentDateExpression($alias = 'p') {
        $dateColumns = [];
        if ($this->tableHasColumn('payment', 'paid_at')) {
            $dateColumns[] = "$alias.paid_at";
        }
        if ($this->tableHasColumn('payment', 'pay_date')) {
            $dateColumns[] = "$alias.pay_date";
        }
        if ($this->tableHasColumn('payment', 'updated_at')) {
            $dateColumns[] = "$alias.updated_at";
        }
        if ($this->tableHasColumn('payment', 'created_at')) {
            $dateColumns[] = "$alias.created_at";
        }

        if (empty($dateColumns)) {
            return 'NULL';
        }

        return 'COALESCE(' . implode(', ', $dateColumns) . ')';
    }
}

