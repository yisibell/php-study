# 基础镜像
FROM php:8.4-fpm

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

# 拷贝自定义php.ini到容器
# COPY php.ini /usr/local/etc/php/conf.d/

# 工作目录
WORKDIR /var/www/html