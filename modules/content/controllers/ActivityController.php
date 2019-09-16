<?php

/**
 * GM工具模块
 */
namespace app\modules\content\controllers;


use app\libs\AdminController;
use app\libs\Methods;
use app\modules\content\models\ActivityLog;
use app\modules\content\models\ActivityPush;
use app\modules\content\models\SscActivity;
use Yii;
use yii\data\Pagination;

class ActivityController  extends AdminController
{
    public $enableCsrfValidation = false;
    public $layout = 'content';

    public function init(){
        parent::init();
        parent::setContentId('activity');
    }
    public function actionIndex(){
        return $this->redirect('/content/index/index');
    }


    /**
     * cqssc
     *列表数据
     */
    public function actionSsc(){
        $action = Yii::$app->controller->action->id;
        parent::setActionId($action);
        $begin = Yii::$app->request->get('beginTime');
        $end = Yii::$app->request->get('endTime');
        $serverId = Yii::$app->request->get('serverId');
        $where = ' 1=1 ';
        if($begin){
            $beginTime = strtotime($begin);
            $where .= " and unix_timestamp(beginTime) >= $beginTime";
        }
        if($end){
            $endTime = strtotime($end) + 86399;
            $where .= " and unix_timestamp(endTime) <= $endTime";
        }
        if($serverId){
            $where .= " and serverId = $serverId";
        }
        $servers =[
            ['id'=>100,'name'=>'外服'],
            ['id'=>900,'name'=>'品鉴'],
            ['id'=>903,'name'=>'刘佳林'],
        ];
        $count = SscActivity::find()->where($where)->count();
        $pages = new Pagination(['totalCount'=>$count,'pageSize'=>20]);
        $data = SscActivity::find()->asArray()->where($where)->orderBy('id desc')->offset($pages->offset)->limit($pages->limit)->all();
        return $this->render('ssc',['data'=>$data,'servers'=>$servers]);
    }
    /**
     * cqssc
     * 添加
     */
    public function actionSscAdd(){
        if($_POST){
            $begin = Yii::$app->request->post('beginTime');
            $end = Yii::$app->request->post('endTime');
            $server = Yii::$app->request->post('server');
            $remark = Yii::$app->request->post('remark');
            if(!$begin || !$end){
                echo "<script>alert('开始结束时间必须都存在');setTimeout(function(){history.go(-1)},1000)</script>";die;
            }
            if(!$server){
                echo "<script>alert('必须选择区服');setTimeout(function(){history.go(-1)},1000)</script>";die;
            }
            $model = new SscActivity();
            $model->beginTime = $begin;
            $model->endTime = $end;
            $model->remark = $remark;
            $model->serverId = $server;
            $model->createTime = time();
            $res = $model->save();
            if($res){
                //通知服务端
                $command_content = ['beginTime'=>strtotime($begin),'endTime'=>strtotime($end)+86399];
//                $res = Methods::GmPost($command_content,903,6,4241);  4241 -时时彩 4242-活动推送
                Methods::GmFileGet($command_content,$server,6,4241);
                echo "<script>alert('添加成功');setTimeout(function(){location.href='ssc'},1000)</script>";die;
            }else{
                echo "<script>alert('添加失败');setTimeout(function(){history.go(-1)},1000)</script>";die;
            }
        }else{
            $servers =[
                ['id'=>100,'name'=>'外服'],
                ['id'=>900,'name'=>'品鉴'],
                ['id'=>903,'name'=>'刘佳林'],
            ];
            return $this->render('ssc-add',['servers'=>$servers]);
        }
    }
    /**
     * 活动推送及奖励
     * 添加
     */
    public function actionActivityPush(){
        $action = Yii::$app->controller->action->id;
        parent::setActionId($action);
        $servers =[
            ['id'=>100,'name'=>'外服'],
            ['id'=>900,'name'=>'品鉴'],
            ['id'=>903,'name'=>'刘佳林'],
        ];
        if($_POST){
            $serverId = Yii::$app->request->post('server');//区服id
            $remark = Yii::$app->request->post('remark');//活动说明
            $type = Yii::$app->request->post('type');//活动类型
            $beginTime = Yii::$app->request->post('beginTime');
            $endTime = Yii::$app->request->post('endTime');
            $conditions = Yii::$app->request->post('liConditions');//道具领取条件
            $propIds = Yii::$app->request->post('propIds');//道具id
            $numbers = Yii::$app->request->post('numbers');//道具数量
            $binds = Yii::$app->request->post('binds');//绑定状态
            if(!$serverId){
                echo "<script>alert('请选择区服');setTimeout(function(){history.go(-1);},1000)</script>";die;
            }
            if(!$remark){
                echo "<script>alert('请选择活动说明');setTimeout(function(){history.go(-1);},1000)</script>";die;
            }
            if(!$type){
                echo "<script>alert('请填写活动类型');setTimeout(function(){history.go(-1);},1000)</script>";die;
            }
            if(!$beginTime){
                echo "<script>alert('请选择开始时间');setTimeout(function(){history.go(-1);},1000)</script>";die;
            }
            if(!$endTime){
                echo "<script>alert('请选择截止时间');setTimeout(function(){history.go(-1);},1000)</script>";die;
            }
            if((count($conditions) == count($propIds)) == (count($numbers) == count($binds)) && count($conditions) > 0){
                $pushContent = ['condition'=>$conditions,'propId'=>$propIds,'number'=>$numbers,'bind'=>$binds];
                $pushContent = json_encode($pushContent);
            }else{
                echo "<script>alert('发放物品数据不正确');setTimeout(function(){history.go(-1);},1000)</script>";die;
            }
            $model = new ActivityPush();
            $model->serverId = $serverId;
            $model->type = $type;
            $model->beginTime = $beginTime;
            $model->endTime = $endTime;
            $model->pushContent = $pushContent;
            $model->createTime = time();
            $re = $model->save();
            if($re){
                //活动日志记录
                $remark = '添加'.$remark.'活动';
                ActivityLog::logAdd($remark,$model->id,1);//1-活动推送 2-五行运势
                //推送服务端 4242-活动推送
                Methods::GmFileGet(['activityId'=>$model->id],$serverId,6,4242);
                echo "<script>alert('添加成功');setTimeout(function(){window.location.href='activity-push';},1000)</script>";die;
            }else{
                echo "<script>alert('添加失败，请重试');setTimeout(function(){history.go(-1);},1000)</script>";die;
            }
        }
        return $this->render('activity-push',['servers'=>$servers]);
    }
    /**
     * 活动推送列表
     */
    public function actionActivityPushList(){
        $action = Yii::$app->controller->action->id;
        parent::setActionId($action);
        $count = ActivityPush::find()->count();
        $page = new Pagination(['totalCount'=>$count]);
        $data = ActivityPush::find()->offset($page->offset)->limit($page->limit)->asArray()->all();
        return $this->render('activity-push-list',['data'=>$data]);
    }
    /**
     * 活动推送列表
     * 删除
     */

}