<?php
class DB {
    private static $host;
    private static $db;
    private static $user;
    private static $pass;
    private static $conn;

    public static function connect() {
        if (self::$conn == null) {
            try {
                //variables de entorno, con valores predeterminados si no están definidas
                self::$host = getenv('DB_HOST') ?: 'localhost';
                self::$db   = getenv('DB_NAME') ?: 'inventario';
                self::$user = getenv('DB_USER') ?: 'root';
                self::$pass = getenv('DB_PASS') ?: '';

                self::$conn = new PDO("mysql:host=".self::$host.";dbname=".self::$db, self::$user, self::$pass);
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Error en la conexión: " . $e->getMessage());
            }
        }
        return self::$conn;
    }
}
?>
