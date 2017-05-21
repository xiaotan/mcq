<?php

namespace app\pet\validate;

use think\Validate;

/**
 * 行为验证器
 * @package app\pet\validate
 */
class Task extends Validate
{
    //定义验证规则
    protected $rule = [
        'title|任务名称'   => 'require|unique:pet_task',
        'type|任务类型'   => 'require',
        'amount|奖励积分'   => 'require|number',
        'begin_time|任务开始时间'   => 'require',
        'end_time|任务结束时间'   => 'require',
    ];

    //定义验证提示
    protected $message = [
        'title.require' => '请输入任务名称',
        'title.unique' => '该任务名称已存在',
        'type.require' => '请选择任务类型',
        'amount.require' => '请输入奖励积分',
        'amount.number' => '奖励积分必须为数字',
        'begin_time.require' => '请选择任务开始时间',
        'end_time.require' => '请选择任务结束时间',
    ];

    protected $scene = [
        'type'   =>  ['title','type','amount'],
        'title'  =>  ['title'],
    ];
}
