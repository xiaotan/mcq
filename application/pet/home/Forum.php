<?php

namespace app\pet\home;

use app\cms\model\Slider as SliderModel;
use app\pet\model\Member as MemberModel;
use app\pet\model\MemberForum as MemberForumModel;
use app\pet\model\MemberFollow as MemberFollowModel;
use app\pet\model\MemberFavorite as MemberFavoriteModel;

/**
 * 前台首页控制器
 * @package app\pet\admin
 */
class Forum extends Common
{
    /**
     * 首页
     * @return mixed
     */
    public function index()
    {
        $slider = SliderModel::where(array("typeid"=>2, "status"=>1))->select();

        // 获取用户信息
        $member = MemberModel::where(array("id"=>session("member_auth.member_id")))->find();

        $forums = $this->getFourmList();

        $this->assign('tab', 1);
        $this->assign('slider', $slider);
        $this->assign('member', $member);
        $this->assign('forums', json_encode($forums));
        return $this->fetch(); // 渲染模板
    }

    public function ajaxGetFourmList(){
        $page = input('get.page/d', 1);
        $mid = input('get.mid/d', 0);
        $data = $this->getFourmList($mid, $page);
        echo $data ? json_encode($data) : 1;
    }

    private function getFourmList($mid = '', $page = 1, $pageSize = 10, $cache = false){
        $forums = array();
        // 做session缓存
        if(session('forum_'.$mid.'_'.$page) && $cache){
            $forums = session('forum_'.$mid.'_'.$page);
        }else{
            if($mid){
                $map['mid'] = $mid;
                $order = "id desc";
            }else{
                $order = "is_stick desc,id desc";
            }
            $map['status'] = 1;
            $data = MemberForumModel::where($map)->order($order)->page($page.','.$pageSize)->select();
            foreach($data as $k=>$v){
                //获取帖子有多少人喜欢
                $forums[$k]['favorite'] = MemberFavoriteModel::where(array("forum_id"=>$v['id']))->count("id");
                $forums[$k]['is_favorite'] = 0;
                if(session("member_auth.member_id")){
                    $item = MemberFavoriteModel::where(array("forum_id"=>$v['id'], "mid"=>session("member_auth.member_id")))->value("id");
                    $forums[$k]['is_favorite'] = $item ? 1 : 0;
                }
                //获取用户信息
                $member = MemberModel::get($v['mid']);
                $forums[$k]['id'] = $v['id'];
                $forums[$k]['nickname'] = $member['nickname'];
                $forums[$k]['avatar'] = get_avatar($member['id']);
                $forums[$k]['date'] = time_tran($v['create_time']);
                $forums[$k]['content'] = $v['content'];
                $forums[$k]['lng'] = $v['lng'];
                $forums[$k]['lat'] = $v['lat'];
                $forums[$k]['year'] = date("y",$v['create_time']);
                $forums[$k]['month'] = date("n",$v['create_time']);
                $forums[$k]['day'] = date("d",$v['create_time']) == date("d",time()) ? '今天' : date("d",$v['create_time']);
                $forums[$k]['lat'] = $v['lat'];
                $forums[$k]['location'] = $v['location'];
                $forums[$k]['url'] = url('forum/space',array('id'=>$v['mid']));
                if($v['images']){
                    $images = explode(',', $v['images']);
                    foreach($images as $k1=>$v1){
                        $images[$k1] = get_file_path($v1);
                    }
                    $forums[$k]['images'] = $images;
                }
            }
        }
        return $forums;
    }

    /**
     * ajax喜欢帖子
     * @return mixed
     */
    public function ajaxFavorite()
    {
        if(!session("member_auth.member_id")){
            $return['code'] = 0;
            $return['info'] = "请先授权登录";
            echo json_encode($return);exit;
        }
        $id = input("get.id/d", '');
        if($id){
            $favorite = MemberFavoriteModel::where(array("forum_id"=>$id, "mid"=>session("member_auth.member_id")))->value("id");
            if($favorite){
                MemberFavoriteModel::where(array("forum_id"=>$id, "mid"=>session("member_auth.member_id")))->delete();
                $return['code'] = 1;
                $return['info'] = 0;
                echo json_encode($return);exit;
            }else{
                $input['mid'] = session("member_auth.member_id");
                $input['forum_id'] = $id;
                MemberFavoriteModel::create($input);

                $return['code'] = 1;
                $return['info'] = 1;
                echo json_encode($return);exit;
            }
        }
    }

    /**
     * ajax关注用户
     * @return mixed
     */
    public function ajaxFollow()
    {
        if(!session("member_auth.member_id")){
            $return['code'] = 0;
            $return['info'] = "请先授权登录";
            echo json_encode($return);exit;
        }
        $id = input("get.id/d", '');
        if($id){
            $follow = MemberFollowModel::where(array("follow_mid"=>$id, "mid"=>session("member_auth.member_id")))->value("id");
            if($follow){
                MemberFollowModel::where(array("follow_mid"=>$id, "mid"=>session("member_auth.member_id")))->delete();
                $return['code'] = 1;
                $return['info'] = 0;
                echo json_encode($return);exit;
            }else{
                $input['mid'] = session("member_auth.member_id");
                $input['follow_mid'] = $id;
                MemberFollowModel::create($input);

                $return['code'] = 1;
                $return['info'] = 1;
                echo json_encode($return);exit;
            }
        }
    }

    /**
     * 个人圈首页
     * @return mixed
     */
    public function space($id)
    {
        if(!$id){
            $this->error('用户不存在');
        }
        // 获取用户信息
        $member = MemberModel::where(array("id"=>$id))->find();
        if(!$member){
            $this->error('用户不存在');
        }
        $member['follow'] = MemberFollowModel::where(array("mid"=>$id))->count("id");
        $member['followed'] = MemberFollowModel::where(array("follow_mid"=>$id))->count("id");
        $forums = $this->getFourmList($id);

        $is_follow = 0;
        if(session("member_auth.member_id")){
            $is_follow = MemberFollowModel::where(array("mid"=>session("member_auth.member_id"), "follow_mid"=>$id))->value("id");
        }

        $this->assign('tab', 1);
        $this->assign('member', $member);
        $this->assign('is_follow', $is_follow ? 1 : 0);
        $this->assign('forums', json_encode($forums));
        return $this->fetch(); // 渲染模板
    }

    /**
     * 发布帖子页
     * @return mixed
     */
    public function publish()
    {
        if(!session("member_auth.member_id")){
            $this->error('请先授权登录');
        }
        return $this->fetch(); // 渲染模板
    }

    //ajax发布帖子
    public function ajaxSendMsg(){
        if(!session("member_auth.member_id")){
            $return['code'] = 0;
            $return['info'] = "请先授权登录";
            echo json_encode($return);exit;
        }
        $data = $this->request->post();
        if(empty($data['content'])){
            $return['code'] = 0;
            $return['info'] = "内容不能为空";
            echo json_encode($return);exit;
        }
        $publish['mid'] = session("member_auth.member_id");
        $publish['lng'] = $data['lng'];
        $publish['lat'] = $data['lat'];
        $publish['location'] = $data['location'];
        $publish['content'] = $data['content'];
        $publish['images'] = $data['imgs'] ? trim($data['imgs'],',') : '';
        $result = MemberForumModel::create($publish);
        if($result){
            $return['code'] = 1;
            $return['info'] = "发布成功";
            echo json_encode($return);exit;
        }else{
            $return['code'] = 0;
            $return['info'] = "发布失败";
            echo json_encode($return);exit;
        }
    }


}