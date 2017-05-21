<?php

namespace app\pet\admin;

use app\admin\controller\Admin;
use app\common\builder\ZBuilder;
use app\pet\model\Business as BusinessModel;
use app\pet\model\BusinessDoctor as BusinessDoctorModel;
use think\Validate;
use think\Config;
use think\Db;

/**
 * @package app\pet\admin
 */
class BusinessDoctor extends Admin
{
    /**
     * 首页
     * @return mixed
     */
    public function index()
    {
        // 查询
        $map = $this->getMap();
        //如果为商家登录，则筛选加上商家id
        if(defined('BID')) {
            $map['bid'] = BID;
        }
        // 排序
        $order = $this->getOrder('update_time desc');
        // 数据列表
        $data_list = BusinessDoctorModel::where($map)->order($order)->paginate();

        //如果为管理员查看，则增加所属商家
        $column = [['id', 'ID']];
        if(!defined('BID')) {
            $business = BusinessModel::where(array('status'=>1))->column('id,name');
            $column[] = ['bid', '所属商家', $business];
        }
        //基础表单
        $column_base = [
            ['name', '医生姓名', 'text'],
            ['position', '医生职位', 'text'],
            ['major', '医生主修', 'text'],
            ['score', '综合评分', 'text'],
            ['create_time', '发布时间', 'datetime'],
            ['status', '状态', 'switch'],
            ['right_button', '操作', 'btn']
        ];

        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
            ->setSearch(['name' => '医生姓名']) // 设置搜索框
            ->addColumns(array_merge($column, $column_base))
            ->addTopButtons('add,enable,disable,delete') // 批量添加顶部按钮
            ->addRightButtons(['edit', 'delete' => ['data-tips' => '删除后无法恢复。']]) // 批量添加右侧按钮
            ->addOrder('id,score,create_time')
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
            //判断是否商家添加
            if(defined('BID')) {
                $data['bid'] = BID;
            }
            // 验证
            $result = $this->validate($data, 'BusinessDoctor');
            // 验证失败 输出错误信息
            if(true !== $result) return $this->error($result);
            //保存数据
            $business_doctor = BusinessDoctorModel::create($data);
            if ($business_doctor) {
                // 记录行为
                action_log('business_doctor_add', 'pet_business_doctor', $business_doctor['id'], UID, $data['name']);
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
            ['text', 'name', '医生姓名'],
            ['text', 'position', '医生职位', '例如：主治医生、实习医生等'],
            ['tags', 'major', '医生主修', '例如：猫科、皮肤科、传染科等（添加方法：在方框里填写并回车）'],
            ['jcrop', 'avatar', '医生头像', '头像规格最佳为 250x250', '', ['aspectRatio' => 1]],
            ['text', 'intro', '医生简介'],
        ];
        // 显示添加页面
        return ZBuilder::make('form')
            ->setPageTips('如果出现无法添加的情况，可能由于浏览器将本页面当成了广告，请尝试关闭浏览器的广告过滤功能再试。', 'warning')
            ->addFormItems(array_merge($forms, $forms_base))
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

        //如果为商家修改，则判断该医生是否属于商家
        if(defined('BID')) {
            $data = BusinessDoctorModel::where(array('id'=>$id, 'bid'=>BID))->value('id');
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
            // 验证
            $result = $this->validate($data, 'BusinessDoctor');
            // 验证失败 输出错误信息
            if(true !== $result) return $this->error($result);
            //保存数据
            if (BusinessDoctorModel::update($data)) {
                // 记录行为
                action_log('business_doctor_edit', 'pet_business_doctor', $id, UID, $data['name']);
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
            ['text', 'name', '医生姓名'],
            ['text', 'position', '医生职位', '例如：主治医生、实习医生等'],
            ['tags', 'major', '医生主修', '例如：猫科、皮肤科、传染科等（添加方法：在方框里填写并回车）'],
            ['jcrop', 'avatar', '医生头像', '头像规格最佳为 250x250', '', ['aspectRatio' => 1]],
            ['text', 'intro', '医生简介'],
        ];
        $info = BusinessDoctorModel::get($id);
        // 显示添加页面
        return ZBuilder::make('form')
            ->setPageTips('如果出现无法添加的情况，可能由于浏览器将本页面当成了广告，请尝试关闭浏览器的广告过滤功能再试。', 'warning')
            ->addFormItems(array_merge($forms, $forms_base))
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
        //商家操作则判断是否属于商家
        if(defined('BID')){
            $id_arr = is_array($ids) ? $ids : array($ids);
            foreach($id_arr as $v){
                $data = BusinessDoctorModel::where(array('id'=>$v, 'bid'=>BID))->value('id');
                if(!$data){
                    $this->error('非法操作');
                }
            }
        }
        $doctor_name = BusinessDoctorModel::where('id', 'in', $ids)->column('name');
        return parent::setStatus($type, ['business_doctor_'.$type, 'pet_business_doctor', 0, UID, implode('、', $doctor_name)]);
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
        //商家操作则判断是否属于商家
        if(defined('BID')){
            $data = BusinessDoctorModel::where(array('id'=>$id, 'bid'=>BID))->value('id');
            if(!$data){
                $this->error('非法操作');
            }
        }
        $business_doctor  = BusinessDoctorModel::where('id', $id)->value($field);
        $details = '字段(' . $field . ')，原值(' . $business_doctor . ')，新值：(' . $value . ')';
        return parent::quickEdit(['business_doctor_edit', 'pet_business_doctor', $id, UID, $details]);
    }

}