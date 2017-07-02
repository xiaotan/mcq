<?php

namespace app\pet\admin;

use app\admin\controller\Admin;
use app\common\builder\ZBuilder;
use app\pet\model\Order as OrderModel;
use app\pet\model\Business as BusinessModel;
use app\pet\model\BusinessEvaluate as BusinessEvaluateModel;
use think\Validate;
use think\Config;
use think\Db;

/**
 * 仪表盘控制器
 * @package app\pet\admin
 */
class Order extends Admin
{
    /**
     * 首页
     * @return mixed
     */
    public function index()
    {
        // 查询
        $map = $this->getMap();
        $map['is_delete'] = 0;
        //如果为商家登录，则筛选加上商家id
        if(defined('BID')) {
            $map['bid'] = BID;
        }
        // 排序
        $order = $this->getOrder('update_time desc');
        // 数据列表
        $data_list = OrderModel::where($map)->order($order)->paginate();
        $status = [
            '0' => '下单成功',
            '1' => '订单完成',
            '2' => '申请退款',
            '3' => '取消订单',
        ];
        //如果为管理员查看，则增加所属商家
        $column = [['id', 'ID']];
        if(!defined('BID')) {
            $business = BusinessModel::where(array('status'=>1))->column('id,name');
            $column[] = ['bid', '商家', $business];
        }
        $btnType = [
            'class' => 'btn btn-info',
            'title' => '核销码',
            'icon'  => 'fa fa-fw fa-sitemap',
            'href'  => url('order/verify')
        ];
        //基础表单
        $column_base = [ // 批量添加数据列
            ['order_no', '订单号', 'text'],
            ['type', '服务类型', 'text'],
            ['pet', '宠物类别', 'text'],
            ['breed', '宠物品种', 'text'],
            ['age', '宠物年龄', 'text'],
            ['create_time', '下单时间', 'datetime'],
            ['is_pay', '支付状态', ['0'=>'未支付', '1'=>'已支付']],
            ['status', '订单状态', $status],
            ['right_button', '操作', 'btn']
        ];
        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
            ->setSearch(['order_no' => '订单号', 'verify_no' => '核销码']) // 设置搜索框
            ->addColumns(array_merge($column, $column_base))
            ->addRightButtons(['edit']) // 批量添加右侧按钮
            ->addTopButton('custom', $btnType) // 添加顶部按钮
            ->addOrder('id,create_time')
            ->setRowList($data_list) // 设置表格数据
            ->fetch(); // 渲染模板
    }

    /**
     * 编辑
     * @param null $id id
     * @author 蔡伟明 <314013107@qq.com>
     * @return mixed
     */
    public function edit($id = null)
    {
        if ($id === null) $this->error('缺少参数');

        //如果为商家修改
        if(defined('BID')) {
            $data = OrderModel::where(array('id'=>$id, 'bid'=>BID))->find()->toArray();
            if(!$data){
                $this->error('非法操作');
            }
        }
        // 保存数据
        if ($this->request->isPost()) {
            // 表单数据
            $post = $this->request->post();
            if($data['status']==1){
                $this->error('订单已完成, 无法更改');
            }
            if($data['status']==3){
                $this->error('订单已取消, 无法更改');
            }
            $input = array();
            //执行退款操作
            if($data['status']==2 && $post['is_refund']==1){
                $input['is_refund'] = 1;
                //退款在这里做
            }
            //订单完成
            if($data['status']==0 && $data['is_pay']==1 && $post['status']==1){
                $input['status'] = 1;
            }
            if(!$input){
                $this->error('订单状态没有改变');
            }
            //保存数据
            if (OrderModel::update($input)) {
                // 记录行为
                action_log('order_edit', 'pet_order', $id, UID, $data['order_no']);
                $this->success('编辑成功', 'index');
            } else {
                $this->error('编辑失败');
            }
        }

        $info = OrderModel::get($id)->toArray();  
        $info['date'] = date("Y-m-d", strtotime($info['date']));   
        $info = array_merge(json_decode($info['address_data'], true), $info);
        if($info['is_evaluate']==1){
            $evaluate = BusinessEvaluateModel::where(array("oid"=>$info['id']))->find()->toArray();
            $info = array_merge($evaluate, $info);
        }
        $pay_type = ['1'=>'微信支付','2'=>'支付宝支付'];
        $is_pay = ['0'=>'未支付','1'=>'已支付'];
        $is_evaluate = ['0'=>'未评价','1'=>'已评价'];
        $status = ['0'=>'下单成功','1'=>'订单完成','2'=>'订单申请退款','3'=>'取消订单'];
        $info['status_text'] = $status[$info['status']];
        $info['pay_type'] = $pay_type[$info['pay_type']];
        $info['is_pay'] = $is_pay[$info['is_pay']];
        $info['is_evaluate'] = $is_evaluate[$info['is_evaluate']];
        // print_r($info);exit;

        // 显示添加页面
        return ZBuilder::make('form')
            ->setPageTips('如果出现无法添加的情况，可能由于浏览器将本页面当成了广告，请尝试关闭浏览器的广告过滤功能再试。', 'warning')
            ->addGroup([
                '订单信息' => [
                    ['hidden', 'id'],
                    ['static', 'title', '订单标题'],
                    ['static', 'order_no', '订单号'],
                    ['static', 'uname', '下单用户'],
                    ['static', 'bname', '商家名称'],
                    ['static', 'dname', '医生名称'],
                    ['static', 'type', '服务类型'],
                    ['static', 'pet', '宠物类别'],
                    ['static', 'breed', '宠物品种'],
                    ['static', 'age', '宠物年龄'],
                    ['static', 'date', '预约日期'],
                    ['static', 'time', '预约时间'],
                    ['static', 'pay_type', '支付类型'],
                    ['static', 'is_pay', '是否支付'],
                    ['static', 'is_evaluate', '是否评价'],
                    ['static', 'coupon_amount', '优惠金额'],
                    ['static', 'score', '使用积分'],
                    ['static', 'amount', '订单总价(元)'],
                    ['static', 'price', '订单最终应付价(元)'],
                    ['static', 'remark', '备注'],
                    ['select', 'status', '订单状态', '', ['0'=>'下单成功','1'=>'订单完成','2'=>'订单申请退款','3'=>'取消订单']],
                    ['static', 'refund_time', '申请退款时间'],
                    ['static', 'refund_reason', '申请退款理由'],
                    ['select', 'is_refund', '是否同意退款', '', ['0'=>'未同意退款','1'=>'同意退款']],
                ],
                '用户地址' => [
                    ['static', 'name', '用户名'],
                    ['static', 'mobile', '手机号'],
                    ['static', 'address', '省市'],
                    ['static', 'street', '街道'],
                ],
                '用户评价' => [
                    ['static', 'business_rate', '商家服务评分'],
                    ['static', 'doctor_rate', '商家医生水平评分'],
                    ['static', 'evaluate', '评价内容'],
                    ['images', 'imgs', '图片列表'],
                ],
            ])
            ->setFormData($info)
            ->layout(['title' => 6, 'order_no' => 6, 'uname' => 6, 'bname' => 6, 'dname' => 6, 'type' => 6, 'pet' => 6, 'breed' => 6, 'age' => 6, 'date' => 6, 'time' => 6, 'pay_type' => 6, 'is_pay' => 6, 'is_evaluate' => 6, 'coupon_amount' => 6, 'score' => 6, 'amount' => 6, 'price' => 6, 'name' => 6, 'mobile' => 6, 'address' => 6, 'street' => 6, 'business_rate' => 6, 'doctor_rate' => 6, 'refund_time' => 2, 'refund_reason' => 2])
            ->setTrigger('status', '2', 'refund_time,is_refund,refund_reason')
            ->fetch();
    }

    public function verify(){

        if(!defined('BID')) $this->error('您不是商家');

        // 保存数据
        if ($this->request->isPost()) {
            // 表单数据
            $data = $this->request->post();
            $order = OrderModel::where(array("bid"=>BID, "verify_no"=>$data['verify_no']))->find();
            if(!$order){
                $this->error('查询不到相关订单，请确认核销码已填写正确');
            }
            if($order['status']==0 && $order['is_pay']==1){
                //保存数据
                $result = OrderModel::where(array("bid"=>BID, "verify_no"=>$data['verify_no']))->setField('status', 1);
                if ($result) {
                    // 记录行为
                    action_log('order_verify', 'pet_order', $order['id'], UID, $order['title']);
                    $this->success('核销成功', 'index');
                } else {
                    $this->error('核销失败');
                }
            }else{
                $this->error('目前订单状态无法核销或订单已核销');
            }
        }
        // 显示添加页面
        return ZBuilder::make('form')
            ->setPageTips('如果出现无法添加的情况，可能由于浏览器将本页面当成了广告，请尝试关闭浏览器的广告过滤功能再试。', 'warning')
            ->addFormItems([
                ['text', 'verify_no', '核销码'],
            ])
            ->fetch();
    }
}