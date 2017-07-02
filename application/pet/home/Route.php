<?php

namespace app\pet\home;

use app\pet\model\Member as MemberModel;
use app\pet\model\MemberRoute as MemberRouteModel;
use app\pet\model\MemberRouteExtend as MemberRouteExtendModel;
use think\Db;

/**
 * 前台首页控制器
 * @package app\pet\admin
 */
class Route extends Common
{

    /**
     * 首页
     * @return mixed
     */
    public function index()
    {
        do_wxlogin();

        // 创建SDK实例
        $script = &  load_wechat('Script');
        // 获取JsApi使用签名，通常这里只需要传 $ur l参数
        $options = $script->getJsSign(get_url());
        // 处理执行结果
        if($options===FALSE){
            // 接口失败的处理
            $this->error($script->errMsg);
        }

        $points = [];
        if(session("?route_id")){
            $points = MemberRouteExtendModel::where(array("route_id"=>session("route_id")))->field("lng,lat")->select();
        }
        /*$points = [
            ["lng"=>'116.399', "lat"=>'39.910'],
            ["lng"=>'116.405', "lat"=>'39.920'],
            ["lng"=>'116.423493', "lat"=>'39.907445'],
        ];*/

        // 用户经纬度
        $lng = get_member_location("lng");
        $lat = get_member_location("lat");
        //获取附近的用户
        $members = Db::query("SELECT id,lng,lat,nickname, ROUND(6378.138*2*ASIN(SQRT(POW(SIN((".$lat."*PI()/180-lat*PI()/180)/2),2)+COS(".$lat."*PI()/180)*COS(lat*PI()/180)*POW(SIN((".$lng."*PI()/180-lng*PI()/180)/2),2)))*1000) AS distance FROM ".config("database.prefix")."pet_member where status=1 and online=1 and lng<>'' and lat<>'' ORDER BY distance ASC");
        foreach($members as $k=>$v){
            $members[$k]['avatar'] = get_member_avatar($v['id']);
            $members[$k]['url'] = url('im/index', array("mid"=>$v['id']));
        }

    	$this->assign("tab", 3);
        $this->assign("members", json_encode($members));
    	$this->assign("points", json_encode($points));
        $this->assign('options', json_encode($options));
        $this->assign('is_route', session("?route_id") ? 1 : 0);
        return $this->fetch(); // 渲染模板
    }

    //ajax开始记录遛狗路线操作
    public function ajaxBeginRoute(){
        if(!session("?member_auth.member_id")){
            $return['code'] = 2;
            $return['info'] = "请先授权登录";
            echo json_encode($return);exit;
        }
        if(!session("?route_id")){
            $route['mid'] = session("member_auth.member_id");
            $route['lng'] = input("post.lng");
            $route['lat'] = input("post.lat");
            $result = MemberRouteModel::create($route);
            if($result){
                session("route_id", $result['id']);
                $extend['route_id'] = $result['id'];
                $extend['lng'] = input("post.lng");
                $extend['lat'] = input("post.lat");
                MemberRouteExtendModel::create($extend);
            }
            $return['code'] = 1;
            $return['info'] = "开始记录您的遛狗路线";
            echo json_encode($return);exit;
        }
    }

    //ajax记录遛狗路线
    public function ajaxRecordRoute(){
        if(session("?route_id")){
            $extend['route_id'] = session("route_id");
            $extend['lng'] = input("post.lng");
            $extend['lat'] = input("post.lat");
            MemberRouteExtendModel::create($extend);
        }
    }

}