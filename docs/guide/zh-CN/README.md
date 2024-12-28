# Webman 代码生成器使用指南

## 目录

- [简介](introduction.md)
- [安装](installation.md)
- [快速开始](quickstart.md)
- [配置说明](configuration.md)
- [代码生成](generation.md)
- [自定义模板](templates.md)
- [最佳实践](best-practices.md)
- [常见问题](faq.md)

## 简介

Webman 代码生成器是一个用于快速生成 CRUD 代码的命令行工具。它可以根据数据库表结构自动生成模型、控制器、服务层、数据仓库、验证器和路由等代码文件。

### 主要特性

- 一键生成完整的 CRUD 代码结构
- 自动识别数据表结构
- 智能生成字段验证规则
- 支持自定义代码模板
- 支持多种数据库类型
- 遵循 PSR 规范和最佳实践

### 系统要求

- PHP >= 7.4
- Webman Framework >= 1.5.0
- MySQL/MariaDB 数据库

## 快速开始

1. 安装代码生成器：
```bash
composer require your-vendor/webman-generator --dev
```

2. 发布配置文件：
```bash
php webman generator:publish
```

3. 生成 CRUD 代码：
```bash
php webman generator:crud users
```

更多详细信息，请查看各个章节的具体说明。 