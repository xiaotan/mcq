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
    ];

    //定义验证提示
    protected $message = [
        // 'name.regex' => '行为标识由字母和下划线组成',
    ];
}
