<?php

namespace app\pet\validate;

use think\Validate;

/**
 * 行为验证器
 * @package app\pet\validate
 */
class BusinessService extends Validate
{
    //定义验证规则
    protected $rule = [
        'type|服务项目'   => 'require',
        'time|服务时间段'   => 'require',
        'pet|宠物类别'   => 'require',
        'breed|宠物品种'   => 'require',
        'price|服务价格'   => 'require',
    ];

    //定义验证提示
    protected $message = [
        'type.require' => '请选择服务项目',
        'time.require' => '请选择服务时间段',
        'pet.require' => '请选择宠物类别',
        'breed.require' => '请选择宠物品种',
        'price.require' => '请输入服务价格',
    ];

    protected $scene = [
        /*'edit'  =>  ['name', 'tel', 'thumb'],
        'name'  =>  ['name'],*/
    ];
}
