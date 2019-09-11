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
    'alipayAppid' => '982280b3587d4133912a8e9e47dc8f3b',
    'alipayKey' => 'c43eaf9e8e284fae94bc245326473d3e',
    'alipayNotify' => 'http://192.168.0.13:8080/pay/api/alipay-notify',
    'alipay_path' => dirname(__FILE__).'/../libs/yii2_alipay/',
    ];
