<?php
// +----------------------------------------------------------------------
// | 海豚PHP框架 [ DolphinPHP ]
// +----------------------------------------------------------------------
// | 版权所有 2016~2017 河源市卓锐科技有限公司 [ http://www.zrthink.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://dolphinphp.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------

namespace app\pet\validate;

use think\Validate;

/**
 * 行为验证器
 * @package app\pet\validate
 * @author 蔡伟明 <314013107@qq.com>
 */
class Business extends Validate
{
    //定义验证规则
    protected $rule = [
        'name|商家名称'   => 'require|unique:business',
        'username|用户名' => 'require|alphaNum|unique:admin_user',
        'nickname|昵称'  => 'require|unique:admin_user',
        'email|邮箱'     => 'email|unique:admin_user',
        'password|密码'  => 'require|length:6,20',
        'mobile|手机号'   => 'regex:^1\d{10}|unique:admin_user',
    ];

    //定义验证提示
    protected $message = [
        // 'name.regex' => '行为标识由字母和下划线组成',
        'username.require' => '请输入用户名',
        'email.require'    => '邮箱不能为空',
        'email.email'      => '邮箱格式不正确',
        'email.unique'     => '该邮箱已存在',
        'password.require' => '密码不能为空',
        'password.length'  => '密码长度6-20位',
        'mobile.regex'     => '手机号不正确',
    ];
}
