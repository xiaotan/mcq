<?php

namespace app\pet\admin;

use app\admin\controller\Admin;
use app\common\builder\ZBuilder;
use app\pet\model\Business as BusinessModel;
use app\pet\model\BusinessCoupon as BusinessCouponModel;
use think\Validate;
use think\Config;
use think\Db;

/**
 * 仪表盘控制器
 * @package app\pet\admin
 */
class BusinessCoupon extends Admin
{
    /**
     * 首页
     * @author 蔡伟明 <314013107@qq.com>
     * @return mixed
     */
    public function index()
    {
        // 查询
        $map = $this->getMap();
        // 排序
        $order = $this->getOrder('update_time desc');
        //如果为商家登录，则筛选加上商家id
        if(defined('BID')) {
            $map['bid'] = BID;
        }
        // 数据列表
        $data_list = BusinessCouponModel::where($map)->order($order)->paginate();

        //如果为管理员查看，则增加所属商家
        $column = [['id', 'ID']];
        if(!defined('BID')) {
            $business = BusinessModel::where(array('status'=>1))->column('id,name');
            $column[] = ['bid', '所属商家', $business];
        }
        //基础表单
        $column_base = [
            ['title', '优惠名称', 'text.edit'],
            ['type', '优惠类型', config('coupon_type')],
            ['amount', '优惠金额', 'text'],
            ['unique', '优惠限制', 'switch'],
            ['status', '状态', 'switch'],
            ['create_time', '发布时间', 'datetime'],
            ['right_button', '操作', 'btn']
        ];

        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
            ->setSearch(['title' => '优惠名称']) // 设置搜索框
            ->addColumns(array_merge($column, $column_base))
            ->addTopButtons('add,enable,disable,delete') // 批量添加顶部按钮
            ->addRightButtons(['edit', 'delete' => ['data-tips' => '删除后无法恢复。']]) // 批量添加右侧按钮
            ->addOrder('id,amount,create_time')
            ->setRowList($data_list) // 设置表格数据
            ->fetch(); // 渲染模板
    }

    /**
     * 新增
     * @author 蔡伟明 <314013107@qq.com>
     * @return mixed
     */
    public function add()
    {
        // 保存数据
        if ($this->request->isPost()) {
            // 表单数据
            $data = $this->request->post();
            //判断是否商家添加
            if(defined('BID')) {
                $data['bid'] = BID;
            }
            //判断是否满减优惠类型
            if($data['type']==1){
                // 验证
                $result = $this->validate($data, 'BusinessCoupon');
            }else{
                // 验证
                $result = $this->validate($data, 'BusinessCoupon.type');
            }
            // 验证失败 输出错误信息
            if(true !== $result) return $this->error($result);

            //保存数据
            $business_coupon = BusinessCouponModel::create($data);
            
            if ($business_coupon) {
                // 记录行为
                action_log('business_coupon_add', 'pet_business_coupon', $business_coupon['id'], UID, $data['title']);
                $this->success('新增成功', 'index');
            } else {
                $this->error('新增失败');
            }
        }

        //如果为管理员添加，则增加商家列表选择
        $forms = array();
        if(!defined('BID')) {
            $business = BusinessModel::where(array('status'=>1))->column('id,name');
            $forms = [['select', 'bid', '所属商家', '', $business]];
        }
        //基础表单
        $forms_base = [
            ['text', 'title', '优惠名称'],
            ['select', 'type', '优惠类型', '', config('coupon_type')],
            ['number', 'amount', '优惠金额'],
            ['number', 'limit_amount', '满减需要填写，满xx金额可以减'],
            ['radio', 'unique', '优惠限制', '优惠是否一个用户只能用一次', ['否', '是'], 1],
            ['datetime', 'begin_time', '优惠开始时间', ''],
            ['datetime', 'end_time', '优惠结束时间', ''],
            ['radio', 'status', '状态', '', ['禁用', '启用'], 1]
        ];

        // 显示添加页面
        return ZBuilder::make('form')
            ->setPageTips('如果出现无法添加的情况，可能由于浏览器将本页面当成了广告，请尝试关闭浏览器的广告过滤功能再试。', 'warning')
            ->addFormItems(array_merge($forms, $forms_base))
            ->setTrigger('type', 1, 'limit_amount')
            ->fetch();
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

        //如果为商家修改，则判断该医生是否属于商家
        if(defined('BID')) {
            $data = BusinessCouponModel::where(array('id'=>$id, 'bid'=>BID))->value('id');
            if(!$data){
                $this->error('非法操作');
            }
        }
        // 保存数据
        if ($this->request->isPost()) {
            // 表单数据
            $data = $this->request->post();
            //判断是否商家添加
            if(defined('BID')) {
                $data['bid'] = BID;
            }
            //判断是否满减优惠类型
            if($data['type']==1){
                // 验证
                $result = $this->validate($data, 'BusinessCoupon');
            }else{
                // 验证
                $result = $this->validate($data, 'BusinessCoupon.type');
            }
            // 验证失败 输出错误信息
            if(true !== $result) return $this->error($result);
            //保存数据
            if (BusinessCouponModel::update($data)) {
                // 记录行为
                action_log('business_coupon_edit', 'pet_business_coupon', $id, UID, $data['title']);
                $this->success('编辑成功', 'index');
            } else {
                $this->error('编辑失败');
            }
        }

        //如果为管理员添加，则增加商家列表选择
        $forms = array();
        if(!defined('BID')) {
            $business = BusinessModel::where(array('status'=>1))->column('id,name');
            $forms = [['select', 'bid', '所属商家', '', $business]];
        }
        //基础表单
        $forms_base = [
            ['hidden', 'id'],
            ['text', 'title', '优惠名称'],
            ['select', 'type', '优惠类型', '', config('coupon_type')],
            ['number', 'amount', '优惠金额'],
            ['number', 'limit_amount', '满减需要填写，满xx金额可以减'],
            ['radio', 'unique', '优惠限制', '优惠是否一个用户只能用一次', ['否', '是'], 1],
            ['datetime', 'begin_time', '优惠开始时间', ''],
            ['datetime', 'end_time', '优惠结束时间', ''],
            ['radio', 'status', '状态', '', ['禁用', '启用'], 1]
        ];
        $info = BusinessCouponModel::get($id);
        // 显示添加页面
        return ZBuilder::make('form')
            ->setPageTips('如果出现无法添加的情况，可能由于浏览器将本页面当成了广告，请尝试关闭浏览器的广告过滤功能再试。', 'warning')
            ->addFormItems(array_merge($forms, $forms_base))
            ->setTrigger('type', 1, 'limit_amount')
            ->setFormData($info)
            ->fetch();
    }

    /**
     * 删除广告
     * @param array $record 行为日志
     * @author 蔡伟明 <314013107@qq.com>
     * @return mixed
     */
    public function delete($record = [])
    {
        return $this->setStatus('delete');
    }

    /**
     * 启用广告
     * @param array $record 行为日志
     * @author 蔡伟明 <314013107@qq.com>
     * @return mixed
     */
    public function enable($record = [])
    {
        return $this->setStatus('enable');
    }

    /**
     * 禁用广告
     * @param array $record 行为日志
     * @author 蔡伟明 <314013107@qq.com>
     * @return mixed
     */
    public function disable($record = [])
    {
        return $this->setStatus('disable');
    }

    /**
     * 设置广告状态：删除、禁用、启用
     * @param string $type 类型：delete/enable/disable
     * @param array $record
     * @author 蔡伟明 <314013107@qq.com>
     * @return mixed
     */
    public function setStatus($type = '', $record = [])
    {
        $ids         = $this->request->isPost() ? input('post.ids/a') : input('param.ids');
        $coupon_name = BusinessCouponModel::where('id', 'in', $ids)->column('title');
        return parent::setStatus($type, ['business_coupon_'.$type, 'pet_business_coupon', 0, UID, implode('、', $coupon_name)]);
    }

    /**
     * 快速编辑
     * @param array $record 行为日志
     * @author 蔡伟明 <314013107@qq.com>
     * @return mixed
     */
    public function quickEdit($record = [])
    {
        $id      = input('post.pk', '');
        $field   = input('post.name', '');
        $value   = input('post.value', '');
        $coupon  = BusinessCouponModel::where('id', $id)->value($field);
        $details = '字段(' . $field . ')，原值(' . $coupon . ')，新值：(' . $value . ')';
        return parent::quickEdit(['business_coupon_edit', 'pet_business_coupon', $id, UID, $details]);
    }
}