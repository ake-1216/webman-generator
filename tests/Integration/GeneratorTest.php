<?php

namespace Tests\Integration;

use Tests\IntegrationTestCase;
use Webman\Generator\Generator\GeneratorManager;
use Webman\Generator\Database\TableSchema;
use Symfony\Component\Console\Output\ConsoleOutput;

class GeneratorTest extends IntegrationTestCase
{
    protected $output;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->output = new ConsoleOutput();
    }
    
    public function testGenerateCompleteCode()
    {
        // 准备测试数据
        $table = 'users';
        $config = $this->getTestConfig();
        
        // 输出配置信息
        $this->output->writeln("\n测试配置:");
        $this->output->writeln("模板路径: " . $config['template_path']);
        $this->output->writeln("输出路径: " . print_r($config['output_path'], true));
        
        // 初始化表结构解析器
        $schema = new TableSchema($table);
        
        // 初始化生成器管理器
        $generator = new GeneratorManager($schema, $config, $this->output);
        
        // 执行代码生成
        $generator->generate();
        
        // 验证生成的文件
        $modelFile = $config['output_path']['model'] . '/User.php';
        $this->output->writeln("\n检查生成的文件:");
        $this->output->writeln("模型文件路径: " . $modelFile);
        $this->output->writeln("文件是否存在: " . (file_exists($modelFile) ? '是' : '否'));
        
        $this->assertFileExists($modelFile);
        $this->assertFileExists($config['output_path']['repository'] . '/UserRepository.php');
        $this->assertFileExists($config['output_path']['service'] . '/UserService.php');
        $this->assertFileExists($config['output_path']['controller'] . '/UserController.php');
        $this->assertFileExists($config['output_path']['validate'] . '/UserValidate.php');
        $this->assertFileExists($config['output_path']['route'] . '/user.php');
        
        // 验证生成的代码内容
        $modelContent = file_get_contents($modelFile);
        $this->assertStringContainsString('class User extends Model', $modelContent);
        $this->assertStringContainsString('protected $table = \'users\'', $modelContent);
        
        $controllerContent = file_get_contents($config['output_path']['controller'] . '/UserController.php');
        $this->assertStringContainsString('class UserController', $controllerContent);
        $this->assertStringContainsString('public function index(Request $request)', $controllerContent);
        
        $validateContent = file_get_contents($config['output_path']['validate'] . '/UserValidate.php');
        $this->assertStringContainsString('class UserValidate extends Validate', $validateContent);
        $this->assertStringContainsString('\'username\' => \'required\'', $validateContent);
    }
    
    public function testGenerateWithCustomConfig()
    {
        // 准备自定义配置
        $config = $this->getTestConfig();
        $config['namespace']['controller'] = 'admin\\controller';
        
        // 初始化表结构解析器
        $schema = new TableSchema('users');
        
        // 初始化生成器管理器
        $generator = new GeneratorManager($schema, $config, $this->output);
        
        // 执行代码生成
        $generator->generate();
        
        // 验证自定义命名空间
        $controllerContent = file_get_contents($config['output_path']['controller'] . '/UserController.php');
        $this->assertStringContainsString('namespace admin\\controller', $controllerContent);
    }
    
    public function testGenerateWithInvalidTable()
    {
        $this->expectException(\RuntimeException::class);
        
        $schema = new TableSchema('non_existent_table');
        $generator = new GeneratorManager($schema, $this->getTestConfig(), $this->output);
        $generator->generate();
    }
    
    public function testGenerateWithCustomTemplate()
    {
        // 准备自定义模板
        $templatePath = $this->outputPath . '/templates';
        
        // 修改模板文件内容
        $controllerStubPath = $templatePath . '/controller.stub';
        $content = file_get_contents($controllerStubPath);
        $content = str_replace('class {{ class }}', 'class {{ class }} // 自定义模板', $content);
        file_put_contents($controllerStubPath, $content);
        
        // 修改配置使用自定义模板
        $config = $this->getTestConfig();
        
        // 执行代码生成
        $schema = new TableSchema('users');
        $generator = new GeneratorManager($schema, $config, $this->output);
        $generator->generate();
        
        // 验证生成的代码
        $controllerContent = file_get_contents($config['output_path']['controller'] . '/UserController.php');
        $this->assertStringContainsString('class UserController // 自定义模板', $controllerContent);
    }
} 