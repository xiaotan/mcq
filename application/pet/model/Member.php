<?php

namespace app\pet\model;

use think\Db;
use think\helper\Hash;
use think\Model as ThinkModel;

/**
 * 会员模型
 * @package app\pet\model
 */
class Member extends ThinkModel
{
    // 设置当前模型对应的完整数据表名称
    protected $table = '__PET_MEMBER__';

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    // 对密码进行加密
    public function setPasswordAttr($value)
    {
        return Hash::make((string)$value);
    }

    /**
     * 用户登录
     * @param string $username 用户名
     * @param string $password 密码
     * @param bool $rememberme 记住登录
     * @return bool|mixed
     */
    public function login($username = '', $password = '', $rememberme = true)
    {
        $username = trim($username);
        $password = trim($password);

        // 匹配登录方式
        if (preg_match("/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/", $username)) {
            // 邮箱登录
            $map['email'] = $username;
        } elseif (preg_match("/^1\d{10}$/", $username)) {
            // 手机号登录
            $map['mobile'] = $username;
        } else {
            // 用户名登录
            $map['username'] = $username;
        }

        $map['status'] = 1;

        // 查找用户
        $member = $this::get($map);
        if (!$member) {
            $this->error = '用户不存在或被禁用！';
        } else {
            if (!Hash::check((string)$password, $member->password)) {
                $this->error = '密码错误！';
            } else {
                $member_id = $member->id;

                // 更新登录信息
                $member->last_login_time = request()->time();
                $member->last_login_ip   = get_client_ip(1);
                if ($member->save()) {
                    // 自动登录
                    return $this->autoLogin($this::get($member_id), $rememberme);
                } else {
                    // 更新登录信息失败
                    $this->error = '登录信息更新失败，请重新登录！';
                    return false;
                }
            }
        }
        return false;
    }

    /**
     * 自动登录
     * @param object $member 用户对象
     * @param bool $rememberme 是否记住登录，默认7天
     * @return bool|int
     */
    public function autoLogin($member, $rememberme = false)
    {
        // 记录登录SESSION和COOKIES
        $auth = array(
            'member_id'             => $member->id,
            'avatar'          => $member->avatar,
            'username'        => $member->username,
            'nickname'        => $member->nickname,
            'money'           => $member->money,
            'score'           => $member->score,
            'last_login_time' => $member->last_login_time,
            'last_login_ip'   => get_client_ip(1),
        );
        session('member_auth', $auth);
        session('member_auth_sign', $this->dataAuthSign($auth));

        // 记住登录
        if ($rememberme) {
            $signin_token = $member->username.$member->id.$member->last_login_time;
            cookie('member_id', $member->id, 24 * 3600 * 7);
            cookie('member_signin_token', $this->dataAuthSign($signin_token), 24 * 3600 * 7);
        }

        return $member->id;
    }

    /**
     * 数据签名认证
     * @param array $data 被认证的数据
     * @return string 签名
     */
    public function dataAuthSign($data = [])
    {
        // 数据类型检测
        if(!is_array($data)){
            $data = (array)$data;
        }

        // 排序
        ksort($data);
        // url编码并生成query字符串
        $code = http_build_query($data);
        // 生成签名
        $sign = sha1($code);
        return $sign;
    }

    /**
     * 判断是否登录
     * @return int 0或用户id
     */
    public function isLogin()
    {
        $member = session('member_auth');
        if (empty($member)) {
            // 判断是否记住登录
            if (cookie('?member_id') && cookie('?member_signin_token')) {
                $member = $this::get(cookie('member_id'));
                if ($member) {
                    $member_signin_token = $this->dataAuthSign($member->username.$member->id.$member->last_login_time);
                    if (cookie('member_signin_token') == $member_signin_token) {
                        // 自动登录
                        $this->autoLogin($member, true);
                        return $member->id;
                    }
                }
            };
            return 0;
        }else{
            return session('member_auth_sign') == $this->dataAuthSign($member) ? $member['member_id'] : 0;
        }
    }
}