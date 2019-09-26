<?php
/**
 * 客服模块
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-6-17
 * Time: 下午2:37
 */
namespace app\modules\content\controllers;


use app\libs\AdminController;
use app\modules\content\models\Role;
use app\modules\content\models\RoleFeedback;
use app\modules\content\models\Server;
use fecshop\services\Page;
use yii\data\Pagination;

class ServiceController extends  AdminController {
    public $enableCsrfValidation = false;
    public $layout = 'content';

    public function init(){
        parent::init();
        parent::setContentId('service');
    }
    public function actionIndex(){
        return $this->redirect('/content/index/index');
    }

    /**
     * 客服账号状态
     */
    public function actionServiceStatus(){
        $action = \Yii::$app->controller->action->id;
        parent::setActionId($action);
        $service = Role::find()->where("service = 1")->asArray()->all();
        return $this->render('service-status',['service'=>$service]);
    }

    /**
     * 用户反馈
     */
    public function actionRoleFeedback(){
        $action = \Yii::$app->controller->action->id;
        parent::setActionId($action);
        $beginTime = \Yii::$app->request->get('beginTime');
        $endTime = \Yii::$app->request->get('endTime');
        $server = \Yii::$app->request->get('serverId');
        $content = \Yii::$app->request->get('content');
        $where  = ' 1=1 ';
        if($beginTime){
            $begin = strtotime($beginTime);
            $where .= " and unix_timestamp(feedTime) >= $begin";
        }
        if($endTime){
            $end = strtotime($endTime) + 86399;
            $where .= " and unix_timestamp(feedTime) <= $end";
        }
        if($server){
            $where .= " and serverId = $server";
        }
        if($content){
            $where .= " and (  feedback like '%{$content}%'  or replyContent like '%{$content}%' )" ;
        }
//        var_dump($where);
        $count = RoleFeedback::find()->where($where)->count();
        $page = new Pagination(['totalCount'=>$count,'pageSize'=>20]);
        $data = RoleFeedback::find()->where($where)->offset($page->offset)->limit($page->limit)->asArray()->all();
        foreach($data as $k => $v){
            $data[$k]['replyName'] = Role::find()->where("id = {$v['replyId']}")->asArray()->one()['name'];
        }
        $servers = Server::getServers();
        return $this->render('role-feedback',['data'=>$data,'page'=>$page,'count'=>$count,'servers'=>$servers]);
    }
}