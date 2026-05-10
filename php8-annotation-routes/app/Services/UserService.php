<?php

namespace App\Services;

class UserService
{
    // 获取用户列表业务
    public function getList()
    {
        // 模拟数据库数据
        return [
            ['id' => 1, 'name' => 'PHP全栈', 'age' => 22],
            ['id' => 2, 'name' => '开发工程师', 'age' => 24]
        ];
    }

    // 根据ID获取单个用户
    public function findById(int $id)
    {
        if ($id === 1) {
            return ['id' => 1, 'name' => 'PHP全栈'];
        }
        return null;
    }
}