<?php

namespace Tests\Database;

use Tests\TestCase;
use Webman\Generator\Database\ConnectionManager;

class ConnectionManagerTest extends TestCase
{
    public function testConnection()
    {
        $connection = ConnectionManager::connection();
        $this->assertNotNull($connection);
        
        // 测试数据库连接是否正常
        $result = $connection->select('SELECT 1');
        $this->assertEquals(1, $result[0]->{'1'});
    }
    
    public function testSchema()
    {
        $schema = ConnectionManager::schema();
        $this->assertNotNull($schema);
    }
    
    public function testTableOperations()
    {
        // 创建测试表
        $this->createTestTable();
        
        // 验证表是否存在
        $this->assertTrue(
            ConnectionManager::connection()
                ->getSchemaBuilder()
                ->hasTable('users')
        );
        
        // 验证表结构
        $columns = ConnectionManager::connection()
            ->getSchemaBuilder()
            ->getColumnListing('users');
            
        $this->assertContains('id', $columns);
        $this->assertContains('username', $columns);
        $this->assertContains('email', $columns);
        
        // 清理测试表
        $this->dropTestTable();
    }
} 