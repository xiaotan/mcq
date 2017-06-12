<?php 

return [
    'token'          => 'mcq', //填写你设定的token
    'appid'          => 'wx65d4b406d7b6056d', //填写高级调用功能的app id, 请在微信开发模式后台查询
    'appsecret'      => '5ec3a8733cbad395468c8ee8b5106d83', //填写高级调用功能的密钥
    'encodingaeskey' => '', //填写加密用的EncodingAESKey（可选，接口传输选择加密时必需）
    'mch_id'         => '1480183502',  //微信支付，商户ID（可选）
    'partnerkey'     => 'yXd4a7NskSYo2i5bUDWUEfKaIBEwPyR8',  //微信支付，密钥（可选）
    'ssl_cer'        => '', //微信支付，双向证书（可选，操作退款或打款时必需）
    'ssl_key'        => '',  //微信支付，双向证书（可选，操作退款或打款时必需）
    'cachepath'      => '', //设置SDK缓存目录（可选，默认位置在./Wechat/Cache下，请保证写权限）
];
