<?php

namespace app\pet\model;

use think\Model as ThinkModel;

/**
 * 商家服务模型
 * @package app\pet\model
 */
class BusinessService extends ThinkModel
{
    // 设置当前模型对应的完整数据表名称
    protected $table = '__PET_BUSINESS_SERVICE__';

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;
}