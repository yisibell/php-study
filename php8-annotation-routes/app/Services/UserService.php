<?php

namespace App\Services;

use \App\Utils\Db;
use Exception;

class UserService
{
  /**
   * 获取用户列表
   */
  public function getList()
  {
    // 先判断用户是否登录（session中是否有用户信息）
    if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
      throw new Exception('未登录'); // 替换Response::error
    }

    // 从数据库查询用户列表（这里只查询id, email，updated_at 隐藏密码等敏感信息）
    $users = Db::fetchAll('SELECT id, email, created_at FROM users');

    return $users;
  }

  /**
   * 根据ID获取单个用户
   */
  public function findById(int $id)
  {
    // 先判断用户是否登录（session中是否有用户信息）
    if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
      throw new Exception('未登录'); // 替换Response::error 
    }

    // 从数据库查询用户信息（这里只查询id, email，updated_at 隐藏密码等敏感信息）
    $user = Db::fetch('SELECT id, email, created_at FROM users WHERE id = ?', [
      $id
    ]);

    if (!$user) {
      throw new Exception('用户不存在'); // 替换Response::error
    }

    return $user;
  }

  /**
   * 获取当前用户信息
   */
  public function getUserInfo()
  {
    // 先判断用户是否登录（session中是否有用户信息）
    if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
      throw new Exception('未登录'); // 替换Response::error
    }
    // 从 session 获取用户id
    $userId = $_SESSION['user']['id'];

    // 从数据库查询用户信息（这里只查询id, email，updated_at 隐藏密码等敏感信息）
    $user = Db::fetch('SELECT id, email, created_at FROM users WHERE id = ?', [
      $userId
    ]);

    if (!$user) {
      throw new Exception('用户不存在'); // 替换Response::error
    }

    return $user;

  }

  /**
   * 获取用户列表（分页）
   */
  public function getListByPage(int $page = 1, int $pageSize = 10)
  {
    // 先判断用户是否登录（session中是否有用户信息）
    if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
      throw new Exception('未登录'); // 替换Response::error
    }

    $data = DB::page('SELECT id, email, created_at FROM users', [], $page, $pageSize);

    return $data;
  }

}