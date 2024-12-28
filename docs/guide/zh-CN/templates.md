# 自定义模板

代码生成器支持自定义代码模板，你可以根据自己的需求修改或创建新的模板。

## 模板位置

默认模板文件位于 `vendor/your-vendor/webman-generator/src/Template/stubs` 目录下：

```
stubs/
├── model.stub          # 模型模板
├── repository.stub     # 数据仓库模板
├── service.stub        # 服务层模板
├── controller.stub     # 控制器模板
├── validate.stub       # 验证器模板
└── route.stub          # 路由模板
```

## 模板语法

模板使用简单的变量替换语法：

- 使用 `{{ variable }}` 语法表示变量
- 变量名区分大小写
- 支持多行内容替换

## 可用变量

### 通用变量

- `{{ namespace }}` - 当前类的命名空间
- `{{ class }}` - 当前类名

### Model 模板变量

- `{{ table }}` - 数据表名
- `{{ primaryKey }}` - 主键字段
- `{{ fillable }}` - 可填充字段列表
- `{{ casts }}` - 字段类型转换
- `{{ hidden }}` - 隐藏字段
- `{{ relations }}` - 模型关联关系

### Repository 模板变量

- `{{ modelNamespace }}` - 模型类命名空间
- `{{ model }}` - 模型类名
- `{{ queryConditions }}` - 查询条件代码

### Service 模板变量

- `{{ repositoryNamespace }}` - 仓库类命名空间
- `{{ validateNamespace }}` - 验证器类命名空间
- `{{ repository }}` - 仓库类名
- `{{ validate }}` - 验证器类名

### Controller 模板变量

- `{{ serviceNamespace }}` - 服务类命名空间
- `{{ service }}` - 服务类名

### Validate 模板变量

- `{{ rules }}` - 验证规则
- `{{ messages }}` - 错误消息
- `{{ createScene }}` - 创建场景规则
- `{{ updateScene }}` - 更新场景规则

### Route 模板变量

- `{{ routePrefix }}` - 路由前缀
- `{{ controller }}` - 控制器完整类名

## 自定义模板示例

### 1. 复制默认模板

首先复制默认模板到你的项目中：

```bash
mkdir -p templates/stubs
cp vendor/your-vendor/webman-generator/src/Template/stubs/* templates/stubs/
```

### 2. 修改配置

在配置文件中指定自定义模板路径：

```php
return [
    'template_path' => base_path() . '/templates/stubs',
    // ... 其他配置
];
```

### 3. 修改模板

例如，修改控制器模板 `templates/stubs/controller.stub`：

```php
<?php

namespace {{ namespace }};

use support\Request;
use {{ serviceNamespace }}\{{ service }};

/**
 * @OA\Tag(
 *     name="{{ class }}",
 *     description="{{ class }} CRUD 接口"
 * )
 */
class {{ class }}
{
    protected $service;

    public function __construct()
    {
        $this->service = new {{ service }}();
    }

    /**
     * @OA\Get(
     *     path="/{{ routePrefix }}",
     *     summary="获取列表",
     *     tags={"{{ class }}"}
     * )
     */
    public function index(Request $request)
    {
        $params = $request->all();
        $list = $this->service->list($params);
        
        return json(['code' => 0, 'msg' => 'success', 'data' => $list]);
    }

    // ... 其他方法
}
```

## 最佳实践

1. 保持模板简单清晰
2. 使用适当的注释
3. 遵循代码规范
4. 考虑代码的可维护性
5. 添加必要的文档注释

## 下一步

- [最佳实践](best-practices.md)
- [常见问题](faq.md) 