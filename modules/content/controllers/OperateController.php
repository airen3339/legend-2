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
use app\modules\content\models\LTVMoney;
use app\modules\content\models\OnlineCount;
use app\modules\content\models\Player;
use app\modules\content\models\PlayerChannelRegister;
use app\modules\content\models\PlayerLogin;
use app\modules\content\models\PlayerRegister;
use app\modules\content\models\Role;
use app\modules\content\models\Server;
use app\modules\content\models\SliverMerchant;
use app\modules\content\models\User;
use app\modules\content\models\YuanbaoRole;
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
//        if($page==1){
//            $first = 0;
//        } else{
//            $first = ($page-1)*31;
//        }
//        if($days <= (31*$page)){
//            $endDays = $days+1;
//        }else{
//            $endDays = $page*31;
//        }
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
        for($i=$days;$i>=0;$i--){
            $dateTime = $monthBegin + 86400*$i;
            $date = date('Y-m-d',$dateTime);
            $end = $dateTime + 86399;
            //当日注册数
            $newRegister = User::find()->where($where." and unix_timestamp(CreateDate) between $dateTime and $end")->count();
            $sumRegister += $newRegister;
            //当日新增设备数
//            $newDevice =  User::getTodayRegisterLoginDevice($dateTime,$end,$joinWhere);
//            $sumDevice += $newDevice;
            //新增账号登录 当日注册账号中的登录数
            $newLogin = User::getTodayRegisterLogin($dateTime,$end,$joinWhere);
            $sumLogin += $newLogin;
            //设备DAU 当日总设备登录数
//            $deviceDau = User::getTodayLoginDevice($dateTime,$end,$joinWhere);
//            $sumDeviceDau += $deviceDau;
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
            $data[] = ['date'=>$date,'newRegister'=>$newRegister,'newLogin'=>$newLogin,'accountDau'=>$accountDau,'oldUser'=>$oldUser,'payRate'=>$payRate,'rechargeUser'=>$rechargeUser,'rechargeCount'=>$rechargeCount,'rechargeMoney'=>$rechargeMoney,'arpu'=>round($arpu,2),'arppu'=>round($arppu,2),'newRechargeUser'=>$newRechargeUser,'newRechargeMoney'=>$newRechargeMoney];
        }
//        $count = $endDays-$first;
        $count = $days;
        //平均付费率
        $averPayRate = floor($sumPayRate/$count);
        $averPayRate .= '%';
        //平均ARPU ARPPU
        $averArpu = floor(100*($sumArpu/$count))/100;
        $averArppu = floor(100*($sumArppu/$count))/100;
        $data[] = ['date'=>'总计','newRegister'=>$sumRegister,'newDevice'=>$sumDevice,'newLogin'=>$sumLogin,'deviceDau'=>$sumDeviceDau,'accountDau'=>$sumAccountDau,'oldUser'=>$sumOldUser,'payRate'=>$averPayRate,'rechargeUser'=>$sumRechUser,'rechargeCount'=>$sumRechCount,'rechargeMoney'=>$sumRechMoney,'arpu'=>$averArpu,'arppu'=>$averArppu,'newRechargeUser'=>$sumNewRechUser,'newRechargeMoney'=>$sumNewRechMoney];
        $page = new Pagination(['totalCount'=>$count,'pageSize'=>$days]);
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
        //计算等级数 等级人数不为0的才算
        $levelTotal = 0;
        $levels = [];
        for($i=1;$i<71;$i++){
            $number = Player::find()->where($where."  and Level = $i")->count();
            if($number > 0){
                $levelTotal += 1;
                $levels[] = $i;
            }
        }
//        $levelTotal = 70;
        $pageSize = 20;
        $page = Yii::$app->request->get('page',1);
        $start = ($page-1)*$pageSize;
        $end = $page*$pageSize;
        if($end > $levelTotal)$end = $levelTotal;
        $userTotal = Player::find()->count();
        for($i=($start+1);$i<=$end;$i++){
            $level = $levels[$i-1];
            $level_num = Player::find()->where($where."  and Level = $level")->count();
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
            $data[]= ['level'=>$level,'user_total'=>$level_num,'user_proportion'=>$user_proportion,'retention_user'=>$retention_user,'retention_proportion'=>$retention_proportion];
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
//                    $objectRoleId  = $add['loginMsg'];
                }else{
                    $addNum = $add['device'];//设备数
//                    $objectDevice = $add['deviceMsg'];//计算对象
//                    $objectRoleId = LTV::deviceGetRoleId($objectDevice);
                }
                $ltvId = $add['id'];
            }else{//所有渠道
                if($ltv ==1){
                    $addNum = LTV::find()->where("date = '{$date}' ")->asArray()->sum('login');
//                    $objectRoleId = LTV::getAllMsg($date,1);//获取当前所有渠道的新增账号信息
                }else{
                    $addNum = LTV::find()->where("date = '{$date}' ")->asArray()->sum('device');
//                    $objectDevice = LTV::getAllMsg($date,2);//获取当前所有渠道的新增设备信息
//                    $objectRoleId = LTV::deviceGetRoleId($objectDevice);
                }
                $ltvId = LTV::find()->select("group_concat(id) as ids")->where("date = '{$date}'")->asArray()->one()['ids'];
            }
            $addNum = $addNum?$addNum:0;
            $dateData['date'] = $date;
            $dateData['addNum'] = $addNum;
            foreach($arrDay as $k => $v){
                $endTime = $dateTime + 86399*$v;
                $time = time();
                if($endTime > $time){
                    $rate = '';
                }else{
                    if($addNum == 0){
                        $rate = 0;
                    }else{
                        //充值金额
                        if($ltvId){
                            if($channel != 99){//选择某个渠道

//                            $sql = "select sum(c.chargenum) as money from chargemoney c inner join player p on p.RoleID = c.roleID inner join `user` u on u.UserID = p.UserID and u.PackageFlag = '{$channel}' and ( unix_timestamp(c.finishTime) between $dateTime and $endTime )  and c.status = 2 and c.roleId in ($objectRoleId)";
                            }else{//所有渠道
//                            $sql = "select sum(c.chargenum) as money from chargemoney c inner join player p on p.RoleID = c.roleID inner join `user` u on u.UserID = p.UserID  and ( unix_timestamp(c.finishTime) between $dateTime and $endTime )  and c.status = 2 and c.roleId in ($objectRoleId)";
                            }

//                        $moneySum = \Yii::$app->db2->createCommand($sql)->queryOne()['money'];
                            $str = $ltv==1?'loginMoney':'deviceMoney';
                            $moneySum = LTVMoney::find()->where("ltvId in ($ltvId) and ( unix_timestamp(date) between $dateTime and $endTime )")->asArray()->sum($str);
                            $moneySum = $moneySum?$moneySum:0;
                        }else{
                            $moneySum = 0;
                        }
                        //总充值/新增数
                        if($moneySum ==0){
                            $rate = 0;
                        }else{
                            $rate = round($moneySum/$addNum,2);
                        }
                    }
                }
                $dateData[$k] = $rate;
            }
            $data[] = $dateData;
        }
        $count = $days+1;
        $page = new Pagination(['totalCount'=>$count,'pageSize'=>31]);
        $channel = User::getChannel();
        return $this->render('ltv-data',['data'=>$data,'page'=>$page,'count'=>$count,'channel'=>$channel]);
    }
    /**
     * 登录在线分布
     * 日志读取
     */
    public function actionLoginOnlineListOld(){
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
     * 登录在线分布
     * 实时读取
     */
    public function actionLoginOnlineList(){
        $action = Yii::$app->controller->action->id;
        parent::setActionId($action);
        $day = Yii::$app->request->get('day');
        $serverId = Yii::$app->request->get('server');
        $today = date('Y-m-d');
        $todayTime = strtotime($today);
        if(!$day){//获取当前时间
            $date = $today;
            $day = $today;
        }elseif(strtotime($day) >= $todayTime){//大于等于今天
            $date = $today;
        }else{
            $date = $day;
        }
        $dateTime = strtotime($date);
        if($dateTime >= $todayTime){//当前小时数据
            $time = time();
            $reduceTime = $time-$dateTime;
            $number = ceil(($reduceTime/3600));
        }else{//全天数据
            $number = 24;
        }
        //获取区服
        if(!$serverId){
            $serverId = Server::find()->orderBy('game_id asc')->asArray()->one()['game_id'];
        }
        $hour = [];
        $data = [];
        for($i=0;$i<$number;$i++){
            $beginTime = $dateTime + $i*3600;
            $endTime = $beginTime + 3599;
            $count = OnlineCount::find()->where("WorldID = '{$serverId}' and upTime between $beginTime and $endTime")->orderBy('Count desc')->asArray()->one()['Count'];
            $count = $count?$count:0;
            $hour[]= $i+1;
            $data[]= $count;
        }
        $series = implode(',',$data);
        $hour = implode(',',$hour);
        $servers = Server::getServers();
        $data = ['series'=>$series,'day'=>$day,'server'=>$serverId,'servers'=>$servers,'hour'=>$hour];
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
    /**
     * 账号新增数量
     */
    public function actionAddNumber(){$action = Yii::$app->controller->action->id;
        parent::setActionId($action);
        $beginTime = Yii::$app->request->get('beginTime');
        $endTime = Yii::$app->request->get('endTime');
//        $page = Yii::$app->request->get('page',1);
        $data = [];
        if($beginTime && $endTime){
            $month_begin = $beginTime;
            $month_now = $endTime;
            //计算时期天数
            $monthNow = strtotime($month_now);
            $monthBegin = strtotime($month_begin);
            $days = ($monthNow-$monthBegin)/86400;
//            if($page==1){
//                $first = 0;
//            } else{
//                $first = ($page-1)*31;
//            }
//            if($days <= (31*$page)){
//                $endDays = $days+1;
//            }else{
//                $endDays = $page*31;
//            }
            for($i=$days;$i>=0;$i--){
                $dateTime = $monthBegin + 86400 * $i;
                $date = date('Y-m-d', $dateTime);
                $end = $dateTime + 86399;

                //新增账号登录 当日注册账号中的登录数
                $register = User::getTodayRegisterLogin($dateTime,$end,"1=1");
                //当日注册数
//                $register = User::find()->where( "  unix_timestamp(CreateDate) between $dateTime and $end")->count();
                $data[] = ['date'=>$date,'register'=> $register?$register:0];
            }
            $count = $days;
        }else{
            $today = date('Y-m-d');
            $begin = strtotime($today);
            $end = $begin + 86399;
            //新增账号登录 当日注册账号中的登录数
            $register = User::getTodayRegisterLogin($begin,$end,"1=1");
            //当日注册数
//            $register = User::find()->where("  unix_timestamp(CreateDate) between $begin and $end")->count();
            $data[] = ['date'=>$today,'register'=> $register?$register:0];
            $count = 1;
        }

        $page = new Pagination(['totalCount'=>$count,'pageSize'=>30]);
        return $this->render('add-number',['data'=>$data,'count'=>$count,'page'=>$page]);
    }
    /**
     * 新增数量图
     * 默认当前月
     */
    public function actionAddNumberImg(){
        $action = Yii::$app->controller->action->id;
        parent::setActionId($action);
        $beginTime = Yii::$app->request->get('beginTime');
        $endTime = Yii::$app->request->get('endTime');
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
        $data = [];
        $series = [];
        for($i=0;$i<$days;$i++){
            $dateTime = $monthBegin + 86400*$i;
            $date = date('Y-m-d',$dateTime);
            $end = $dateTime + 86399;
            $date = str_replace('-','',$date);
            $series[] = $date;
            //当日注册数
            $register = User::find()->where( "  unix_timestamp(CreateDate) between $dateTime and $end")->count();
            $data[] = $register?$register:0;
        }
        $series = implode(',',$series);
        $data = implode(',',$data);
        $data = ['series'=>$series,'data'=>$data,'date'=>$beginTime.'至'.$endTime];
        return $this->render('add-number-img',$data);
    }
    /**
     * 银商统计
     *
     */
    public function actionYsCount(){
        $action = Yii::$app->controller->action->id;
        parent::setActionId($action);
        $userId = Yii::$app->request->get('userId');
        $name = Yii::$app->request->get('name');
        $beginTime = Yii::$app->request->get('beginTime');
        $endTime = Yii::$app->request->get('endTime');
        if($userId || $name || $beginTime || $endTime){//有一个搜索存在更新日志
            YuanbaoRole::getYuanbaoData();
        }
        $where = "where 1 = 1";
        $ycWhere = " and type = 3 ";
        if($userId){
            $where.= " and us.UserID = '{$userId}'";
        }
        if($name){
            $where .= " and p.Name = '{$name}'";
        }
        if($beginTime){
            $begin = strtotime($beginTime);
            $ycWhere .= " and unix_timestamp(dateTime) >= $begin";
        }
        if($endTime){
            $end = strtotime($endTime);
            $ycWhere .= " and unix_timestamp(dateTime) <= $end";
        }
        $sql = " select us.* from {{%yin_shang_user}} us left join {{%player}} p on p.UserID = us.UserID $where order by us.UserID";
        $total = Yii::$app->db2->createCommand($sql)->queryAll();
        $count = count($total);
        $pages = new Pagination(['totalCount'=>$count]);
        $page = Yii::$app->request->get('page',1);
        if(!$page)$page=1;
        $limit = " limit ".(20*($page-1)).',20';
        $sql = " select us.*,p.Name,p.Ingot,p.RoleID from {{%yin_shang_user}} us left join {{%player}} p on p.UserID = us.UserID $where order by us.UserID $limit";
        $data = Yii::$app->db2->createCommand($sql)->queryAll();
        foreach($data as $k => $v){
            $roleId = $v['RoleID'];
            if($roleId){//获取账号的赠送元宝和收入元宝统计
                $out = YuanbaoRole::find()->where("roleId = '{$roleId}' and added = 0 $ycWhere ")->sum('money');
                $in = YuanbaoRole::find()->where("roleId = '{$roleId}' and added = 1 $ycWhere")->sum('money');
                $out = $out?$out:0;
                $in = $in?$in:0;
            }else{
                $out = 0;
                $in = 0;
            }
            $data[$k]['out'] = $out;
            $data[$k]['in'] = $in;
        }
        $sql = "select p.RoleID  from {{%yin_shang_user}} us inner join {{%player}} p on p.UserID = us.UserID $where";
        $roleData = Yii::$app->db2->createCommand($sql)->queryAll();
        $roles = [];
        foreach($roleData as $r => $t){
            $roles[]= $t['RoleID'];
        }
        $roleIds = implode(',',$roles);
        if($roleIds){
            //账号总的赠送元宝数
            $outTotal = YuanbaoRole::find()->where(" roleId in ({$roleIds}) and added = 0 $ycWhere")->sum('money');
            //账号总的收入元宝数
            $inTotal = YuanbaoRole::find()->where(" roleId in ({$roleIds}) and added = 1 $ycWhere")->sum('money');
        }else{
            $outTotal = 0;
            $inTotal = 0;
        }
        //账号总数
        $sql = "select us.UserID  from {{%yin_shang_user}} us left join {{%player}} p on p.UserID = us.UserID $where group by us.UserID";
        $userIdData = Yii::$app->db2->createCommand($sql)->queryAll();
        $countTotal = count($userIdData);
        //账号角色总数
        $roleTotal = count($roles);
        return $this->render('ys-count',['page'=>$pages,'count'=>$count,'data'=>$data,'inTotal'=>$inTotal?$inTotal:0,'outTotal'=>$outTotal?$outTotal:0,'countTotal'=>$countTotal?$countTotal:0,'roleTotal'=>$roleTotal?$roleTotal:0]);
    }
    /**
     * 商人赠送数据
     * 数据详情
     */
    public function actionYsCountDetail(){
        $beginTime = Yii::$app->request->get('beginTime');
        $endTime = Yii::$app->request->get('endTime');
        $type = Yii::$app->request->get('type',1);//1-赠送 2-接收
        $roleId = Yii::$app->request->get('roleId','');
        $where = ' 1= 1 ';
        $count = 0;
        if($roleId){//获取账号的赠送元宝和收入元宝统计
            if($type ==1){
                $where .= " and roleId = '{$roleId}' and added = 0 and type = 3";
            }else{
                $where .= " and roleId = '{$roleId}' and added = 1 and type = 3";
            }
            if($beginTime){
                $begin = strtotime($beginTime);
                $where .= " and unix_timestamp(dateTime) >= $begin";
            }
            if($endTime){
                $end = strtotime($endTime);
                $where .= " and unix_timestamp(dateTime) <= $end";
            }
            $count = YuanbaoRole::find()->where($where)->count();
        }else{
            $where = ' 1 != 1';
        }
        $page = new Pagination(['totalCount'=>$count,'pageSize'=>20]);
        $data = YuanbaoRole::find()->where($where)->asArray()->offset($page->offset)->limit($page->limit)->all();
        return $this->render('ys-count-detail',['page'=>$page,'count'=>$count,'data'=>$data,'roleId'=>$roleId,'type'=>$type]);
    }
    /**
     * 在线实时人数
     */
    public function actionOnlineNumber(){
        $action = Yii::$app->controller->action->id;
        parent::setActionId($action);
        $servers = Server::getServers();
        $time = Yii::$app->request->get('beginTime');
        $serverId = Yii::$app->request->get('server');
        if(!$time){
            $time = time();
        }else{
            $time = strtotime($time);
        }
        $data = [];
        if($serverId){
            $count = OnlineCount::find()->where("WorldID = {$serverId} and upTime <= $time")->asArray()->orderBy('upTime desc')->one()['Count'];
            $count = $count?$count:0;
            $data[]= ['name'=>$serverId.'服','count'=>$count];
        }else{
            foreach($servers as $k => $v){
                $serverId = $v['id'];
                $count = OnlineCount::find()->where("WorldID = {$serverId} and upTime <= $time")->asArray()->orderBy('upTime desc')->one()['Count'];
                $count = $count?$count:0;
                $data[]= ['name'=>$v['name'],'count'=>$count];
            }
        }
        return $this->render('online-number',['data'=>$data,'servers'=>$servers]);
    }
    /**
     * 充值查询
     */
    public function actionRechargeQuery(){
        $action = Yii::$app->controller->action->id;
        parent::setActionId($action);
        $servers = Server::getServers();
        $beginTime = Yii::$app->request->get('beginTime');
        $endTime = Yii::$app->request->get('endTime');
        $serverId = Yii::$app->request->get('server');
        $name = Yii::$app->request->get('name');
        $roleId = Yii::$app->request->get('roleId');
        $where = " 1=1 ";
        if($serverId){
            $where .= " and c.WorldID = '{$serverId}'";
        }
        if($name){
            $roleId = Player::find()->select('group_concat(roleID) as ids')->where("Name = '{$name}'")->asArray()->one()['ids'];
            if($name){
                $where .= " and c.roleID in ($roleId)";
            }else{
                $where .= " and 1 > 2 ";
            }
        }
        if($roleId){
            $where .= " and c.roleID = '{$roleId}'";
        }
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
        $data = [];
        $totalMoney = 0;
        $totalCount = 0;
        for($i=$days;$i>=0;$i--){
            $dateTime = $monthBegin + 86400*$i;
            $date = date('Y-m-d',$dateTime);
            $end = $dateTime + 86399;
            //充值次数
            $rechargeCount = ChargeMoney::getTodayChargeCount($dateTime,$end,$where);
            //充值金额
            $recharge = ChargeMoney::getTodayChargeMoney($dateTime,$end,$where);
            $totalMoney += $recharge;
            $totalCount += $rechargeCount;
            $data[] = ['date'=>$date,'count'=>$rechargeCount,'recharge'=>$recharge];
        }
        return $this->render('recharge-query',['data'=>$data,'servers'=>$servers,'totalMoney'=>$totalMoney,'totalCount'=>$totalCount]);
    }
}