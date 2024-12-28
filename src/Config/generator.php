<?php

return [
    'template_path' => __DIR__ . '/../Template',
    'output_path' => [
        'controller' => 'app/controller',
        'model' => 'app/model',
        'repository' => 'app/repository',
        'service' => 'app/service',
        'validate' => 'app/validate',
        'route' => 'config/route',
    ],
    'namespace' => [
        'controller' => 'app\\controller',
        'model' => 'app\\model',
        'repository' => 'app\\repository',
        'service' => 'app\\service',
        'validate' => 'app\\validate',
    ],
    'database' => [
        'driver' => 'mysql',
        'host' => '127.0.0.1',
        'port' => 3306,
        'database' => 'webman',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
    ]
]; 