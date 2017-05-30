<?php

namespace app\pet\home;

use app\pet\model\Order as OrderModel;
use app\cms\model\Slider as SliderModel;
use app\pet\model\Member as MemberModel;
use app\pet\model\Business as BusinessModel;
use app\pet\model\BusinessTime as BusinessTimeModel;
use app\pet\model\MemberCollect as MemberCollectModel;
use app\pet\model\MemberAddress as MemberAddressModel;
use app\pet\model\MemberScoreUse as MemberScoreUseModel;
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
        //查询用户是否关注该商家
        $is_collect = MemberCollectModel::where(array("bid"=>$business->id, "mid"=>$member_id))->value("id");
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
                $current_type = $v['id'];
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
                            $service[$v['id']]['pets'][$v1['id']]['breeds'][$v2['id']]['id'] = $v2['id'];
                            $service[$v['id']]['pets'][$v1['id']]['breeds'][$v2['id']]['name'] = $v2['name'];
                            $service[$v['id']]['pets'][$v1['id']]['breeds'][$v2['id']]['icon'] = get_file_path($v2['icon']);
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
                    if(BusinessTimeModel::where(array("bid"=>$v['bid'], "tid"=>$k1, "date"=>$date))->value("id")){
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
                    if(BusinessTimeModel::where(array("bid"=>$v['bid'], "tid"=>$k1, "date"=>$date))->value("id")){
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
                    if(BusinessTimeModel::where(array("bid"=>$v['bid'], "tid"=>$k1, "date"=>$date))->value("id")){
                        $status = 0;
                    }
                    // 设置时间段状态
                    $service[$v['id']]['times'][$date]['night'][$k1]['status'] = $status;
                }
            }
        }
        // print_r($member);exit;
        $this->assign('tab', 2);
        $this->assign('id', $id);
        $this->assign('slider', $slider);
        $this->assign('is_collect', $is_collect ? 1 : 0);
        $this->assign('address', json_encode($address));
        $this->assign('current_type', $current_type);
        $this->assign('member', json_encode($member));
        $this->assign('business', $business); // 商家基本信息
        $this->assign('service', json_encode($service));
        return $this->fetch(); // 渲染模板
    }

    /**
     * ajax商家服务下单
     * @return mixed
     */
    public function ajaxDoOrder(){
        $order = array();
        $now = time();
        // 判断用户是否登录
        $MemberModel = new MemberModel;
        if(!$order['mid'] = $MemberModel->isLogin()){
            $return['code'] = 0;
            $return['info'] = "请先授权登录";
            echo json_encode($return);exit;
        }
        // 获取用户信息
        $member = $MemberModel->where(array("id"=>$order['mid']))->find();
        $order['uname'] = $member->nickname;
        // 接收参数
        $data = $this->request->post();
        // 商家id不存在
        $order['bid'] = isset($data['business_id']) ? intval($data['business_id']) : '';
        if(!$order['bid']){
            $return['code'] = 0;
            $return['info'] = "该商家不存在";
            echo json_encode($return);exit;
        }
        // 获取商店信息
        $business = BusinessModel::where(array("id"=>$order['bid'], "status"=>1))->find();
        // 查询不到商店，则为已关停
        if(!$business){
            $return['code'] = 0;
            $return['info'] = "商店已关停";
            echo json_encode($return);exit;
        }
        $order['bname'] = $business->name;
        // 服务不存在
        $service_id = isset($data['service_id']) ? intval($data['service_id']) : '';
        if(!$service_id){
            $return['code'] = 0;
            $return['info'] = "请选择服务类型";
            echo json_encode($return);exit;
        }
        // 获取服务信息
        $business_service = BusinessServiceModel::where(array("id"=>$service_id, "status"=>1))->find();
        // 查询不到商店，则为已关停
        if(!$business_service){
            $return['code'] = 0;
            $return['info'] = "商店服务已下架";
            echo json_encode($return);exit;
        }
        $order['type'] = BusinessServiceConfigModel::where(array("id"=>$business_service->type))->value("name");
        // 宠物不存在
        $pet_id = isset($data['pet_id']) ? intval($data['pet_id']) : '';
        if(!$pet_id || !in_array($pet_id, explode(',', $business_service->pet))){
            $return['code'] = 0;
            $return['info'] = "请选择宠物";
            echo json_encode($return);exit;
        }
        $order['pet'] = BusinessServiceConfigModel::where(array("id"=>$pet_id))->value("name");
        // 宠物品种不存在
        $breed_id = isset($data['breed_id']) ? intval($data['breed_id']) : '';
        if(!$breed_id || !in_array($breed_id, explode(',', $business_service->breed))){
            $return['code'] = 0;
            $return['info'] = "请选择宠物品种";
            echo json_encode($return);exit;
        }
        $order['breed'] = BusinessServiceConfigModel::where(array("id"=>$breed_id))->value("name");
        // 医生id
        $doctor_id = isset($data['doctor_id']) ? intval($data['doctor_id']) : '';
        if($doctor_id && in_array($doctor_id, explode(',', $business_service->doctor))){
            $order['did'] = $doctor_id;
            $order['dname'] = BusinessDoctorModel::where(array("id"=>$order['did']))->value("name");
        }
        // 宠物年龄
        $age = config("pet_age");
        $age_id = isset($data['age_id']) ? intval($data['age_id']) : '';
        if(!$age_id || !isset($age[$age_id])){
            $return['code'] = 0;
            $return['info'] = "请选择宠物年龄";
            echo json_encode($return);exit;
        }
        $order['age'] = $age[$age_id];
        // 日期
        if(empty($data['date'])){
            $return['code'] = 0;
            $return['info'] = "请选择日期";
            echo json_encode($return);exit;
        }
        $order['date'] = $data['date'];
        // 时间段
        $times = get_times();
        $time_id = isset($data['time_id']) ? intval($data['time_id']) : '';
        if(!$time_id || !in_array($time_id, explode(',', $business_service->time))){
            $return['code'] = 0;
            $return['info'] = "请选择时间段";
            echo json_encode($return);exit;
        }
        // 如果已有用户预约该时间段，则排除
        if(BusinessTimeModel::where(array("bid"=>$order['bid'], "tid"=>$time_id, "date"=>$order['date']))->value("id")){
            $return['code'] = 0;
            $return['info'] = "选择时间段不可用";
            echo json_encode($return);exit;
        }
        $order['time'] = $times[$time_id];
        // 地址
        $address_id = isset($data['address_id']) ? intval($data['address_id']) : '';
        if(!$address_id){
            $return['code'] = 0;
            $return['info'] = "请选择地址";
            echo json_encode($return);exit;
        }
        $address = MemberAddressModel::where(array("id"=>$address_id, "mid"=>$order['mid']))->find();
        if($address){
            $order['address_id'] = $address_id;
            $order['address_data'] = json_encode($address->toArray());
        }else{
            $return['code'] = 0;
            $return['info'] = "非法地址";
            echo json_encode($return);exit;
        }
        // 支付类型
        $pay_type = isset($data['payment']) ? intval($data['payment']) : '';
        if(!$pay_type){
            $return['code'] = 0;
            $return['info'] = "请选择支付类型";
            echo json_encode($return);exit;
        }
        $order['pay_type'] = $pay_type;
        // 优惠id
        $coupon_id = isset($data['coupon_id']) ? intval($data['coupon_id']) : '';
        if($coupon_id && in_array($coupon_id, explode(',', $business_service->coupon))){
            $coupon = BusinessCouponModel::where(array("id"=>$coupon_id, "bid"=>$business->id, "status"=>1,'begin_time'=>['<',$now],'end_time'=>['>',$now]))->find();
            if($coupon){
                // 如果是唯一优惠，则判断用户是否已经用过优惠
                if($coupon['unique']){
                    $order_id = OrderModel::where(array("mid"=>$order['mid'], "coupon_id"=>$coupon['id']))->value('id');
                    if($order_id){
                        $return['code'] = 0;
                        $return['info'] = "您已经使用过该优惠";
                        echo json_encode($return);exit;
                    }
                }
                $order['coupon_id'] = $coupon_id;
                $order['coupon_amount'] = $coupon->amount;
            }else{
                $return['code'] = 0;
                $return['info'] = "优惠已过期";
                echo json_encode($return);exit;
            }
        }
        // 积分
        $score = isset($data['score']) ? intval($data['score']) : '';
        if($score){
            if($member->score<$score){
                $return['code'] = 0;
                $return['info'] = "可用积分不足";
                echo json_encode($return);exit;
            }else{
                $order['score'] = $score;
            }
        }else{
            $order['score'] = 0;
        }
        // 备注
        $order['remark'] = isset($data['remark']) ? $data['remark'] : '';
        // 订单号
        $order['order_no'] = get_order_no();
        // 支付总额
        $order['amount'] = $business_service->price;
        $reduce = 0;
        if(isset($order['coupon_amount'])){
            $reduce = $reduce + $order['coupon_amount'];
        }
        if($order['score']){
            $reduce = $reduce + $order['score'];
        }
        $order['price'] = $order['amount'] - $reduce;
        $order['title'] = $order['bname'].'-'.$order['type'].'-'.$order['pet'].'-'.$order['breed'];
        // 订单入库
        $result = OrderModel::create($order);
        if($result){
            // 添加时间段记录
            $bTime['oid'] = $result['id'];
            $bTime['bid'] = $business->id;
            $bTime['tid'] = $time_id;
            $bTime['date'] = $order['date'];
            BusinessTimeModel::create($bTime);
            // 消耗积分记录，同时更新用户总积分
            if($order['score']){
                $scoreUse['mid'] = $order['mid'];
                $scoreUse['oid'] = $result['id'];
                $scoreUse['score'] = $order['score'];
                MemberScoreUseModel::create($scoreUse);
                // 更新用户总积分
                $MemberModel::where(array("id"=>$order['mid']))->setField('score', $member->score-$score);
            }
            //返回相关信息
            $return['code'] = 1;
            $return['info'] = $result;
            echo json_encode($return);exit;
        }
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
     * ajax收藏商家
     * @return mixed
     */
    public function ajaxCollect(){
        if($bid = input("get.bid", 0, "intval")){
            // 判断用户是否登录
            $MemberModel = new MemberModel;
            if($member_id = $MemberModel->isLogin()){
                //判断是否已经收藏
                $is_collect = MemberCollectModel::where(array("bid"=>$bid, "mid"=>$member_id))->value("id");
                if($is_collect){
                    $result = MemberCollectModel::where(array("id"=>$is_collect))->delete();
                    if($result){
                        $return['code'] = 2;
                        $return['info'] = "操作成功";
                        echo json_encode($return);exit;
                    }else{
                        $return['code'] = 0;
                        $return['info'] = "操作失败";
                        echo json_encode($return);exit;
                    }
                }else{
                    $input['bid'] = $bid;
                    $input['mid'] = $member_id;
                    $result = MemberCollectModel::create($input);
                    if($result){
                        $return['code'] = 1;
                        $return['info'] = "操作成功";
                        echo json_encode($return);exit;
                    }else{
                        $return['code'] = 0;
                        $return['info'] = "操作失败";
                        echo json_encode($return);exit;
                    }
                }
            }
        }
        	
    }
}