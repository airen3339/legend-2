<?php

/**
 * GM工具模块
 */
namespace app\modules\content\controllers;


use app\libs\AdminController;
use app\libs\Methods;
use app\modules\content\models\ActivityLog;
use app\modules\content\models\ActivityPush;
use app\modules\content\models\Role;
use app\modules\content\models\Server;
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
        $servers = Server::getServers();
        $count = SscActivity::find()->where($where)->count();
        $pages = new Pagination(['totalCount'=>$count,'pageSize'=>20]);
        $data = SscActivity::find()->asArray()->where($where)->orderBy('id desc')->offset($pages->offset)->limit($pages->limit)->all();
        return $this->render('ssc',['data'=>$data,'servers'=>$servers,'page'=>$pages,'count'=>$count]);
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
            $servers = Server::getServers();
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
        $servers = Server::getServers();
        if($_POST){
            $pushId = Yii::$app->request->post('pushId',0);
            $serverId = Yii::$app->request->post('server');//区服id
            $type = Yii::$app->request->post('type');//活动类型 1-每日单冲  2-累计充值 3-五行运势
            $beginTime = Yii::$app->request->post('beginTime');
            $endTime = Yii::$app->request->post('endTime');
            $conditions = Yii::$app->request->post('liConditions');//道具领取条件
            $propIds = Yii::$app->request->post('propIds');//道具id
            $numbers = Yii::$app->request->post('numbers');//道具数量
            $binds = Yii::$app->request->post('binds');//绑定状态
            if(!$serverId){
                echo "<script>alert('请选择区服');setTimeout(function(){history.go(-1);},1000)</script>";die;
            }
            if(!$type){
                echo "<script>alert('请填写活动类型');setTimeout(function(){history.go(-1);},1000)</script>";die;
            }else{
                if($type ==1){
                    $remark = '每日单充';
                }elseif($type ==2){
                    $remark = '累计消费';
                }elseif($type ==3){
                    $remark = '五行运势';
                }else{
                    $remark = '';
                }
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
            if($pushId){
                $model = ActivityPush::findOne($pushId);
            }else{
                $model = new ActivityPush();
            }
            $model->serverId = $serverId;
            $model->type = $type;
            $model->beginTime = $beginTime;
            $model->endTime = $endTime;
            $model->pushContent = $pushContent;
            $model->createTime = time();
            $model->remark = $remark;
            $model->operator = Yii::$app->session->get('adminId');//操作者
            $re = $model->save();
            if($re){
                //活动日志记录
                if($pushId){
                    $remark = '修改'.$remark.'活动';
                    $target = 'activity-push-list';
                }else{
                    $target = 'activity-push';
                    $remark = '添加'.$remark.'活动';
                }
                ActivityLog::logAdd($remark,$model->id,$type);//1-每日单充 2-累计消费 3-五行运势
                //推送服务端 4242-活动推送
                ActivityPush::pushActivity($serverId,$model->id,$type,$beginTime,$endTime,$pushContent);

                echo "<script>alert('添加成功');setTimeout(function(){window.location.href='$target';},1000)</script>";die;
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
        //获取区服
        $servers = Server::getServers();
        $server = Yii::$app->request->get('server');//区服
        $type = Yii::$app->request->get('type',0);//1-每日单充 2-累计充值 3-五行运势
        $where = ' 1 = 1 ';
        if($server){
            $where .= "  and serverId = $server ";
        }
        if($type){
            $where .= " and type  = '{$type}' ";
        }
        $count = ActivityPush::find()->where($where)->count();
        $page = new Pagination(['totalCount'=>$count]);
        $data = ActivityPush::find()->where($where)->orderBy('id desc')->offset($page->offset)->limit($page->limit)->asArray()->all();
        foreach($data as $k => $v){
            $data[$k]['operatorName'] = Role::find()->where("id = {$v['operator']}")->asArray()->one()['name'];
        }
        return $this->render('activity-push-list',['data'=>$data,'servers'=>$servers]);
    }
    /**
     * 活动推送列表
     * 删除
     */
    public function actionActivityPushDelete(){
        $id = Yii::$app->request->get('id');
        if($id){
            $activity = ActivityPush::findOne($id);
            $remark = $activity->remark;
            $res = ActivityPush::deleteAll("id = $id");
            if($res ){
                ActivityLog::logAdd('删除'.$remark.'活动',$id,1);
                echo "<script>alert('删除成功');setTimeout(function(){location.href='activity-push-list';},1000)</script>";die;
            }else{
                echo "<script>alert('操作失败，请重试');setTimeout(function(){history.go(-1);},1000)</script>";die;
            }
        }else{
            echo "<script>alert('操作失败，请重试');setTimeout(function(){history.go(-1);},1000)</script>";die;
        }
    }
    /**
     * 活动推送列表
     * 活动修改
     */
    public function actionActivityPushEdit(){
        $id = Yii::$app->request->get('id');
        $data = ActivityPush::find()->where("id = $id")->asArray()->one();
        $servers = Server::getServers();
        $data['pushContent'] = json_decode($data['pushContent'],true);
        return $this->render('activity-push-edit',['data'=>$data,'servers'=>$servers]);
    }
    /**
     * 五行运势活动列表
     */
    public function actionFiveActivity(){
        $action = Yii::$app->controller->action->id;
        parent::setActionId($action);
        //获取区服
        $servers = Server::getServers();
        $server = Yii::$app->request->get('server');//区服
        if($server){
            $where = " activityType = 2 and serverId = $server ";
        }else{
            $where = " activityType = 2 ";
        }
        $count = ActivityPush::find()->where($where)->count();
        $page = new Pagination(['totalCount'=>$count]);
        $data = ActivityPush::find()->where($where)->orderBy('id desc')->offset($page->offset)->limit($page->limit)->asArray()->all();
        foreach($data as $k => $v){
            $data[$k]['operatorName'] = Role::find()->where("id = {$v['operator']}")->asArray()->one()['name'];
        }
        return $this->render('five-activity',['data'=>$data,'servers'=>$servers]);
    }
    /**
     * 五行运势活动列表
     * 添加 修改
     */
    public function actionFiveActivityAdd()
    {
        $servers = Server::getServers();
        if ($_POST) {
            $pushId = Yii::$app->request->post('pushId', 0);
            $serverId = Yii::$app->request->post('server');//区服id
            $beginTime = Yii::$app->request->post('beginTime');
            $endTime = Yii::$app->request->post('endTime');
            $toolName = Yii::$app->request->post('toolName');//道具名称
            $toolId = Yii::$app->request->post('toolId');//道具id
            $numbers = Yii::$app->request->post('numbers');//道具数量
            if (!$serverId) {
                echo "<script>alert('请选择区服');setTimeout(function(){history.go(-1);},1000)</script>";
                die;
            }
            if (!$beginTime) {
                echo "<script>alert('请选择开始时间');setTimeout(function(){history.go(-1);},1000)</script>";
                die;
            }
            if (!$endTime) {
                echo "<script>alert('请选择截止时间');setTimeout(function(){history.go(-1);},1000)</script>";
                die;
            }
            if ((count($toolName) == count($toolId)) == (count($numbers) == count($toolName)) && count($toolName) > 0) {
                $pushContent = ['toolName' => $toolName, 'toolId' => $toolId, 'number' => $numbers];
                $pushContent = json_encode($pushContent);
            } else {
                echo "<script>alert('发放物品数据不正确');setTimeout(function(){history.go(-1);},1000)</script>";
                die;
            }
            if ($pushId) {
                $model = ActivityPush::findOne($pushId);
            } else {
                $model = new ActivityPush();
            }
            $model->serverId = $serverId;
            $model->type = '';
            $model->beginTime = $beginTime;
            $model->endTime = $endTime;
            $model->pushContent = $pushContent;
            $model->createTime = time();
            $model->remark = '五行运势活动';
            $model->activityType = 2;//1-活动推送 2-五行运势活动
            $model->operator = Yii::$app->session->get('adminId');//操作者
            $re = $model->save();
            if ($re) {
                //活动日志记录
                if ($pushId) {
                    $remark = '修改五行运势活动';
                } else {
                    $remark = '添加五行运势活动';
                }
                ActivityLog::logAdd($remark, $model->id, 2);//1-活动推送 2-五行运势
                //推送服务端 4242-活动推送
//                Methods::GmFileGet(['activityId' => $model->id], $serverId, 6, 4242);

                echo "<script>alert('添加成功');setTimeout(function(){window.location.href='five-activity';},1000)</script>";
                die;
            } else {
                echo "<script>alert('添加失败，请重试');setTimeout(function(){history.go(-1);},1000)</script>";
                die;
            }
        }else{
            $id = Yii::$app->request->get('id');
            if($id){
                $data = ActivityPush::find()->where("id = $id")->asArray()->one();
                $data['pushContent'] = json_decode($data['pushContent'],true);
            }else{
                $data =[];
            }
            return $this->render('five-activity-add',['data'=>$data,'servers'=>$servers]);
        }
    }
    /**
     * 五行运势活动
     * 删除
     */
    public function actionFiveActivityDelete(){
        $id = Yii::$app->request->get('id');
        if($id){
            $res = ActivityPush::deleteAll("id = $id");
            if($res ){
                ActivityLog::logAdd('删除五行运势活动',$id,2);
                echo "<script>alert('删除成功');setTimeout(function(){location.href='five-activity';},1000)</script>";die;
            }else{
                echo "<script>alert('操作失败，请重试');setTimeout(function(){history.go(-1);},1000)</script>";die;
            }
        }else{
            echo "<script>alert('操作失败，请重试');setTimeout(function(){history.go(-1);},1000)</script>";die;
        }
    }
    /**
     * 活动操作日志
     */
    public function actionActivityLog(){
        $action = Yii::$app->controller->action->id;
        parent::setActionId($action);
        $type = Yii::$app->request->get('type',0);//type 1-活动推送 2-五行运势
        $uid = Yii::$app->request->get('uid');
        $where = " type in (1,2,3) ";//1-活动推送 2-五行运势
        if($type){
            $where .= " and type = $type";
        }
        if($uid){
            $where .= " and operatorId = $uid";
        }
        $count = ActivityLog::find()->where($where)->count();
        $page = new Pagination(['totalCount'=>$count]);
        $data = ActivityLog::find()->where($where)->orderBy('id desc')->offset($page->offset)->limit($page->limit)->asArray()->all();
        return $this->render('activity-log',['data'=>$data,'page'=>$page,'count'=>$count]);
    }

}