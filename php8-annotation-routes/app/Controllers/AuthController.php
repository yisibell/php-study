<?php
namespace App\Controllers;

use App\Attributes\Post;
use App\Attributes\Get;
use App\Services\AuthService;
use App\Utils\Response;
use Exception; // 新增：引入异常类

class AuthController
{
  /**
   * 注册接口（仅邮箱+密码）
   */
  #[Post('/auth/register')]
  public function register()
  {
    try {
      // 获取POST参数（兼容JSON和表单提交）
      $postData = json_decode(file_get_contents('php://input'), true) ?: $_POST;
      $authService = new AuthService();

      if ($authService->register($postData)) {
        // 注册成功后自动登录
        $user = $authService->login($postData['email'], $postData['password']);
        $_SESSION['user'] = $user;
        Response::success($user, '注册并自动登录成功');
      } else {
        Response::error('注册失败');
      }
    } catch (Exception $e) {
      // 捕获服务层抛出的异常，统一返回错误响应
      Response::error($e->getMessage());
    }
  }

  /**
   * 登录接口（仅邮箱+密码）
   */
  #[Post('/auth/login')]
  public function login()
  {
    try {
      $postData = json_decode(file_get_contents('php://input'), true) ?: $_POST;
      $email = $postData['email'] ?? '';
      $password = $postData['password'] ?? '';

      $authService = new AuthService();
      $user = $authService->login($email, $password);

      if ($user) {
        $_SESSION['user'] = $user;
        Response::success($user, '登录成功');
      } else {
        Response::error('登录失败');
      }
    } catch (Exception $e) {
      Response::error($e->getMessage());
    }
  }

  /**
   * 退出登录接口
   */
  #[Get('/auth/logout')]
  public function logout()
  {
    try {
      // 1. 校验是否已登录
      if (!isset($_SESSION['user'])) {
        Response::success([], '已退出登录');
      }

      // 2. 清空Session所有数据
      $_SESSION = [];

      // 3. 销毁Session ID（触发PHP底层自动清理Redis中的Session数据）
      if (ini_get("session.use_cookies")) {
        // 清除前端的Session Cookie（防止客户端复用）
        $cookieParams = session_get_cookie_params();
        setcookie(
          session_name(),
          '',
          time() - 42000,
          $cookieParams["path"],
          $cookieParams["domain"],
          $cookieParams["secure"],
          $cookieParams["httponly"]
        );
      }

      // 4. 销毁当前Session（关键：PHP会自动删除Redis中对应的Session数据）
      session_destroy();

      Response::success([], '退出登录成功');

    } catch (Exception $e) {
      Response::error('退出登录失败：' . $e->getMessage(), 500);
    }

  }
}