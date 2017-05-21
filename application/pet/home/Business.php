<?php

namespace app\pet\home;

use app\cms\model\Slider as SliderModel;
use app\pet\model\Business as BusinessModel;
use app\pet\model\BusinessTime as BusinessTimeModel;
use app\pet\model\BusinessDoctor as BusinessDoctorModel;
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
        if ($id === null) $this->error('缺少参数');
        //获取商店信息
        $business = BusinessModel::where(array("id"=>$id, "status"=>1))->find();
        //获取滚动图，如果商家没有上传则获取全站的
        if($business->banner){
            $slider = explode(',', $business->banner);
        }else{
            $slider = SliderModel::where(array("typeid"=>1, "status"=>1))->column('cover');
        }
        //获取商家服务
        $service = array();
        $business_service = BusinessServiceModel::where(array("bid"=>$id, "status"=>1))->select();
        foreach($business_service as $k=>$v){
            if($k==0){
                $current = $v['id'];
            }
            $service[$v['id']]['id'] = $v['id'];
            $service[$v['id']]['type'] = $v['type'];
            $service[$v['id']]['name'] = $v['name'];
            $service[$v['id']]['price'] = $v['price'];
            $icon = BusinessServiceConfigModel::where(array("id"=>$v['type']))->value("icon");
            $service[$v['id']]['icon'] = get_file_path($icon);
            //获取宠物类型、宠物品种
            $pets = BusinessServiceConfigModel::where(array("id"=>['in',$v['pet']], "status"=>1))->select();
            foreach($pets as $k1=>$v1){
                $service[$v['id']]['pets'][$v1['id']]['id'] = $v1['id'];
                $service[$v['id']]['pets'][$v1['id']]['name'] = $v1['name'];
                $service[$v['id']]['pets'][$v1['id']]['icon'] = get_file_path($v1['icon']);
                $service[$v['id']]['pets'][$v1['id']]['pet_icon'] = get_file_path($v1['pet_icon']);
                $breeds = BusinessServiceConfigModel::where(array("pid"=>$v1['id'], "id"=>['in',$v['breed']], "status"=>1))->select();
                foreach($breeds as $k2=>$v2){
                    $service[$v['id']]['pets'][$v1['id']]['breed'][$v2['id']]['id'] = $v2['id'];
                    $service[$v['id']]['pets'][$v1['id']]['breed'][$v2['id']]['name'] = $v2['name'];
                    $service[$v['id']]['pets'][$v1['id']]['breed'][$v2['id']]['icon'] = get_file_path($v2['icon']);
                }
            }
            //获取医生列表
            $doctors = BusinessDoctorModel::where(array("id"=>['in',$v['doctor']], "status"=>1))->select();
            foreach($doctors as $k1=>$v1){
                $service[$v['id']]['doctors'][$v1['id']]['id'] = $v1['id'];
                $service[$v['id']]['doctors'][$v1['id']]['name'] = $v1['name'];
                $service[$v['id']]['doctors'][$v1['id']]['position'] = $v1['position'];
                $service[$v['id']]['doctors'][$v1['id']]['intro'] = $v1['intro'];
                $service[$v['id']]['doctors'][$v1['id']]['major'] = $v1['major'];
                $service[$v['id']]['doctors'][$v1['id']]['is_auth'] = $v1['is_auth'];
                $service[$v['id']]['doctors'][$v1['id']]['score'] = $v1['score'];
                $service[$v['id']]['doctors'][$v1['id']]['avatar'] = get_file_path($v1['avatar']);
            }
            //获取时间段,在这里进行排除预约过的时间段
            for($i=0;$i<7;$i++){
                $date = date("Ymd", time()+$i*86400);
                if($i==0){
                   $date_name = "今天"; 
                }elseif($i==1){
                    $date_name = "明天"; 
                }else{
                    $date_name = date("m/d", time()+$i*86400);
                }
                $service[$v['id']]['times'][$date]['info']['date'] = $date;
                $service[$v['id']]['times'][$date]['info']['name'] = $date_name;
                // 上午
                $forenoon = config("forenoon");
                foreach($forenoon as $k1=>$v1){
                    $service[$v['id']]['times'][$date]['forenoon'][$k1]['id'] = $k1;
                    $service[$v['id']]['times'][$date]['forenoon'][$k1]['name'] = $v1;
                    $status = 1;
                    if(!in_array($k1, explode(',', $v['time']))){
                        $status = 0;
                    }
                    if(BusinessTimeModel::where(array("bid"=>$v['bid'], "tid"=>$k1, "time"=>$date))->value("id")){
                        $status = 0;
                    }
                    $service[$v['id']]['times'][$date]['forenoon'][$k1]['status'] = $status;
                }
                // 下午
                $afternoon = config("afternoon");
                foreach($afternoon as $k1=>$v1){
                    $service[$v['id']]['times'][$date]['afternoon'][$k1]['id'] = $k1;
                    $service[$v['id']]['times'][$date]['afternoon'][$k1]['name'] = $v1;
                    $status = 1;
                    if(!in_array($k1, explode(',', $v['time']))){
                        $status = 0;
                    }
                    if(BusinessTimeModel::where(array("bid"=>$v['bid'], "tid"=>$k1, "time"=>$date))->value("id")){
                        $status = 0;
                    }
                    $service[$v['id']]['times'][$date]['afternoon'][$k1]['status'] = $status;
                }
                // 晚上
                $night = config("night");
                foreach($night as $k1=>$v1){
                    $service[$v['id']]['times'][$date]['night'][$k1]['id'] = $k1;
                    $service[$v['id']]['times'][$date]['night'][$k1]['name'] = $v1;
                    $status = 1;
                    if(!in_array($k1, explode(',', $v['time']))){
                        $status = 0;
                    }
                    if(BusinessTimeModel::where(array("bid"=>$v['bid'], "tid"=>$k1, "time"=>$date))->value("id")){
                        $status = 0;
                    }
                    $service[$v['id']]['times'][$date]['night'][$k1]['status'] = $status;
                }
            }
        }

        // print_r($service);exit;
        $this->assign('slider', $slider);
        $this->assign('current', $current);
        $this->assign('business', $business);
        $this->assign('ages', json_encode(config("pet_age")));
        $this->assign('current_date', date("Ymd", time()));
        $this->assign('service', json_encode($service));
        $this->assign('doctor', json_encode($service[$current]['doctors']));
        $this->assign('times', json_encode($service[$current]['times'][date("Ymd", time())]));
        $this->assign('dates', json_encode($service[$current]['times']));
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