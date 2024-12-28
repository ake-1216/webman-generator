<?php

namespace Webman\Generator\Generator;

class ModelGenerator extends AbstractGenerator
{
    public function generate(): void
    {
        $template = $this->getTemplate('model');
        
        $vars = [
            'namespace' => $this->getNamespace('model'),
            'class' => $this->getClassName('model'),
            'table' => $this->schema->getTable(),
            'fillable' => $this->getFillable(),
            'casts' => $this->getCasts(),
            'dates' => $this->getDates(),
            'rules' => $this->getRules(),
        ];
        
        $content = $this->replaceVars($template, $vars);
        $this->saveFile('model', $content);
    }
    
    protected function getFillable(): string
    {
        $fillable = [];
        foreach ($this->schema->getColumns() as $column) {
            if (!in_array($column->getName(), ['id', 'created_at', 'updated_at'])) {
                $fillable[] = "        '{$column->getName()}'";
            }
        }
        return implode(",\n", $fillable);
    }
    
    protected function getCasts(): string
    {
        $casts = [];
        foreach ($this->schema->getColumns() as $column) {
            $type = $this->getCastType($column->getType()->getName());
            if ($type) {
                $casts[] = "        '{$column->getName()}' => '{$type}'";
            }
        }
        return implode(",\n", $casts);
    }
    
    protected function getDates(): string
    {
        $dates = [];
        foreach ($this->schema->getColumns() as $column) {
            if ($column->getType()->getName() === 'datetime') {
                $dates[] = "        '{$column->getName()}'";
            }
        }
        return implode(",\n", $dates);
    }
    
    protected function getRules(): string
    {
        $rules = [];
        foreach ($this->schema->getColumns() as $column) {
            $rule = $this->getValidationRule($column);
            if ($rule) {
                $rules[] = "        '{$column->getName()}' => '{$rule}'";
            }
        }
        return implode(",\n", $rules);
    }
    
    private function getCastType(string $type): ?string
    {
        return match ($type) {
            'integer' => 'integer',
            'decimal' => 'decimal',
            'float' => 'float',
            'boolean' => 'boolean',
            'datetime' => 'datetime',
            'json' => 'json',
            default => null,
        };
    }
    
    private function getValidationRule($column): ?string
    {
        $rules = [];
        
        if (!$column->getNotnull()) {
            $rules[] = 'nullable';
        }
        
        $type = $column->getType()->getName();
        $rules[] = match ($type) {
            'integer' => 'integer',
            'decimal', 'float' => 'numeric',
            'boolean' => 'boolean',
            'datetime' => 'date',
            default => 'string',
        };
        
        if ($column->getLength()) {
            $rules[] = "max:{$column->getLength()}";
        }
        
        return implode('|', $rules);
    }
} 