# Docker 开发环境 (docker-env)

基于 Docker Compose 的 PHP 8.3 本地开发环境,包含 **Nginx + PHP-FPM + MySQL + Redis** 四个服务,全部原生配置,无集成面板。

## 目录结构

```
docker-env/
├── docker-compose.yml          # Compose 编排（nginx / php / mysql / redis）
├── .env                        # 全局配置（端口、密码、项目路径）
├── nginx/
│   └── conf.d/
│       └── default.conf        # Nginx 站点配置（入口固定为 /public/index.php）
├── php/
│   ├── Dockerfile              # 自定义 PHP 镜像（基于 php:8.3-fpm）
│   └── conf.d/
│       ├── php.custom.ini      # PHP 配置（内存/时区/OPcache/Session）
│       └── redis.custom.ini    # Redis 扩展配置（Session 锁/超时）
├── mysql/
│   └── conf.d/
│       └── my.custom.cnf       # MySQL 配置（字符集/InnoDB/慢查询）
└── redis/
    └── redis.conf              # Redis 配置（内存上限/淘汰策略/AOF+RDB）
```

## 快速开始

```bash
cd docker-env

# 1. 编辑 .env，把 PROJECT_PATH 指向你的项目源码目录（默认 ../php8-annotation-routes）

# 2. 构建 PHP 镜像（php 服务基于本地 Dockerfile）
docker compose build php

# 3. 启动全部服务（--pull=never 使用本地缓存镜像，避免 Docker Hub 网络超时）
docker compose up -d --pull=never
```

启动后访问 <http://localhost:8080>。

## 服务说明

| 服务 | 镜像 | 宿主机端口 | 容器名 | 说明 |
|------|------|-----------|--------|------|
| nginx | `nginx`（官方） | `${NGINX_HOST_PORT}` → 80 | `php-api-nginx` | 反向代理，`fastcgi_pass php:9000` |
| php | 本地构建 `php:8.3-fpm` | - | `php-api-php` | 扩展：`mysqli` `pdo_mysql` `gd` `zip` `mbstring` `redis` |
| mysql | `mysql:8.0` | `${MYSQL_HOST_PORT}` → 3306 | `php-api-mysql` | 初始化库 `api_db`，数据卷 `mysql-data` |
| redis | `redis`（官方） | `${REDIS_HOST_PORT}` → 6379 | `php-api-redis` | AOF 持久化 + 密码认证，数据卷 `redis-data` |

四个服务处于同一网络 `api-network`，容器内通过服务名互访（如应用里连 MySQL 用 `host=mysql`、Redis 用 `host=redis`）。

## 配置项 (.env)

| 变量 | 默认值 | 说明 |
|------|--------|------|
| `PROJECT_PATH` | `../php8-annotation-routes` | 宿主机项目源码目录，挂载到容器的 `/var/www/html` |
| `NGINX_HOST_PORT` | `8080` | Nginx 宿主机端口 |
| `MYSQL_HOST_PORT` | `3306` | MySQL 宿主机端口 |
| `REDIS_HOST_PORT` | `6379` | Redis 宿主机端口 |
| `MYSQL_VERSION` | `8.0` | MySQL 镜像版本 |
| `MYSQL_ROOT_PASSWORD` | `root` | MySQL root 密码 |
| `MYSQL_DATABASE` | `api_db` | 首次启动自动创建的数据库 |
| `MYSQL_USER` / `MYSQL_PASSWORD` | `api_user` / `api_pass` | 业务账号（仅初始化时生效，改密码需清数据卷） |
| `REDIS_PASSWORD` | `redis_pass` | Redis 认证密码（见下方注意事项） |
| `PHP_IMAGE_NAME` | `php-api:8.3` | 构建产物镜像名 |
| `CONTAINER_PREFIX` | `php-api` | 容器名前缀 |
| `PHP_VERSION` | `8.3` | PHP 镜像版本，经 build args 传入 `php/Dockerfile`（修改后需重新构建镜像） |

修改 `.env` 后重新执行 `docker compose up -d` 即可生效。

## 自定义配置

所有配置文件通过 volume 挂载，改完宿主机的文件后重启对应服务生效（`docker compose restart <服务名>`）：

- **PHP**：`php/conf.d/php.custom.ini` → 容器 `/usr/local/etc/php/conf.d/custom.ini`
  - 内存/执行时间/上传限制、时区 `Asia/Shanghai`
  - Session 存储引擎为 Redis、严格模式 + HttpOnly（防 XSS / 会话固定）
  - OPcache 已开启
- **PHP Redis 扩展**：`php/conf.d/redis.custom.ini` → 容器 `docker-php-ext-redis.custom.ini`
  - Session 互斥锁（防并发击穿）、连接超时等
- **MySQL**：`mysql/conf.d/my.custom.cnf` → 容器 `/etc/mysql/conf.d/my.custom.cnf`
  - `utf8mb4` 字符集、InnoDB 内存调优、慢查询日志（>1s 记录到 `/var/lib/mysql/mysql-slow.log`）
- **Redis**：`redis/redis.conf` → 容器 `/usr/local/etc/redis/redis.conf`（只读挂载）
  - `maxmemory 256mb` + `volatile-lru` 淘汰策略
  - AOF（每秒刷盘）+ RDB 快照双重持久化
  - 密码不在该文件中配置，仍由 `.env` 的 `REDIS_PASSWORD` 经命令行参数传入（命令行优先级高于配置文件，避免两处不一致）
- **Nginx**：`nginx/conf.d/default.conf` → 容器 `/etc/nginx/conf.d/default.conf`
  - `client_max_body_size 20M`（与 PHP 上传限制一致）
  - 所有请求走 `/public/index.php`（API 专用），非此目录结构需改这里

## 常用命令

```bash
docker compose up -d --pull=never   # 启动（跳过网络拉取）
docker compose down                 # 停止并删除容器（保留数据卷）
docker compose down -v              # 连同数据卷一起删除（清空 MySQL/Redis 数据）
docker compose build php            # 重新构建 PHP 镜像（改 Dockerfile 后）
docker compose exec php sh          # 进入 PHP 容器
docker compose logs -f [nginx|php|mysql|redis]  # 查看日志
docker compose ps                   # 查看服务状态
```

## 注意事项

1. **Redis 密码需两处一致**：`php.custom.ini` 中 `session.save_path` 的 `auth=redis_pass` 必须与 `.env` 的 `REDIS_PASSWORD` 保持一致，否则 Session 写入会报错。
2. **启动顺序由健康检查保证**：MySQL / Redis 通过 `healthcheck` 确认就绪后 PHP 才启动，PHP 就绪后 Nginx 才启动（`depends_on: condition: service_healthy`），避免数据库未就绪时的"连接被拒绝"竞态。`docker compose ps` 可查看各服务健康状态。
3. **升级 PHP 版本**：修改 `.env` 的 `PHP_VERSION` 后需 `docker compose build php` 重新构建镜像（构建参数传入 Dockerfile）。
4. **首次拉取镜像**：Docker Hub 网络不稳定时先正常 `docker compose up -d` 拉取一次镜像，之后日常启动都用 `--pull=never` 走本地缓存。
5. **MYSQL_USER 只在初始化时生效**：`mysql-data` 数据卷已存在后修改 `MYSQL_USER/PASSWORD` 不会生效，需要 `docker compose down -v` 重建。
