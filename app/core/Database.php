<?php

class Database {
    private static $connection;

    public static function connect() {
        if (!self::$connection) {
            $config = require __DIR__ . '/../../config/database.php';

            self::$connection = new PDO(
                "mysql:host={$config['host']};dbname={$config['dbname']}",
                $config['user'],
                $config['pass']
            );
        }

        return self::$connection;
    }
}

?>