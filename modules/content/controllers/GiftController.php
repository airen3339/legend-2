<?php

/**
 * 礼包管理模块
 */
namespace app\modules\content\controllers;


use app\libs\AdminController;
use Yii;

class GiftController  extends AdminController
{
    public $enableCsrfValidation = false;
    public $layout = 'content';

    public function init(){
        parent::init();
        parent::setContentId('gift');
    }
    public function actionIndex(){
        return $this->redirect('/content/index/index');
    }
    /**
     * 配置礼包
     */
    public function actionConfigGift(){
        $action = Yii::$app->controller->action->id;
        parent::setActionId($action);
        if($_POST){
            $beginTime = Yii::$app->request->post('beginTime');
            $endTime = Yii::$app->request->post('endTime');
            $content = Yii::$app->request->post('content');
        }else{
            return $this->render('config-gift',[]);
        }
    }
    /**
     * 激活码列表
     */
    public function actionCodeList(){
        $action = Yii::$app->controller->action->id;
        parent::setActionId($action);
        $data = [
            ['id'=>1,'name'=>'cc','createPower'=>0,'catalog'=>'dd'],
            ['id'=>1,'name'=>'cc','createPower'=>0,'catalog'=>'dd'],
            ['id'=>1,'name'=>'cc','createPower'=>0,'catalog'=>'dd'],
            ['id'=>1,'name'=>'cc','createPower'=>0,'catalog'=>'dd'],
            ['id'=>1,'name'=>'cc','createPower'=>0,'catalog'=>'dd'],
            ['id'=>1,'name'=>'cc','createPower'=>0,'catalog'=>'dd'],
            ['id'=>1,'name'=>'cc','createPower'=>0,'catalog'=>'dd'],
            ['id'=>1,'name'=>'cc','createPower'=>0,'catalog'=>'dd'],
            ['id'=>1,'name'=>'cc','createPower'=>0,'catalog'=>'dd'],
            ['id'=>1,'name'=>'cc','createPower'=>0,'catalog'=>'dd'],
        ];
        return $this->render('code-list',['data'=>$data]);
    }
    /**
     * 礼包激活数据
     */
    public function actionGiftActivationData(){
        $action = Yii::$app->controller->action->id;
        parent::setActionId($action);
        $data = [
            ['id'=>1,'name'=>'cc','createPower'=>0,'catalog'=>'dd'],
            ['id'=>1,'name'=>'cc','createPower'=>0,'catalog'=>'dd'],
            ['id'=>1,'name'=>'cc','createPower'=>0,'catalog'=>'dd'],
            ['id'=>1,'name'=>'cc','createPower'=>0,'catalog'=>'dd'],
            ['id'=>1,'name'=>'cc','createPower'=>0,'catalog'=>'dd'],
            ['id'=>1,'name'=>'cc','createPower'=>0,'catalog'=>'dd'],
            ['id'=>1,'name'=>'cc','createPower'=>0,'catalog'=>'dd'],
            ['id'=>1,'name'=>'cc','createPower'=>0,'catalog'=>'dd'],
            ['id'=>1,'name'=>'cc','createPower'=>0,'catalog'=>'dd'],
            ['id'=>1,'name'=>'cc','createPower'=>0,'catalog'=>'dd'],
        ];
        return $this->render('gift-activation-data',['data'=>$data]);
    }
}