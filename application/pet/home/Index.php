<?php

namespace app\pet\home;

use app\pet\model\Member as MemberModel;
use app\cms\model\Slider as SliderModel;
use app\pet\model\Business as BusinessModel;
use app\pet\model\BusinessCoupon as BusinessCouponModel;
use think\Validate;
use think\Db;

/**
 * 前台首页控制器
 * @package app\pet\admin
 */
class Index extends Common
{
    /**
     * 首页
     * @return mixed
     */
    public function index()
    {
        // echo session("member_auth.member_id");
        //在这里处理微信授权登录,先进行静默授权
        // do_wxlogin();
        // 跳转到萌宠圈
        $this->redirect('forum/index');
    }

    public function login($name = 'xiaotan'){
        $MemberModel = new MemberModel;
        $uid = $MemberModel->login($name, '159456');
        echo $uid.'---';
        print_r(session('member_auth'));
    }

    /**
     * 退出登录
     * @author 蔡伟明 <314013107@qq.com>
     */
    public function logout()
    {
        // session(null);
        session('member_auth', null);
        session('member_auth_sign', null);
        cookie('member_id', null);
        cookie('member_signin_token', null);

        return $this->redirect('index/index');
    }

    public function wxLogin(){
        // SDK实例对象
        $oauth = & load_wechat('Oauth');
        // 执行接口操作
        $result = $oauth->getOauthAccessToken();
        // print_r($result);exit;
        // 处理返回结果
        if($result===FALSE){
            return false;
        }else{
            if($result['scope'] == 'snsapi_base'){
                //先判断是否有用户
                $member = MemberModel::where(array("openid"=>$result['openid']))->find();
                if($member){
                    $this->dologinUser($member['username']);
                }else{
                    $user = & load_wechat('User');
                    $result = $user->getUserInfo($result['openid']);
                    if($result['subscribe']==0){
                        $result = $oauth->getOauthRedirect(url("pet/index/wxLogin", '', 'html', true), 'state', 'snsapi_userinfo');
                        if($result===FALSE){
                            return false;
                        }else{
                            header("Location: " . $result);
                            exit;
                        }
                    }else{
                        $this->loginUser($result);
                    }
                }
            }else{
                $result = $oauth->getOauthUserinfo($result['access_token'], $result['openid']);
                // 处理返回结果
                if($result===FALSE){
                    return false;
                }else{
                    $this->loginUser($result);
                }
            }
        }
    }

    //处理微信登录
    private function loginUser($info){
        //查询用户信息
        $member = MemberModel::where(array("openid"=>$info['openid']))->find();
        //openid判断是否存在用户,如果不存在，则先自动注册一个
        if($member){
            //用户已经存在，则进行登陆操作
            $this->dologinUser($member['username']);
        }else{
            //用户不存在，进行注册
            $input['openid'] = $info['openid'];
            $input['username'] = "mcq".time();
            $input['nickname'] = $info['nickname'];
            $input['sex'] = $info['sex'];
            $input['status'] = 1;
            $input['wx_avatar'] = $info['headimgurl'];
            $input['password'] = $info['openid'].'mcq159456';
            $result = MemberModel::create($input);
            //进行登陆操作
            if($result){
                $this->dologinUser($result['username']);
            }
        }
    }

    //登录操作
    private function dologinUser($username){
        //进行登陆操作
        $MemberModel = new MemberModel;
        $MemberModel->wxlogin($username);
        //重定向到原页面
        if(session("?back_url")){
            $this->redirect(session("back_url"));
        }
    }

    //ajax更新个人位置
    public function ajaxUpdateLocation(){
        if(session("?member_auth.member_id")){
            $lng = input("post.lng", '');
            $lat = input("post.lat", '');
            if($lng && $lat){
                MemberModel::where(array("id"=>session("member_auth.member_id")))->update(array("lng"=>$lng, "lat"=>$lat));
                session("member_".session("member_auth.member_id")."_lng", $lng);
                session("member_".session("member_auth.member_id")."_lat", $lat);
                echo 1;
            }
        }
    }
}