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

        $pets = BusinessServiceConfigModel::where(array('status'=>1, 'config_id'=>2))->column('id,name');

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
            ->setRowList($data_list) // 设置表格数据
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
            $data['status'] = isset($data['status']) ? 1 : 0;
            // 验证
            $result = $this->validate($data, 'ServiceConfig');
            // 验证失败 输出错误信息
            if(true !== $result) return $this->error($result);
            //保存数据
            $service_config = BusinessServiceConfigModel::create($data);
            if($service_config){
                // 记录行为
                action_log('service_config_add', 'service_config', $service_config['id'], UID, $data['name']);
                $this->success('新增成功', 'index');
            }else{
                $this->error('新增失败');
            }
        }

        //服务配置列表
        $list_type = config('service_config');

        $pets = BusinessServiceConfigModel::where(array('status'=>1, 'config_id'=>2))->column('id,name');

        // 显示添加页面
        return ZBuilder::make('form')
            ->setPageTips('如果出现无法添加的情况，可能由于浏览器将本页面当成了广告，请尝试关闭浏览器的广告过滤功能再试。', 'warning')
            ->addFormItems([
                ['text', 'name', '配置名称'],
                ['select', 'pid', '父类配置', '', $pets],
                ['select', 'config_id', '服务配置', '', $list_type],
                ['image', 'icon', '配置图标'],
                ['switch', 'status', '状态', '', 1],
            ])
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
            $data['status'] = isset($data['status']) ? 1 : 0;
            // 验证
            $result = $this->validate($data, 'ServiceConfig');
            // 验证失败 输出错误信息
            if(true !== $result) return $this->error($result);
            //保存数据
            if (BusinessServiceConfigModel::update($data)) {
                // 记录行为
                action_log('service_config_edit', 'service_config', $id, UID, $data['name']);
                $this->success('编辑成功', 'index');
            } else {
                $this->error('编辑失败');
            }
        }
        
        $pets = BusinessServiceConfigModel::where(array('status'=>1, 'config_id'=>2))->column('id,name');

        $info = BusinessServiceConfigModel::get($id);
        // 显示添加页面
        return ZBuilder::make('form')
            ->setPageTips('如果出现无法添加的情况，可能由于浏览器将本页面当成了广告，请尝试关闭浏览器的广告过滤功能再试。', 'warning')
            ->addFormItems([
                ['hidden', 'id'],
                ['text', 'name', '配置名称'],
                ['select', 'pid', '父类配置', '', $pets],
                ['select', 'config_id', '服务配置', '', config('service_config')],
                ['image', 'icon', '配置图标'],
                ['switch', 'status', '状态', '', 1],
            ])
            ->setFormData($info)
            ->fetch();
    }
}