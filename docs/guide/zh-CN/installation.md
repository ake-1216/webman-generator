# 安装说明

## Composer 安装

使用 Composer 安装代码生成器：

```bash
composer require your-vendor/webman-generator --dev
```

> 注意：建议只在开发环境中安装此工具。

## 发布配置文件

安装完成后，需要发布配置文件：

```bash
php webman generator:publish
```

这个命令会在 `config/plugin/generator/` 目录下创建配置文件。

## 配置数据库连接

编辑 `config/plugin/generator/app.php` 文件，配置数据库连接信息：

```php
return [
    'database' => [
        'driver' => 'mysql',
        'host' => '127.0.0.1',
        'port' => 3306,
        'database' => 'your_database',
        'username' => 'your_username',
        'password' => 'your_password',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
    ],
    // ... 其他配置
];
```

## 配置命名空间

在同一配置文件中，设置生成代码的命名空间：

```php
return [
    // ... 数据库配置
    'namespace' => [
        'controller' => 'app\\controller',
        'model' => 'app\\model',
        'repository' => 'app\\repository',
        'service' => 'app\\service',
        'validate' => 'app\\validate',
    ],
];
```

## 配置输出路径

配置生成的文件保存路径：

```php
return [
    // ... 其他配置
    'output_path' => [
        'controller' => app_path() . '/controller',
        'model' => app_path() . '/model',
        'repository' => app_path() . '/repository',
        'service' => app_path() . '/service',
        'validate' => app_path() . '/validate',
        'route' => base_path() . '/config/route',
    ],
];
```

## 验证安装

安装完成后，可以通过以下命令验证是否安装成功：

```bash
php webman generator:list
```

如果看到可用的生成器命令列表，说明安装成功。

## 常见问题

### 1. 安装失败

- 检查 PHP 版本是否满足要求 (>= 7.4)
- 检查 Composer 配置是否正确
- 尝试清除 Composer 缓存：`composer clear-cache`

### 2. 配置文件发布失败

- 确保 `config/plugin` 目录可写
- 手动创建配置目录：`mkdir -p config/plugin/generator`
- 手动复制配置文件

### 3. 数据库连接失败

- 检查数据库配置信息是否正确
- 确保数据库服务器可访问
- 检查数据库用户权限

## 下一步

- [快速开始](quickstart.md)
- [配置说明](configuration.md) 