<?php

/**
 * 运营数据模块
 */
namespace app\modules\content\controllers;


use app\libs\AdminController;
use app\libs\Chart;
use app\modules\content\models\Role;
use Yii;
use yii\data\Pagination;

class OperateController  extends AdminController
{
    public $enableCsrfValidation = false;
    public $layout = 'content';

    public function init(){
        parent::init();
        parent::setContentId('operate');
        require_once IndexDir.'/../libs/protobuf/out/ItemProtocol.php';
        require_once IndexDir.'/../libs/protobuf/out/PBItemGroup.php';
        require_once IndexDir.'/../libs/protobuf/out/PBItem.php';
        require_once IndexDir.'/../libs/protobuf/out/DigMineProtocol.php';
    }
    public function actionIndex(){
        return $this->redirect('/content/index/index');
    }
    public function actionTest1(){
        $strTest = Yii::$app->db2->createCommand("select * from digmine limit 0,1")->queryOne()['datas'];
        var_dump($strTest);
        $digMine = new \DigMineProtocol();
        $digMine->setExchangeCount(12);
        $digMine->setExchangeTime(300);
        $digMine->setOut(23);
        $digMine->setNew('false');
        $str = $digMine->serializeToString();
        var_dump($str);
        $sig = new \DigMineProtocol();
        $sig->mergeFromString($str);
        var_dump('Count:'.$sig->getExchangeCount().' Time:'.$sig->getExchangeTime().' Out:'.$sig->getOut().' New:'.$sig->getNew());die;
    }
    public function actionTest2(){
        $data = Yii::$app->db2->createCommand("select * from item")->queryAll();
        $str = $data[0]['datas'];
//        $group = new \PBItemGroup();
//        $group->setId(23);
//        $group->setCapacity('12');
//        $str = $group->serializeToString();
//        $role = Role::findOne(9);
//        $role->realPass = $str;
//        $role->save();
//        var_dump($str);echo '<br/>';
//        $strs = Role::findOne(9);
//        $strs = $strs->realPass;
//        var_dump($strs);
//        $strs = trim($strs);
        $group = new \PBItemGroup();
        $group->mergeFromString($str);
        var_dump('id:'.$group->getId().' capacity:'.$group->getCapacity());die;
        $item = new \ItemProtocol();
        $item->mergeFromString($str);
        var_dump($item->getGroups());die;
    }
    /**
     * 数据查询
     */
    public function actionDataQuery(){
        $action = Yii::$app->controller->action->id;
        parent::setActionId($action);
        $beginTime = Yii::$app->request->get('beginTime');
        $endTime = Yii::$app->request->post('endTime');
        $service = Yii::$app->request->get('service');
        $channel = Yii::$app->request->get('channel');
        $where = " 1=1 ";
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
        if($channel){
            $where .= " and channel = '{$channel}'";
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
        $count = 20;
        $page = new Pagination(['totalCount'=>$count,'pageSize'=>20]);
        return $this->render('data-query',['data'=>$data,'page'=>$page,'count'=>$count]);
    }
    /**
     * 等级分布
     */
    public function actionLevelList(){
        $action = Yii::$app->controller->action->id;
        parent::setActionId($action);
        $beginTime = Yii::$app->request->get('beginTime');
        $endTime = Yii::$app->request->post('endTime');
        $service = Yii::$app->request->get('service');
        $where = " 1=1 ";
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
        $count = 20;
        $page = new Pagination(['totalCount'=>$count,'pageSize'=>20]);
        return $this->render('level-list',['data'=>$data,'page'=>$page,'count'=>$count]);
    }
    /**
     * 留存数据
     */
    public function actionRetainData(){
        $action = Yii::$app->controller->action->id;
        parent::setActionId($action);
        $beginTime = Yii::$app->request->get('beginTime');
        $endTime = Yii::$app->request->post('endTime');
        $service = Yii::$app->request->get('service');
        $channel = Yii::$app->request->get('channel');
        $where = " 1=1 ";
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
        if($channel){
            $where .= " and channel = '{$channel}'";
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
        $count = 20;
        $page = new Pagination(['totalCount'=>$count,'pageSize'=>20]);
        return $this->render('data-query',['data'=>$data,'page'=>$page,'count'=>$count]);
    }
    /**
     * vip分布
     */
    public function actionVipList(){
        $action = Yii::$app->controller->action->id;
        parent::setActionId($action);
        $service = Yii::$app->request->get('service');
        $vipList = Yii::$app->request->get('vipList');
        $where = ' 1=1 ';
        if($service){
            $where .= " and service = '{$service}'";
        }
        if($vipList){
            $where .= " and vip = $vipList";
        }
        $data = [
            ['id'=>1,'name'=>'cc','createPower'=>0,'catalog'=>'dd'],
            ['id'=>1,'name'=>'cc','createPower'=>0,'catalog'=>'dd'],
            ['id'=>1,'name'=>'cc','createPower'=>0,'catalog'=>'dd'],
            ['id'=>1,'name'=>'cc','createPower'=>0,'catalog'=>'dd']
        ];
        return $this->render('vip-list',['data'=>$data]);
    }
    /**
     * 等级充值分布
     */
    public function actionLevelDepositList(){
        $action = Yii::$app->controller->action->id;
        parent::setActionId($action);
        $service = Yii::$app->request->get('service');
        $channel = Yii::$app->request->get('channel');
        $where = ' 1=1 ';
        if($service){
            $where .= " and service = '{$service}'";
        }
        if($channel){
            $where .= " and channel = $channel";
        }
        $data = [
            ['id'=>1,'name'=>'cc','createPower'=>0,'catalog'=>'dd'],
            ['id'=>1,'name'=>'cc','createPower'=>0,'catalog'=>'dd'],
            ['id'=>1,'name'=>'cc','createPower'=>0,'catalog'=>'dd'],
            ['id'=>1,'name'=>'cc','createPower'=>0,'catalog'=>'dd']
        ];
        return $this->render('level-deposit-list',['data'=>$data]);
    }
    /**
     * 充值排行查询
     */
    public function actionDepositRankQuery(){
        $action = Yii::$app->controller->action->id;
        parent::setActionId($action);
        $beginTime = Yii::$app->request->get('beginTime');
        $endTime = Yii::$app->request->post('endTime');
        $service = Yii::$app->request->get('service');
        $where = " 1=1 ";
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
        $data = [
            ['id'=>1,'name'=>'cc','createPower'=>0,'catalog'=>'dd'],
            ['id'=>1,'name'=>'cc','createPower'=>0,'catalog'=>'dd'],
            ['id'=>1,'name'=>'cc','createPower'=>0,'catalog'=>'dd'],
            ['id'=>1,'name'=>'cc','createPower'=>0,'catalog'=>'dd']
        ];
        return $this->render('deposit-rank-query',['data'=>$data]);
    }
    /**
     * LTV数据
     */
    public function actionLtvData(){
        $action = Yii::$app->controller->action->id;
        parent::setActionId($action);
        $beginTime = Yii::$app->request->get('beginTime');
        $endTime = Yii::$app->request->post('endTime');
        $service = Yii::$app->request->get('service');
        $channel = Yii::$app->request->get('channel');
        $ltv = Yii::$app->request->get('ltv');
        $where = " 1=1 ";
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
        if($channel){
            $where .= " and channel = '{$channel}'";
        }
        if($ltv){
            $where .= " and ltv = '{$ltv}'";
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
        $count = 20;
        $page = new Pagination(['totalCount'=>$count,'pageSize'=>20]);
        return $this->render('ltv-data',['data'=>$data,'page'=>$page,'count'=>$count]);
    }
    /**
     * 登录在线分布
     */
    public function actionLoginOnlineList(){
        $action = Yii::$app->controller->action->id;
        parent::setActionId($action);
        $day = Yii::$app->request->get('day');
        if(!$day){//获取上一天的日期
            $day = date('Y-m-d',strtotime("-1day"));
        }
        $service = Yii::$app->request->get('service');
        $where = " date = '{$day}'";
        if($service){
            $where .= " and service = '{$service}'";
        }
        $data = ['series'=>'29,30,45,54,65,45,76,23,54,67,32,45,66,78,99,67,123,121,99,321,123,156,222,333'];
        return $this->render('login-online-list',$data);
    }
    /**
     * 滚服数据
     */
    public function actionRollData(){
        $action = Yii::$app->controller->action->id;
        parent::setActionId($action);
        $beginTime = Yii::$app->request->get('beginTime');
        $endTime = Yii::$app->request->post('endTime');
        $service = Yii::$app->request->get('service');
        $where = " 1=1 ";
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
        $count = 20;
        $page = new Pagination(['totalCount'=>$count,'pageSize'=>20]);
        return $this->render('roll-data',['data'=>$data,'page'=>$page,'count'=>$count]);
    }
}