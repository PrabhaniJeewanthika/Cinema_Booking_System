<?php
class Database {
    private static $conn = null;

    private function __construct() {}

    public static function getConnection() {
        if (self::$conn === null) {
            self::$conn = new mysqli('localhost', 'root', '', 'cinema_booking');

            if (self::$conn->connect_error) {
                die("Database Connection Failed: " . self::$conn->connect_error);
            }
        }
        return self::$conn;
    }

    public static function closeConnection() {
        if (self::$conn !== null) {
            self::$conn->close();
            self::$conn = null;
        }
    }
}
?>
