<?php

namespace Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Webman\Generator\Database\ConnectionManager;

abstract class TestCase extends BaseTestCase
{
    protected array $config;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        // 加载测试配置
        $this->config = [
            'database' => [
                'driver' => 'mysql',
                'host' => '127.0.0.1',
                'port' => 3306,
                'database' => 'webman_test',
                'username' => 'root',
                'password' => '',
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ],
            'namespace' => [
                'controller' => 'app\\controller',
                'model' => 'app\\model',
                'repository' => 'app\\repository',
                'service' => 'app\\service',
                'validate' => 'app\\validate',
            ],
            'output_path' => [
                'controller' => __DIR__ . '/temp/app/controller',
                'model' => __DIR__ . '/temp/app/model',
                'repository' => __DIR__ . '/temp/app/repository',
                'service' => __DIR__ . '/temp/app/service',
                'validate' => __DIR__ . '/temp/app/validate',
                'route' => __DIR__ . '/temp/config/route',
            ],
            'template_path' => __DIR__ . '/../src/Template/stubs',
        ];
        
        // 初始化数据库连接
        ConnectionManager::initialize($this->config['database']);
        
        // 创建临时目录
        $this->createTempDirectories();
    }
    
    protected function tearDown(): void
    {
        parent::tearDown();
        
        // 清理临时目录
        $this->cleanTempDirectories();
    }
    
    protected function createTempDirectories(): void
    {
        foreach ($this->config['output_path'] as $path) {
            if (!is_dir($path)) {
                mkdir($path, 0755, true);
            }
        }
    }
    
    protected function cleanTempDirectories(): void
    {
        $this->removeDirectory(__DIR__ . '/temp');
    }
    
    protected function removeDirectory(string $path): void
    {
        if (!is_dir($path)) {
            return;
        }
        
        $files = array_diff(scandir($path), ['.', '..']);
        foreach ($files as $file) {
            $filePath = $path . '/' . $file;
            is_dir($filePath) ? $this->removeDirectory($filePath) : unlink($filePath);
        }
        
        rmdir($path);
    }
    
    protected function createTestTable(): void
    {
        $sql = <<<SQL
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL COMMENT '用户名',
  `password` varchar(100) NOT NULL COMMENT '密码',
  `email` varchar(100) NOT NULL COMMENT '邮箱',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态:1=启用,0=禁用',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
SQL;
        
        ConnectionManager::connection()->statement($sql);
    }
    
    protected function dropTestTable(): void
    {
        ConnectionManager::connection()->statement('DROP TABLE IF EXISTS `users`');
    }
} 