<?php

namespace Tests\Template;

use Tests\TestCase;
use Webman\Generator\Template\TemplateManager;

class TemplateManagerTest extends TestCase
{
    private TemplateManager $template;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->template = new TemplateManager($this->config['template_path']);
    }
    
    public function testSetVariables()
    {
        $variables = [
            'namespace' => 'app\\model',
            'class' => 'User',
            'table' => 'users',
        ];
        
        $this->template->setVariables($variables);
        
        // 渲染模板并验证变量替换
        $content = $this->template->render('model');
        
        $this->assertStringContainsString('namespace app\\model', $content);
        $this->assertStringContainsString('class User', $content);
        $this->assertStringContainsString("protected \$table = 'users'", $content);
    }
    
    public function testRenderNonExistentTemplate()
    {
        $this->expectException(\RuntimeException::class);
        $this->template->render('non_existent');
    }
    
    public function testSave()
    {
        $variables = [
            'namespace' => 'app\\model',
            'class' => 'User',
            'table' => 'users',
            'primaryKey' => 'id',
            'fillable' => "'username',\n        'email'",
            'casts' => "'created_at' => 'datetime'",
            'hidden' => "'password'",
            'relations' => '',
        ];
        
        $this->template->setVariables($variables);
        
        $outputPath = $this->config['output_path']['model'] . '/User.php';
        $this->template->save('model', $outputPath);
        
        $this->assertFileExists($outputPath);
        $content = file_get_contents($outputPath);
        
        $this->assertStringContainsString('namespace app\\model', $content);
        $this->assertStringContainsString('class User', $content);
        $this->assertStringContainsString("protected \$table = 'users'", $content);
    }
} 