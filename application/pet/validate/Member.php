<?php

namespace app\pet\validate;

use think\Validate;

/**
 * 行为验证器
 * @package app\pet\validate
 */
class Member extends Validate
{
    //定义验证规则
    protected $rule = [
        'username|用户名' => 'require|alphaNum|unique:pet_member',
        'nickname|昵称'  => 'require|unique:pet_member',
        'email|邮箱'     => 'email|unique:pet_member',
        'password|密码'  => 'require|length:6,20',
        'mobile|手机号'   => 'regex:^1\d{10}|unique:pet_member',
    ];

    //定义验证提示
    protected $message = [
        'username.require' => '请输入用户名',
        'email.require'    => '邮箱不能为空',
        'email.email'      => '邮箱格式不正确',
        'email.unique'     => '该邮箱已存在',
        'password.require' => '密码不能为空',
        'password.length'  => '密码长度6-20位',
        'mobile.regex'     => '手机号不正确',
    ];

    //定义验证场景
    protected $scene = [
        /*//更新
        'update'  =>  ['email', 'password' => 'length:6,20', 'mobile'],
        //登录
        'signin'  =>  ['username' => 'require', 'password' => 'require'],*/
    ];
}
