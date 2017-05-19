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
class Task extends Validate
{
    //定义验证规则
    protected $rule = [
        'title|任务名称'   => 'require|unique:task',
        'type|任务类型'   => 'require',
        'amount|奖励积分'   => 'require|number',
    ];

    //定义验证提示
    protected $message = [
        'title.require' => '请输入任务名称',
        'type.require' => '请选择任务类型',
        'amount.require' => '请输入奖励积分',
        'amount.number' => '奖励积分必须为数字',
    ];

    protected $scene = [
        'title'  =>  ['title'],
    ];
}
