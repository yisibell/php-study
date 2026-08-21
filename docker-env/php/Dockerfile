# 基础镜像（PHP 版本由构建参数控制，见 docker-compose.yml 的 build.args）
ARG PHP_VERSION=8.3
FROM php:${PHP_VERSION}-fpm

# 安装系统依赖
RUN apt-get update && apt-get install -y --no-install-recommends \
    libpng-dev \
    libzip-dev \
    libonig-dev \
    curl \
    git \
    && rm -rf /var/lib/apt/lists/*

# 安装常用PHP扩展
RUN docker-php-ext-install \
    mysqli \
    pdo_mysql \
    gd \
    zip \
    mbstring

# 安装Redis扩展
RUN pecl install redis && docker-php-ext-enable redis

# 自定义 php.ini 通过 docker-compose.yml 的 volumes 挂载（php/conf.d/），无需 COPY

# 工作目录
WORKDIR /var/www/html