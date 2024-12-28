<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Webman\Generator\Database\ConnectionManager;

class IntegrationTestCase extends TestCase
{
    protected $testDb = 'wm_admin';
    protected $outputPath;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        // 设置测试数据库
        $config = [
            'driver' => 'mysql',
            'host' => 'kandian-mysql',
            'port' => 3306,
            'database' => $this->testDb,
            'username' => 'root',
            'password' => '123456',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
        ];
        
        ConnectionManager::initialize($config);
        
        // 创建临时输出目录
        $this->outputPath = '/tmp/generator_test_' . uniqid();
        mkdir($this->outputPath, 0777, true);
        
        // 创建模板目录并复制模板文件
        $this->createTemplateDirectory();
        
        // 准备测试数据库
        $this->prepareTestDatabase();
    }
    
    protected function tearDown(): void
    {
        parent::tearDown();
        
        // 清理临时目录
        $this->removeDirectory($this->outputPath);
        
        // 清理测试数据库
        $this->cleanTestDatabase();
    }
    
    protected function prepareTestDatabase(): void
    {
        $connection = ConnectionManager::connection();
        
        // 创建测试表
        $connection->statement("DROP TABLE IF EXISTS `users`");
        $connection->statement("
            CREATE TABLE `users` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `username` varchar(100) NOT NULL COMMENT '用户名',
                `password` varchar(100) NOT NULL COMMENT '密码',
                `email` varchar(100) NOT NULL COMMENT '邮箱',
                `status` tinyint(4) DEFAULT '1' COMMENT '状态:1=启用,0=禁用',
                `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at` timestamp NULL DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
    }
    
    protected function cleanTestDatabase(): void
    {
        $connection = ConnectionManager::connection();
        $connection->statement("DROP TABLE IF EXISTS `users`");
    }
    
    protected function removeDirectory($dir): void
    {
        if (!is_dir($dir)) {
            return;
        }
        
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            is_dir($path) ? $this->removeDirectory($path) : unlink($path);
        }
        rmdir($dir);
    }
    
    protected function getTestConfig(): array
    {
        return [
            'template_path' => $this->outputPath . '/templates',
            'output_path' => [
                'controller' => $this->outputPath . '/controller',
                'model' => $this->outputPath . '/model',
                'repository' => $this->outputPath . '/repository',
                'service' => $this->outputPath . '/service',
                'validate' => $this->outputPath . '/validate',
                'route' => $this->outputPath . '/route',
            ],
            'namespace' => [
                'controller' => 'Tests\\Generated\\Controller',
                'model' => 'Tests\\Generated\\Model',
                'repository' => 'Tests\\Generated\\Repository',
                'service' => 'Tests\\Generated\\Service',
                'validate' => 'Tests\\Generated\\Validate',
            ]
        ];
    }

    protected function createTemplateDirectory(): void
    {
        $templatePath = $this->outputPath . '/templates';
        if (!is_dir($templatePath)) {
            mkdir($templatePath, 0777, true);
        }

        // 复制所有模板文件
        $sourceDir = dirname(__DIR__) . '/src/Template/stubs';
        $files = scandir($sourceDir);
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            copy($sourceDir . '/' . $file, $templatePath . '/' . $file);
        }
    }
} 