<?php

namespace Webman\Generator\Generator;

class ControllerGenerator extends AbstractGenerator
{
    public function generate(): void
    {
        $template = $this->getTemplate('controller');
        
        $vars = [
            'namespace' => $this->getNamespace('controller'),
            'class' => $this->getClassName('controller'),
            'service_namespace' => $this->getNamespace('service'),
            'service_class' => $this->getClassName('service'),
            'validate_namespace' => $this->getNamespace('validate'),
            'validate_class' => $this->getClassName('validate'),
            'table' => $this->schema->getTable(),
        ];
        
        $content = $this->replaceVars($template, $vars);
        $this->saveFile('controller', $content);
    }
} 