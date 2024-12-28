<?php

namespace Webman\Generator\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Webman\Generator\Database\ConnectionManager;
use Webman\Generator\Database\TableSchema;
use Webman\Generator\Generator\GeneratorManager;

class CrudCommand extends Command
{
    protected static $defaultName = 'generator:crud';
    protected static $defaultDescription = 'Generate CRUD code from database table';

    protected function configure(): void
    {
        $this
            ->addArgument(
                'table',
                InputArgument::REQUIRED,
                'The database table name'
            )
            ->addOption(
                'force',
                'f',
                InputOption::VALUE_NONE,
                'Force overwrite existing files'
            )
            ->addOption(
                'config',
                'c',
                InputOption::VALUE_REQUIRED,
                'Custom config file path'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $table = $input->getArgument('table');
        $force = $input->getOption('force');
        $configPath = $input->getOption('config');
        
        try {
            // 加载配置
            $config = $this->loadConfig($configPath);
            
            // 显示配置信息
            $output->writeln("<info>使用配置:</info>");
            $output->writeln("  数据库: {$config['database']['database']}");
            $output->writeln("  表名: {$table}");
            
            // 如果不是强制模式，询问是否继续
            if (!$force && !$this->confirmGeneration($input, $output)) {
                return Command::SUCCESS;
            }
            
            // 初始化数据库连接
            ConnectionManager::initialize($config['database']);
            
            // 解析表结构
            $schema = new TableSchema($table);
            
            $output->writeln("\n<info>表结构解析完成</info>");
            $output->writeln("  模型名称: " . $schema->getModelName());
            $output->writeln("  字段数量: " . count($schema->getColumns()));
            
            // 生成代码
            $generator = new GeneratorManager($schema, $config, $output);
            $generator->generate();
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $output->writeln("<error>错误: {$e->getMessage()}</error>");
            return Command::FAILURE;
        }
    }
    
    private function loadConfig(?string $configPath): array
    {
        if ($configPath) {
            if (!file_exists($configPath)) {
                throw new \RuntimeException("配置文件不存在: {$configPath}");
            }
            $config = require $configPath;
        } else {
            $config = require __DIR__ . '/../Config/generator.php';
        }
        
        $this->validateConfig($config);
        
        return $config;
    }
    
    private function validateConfig(array $config): void
    {
        $required = ['database', 'namespace', 'output_path', 'template_path'];
        
        foreach ($required as $key) {
            if (!isset($config[$key])) {
                throw new \RuntimeException("配置缺少必要项: {$key}");
            }
        }
    }
    
    private function confirmGeneration(InputInterface $input, OutputInterface $output): bool
    {
        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion('确定要生成代码吗? [y/N] ', false);
        
        return $helper->ask($input, $output, $question);
    }
} 