<?php

namespace app\pet\validate;

use think\Validate;

/**
 * 行为验证器
 * @package app\pet\validate
 */
class BusinessDoctor extends Validate
{
    //定义验证规则
    protected $rule = [
        'bid|所属商家id'   => 'require',
        'name|医生姓名'   => 'require',
        'position|医生职位'   => 'require',
        'major|医生主修'   => 'require',
        'avatar|医生头像'   => 'require',
    ];

    //定义验证提示
    protected $message = [
        'bid.require' => '请选择所属商家',
        'name.require' => '请输入医生姓名',
        'position.require' => '请输入医生职位',
        'major.require' => '请输入医生主修',
        'avatar.require' => '请上传医生头像',
    ];
    
}
