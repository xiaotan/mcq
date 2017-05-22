<?php

namespace app\pet\home;

use app\pet\model\Order as OrderModel;
use app\cms\model\Slider as SliderModel;
use app\pet\model\Member as MemberModel;
use app\pet\model\Business as BusinessModel;
use app\pet\model\BusinessTime as BusinessTimeModel;
use app\pet\model\MemberAddress as MemberAddressModel;
use app\pet\model\BusinessDoctor as BusinessDoctorModel;
use app\pet\model\BusinessCoupon as BusinessCouponModel;
use app\pet\model\BusinessService as BusinessServiceModel;
use app\pet\model\BusinessServiceConfig as BusinessServiceConfigModel;
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
    public function index($id)
    {
        // 商家id不能为空
        if ($id === null){
            $this->error('缺少参数');
        }
        // 判断用户是否登录
        $MemberModel = new MemberModel;
        if(!$member_id = $MemberModel->isLogin()){
            $this->error('请先授权登录');
        }
        // 获取用户信息
        $member = $MemberModel->where(array("id"=>$member_id))->find()->toArray();
        // 获取用户的地址
        $address = MemberAddressModel::where(array("mid"=>$member_id))->select();
        // 获取商店信息
        $business = BusinessModel::where(array("id"=>$id, "status"=>1))->find();
        // 查询不到商店，则为已关停
        if(!$business){
            $this->error('商店已关停');
        }
        // 获取滚动图，如果商家没有上传则获取全站的
        if($business->banner){
            $slider = explode(',', $business->banner);
        }else{
            $slider = SliderModel::where(array("typeid"=>3, "status"=>1))->column('cover');
        }
        // 获取商家服务
        $service = array();
        $business_service = BusinessServiceModel::where(array("bid"=>$id, "status"=>1))->select();
        if(!$business_service){
            $this->error('该商家还没有添加服务');
        }
        foreach($business_service as $k=>$v){
            // 默认显示第一个服务的信息
            if($k==0){
                $current = $v['id'];
            }
            // 赋值服务基本信息
            $service[$v['id']]['id'] = $v['id'];
            $service[$v['id']]['type'] = $v['type'];
            $service[$v['id']]['name'] = $v['name'];
            $service[$v['id']]['price'] = $v['price'];
            $icon = BusinessServiceConfigModel::where(array("id"=>$v['type']))->value("icon");
            $service[$v['id']]['icon'] = get_file_path($icon);
            // 获取宠物类型、宠物品种
            $pets = BusinessServiceConfigModel::where(array("id"=>['in',$v['pet']], "status"=>1))->select();
            if($pets){
                foreach($pets as $k1=>$v1){
                    // 赋值宠物类型基本信息
                    $service[$v['id']]['pets'][$v1['id']]['id'] = $v1['id'];
                    $service[$v['id']]['pets'][$v1['id']]['name'] = $v1['name'];
                    $service[$v['id']]['pets'][$v1['id']]['icon'] = get_file_path($v1['icon']);
                    $service[$v['id']]['pets'][$v1['id']]['pet_icon'] = get_file_path($v1['pet_icon']); // 弹出窗趴在顶部的宠物图片
                    // 获取宠物类型响应的宠物品种列表
                    $breeds = BusinessServiceConfigModel::where(array("pid"=>$v1['id'], "id"=>['in',$v['breed']], "status"=>1))->select();
                    if($breeds){
                        foreach($breeds as $k2=>$v2){
                            $service[$v['id']]['pets'][$v1['id']]['breed'][$v2['id']]['id'] = $v2['id'];
                            $service[$v['id']]['pets'][$v1['id']]['breed'][$v2['id']]['name'] = $v2['name'];
                            $service[$v['id']]['pets'][$v1['id']]['breed'][$v2['id']]['icon'] = get_file_path($v2['icon']);
                        }
                    }
                }
            }
            // 获取医生列表
            $doctors = BusinessDoctorModel::where(array("id"=>['in',$v['doctor']], "status"=>1))->select();
            if($doctors){
                foreach($doctors as $k1=>$v1){
                    // 赋值医生基本信息
                    $service[$v['id']]['doctors'][$v1['id']]['id'] = $v1['id'];
                    $service[$v['id']]['doctors'][$v1['id']]['name'] = $v1['name'];
                    $service[$v['id']]['doctors'][$v1['id']]['position'] = $v1['position'];
                    $service[$v['id']]['doctors'][$v1['id']]['intro'] = $v1['intro'];
                    $service[$v['id']]['doctors'][$v1['id']]['major'] = $v1['major'];
                    $service[$v['id']]['doctors'][$v1['id']]['is_auth'] = $v1['is_auth'];
                    $service[$v['id']]['doctors'][$v1['id']]['score'] = $v1['score'];
                    $service[$v['id']]['doctors'][$v1['id']]['avatar'] = get_file_path($v1['avatar']);
                }
            }
            // 获取可用的优惠
            $coupon = BusinessCouponModel::where(array("id"=>['in',$v['coupon']], "status"=>1,'begin_time'=>['<',time()],'end_time'=>['>',time()]))->select();
            if($coupon){
                foreach($coupon as $k1=>$v1){
                    // 如果是唯一优惠，则判断用户是否已经用过优惠
                    if($v1['unique']){
                        $order_id = OrderModel::where(array("mid"=>$member_id, "coupon_id"=>$v1['id']))->value('id');
                        if($order_id){
                            continue;
                        }
                    }
                    // 如果是满减，则需要判断服务的金额是否满足
                    if($v1['type']==1 && $v1['limit_amount']>$v['price']){
                        continue;
                    }
                    // 赋值医生基本信息
                    $service[$v['id']]['coupons'][$v1['id']]['id'] = $v1['id'];
                    $service[$v['id']]['coupons'][$v1['id']]['type'] = $v1['type'];
                    $service[$v['id']]['coupons'][$v1['id']]['title'] = $v1['title'];
                    $service[$v['id']]['coupons'][$v1['id']]['amount'] = $v1['amount'];
                    $service[$v['id']]['coupons'][$v1['id']]['limit_amount'] = $v1['limit_amount'];
                }
            }

            // 获取时间段,在这里进行排除预约过的时间段(默认获取最近一周)
            for($i=0;$i<7;$i++){
                // 生成时间日期 格式 Ymd->20170520
                $date = date("Ymd", time()+$i*86400);
                // 生成显示为用户的日期名称
                if($i==0){
                    $date_name = "今天"; 
                }elseif($i==1){
                    $date_name = "明天"; 
                }else{
                    $date_name = date("m/d", time()+$i*86400);
                }
                // 赋值日期基本信息
                $service[$v['id']]['times'][$date]['info']['date'] = $date;
                $service[$v['id']]['times'][$date]['info']['name'] = $date_name;
                // 上午时间段
                $forenoon = config("forenoon");
                foreach($forenoon as $k1=>$v1){
                    // 赋值时间段基本信息
                    $service[$v['id']]['times'][$date]['forenoon'][$k1]['id'] = $k1;
                    $service[$v['id']]['times'][$date]['forenoon'][$k1]['name'] = $v1;
                    // 默认时间段可用
                    $status = 1;
                    // 如果商家设置不可用，则排除
                    if(!in_array($k1, explode(',', $v['time']))){
                        $status = 0;
                    }
                    // 如果已有用户预约该时间段，则排除
                    if(BusinessTimeModel::where(array("bid"=>$v['bid'], "tid"=>$k1, "time"=>$date))->value("id")){
                        $status = 0;
                    }
                    // 设置时间段状态
                    $service[$v['id']]['times'][$date]['forenoon'][$k1]['status'] = $status;
                }
                // 下午时间段
                $afternoon = config("afternoon");
                foreach($afternoon as $k1=>$v1){
                    // 赋值时间段基本信息
                    $service[$v['id']]['times'][$date]['afternoon'][$k1]['id'] = $k1;
                    $service[$v['id']]['times'][$date]['afternoon'][$k1]['name'] = $v1;
                    // 默认时间段可用
                    $status = 1;
                    // 如果商家设置不可用，则排除
                    if(!in_array($k1, explode(',', $v['time']))){
                        $status = 0;
                    }
                    // 如果已有用户预约该时间段，则排除
                    if(BusinessTimeModel::where(array("bid"=>$v['bid'], "tid"=>$k1, "time"=>$date))->value("id")){
                        $status = 0;
                    }
                    // 设置时间段状态
                    $service[$v['id']]['times'][$date]['afternoon'][$k1]['status'] = $status;
                }
                // 晚上时间段
                $night = config("night");
                foreach($night as $k1=>$v1){
                    // 赋值时间段基本信息
                    $service[$v['id']]['times'][$date]['night'][$k1]['id'] = $k1;
                    $service[$v['id']]['times'][$date]['night'][$k1]['name'] = $v1;
                    // 默认时间段可用
                    $status = 1;
                    // 如果商家设置不可用，则排除
                    if(!in_array($k1, explode(',', $v['time']))){
                        $status = 0;
                    }
                    // 如果已有用户预约该时间段，则排除
                    if(BusinessTimeModel::where(array("bid"=>$v['bid'], "tid"=>$k1, "time"=>$date))->value("id")){
                        $status = 0;
                    }
                    // 设置时间段状态
                    $service[$v['id']]['times'][$date]['night'][$k1]['status'] = $status;
                }
            }
        }
        // print_r($member);exit;
        $this->assign('slider', $slider);
        $this->assign('address', json_encode($address));
        $this->assign('current_type', $current);
        $this->assign('member', json_encode($member));
        $this->assign('business', $business); // 商家基本信息
        $this->assign('ages', json_encode(config("pet_age"))); // 宠物年龄段
        $this->assign('current_date', date("Ymd", time())); //默认显示的日期
        $this->assign('service', json_encode($service));
        $this->assign('doctor', isset($service[$current]['doctors']) ? json_encode($service[$current]['doctors']) : '{}');
        $this->assign('coupon', isset($service[$current]['coupons']) ? json_encode($service[$current]['coupons']) : '{}');
        $this->assign('times', json_encode($service[$current]['times'][date("Ymd", time())]));
        $this->assign('dates', json_encode($service[$current]['times']));
        return $this->fetch(); // 渲染模板
    }

    /**
     * ajax添加用户地址
     * @return mixed
     */
    public function ajaxAddAddress(){
        // 判断用户是否登录
        $MemberModel = new MemberModel;
        if(!$member_id = $MemberModel->isLogin()){
            $return['code'] = 0;
            $return['info'] = "请先授权登录";
            echo json_encode($return);exit;
        }
        $data = $this->request->post();
        // 验证
        $result = $this->validate($data, 'MemberAddress');
        // 验证失败 输出错误信息
        if(true !== $result){
            $return['code'] = 0;
            $return['info'] = $result;
            echo json_encode($return);exit;
        }
        $data['mid'] = $member_id;
        //保存数据
        $address = MemberAddressModel::create($data);
        if($address){
            $address = MemberAddressModel::where(array("mid"=>$member_id))->select();
            $return['code'] = 1;
            $return['info'] = $address;
            echo json_encode($return);exit;
        }else{
            $return['code'] = 0;
            $return['info'] = "添加地址失败";
            echo json_encode($return);exit;
        }
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