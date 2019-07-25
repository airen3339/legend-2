<?php

namespace app\controllers;

use Yii;
use app\libs\ApiControl;


class LoginController extends ApiControl
{
    public function actionIndex()
    {
      
require('./../vendor/autoload.php');
require('./../libs/UserInfo.php');
require('./../libs/GPBMetadata/User.php');

$pbUserInfo = new UserInfo();
$pbUserInfo->setId(1001);
$pbUserInfo->setName('jack');
$str = $pbUserInfo->serializeToString(); 
var_dump($str);die;
}
}
