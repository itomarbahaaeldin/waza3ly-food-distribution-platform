<?php

class Config {
    private static $pdo = null;

    public static function getConnection() {
        if (self::$pdo === null) {
            try {
                $host = "localhost";
                $dbname = "Waza3ly";
                $user = "postgres";
                $password = "root";
                $port = 5432;

                self::$pdo = new PDO(
                    "pgsql:host={$host};port={$port};dbname={$dbname}",
                    $user,
                    $password
                );

                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            } catch (PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}

?>