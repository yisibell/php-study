<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET,POST,OPTIONS');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

// 加载自动加载
require __DIR__ . '/vendor/autoload.php';

// 获取请求
$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// 配置
$controllerDir = __DIR__ . '/app/Controllers';
$namespace = "App\\Controllers\\";
$routes = [];

// 自动扫描所有控制器
$files = scandir($controllerDir);
foreach ($files as $file) {
    if ($file === '.' || $file === '..') continue;

    $className = basename($file, '.php');
    $fullClass = $namespace . $className;

    // 反射读取注释
    $ref = new \ReflectionClass($fullClass);

    foreach ($ref->getMethods() as $methodObj) {
        $doc = $methodObj->getDocComment();

        // 匹配 @Get
        if (preg_match('/@Get\("(.*?)"\)/i', $doc, $match)) {
            $routes['GET'][$match[1]] = [$fullClass, $methodObj->getName()];
        }

        // 匹配 @Post
        if (preg_match('/@Post\("(.*?)"\)/i', $doc, $match)) {
            $routes['POST'][$match[1]] = [$fullClass, $methodObj->getName()];
        }
    }
}

// 匹配路由
if (isset($routes[$method][$uri])) {
    [$class, $action] = $routes[$method][$uri];
    $ctrl = new $class();
    $ctrl->$action();
} else {
    echo json_encode(['code' => 404, 'msg' => '路由不存在']);
}