<?php

namespace app\pet\home;

use app\pet\model\Task as TaskModel;
use app\pet\model\TaskDo as TaskDoModel;
use app\pet\model\Order as OrderModel;
use app\pet\model\Member as MemberModel;
use app\pet\model\Business as BusinessModel;
use app\pet\model\MemberScore as MemberScoreModel;
use app\pet\model\BusinessCoupon as BusinessCouponModel;
use app\pet\model\BusinessDoctor as BusinessDoctorModel;
use app\pet\model\BusinessEvaluate as BusinessEvaluateModel;

/**
 * 前台首页控制器
 * @package app\pet\admin
 */
class Ucenter extends Common
{

    /**
     * 初始化方法
     * @author 蔡伟明 <314013107@qq.com>
     */
    protected function _initialize()
    {
        parent::_initialize();
        // 判断用户是否登录
        $this->MemberModel = new MemberModel;
        $this->member_id = $this->MemberModel->isLogin();
        if(!$this->member_id){
            $this->error('请先授权登录');
        }
    }

    /**
     * 首页
     * @return mixed
     */
    public function index()
    {
        $member = $this->MemberModel->where(array("id"=>$this->member_id))->find()->toArray();
        // print_r($member);exit;
        $this->assign('tab', 4);
        $this->assign('member', $member);
        return $this->fetch(); // 渲染模板
    }

    /**
     * 订单列表 0全部 1待付款 2已付款 3待评价
     * @return mixed
     */
    public function order($type = 0){
        $type = in_array($type, array(1, 2, 3)) ? $type : 0;
        $types = array('0'=>'全部订单', '1'=>'待付款', '2'=>'已付款', '3'=>'待评价');
        $data = $this->getOrderList($type);

        $this->assign('type', $type);
        $this->assign('types', json_encode($types));
        $this->assign('data', json_encode($data));
        return $this->fetch(); // 渲染模板
    }

    // ajax分页搜索订单列表
    public function ajaxGetOrderList(){
        $page = input('get.page/d', 1);
        $type = input('get.type/d', 0);
        $data = $this->getOrderList($type, $page);
        echo $data ? json_encode($data) : 1;
    }

    private function getOrderList($type = 0, $page = 1, $pageSize = 10, $cache = false){
        $order = array();
        // 做session缓存，防止多次查询数据库，需在用户重新定位时清除该缓存
        if(session('order_'.$type.'_'.$page) && $cache){
            $order = session('order_'.$type.'_'.$page);
        }else{
            $map['is_delete'] = 0;
            $map['mid'] = $this->member_id;
            switch ($type) {
                case '1':
                    $map['is_pay'] = 0;
                    break;
                case '2':
                    $map['is_pay'] = 1;
                    break;
                case '3':
                    $map['is_evaluate'] = 0;
                    $map['status'] = 1;
                    break;
            }
            // $data = OrderModel::where($map)->limit(($page-1)*$pageSize,$pageSize)->select();
            $data = OrderModel::where($map)->page($page.",".$pageSize)->order("id desc")->select();
            foreach($data as $k=>$v){
                $order[$k]['id'] = $v['id'];
                $order[$k]['bid'] = $v['bid'];
                $order[$k]['bname'] = $v['bname'];
                $order[$k]['type'] = $v['type'];
                $order[$k]['pet'] = $v['pet'];
                $order[$k]['breed'] = $v['breed'];
                $order[$k]['age'] = $v['age'];
                $order[$k]['order_no'] = $v['order_no'];
                $order[$k]['status'] = $v['status'];
                $order[$k]['is_pay'] = $v['is_pay'];
                $order[$k]['is_refund'] = $v['is_refund'];
                $order[$k]['is_evaluate'] = $v['is_evaluate'];
                $order[$k]['amount'] = $v['amount'];
                $order[$k]['price'] = $v['price'];
                $thumb = BusinessModel::where(array("id"=>$v['bid']))->value("thumb");
                $order[$k]['thumb'] = get_file_path($thumb);
                $order[$k]['burl'] = url("business/index",array('id'=>$v['bid']));
                $order[$k]['ourl'] = url("ucenter/show",array('id'=>$v['id']));
                $order[$k]['purl'] = url("ucenter/payment",array('id'=>$v['id']));
                if(($v['status']==0 && $v['is_pay']==1) || ($v['status']==2 && $v['is_refund']==0)){
                    $order[$k]['rurl'] = url("ucenter/refund",array('id'=>$v['id']));
                }
                if($v['status']==1){
                    $order[$k]['eurl'] = url("ucenter/evaluate",array('id'=>$v['id']));
                }
            }
            session('order_'.$type.'_'.$page, $order);
        }
        return $order;
    }

    /**
     * 订单详情
     * @return mixed
     */
    public function show($id){
        if(!$id){
            $this->error('非法请求');
        }
        $order = OrderModel::where(array("id"=>$id, "mid"=>$this->member_id, "is_delete"=>0))->find()->toArray();
        if(!$order){
            $this->error('订单不存在或已删除');
        }
        $business = BusinessModel::get($order['bid']);
        if($order['coupon_id']){
            $coupon = BusinessCouponModel::get($order['coupon_id']);
            $this->assign('coupon', $coupon);
        }

        $this->assign('order', $order);
        $this->assign('business', $business);
        return $this->fetch(); // 渲染模板
    }

    /**
     * ajax取消订单
     * @return mixed
     */
    public function ajaxCancelOrder($id){
        if($id){
            $order = OrderModel::where(array("id"=>$id, "mid"=>$this->member_id, "is_delete"=>0))->find()->toArray();
            if(!$order){
                $return['code'] = 0;
                $return['info'] = "订单不存在或已删除";
                echo json_encode($return);exit;
            }
            if($order['status']==0 && $order['is_pay']==0){
                $result = OrderModel::where(array("id"=>$id, "mid"=>$this->member_id))->setField('status', 3);
                if($result){
                    $return['code'] = 1;
                    $return['info'] = "订单成功取消";
                    echo json_encode($return);exit;
                }else{
                    $return['code'] = 0;
                    $return['info'] = "订单状态更新失败";
                    echo json_encode($return);exit;
                }
            }else{
                $return['code'] = 0;
                $return['info'] = "目前订单状态不可操作";
                echo json_encode($return);exit;
            }
        }
    }

    /**
     * 订单退款
     * @return mixed
     */
    public function refund($id){
        if(!$id){
            $this->error('非法请求');
        }
        $order = OrderModel::where(array("id"=>$id, "mid"=>$this->member_id, "is_delete"=>0))->find()->toArray();
        if(!$order){
            $this->error('订单不存在或已删除');
        }
        if($order['status']==2 && $order['is_refund']==1){
            $this->error('订单已退款成功');
        }
        $thumb = BusinessModel::where(array("id"=>$order['bid']))->value("thumb");
        $order['thumb'] = get_file_path($thumb);
        $business = BusinessModel::get($order['bid']);

        $this->assign('order', $order);
        $this->assign('business', $business);
        if($order['status']==2){
            return $this->fetch('refunded'); // 渲染模板
        }else{
            return $this->fetch(); // 渲染模板
        }
    }

    /**
     * ajax订单申请退款
     * @return mixed
     */
    public function ajaxRefundOrder(){
        $id = input("get.id", 0, 'intval');
        $reason = input("get.reason", '');
        if(!$id || !$reason){
            $return['code'] = 0;
            $return['info'] = "必填参数不能为空";
            echo json_encode($return);exit;
        }
        $order = OrderModel::where(array("id"=>$id, "mid"=>$this->member_id, "is_delete"=>0))->find()->toArray();
        if(!$order){
            $return['code'] = 0;
            $return['info'] = "订单不存在或已删除";
            echo json_encode($return);exit;
        }
        if($order['status']==0 && $order['is_pay']==1){
            $input['status'] = 2;
            $input['refund_time'] = time();
            $input['refund_reason'] = $reason;
            $result = OrderModel::where(array("id"=>$id, "mid"=>$this->member_id))->update($input);
            if($result){
                $return['code'] = 1;
                $return['info'] = "订单退款申请成功";
                echo json_encode($return);exit;
            }else{
                $return['code'] = 0;
                $return['info'] = "订单状态更新失败";
                echo json_encode($return);exit;
            }
        }else{
            $return['code'] = 0;
            $return['info'] = "目前订单状态不可操作";
            echo json_encode($return);exit;
        }
    }

    /**
     * 评价订单
     * @return mixed
     */
    public function evaluate($id){
        if(!$id){
            $this->error('非法请求');
        }
        $order = OrderModel::where(array("id"=>$id, "mid"=>$this->member_id, "is_delete"=>0))->find()->toArray();
        if(!$order){
            $this->error('订单不存在或已删除');
        }
        if($order['status']==1 && $order['is_evaluate']==1){
            $evaluate = BusinessEvaluateModel::where(array("oid"=>$order['id']))->find();
            $this->assign('evaluate', $evaluate);
        }
        $thumb = BusinessModel::where(array("id"=>$order['bid']))->value("thumb");
        $order['thumb'] = get_file_path($thumb);
        $business = BusinessModel::get($order['bid']);

        $this->assign('order', $order);
        $this->assign('business', $business);
        if($order['status']==1 && $order['is_evaluate']==1){
            return $this->fetch('evaluated'); // 渲染模板
        }else{
            return $this->fetch(); // 渲染模板
        }
    }

    /**
     * ajax评价订单
     * @return mixed
     */
    public function ajaxEvaluateOrder(){
        $data = $this->request->post();
        if($data['id']){
            $order = OrderModel::where(array("id"=>$data['id'], "mid"=>$this->member_id))->find()->toArray();
            if(!$order){
                $return['code'] = 0;
                $return['info'] = "订单不存在或已删除";
                echo json_encode($return);exit;
            }
            if($order['status']==1 && $order['is_evaluate']==0){
                if(empty($data['b_stars']) || empty($data['comment']) || ($order['did'] && empty($data['d_stars']))){
                    $return['code'] = 0;
                    $return['info'] = "必填参数不能为空";
                    echo json_encode($return);exit;
                }else{
                    if($data['imgs'] && count(explode(',', $data['imgs']))>8){
                        $return['code'] = 0;
                        $return['info'] = "最多上传8张图片";
                        echo json_encode($return);exit;
                    }
                    //记录评价
                    $evaluate['uid'] = $this->member_id;
                    $evaluate['oid'] = $order['id'];
                    $evaluate['bid'] = $order['bid'];
                    $evaluate['did'] = $order['did'];
                    $evaluate['business_rate'] = intval($data['b_stars'])<6 && intval($data['b_stars'])>0 ? intval($data['b_stars']) : 0;
                    $evaluate['doctor_rate'] = intval($data['d_stars'])<6 && intval($data['d_stars'])>0 ? intval($data['d_stars']) : 0;
                    $evaluate['evaluate'] = $data['comment'];
                    $evaluate['imgs'] = $data['imgs'] ? trim($data['imgs'],',') : '';
                    $result = BusinessEvaluateModel::create($evaluate);
                    if($result){
                        $input['is_evaluate'] = 1;
                        $input['evaluate_time'] = time();
                        OrderModel::where(array("id"=>$order['id']))->update($input);

                        //更新商家、医生评分平均值
                        $b_ava = get_business_ava($order['bid']);
                        BusinessModel::where(array("id"=>$order['bid']))->setField('score', $b_ava);
                        if($data['d_stars']){
                            $d_ava = get_doctor_ava($order['did']);
                            BusinessDoctorModel::where(array("id"=>$order['did']))->setField('score', $d_ava);
                        }

                        $return['code'] = 1;
                        $return['info'] = "订单评价成功";
                        echo json_encode($return);exit;
                    }else{
                        $return['code'] = 0;
                        $return['info'] = "订单状态更新失败";
                        echo json_encode($return);exit;
                    }
                }
            }else{
                $return['code'] = 0;
                $return['info'] = "目前订单状态不可操作";
                echo json_encode($return);exit;
            }
        }
    }

    /**
     * 我的账户
     * @return mixed
     */
    public function account()
    {
        $member = $this->MemberModel->where(array("id"=>$this->member_id))->find()->toArray();
        $score = MemberScoreModel::where("mid=".$this->member_id." and create_time>".mktime(0,0,0,date("m"),date("d"),date("Y"))." and create_time<".mktime(23,59,59,date("m"),date("d"),date("Y")))->sum("amount");
        $tasks = TaskModel::where(array("status"=>1, "begin_time"=>['<', time()], "end_time"=>['>', time()]))->select();
        //今天是否签到
        $qiandao = TaskDoModel::where("task_id=2 and mid=".$this->member_id." and create_time>".mktime(0,0,0,date("m"),date("d"),date("Y"))." and create_time<".mktime(23,59,59,date("m"),date("d"),date("Y")))->value("id");
        // print_r($qiandao);exit;

        $this->assign('qiandao', $qiandao);
        $this->assign('tasks', $tasks);
        $this->assign('member', $member);
        $this->assign('score', $score ? $score: 0);
        return $this->fetch(); // 渲染模板
    }

    /**
     * ajax领取任务奖励
     * @return mixed
     */
    public function ajaxGetScore(){
        $task_id = input("get.task_id/d", '');
        if($task_id){
            $task = TaskModel::where(array("id"=>$task_id))->find()->toArray();
            if(!$task){
                $return['code'] = 0;
                $return['info'] = "任务不存在";
                echo json_encode($return);exit;
            }
            // 每日签到
            if($task_id==2){
                //查看今天是否签到
                $task_do = TaskDoModel::where("task_id=2 and mid=".$this->member_id." and create_time>".mktime(0,0,0,date("m"),date("d"),date("Y"))." and create_time<".mktime(23,59,59,date("m"),date("d"),date("Y")))->value("id");
                if($task_do){
                    $return['code'] = 0;
                    $return['info'] = "今天已签到";
                    echo json_encode($return);exit;
                }else{
                    $this->handleScore($task_id, $task['amount']);

                    $return['code'] = 1;
                    $return['info'] = "签到成功";
                    echo json_encode($return);exit;
                }
            }else{
                //根据任务类型分别处理 1唯一任务 2每日任务
                if($task['type']==1){
                    $task_do = TaskDoModel::where(array("task_id"=>$task_id, "mid"=>$this->member_id))->find();
                }else{
                    $task_do = TaskDoModel::where("task_id=".$task_id." and mid=".$this->member_id." and create_time>".mktime(0,0,0,date("m"),date("d"),date("Y"))." and create_time<".mktime(23,59,59,date("m"),date("d"),date("Y")))->value("id");
                }
                if($task_do){
                    $this->handleScore($task_id, $task_do['amount']);
                }else{
                    $return['code'] = 0;
                    $return['info'] = "任务未完成, 不可领取";
                    echo json_encode($return);exit;
                }
            }
        }
    }

    /**
     * 进行用户签到操作，同时更新相关表（用户表、用户积分表、任务进行表）
     * @return mixed
     */
    private function handleScore($task_id, $amount){
        if($task_id==2){
            $taskDo['mid'] = $this->member_id;
            $taskDo['task_id'] = $task_id;
            $taskDo['status'] = 1;
            $taskDo['is_get'] = 1;
            $taskDo['amount'] = $amount;
            TaskDoModel::create($taskDo);
        }else{
            TaskDoModel::where(array("mid"=>$this->member_id, "task_id"=>$task_id))->setField("is_get", 1);
        }

        $memberScore['mid'] = $this->member_id;
        $memberScore['type'] = 2;
        $memberScore['task_id'] = $task_id;
        $memberScore['amount'] = $amount;
        MemberScoreModel::create($memberScore);

        $this->MemberModel->where(array("id"=>$this->member_id))->setInc("score", $amount);
    }

    /**
     * 在线支付
     * @return mixed
     */
    public function payment($id){
        if(!$id){
            $this->error('非法请求');
        }
        $order = OrderModel::where(array("id"=>$id, "mid"=>$this->member_id, "is_delete"=>0))->find()->toArray();
        if(!$order){
            $this->error('订单不存在或已删除');
        }
        $business = BusinessModel::get($order['bid']);

        $this->assign('order', $order);
        $this->assign('business', $business);
        return $this->fetch(); // 渲染模板
    }

    /**
     * 我的地址
     * @return mixed
     */
    public function address(){
        
    }

    /**
     * 我的收藏
     * @return mixed
     */
    public function collect(){
        
    }

    /**
     * ajax上传图片
     * @return mixed
     */
    public function ajaxUploadImg(){
        $result = parent::saveFile('images');
        return $result;
    }

    /**
     * ajax上传文件
     * @return mixed
     */
    public function ajaxUploadFile(){
        parent::saveFile();
    }
}