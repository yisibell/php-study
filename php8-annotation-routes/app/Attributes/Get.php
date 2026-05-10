<?php
namespace App\Attributes;

// 标记可以注解在方法上
#[\Attribute(\Attribute::TARGET_METHOD)]
class Get
{
    public function __construct(public string $path)
    {
    }
}