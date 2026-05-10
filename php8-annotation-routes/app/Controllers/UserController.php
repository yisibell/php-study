<?php
namespace App\Controllers;

use App\Attributes\Get;
use App\Attributes\Post;
use App\Services\UserService;
use App\Utils\Response;

class UserController
{
    #[Get('/user/list')]
    public function list()
    {
        $service = new UserService();
        Response::success($service->getList());
    }

    #[Get('/user/info')]
    public function info()
    {
        $id = $_GET['id'] ?? 0;
        $service = new UserService();
        $user = $service->findById((int)$id);
        $user ? Response::success($user) : Response::error('用户不存在');
    }

    #[Post('/user/add')]
    public function add()
    {
        Response::success([], '添加成功');
    }
}