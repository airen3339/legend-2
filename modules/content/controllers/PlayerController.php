<?php

/**
 * 玩家相关模块
 */
namespace app\modules\content\controllers;


use app\libs\AdminController;
use app\modules\content\models\ChargeMoney;
use app\modules\content\models\Player;
use app\modules\content\models\User;
use app\modules\pay\models\Recharge;
use yii\data\Pagination;

class PlayerController  extends AdminController
{
    public $enableCsrfValidation = false;
    public $layout = 'content';

    public function init(){
        parent::init();
        parent::setContentId('player');
    }
    public function actionIndex(){
        return $this->redirect('/content/index/index');
    }
    /**
     * 角色信息
     */
    public function actionRoleInformation(){
        $action = \Yii::$app->controller->action->id;
        parent::setActionId($action);
        $service = \Yii::$app->request->get('service');
        $roleId = \Yii::$app->request->get('roleId');
        $page = \Yii::$app->request->get('page',1);
        $where = ' 1=1 ';
        if($service){
            $where .= " and u.WorldID = '{$service}'";
        }
        if($roleId){
            $where .= " and p.RoleID = '{$roleId}' ";
        }
        $sql = "select p.RoleID,p.UserID,p.LastLogin,p.CreateDate,u.PackageFlag,u.WorldID,p.Name from `user` u inner join player p on p.UserID = u.UserID where $where";
        $count = \Yii::$app->db2->createCommand($sql)->queryAll();
        $count = count($count);
        $limit = " limit ".(20*($page-1)).",20";
        $pages = new Pagination(['totalCount'=>$count,'pageSize'=>20]);
        $sql .= $limit;
        $user = \Yii::$app->db2->createCommand($sql)->queryAll();
        return $this->render('role-information',['user'=>$user,'page'=>$pages,'count'=>$count]);
    }
    /**
     * 详细信息
     */
    public function actionDetailInformation(){
        $action = \Yii::$app->controller->action->id;
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
        return $this->render('detail-information',['data'=>$data]);
    }
    /**
     * 订单查询
     */
    public function actionOrderQuery(){
        $action = \Yii::$app->controller->action->id;
        parent::setActionId($action);
        $service = \Yii::$app->request->get('server',0);
        $uid = \Yii::$app->request->get('uid');
        $order = \Yii::$app->request->get('order');
        $status = \Yii::$app->request->get('status',0);
        $where = ' 1=1 ';
        if($service){
            $where .= " and worldID = '{$service}'";
        }
        if($uid){
            $where .= " and roleID = '{$uid}' ";
        }
        if($order){
            $where .= " and orderid = '{$order}'";
        }
        if($status ==1){
            $where .= " and unix_timestamp(finishTime) > 0 ";
        }elseif($status ==2){
            $where .= " and unix_timestamp(finishTime) = 0 ";
        }
        $total = ChargeMoney::find()->where("$where")->count();
        $pages = new Pagination(['totalCount'=>$total,'pageSize'=>20]);
        $data = ChargeMoney::find()->where($where)->orderBy('createTime desc')->offset($pages->offset)->limit($pages->limit)->asArray()->all();
        foreach($data as $k => $v){
            $sql = "select u.Username,u.PackageFlag from `user` u inner join player p on p.UserID = u.UserID inner join chargemoney c on c.roleID = p.RoleID where c.roleID = '{$v['roleID']}' ";
            $da = \Yii::$app->db2->createCommand($sql)->queryOne();
            $data[$k]['channel'] = $da['Username'];
            $data[$k]['username'] = $da['PackageFlag'];
        }
        return $this->render('order-query',['data'=>$data,'page'=>$pages,'count'=>$total]);
    }
    /**
     * 货币消耗
     */
    public function actionMoneyUse(){
        $action = \Yii::$app->controller->action->id;
        parent::setActionId($action);
        $service = \Yii::$app->request->get('service');
        $moneyUse = \Yii::$app->request->get('moneyUse');
        $where = ' 1=1 ';
        if($service){
            $where .= " and service = '{$service}'";
        }
        if($moneyUse){
            $where .= " and use = $moneyUse ";
        }
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
        return $this->render('money-use',['data'=>$data]);
    }
    /**
     * 日志查询
     */
    public function actionLogQuery(){
        $action = \Yii::$app->controller->action->id;
        parent::setActionId($action);
        $beginTime = \Yii::$app->request->get('beginTime');
        $endTime = \Yii::$app->request->post('endTime');
        $service = \Yii::$app->request->get('service');
        $uid = \Yii::$app->request->get('uid');
        $attr = \Yii::$app->request->get('attr');
        $goods = \Yii::$app->request->get('goods');
        $count = \Yii::$app->request->get('count');
        $way = \Yii::$app->request->get('way');
        $where = ' 1=1 ';
        if($beginTime){
            $begin = strtotime($beginTime);
            $where .=  " and createTime >= $begin";
        }
        if($endTime){
            $end = strtotime($endTime) + 86399;
            $where .= " and createTime <= $end";
        }
        if($service){
            $where .= " and service = '{$service}'";
        }
        if($uid){
            $where .= " and uid = $uid ";
        }
        if($attr){
            $where .= " and attr = $attr ";
        }
        if($goods){
            $where .= " and attr = $goods ";
        }
        if($count){
            $where .= " and attr = $count ";
        }
        if($way){
            $where .= " and attr = $way ";
        }
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
        return $this->render('log-query',['data'=>$data]);
    }
}