<?php

/**
 * GM工具模块
 */
namespace app\modules\content\controllers;


use app\libs\AdminController;
use app\libs\Methods;
use app\modules\content\models\ActivityLog;
use app\modules\content\models\LTV;
use app\modules\content\models\Notice;
use app\modules\content\models\Role;
use app\modules\content\models\SscActivity;
use Yii;
use yii\base\Controller;
use yii\data\Pagination;

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
        $serverId = Yii::$app->request->get('server',0);
        $type = Yii::$app->request->get('type');//1-首页公告
        $where = " 1 = 1 ";
        if($serverId){
            $where .= " and serverId = $serverId";
        }
        if($type){
            $where .= " and type = $type";
        }
        $count = Notice::find()->where($where)->count();
        $page = new Pagination(['totalCount'=>$count]);
        $data = Notice::find()->where($where)->orderBy('id desc')->offset($page->offset)->limit($page->limit)->asArray()->all();
        foreach($data as $k => $v){
            $data[$k]['createName'] = Role::find()->where("id = {$v['creator']}")->asArray()->one()['name'];
        }
        $servers = LTV::getServers();
        return $this->render('notice-query',['data'=>$data,'count'=>$count,'page'=>$page,'servers'=>$servers]);
    }
    /**
     * 首页公告
     */
    public function actionIndexNotice(){
        $action = Yii::$app->controller->action->id;
        parent::setActionId($action);
        if($_POST){
            $id = Yii::$app->request->post('id',0);
            $beginTime = Yii::$app->request->post('beginTime');
            $endTime = Yii::$app->request->post('endTime');
            $content = Yii::$app->request->post('content');
            if(!$content){
                echo "<script>alert('请填写公告内容');setTimeout(function(){history.go(-1);},1000)</script>";die;
            }
            if($id){
                $model = Notice::findOne($id);
                $remark = '修改首页公告';
            }else{
                $remark = '添加首页公告';
                $model = new Notice();
            }
            $model->content = $content;
            $model->beginTime = $beginTime;
            $model->endTime = $endTime;
            $model->creator = Yii::$app->session->get('adminId');
            $model->type = 1;//1-首页公告
            $model->createTime = time();
            $res = $model->save();
            if($res){
                ActivityLog::logAdd($remark,$model->id,3);
                //推送服务端
//                Methods::GmFileGet($content,0,6,4243);
                echo "<script>alert('操作成功');setTimeout(function(){location.href='notice-query';},1000)</script>";die;
            }else{
                echo "<script>alert('添加失败，请重试');setTimeout(function(){history.go(-1);},1000)</script>";die;
            }
        }else{
            $id = Yii::$app->request->get('id',0);
            if($id){
                $notice = Notice::find()->where("id = $id")->asArray()->one();
                $data = ['notice'=>$notice];
            }else{
                $data  = [];
            }
            return $this->render('index-notice',$data);
        }
    }
    /**
     * 公告删除
     */
    public function actionNoticeDelete(){
        $id = Yii::$app->request->get('id');
        if($id){
            $res = Notice::deleteAll("id = $id");
            if($res ){
                ActivityLog::logAdd('删除首页公告',$id,3);
                echo "<script>alert('删除成功');setTimeout(function(){location.href='notice-query';},1000)</script>";die;
            }else{
                echo "<script>alert('操作失败，请重试');setTimeout(function(){history.go(-1);},1000)</script>";die;
            }
        }else{
            echo "<script>alert('操作失败，请重试');setTimeout(function(){history.go(-1);},1000)</script>";die;
        }
    }
}