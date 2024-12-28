<?php

namespace Webman\Generator\Generator;

class RouteGenerator extends AbstractGenerator
{
    public function generate(): void
    {
        $template = $this->getTemplate('route');
        
        $vars = [
            'controller_namespace' => $this->getNamespace('controller'),
            'controller_class' => $this->getClassName('controller'),
            'route_prefix' => $this->getRoutePrefix(),
        ];
        
        $content = $this->replaceVars($template, $vars);
        
        // 路由文件使用小写表名
        $outputPath = $this->getOutputPath('route');
        $fileName = strtolower($this->schema->getTable());
        if (substr($fileName, -1) === 's') {
            $fileName = substr($fileName, 0, -1);
        }
        $filePath = $outputPath . '/' . $fileName . '.php';
        
        $this->createDirectory(dirname($filePath));
        file_put_contents($filePath, $content);
    }
    
    protected function getRoutePrefix(): string
    {
        $table = $this->schema->getTable();
        return str_replace('_', '-', $table);
    }
} 