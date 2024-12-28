<?php

namespace Webman\Generator\Generator;

class ServiceGenerator extends AbstractGenerator
{
    public function generate(): void
    {
        $template = $this->getTemplate('service');
        
        $vars = [
            'namespace' => $this->getNamespace('service'),
            'class' => $this->getClassName('service'),
            'repository_namespace' => $this->getNamespace('repository'),
            'repository_class' => $this->getClassName('repository'),
            'validate_namespace' => $this->getNamespace('validate'),
            'validate_class' => $this->getClassName('validate'),
        ];
        
        $content = $this->replaceVars($template, $vars);
        $this->saveFile('service', $content);
    }
} 