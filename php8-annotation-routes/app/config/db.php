<?php

/**
 * 数据库连接配置（对应 docker-env/.env 中的 MySQL 配置）
 */
return [
    // 数据库主机（Docker 网络内用服务名连接）
    'host' => 'mysql',

    // 数据库端口
    'port' => '3306',

    // 数据库名
    'dbname' => 'api_db',

    // 用户名
    'username' => 'root',

    // 密码
    'password' => 'root',

    // 字符集
    'charset' => 'utf8mb4',
];
