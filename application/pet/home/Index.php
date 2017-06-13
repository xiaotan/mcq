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
        // echo date('ymdH') . rand(1000, 9999);exit;
        // print_r(get_avatar());exit;
        //在这里处理微信授权登录,先进行静默授权
        // do_wxlogin();

    	// 商家列表页滚动图
        $slider = SliderModel::where(array("typeid"=>1, "status"=>1))->order("sort asc")->select();

        // 获取最近用户的商家列表
        $business = $this->getBusinessList();

        $this->assign('tab', 2);
        $this->assign('slider', $slider);
        $this->assign('business', json_encode($business));
        return $this->fetch(); // 渲染模板
    }

    // ajax关键字搜索商家
    public function ajaxSearchKeyword(){
    	$keyword = input('get.keyword/s');
    	if($keyword){
    		// 用户经纬度
	        $lng = "108.354863";
	        $lat = "22.831772";
    		// 根据用户经纬度获取最接近用户的商家
	        $data = Db::query("SELECT id,name,score,thumb, ROUND(6378.138*2*ASIN(SQRT(POW(SIN((".$lat."*PI()/180-lat*PI()/180)/2),2)+COS(".$lat."*PI()/180)*COS(lat*PI()/180)*POW(SIN((".$lng."*PI()/180-lng*PI()/180)/2),2)))*1000) AS distance FROM ".config("database.prefix")."pet_business where status=1 and tag like '%".$keyword."%' or name like '%".$keyword."%' ORDER BY distance ASC,score desc");
	        if($data){
	        	//获取缩略图url
		        foreach($data as $k=>$v){
		        	$data[$k]['thumb'] = get_file_path($v['thumb']);
		        	$data[$k]['distance'] = round($v['distance']/1000 ,2);
                    $data[$k]['url'] = url("business/index",array('id'=>$v['id']));
                    $data[$k]['coupon'] = BusinessCouponModel::where(array("status"=>1,'bid'=>$v['id'],'begin_time'=>['<',time()],'end_time'=>['>',time()]))->order("create_time asc")->value('title');
		        }
	        }
	        echo $data ? json_encode($data) : 1;
    	}
    }

    // ajax分页搜索商家
    public function ajaxGetBusinessList(){
    	$page = input('get.page/d', 1);
    	$data = $this->getBusinessList($page);
    	echo $data ? json_encode($data) : 1;
    }

    /**
     * 获取离用户最近的商家列表
     * @param  number  $page     分页数
     * @param  integer $pageSize 分页步长
     * @return array             商家列表
     */
    private function getBusinessList($page = 1, $pageSize = 10, $cache = false){
    	// 用户经纬度
        $lng = "108.354863";
        $lat = "22.831772";
        $business = array();
        // 做session缓存，防止多次查询数据库，需在用户重新定位时清除该缓存
        if(session('business'.$page) && $cache){
        	$business = session('business'.$page);
        }else{
        	// 根据用户经纬度获取最接近用户的商家
	        $business = Db::query("SELECT id,name,score,thumb, ROUND(6378.138*2*ASIN(SQRT(POW(SIN((".$lat."*PI()/180-lat*PI()/180)/2),2)+COS(".$lat."*PI()/180)*COS(lat*PI()/180)*POW(SIN((".$lng."*PI()/180-lng*PI()/180)/2),2)))*1000) AS distance FROM ".config("database.prefix")."pet_business where status=1 ORDER BY distance ASC,score desc LIMIT ".($page-1)*$pageSize.",".$pageSize);
	        if($business){
		        foreach($business as $k=>$v){
		        	$business[$k]['thumb'] = get_file_path($v['thumb']);
		        	$business[$k]['distance'] = round($v['distance']/1000 ,2);
                    $business[$k]['url'] = url("business/index",array('id'=>$v['id']));
                    $business[$k]['coupon'] = BusinessCouponModel::where(array("status"=>1,'bid'=>$v['id'],'begin_time'=>['<',time()],'end_time'=>['>',time()]))->order("create_time asc")->value('title');
		        }
		        session('business'.$page, $business);
	        }
        }
	    return $business;
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

    public function im(){
        return $this->fetch(); // 渲染模板
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
}