<?php
/**
 * 定时任务api
 */

namespace app\modules\content\controllers;


use app\modules\content\models\ChargeMoney;
use app\modules\content\models\CurrencyData;
use app\modules\content\models\Item;
use app\modules\content\models\LoginData;
use app\modules\content\models\LoginRole;
use app\modules\content\models\LTV;
use app\modules\content\models\LTVMoney;
use app\modules\content\models\MailReceive;
use app\modules\content\models\Notice;
use app\modules\content\models\Player;
use app\modules\content\models\PlayerChannelRegister;
use app\modules\content\models\PlayerLogin;
use app\modules\content\models\PlayerRegister;
use app\modules\content\models\QuestionCategory;
use app\modules\content\models\Server;
use app\modules\content\models\User;
use app\modules\content\models\YuanbaoRole;
use app\modules\pay\models\Recharge;
use function GuzzleHttp\Psr7\str;
use Think\Exception;
use yii\web\Controller;

class TimerController extends Controller
{
    /**
     * 当天用户登录数据获取
     * 数据写入
     * 留存数据
     *
     */
    public function actionRetain(){
        $today = date('Y-m-d');
        $begin = strtotime($today);
        $end = $begin + 86399;
        //获取今日新增的用户数据 登录数据
        $user = Player::find()->select("RoleID")->where("unix_timestamp(CreateDate) between $begin and $end")->asArray()->all();
        $total = count($user);
        $time = time();
        //今日登录用户
        $loginUser = Player::find()->select("RoleID")->where("LastLogin between $begin and $end")->asArray()->all();
        $loginTotal = count($loginUser);
        //开启事务
        $tr = \Yii::$app->db->beginTransaction();
        $save = 0;
        $roleIds = '';
        foreach($user as $t => $r){
            $roleIds .= ','.$r['RoleID'];
        }
        $roleIds = trim($roleIds,',');
        foreach($loginUser as $k => $v){
            $login = new PlayerLogin();
            $login->date = $today;
            $login->roleId = $v['RoleID'];
            $login->createTime = $time;
            $res = $login->save();
            if($res){
                $save++;
            }else{
                break;
            }
        }
        if($save == $loginTotal){
            $register = new PlayerRegister();
            $register->date = $today;
            $register->roleIds = $roleIds;
            $register->total = $total;
            $register->accountDau = $loginTotal;
            $register->createTime = $time;
            $result = $register->save();
            if($result){
                $tr->commit();
            }else{
                $tr->rollBack();
            }
        }else{
            $tr->rollBack();
        }
        //记录当天不同渠道的留存数据
        $channels = User::getChannel();
        foreach($channels as $l => $t){
            if($roleIds){//今日新增用户账号大于0
                //今日渠道新增用户账号登录
                $sql = " select p.RoleID from player p inner join `user` u on u.UserID = p.UserID where u.PackageFlag = '{$t}' and p.RoleID in ($roleIds)";
                $roles = \Yii::$app->db2->createCommand($sql)->queryAll();
                $ids = [];
                $channel_total = 0;
                foreach($roles as $e => $q){
                    $ids[] = $q['RoleID'];
                    $channel_total += 1;
                }
                $channel_roleIds = implode(',',$ids);
            }else{
                $channel_roleIds = '';
                $channel_total = 0;
            }
            //当前渠道今日登录用户
            $sql = " select count(p.RoleID) as total from player p inner join `user` u on u.UserID = p.UserID where u.PackageFlag = '{$t}' and ( p.LastLogin between $begin and $end )";
            $loginTotal = \Yii::$app->db2->createCommand($sql)->queryOne()['total'];
            $model = new PlayerChannelRegister();
            $model->date = $today;
            $model->channel = $t;
            $model->roleIds = $channel_roleIds;
            $model->total = $channel_total;
            $model->accountDau = $loginTotal;
            $model->createTime = $time;
            $model->save();
        }
    }

    /**
     * 当日ltv数据记录
     * 分渠道统计
     */
    public function actionLtvData(){
        $channel = User::getChannel();
        $today = date('Y-m-d');
        $begin = strtotime($today);
        $end = $begin + 86399;
        foreach($channel as $k => $v){
            //渠道今日新增账号数
            $sql = "select p.RoleID from `user` u inner join `player` p on p.UserID = u.UserID where u.PackageFlag = '{$v}' and ( unix_timestamp(u.CreateDate) between $begin and $end ) ";
            $amount = \Yii::$app->db2->createCommand($sql)->queryAll();
            $login = count($amount);
            $loginMsg = '';//新增账号信息
            foreach($amount as $y => $p){
                $loginMsg .= $p['RoleID'].',';
            }
            $loginMsg = trim($loginMsg,',');
            //新增设备数
            $sql = " select u.DevString from `user` u inner join `player` p on p.UserID = u.UserID where u.PackageFlag = '{$v}' and ( unix_timestamp(u.CreateDate) between $begin and $end )  group by u.DevString";
            $device = \Yii::$app->db2->createCommand($sql)->queryAll();
            $deviceCount = count($device);
            $deviceMsg = '';//新增设备信息
            foreach($device as $t => $g){
                $deviceMsg .= $g['DevString'].',';
            }
            $deviceMsg = trim($deviceMsg,',');
            //渠道今日充值金额
//            $sql = "select sum(c.chargenum) as money from chargemoney c inner join player p on p.RoleID = c.roleID inner join `user` u on u.UserID = p.UserID and u.PackageFlag = '{$v}' and ( unix_timestamp(c.finishTime) between $begin and $end )  and c.status = 2 ";
//            $money = \Yii::$app->db2->createCommand($sql)->queryOne()['money'];
            $model = new LTV();
            $model->date = $today;
//            $model->money = $money?$money:0;
            $model->device = $deviceCount;
            $model->login = $login;
            $model->createTime = time();
            $model->channel = $v;
            $model->deviceMsg = $deviceMsg;
            $model->loginMsg = $loginMsg;
            $model->save();
        }
        //统计当日的充值数据
        LTVMoney::recordLtvMoneyData();
    }
    /**
     * 角色登录日志数据记录
     * 分区服
     */
    public function actionLoginData(){
        ini_set('user_agent','Mozilla/4.0 (compatible; MSIE 5.00; Windows 98)');
        $date = date('Y-m-d');
        $servers = Server::getServers();//获取区服
//        $url = IndexDir.'/files/';
        $url = 'http://192.168.0.30/logs/TLog/';
        foreach($servers as $k => $v) {
            $dat = str_replace('-','',$date);
            //获取日志文件并统计
            $fileName = 'Tlog.' . $v['id'] . '.0_' . $dat . '.log';
            $path = $url . $fileName;
            try{
                $file = file_get_contents($path);
                $file = str_replace(array("\n","\r","\t"),'',$file);
                preg_match_all('/PlayerLogin(\|([^\|]+))+(\|\|)([^|])((\|)([^\|]+))+(\|\|)([^|]+)(\|\|\|\|)([^|]+)((\|)([^\|]+))+MoneyFlow/', $file, $arrLogin);
                $login = $arrLogin[0];
                $loginTime = [];//登录时间
                preg_match_all('/PlayerLogout(\|([^\|]+))+(\|\|)([^|])((\|)([^\|]+))+(\|\|)([^|]+)(\|\|\|\|)([^|]+)((\|)([^\|]+))+MoneyFlow/', $file, $arrLoginOut);
                $loginOut = $arrLoginOut[0];
                $loginOutData = [];//退出数据
                //退出时间处理
                foreach ($loginOut as $pp => $oo) {//解析退出数据统计
                    $oo = str_replace('MoneyFlow','',$oo);
                    $loginOutArr = explode('|', $oo);//键值对应 1-区服 2-时间 6-设备号 24-角色id
                    $key = 'role' . $loginOutArr[24];
                    $loginOutData[$key][] = ['serverId' => $loginOutArr[1], 'loginOutTime' => $loginOutArr[2], 'roleId' => $loginOutArr[18]];
                }
                $site = 0;//对应的登出位置
                //登录数据处理 解析 对应退出 读入数据库
                foreach ($login as $p => $o) {//解析登录数据统计
                    $o = str_replace('MoneyFlow','',$o);
                    $loginArr = explode('|', $o);//键值对应 1-区服 2-时间 6-设备号 18-角色id 19-姓名 29-登录ip
                    $currTime = strtotime($loginArr[2]);//当前登录时间
                    $roleId = $loginArr[18];
                    $key = 'role' . $roleId;
                    $code = 0;//数据库记录识别 0-不记录 1-记录
                    if (isset($loginTime[$key])) {//已有该角色id登录数据
                        if (($loginTime[$key] + 60) < $currTime) {//如果该角色下次登录时间大于上次登录时间一分钟才算
                            $code = 1;
                            $site++;
                            $loginTime[$key] = $currTime;//更新登录时间 方便下次比较
                        }
                    } else {
                        $code = 1;
                        $site = 0;
                        $loginTime[$key] = $currTime;//更新登录时间 方便下次比较
                    }
                    //数据记录
                    if ($code == 1) {
                        $loginOutTime = '';
                        //获取对应的登出时间
                        if (isset($loginOutData[$key][$site])) {//是否有对应位置的登出时间
                            $loginOutTime = $loginOutData[$key][$site]['loginOutTime'];
                            $begin = strtotime($loginArr[2]);
                            $end = strtotime($loginOutTime);
                            if ($begin > $end) {//对应的登录时间小于于登出时间 记录该条数据
                                $loginOutTime = '';
                            }
                        } else {//没有默认一直在线到第二天
                            if (isset($loginOutData[$key][$site - 1])) {//对应的登出记录是否有上一条
                                $loginOutTime = date("Y-m-d 23:59:59");//有上一条可记录 没有的话上一条的登出时间已记录到凌晨 不需要再记录
                            }
                        }
                        if ($loginOutTime) {
                            $model = new LoginRole();
                            $model->roleId = $roleId;
                            $model->date = $date;
                            $model->loginTime = $loginArr[2];
                            $model->loginOutTime = $loginOutTime;
                            $model->serverId = $loginArr[1]/10;
                            $model->device = $loginArr[6];
                            $model->ip = $loginArr[29];
                            $model->name = $loginArr[19];
                            $model->createTime = time();
                            $model->save();
                        }
                    }
                }
            }catch(\Exception $e){

            }
            //统计当天各个小时的在线人数
            $data = [];
            for($i=1;$i<=24;$i++){
                $beginTime = strtotime($date)+($i-1)*3600;
                $endTime = $beginTime + 3600;
                //登录时间在时间段内 或者 退出时间在此时间段内 或者 登录退出时间在此时间段外
                $where = " date = '{$date}' and serverId = {$v['id']} and ( ( unix_timestamp(loginTime) between $beginTime and $endTime ) or ( unix_timestamp(loginOutTime) between $beginTime and $endTime ) or ( unix_timestamp(loginTime) < $beginTime and unix_timestamp(loginOutTime) > $endTime ) )";
                $number = LoginRole::find()->where($where)->groupBy('roleId')->count();
                $data[] = $number?$number:0;
            }
            $model = new LoginData();
            $model->serverId = $v['id'];
            $model->date = $date;
            $model->data = implode(',',$data);
            $model->createTime = time();
            $model->save();
        }
    }
    /**
     * 角色消耗日志记录
     * 分区服
     * type 1-元宝兑换 2-时时彩下注 3-赠送元宝 4-充值元宝
     */
    public function actionYuanbaoData(){
        YuanbaoRole::getYuanbaoData();
    }

    /**
     * 公告内容判断
     * 超出公告时间清楚公告文件内容
     */
    public function actionCheckIndexNotice(){
        //查询最新公告
        $today = strtotime(date('Y-m-d'));
        $notice = Notice::find()->where(" unix_timestamp(beginTime) <= $today and $today <= unix_timestamp(endTime)")->orderBy('beginTime desc')->asArray()->one();
        Notice::updateAll(['current'=>0],"type = 1 and current = 1");//清除当前公告状态
        if($notice){
            $content = $notice['content'];
            Notice::updateAll(['current'=>1]," id = {$notice['id']}");//添加当前公告状态
        }else{
            $content = '';
        }
        $path = fopen(IndexDir.'/files/notice/indexNotice.txt','w');
        fwrite($path, mb_convert_encoding( $content, 'UTF-8', mb_detect_encoding($content) ));
        fclose($path);
    }
    /**
     * 定时记录用户邮件接收日志
     */
    public function actionRoleMailReceive(){
        MailReceive::getMailLog();
    }
}