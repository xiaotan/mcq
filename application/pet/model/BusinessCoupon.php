<?php

namespace app\pet\model;

use think\Model as ThinkModel;

/**
 * 商家优惠模型
 * @package app\pet\model
 */
class BusinessCoupon extends ThinkModel
{
    // 设置当前模型对应的完整数据表名称
    protected $table = '__PET_BUSINESS_COUPON__';

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    // 定义修改器
    public function setBeginTimeAttr($value)
    {
        return $value != '' ? strtotime($value) : 0;
    }
    public function setEndTimeAttr($value)
    {
        return $value != '' ? strtotime($value) : 0;
    }
    public function getBeginTimeAttr($value)
    {
        return $value != 0 ? date('Y-m-d', $value) : '';
    }
    public function getEndTimeAttr($value)
    {
        return $value != 0 ? date('Y-m-d', $value) : '';
    }
}