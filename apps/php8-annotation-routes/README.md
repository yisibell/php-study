# php8-annotation-routes

基于 **PHP 8 Attribute 注解路由**的完整 API 练习项目：零框架实现「前端控制器 + 反射扫描路由 + 原生 MVC」，数据层用 MySQL（PDO），会话用 Redis Session。

## 与 PHP 7 版本的对比

| | [php7-annotation-routes](../php7-annotation-routes) | php8-annotation-routes（本项目） |
|--|--|--|
| 注解方式 | docblock 注释 + 正则匹配 | 原生 Attribute（`#[Get('/user/list')]`） |
| 路由扫描 | 正则解析注释文本 | ReflectionAttribute 反射 |
| 数据层 | 无（静态假数据） | MySQL + PDO 预处理绑定 |
| 会话 | 无 | Redis Session |
| 功能 | 3 个演示接口 | 注册 / 登录 / 用户管理 |

## 目录结构

```
php8-annotation-routes/
├── public/
│   └── index.php              # 入口：CORS 处理 → 反射扫描 Attribute → 路由分发
├── app/
│   ├── Attributes/            # 自定义注解类 Get / Post
│   ├── Controllers/           # 控制器：解析请求、调用 Service
│   ├── Services/              # 业务层：校验、业务逻辑、调用 Db
│   └── Utils/
│       ├── Db.php             # PDO 单例 + 预处理 + 通用分页
│       └── Response.php       # 统一 JSON 响应
└── config/
    ├── db.php                 # MySQL 连接配置
    └── cors.php               # CORS 配置
```

## 路由表

| 方法 | 路径 | 说明 |
|------|------|------|
| POST | `/auth/register` | 注册（邮箱 + 密码，成功后自动登录） |
| POST | `/auth/login` | 登录 |
| GET | `/auth/logout` | 退出登录（清空并销毁 Session） |
| GET | `/user/list` | 用户列表（需登录） |
| GET | `/user/info` | 当前登录用户信息（需登录） |
| GET | `/user/current` | 同 `/user/info` |
| GET | `/user/list-by-page?page=1&pageSize=10` | 分页用户列表（需登录） |

统一响应格式：`{"code": 0, "msg": "...", "data": ...}`，业务异常统一由 `Response::error()` 返回。

## 技术点

- **注解路由**：启动时扫描 `app/Controllers`，用 `ReflectionClass::getAttributes()` 读取 `#[Get]` / `#[Post]` 注册路由，无需路由配置文件
- **PDO 封装**：[Db.php](app/Utils/Db.php) 单例连接、预处理绑定防注入、通用分页（自动 COUNT + LIMIT）
- **密码安全**：`password_hash()` / `password_verify()`，数据库不存明文
- **Redis Session**：由 docker-env 的 PHP 配置（`php.custom.ini` + `redis.custom.ini`）开启，Session 落 Redis
- **CORS**：配置外置于 [config/cors.php](config/cors.php)，OPTIONS 预检请求直接放行

## 运行

仓库根目录的 [docker-env](../../docker-env/README.md) 默认已指向本项目，首次使用：

```bash
# 1. 生成 composer 自动加载文件（需先安装依赖）
composer install

# 2. 构建并启动环境
cd docker-env
docker compose build php
docker compose up -d --pull=never
```

启动后访问 <http://localhost:8080>。

首次使用需在 MySQL 中创建 `users` 表：

```sql
CREATE TABLE users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(191) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

## 接口示例

```bash
# 注册（成功后自动登录）
curl -X POST http://localhost:8080/auth/register \
  -H 'Content-Type: application/json' \
  -d '{"email":"test@example.com","password":"123456"}'

# 查看用户列表（需携带注册时返回的 Session Cookie）
curl -b cookies.txt http://localhost:8080/user/list
```
