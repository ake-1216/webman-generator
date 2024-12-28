<?php

namespace Webman\Generator\Generator;

use Webman\Generator\Database\TableSchema;

class ValidateGenerator extends AbstractGenerator
{
    protected function getRules(): array
    {
        $rules = [];
        foreach ($this->schema->getColumns() as $column) {
            $rules[] = "        '{$column->getName()}' => 'required'";
        }
        return $rules;
    }
    
    protected function getMessages(): array
    {
        $messages = [];
        foreach ($this->schema->getColumns() as $column) {
            $messages[] = "        '{$column->getName()}.required' => '{$column->getComment()} 不能为空'";
        }
        return $messages;
    }
    
    protected function getCreateScene(): array
    {
        $scene = [];
        foreach ($this->schema->getColumns() as $column) {
            if (!in_array($column->getName(), ['id', 'created_at', 'updated_at'])) {
                $scene[] = "            '{$column->getName()}'";
            }
        }
        return $scene;
    }
    
    protected function getUpdateScene(): array
    {
        return $this->getCreateScene();
    }
    
    public function generate(): void
    {
        $template = $this->getTemplate('validate');
        
        $vars = [
            'namespace' => $this->getNamespace('validate'),
            'class' => $this->getClassName('validate'),
            'rules' => implode(",\n", $this->getRules()),
            'messages' => implode(",\n", $this->getMessages()),
            'createScene' => implode(",\n", $this->getCreateScene()),
            'updateScene' => implode(",\n", $this->getUpdateScene()),
        ];
        
        $content = $this->replaceVars($template, $vars);
        $this->saveFile('validate', $content);
    }
} 