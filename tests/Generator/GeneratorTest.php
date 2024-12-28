<?php

namespace Tests\Generator;

use Tests\TestCase;
use Webman\Generator\Database\TableSchema;
use Webman\Generator\Generator\ModelGenerator;
use Webman\Generator\Generator\RepositoryGenerator;
use Webman\Generator\Generator\ServiceGenerator;
use Webman\Generator\Generator\ControllerGenerator;
use Webman\Generator\Generator\ValidateGenerator;
use Webman\Generator\Generator\RouteGenerator;

class GeneratorTest extends TestCase
{
    private TableSchema $schema;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->createTestTable();
        $this->schema = new TableSchema('users');
    }
    
    protected function tearDown(): void
    {
        $this->dropTestTable();
        parent::tearDown();
    }
    
    public function testModelGenerator()
    {
        $generator = new ModelGenerator($this->schema, $this->config);
        $generator->generate();
        
        $filePath = $this->config['output_path']['model'] . '/User.php';
        $this->assertFileExists($filePath);
        
        $content = file_get_contents($filePath);
        $this->assertStringContainsString('namespace app\\model', $content);
        $this->assertStringContainsString('class User extends Model', $content);
        $this->assertStringContainsString("protected \$table = 'users'", $content);
    }
    
    public function testRepositoryGenerator()
    {
        $generator = new RepositoryGenerator($this->schema, $this->config);
        $generator->generate();
        
        $filePath = $this->config['output_path']['repository'] . '/UserRepository.php';
        $this->assertFileExists($filePath);
        
        $content = file_get_contents($filePath);
        $this->assertStringContainsString('namespace app\\repository', $content);
        $this->assertStringContainsString('class UserRepository', $content);
        $this->assertStringContainsString('use app\\model\\User', $content);
    }
    
    public function testServiceGenerator()
    {
        $generator = new ServiceGenerator($this->schema, $this->config);
        $generator->generate();
        
        $filePath = $this->config['output_path']['service'] . '/UserService.php';
        $this->assertFileExists($filePath);
        
        $content = file_get_contents($filePath);
        $this->assertStringContainsString('namespace app\\service', $content);
        $this->assertStringContainsString('class UserService', $content);
        $this->assertStringContainsString('use app\\repository\\UserRepository', $content);
    }
    
    public function testControllerGenerator()
    {
        $generator = new ControllerGenerator($this->schema, $this->config);
        $generator->generate();
        
        $filePath = $this->config['output_path']['controller'] . '/UserController.php';
        $this->assertFileExists($filePath);
        
        $content = file_get_contents($filePath);
        $this->assertStringContainsString('namespace app\\controller', $content);
        $this->assertStringContainsString('class UserController', $content);
        $this->assertStringContainsString('use app\\service\\UserService', $content);
    }
    
    public function testValidateGenerator()
    {
        $generator = new ValidateGenerator($this->schema, $this->config);
        $generator->generate();
        
        $filePath = $this->config['output_path']['validate'] . '/UserValidate.php';
        $this->assertFileExists($filePath);
        
        $content = file_get_contents($filePath);
        $this->assertStringContainsString('namespace app\\validate', $content);
        $this->assertStringContainsString('class UserValidate extends Validate', $content);
        $this->assertStringContainsString("'username' => 'required|string'", $content);
    }
    
    public function testRouteGenerator()
    {
        $generator = new RouteGenerator($this->schema, $this->config);
        $generator->generate();
        
        $filePath = $this->config['output_path']['route'] . '/user.php';
        $this->assertFileExists($filePath);
        
        $content = file_get_contents($filePath);
        $this->assertStringContainsString("Route::group('/users'", $content);
        $this->assertStringContainsString('app\\controller\\UserController', $content);
    }
} 