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
use app\pet\model\Service as ServiceModel;
use app\pet\model\ServiceConfig as ServiceConfigModel;
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
        // 数据列表
        $data_list = ServiceModel::where($map)->order($order)->paginate();

        $btnType = [
            'class' => 'btn btn-info',
            'title' => '服务配置',
            'icon'  => 'fa fa-fw fa-sitemap',
            'href'  => url('business_service_config/index')
        ];

        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
            ->setSearch(['name' => '服务名称']) // 设置搜索框
            ->addColumns([ // 批量添加数据列
                ['id', 'ID'],
                ['name', '商家名称', 'text'],
                // ['type', '服务项目', 'select', $type_list],
                ['tel', '商家电话', 'text'],
                ['create_time', '入驻时间', 'datetime'],
                ['status', '状态', 'switch'],
                ['right_button', '操作', 'btn']
            ])
            ->addTopButtons('add,enable,disable,delete') // 批量添加顶部按钮
            ->addTopButton('custom', $btnType) // 添加顶部按钮
            ->addRightButtons(['edit', 'delete' => ['data-tips' => '删除后无法恢复。']]) // 批量添加右侧按钮
            ->addOrder('id,name,create_time')
            ->setRowList($data_list) // 设置表格数据
            ->addValidate('Business', 'name')
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
            print_r($data);exit;
            // 验证
            $result = $this->validate($data, 'Business');
            // $result = $this->validate('Business.add')->save($data);
            // 验证失败 输出错误信息
            if(true !== $result) return $this->error($result);
            //分别存储经纬度
            if($data['map']){
                $map = explode(",", $data['map']);
                $data['lng'] = $map[0];
                $data['lat'] = $map[1];
                //详细地址
                $data['address'] = $data['map_address'];
            }

            //保存数据
            $business = BusinessModel::create($data);
            if($business){
                $data['bid'] = $business['id'];
                $user = UserModel::create($data);
                if ($user) {
                    // 记录行为
                    action_log('business_add', 'business', $business['id'], UID, $data['name']);
                    action_log('user_add', 'admin_user', $user['id'], UID, $data['nickname']);
                    $this->success('新增成功', 'index');
                } else {
                    BusinessModel::destroy($business['id']);
                    UserModel::destroy($user['id']);
                    $this->error('新增失败');
                }
            }
        }

        $type_list = ServiceConfigModel::where(array('status'=>1, 'config_id'=> 1))->column('id,name');

        $pet_list = ServiceConfigModel::where(array('status'=>1, 'config_id'=> 2))->column('id,name');

        $breed_list = ServiceConfigModel::where(array('status'=>1, 'config_id'=> 3))->column('id,name');

        // 显示添加页面
        return ZBuilder::make('form')
            ->setPageTips('如果出现无法添加的情况，可能由于浏览器将本页面当成了广告，请尝试关闭浏览器的广告过滤功能再试。', 'warning')
            ->addFormItems([
                ['select', 'type', '服务项目', '', $type_list],
                ['number', 'price', '服务价格'],
                // ['select', 'pet', '宠物', '服务项目支持的宠物', $pet_list, array_keys($pet_list), 'multiple'],
                ['checkbox', 'pet', '宠物', '服务项目支持的宠物分类', $pet_list, array_keys($pet_list)],
                ['select', 'breed', '品种', '服务项目支持的宠物品种', $breed_list, array_keys($breed_list)],
                ['switch', 'is_coin', '金币支付', '', 1, '', 'disabled'],
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

            // 验证
            $result = $this->validate($data, 'Business.edit');
            // 验证失败 输出错误信息
            if(true !== $result) return $this->error($result);

            //分别存储经纬度
            $map = explode(",", $data['map']);
            $data['lng'] = $map[0];
            $data['lat'] = $map[1];
            //详细地址
            $data['address'] = $data['map_address'];
            //保存数据
            if (BusinessModel::update($data)) {
                // 记录行为
                action_log('business_edit', 'business', $id, UID, $data['name']);
                $this->success('编辑成功', 'index');
            } else {
                $this->error('编辑失败');
            }
        }

        $info = BusinessModel::get($id);
        $info['map'] = $info['lng'].",".$info['lat'];
        // 显示编辑页面
        return ZBuilder::make('form')
            ->setPageTips('如果出现无法添加的情况，可能由于浏览器将本页面当成了广告，请尝试关闭浏览器的广告过滤功能再试。', 'warning')
            ->addFormItems([
                ['hidden', 'id'],
                ['text', 'name', '商家名称', '必填，请填写商家全称'],
                ['text', 'tel', '商家电话', '可以填手机号或座机号，座机记得加上区号，例：0771-1234567'],
                ['image', 'thumb', '缩略图'],
                ['images', 'banner', '商家banner（多图）','最多上传5张图,每张图最大4m','','4096'],
                ['bmap', 'map', '商家位置', config('baidu_map_ak')],
            ])
            ->layout(['name' => 6, 'tel' => 6])
            ->setFormData($info)
            ->fetch();
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
        $business  = BusinessModel::where('id', $id)->value($field);
        $details = '字段(' . $field . ')，原值(' . $business . ')，新值：(' . $value . ')';
        return parent::quickEdit(['business_edit', 'business', $id, UID, $details]);
    }
}