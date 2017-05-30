<?php

namespace app\pet\model;

use think\Model as ThinkModel;

/**
 * @package app\pet\model
 */
class MemberFollow extends ThinkModel
{
    // 设置当前模型对应的完整数据表名称
    protected $table = '__PET_MEMBER_FOLLOW__';

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;
}