<?php

namespace app\pet\admin;

use app\admin\controller\Admin;
use app\common\builder\ZBuilder;
use app\pet\model\Task as TaskModel;
use think\Validate;
use think\Config;
use think\Db;

/**
 * 仪表盘控制器
 * @package app\pet\admin
 */
class Task extends Admin
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
        $order = $this->getOrder('create_time desc');
        // 数据列表
        $data_list = TaskModel::where($map)->order($order)->paginate();

        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
            ->setSearch(['title' => '任务名称']) // 设置搜索框
            ->addColumns([ // 批量添加数据列
                ['id', 'ID'],
                ['title', '任务名称', 'text'],
                ['type', '任务类型', config('task_type')],
                ['intro', '任务简介', 'text'],
                ['amount', '任务积分', 'text'],
                ['status', '状态', 'switch'],
                ['right_button', '操作', 'btn']
            ])
            ->addTopButtons('add,enable,disable,delete') // 批量添加顶部按钮
            ->addRightButtons(['edit', 'delete' => ['data-tips' => '删除后无法恢复。']]) // 批量添加右侧按钮
            ->addOrder('id,amount,create_time')
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
            // 验证
            if($data['type']==1){
                $result = $this->validate($data, 'Task');
            }else{
                $result = $this->validate($data, 'Task.type');
            }
            
            // 验证失败 输出错误信息
            if(true !== $result) return $this->error($result);

            //保存数据
            $task = TaskModel::create($data);
            
            if ($task) {
                // 记录行为
                action_log('task_add', 'pet_task', $task['id'], UID, $data['title']);
                $this->success('新增成功', 'index');
            } else {
                $this->error('新增失败');
            }
        }

        // 显示添加页面
        return ZBuilder::make('form')
            ->setPageTips('如果出现无法添加的情况，可能由于浏览器将本页面当成了广告，请尝试关闭浏览器的广告过滤功能再试。', 'warning')
            ->addFormItems([
                ['text', 'title', '任务名称'],
                ['select', 'type', '任务类型', '唯一任务需要填写任务开始时间和结束时间', config('task_type')],
                ['datetime', 'begin_time', '任务开始时间', ''],
                ['datetime', 'end_time', '任务结束时间', ''],
                ['text', 'intro', '任务简介'],
                ['number', 'amount', '奖励积分'],
                ['radio', 'status', '状态', '', ['禁用', '启用'], 1]
            ])
            ->setTrigger('type', 1, 'begin_time,end_time')
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
            // 验证
            if($data['type']==1){
                $result = $this->validate($data, 'Task');
            }else{
                $result = $this->validate($data, 'Task.type');
            }
            // 验证失败 输出错误信息
            if(true !== $result) return $this->error($result);
            
            if (TaskModel::update($data)) {
                // 记录行为
                action_log('task_edit', 'pet_task', $id, UID, $data['title']);
                $this->success('编辑成功', 'index');
            } else {
                $this->error('编辑失败');
            }
        }

        $info = TaskModel::get($id);

        // 显示添加页面
        return ZBuilder::make('form')
            ->setPageTips('如果出现无法添加的情况，可能由于浏览器将本页面当成了广告，请尝试关闭浏览器的广告过滤功能再试。', 'warning')
            ->addFormItems([
                ['hidden', 'id'],
                ['text', 'title', '任务名称'],
                ['select', 'type', '任务类型', '唯一任务需要填写任务开始时间和结束时间', config('task_type')],
                ['datetime', 'begin_time', '任务开始时间', ''],
                ['datetime', 'end_time', '任务结束时间', ''],
                ['text', 'intro', '任务简介'],
                ['number', 'amount', '奖励积分'],
                ['radio', 'status', '状态', '', ['禁用', '启用'], 1]
            ])
            ->setTrigger('type', 1, 'begin_time,end_time')
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
        $task_name = TaskModel::where('id', 'in', $ids)->column('title');
        return parent::setStatus($type, ['task_'.$type, 'pet_task', 0, UID, implode('、', $task_name)]);
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
        $task  = TaskModel::where('id', $id)->value($field);
        $details = '字段(' . $field . ')，原值(' . $task . ')，新值：(' . $value . ')';
        return parent::quickEdit(['task_edit', 'pet_task', $id, UID, $details]);
    }
}