<?php
return [
    'adminEmail' => 'admin@example.com',
    'baseUrl' => '',
    'htmlUrl' => '/html',
    'upImage' => 'files/upload/image',
    'upSpoken' => 'files/upload/spoken/',
    'synchronousPush' => 'files/push/',
    'defaultImg' => '/cn/images/details_defaultImg.png',
    'timeLimit' => 600,
    'province' =>510000,//支付宝签名参数 省份
    'city' =>510100,//支付宝签名参数 城市
    'area' =>510101,//支付宝签名参数 地区
    'alipayAppid' => '982280b3587d4133912a8e9e47dc8f3b',//支付宝id
    'alipayKey' => 'c43eaf9e8e284fae94bc245326473d3e',//支付宝密钥
    'alipayNotify' => 'http://139.9.243.254/pay/api/alipay-notify',//支付宝回调地址
    'gameServerUrl' => 'http://139.9.238.82:8080',//游戏服务端通知地址
    'alipay_path' => dirname(__FILE__).'/../libs/yii2_alipay/',
    ];
