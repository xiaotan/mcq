<?php

namespace app\pet\admin;

use app\admin\controller\Admin;
use app\common\builder\ZBuilder;
use app\pet\model\Business as BusinessModel;
use app\user\model\User as UserModel;
use think\Validate;
use think\Config;
use think\Db;

/**
 * 商家控制器
 * @package app\pet\admin
 */
class Business extends Admin
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
        $data_list = BusinessModel::where($map)->order($order)->paginate();

        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
            ->setSearch(['name' => '商家名称']) // 设置搜索框
            ->addColumns([ // 批量添加数据列
                ['id', 'ID'],
                ['name', '商家名称', 'text'],
                ['tel', '商家电话', 'text'],
                ['address', '商家地址', 'text'],
                ['score', '综合评分', 'text'],
                ['create_time', '入驻时间', 'datetime'],
                ['status', '状态', 'switch'],
                ['right_button', '操作', 'btn']
            ])
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
            // 验证
            $result = $this->validate($data, 'Business.add');
            // 验证失败 输出错误信息
            if(true !== $result) return $this->error($result);
            //分别存储经纬度
            if($data['map']){
                $map = explode(",", $data['map']);
                $data['lng'] = $map[0];
                $data['lat'] = $map[1];
                //详细地址
                $data['address'] = $data['map_address'] ? $data['map_address'] : '';
            }
            //验证商家标签数
            if($data['tag'] && count(explode(',', $data['tag']))>config('business_tag_num')){
                $this->error("商家标签最多".config('business_tag_num')."个");
            }
            //默认为商家用户分组
            $data['role'] = 2;
            //保存数据
            $business = BusinessModel::create($data);
            if($business){
                $data['bid'] = $business['id'];
                $user = UserModel::create($data);
                if ($user) {
                    // 记录行为
                    action_log('business_add', 'pet_business', $business['id'], UID, $data['name']);
                    action_log('user_add', 'admin_user', $user['id'], UID, $data['nickname']);
                    $this->success('新增成功', 'index');
                } else {
                    BusinessModel::destroy($business['id']);
                    UserModel::destroy($user['id']);
                    $this->error('新增失败');
                }
            }
        }

        // 显示添加页面
        return ZBuilder::make('form')
            ->setPageTips('如果出现无法添加的情况，可能由于浏览器将本页面当成了广告，请尝试关闭浏览器的广告过滤功能再试。', 'warning')
            ->addGroup([
                '商家基本信息' => [
                    ['text', 'name', '商家名称', '必填，请填写商家全称'],
                    ['text', 'tel', '商家电话', '可以填手机号或座机号，座机记得加上区号，例：0771-1234567'],
                    ['tags', 'tag', '商家标签', '商家标签可以帮助用户更快搜索到您的店铺，最多添加'.config('business_tag_num').'个标签（添加方式：在方框里输入标签并回车）'],
                    ['jcrop', 'thumb', '缩略图', '缩略图规格为 250x180', '', ['minSize' => [250, 180], 'maxSize' => [250, 180]]],
                    ['images', 'banner', '商家banner（多图）','最多上传5张图,每张图最大4m,图片规格为：520x250 最佳，其他尺寸有可能会变形','','4096'],
                    ['bmap', 'map', '商家位置', config('baidu_map_ak'), '商家位置信息包括[位置定位]和[标签定位],为方便用户更快找到店铺，请务必填写正确地址'],
                ],
                '商家账户信息' => [
                    ['text', 'username', '用户名', '必填，可由英文字母、数字组成'],
                    ['text', 'nickname', '昵称', '可以是中文'],
                    ['password', 'password', '密码', '必填，6-20位'],
                    ['text', 'email', '邮箱', ''],
                    ['text', 'mobile', '手机号'],
                    ['jcrop', 'avatar', '头像'],
                    ['radio', 'status', '状态', '', ['禁用', '启用'], 1]
                ],
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
            if($data['map']){
                $map = explode(",", $data['map']);
                $data['lng'] = $map[0];
                $data['lat'] = $map[1];
                //详细地址
                $data['address'] = $data['map_address'] ? $data['map_address'] : '';
            }
            //验证商家标签数
            if($data['tag'] && count(explode(',', $data['tag']))>config('business_tag_num')){
                $this->error("商家标签最多".config('business_tag_num')."个");
            }
            //保存数据
            if (BusinessModel::update($data)) {
                // 记录行为
                action_log('business_edit', 'pet_business', $id, UID, $data['name']);
                $this->success('编辑成功', 'index');
            } else {
                $this->error('编辑失败');
            }
        }

        $info = BusinessModel::get($id);
        //经纬度
        if($info['lng'] && $info['lat']){
            $info['map'] = $info['lng'].",".$info['lat'];
            $info['map_address'] = $info['address'];
        }
        // 显示编辑页面
        return ZBuilder::make('form')
            ->setPageTips('如果出现无法添加的情况，可能由于浏览器将本页面当成了广告，请尝试关闭浏览器的广告过滤功能再试。', 'warning')
            ->addFormItems([
                ['hidden', 'id'],
                ['text', 'name', '商家名称', '必填，请填写商家全称'],
                ['text', 'tel', '商家电话', '可以填手机号或座机号，座机记得加上区号，例：0771-1234567'],
                ['tags', 'tag', '商家标签', '商家标签可以帮助用户更快搜索到您的店铺，最多添加'.config('business_tag_num').'个标签（添加方式：在方框里输入标签并回车）'],
                ['jcrop', 'thumb', '缩略图', '缩略图规格为 250x180', '', ['minSize' => [250, 180], 'maxSize' => [250, 180]]],
                ['images', 'banner', '商家banner（多图）','最多上传5张图,每张图最大4m,图片规格为：520x250 最佳，其他尺寸有可能会变形','','4096'],
                ['bmap', 'map', '商家位置', config('baidu_map_ak'), '商家位置信息包括[位置定位]和[标签定位],为方便用户更快找到店铺，请务必填写正确地址',$info['map'],$info['map_address']],
            ])
            ->setFormData($info)
            ->fetch();
    }

    /**
     * 我的店铺信息
     * @param null $id id
     * @return mixed
     */
    public function my()
    {
        if(!defined('BID')) $this->error('店铺不存在');

        // 保存数据
        if ($this->request->isPost()) {
            // 表单数据
            $data = $this->request->post();
            // 验证
            $result = $this->validate($data, 'Business.my');
            // 验证失败 输出错误信息
            if(true !== $result) return $this->error($result);
            //分别存储经纬度
            if($data['map']){
                $map = explode(",", $data['map']);
                $data['lng'] = $map[0];
                $data['lat'] = $map[1];
                //详细地址
                $data['address'] = $data['map_address'] ? $data['map_address'] : '';
            }
            //验证商家标签数
            if($data['tag'] && count(explode(',', $data['tag']))>config('business_tag_num')){
                $this->error("商家标签最多".config('business_tag_num')."个");
            }
            //保存数据
            if (BusinessModel::update($data)) {
                // 记录行为
                action_log('business_edit', 'pet_business', BID, UID, $data['name']);
                $this->success('编辑成功');
            } else {
                $this->error('编辑失败');
            }
        }

        $info = BusinessModel::get(BID);
        //经纬度
        if($info['lng'] && $info['lat']){
            $info['map'] = $info['lng'].",".$info['lat'];
            $info['map_address'] = $info['address'];
        }
        // 显示编辑页面
        return ZBuilder::make('form')
            ->setPageTips('如果出现无法添加的情况，可能由于浏览器将本页面当成了广告，请尝试关闭浏览器的广告过滤功能再试。', 'warning')
            ->addFormItems([
                ['hidden', 'id'],
                ['text', 'name', '商家名称', '必填，请填写商家全称'],
                ['text', 'tel', '商家电话', '可以填手机号或座机号，座机记得加上区号，例：0771-1234567'],
                ['tags', 'tag', '商家标签', '商家标签可以帮助用户更快搜索到您的店铺，最多添加'.config('business_tag_num').'个标签（添加方式：在方框里输入标签并回车）'],
                ['jcrop', 'thumb', '缩略图', '缩略图规格为 250x180', '', ['minSize' => [250, 180], 'maxSize' => [250, 180]]],
                ['images', 'banner', '商家banner（多图）','最多上传5张图,每张图最大4m,图片规格为：520x250 最佳，其他尺寸有可能会变形','','4096'],
                ['bmap', 'map', '商家位置', config('baidu_map_ak'), '商家位置信息包括[位置定位]和[标签定位],为方便用户更快找到店铺，请务必填写正确地址', $info['map'], $info['map_address']],
            ])
            ->setFormData($info)
            ->fetch();
    }

    /**
     * 删除商家，需同步删除商家相关信息（医生、服务、优惠等）
     * @param array $record 行为日志
     * @return mixed
     */
    public function delete($record = [])
    {
        $ids = $this->request->isPost() ? input('post.ids/a') : input('param.ids');
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
        $business_name = BusinessModel::where('id', 'in', $ids)->column('name');
        return parent::setStatus($type, ['business_'.$type, 'pet_business', 0, UID, implode('、', $business_name)]);
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
        return parent::quickEdit(['business_edit', 'pet_business', $id, UID, $details]);
    }

}