<?php

namespace app\pet\validate;

use think\Validate;

/**
 * 行为验证器
 * @package app\pet\validate
 */
class MemberAddress extends Validate
{
    //定义验证规则
    protected $rule = [
        'name|姓名' => 'require',
        'mobile|手机号' => 'require|regex:^1\d{10}',
        'sex|性别' => 'require',
        'address|地址' => 'require',
    ];

    //定义验证提示
    protected $message = [
        'name.require' => '请输入姓名',
        'mobile.require' => '请输入手机号',
        'mobile.regex'     => '手机号不正确',
        'sex.require' => '请选择性别',
        'address.require' => '请输入地址',
    ];

    //定义验证场景
    protected $scene = [
        /*//更新
        'update'  =>  ['email', 'password' => 'length:6,20', 'mobile'],
        //登录
        'signin'  =>  ['username' => 'require', 'password' => 'require'],*/
    ];
}
