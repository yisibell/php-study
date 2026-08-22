# php7-annotation-routes

PHP 7 时代的**注解路由**演示项目 —— 在方法的 docblock 注释里写 `@Get("/user/list")` 标注路由，入口文件用正则表达式解析注释构建路由表，零框架实现「前端控制器 + 自动扫描路由」。

> 这是 [php8-annotation-routes](../php8-annotation-routes) 的前身。PHP 7 没有原生 Attribute，只能用注释 + 正则模拟注解；PHP 8 则用原生反射直接读取 Attribute。

## 路由表

| 方法 | 路径 | 说明 |
|------|------|------|
| GET | `/user/list` | 用户列表 |
| GET | `/user/info` | 用户信息 |
| POST | `/user/add` | 添加用户 |

> 接口只返回写死的静态数据，不连数据库 —— 本项目只演示路由扫描机制本身。

## 目录结构

```
php7-annotation-routes/
├── index.php                  # 入口：扫描控制器 → 正则解析 docblock → 匹配路由分发
├── composer.json              # 仅 PSR-4 自动加载，无第三方依赖
└── app/
    └── Controllers/
        └── UserController.php # 演示控制器（@Get / @Post 注释标注路由）
```

## 实现原理

入口文件对 `app/Controllers` 下每个类做反射，再用正则从方法注释中提取路径：

```php
// 匹配 @Get("/user/list") 形式的注释
preg_match('/@Get\("(.*?)"\)/i', $doc, $match);
$routes['GET'][$match[1]] = [$fullClass, $methodName];
```

请求进来后按 `方法 + URI` 查路由表，命中则实例化控制器并调用对应方法。

## 运行

使用仓库根目录的 [docker-env](../../docker-env/README.md)，改两处配置后重新构建：

```bash
# 1. 修改 docker-env/.env：
#    PROJECT_PATH=../apps/php7-annotation-routes
#    PHP_VERSION=7.4

# 2. 生成 composer 自动加载文件
composer install      # 或在 PHP 容器内执行

# 3. 重新构建 PHP 镜像并启动
cd docker-env
docker compose build php
docker compose up -d --pull=never
```

启动后访问 <http://localhost:8080/user/list>。
