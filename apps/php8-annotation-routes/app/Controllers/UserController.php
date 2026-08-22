<?php
namespace App\Controllers;

use App\Attributes\Get;
use App\Attributes\Post;
use App\Services\UserService;
use App\Utils\Response;
use Exception;

class UserController
{
  #[Get('/user/list')]
  public function list()
  {
    try {
      $service = new UserService();
      $users = $service->getList();
      Response::success($users);
    } catch (Exception $e) {
      Response::error($e->getMessage());
    }
  }

  #[Get('/user/info')]
  public function info()
  {
    try {
      $service = new UserService();
      $user = $service->getUserInfo();
      $user ? Response::success($user) : Response::error('未登录');
    } catch (Exception $e) {
      Response::error($e->getMessage());
    }
  }

  #[Get('/user/current')]
  public function current()
  {
    try {
      $service = new UserService();
      $user = $service->getUserInfo();
      $user ? Response::success($user) : Response::error('未登录');
    } catch (Exception $e) {
      Response::error($e->getMessage());
    }
  }

  #[Get('/user/list-by-page')]
  public function listByPage()
  {
    try {
      // 取值 + 转整型 + 设默认值
      $page = (int) ($_GET['page'] ?? 1);       // 默认第1页
      $pageSize = (int) ($_GET['pageSize'] ?? 10); // 默认每页10条

      $service = new UserService();
      $users = $service->getListByPage($page, $pageSize);

      Response::success($users);

    } catch (Exception $e) {
      Response::error($e->getMessage());
    }
  }

}