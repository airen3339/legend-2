<?php

/**
 * 运营数据模块
 */
namespace app\modules\content\controllers;


use app\libs\AdminController;
use app\libs\Chart;
use app\modules\content\models\ChargeMoney;
use app\modules\content\models\LoginData;
use app\modules\content\models\LTV;
use app\modules\content\models\Player;
use app\modules\content\models\PlayerChannelRegister;
use app\modules\content\models\PlayerLogin;
use app\modules\content\models\PlayerRegister;
use app\modules\content\models\Role;
use app\modules\content\models\Server;
use app\modules\content\models\User;
use Yii;
use yii\data\Pagination;

class OperateController  extends AdminController
{
    public $enableCsrfValidation = false;
    public $layout = 'content';

    public function init(){
        parent::init();
        parent::setContentId('operate');
    }
    public function actionIndex(){
        return $this->redirect('/content/index/index');
    }
    /**
     * 数据查询
     */
    public function actionDataQuery(){
        $action = Yii::$app->controller->action->id;
        parent::setActionId($action);
        $beginTime = Yii::$app->request->get('beginTime');
        $endTime = Yii::$app->request->get('endTime');
        $service = Yii::$app->request->get('server',0);
        $channel = Yii::$app->request->get('channel',99);
        $page = Yii::$app->request->get('page',1);
        $where = " 1=1 ";
        $joinWhere = ' 1=1 ';
        if($beginTime && $endTime){
            $month_begin = $beginTime;
            $month_now = $endTime;
        }else{
            $month_begin = date('Y-m-01');
            $month_now = date("Y-m-d");
        }
        if($service){
            $where .= " and WorldID = '{$service}'";
            $joinWhere .= " and u.WorldID = '{$service}'";
        }
        if($channel != 99){
            $where .= " and PackageFlag = '{$channel}'";
            $joinWhere .= " and u.PackageFlag = '{$channel}'";
        }
        //计算时期天数
        $monthNow = strtotime($month_now);
        $monthBegin = strtotime($month_begin);
        $days = ($monthNow-$monthBegin)/86400;
        $data = [];
        if($page==1){
            $first = 0;
        } else{
            $first = ($page-1)*31;
        }
        if($days <= (31*$page)){
            $endDays = $days+1;
        }else{
            $endDays = $page*31;
        }
        $sumRegister = 0;
        $sumDevice = 0;
        $sumLogin = 0;
        $sumDeviceDau = 0;
        $sumAccountDau = 0;
        $sumOldUser = 0;
        $sumPayRate = 0;
        $sumRechUser = 0;
        $sumRechCount = 0;
        $sumRechMoney = 0;
        $sumArpu = 0;
        $sumArppu = 0;
        $sumNewRechUser = 0;
        $sumNewRechMoney = 0;
        for($i=$first;$i<$endDays;$i++){
            $dateTime = $monthBegin + 86400*$i;
            $date = date('Y-m-d',$dateTime);
            $end = $dateTime + 86399;
            //当日注册数
            $newRegister = User::find()->where($where." and unix_timestamp(CreateDate) between $dateTime and $end")->count();
            $sumRegister += $newRegister;
            //当日新增设备数
            $newDevice =  User::getTodayRegisterLoginDevice($dateTime,$end,$joinWhere);
            $sumDevice += $newDevice;
            //新增账号登录 当日注册账号中的登录数
            $newLogin = User::getTodayRegisterLogin($dateTime,$end,$joinWhere);
            $sumLogin += $newLogin;
            //设备DAU 当日总设备登录数
            $deviceDau = User::getTodayLoginDevice($dateTime,$end,$joinWhere);
            $sumDeviceDau += $deviceDau;
            //账号DAU 当日总账号登录数
            $accountDau = Player::getTodayLogin($dateTime,$end,$joinWhere);
            $sumAccountDau += $accountDau;
            //老用户 账号DAU-新增账号登录数
            $oldUser = $accountDau - $newLogin;
            $sumOldUser += $oldUser;
            //充值人数
            $rechargeUser = ChargeMoney::getTodayChargeNum($dateTime,$end,$joinWhere);
            //付费率
            if($accountDau == 0 || $rechargeUser == 0){
                $payRate = 0;
            }else{
                $payRate = floor(100*($rechargeUser/$accountDau));
            }
            $sumPayRate += $payRate;
            $payRate .= '%';
            //充值次数
            $rechargeCount = ChargeMoney::getTodayChargeCount($dateTime,$end,$joinWhere);
            //充值金额
            $rechargeMoney = ChargeMoney::getTodayChargeMoney($dateTime,$end,$joinWhere);
            $rechargeMoney = $rechargeMoney?$rechargeMoney:0;
            $sumRechMoney += $rechargeMoney;
            //ARPU  充值金额/账号DAU
            if($rechargeMoney ==0 || $accountDau ==0){
                $arpu = '0';
            }else{
                $arpu = round($rechargeMoney/$accountDau,2);
            }
            $sumArpu += $arpu;
            //ARPPU  充值金额/充值人数
            if($rechargeMoney ==0 || $rechargeUser ==0){
                $arppu = '0';
            }else{
                $arppu = $rechargeMoney/$rechargeUser;
            }
            $sumArppu += $arppu;
            //新增充值人数
            $rechargeData = ChargeMoney::getTodayChargeData($dateTime,$end,$joinWhere);
            $newRechargeUser = $rechargeData['rechargeNum'];
            $sumNewRechUser += $newRechargeUser;
            //新增充值金额
            $newRechargeMoney = $rechargeData['rechargeMoney'];
            $sumNewRechMoney += $newRechargeMoney;
            $data[] = ['date'=>$date,'newRegister'=>$newRegister,'newDevice'=>$newDevice,'newLogin'=>$newLogin,'deviceDau'=>$deviceDau,'accountDau'=>$accountDau,'oldUser'=>$oldUser,'payRate'=>$payRate,'rechargeUser'=>$rechargeUser,'rechargeCount'=>$rechargeCount,'rechargeMoney'=>$rechargeMoney,'arpu'=>$arpu,'arppu'=>$arppu,'newRechargeUser'=>$newRechargeUser,'newRechargeMoney'=>$newRechargeMoney];
        }
        $count = $endDays-$first;
        //平均付费率
        $averPayRate = floor($sumPayRate/$count);
        $averPayRate .= '%';
        //平均ARPU ARPPU
        $averArpu = floor(100*($sumArpu/$count))/100;
        $averArppu = floor(100*($sumArppu/$count))/100;
        $data[] = ['date'=>'总计','newRegister'=>$sumRegister,'newDevice'=>$sumDevice,'newLogin'=>$sumLogin,'deviceDau'=>$sumDeviceDau,'accountDau'=>$sumAccountDau,'oldUser'=>$sumOldUser,'payRate'=>$averPayRate,'rechargeUser'=>$sumRechUser,'rechargeCount'=>$sumRechCount,'rechargeMoney'=>$sumRechMoney,'arpu'=>$averArpu,'arppu'=>$averArppu,'newRechargeUser'=>$sumNewRechUser,'newRechargeMoney'=>$sumNewRechMoney];
        $count = $days+1;
        $page = new Pagination(['totalCount'=>$count,'pageSize'=>31]);
        $servers = Server::getServers();//获取区服
        $channel = User::getChannel();//获取渠道
        return $this->render('data-query',['data'=>$data,'page'=>$page,'count'=>$count,'servers'=>$servers,'channel'=>$channel]);
    }
    /**
     * 等级分布
     */
    public function actionLevelList(){
        $action = Yii::$app->controller->action->id;
        parent::setActionId($action);
        $beginTime = Yii::$app->request->get('beginTime');
        $endTime = Yii::$app->request->get('endTime');
        $service = Yii::$app->request->get('server');
        $where = " 1=1 ";
        if($beginTime){
            $begin = strtotime($beginTime);
            $where .=  " and unix_timestamp(CreateDate) >= $begin";
        }
        if($endTime){
            $end = strtotime($endTime) + 86399;
            $where .= " and unix_timestamp(CreateDate) <= $end";
        }
        if($service){
            $where .= " and WorldID = '{$service}'";
        }
        $data = [];
        $levelTotal = 70;
        $pageSize = 20;
        $page = Yii::$app->request->get('page',1);
        $start = ($page-1)*$pageSize;
        $end = $page*$pageSize;
        if($end > $levelTotal)$end = $levelTotal;
        $userTotal = Player::find()->count();
        for($i=($start+1);$i<=$end;$i++){
            $level_num = Player::find()->where($where."  and Level = $i")->count();
            if($userTotal == 0 || $level_num ==0){
                $user_proportion = '0%';//用户占比
            }else{
                $user_proportion = (floor(10000*($level_num/$userTotal))/100).'%';
            }
            //等级滞留用户 7天内未登录
            $now = time();
            $seven_before = $now - 86400*7;
            //滞留用户数
            $retention_user = Player::find()->where($where." and LastLogin < $seven_before and Level = $i")->count();
            //滞留用户比例
            if($retention_user ==0 || $level_num ==0){
                $retention_proportion = '0%';
            }else{
                $retention_proportion = (floor(10000*($retention_user/$level_num))/100).'%';
            }
            $data[]= ['level'=>$i,'user_total'=>$level_num,'user_proportion'=>$user_proportion,'retention_user'=>$retention_user,'retention_proportion'=>$retention_proportion];
        }
        $page = new Pagination(['totalCount'=>$levelTotal,'pageSize'=>$pageSize]);
        $servers = Server::getServers();
        return $this->render('level-list',['data'=>$data,'page'=>$page,'count'=>$levelTotal,'servers'=>$servers]);
    }
    /**
     * 留存数据
     */
    public function actionRetainData(){
        $action = Yii::$app->controller->action->id;
        parent::setActionId($action);
        $beginTime = Yii::$app->request->get('beginTime');
        $endTime = Yii::$app->request->get('endTime');
        $channel = Yii::$app->request->get('channel',99);
        $page = Yii::$app->request->get('page',1);
        if($beginTime && $endTime){
            $month_begin = $beginTime;
            $month_now = $endTime;
        }else{
            $month_begin = date('Y-m-01');
            $month_now = date("Y-m-d");
        }
        //计算时期天数
        $monthNow = strtotime($month_now);
        $monthBegin = strtotime($month_begin);
        $days = ($monthNow-$monthBegin)/86400;
        if($page==1){
            $first = 0;
        } else{
            $first = ($page-1)*31;
        }
        if($days <= (31*$page)){
            $endDays = $days+1;
        }else{
            $endDays = $page*31;
        }
        $data = [];
        for($i=$first;$i<$endDays;$i++){
            $dateTime = $monthBegin + $i*86400;
            $date = date("Y-m-d",$dateTime);
            if($channel != 99){//获取渠道留存数据
                $register = PlayerChannelRegister::find()->where("date='{$date}' and channel = '{$channel}'")->asArray()->one();
            }else{//全部留存数据
                $register = PlayerRegister::find()->where("date = '{$date}'")->asArray()->one();
            }
            if($register){
                $roleIds = $register['roleIds'];
                //老用户
                $register['oldUser'] = $register['accountDau'] - $register['total'];
                //2/3/5/7/15日留存
                $arr = ['two'=>2,'three'=>3,'five'=>5,'seven'=>7,'fifteen'=>15];
                foreach($arr as $p => $o){
                    $retainDay = strtotime($date) + 86400*($o-1);
                    $retainDate = date('Y-m-d',$retainDay);
                    if($roleIds){
                        $retainUser = PlayerLogin::find()->where("date = '{$retainDate}' and roleId in ($roleIds)")->count();
                    }else{
                        $retainUser = 0;
                    }
                    if($retainUser ==0 || $register['total'] < 1){
                        $retainRate = '0%';
                    }else{
                        $retainRate = (floor(100*($retainUser/$register['total']))).'%';
                    }
                    $register[$p]=$retainRate;
                }
                $data[] = $register;
            }else{
                $data[]= ['date'=>$date,'total'=>0,'accountDau'=>0,'oldUser'=>0,'two'=>'0%','three'=>'0%','five'=>'0%','seven'=>'0%','fifteen'=>'0%'];
            }
        }
        $count = $days+1;
        $page = new Pagination(['totalCount'=>$count,'pageSize'=>31]);
        $channel = User::getChannel();
        return $this->render('retain-data',['data'=>$data,'page'=>$page,'count'=>$count,'channel'=>$channel]);
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
        $service = Yii::$app->request->get('server','');
        $channel = Yii::$app->request->get('channel',99);
        $where = ' 1=1 ';
        if($service){
            $where .= " and c.worldID = '{$service}'";
        }
        if($channel != 99){
            $where .= " and u.PackageFlag = '{$channel}'";
        }
        $levelTotal = 70;
        $pageSize = 20;
        $page = Yii::$app->request->get('page',1);
        $start = ($page-1)*$pageSize;
        $end = $page*$pageSize;
        if($end > $levelTotal)$end = $levelTotal;
        $data = [];
        for($i=($start);$i<=$end;$i++){
            //总充值金额
            $depositData = ChargeMoney::getChargeMoney($i,$where);
            $depositMoney = $depositData['money'];
            //人数
            $userNum = $depositData['total'];
            $data[]= ['level'=>$i,'depositMoney'=>$depositMoney,'userNum'=>$userNum];
        }
        $page = new Pagination(['totalCount'=>$levelTotal,'pageSize'=>$pageSize]);
        $servers = Server::getServers();//获取区服
        $channel = User::getChannel();//获取渠道
        return $this->render('level-deposit-list',['data'=>$data,'page'=>$page,'count'=>$levelTotal,'servers'=>$servers,'channel'=>$channel]);
    }
    /**
     * 充值排行查询
     */
    public function actionDepositRankQuery(){
        $action = Yii::$app->controller->action->id;
        parent::setActionId($action);
        $beginTime = Yii::$app->request->get('beginTime');
        $endTime = Yii::$app->request->get('endTime');
        $service = Yii::$app->request->get('server');
        $page = Yii::$app->request->get('page',1);
        $where = " 1=1 ";
        if($service){
            $where .= " and c.WorldID = '{$service}'";
        }
        if($beginTime){
            $begin = strtotime($beginTime);
            $where .=  " and unix_timestamp(c.finishTime) >= $begin";
        }
        if($endTime){
            $end = strtotime($endTime) + 86399;
            $where .= " and unix_timestamp(c.finishTime) <= $end";
        }
        $where .= " and c.status = 2";
        $data = ChargeMoney::getChargeRankQuery($where,$page);
        $servers = Server::getServers();
        $data['servers'] = $servers;
        return $this->render('deposit-rank-query',$data);
    }
    /**
     * LTV数据
     */
    public function actionLtvData(){
        $action = Yii::$app->controller->action->id;
        parent::setActionId($action);
        $beginTime = Yii::$app->request->get('beginTime');
        $endTime = Yii::$app->request->get('endTime');
        $channel = Yii::$app->request->get('channel',99);
        $page = Yii::$app->request->get('page',1);
        $ltv = Yii::$app->request->get('ltv',1);//1-账号登录数  2-设备登录数
        if($beginTime && $endTime){
            $month_begin = $beginTime;
            $month_now = $endTime;
        }else{
            $month_begin = date('Y-m-01');
            $month_now = date("Y-m-d");
        }
        //计算时期天数
        $monthNow = strtotime($month_now);
        $monthBegin = strtotime($month_begin);
        $days = ($monthNow-$monthBegin)/86400;
        if($page==1){
            $first = 0;
        } else{
            $first = ($page-1)*31;
        }
        if($days <= (31*$page)){
            $endDays = $days+1;
        }else{
            $endDays = $page*31;
        }
        $data = [];
        $arrDay = ['one'=>1,'three'=>3,'five'=>5,'seven'=>7,'fifteen'=>15,'thirty'=>30];
        for($i=$first;$i<$endDays;$i++){
            $dateData = [];
            $dateTime = $monthBegin + 86400*$i;
            $date = date('Y-m-d',$dateTime);
            //新增数
            if($channel != 99){//选择某个渠道
                $add = LTV::find()->where("date = '{$date}' and channel = '{$channel}'")->asArray()->one();
                if($ltv ==1){
                    $addNum = $add['login'];//账号数
                }else{
                    $addNum = $add['device'];//设备数
                }
            }else{//所有渠道
                if($ltv ==1){
                    $addNum = LTV::find()->where("date = '{$date}' ")->asArray()->sum('login');
                }else{
                    $addNum = LTV::find()->where("date = '{$date}' ")->asArray()->sum('device');
                }
            }
            $addNum = $addNum?$addNum:0;
            $dateData['date'] = $date;
            $dateData['addNum'] = $addNum;
            foreach($arrDay as $k => $v){
                if($addNum == 0){
                    $rate = 0;
                }else{
                    $endTime = $dateTime + 86399*$v;
                    //充值金额
                    if($channel){//选择某个渠道
                        $moneySum = LTV::find()->where("( unix_timestamp(date) between $dateTime and $endTime ) and channel = '{$channel}'")->sum('money');
                    }else{//所有渠道
                        $moneySum = LTV::find()->where("unix_timestamp(date) between $dateTime and $endTime ")->sum('money');
                    }
                    //总充值/新增数
                    if($moneySum ==0){
                        $rate = 0;
                    }else{
                        $rate = round($moneySum/$addNum,2);
                    }
                }
                $dateData[$k] = $rate;
            }
            $data[] = $dateData;
        }
        $count = $days+1;
        $page = new Pagination(['totalCount'=>$count,'pageSize'=>20]);
        $channel = User::getChannel();
        return $this->render('ltv-data',['data'=>$data,'page'=>$page,'count'=>$count,'channel'=>$channel]);
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
        $service = Yii::$app->request->get('server');
        $where = " date = '{$day}'";
        if($service){
            $where .= " and serverId = '{$service}'";
        }
        $data = LoginData::find()->where($where)->orderBy('id desc ')->asArray()->one();
        $server = $data['serverId'];
        $servers = Server::getServers();
        $data = ['series'=>$data['data'],'day'=>$day,'server'=>$server,'servers'=>$servers];
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