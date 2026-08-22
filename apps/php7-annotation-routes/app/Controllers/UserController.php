<?php

namespace App\Controllers;

class UserController
{
    /**
     * 用户列表
     * @Get("/user/list")
     */
    public function list()
    {
        echo json_encode([
            'code' => 200,
            'msg' => '成功',
            'data' => ['用户1', '用户2']
        ]);
    }

    /**
     * 用户信息
     * @Get("/user/info")
     */
    public function info()
    {
        echo json_encode([
            'code' => 200,
            'msg' => '获取成功',
            'data' => ['id' => 1, 'name' => '张三']
        ]);
    }

    /**
     * 添加用户
     * @Post("/user/add")
     */
    public function add()
    {
        echo json_encode([
            'code' => 200,
            'msg' => '添加成功'
        ]);
    }
}