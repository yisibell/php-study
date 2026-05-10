<?php

namespace App\Utils;

class Response
{
    // 成功返回
    public static function success($data = [], string $msg = '操作成功')
    {
        header('Content-Type: application/json;charset=utf-8');
        echo json_encode([
            'code' => 200,
            'msg'  => $msg,
            'data' => $data
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // 失败返回
    public static function error(string $msg = '操作失败', int $code = 400)
    {
        header('Content-Type: application/json;charset=utf-8');
        echo json_encode([
            'code' => $code,
            'msg'  => $msg,
            'data' => []
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
}