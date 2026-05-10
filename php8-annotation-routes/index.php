<?php
// 引入自动加载
require __DIR__ . '/vendor/autoload.php';

use App\Attributes\Get;
use App\Attributes\Post;

// 跨域
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET,POST,OPTIONS');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

// 1. 获取当前请求方式和URI
$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// 2. 定义控制器目录、命名空间根
$ctrlNamespace = "App\\Controllers\\";
$ctrlDir = __DIR__ . '/app/Controllers';

// 3. 路由容器 [path => [控制器,方法]]
$routes = [];

// 4. 扫描控制器目录所有PHP文件
$files = scandir($ctrlDir);
foreach ($files as $file) {
    if ($file === '.' || $file === '..' || !str_ends_with($file, '.php')) {
        continue;
    }

    // 类名
    $className = basename($file, '.php');
    // 完整类全名
    $fullClass = $ctrlNamespace . $className;

    // 反射类
    $refClass = new ReflectionClass($fullClass);
    // 遍历所有方法
    foreach ($refClass->getMethods() as $refMethod) {
        // 读取 Get 注解
        $getAttr = $refMethod->getAttributes(Get::class);
        if (!empty($getAttr)) {
            $path = $getAttr[0]->newInstance()->path;
            $routes['GET'][$path] = [$fullClass, $refMethod->getName()];
        }

        // 读取 Post 注解
        $postAttr = $refMethod->getAttributes(Post::class);
        if (!empty($postAttr)) {
            $path = $postAttr[0]->newInstance()->path;
            $routes['POST'][$path] = [$fullClass, $refMethod->getName()];
        }
    }
}

// 5. 匹配路由并执行
if (isset($routes[$method][$uri])) {
    [$ctrlClass, $action] = $routes[$method][$uri];
    $ctrl = new $ctrlClass();
    $ctrl->$action();
} else {
    header('Content-Type:application/json');
    echo json_encode(['code'=>404,'msg'=>'接口不存在']);
}