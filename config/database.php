<?php
/**
 * SDC Dental Care Management System
 * Database Configuration & PDO Singleton Connection
 */

require_once __DIR__ . '/constants.php';

class Database {
    private static ?PDO $instance = null;
    private static bool $schemaMigrated = false;

    private const DB_HOST = '127.0.0.1';
    private const DB_PORT = '3306';
    private const DB_NAME = 'sdc_dental_db';
    private const DB_USER = 'root';
    private const DB_PASS = '';
    private const DB_CHARSET = 'utf8mb4';

    public static function getConnection(): PDO {
        if (self::$instance === null) {
            $dsn = "mysql:host=" . self::DB_HOST . ";port=" . self::DB_PORT . ";dbname=" . self::DB_NAME . ";charset=" . self::DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                self::$instance = new PDO($dsn, self::DB_USER, self::DB_PASS, $options);
                self::ensureSchemaUpdates();
            } catch (PDOException $e) {
                // If database does not exist or connection fails, log or provide structured error
                throw new PDOException("Database connection error: " . $e->getMessage(), (int)$e->getCode());
            }
        }
        return self::$instance;
    }

    /**
     * Auto-apply lightweight schema migrations for middle initial and nullable foreign keys
     */
    public static function ensureSchemaUpdates(): void {
        if (self::$schemaMigrated || self::$instance === null) {
            return;
        }

        try {
            // 1. Ensure middle_initial column exists in patients
            $col = self::$instance->query("SHOW COLUMNS FROM `patients` LIKE 'middle_initial'")->fetch();
            if (!$col) {
                self::$instance->exec("ALTER TABLE `patients` ADD COLUMN `middle_initial` VARCHAR(10) NULL DEFAULT NULL AFTER `first_name`");
            }

            // 2. Ensure patient_supply_usage allows NULL for guest patient usage
            self::$instance->exec("ALTER TABLE `patient_supply_usage` MODIFY COLUMN `patient_id` INT NULL");

            // 3. Ensure billing_invoices allows NULL for guest patient invoices
            self::$instance->exec("ALTER TABLE `billing_invoices` MODIFY COLUMN `patient_id` INT NULL");

            // 4. Ensure payments allows NULL for guest patient payments
            self::$instance->exec("ALTER TABLE `payments` MODIFY COLUMN `patient_id` INT NULL");
        } catch (Throwable $e) {
            // Gracefully ignore if tables do not exist yet or privileges restricted
        }

        self::$schemaMigrated = true;
    }

    /**
     * Check if connection to MySQL server can be established
     */
    public static function checkServerConnection(): bool {
        try {
            $dsn = "mysql:host=" . self::DB_HOST . ";port=" . self::DB_PORT . ";charset=" . self::DB_CHARSET;
            $pdo = new PDO($dsn, self::DB_USER, self::DB_PASS, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Helper to execute prepared queries with parameters
     */
    public static function query(string $sql, array $params = []): PDOStatement {
        $stmt = self::getConnection()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /**
     * Fetch single row
     */
    public static function fetch(string $sql, array $params = []): ?array {
        $stmt = self::query($sql, $params);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Fetch all rows
     */
    public static function fetchAll(string $sql, array $params = []): array {
        $stmt = self::query($sql, $params);
        return $stmt->fetchAll();
    }

    /**
     * Fetch single scalar value
     */
    public static function fetchValue(string $sql, array $params = []): mixed {
        $stmt = self::query($sql, $params);
        return $stmt->fetchColumn();
    }

    /**
     * Last Insert ID
     */
    public static function lastInsertId(): string {
        return self::getConnection()->lastInsertId();
    }
}

