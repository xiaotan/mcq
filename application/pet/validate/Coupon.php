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
class Coupon extends Validate
{
    //定义验证规则
    protected $rule = [
        'title|优惠标题'   => 'require',
        'amount|优惠金额'   => 'require',
        'type|优惠类型'   => 'require',
        'begin_time|优惠开始时间' => 'require',
        'end_time|优惠结束时间'  => 'require',
    ];

    //定义验证提示
    protected $message = [
        'title.require' => '请输入优惠标题',
        'amount.require' => '请输入优惠金额',
        'type.require' => '请选择优惠类型',
        'begin_time.require' => '请选择优惠开始时间',
        'end_time.require' => '请选择优惠结束时间',
    ];

    protected $scene = [
        /*'edit'  =>  ['name', 'tel', 'thumb'],
        'name'  =>  ['name'],*/
    ];
}
