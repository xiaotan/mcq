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

namespace app\pet\admin;

use app\admin\controller\Admin;
use app\common\builder\ZBuilder;
use app\pet\model\Coupon as CouponModel;
// use app\user\model\User as UserModel;
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
        // 数据列表
        $data_list = CouponModel::where($map)->order($order)->paginate();

        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
            ->setSearch(['name' => '商家名称']) // 设置搜索框
            ->addColumns([ // 批量添加数据列
                ['id', 'ID'],
                ['title', '优惠名称', 'text.edit'],
                ['type', '优惠类型', 'select', config('coupon_type')],
                ['amount', '优惠金额', 'text'],
                ['unique', '优惠限制', 'switch'],
                ['status', '状态', 'switch'],
                ['create_time', '发布时间', 'datetime'],
                ['right_button', '操作', 'btn']
            ])
            ->addTopButtons('add,enable,disable,delete') // 批量添加顶部按钮
            ->addRightButtons(['edit', 'delete' => ['data-tips' => '删除后无法恢复。']]) // 批量添加右侧按钮
            ->addOrder('id,name,create_time')
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
            $data['status'] = isset($data['status']) ? 1 : 0;
            $data['unique'] = isset($data['unique']) ? 1 : 0;
            $data['begin_time'] = $data['begin_time'] ? strtotime($data['begin_time']) : 0;
            $data['end_time'] = $data['end_time'] ? strtotime($data['end_time']) : 0;
            // 验证
            $result = $this->validate($data, 'Coupon');
            // 验证失败 输出错误信息
            if(true !== $result) return $this->error($result);

            //保存数据
            $coupon = CouponModel::create($data);
            
            if ($coupon) {
                // 记录行为
                action_log('coupon_add', 'coupon', $coupon['id'], UID, $data['title']);
                $this->success('新增成功', 'index');
            } else {
                $this->error('新增失败');
            }
        }

        // 显示添加页面
        return ZBuilder::make('form')
            ->setPageTips('如果出现无法添加的情况，可能由于浏览器将本页面当成了广告，请尝试关闭浏览器的广告过滤功能再试。', 'warning')
            ->addFormItems([
                ['text', 'title', '优惠名称'],
                ['select', 'type', '优惠类型', '', config('coupon_type')],
                ['number', 'amount', '优惠金额'],
                ['number', 'limit_amount', '满减需要填写，满xx金额可以减'],
                ['switch', 'unique', '优惠限制', '优惠是否一个用户只能用一次'],
                ['datetime', 'begin_time', '优惠开始时间', ''],
                ['datetime', 'end_time', '优惠结束时间', ''],
                ['switch', 'status', '状态', '', 1],
            ])
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

        // 保存数据
        if ($this->request->isPost()) {
            // 表单数据
            $data = $this->request->post();
            $data['status'] = isset($data['status']) ? 1 : 0;
            $data['unique'] = isset($data['unique']) ? 1 : 0;
            $data['begin_time'] = $data['begin_time'] ? strtotime($data['begin_time']) : 0;
            $data['end_time'] = $data['end_time'] ? strtotime($data['end_time']) : 0;
            // 验证
            $result = $this->validate($data, 'Coupon');
            // 验证失败 输出错误信息
            if(true !== $result) return $this->error($result);

            //保存数据
            if (CouponModel::update($data)) {
                // 记录行为
                action_log('coupon_edit', 'coupon', $id, UID, $data['title']);
                $this->success('编辑成功', 'index');
            } else {
                $this->error('编辑失败');
            }
        }

        $info = CouponModel::get($id);
        $info['begin_time'] = $info['begin_time'] ? date('Y:m:d H:i:s', $info['begin_time']) : '';
        $info['end_time'] = $info['end_time'] ? date('Y:m:d H:i:s', $info['end_time']) : '';
        // 显示添加页面
        return ZBuilder::make('form')
            ->setPageTips('如果出现无法添加的情况，可能由于浏览器将本页面当成了广告，请尝试关闭浏览器的广告过滤功能再试。', 'warning')
            ->addFormItems([
                ['hidden', 'id'],
                ['text', 'title', '优惠名称'],
                ['select', 'type', '优惠类型', '', config('coupon_type')],
                ['number', 'amount', '优惠金额'],
                ['number', 'limit_amount', '满减需要填写，满xx金额可以减'],
                ['switch', 'unique', '优惠限制', '优惠是否一个用户只能用一次'],
                ['datetime', 'begin_time', '优惠开始时间', ''],
                ['datetime', 'end_time', '优惠结束时间', ''],
                ['switch', 'status', '状态', '', 1],
            ])
            ->setFormData($info)
            ->setTrigger('type', 1, 'limit_amount')
            ->fetch();
    }
}