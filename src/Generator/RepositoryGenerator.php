<?php

namespace Webman\Generator\Generator;

class RepositoryGenerator extends AbstractGenerator
{
    public function generate(): void
    {
        $template = $this->getTemplate('repository');
        
        $vars = [
            'namespace' => $this->getNamespace('repository'),
            'class' => $this->getClassName('repository'),
            'model_namespace' => $this->getNamespace('model'),
            'model_class' => $this->getClassName('model'),
            'query_conditions' => $this->getQueryConditions(),
        ];
        
        $content = $this->replaceVars($template, $vars);
        $this->saveFile('repository', $content);
    }
    
    protected function getQueryConditions(): string
    {
        $conditions = [];
        foreach ($this->schema->getColumns() as $column) {
            $name = $column->getName();
            if (!in_array($name, ['id', 'created_at', 'updated_at'])) {
                $conditions[] = <<<PHP
                if (isset(\$params['{$name}']) && \$params['{$name}'] !== '') {
                    \$query->where('{$name}', \$params['{$name}']);
                }
PHP;
            }
        }
        return implode("\n            ", $conditions);
    }
} 