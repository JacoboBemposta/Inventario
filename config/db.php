<?php
class DB {
    private static $host = "localhost";
    private static $db = "inventario";
    private static $user = "root"; 
    private static $pass = "";
    private static $conn;

    public static function connect() {
        if (self::$conn == null) {
            try {
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