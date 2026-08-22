# hello-docker

🐳 通用 Docker 环境学习仓库 —— 基于 Docker Compose 搭建各类开发环境，每个环境自包含、可移植、即开即用；环境服务的应用统一放在 `apps/` 下。

当前以 PHP 技术栈为例，后续可持续扩展其他语言 / 技术栈的环境。

## 目录结构

```
hello-docker/
├── docker-env/                  # PHP API 环境（Nginx + PHP-FPM + MySQL + Redis）
└── apps/
    ├── php8-annotation-routes/  # PHP 8 Attribute 注解路由 API
    └── php7-annotation-routes/  # PHP 7 docblock 注解路由演示
```

## 子项目文档

| 子项目                                                          | 说明                                    |
| --------------------------------------------------------------- | --------------------------------------- |
| [docker-env](docker-env/README.md)                              | PHP API 环境：编排、配置项、常用命令    |
| [php8-annotation-routes](apps/php8-annotation-routes/README.md) | PHP 8 注解路由 + MySQL + Redis 完整 API |
| [php7-annotation-routes](apps/php7-annotation-routes/README.md) | PHP 7 docblock 注解路由演示             |

## 快速开始

`docker-env` 默认挂载 php8 项目，启动后访问 <http://localhost:8080>：

```bash
cd docker-env
docker compose build php            # 首次构建 PHP 镜像
docker compose up -d --pull=never   # 启动全部服务（跳过网络拉取）
```

详细配置与常用命令见 [docker-env/README.md](docker-env/README.md)。

## 环境约定

仓库中的环境遵循同一套组织方式，新增环境照此复制即可：

- **自包含**：一个环境一个目录，编排、镜像构建、配置文件全部内聚，互不依赖
- **配置外置**：端口、密码、版本等集中在 `.env`，服务配置挂载进容器，改完重启即生效
- **健康检查驱动启动顺序**：依赖服务就绪后才启动下游，避免"连接被拒绝"竞态
- **数据持久化**：数据落命名卷，只有 `down -v` 才会销毁

当前环境一览：

| 环境                               | 组件                            | 服务的应用                                                              |
| ---------------------------------- | ------------------------------- | ----------------------------------------------------------------------- |
| [docker-env](docker-env/README.md) | Nginx / PHP-FPM / MySQL / Redis | [php8-annotation-routes](apps/php8-annotation-routes/README.md)（默认） |

## 应用演进

- **PHP 注解路由**：[php7](apps/php7-annotation-routes/README.md)（docblock 正则注解）→ [php8](apps/php8-annotation-routes/README.md)（原生 Attribute 反射 + MySQL/Redis）

## 注意事项

- 本项目仅用于个人学习，不可用于生产环境
- Docker Hub 网络不稳定时使用 `--pull=never` 走本地缓存镜像
