<?php
namespace App\Services;

use App\Utils\Db;
// 新增：自定义异常类（需先创建，或用PHP内置异常）
use Exception;

class AuthService
{
  // 注册（仅邮箱+密码）
  public function register(array $userData): bool
  {
    // 1. 校验参数：仅校验邮箱和密码
    if (empty($userData['email']) || empty($userData['password'])) {
      throw new Exception('邮箱/密码不能为空'); // 替换Response::error
    }

    // 2. 检查邮箱是否已存在
    $user = Db::fetch('SELECT id FROM users WHERE email = ?', [
      $userData['email']
    ]);
    if ($user) {
      throw new Exception('邮箱已存在'); // 替换Response::error
    }

    // 3. 密码加密（PHP8推荐password_hash）
    $passwordHash = password_hash($userData['password'], PASSWORD_DEFAULT);

    // 4. 插入数据：仅插入邮箱和密码
    $sql = "INSERT INTO users (email, password) VALUES (?, ?)";
    $row = Db::exec($sql, [
      $userData['email'],
      $passwordHash
    ]);

    return $row > 0;
  }

  // 登录（仅邮箱+密码）
  public function login(string $email, string $password): ?array
  {
    // 1. 校验参数
    if (empty($email) || empty($password)) {
      throw new Exception('邮箱/密码不能为空'); // 替换Response::error
    }

    // 2. 根据邮箱查询用户
    $user = Db::fetch('SELECT id, email, password FROM users WHERE email = ?', [
      $email
    ]);
    if (!$user) {
      throw new Exception('用户不存在'); // 替换Response::error
    }

    // 3. 验证密码
    if (!password_verify($password, $user['password'])) {
      throw new Exception('密码错误'); // 替换Response::error
    }

    // 4. 隐藏密码，返回用户信息
    unset($user['password']);
    return $user;
  }
}