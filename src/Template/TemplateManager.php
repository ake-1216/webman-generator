<?php

namespace Webman\Generator\Template;

class TemplateManager
{
    private string $templatePath;
    private array $variables = [];
    
    public function __construct(string $templatePath)
    {
        $this->templatePath = rtrim($templatePath, '/');
    }
    
    public function setVariables(array $variables): void
    {
        $this->variables = array_merge($this->variables, $variables);
    }
    
    public function render(string $template): string
    {
        $templateFile = $this->templatePath . '/' . $template . '.stub';
        
        if (!file_exists($templateFile)) {
            throw new \RuntimeException("Template file not found: {$templateFile}");
        }
        
        $content = file_get_contents($templateFile);
        
        return $this->replaceVariables($content);
    }
    
    private function replaceVariables(string $content): string
    {
        foreach ($this->variables as $key => $value) {
            $content = str_replace('{{ ' . $key . ' }}', $value, $content);
        }
        
        return $content;
    }
    
    public function save(string $template, string $targetPath): void
    {
        $content = $this->render($template);
        
        $directory = dirname($targetPath);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        
        file_put_contents($targetPath, $content);
    }
} 