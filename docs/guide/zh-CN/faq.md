# 常见问题

## 安装和配置

### Q: 安装时报错 "minimum-stability" 相关问题

**A:** 在你的 `composer.json` 中添加：

```json
{
    "minimum-stability": "dev",
    "prefer-stable": true
}
```

### Q: 找不到配置文件

**A:** 确保已经运行了发布命令：

```bash
php webman generator:publish
```

### Q: 数据库连接失败

**A:** 检查以下几点：
1. 数据库配置是否正确
2. 数据库服务是否启动
3. 用户名密码是否正确
4. 数据库是否存在

## 代码生成

### Q: 生成的文件没有出现在指定目录

**A:** 检查以下几点：
1. 输出目录配置是否正确
2. 目录是否有写入权限
3. 是否使用了 `--force` 参数覆盖现有文件

### Q: 表名和生成的类名不对应

**A:** 代码生成器会自动处理表名到类名的转换：
- 表名使用下划线命名法（snake_case）
- 类名使用大驼峰命名法（PascalCase）
- 默认会将表名单数化

例如：
- `users` -> `User`
- `order_items` -> `OrderItem`
- `product_categories` -> `ProductCategory`

### Q: 如何自定义生成的代码格式

**A:** 有两种方式：
1. 修改模板文件
2. 使用自定义模板路径

详见[自定义模板](templates.md)章节。

## 字段和验证

### Q: 如何修改字段验证规则

**A:** 有两种方法：
1. 在数据库中添加字段注释
2. 修改验证器模板

例如，添加字段注释：
```sql
`email` varchar(100) NOT NULL COMMENT '邮箱|email|required'
```

### Q: 如何处理特殊字段类型

**A:** 在模型中添加类型转换：

```php
protected $casts = [
    'options' => 'array',
    'is_active' => 'boolean',
    'price' => 'decimal:2'
];
```

### Q: 如何添加自定义验证规则

**A:** 在验证器类中添加自定义规则：

```php
protected function validateMobile($value)
{
    return preg_match('/^1[3-9]\d{9}$/', $value);
}
```

## 性能和优化

### Q: 生成的代码是否会影响性能

**A:** 生成的代码：
1. 使用了依赖注入
2. 实现了代码分层
3. 支持缓存机制
4. 可以根据需要优化

### Q: 如何优化查询性能

**A:** 可以：
1. 添加适当的索引
2. 使用查询缓存
3. 优化查询条件
4. 使用延迟加载

### Q: 大表如何处理

**A:** 建议：
1. 使用分页查询
2. 添加适当索引
3. 优化查询条件
4. 考虑分表策略

## 其他问题

### Q: 如何贡献代码

**A:** 欢迎提交 PR：
1. Fork 项目
2. 创建特性分支
3. 提交代码
4. 创建 Pull Request

### Q: 如何报告 Bug

**A:** 请在 GitHub Issues 中提供：
1. 问题描述
2. 复现步骤
3. 期望结果
4. 实际结果
5. 环境信息

### Q: 获取更多帮助

如果你遇到了其他问题：
1. 查看[文档](README.md)
2. 提交 [Issue](https://github.com/your-vendor/webman-generator/issues)
3. 加入交流群获取帮助 