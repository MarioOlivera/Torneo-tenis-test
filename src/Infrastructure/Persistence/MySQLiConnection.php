<?php
namespace Src\Infrastructure\Persistence;

class MySQLiConnection {
    private static ?\mysqli $connection = null;

    public static function getInstance(): \mysqli {
        if (self::$connection === null) {
            self::$connection = new \mysqli(
                $_ENV['DB_HOST'],
                $_ENV['DB_USER'],
                $_ENV['DB_PASS'],
                $_ENV['DB_NAME']
            );
            
            if (self::$connection->connect_error) {
                throw new \RuntimeException("DB connection failed: " . self::$connection->connect_error);
            }
        }
        return self::$connection;
    }
}