<?php

namespace app\pet\admin;

use app\admin\controller\Admin;
use app\common\builder\ZBuilder;
use app\pet\model\BusinessServiceConfig as BusinessServiceConfigModel;
use think\Validate;
use think\Config;
use think\Db;

/**
 * 仪表盘控制器
 * @package app\pet\admin
 */
class BusinessServiceConfig extends Admin
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
        // 数据列表
        $data_list = BusinessServiceConfigModel::where($map)->order($order)->paginate();
        //宠物配置列表
        $pets = BusinessServiceConfigModel::where(array('status'=>1, 'config_id'=>2))->column('id,name');
        $pets['0'] = '无';
        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
            ->setSearch(['name' => '配置名称']) // 设置搜索框
            ->addColumns([ // 批量添加数据列
                ['id', 'ID'],
                ['name', '配置名称', 'text.edit'],
                ['icon', '配置图标', 'picture'],
                ['pid', '父类配置', 'select', $pets],
                ['config_id', '服务配置', 'select', config('service_config')],
                ['create_time', '创建时间', 'datetime'],
                ['status', '状态', 'switch'],
                ['right_button', '操作', 'btn']
            ])
            ->addTopButtons('add,enable,disable,delete') // 批量添加顶部按钮
            ->addRightButtons(['edit', 'delete' => ['data-tips' => '删除后无法恢复。']]) // 批量添加右侧按钮
            ->addOrder('id,status,create_time')
            ->setRowList($data_list ) // 设置表格数据
            ->fetch(); // 渲染模板
    }

    /**
     * 新增
     * @return mixed
     */
    public function add()
    {
        // 保存数据
        if ($this->request->isPost()) {
            // 表单数据
            $data = $this->request->post();
            // 验证
            $result = $this->validate($data, 'BusinessServiceConfig');
            // 验证失败 输出错误信息
            if(true !== $result) return $this->error($result);
            //保存数据
            $service_config = BusinessServiceConfigModel::create($data);
            if($service_config){
                // 记录行为
                action_log('business_service_config_add', 'pet_business_service_config', $service_config['id'], UID, $data['name']);
                $this->success('新增成功', 'index');
            }else{
                $this->error('新增失败');
            }
        }
        //父类宠物服务配置列表
        $pets = BusinessServiceConfigModel::where(array('status'=>1, 'config_id'=>2))->column('id,name');

        // 显示添加页面
        return ZBuilder::make('form')
            ->setPageTips('如果出现无法添加的情况，可能由于浏览器将本页面当成了广告，请尝试关闭浏览器的广告过滤功能再试。', 'warning')
            ->addFormItems([
                ['text', 'name', '配置名称'],
                ['select', 'config_id', '服务配置', '', config('service_config')],
                ['select', 'pid', '所属宠物类型', '', $pets],
                ['image', 'icon', '配置图标'],
                ['image', 'pet_icon', '弹窗宠物图标'],
                ['radio', 'status', '状态', '', ['禁用', '启用'], 1]
            ])
            ->setTrigger('config_id', 3, 'pid')
            ->setTrigger('config_id', 2, 'pet_icon')
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

        // 保存数据
        if ($this->request->isPost()) {
            // 表单数据
            $data = $this->request->post();
            // 验证
            $result = $this->validate($data, 'BusinessServiceConfig');
            // 验证失败 输出错误信息
            if(true !== $result) return $this->error($result);
            //保存数据
            if (BusinessServiceConfigModel::update($data)) {
                // 记录行为
                action_log('business_service_config_edit', 'pet_business_service_config', $id, UID, $data['name']);
                $this->success('编辑成功', 'index');
            } else {
                $this->error('编辑失败');
            }
        }
        //父类宠物服务配置列表
        $pets = BusinessServiceConfigModel::where(array('status'=>1, 'config_id'=>2))->column('id,name');
        //获取原来数据
        $info = BusinessServiceConfigModel::get($id);
        // 显示添加页面
        return ZBuilder::make('form')
            ->setPageTips('如果出现无法添加的情况，可能由于浏览器将本页面当成了广告，请尝试关闭浏览器的广告过滤功能再试。', 'warning')
            ->addFormItems([
                ['hidden', 'id'],
                ['text', 'name', '配置名称'],
                ['select', 'config_id', '服务配置', '', config('service_config')],
                ['select', 'pid', '所属宠物类型', '', $pets],
                ['image', 'icon', '配置图标'],
                ['image', 'pet_icon', '弹窗宠物图标'],
                ['radio', 'status', '状态', '', ['禁用', '启用'], 1]
            ])
            ->setTrigger('config_id', 3, 'pid')
            ->setTrigger('config_id', 2, 'pet_icon')
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
        $ids = $this->request->isPost() ? input('post.ids/a') : input('param.ids');
        $service_config_name = BusinessServiceConfigModel::where('id', 'in', $ids)->column('name');
        return parent::setStatus($type, ['business_service_config_'.$type, 'pet_business_service_config', 0, UID, implode('、', $service_config_name)]);
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
        $service_config  = BusinessServiceConfigModel::where('id', $id)->value($field);
        $details = '字段(' . $field . ')，原值(' . $service_config . ')，新值：(' . $value . ')';
        return parent::quickEdit(['business_service_config_edit', 'pet_business_service_config', $id, UID, $details]);
    }
}