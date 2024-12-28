<?php

namespace Tests\Database;

use Tests\TestCase;
use Webman\Generator\Database\TableSchema;

class TableSchemaTest extends TestCase
{
    private TableSchema $schema;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        // 创建测试表
        $this->createTestTable();
        
        // 初始化表结构解析器
        $this->schema = new TableSchema('users');
    }
    
    protected function tearDown(): void
    {
        // 清理测试表
        $this->dropTestTable();
        
        parent::tearDown();
    }
    
    public function testGetModelName()
    {
        $this->assertEquals('User', $this->schema->getModelName());
    }
    
    public function testGetColumns()
    {
        $columns = $this->schema->getColumns();
        
        $this->assertCount(7, $columns);
        $this->assertArrayHasKey('id', $columns);
        $this->assertArrayHasKey('username', $columns);
        $this->assertArrayHasKey('email', $columns);
    }
    
    public function testGetPrimaryKey()
    {
        $primaryKey = $this->schema->getPrimaryKey();
        
        $this->assertIsArray($primaryKey);
        $this->assertContains('id', $primaryKey);
    }
    
    public function testGetColumnComments()
    {
        $comments = $this->schema->getColumnComments();
        
        $this->assertIsArray($comments);
        $this->assertEquals('用户名', $comments['username']);
        $this->assertEquals('邮箱', $comments['email']);
    }
    
    public function testGetValidationRules()
    {
        $rules = $this->schema->getValidationRules();
        
        $this->assertIsArray($rules);
        
        // 验证用户名规则
        $this->assertArrayHasKey('username', $rules);
        $this->assertContains('required', $rules['username']);
        $this->assertContains('string', $rules['username']);
        
        // 验证邮箱规则
        $this->assertArrayHasKey('email', $rules);
        $this->assertContains('required', $rules['email']);
        $this->assertContains('string', $rules['email']);
    }
} 