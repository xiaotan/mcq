<?php

namespace app\pet\validate;

use think\Validate;

/**
 * 行为验证器
 * @package app\pet\validate
 */
class BusinessServiceConfig extends Validate
{
    //定义验证规则
    protected $rule = [
        'name|配置名称'   => 'require|unique:pet_business_service_config',
        'icon|配置图标'   => 'require',
        'config_id|服务配置'   => 'require',
    ];

    //定义验证提示
    protected $message = [
        'name.require' => '配置名称不能为空',
        'name.unique' => '该配置名称已存在',
        'icon.require' => '服务配置不能为空',
        'config_id.require' => '配置图标不能为空',
    ];
}
