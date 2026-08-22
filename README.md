# hello-docker

🐘 Docker + PHP 学习仓库 —— 基于 Docker Compose 搭建完整、可移植的 PHP 开发环境，零框架手写 PHP API，系统学习 Linux、Docker 与 PHP 后端基础。

## 目录结构

```
hello-docker/
├── docker-env/                  # Docker 开发环境（Nginx + PHP-FPM + MySQL + Redis）
└── apps/
    ├── php8-annotation-routes/  # PHP 8 Attribute 注解路由 API（当前主项目）
    └── php7-annotation-routes/  # PHP 7 docblock 注解路由演示（演进前身）
```

## 子项目文档

| 子项目 | 说明 |
|--------|------|
| [docker-env](docker-env/README.md) | 环境编排、配置项、常用命令、注意事项 |
| [php8-annotation-routes](apps/php8-annotation-routes/README.md) | PHP 8 注解路由 + MySQL + Redis 完整 API |
| [php7-annotation-routes](apps/php7-annotation-routes/README.md) | PHP 7 docblock 注解路由演示 |

## 快速开始

`docker-env` 默认挂载 php8 项目，启动后访问 <http://localhost:8080>：

```bash
cd docker-env
docker compose build php            # 首次构建 PHP 镜像
docker compose up -d --pull=never   # 启动全部服务（跳过网络拉取）
```

详细配置与常用命令见 [docker-env/README.md](docker-env/README.md)。

## 技术栈

| 组件 | 说明 |
|------|------|
| Nginx | 反向代理，请求转发至 PHP-FPM（`fastcgi_pass php:9000`） |
| PHP-FPM | PHP 运行时（版本由 `.env` 控制，默认 8.3） |
| MySQL 8.0 | 数据持久化（命名卷 + 自定义配置） |
| Redis | 缓存 + Session 存储（AOF / RDB 双持久化） |

已安装的 PHP 扩展：`pdo_mysql`、`mysqli`、`gd`、`zip`、`mbstring`、`redis`

## 学习脉络

- **应用演进**：`php7-annotation-routes`（docblock 正则注解）→ `php8-annotation-routes`（原生 Attribute 反射 + MySQL/Redis）
- **环境演进**：早期三件套环境（已删除，git 历史可见）→ 现在 `docker-env` 四件套 + 健康检查启动顺序 + 配置外置

## 注意事项

- 本项目仅用于个人学习，不可用于生产环境
- `.env` 文件与数据库密码均已 gitignore，不会被上传
- Docker Hub 网络不稳定时使用 `--pull=never` 走本地缓存镜像
