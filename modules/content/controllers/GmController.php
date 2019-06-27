<?php

/**
 * GM工具模块
 */
namespace app\modules\content\controllers;


use app\libs\AdminController;
use Yii;

class GmController  extends AdminController
{
    public $enableCsrfValidation = false;
    public $layout = 'content';

    public function init(){
        parent::init();
        parent::setContentId('gm');
    }
    public function actionIndex(){
        return $this->redirect('/content/index/index');
    }
    /**
     * 区服添加奖励
     */
    public function actionServiceAddReward(){
        $action = Yii::$app->controller->action->id;
        parent::setActionId($action);
        if($_POST){

        }else{
            return $this->render('service-add-reward',[]);
        }
    }
    /**
     * 玩家添加奖励
     */
    public function actionPlayerAddReward(){
        $action = Yii::$app->controller->action->id;
        parent::setActionId($action);
        if($_POST){

        }else{
            return $this->render('player-add-reward',[]);
        }
    }
    /**
     * 发奖操作记录
     */
    public function actionRewardRecord(){
        $action = Yii::$app->controller->action->id;
        parent::setActionId($action);
        $service = \Yii::$app->request->get('service');
        $uid = \Yii::$app->request->get('uid');
        $where = ' 1=1 ';
        if($service){
            $where .= " and service = '{$service}'";
        }
        if($uid){
            $where .= " and uid = $uid ";
            $data = ['id'=>1,'name'=>'cc','createPower'=>0,'catalog'=>'dd'];
        }else{
            $data = [];
        }
        return $this->render('reward-record',['data'=>$data]);
    }
    /**
     * 区服添加公告
     */
    public function actionServiceAddNotice(){
        $action = Yii::$app->controller->action->id;
        parent::setActionId($action);
        if($_POST){

        }else{
            return $this->render('service-add-notice',[]);
        }
    }
    /**
     * 公告查询
     */
    public function actionNoticeQuery(){
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
        return $this->render('notice-query',['data'=>$data]);
    }
    /**
     * 首页公告
     */
    public function actionIndexNotice(){
        $action = Yii::$app->controller->action->id;
        parent::setActionId($action);
        if($_POST){
            $beginTime = Yii::$app->request->post('beginTime');
            $endTime = Yii::$app->request->post('endTime');
            $content = Yii::$app->request->post('content');
        }else{
            return $this->render('index-notice',[]);
        }
    }
}