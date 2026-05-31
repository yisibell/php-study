<?php

namespace App\Utils;

class Response
{
  /**
   * 成功返回
   * @param mixed $data 返回数据
   * @param string $msg 返回消息
   * @return void
   */
  public static function success($data = [], string $msg = '操作成功')
  {
    header('Content-Type: application/json;charset=utf-8');
    echo json_encode([
      'code' => 0,
      'msg' => $msg,
      'data' => $data
    ], JSON_UNESCAPED_UNICODE);
    exit;
  }

  /**
   * 错误返回
   * @param string $msg 错误消息
   * @param int $code 错误码
   * @param mixed $data 额外数据
   * @return void
   */
  public static function error(string $msg = '操作失败', int $code = 400, $data = [])
  {
    header('Content-Type: application/json;charset=utf-8');
    echo json_encode([
      'code' => $code,
      'msg' => $msg,
      'data' => $data
    ], JSON_UNESCAPED_UNICODE);
    exit;
  }
}