<?php

namespace Tests\Command;

use Tests\TestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Webman\Generator\Command\CrudCommand;

class CrudCommandTest extends TestCase
{
    private CommandTester $commandTester;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $command = new CrudCommand();
        $this->commandTester = new CommandTester($command);
        
        $this->createTestTable();
    }
    
    protected function tearDown(): void
    {
        $this->dropTestTable();
        parent::tearDown();
    }
    
    public function testExecute()
    {
        // 执行命令
        $this->commandTester->execute([
            'table' => 'users',
            '--force' => true,
        ]);
        
        // 验证输出
        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('使用配置', $output);
        $this->assertStringContainsString('表结构解析完成', $output);
        $this->assertStringContainsString('开始生成代码', $output);
        $this->assertStringContainsString('代码生成完成', $output);
        
        // 验证生成的文件
        $this->assertFileExists($this->config['output_path']['model'] . '/User.php');
        $this->assertFileExists($this->config['output_path']['repository'] . '/UserRepository.php');
        $this->assertFileExists($this->config['output_path']['service'] . '/UserService.php');
        $this->assertFileExists($this->config['output_path']['controller'] . '/UserController.php');
        $this->assertFileExists($this->config['output_path']['validate'] . '/UserValidate.php');
        $this->assertFileExists($this->config['output_path']['route'] . '/user.php');
    }
    
    public function testExecuteWithCustomConfig()
    {
        // 创建临时配置文件
        $configPath = __DIR__ . '/../temp/custom-config.php';
        $this->createDirectory(dirname($configPath));
        file_put_contents($configPath, '<?php return ' . var_export($this->config, true) . ';');
        
        // 执行命令
        $this->commandTester->execute([
            'table' => 'users',
            '--force' => true,
            '--config' => $configPath,
        ]);
        
        // 验证输出
        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('使用配置', $output);
        $this->assertStringContainsString('代码生成完成', $output);
    }
    
    public function testExecuteWithNonExistentTable()
    {
        $this->commandTester->execute([
            'table' => 'non_existent_table',
            '--force' => true,
        ]);
        
        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('错误', $output);
    }
    
    public function testExecuteWithInvalidConfig()
    {
        $configPath = __DIR__ . '/../temp/invalid-config.php';
        $this->createDirectory(dirname($configPath));
        file_put_contents($configPath, '<?php return [];');
        
        $this->commandTester->execute([
            'table' => 'users',
            '--force' => true,
            '--config' => $configPath,
        ]);
        
        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('配置缺少必要项', $output);
    }
} 