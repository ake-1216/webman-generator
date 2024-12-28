<?php

namespace Webman\Generator\Generator;

use Webman\Generator\Database\TableSchema;
use Symfony\Component\Console\Output\OutputInterface;
use RuntimeException;

abstract class AbstractGenerator
{
    protected TableSchema $schema;
    protected array $config;
    protected OutputInterface $output;
    
    public function __construct(TableSchema $schema, array $config, OutputInterface $output)
    {
        $this->schema = $schema;
        $this->config = $config;
        $this->output = $output;
    }
    
    abstract public function generate(): void;
    
    protected function getNamespace(string $type): string
    {
        return $this->config['namespace'][$type] ?? '';
    }
    
    protected function getOutputPath(string $type): string
    {
        return $this->config['output_path'][$type] ?? '';
    }
    
    protected function getClassName(string $type): string
    {
        $table = $this->schema->getTable();
        $name = str_replace('_', '', ucwords($table, '_'));
        if (substr($name, -1) === 's') {
            $name = substr($name, 0, -1);
        }
        
        // 模型类不添加后缀
        if ($type === 'model') {
            return $name;
        }
        
        return $name . ucfirst($type);
    }
    
    protected function getTemplate(string $type): string
    {
        $path = $this->config['template_path'] . '/' . $type . '.stub';
        if (!file_exists($path)) {
            throw new RuntimeException("Template file not found: {$path}");
        }
        return file_get_contents($path);
    }
    
    protected function replaceVars(string $template, array $vars): string
    {
        foreach ($vars as $key => $value) {
            $template = str_replace('{{ ' . $key . ' }}', $value, $template);
        }
        return $template;
    }
    
    protected function saveFile(string $type, string $content): void
    {
        $outputPath = $this->getOutputPath($type);
        $className = $this->getClassName($type);
        $filePath = $outputPath . '/' . $className . '.php';
        
        $this->output->writeln("保存文件: " . $filePath);
        $this->output->writeln("目录是否存在: " . (is_dir(dirname($filePath)) ? '是' : '否'));
        
        $this->createDirectory(dirname($filePath));
        
        $this->output->writeln("创建目录后是否存在: " . (is_dir(dirname($filePath)) ? '是' : '否'));
        
        if (file_put_contents($filePath, $content) === false) {
            throw new RuntimeException("Failed to write file: {$filePath}");
        }
        
        $this->output->writeln("文件是否创建成功: " . (file_exists($filePath) ? '是' : '否'));
    }
    
    protected function createDirectory(string $path): void
    {
        if (!is_dir($path)) {
            if (!mkdir($path, 0777, true)) {
                throw new RuntimeException("Failed to create directory: {$path}");
            }
        }
    }
    
    protected function formatCode(string $code): string
    {
        // 移除多余的空行
        $code = preg_replace('/\n\s*\n\s*\n/', "\n\n", $code);
        // 确保文件以单个换行结束
        return rtrim($code) . "\n";
    }
} 