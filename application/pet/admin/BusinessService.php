<?php

namespace app\pet\admin;

use app\admin\controller\Admin;
use app\common\builder\ZBuilder;
use app\pet\model\Business as BusinessModel;
use app\pet\model\BusinessDoctor as BusinessDoctorModel;
use app\pet\model\BusinessCoupon as BusinessCouponModel;
use app\pet\model\BusinessService as BusinessServiceModel;
use app\pet\model\BusinessServiceConfig as BusinessServiceConfigModel;
use think\Validate;
use think\Config;
use think\Db;

/**
 * 仪表盘控制器
 * @package app\pet\admin
 */
class BusinessService extends Admin
{
    /**
     * 首页
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
        $data_list = BusinessServiceModel::where($map)->order($order)->paginate();

        $btnType = [
            'class' => 'btn btn-info',
            'title' => '服务配置',
            'icon'  => 'fa fa-fw fa-sitemap',
            'href'  => url('business_service_config/index')
        ];

        //如果为管理员查看，则增加所属商家
        $column = [['id', 'ID']];
        if(!defined('BID')) {
            $business = BusinessModel::where(array('status'=>1))->column('id,name');
            $column[] = ['bid', '所属商家', $business];
        }

        //基础表单
        $column_base = [ // 批量添加数据列
            ['name', '服务项目', 'text'],
            ['price', '服务价格', 'text'],
            ['create_time', '发布时间', 'datetime'],
            ['status', '状态', 'switch'],
            ['right_button', '操作', 'btn']
        ];

        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
            ->setSearch(['name' => '服务名称']) // 设置搜索框
            ->addColumns(array_merge($column, $column_base))
            ->addTopButtons('add,enable,disable,delete') // 批量添加顶部按钮
            ->addTopButton('custom', $btnType) // 添加顶部按钮
            ->addRightButtons(['edit', 'delete' => ['data-tips' => '删除后无法恢复。']]) // 批量添加右侧按钮
            ->addOrder('id,create_time')
            ->setRowList($data_list) // 设置表格数据
            ->fetch(); // 渲染模板
    }

    /**
     * 新增
     * @return mixed
     */
    public function add()
    {
        //判断是否商家添加
        if(!defined('BID')) {
            $this->error("您不是商家账号，无法添加服务！");
        }

        $type_list = BusinessServiceConfigModel::where(array('status'=>1, 'config_id'=> 1))->column('id,name');
        $pet_list = BusinessServiceConfigModel::where(array('status'=>1, 'config_id'=> 2))->column('id,name');
        $breed_list = BusinessServiceConfigModel::where(array('status'=>1, 'config_id'=> 3))->column('id,name');

        // 保存数据
        if ($this->request->isPost()) {
            // 表单数据
            $data = $this->request->post();
            // 商家id
            $data['bid'] = BID;
            // 验证
            $result = $this->validate($data, 'BusinessService');
            // 验证失败 输出错误信息
            if(true !== $result) return $this->error($result);
            //处理数据
            $data['pet'] = implode(',',$data['pet']);
            $data['breed'] = implode(',',$data['breed']);
            $data['time'] = implode(',',$data['time']);
            $data['coupon'] = isset($data['coupon']) ? implode(',',$data['coupon']) : '';
            $data['doctor'] = isset($data['doctor']) ? implode(',',$data['doctor']) : '';
            $data['name'] = $type_list[$data['type']];
            //保存数据
            $business_service = BusinessServiceModel::create($data);
            if($business_service){
                // 记录行为
                action_log('business_service_add', 'pet_business_service', $business_service['id'], UID, $data['name']);
                $this->success('新增成功', 'index');
            } else {
                $this->error('新增失败');
            }
        }

        $js_str = '';
        foreach($pet_list as $k=>$v){
            $item = BusinessServiceConfigModel::where(array('status'=>1, 'config_id'=> 3, 'pid'=>$k))->column('id,name');
            $item_str = '';
            if($item){
                foreach($item as $kk=>$vv){
                    $item_str .= "#breed".$kk.",";
                }
                $js_str .= '$("#pet'.$k.'").change(function(){
                    if($("#pet'.$k.'").is(":checked")){
                        $("'.trim($item_str,',').'").prop("checked","checked");
                    }else{
                        $("'.trim($item_str,',').'").prop("checked",false);
                    }
                })
                $("'.trim($item_str,',').'").change(function(){
                    if($("'.trim($item_str,',').'").is(":checked")){
                        $("#pet'.$k.'").prop("checked","checked");
                    }
                })
                ';
            }
        }
        $js = <<<EOF
            <script type="text/javascript">
                $(function(){
                    $js_str
                });
            </script>
EOF;
        $doctor = BusinessDoctorModel::where(array('status'=>1,'bid'=>BID))->column('id,name');
        $coupon = BusinessCouponModel::where(array('status'=>1,'bid'=>BID,'begin_time'=>['<',time()],'end_time'=>['>',time()]))->column('id,title');

        // 显示添加页面
        return ZBuilder::make('form')
            ->setPageTips('如果出现无法添加的情况，可能由于浏览器将本页面当成了广告，请尝试关闭浏览器的广告过滤功能再试。', 'warning')
            ->addFormItems([
                ['select', 'type', '服务项目', '', $type_list],
                ['number', 'price', '服务价格'],
                ['checkbox', 'pet', '宠物', '服务项目支持的宠物分类', $pet_list, array_keys($pet_list)],
                ['checkbox', 'breed', '品种', '服务项目支持的宠物品种', $breed_list, array_keys($breed_list)],
                ['checkbox', 'doctor', '医生', '选择负责该项服务的医生', $doctor],
                ['checkbox', 'coupon', '商家优惠', '选择该项服务支持的优惠（只显示启用的未过期的优惠）', $coupon],
                ['checkbox', 'time', '服务时间段', '选择服务时间段', config('forenoon')+config('afternoon')+config('night')],
                ['text', 'time_num', '每个时间段可接单数', '指一个时间段用户可以下单的数量', "2"],
                // ['switch', 'is_coin', '金币支付', '', 1, '', 'disabled'],
            ])
            ->setExtraJs($js)
            ->fetch();
    }

    /**
     * 编辑
     * @param null $id id
     * @return mixed
     */
    public function edit($id = null)
    {
        if ($id === null) $this->error('缺少参数');

        //判断是否商家添加
        if(!defined('BID')) {
            $this->error("您不是商家账号，无法添加服务！");
        }else{
            $data = BusinessServiceModel::where(array('id'=>$id, 'bid'=>BID))->value('id');
            if(!$data){
                $this->error('非法操作');
            }
        }

        $type_list = BusinessServiceConfigModel::where(array('status'=>1, 'config_id'=> 1))->column('id,name');
        $pet_list = BusinessServiceConfigModel::where(array('status'=>1, 'config_id'=> 2))->column('id,name');
        $breed_list = BusinessServiceConfigModel::where(array('status'=>1, 'config_id'=> 3))->column('id,name');

        // 保存数据
        if ($this->request->isPost()) {
            // 表单数据
            $data = $this->request->post();
            // 商家id
            $data['bid'] = BID;
            // 验证
            $result = $this->validate($data, 'BusinessService');
            // 验证失败 输出错误信息
            if(true !== $result) return $this->error($result);
            //处理数据
            $data['pet'] = implode(',',$data['pet']);
            $data['breed'] = implode(',',$data['breed']);
            $data['time'] = implode(',',$data['time']);
            $data['coupon'] = isset($data['coupon']) ? implode(',',$data['coupon']) : '';
            $data['doctor'] = isset($data['doctor']) ? implode(',',$data['doctor']) : '';
            $data['name'] = $type_list[$data['type']];
            //保存数据
            if (BusinessServiceModel::update($data)) {
                // 记录行为
                action_log('business_service_edit', 'pet_business_service', $id, UID, $data['name']);
                $this->success('编辑成功', 'index');
            } else {
                $this->error('编辑失败');
            }
        }

        $js_str = '';
        foreach($pet_list as $k=>$v){
            $item = BusinessServiceConfigModel::where(array('status'=>1, 'config_id'=> 3, 'pid'=>$k))->column('id,name');
            $item_str = '';
            if($item){
                foreach($item as $kk=>$vv){
                    $item_str .= "#breed".$kk.",";
                }
                $js_str .= '$("#pet'.$k.'").change(function(){
                    if($("#pet'.$k.'").is(":checked")){
                        $("'.trim($item_str,',').'").prop("checked","checked");
                    }else{
                        $("'.trim($item_str,',').'").prop("checked",false);
                    }
                })
                $("'.trim($item_str,',').'").change(function(){
                    if($("'.trim($item_str,',').'").is(":checked")){
                        $("#pet'.$k.'").prop("checked","checked");
                    }
                })
                ';
            }
        }
        $js = <<<EOF
            <script type="text/javascript">
                $(function(){
                    $js_str
                });
            </script>
EOF;
        $doctor = BusinessDoctorModel::where(array('status'=>1,'bid'=>BID))->column('id,name');
        $coupon = BusinessCouponModel::where(array('status'=>1,'bid'=>BID,'begin_time'=>['<',time()],'end_time'=>['>',time()]))->column('id,title');

        $info = BusinessServiceModel::get($id);
        //处理数据
        $info['pet'] = explode(',',$info['pet']);
        $info['breed'] = explode(',',$info['breed']);
        $info['time'] = explode(',',$info['time']);
        $info['coupon'] = isset($info['coupon']) ? explode(',',$info['coupon']) : '';
        $info['doctor'] = isset($info['doctor']) ? explode(',',$info['doctor']) : '';

        // 显示添加页面
        return ZBuilder::make('form')
            ->setPageTips('如果出现无法添加的情况，可能由于浏览器将本页面当成了广告，请尝试关闭浏览器的广告过滤功能再试。', 'warning')
            ->addFormItems([
                ['hidden', 'id'],
                ['select', 'type', '服务项目', '', $type_list],
                ['number', 'price', '服务价格'],
                ['checkbox', 'pet', '宠物', '服务项目支持的宠物分类', $pet_list, array_keys($pet_list)],
                ['checkbox', 'breed', '品种', '服务项目支持的宠物品种', $breed_list, array_keys($breed_list)],
                ['checkbox', 'doctor', '医生', '选择负责该项服务的医生', $doctor],
                ['checkbox', 'coupon', '商家优惠', '选择该项服务支持的优惠（只显示启用的未过期的优惠）', $coupon],
                ['checkbox', 'time', '服务时间段', '选择服务时间段', config('forenoon')+config('afternoon')+config('night')],
                ['text', 'time_num', '每个时间段可接单数', '指一个时间段用户可以下单的数量'],
                // ['switch', 'is_coin', '金币支付', '', 1, '', 'disabled'],
            ])
            ->setExtraJs($js)
            ->setFormData($info)
            ->fetch();
    }

    /**
     * 删除
     * @param array $record 行为日志
     * @return mixed
     */
    public function delete($record = [])
    {
        return $this->setStatus('delete');
    }

    /**
     * 启用
     * @param array $record 行为日志
     * @return mixed
     */
    public function enable($record = [])
    {
        return $this->setStatus('enable');
    }

    /**
     * 禁用
     * @param array $record 行为日志
     * @return mixed
     */
    public function disable($record = [])
    {
        return $this->setStatus('disable');
    }

    /**
     * 设置状态：删除、禁用、启用
     * @param string $type 类型：delete/enable/disable
     * @param array $record
     * @return mixed
     */
    public function setStatus($type = '', $record = [])
    {
        $ids         = $this->request->isPost() ? input('post.ids/a') : input('param.ids');
        $service_name = BusinessServiceModel::where('id', 'in', $ids)->column('name');
        return parent::setStatus($type, ['business_service_'.$type, 'pet_business_service', 0, UID, implode('、', $service_name)]);
    }

    /**
     * 快速编辑
     * @param array $record 行为日志
     * @return mixed
     */
    public function quickEdit($record = [])
    {
        $id      = input('post.pk', '');
        $field   = input('post.name', '');
        $value   = input('post.value', '');
        $business_service  = BusinessServiceModel::where('id', $id)->value($field);
        $details = '字段(' . $field . ')，原值(' . $business_service . ')，新值：(' . $value . ')';
        return parent::quickEdit(['business_service_edit', 'pet_business_service', $id, UID, $details]);
    }
}