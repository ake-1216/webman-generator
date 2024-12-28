<?php

namespace Webman\Generator\Generator;

use Webman\Generator\Database\TableSchema;
use Symfony\Component\Console\Output\OutputInterface;

class GeneratorManager
{
    private TableSchema $schema;
    private array $config;
    private OutputInterface $output;
    
    public function __construct(TableSchema $schema, array $config, OutputInterface $output)
    {
        $this->schema = $schema;
        $this->config = $config;
        $this->output = $output;
    }
    
    public function generate(): void
    {
        $this->output->writeln("\n开始生成代码...");
        
        $generators = [
            'Model' => ModelGenerator::class,
            'Repository' => RepositoryGenerator::class,
            'Service' => ServiceGenerator::class,
            'Controller' => ControllerGenerator::class,
            'Validate' => ValidateGenerator::class,
            'Route' => RouteGenerator::class,
        ];
        
        foreach ($generators as $name => $class) {
            $this->output->write("  生成 Webman\\{$name}      ");
            try {
                $generator = new $class($this->schema, $this->config, $this->output);
                $generator->generate();
                $this->output->writeln("✓");
            } catch (\Exception $e) {
                $this->output->writeln("✗");
                $this->output->writeln("    错误: " . $e->getMessage());
            }
        }
        
        $this->output->writeln("\n代码生成完成!");
    }
} 