<?php

namespace app\pet\home;

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
    	$points = [
    		["lng"=>'116.399', "lat"=>'39.910'],
    		["lng"=>'116.405', "lat"=>'39.920'],
    		["lng"=>'116.423493', "lat"=>'39.907445'],
    	];
    	$this->assign("tab", 3);
    	$this->assign("points", json_encode($points));
        return $this->fetch(); // 渲染模板
    }
}