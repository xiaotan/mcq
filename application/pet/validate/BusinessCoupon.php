<?php

namespace app\pet\validate;

use think\Validate;

/**
 * 行为验证器
 * @package app\pet\validate
 */
class BusinessCoupon extends Validate
{
    //定义验证规则
    protected $rule = [
        'bid|所属商家id'   => 'require',
        'title|优惠标题'   => 'require',
        'amount|优惠金额'   => 'require',
        'limit_amount|满减所需金额'   => 'require',
        'type|优惠类型'   => 'require',
        'begin_time|优惠开始时间' => 'require',
        'end_time|优惠结束时间'  => 'require',
    ];

    //定义验证提示
    protected $message = [
        'bid.require' => '请选择所属商家',
        'title.require' => '请输入优惠标题',
        'amount.require' => '请输入优惠金额',
        'limit_amount.require' => '请输入满减所需金额',
        'type.require' => '请选择优惠类型',
        'begin_time.require' => '请选择优惠开始时间',
        'end_time.require' => '请选择优惠结束时间',
    ];

    protected $scene = [
        'type'  =>  ['bid', 'title', 'amount', 'type', 'begin_time', 'end_time'],
        'title'  =>  ['title'],
    ];
}
