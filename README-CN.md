# php-study

[English](README.md)

🐘 PHP-Study 学习仓库

个人 PHP 学习仓库。基于 Docker Compose 构建完整、独立、可移植的 PHP 开发环境，所有配置均为原生 Docker，不含任何集成面板，适合系统地学习 Linux、Docker 以及 PHP 后端基础。

---

📁 项目结构

```plain text
php-study/
├── docker-env/             # PHP 8.3 基础环境
├── docker-env2/            # PHP 8.4 最新实践环境
├── php7-annotation-routes/ # PHP 7 注解路由演示项目
├── php8-annotation-routes/ # PHP 8 注解路由演示项目
├── .gitignore
└── README.md
```

---

🧱 技术栈 (Docker 容器)

- Nginx: 静态资源托管、请求转发、反向代理

- PHP-FPM: PHP 运行时 (支持 PHP 8.3 / 8.4)

- MySQL 8.0: 持久化数据存储

已安装的 PHP 扩展

pdo_mysql, mysqli, gd, zip, mbstring, redis

---

🚀 快速开始 (Mac / Linux)

1. 启动环境 (避免 Docker 网络超时)

由于 Docker Hub 连接不稳定，推荐使用本地缓存镜像启动。

docker compose up -d --pull=never

2. 常用命令

# 后台启动容器

docker compose up -d

# 停止并移除容器

docker compose down

# 进入 PHP 容器终端

docker compose exec php sh

# 重启单个服务

docker compose restart php

# 查看实时日志

docker compose logs -f

---

✨ 环境特性

- 容器化部署: 无需本地安装 PHP、MySQL 或 Nginx

- 热挂载: 代码修改实时生效

- 数据持久化: MySQL 使用命名卷防止数据丢失

- 强隔离性: 多个 PHP 版本独立运行互不冲突

- 原生配置: 手写 Dockerfile 用于底层学习

---

📚 学习计划

1. Docker 基础命令、镜像、容器、挂载与网络

2. 手写 Dockerfile 自定义 PHP 镜像

3. Nginx + PHP-FPM 运行原理

4. PHP 注解路由、原生 MVC 与 API 开发

5. MySQL 基础、事务与关联查询

---

⚠️ 注意事项

- 本项目仅用于个人学习，不可用于生产环境。

- .env 文件、数据库密码及 vendor 依赖均已忽略，不会被上传。

- 开发环境为 Intel macOS，Docker 网络异常时请使用 --pull=never 跳过网络拉取。

---

🖥 开发环境

- 设备: Intel macOS

- 工具: Docker Desktop、Terminal

- 代理: 全局 VPN (Docker 引擎不跟随系统代理)

---

持续学习，持续进步 ✨
