<?php
/**
 * 登录管理
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-6-17
 * Time: 下午2:37
 */
namespace app\modules\content\controllers;

use app\modules\content\models\LoginLog;
use app\modules\content\models\Role;
use app\modules\content\models\User;
use yii;
use yii\web\Controller;

class LoginController extends  Controller{
    public $enableCsrfValidation = false;
    /**
     * 后台登录
     *
     */
    public function actionLogin(){
        return $this->renderPartial('login');
    }
    /**
     * 登录验证
     */
    public function actionCheckLogin(){
        $userName = Yii::$app->request->post("userName");
        $userPass = Yii::$app->request->post("userPass");
        $userName = trim($userName);
        $userPass = trim(md5(md5($userPass)));
        $res = Role::find()->where("name='{$userName}' and password = '{$userPass}'")->one();
        if($res){
            Yii::$app->session->set('adminId',$res->id);
            Yii::$app->session->set('createPower',$res->createPower);
            return $this->redirect('/content/index/index');
        }else{
            echo '<script>alert("帐号或密码不正确");history.go(-1);</script>';
            exit;
        }
    }
    /**
     * 注销账户
     * @return string
     * */
    public function actionLoginOut()
    {
        $session    = Yii::$app->session;
        $session->removeAll();
        $session->remove('adminId');
        $this->redirect('/content/login/login');
    }

}