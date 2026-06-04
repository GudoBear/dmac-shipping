<?php
/**
 * Shared database model base class.
 * Public methods are safe helpers for controllers/pages.
 * Private methods keep low-level PDO work reusable and consistent.
 */
abstract class BaseModel {
    protected $db;
    private static $columnCache = [];
    private static $tableCache = [];

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function tableExists($table) {
        $key = (string)$table;
        if (array_key_exists($key, self::$tableCache)) {
            return self::$tableCache[$key];
        }
        $stmt = $this->db->prepare("SELECT 1 FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? LIMIT 1");
        $stmt->execute([$table]);
        return self::$tableCache[$key] = (bool)$stmt->fetchColumn();
    }

    public function columnExists($table, $column) {
        $key = $table . '.' . $column;
        if (array_key_exists($key, self::$columnCache)) {
            return self::$columnCache[$key];
        }
        $stmt = $this->db->prepare("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ? LIMIT 1");
        $stmt->execute([$table, $column]);
        return self::$columnCache[$key] = (bool)$stmt->fetchColumn();
    }

    protected function fetchAllRows($sql, array $params = []) {
        return $this->runStatement($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function fetchRow($sql, array $params = []) {
        return $this->runStatement($sql, $params)->fetch(PDO::FETCH_ASSOC);
    }

    protected function fetchValue($sql, array $params = []) {
        return $this->runStatement($sql, $params)->fetchColumn();
    }

    protected function executeQuery($sql, array $params = []) {
        return $this->runStatement($sql, $params)->rowCount();
    }

    private function runStatement($sql, array $params = []) {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
}
?>
