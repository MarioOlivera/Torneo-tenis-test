<?php
namespace Src\Infrastructure\Persistence;

class MySQLiConnection {
    private static ?\mysqli $connection = null;

    public static function getInstance(): \mysqli {
        if (self::$connection === null) {
            self::$connection = new \mysqli(
                'localhost',
                'root',
                '', 
                'torneo'
            );
            
            if (self::$connection->connect_error) {
                throw new \RuntimeException("DB connection failed: " . self::$connection->connect_error);
            }
        }
        return self::$connection;
    }
}