<?php
if (!function_exists('get_db_connection')) {
    function get_db_connection() {
        static $pdo = null;
        
        if ($pdo !== null) {
            return $pdo;
        }

        $config = require __DIR__ . '/../config.php';

        try {
            $pdo = new PDO(
                "mysql:host={$config['db']['host']};dbname={$config['db']['database']};charset=utf8mb4",
                $config['db']['username'],
                $config['db']['password'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
            
            return $pdo;
        } catch (PDOException $e) {
            error_log("Database connection failed: " . $e->getMessage());
            if (strpos($e->getMessage(), "Unknown database") !== false) {
                die("Database does not exist. Please run the schema.sql file first.");
            }
            die("Could not connect to the database. Please check your configuration.");
        }
    }
}

return get_db_connection();