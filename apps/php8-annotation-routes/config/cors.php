<?php

/**
 * CORS (跨域资源共享) 配置
 */
return [
    // 允许的源站地址
    'origin' => 'http://127.0.0.1:8080',

    // 允许的 HTTP 方法
    'methods' => 'GET,POST,OPTIONS',

    // 是否允许携带 Cookie
    'credentials' => true,

    // 允许的请求头
    'headers' => 'Content-Type',
];
