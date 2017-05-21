<?php

namespace app\pet\model;

use think\Model as ThinkModel;

/**
 * 医生模型
 * @package app\pet\model
 */
class BusinessDoctor extends ThinkModel
{
    // 设置当前模型对应的完整数据表名称
    protected $table = '__PET_BUSINESS_DOCTOR__';

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;
}