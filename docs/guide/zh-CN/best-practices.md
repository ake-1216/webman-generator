# 最佳实践

本章节介绍使用代码生成器的一些最佳实践和建议。

## 数据库设计

### 1. 表命名规范

- 使用小写字母和下划线
- 使用复数形式
- 表名应该清晰表达业务含义

```sql
-- 好的命名
users
order_items
product_categories

-- 不好的命名
user
orderItem
ProductCategory
```

### 2. 字段命名规范

- 使用小写字母和下划线
- 主键统一使用 `id`
- 外键使用 `表名_id` 格式
- 统一使用 `created_at`、`updated_at` 记录时间

```sql
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `product_id` int(11) NOT NULL COMMENT '商品ID',
  `status` tinyint(4) NOT NULL COMMENT '状态',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### 3. 添加字段注释

- 使用清晰的中文注释
- 说明字段用途
- 对于枚举值，列出所有可能的值

```sql
`status` tinyint(4) NOT NULL COMMENT '状态:1=待付款,2=已付款,3=已发货,4=已完成,5=已取消'
```

## 代码组织

### 1. 目录结构

推荐的目录结构：

```
app/
├── controller/         # 控制器层：处理请求和响应
├── model/             # 模型层：数据库映射
├── repository/        # 仓库层：数据访问
├── service/           # 服务层：业务逻辑
├── validate/          # 验证层：数据验证
└── support/           # 支持类：工具、助手等
```

### 2. 分层职责

- Controller：请求处理、参数校验、响应格式化
- Service：业务逻辑、事务处理、数据组装
- Repository：数据访问、查询条件、ORM 操作
- Model：数据映射、关联关系、属性转换
- Validate：数据验证、验证规则、错误消息

## 开发流程

### 1. 准备阶段

1. 设计数据库表结构
2. 编写建表 SQL
3. 准备配置文件

### 2. 生成代码

1. 生成基础 CRUD 代码
2. 检查生成的文件
3. 调整代码结构

### 3. 自定义开发

1. 添加自定义业务逻辑
2. 扩展查询条件
3. 完善验证规则
4. 添加单元测试

## 代码规范

### 1. 命名规范

- 类名：大驼峰命名，如 `UserService`
- 方法名：小驼峰命名，如 `getUserList`
- 变量名：小驼峰命名，如 `pageSize`
- 常量名：大写下划线，如 `MAX_RETRY_TIMES`

### 2. 注释规范

- 类注释：说明类的用途
- 方法注释：说明参数和返回值
- 关键代码注释：说明实现逻辑

```php
/**
 * 用户服务类
 */
class UserService
{
    /**
     * 获取用户列表
     *
     * @param array $params 查询参数
     * @return array
     */
    public function getUserList(array $params)
    {
        // 处理查询参数
        $query = $this->buildQuery($params);
        
        // 获取分页数据
        return $query->paginate();
    }
}
```

## 安全考虑

### 1. 输入验证

- 始终验证用户输入
- 使用验证器类
- 设置适当的验证规则

### 2. SQL 注入防范

- 使用参数绑定
- 避免直接拼接 SQL
- 使用 ORM 的查询构造器

### 3. XSS 防范

- 输出时进行 HTML 转义
- 使用 `htmlspecialchars` 函数
- 在模板中使用安全的输出方式

## 性能优化

### 1. 数据库优化

- 合理使用索引
- 优化查询条件
- 避免 N+1 查询问题

### 2. 缓存使用

- 缓存查询结果
- 使用缓存标签
- 设置合适的缓存时间

### 3. 代码优化

- 避免重复查询
- 使用批量操作
- 延迟加载关联数据

## 下一步

- [常见问题](faq.md) 