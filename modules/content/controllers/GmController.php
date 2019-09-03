<?php

/**
 * GM工具模块
 */
namespace app\modules\content\controllers;


use app\libs\AdminController;
use app\libs\Methods;
use app\modules\content\models\SscActivity;
use Yii;
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
    /**
     * cqssc
     *列表数据
     */
    public function actionSsc(){
        $action = Yii::$app->controller->action->id;
        parent::setActionId($action);
        $begin = Yii::$app->request->get('beginTime');
        $end = Yii::$app->request->post('endTime');
        $where = ' 1=1 ';
        if($begin){
            $beginTime = strtotime($begin);
            $where .= " and unix_timestamp(beginTime) >= $beginTime";
        }
        if($end){
            $endTime = strtotime($end) + 86399;
            $where .= " and unix_timestamp(endTime) <= $endTime";
        }
        $count = SscActivity::find()->where($where)->count();
        $pages = new Pagination(['totalCount'=>$count,'pageSize'=>20]);
        $data = SscActivity::find()->asArray()->where($where)->orderBy('id desc')->offset($pages->offset)->limit($pages->limit)->all();
        return $this->render('ssc',['data'=>$data]);
    }
    /**
     * cqssc
     * 添加
     */
    public function actionSscAdd(){
        if($_POST){
            $begin = Yii::$app->request->post('beginTime');
            $end = Yii::$app->request->post('endTime');
            $remark = Yii::$app->request->post('remark');
            if(!$begin || !$end){
                echo "<script>alert('开始结束时间必须都存在');setTimeout(function(){history.go(-1)},1000)</script>";die;
            }
            $model = new SscActivity();
            $model->beginTime = $begin;
            $model->endTime = $end;
            $model->remark = $remark;
            $model->createTime = time();
            $res = $model->save();
            if($res){
                //通知服务端
                $command_content = ['beginTime'=>strtotime($begin),'end'=>strtotime($end)+86399];
//                $res = Methods::GmPost($command_content,903,6,4241);
                Methods::GmFileGet($command_content,903,6,4241);
                echo "<script>alert('添加成功');setTimeout(function(){location.href='ssc'},1000)</script>";die;
            }else{
                echo "<script>alert('添加失败');setTimeout(function(){history.go(-1)},1000)</script>";die;
            }
        }else{
            return $this->render('ssc-add');
        }
    }
}