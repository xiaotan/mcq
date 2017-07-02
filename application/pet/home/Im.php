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

namespace app\pet\home;

use GatewayClient\Gateway;
use app\pet\model\Face as FaceModel;
use app\pet\model\Member as MemberModel;
use app\pet\model\MemberMessage as MemberMessageModel;
use think\Session;
/**
 * 前台首页控制器
 * @package app\pet\admin
 */
class Im extends Common
{
    /**
     * 初始化方法
     * @author 蔡伟明 <314013107@qq.com>
     */
    protected function _initialize()
    {
        parent::_initialize();
        // 判断用户是否登录
        $this->MemberModel = new MemberModel;
        $this->member_id = $this->MemberModel->isLogin();
        if(!$this->member_id){
            $this->error('请先授权登录');
            // do_wxlogin();
        }
    }

    /**
     * 首页
     * @author 蔡伟明 <314013107@qq.com>
     * @return mixed
     */
    public function index($mid=0)
    {
    	if(!$mid){
    		$this->error('用户不存在');
    	}
        //判断对话用户是否存在
        $member = MemberModel::where(array("id"=>$mid))->find();
        if(!$member){
            $this->error('用户不存在');
        }
        $message = MemberMessageModel::where("(from_mid=".$this->member_id." and to_mid=".$mid.") or (from_mid=".$mid." and to_mid=".$this->member_id.")")->order("create_time desc")->select();
        // echo MemberMessageModel::getLastSql();exit;
        $result = [];
        if($message){
            $result = $this->handleMessage($message);
        }
        //获取表情列表
        $face = FaceModel::where(array("status"=>1))->order("sort desc,create_time asc")->select();

        $this->assign('mid', $mid);
        $this->assign('face', $face);
        $this->assign('member', $member);
        $this->assign('result', json_encode($result));
        return $this->fetch(); // 渲染模板
    }

    public function bind(){
    	if(session("?member_auth.member_id")){
    		$client_id = input("post.client_id");
	        // 设置GatewayWorker服务的Register服务ip和端口，请根据实际情况改成实际值
	        Gateway::$registerAddress = '127.0.0.1:1238';
	        // client_id与uid绑定
	        Gateway::bindUid($client_id, session("member_auth.member_id"));
	        MemberModel::where(array("id"=>session("member_auth.member_id")))->update(array("online"=>1, "client_id"=>$client_id));
    	}
    }

    private function sendMessage($mid=0, $msg=''){
        if(empty($mid) || empty($msg)){
            return false;
        }
        //判断用户是否在线
        $member = MemberModel::where(array("id"=>$mid))->find();
        $online = Gateway::isOnline($member['client_id']);
        if(!$online){
            return false;
        }
        Gateway::$registerAddress = '127.0.0.1:1238';
        $return['type'] = 'sendMessage';
        $return['msg'] = $msg;
        // 向指定用户发送数据
        Gateway::sendToUid($mid, json_encode($return));
    }

    public function ajaxSendMessage($mid='', $message=''){
        if(empty($mid) || empty($message)){
            $return['code'] = 0;
            $return['info'] = "消息不能为空";
            echo json_encode($return);exit;
        }
        //处理消息中的表情
        $face = FaceModel::where(array("status"=>1))->select();
        foreach($face as $v){
            $icon = '<img alt="'.$v['title'].'" title="'.$v['title'].'" src="'.get_file_path($v['icon']).'">';
            $message = str_replace($v['title'],$icon,$message);
        }
        $input['to_mid'] = $mid;
        $input['from_mid'] = $this->member_id;
        $input['message'] = $message;
        $data = MemberMessageModel::create($input);
        if($data){
            $msg = $this->handleMessage(array($data->toArray()));
            $sendmsg = $msg;
            //发送的信息要交换位置
            foreach($msg as $k=>$v){
                $sendmsg[$k]['position'] = $v['position']==1?2:1;
            }
            //发送数据
            $this->sendMessage($mid, $sendmsg);
            $return['code'] = 1;
            $return['info'] = $msg;
            echo json_encode($return);exit;
        }else{
            $return['code'] = 0;
            $return['info'] = "发送失败";
            echo json_encode($return);exit;
        }
    }

    public function ajaxSendImage($mid='', $image=''){
        if(empty($mid) || empty($image)){
            $return['code'] = 0;
            $return['info'] = "消息不能为空";
            echo json_encode($return);exit;
        }
        $input['to_mid'] = $mid;
        $input['from_mid'] = $this->member_id;
        $input['image'] = $image;
        $input['message'] = '';
        $data = MemberMessageModel::create($input);
        if($data){
            $msg = $this->handleMessage(array($data->toArray()));
            $sendmsg = $msg;
            //发送的信息要交换位置
            foreach($msg as $k=>$v){
                $sendmsg[$k]['position'] = $v['position']==1?2:1;
            }
            //发送数据
            $this->sendMessage($mid, $sendmsg);
            $return['code'] = 1;
            $return['info'] = $msg;
            echo json_encode($return);exit;
        }else{
            $return['code'] = 0;
            $return['info'] = "发送失败";
            echo json_encode($return);exit;
        }
    }

    public function ajaxSendAudio(){

    }

    public function ajaxhandleVideo(){

    }

    private function handleMessage($message){
        $result = [];
        $message = array_reverse($message);
        foreach($message as $k=>$v){
            //如果当前信息较上条信息相差N分钟。则显示时间标志
            $result[$k]['time_tip'] = '';
            if($k>0){
                if(($v['create_time']-config('message_time')*60)>$message[$k-1]['create_time']){
                    $result[$k]['time_tip'] = time_tran($v['create_time']);
                }
            }
            //对话信息的位置（左1和右2）
            $result[$k]['position'] = 1;
            if($v['from_mid']==$this->member_id){
                $result[$k]['position'] = 2;
            }
            $result[$k]['from_mid'] = $v['from_mid'];
            $result[$k]['from_avatar'] = get_member_avatar($v['from_mid']);
            $result[$k]['from_name'] = get_member_name($v['from_mid']);
            $result[$k]['to_mid'] = $v['to_mid'];
            $result[$k]['to_avatar'] = get_member_avatar($v['to_mid']);
            $result[$k]['to_name'] = get_member_name($v['to_mid']);
            $result[$k]['image'] = isset($v['image']) && $v['image']?get_file_path($v['image']):'';
            $result[$k]['video'] = isset($v['video']) && $v['video']?get_file_path($v['video']):'';
            $result[$k]['audio'] = isset($v['audio']) && $v['audio']?get_file_path($v['audio']):'';
            $result[$k]['message'] = $v['message']?$v['message']:'';
            $result[$k]['create_time'] = $v['create_time'];
        }
        // print_r($result);exit;
        return $result;
    }


}