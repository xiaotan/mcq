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

use app\pet\model\Business as BusinessModel;
use app\cms\model\Slider as SliderModel;
use think\Validate;
use think\Config;
use think\Db;

/**
 * 前台首页控制器
 * @package app\pet\admin
 */
class Business extends Common
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
     * 退款
     * @return mixed
     */
    public function refund(){
    	return $this->fetch(); // 渲染模板
    }

	/**
     * 退款详情
     * @return mixed
     */
    public function refunded(){
    	return $this->fetch(); // 渲染模板
    }

	/**
     * 订单评价
     * @return mixed
     */
    public function comment(){
    	return $this->fetch(); // 渲染模板
    }

	/**
     * 订单详情
     * @return mixed
     */
    public function commented(){
    	return $this->fetch(); // 渲染模板
    }

	/**
     * 订单支付
     * @return mixed
     */
    public function payment(){
    	return $this->fetch(); // 渲染模板
    }

	/**
     * 订单支付
     * @return mixed
     */
    public function order(){
    	return $this->fetch(); // 渲染模板
    }

	/**
     * 订单详情
     * @return mixed
     */
    public function show(){
    	return $this->fetch(); // 渲染模板
    }
}