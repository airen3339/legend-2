<?php

/**
 * 玩家相关模块
 */
namespace app\modules\content\controllers;


use app\libs\AdminController;
use app\modules\content\models\ActivityLog;
use app\modules\content\models\ChargeMoney;
use app\modules\content\models\CurrencyData;
use app\modules\content\models\Item;
use app\modules\content\models\MailReceive;
use app\modules\content\models\Player;
use app\modules\content\models\RoleActivity;
use app\modules\content\models\Server;
use app\modules\content\models\YuanbaoRole;
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
        $name = \Yii::$app->request->get('name','');
        $service = \Yii::$app->request->get('server');
        $roleId = \Yii::$app->request->get('roleId');
        $page = \Yii::$app->request->get('page',1);
        $where = ' 1=1 ';
        if($name){
            $where .= " and p.Name = '{$name}'";
        }
        if($service){
            $where .= " and u.WorldID = '{$service}'";
        }
        if($roleId){
            $where .= " and p.RoleID = '{$roleId}' ";
        }
        $sql = "select p.RoleID,p.UserID,p.LastLogin,p.CreateDate,u.PackageFlag,p.WorldID,p.Name from `user` u inner join player p on p.UserID = u.UserID where $where";
        $count = \Yii::$app->db2->createCommand($sql)->queryAll();
        $count = count($count);
        $limit = " limit ".(20*($page-1)).",20";
        $pages = new Pagination(['totalCount'=>$count,'pageSize'=>20]);
        $sql .= $limit;
        $user = \Yii::$app->db2->createCommand($sql)->queryAll();
        $servers = Server::getServers();
        return $this->render('role-information',['user'=>$user,'page'=>$pages,'count'=>$count,'servers'=>$servers]);
    }
    /**
     * 详细信息
     */
    public function actionDetailInformation(){
        $action = \Yii::$app->controller->action->id;
        parent::setActionId($action);
        $uid = \Yii::$app->request->get('uid');
        $name = \Yii::$app->request->get('name','');
        $where = ' 1=1 ';
        $wh = ' 1=1 ';
        if($uid || $name){
            if($uid){
                $where .= " and RoleID = '{$uid}' ";
            }
            if($name){
                $where .= " and Name = '{$name}' ";
            }
            $data = Player::find()->select("RoleID,UserID,WorldID,WorldName,Name,Level,Ingot,Cash,Money,CurHP,CurMP,Exp,Battle,Vital,MonsterKillNum,SoulScore,PkValue")->where($where)->asArray()->one();
            if($data){
                $roleId = $data['RoleID'];
                $wh .= " and status = 2 and RoleID = '{$roleId}'";
                //充值金额
                $money = ChargeMoney::find()->where($wh)->sum('chargenum');
                $data['rechargeMoney'] = $money?$money:0;
                //更新元宝消耗记录
                YuanbaoRole::getYuanbaoData();
            }else{
                $data = [];
            }
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
        $name = \Yii::$app->request->get('name','');
        $where = ' 1=1 ';
        if($name){
            $roleId = Player::find()->where("Name = '{$name}'")->asArray()->one()['RoleID'];
            if($roleId){
                $where .= " and roleId = '{$roleId}'";
            }else{
                $where .= " and 1 > 2";
            }
        }
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
            $sql = "select u.Username,u.PackageFlag,p.Name from `user` u inner join player p on p.UserID = u.UserID inner join chargemoney c on c.roleID = p.RoleID where c.roleID = '{$v['roleID']}' ";
            $da = \Yii::$app->db2->createCommand($sql)->queryOne();
            $data[$k]['username'] = $da['Username'];
            $data[$k]['packageFlag'] = $da['PackageFlag'];
            $data[$k]['roleName'] = $da['Name'];
        }
        $servers = Server::getServers();
        return $this->render('order-query',['data'=>$data,'page'=>$pages,'count'=>$total,'servers'=>$servers]);
    }
    /**
     * 货币消耗
     */
    public function actionMoneyUse(){
        $action = \Yii::$app->controller->action->id;
        parent::setActionId($action);
        $server = \Yii::$app->request->get('server',0);
        $type = \Yii::$app->request->get('type',0);
        $where = ' type = 1 ';
        if($server){
            $where .= " and serverId = '{$server}'";
        }
        if($type){
            $where .= " and typeObject = $type ";
        }
        $count = CurrencyData::find()->where($where)->groupBy("serverId,typeObject,added")->count();
        $page = new Pagination(['totalCount'=>$count]);
        $data = CurrencyData::find()->select("serverId,type,typeObject,remark,added,sum(number) as money")->where($where)->offset($page->offset)->limit($page->limit)->groupBy("serverId,typeObject,added")->asArray()->all();
        $servers = Server::getServers();
        $types = YuanbaoRole::getTypes();
        return $this->render('money-use',['data'=>$data,'servers'=>$servers,'types'=>$types,'page'=>$page,'count'=>$count]);
    }
    /**
     * 日志查询
     */
    public function actionLogQuery(){
        $action = \Yii::$app->controller->action->id;
        parent::setActionId($action);
        $beginTime = \Yii::$app->request->get('beginTime');
        $endTime = \Yii::$app->request->get('endTime');
        $service = \Yii::$app->request->get('server');
        $uid = \Yii::$app->request->get('uid');
        $type = \Yii::$app->request->get('type');
        $added = \Yii::$app->request->get('added',99);
        $name = \Yii::$app->request->get('name','');
        $where = ' 1=1 ';
        if($beginTime){
            if($type ==4){
                $begin = strtotime($beginTime);
                $where .= " and createTime >= $begin";
            }else{
                $where .=  " and date >= $beginTime";
            }
        }
        if($endTime){
            if($type ==4){
            $end = strtotime($endTime) + 86399;
            $where .= " and createTime <= $end";
            }else{
                $where .= " and date <= $endTime";
            }
        }
        if($service){
            if($type ==4){
                $where .= " and server_id = '{$service}'";
            }else{
                $where .= " and serverId = '{$service}'";
            }
        }
        if($uid){
            $where .= " and roleId = '{$uid}' ";
        }
        if($name){
            $roleId = Player::find()->where("Name = '{$name}'")->asArray()->one()['RoleID'];
            if($roleId){
                $where .= " and roleId = '{$roleId}'";
            }else{
                $where .= " and 1 > 2";
            }

        }
        if($type && $type != 4){
            $where .= " and type = $type ";
        }
        if( $type != 4){//不是元宝充值
            if($added != 99){
                $where .= " and added = $added ";
            }
            $count = YuanbaoRole::find()->where($where)->count();
            $page = new Pagination(['totalCount'=>$count]);
            $data = YuanbaoRole::find()->where($where)->orderBy('dateTime desc')->offset($page->offset)->limit($page->limit)->asArray()->all();
        }else{
            if($added == 0){//元宝充值没有支出
                $count = 0;
                $page = new Pagination(['totalCount'=>$count]);
                $data = [];
            }else{
                $count = Recharge::find()->where($where)->count();
                $page = new Pagination(['totalCount'=>$count]);
                $data = Recharge::find()->where($where)->orderBy('createTime desc')->offset($page->offset)->limit($page->limit)->asArray()->all();
            }
        }
        $servers = Server::getServers();
        $types = YuanbaoRole::getTypes();
        return $this->render('log-query',['data'=>$data,'servers'=>$servers,'types'=>$types,'page'=>$page,'count'=>$count]);
    }

    /**
     * 角色邮件领取
     */
    public function actionMailReceive(){
        $action = \Yii::$app->controller->action->id;
        parent::setActionId($action);
        $name = \Yii::$app->request->get('name','');
        $serverId = \Yii::$app->request->get('server','');
        $where = " 1=1";
        if($serverId || $name){
            //获取实时日志记录
            MailReceive::getMailLog();
        }
        if($serverId){
            $where .= " and serverId = $serverId ";
        }
        if($name){
            $roleId = Player::find()->where("Name='{$name}'")->asArray()->one()['Name'];
            if($roleId){
                $where .= " and roleId = '{$roleId}'";
            }else{
                $where .= " and 1 > 2";
            }
        }
        $count = MailReceive::find()->where($where)->count();
        $page = new Pagination(['totalCount'=>$count]);
        $data = MailReceive::find()->where($where)->orderBy('id desc')->offset($page->offset)->limit($page->limit)->asArray()->all();
        foreach($data as $k => $v){
            $content = Item::find()->where("itemid = {$v['content']}")->asArray()->one()['name'];
            $data[$k]['content'] = $content.' '.$v['content'];
        }
        $servers = Server::getServers();
        return $this->render('mail-receive',['data'=>$data,'page'=>$page,'count'=>$count,'servers'=>$servers]);
    }
    /**
     * 等级排行
     * 前20名
     */
    public function actionLevelOrder(){
        $action = \Yii::$app->controller->action->id;
        parent::setActionId($action);
        //等级前20名 等级一样以经验排
        $player = Player::find()->select("RoleID,UserID,WorldID,WorldName,Name,Level,Ingot,Cash,Money,CurHP,CurMP,Exp,Battle,Vital,MonsterKillNum,SoulScore,PkValue")->orderBy("Level desc,Exp desc")->limit(20)->asArray()->all();
        foreach($player as $k => $v){
            $roleId = $v['RoleID'];
            $wh = " status = 2 and RoleID = '{$roleId}'";
            //充值金额
            $money = ChargeMoney::find()->where($wh)->sum('chargenum');
            $player[$k]['rechargeMoney'] = $money?$money:0;
            //更新元宝消耗记录
//            YuanbaoRole::getYuanbaoData();
        }
        return $this->render('level-order',['data'=>$player]);
    }
    /**
     * 战力排行
     * 前20名
     */
    public function actionZlOrder(){
        $action = \Yii::$app->controller->action->id;
        parent::setActionId($action);
        //等级前20名 等级一样以经验排
        $player = Player::find()->select("RoleID,UserID,WorldID,WorldName,Name,Level,Ingot,Cash,Money,CurHP,CurMP,Exp,Battle,Vital,MonsterKillNum,SoulScore,PkValue")->orderBy("Battle  desc")->limit(20)->asArray()->all();
        foreach($player as $k => $v){
            $roleId = $v['RoleID'];
            $wh = " status = 2 and RoleID = '{$roleId}'";
            //充值金额
            $money = ChargeMoney::find()->where($wh)->sum('chargenum');
            $player[$k]['rechargeMoney'] = $money?$money:0;
            //更新元宝消耗记录
//            YuanbaoRole::getYuanbaoData();
        }
        return $this->render('zl-order',['data'=>$player]);
    }
    /**
     * 用户天中宝藏活动数据统计
     *
     */
    public function actionTzbzCount(){
        $action = \Yii::$app->controller->action->id;
        parent::setActionId($action);
        $beginTime = \Yii::$app->request->get('beginTime');
        $endTime = \Yii::$app->request->get('endTime');
        $name = \Yii::$app->request->get('name');
        $serverId = \Yii::$app->request->get('server');
        $where = " 1 = 1 ";

        //天和宝藏数据字段
        $arr = ActivityLog::tzbzReward();
        $percent = '0%';
        $hadRole = '';
        if($name){
            $roleId = Player::find()->where(" Name = '{$name}'")->asArray()->one()['RoleID'];
//            $roleId = '1539274448577280';
            if($roleId){
                $hadRole = $roleId;
                $today = strtotime(date('Y-m-d'));
                if($beginTime){
                    $begin = strtotime($beginTime);
                    $where .= " and unix_timestamp(dateTime) >= $begin";
                    if($begin > $today){
                        //统计用户最新的活动数据
                        YuanbaoRole::updateTzbzData($roleId);
                    }
                }
                if($endTime){
                    $end = strtotime($endTime);
                    $where .= " and unix_timestamp(dateTime) <= $end";
                    if($end > $today){
                        //统计用户最新的活动数据
                        YuanbaoRole::updateTzbzData($roleId);
                    }
                }else{
                    //统计用户最新的活动数据
                    YuanbaoRole::updateTzbzData($roleId);
                }
                if($serverId){
                    $where .= " and serverId = $serverId ";
                }
                $where .= " and roleId = '{$roleId}' and type = 1";
                $total = 0;
//                $number = 0;//经验次数
                foreach($arr as $k => $v){//统计次数
                    $count = RoleActivity::find()->where("$where and contentId = {$v['id']}")->count();
                    $count = $count?$count:0;
//                    if($v['id'] == 444444){
//                        $number = $count;
//                    }
                    $total += $count;
                    $arr[$k]['count'] = $count;
                }
//                if($number && $total){
//                    $percent = (floor(100*($number/$total))/100).'%';
//                }
            }
        }
//        $totalArr = ['id'=>'','name'=>'经验次数概率','count'=>$percent];
//        $arr[] = $totalArr;
        $servers = Server::getServers();
        return $this->render('tzbz-count',['data'=>$arr,'servers'=>$servers,'hadRole'=>$hadRole]);
    }
    
}