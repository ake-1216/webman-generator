<?php

namespace Webman\Generator\Database;

use Illuminate\Support\Str;
use RuntimeException;

class TableSchema
{
    private string $table;
    private array $columns;
    private array $primaryKey;
    private array $foreignKeys;
    private array $indexes;
    
    public function __construct(string $table)
    {
        $this->table = $table;
        $this->loadTableSchema();
    }
    
    public function getTable(): string
    {
        return $this->table;
    }
    
    private function loadTableSchema(): void
    {
        $schema = ConnectionManager::schema();
        $connection = ConnectionManager::connection();
        
        // 检查表是否存在
        if (!$schema->hasTable($this->table)) {
            throw new RuntimeException("Table '{$this->table}' does not exist");
        }
        
        // 获取列信息
        $this->columns = $connection->getDoctrineSchemaManager()
            ->listTableColumns($this->table);
            
        // 获取主键
        $this->primaryKey = $schema->getColumnListing($this->table);
        
        // 获取外键
        $this->foreignKeys = $connection->getDoctrineSchemaManager()
            ->listTableForeignKeys($this->table);
            
        // 获取索引
        $this->indexes = $connection->getDoctrineSchemaManager()
            ->listTableIndexes($this->table);
    }
    
    public function getModelName(): string
    {
        return Str::studly(Str::singular($this->table));
    }
    
    public function getColumns(): array
    {
        return $this->columns;
    }
    
    public function getPrimaryKey(): array
    {
        return $this->primaryKey;
    }
    
    public function getForeignKeys(): array
    {
        return $this->foreignKeys;
    }
    
    public function getIndexes(): array
    {
        return $this->indexes;
    }
    
    public function getColumnComments(): array
    {
        $comments = [];
        $connection = ConnectionManager::connection();
        
        $sql = "SELECT COLUMN_NAME, COLUMN_COMMENT 
                FROM INFORMATION_SCHEMA.COLUMNS 
                WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?";
                
        $results = $connection->select($sql, [
            $connection->getDatabaseName(),
            $this->table
        ]);
        
        foreach ($results as $result) {
            $comments[$result->COLUMN_NAME] = $result->COLUMN_COMMENT;
        }
        
        return $comments;
    }
    
    public function getValidationRules(): array
    {
        $rules = [];
        
        foreach ($this->columns as $column) {
            $columnRules = [];
            
            // 必填规则
            if (!$column->getNotnull() && !$column->getAutoincrement()) {
                $columnRules[] = 'required';
            }
            
            // 类型规则
            switch ($column->getType()->getName()) {
                case 'integer':
                    $columnRules[] = 'integer';
                    break;
                case 'string':
                    $columnRules[] = 'string';
                    $columnRules[] = 'max:' . $column->getLength();
                    break;
                case 'text':
                    $columnRules[] = 'string';
                    break;
                case 'boolean':
                    $columnRules[] = 'boolean';
                    break;
                case 'datetime':
                    $columnRules[] = 'date';
                    break;
                case 'decimal':
                    $columnRules[] = 'numeric';
                    break;
            }
            
            if (!empty($columnRules)) {
                $rules[$column->getName()] = $columnRules;
            }
        }
        
        return $rules;
    }
} 