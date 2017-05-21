<?php

namespace app\pet\validate;

use think\Validate;

/**
 * 行为验证器
 * @package app\pet\validate
 */
class Business extends Validate
{
    //定义验证规则
    protected $rule = [
        'name|商家名称'   => 'require|unique:pet_business',
        'tel|商家电话'   => 'require',
        'thumb|缩略图'   => 'require',
        'map|商家位置信息'   => 'require',
        'map_address|商家位置信息'   => 'require',

        'username|用户名' => 'require|alphaNum|unique:admin_user',
        'nickname|昵称'  => 'require|unique:admin_user',
        'email|邮箱'     => 'email|unique:admin_user',
        'password|密码'  => 'require|length:6,20',
        'mobile|手机号'   => 'regex:^1\d{10}|unique:admin_user',
    ];

    //定义验证提示
    protected $message = [
        'name.require' => '请输入商家名称',
        'name.unique' => '该商家名称已存在',
        'tel.require' => '请输入商家电话',
        'thumb.require' => '请上传缩略图',
        'map.require' => '请定位商家位置信息',

        'username.require' => '请输入用户名',
        'username.unique' => '该用户名已存在',
        'username.alphaNum' => '用户名只能为字母和数字',
        'nickname.require' => '请输入昵称',
        'nickname.unique' => '该昵称已存在',
        'email.email'      => '邮箱格式不正确',
        'email.unique'     => '该邮箱已存在',
        'password.require' => '密码不能为空',
        'password.length'  => '密码长度6-20位',
        'mobile.regex'     => '手机号不正确',
    ];

    protected $scene = [
        'add'   =>  ['name', 'username', 'nickname', 'email', 'password', 'mobile'],
        'edit'  =>  ['name'],
        'my'    =>  ['name', 'tel', 'thumb', 'map'],
    ];
}
