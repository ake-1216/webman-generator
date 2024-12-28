# Webman CRUD Generator

一个用于 Webman 框架的代码生成器，可以快速生成 CRUD 相关的代码文件。

## 功能特性

- 自动生成 Model、Repository、Service、Controller、Validate 和 Route 文件
- 支持自定义模板
- 支持自定义命名空间和输出路径
- 基于数据库表结构自动生成代码
- 支持验证规则生成
- 支持查询条件生成

## 安装

```bash
composer require webman/generator --dev
```

## 使用方法

### 基本用法

1. 在命令行中执行：

```bash
php webman generator:crud users
```

这将基于 users 表生成以下文件：
- app/model/User.php
- app/repository/UserRepository.php
- app/service/UserService.php
- app/controller/UserController.php
- app/validate/UserValidate.php
- config/route/user.php

### 自定义配置

你可以通过创建 `config/generator.php` 文件来自定义生成器的配置：

```php
return [
    // 模板文件路径
    'template_path' => base_path() . '/vendor/webman/generator/src/Template/stubs',
    
    // 生成文件的输出路径
    'output_path' => [
        'controller' => base_path() . '/app/controller',
        'model' => base_path() . '/app/model',
        'repository' => base_path() . '/app/repository',
        'service' => base_path() . '/app/service',
        'validate' => base_path() . '/app/validate',
        'route' => base_path() . '/config/route',
    ],
    
    // 命名空间配置
    'namespace' => [
        'controller' => 'app\\controller',
        'model' => 'app\\model',
        'repository' => 'app\\repository',
        'service' => 'app\\service',
        'validate' => 'app\\validate',
    ],
];
```

### 自定义模板

你可以通过复制并修改默认模板文件来自定义生成的代码。默认模板位于 `vendor/webman/generator/src/Template/stubs` 目录下：

- model.stub - 模型类模板
- repository.stub - 仓库类模板
- service.stub - 服务类模板
- controller.stub - 控制器类模板
- validate.stub - 验证器类模板
- route.stub - 路由配置模板

### 生成的代码结构

#### Model

```php
namespace app\model;

use support\Model;

class User extends Model
{
    protected $table = 'users';
    
    protected $fillable = ['username', 'email', 'status'];
    
    protected $casts = [
        'status' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
```

#### Repository

```php
namespace app\repository;

use app\model\User;

class UserRepository
{
    public function paginate(array $params = [])
    {
        $query = User::query();
        
        if (isset($params['username'])) {
            $query->where('username', $params['username']);
        }
        
        return $query->paginate();
    }
}
```

#### Service

```php
namespace app\service;

use app\repository\UserRepository;
use app\validate\UserValidate;

class UserService
{
    protected $repository;
    protected $validate;
    
    public function __construct(UserRepository $repository, UserValidate $validate)
    {
        $this->repository = $repository;
        $this->validate = $validate;
    }
    
    public function paginate(array $params = [])
    {
        return $this->repository->paginate($params);
    }
}
```

#### Controller

```php
namespace app\controller;

use app\service\UserService;
use support\Request;

class UserController
{
    protected $service;
    
    public function __construct(UserService $service)
    {
        $this->service = $service;
    }
    
    public function index(Request $request)
    {
        $params = $request->all();
        $data = $this->service->paginate($params);
        return json(['code' => 0, 'msg' => 'success', 'data' => $data]);
    }
}
```

## 测试

```bash
composer test
```

## 贡献

欢迎提交 Pull Request 和 Issue。

## 开源协议

MIT License 