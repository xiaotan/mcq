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

namespace app\pet\home;

/**
 * 前台首页控制器
 * @package app\pet\admin
 */
class Forum extends Common
{
    /**
     * 首页
     * @return mixed
     */
    public function index()
    {
        return $this->fetch(); // 渲染模板
    }

    /**
     * 我的圈首页
     * @return mixed
     */
    public function me()
    {
        return $this->fetch(); // 渲染模板
    }
}