# 快速开始

本章节将通过一个简单的示例，演示如何使用代码生成器快速生成 CRUD 代码。

## 准备工作

1. 确保已经完成[安装](installation.md)和配置
2. 准备好数据库和表结构

## 示例表结构

以一个用户表为例：

```sql
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL COMMENT '用户名',
  `password` varchar(100) NOT NULL COMMENT '密码',
  `email` varchar(100) NOT NULL COMMENT '邮箱',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态:1=启用,0=禁用',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

## 生成代码

执行以下命令生成代码：

```bash
php webman generator:crud users
```

这个命令会生成以下文件：

```
app/
├── model/
│   └── User.php              # 数据模型
├── repository/
│   └── UserRepository.php    # 数据仓库
├── service/
│   └── UserService.php       # 服务层
├── controller/
│   └── UserController.php    # 控制器
├── validate/
│   └── UserValidate.php      # 验证器
└── route/
    └── user.php              # 路由配置
```

## 生成的 API 接口

生成的代码提供了以下 RESTful API 接口：

- `GET /users` - 获取用户列表
- `POST /users` - 创建用户
- `GET /users/{id}` - 获取用户详情
- `PUT /users/{id}` - 更新用户
- `DELETE /users/{id}` - 删除用户

## 使用示例

### 获取用户列表

```bash
curl http://localhost:8787/users
```

响应：
```json
{
    "code": 0,
    "msg": "success",
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "username": "admin",
                "email": "admin@example.com",
                "status": 1,
                "created_at": "2024-01-01 00:00:00"
            }
        ],
        "total": 1,
        "per_page": 15
    }
}
```

### 创建用户

```bash
curl -X POST http://localhost:8787/users \
  -H "Content-Type: application/json" \
  -d '{
    "username": "test",
    "password": "123456",
    "email": "test@example.com"
  }'
```

### 更新用户

```bash
curl -X PUT http://localhost:8787/users/1 \
  -H "Content-Type: application/json" \
  -d '{
    "email": "new@example.com"
  }'
```

## 自定义生成

### 指定配置文件

```bash
php webman generator:crud users --config=custom-config.php
```

### 强制覆盖现有文件

```bash
php webman generator:crud users --force
```

## 下一步

- [配置说明](configuration.md)
- [自定义模板](templates.md)
- [最佳实践](best-practices.md) 