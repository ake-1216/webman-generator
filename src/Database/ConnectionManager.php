<?php

namespace Webman\Generator\Database;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Connection;

class ConnectionManager
{
    private static ?Capsule $capsule = null;
    
    public static function initialize(array $config): void
    {
        if (self::$capsule !== null) {
            return;
        }

        $capsule = new Capsule;
        
        $capsule->addConnection([
            'driver'    => $config['driver'] ?? 'mysql',
            'host'      => $config['host'] ?? '127.0.0.1',
            'port'      => $config['port'] ?? 3306,
            'database'  => $config['database'] ?? 'webman',
            'username'  => $config['username'] ?? 'root',
            'password'  => $config['password'] ?? '',
            'charset'   => $config['charset'] ?? 'utf8mb4',
            'collation' => $config['collation'] ?? 'utf8mb4_unicode_ci',
            'prefix'    => $config['prefix'] ?? '',
        ]);

        $capsule->setAsGlobal();
        $capsule->bootEloquent();
        
        self::$capsule = $capsule;
    }
    
    public static function connection(): Connection
    {
        if (self::$capsule === null) {
            throw new \RuntimeException('Database connection not initialized');
        }
        
        return self::$capsule->getConnection();
    }
    
    public static function schema()
    {
        return self::connection()->getSchemaBuilder();
    }
} 