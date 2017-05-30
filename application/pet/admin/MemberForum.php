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
use app\pet\model\MemberForum as MemberForumModel;
// use app\user\model\User as UserModel;
use think\Validate;
use think\Config;
use think\Db;

/**
 * 仪表盘控制器
 * @package app\pet\admin
 */
class MemberForum extends Admin
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
        $data_list = MemberForumModel::where($map)->order($order)->paginate();

        foreach($data_list as $k=>$v){
            $data_list[$k]['name'] = get_member_name($v['mid']);
            $data_list[$k]['content'] = str_cut($v['content'], 50);
        }
        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
            // ->setSearch(['name' => '商家名称']) // 设置搜索框
            ->addColumns([ // 批量添加数据列
                ['id', 'ID'],
                ['name', '发布人', 'text'],
                ['content', '发布内容', 'text'],
                ['create_time', '发布时间', 'datetime'],
                ['status', '状态', 'switch'],
                ['is_stick', '置顶', 'switch'],
                ['right_button', '操作', 'btn']
            ])
            ->addTopButtons('enable,disable,delete') // 批量添加顶部按钮
            ->addRightButtons(['edit', 'delete' => ['data-tips' => '删除后无法恢复。']]) // 批量添加右侧按钮
            ->addOrder('id,create_time')
            ->setRowList($data_list) // 设置表格数据
            ->fetch(); // 渲染模板
    }

    /**
     * 查看
     * @param null $id id
     * @author 蔡伟明 <314013107@qq.com>
     * @return mixed
     */
    public function edit($id = null)
    {
        if ($id === null) $this->error('缺少参数');

        $info = MemberForumModel::get($id);
        // 显示编辑页面
        return ZBuilder::make('form')
            ->setPageTips('如果出现无法添加的情况，可能由于浏览器将本页面当成了广告，请尝试关闭浏览器的广告过滤功能再试。', 'warning')
            ->addFormItems([
                ['hidden', 'id'],
                ['static', 'content', '发布内容'],
                ['images', 'images', '图片列表'],
                ['static', 'location', '发布定位'],
                ['select', 'status', '状态', '', ['0'=>'未启用','1'=>'已启用']],
                ['select', 'is_refund', '置顶状态', '', ['0'=>'未置顶','1'=>'已置顶']],
            ])
            ->hideBtn('submit')
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
        $MemberForum = MemberForumModel::where('id', 'in', $ids)->column('id');
        return parent::setStatus($type, ['member_forum_'.$type, 'pet_member_forum', 0, UID, implode('、', $MemberForum)]);
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
        $forum  = MemberForumModel::where('id', $id)->value($field);
        $details = '字段(' . $field . ')，原值(' . $forum . ')，新值：(' . $value . ')';
        return parent::quickEdit(['member_forum__edit', 'pet_member_forum', $id, UID, $details]);
    }
}