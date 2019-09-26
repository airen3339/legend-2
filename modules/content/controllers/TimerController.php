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
use app\modules\content\models\Notice;
use app\modules\content\models\Player;
use app\modules\content\models\PlayerChannelRegister;
use app\modules\content\models\PlayerLogin;
use app\modules\content\models\PlayerRegister;
use app\modules\content\models\Server;
use app\modules\content\models\User;
use app\modules\content\models\YuanbaoRole;
use app\modules\pay\models\Recharge;
use function GuzzleHttp\Psr7\str;
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
            $sql = "select p.* from `user` u inner join `player` p on p.UserID = u.UserID where u.PackageFlag = '{$v}' and ( unix_timestamp(u.CreateDate) between $begin and $end ) ";
            $amount = \Yii::$app->db2->createCommand($sql)->queryAll();
            $login = count($amount);
            //新增设备数
            $sql .= " group by u.DevString";
            $device = \Yii::$app->db2->createCommand($sql)->queryAll();
            $deviceCount = count($device);
            //渠道今日充值金额
            $sql = "select sum(c.chargenum) as money from chargemoney c inner join player p on p.RoleID = c.roleID inner join `user` u on u.UserID = p.UserID and u.PackageFlag = '{$v}' and ( unix_timestamp(c.finishTime) between $begin and $end )  and c.status = 2 ";
            $money = \Yii::$app->db2->createCommand($sql)->queryOne()['money'];
            $model = new LTV();
            $model->date = $today;
            $model->money = $money?$money:0;
            $model->device = $deviceCount;
            $model->login = $login;
            $model->createTime = time();
            $model->channel = $v;
            $model->save();
        }
    }
    /**
     * 角色登录日志数据记录
     * 分区服
     */
    public function actionLoginData(){
        $date = date('Y-m-d');
        $servers = Server::getServers();//获取区服
        $url = 'http://192.168.0.30/logs/TLog/';
        foreach($servers as $k => $v) {
            $dat = str_replace('-','',$date);
            //获取日志文件并统计
            $fileName = 'Tlog.' . $v['id'] . '.0_' . $dat . '.log';
            $path = $url . $fileName;
            if (file_exists($path)) {
                $file = file_get_contents($path);
                preg_match_all('/PlayerLogin(.*)+/', $file, $arrLogin);
                $login = $arrLogin[0];
//                $loginData = [];//登录数据
                $loginTime = [];//登录时间
                preg_match_all('/PlayerLogout(.*)+/', $file, $arrLoginOut);
                $loginOut = $arrLoginOut[0];
                $loginOutData = [];//退出数据
                //退出时间处理
                foreach ($loginOut as $pp => $oo) {//解析退出数据统计
                    $loginOutArr = explode('|', $oo);//键值对应 1-区服 2-时间 6-设备号 24-角色id
                    $key = 'role' . $loginOutArr[24];
                    $loginOutData[$key][] = ['serverId' => $loginOutArr[1], 'loginOutTime' => $loginOutArr[2], 'roleId' => $loginOutArr[18]];
                }
                $site = 0;//对应的登出位置
                //登录数据处理 解析 对应退出 读入数据库
                foreach ($login as $p => $o) {//解析登录数据统计
                    $loginArr = explode('|', $o);//键值对应 1-区服 2-时间 6-设备号 18-角色id 19-姓名 29-登录ip
                    $currTime = strtotime($loginArr[2]);//当前登录时间
                    $roleId = $loginArr[18];
                    $key = 'role' . $roleId;
                    $code = 0;//数据库记录识别 0-不记录 1-记录
                    if (isset($loginTime[$key])) {//已有该角色id登录数据
                        if (($loginTime[$key] + 60) < $currTime) {//如果该角色下次登录时间大于上次登录时间一分钟才算
//                            $loginData[$key][]= ['serverId'=>$loginArr[1],'loginTime'=>$loginArr[2],'device'=>$loginArr[6],'roleId'=>$loginArr[18],'name'=>$loginArr[19],'ip'=>$loginArr[29]];
                            $code = 1;
                            $site++;
                            $loginTime[$key] = $currTime;//更新登录时间 方便下次比较
                        }
                    } else {
//                        $loginData[$key][]= ['serverId'=>$loginArr[1],'loginTime'=>$loginArr[2],'device'=>$loginArr[6],'roleId'=>$loginArr[18],'name'=>$loginArr[19],'ip'=>$loginArr[29]];
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
        $date = date('Y-m-d');
        $servers = Server::getServers();//获取区服
        $url = IndexDir.'/files/';
        $url = 'http://192.168.0.30/logs/TLog/';
        foreach($servers as $k => $v) {
            $fileName = "lua_log-{$v['id']}-$date.txt";
            $path = $url.$fileName;
            var_dump($path);
            if($v['id'] ==100){
                $content = file_get_contents($path);
                var_dump($content);
            }else{
                echo $v['id'];
                $content = file_get_contents($path);
                var_dump($content);
            }
            var_dump(file_exists($path));
            if(file_exists($path)){
                $content = file_get_contents($path);
                $content = trim($content);
                $content = explode("legend",$content);
                var_dump($content);
                foreach($content as $p => $m){
                    var_dump($m);
                    if(!trim($m)){
                        continue;
                    }
                    $arr = explode('@',trim($m));//键值 0-时间 1-type类型 2-角色id 3-增加减少 4-金额 5-说明
                    //记录用户消费数据
                    $type = self::getData($arr[1]);
                    $model = new YuanbaoRole();
                    $model->date = $date;
                    $model->serverId = $v['id'];
                    $model->roleId = self::getData($arr[2]);
                    $model->dateTime = $date." ".$arr[0];
                    $model->money = self::getData($arr[4]);
                    $model->type = $type;
                    $model->added = self::getData($arr[3]);
                    if($type == 6){//商城购买
                        $remark = str_replace('explain:','',$arr[5]);
                        $patterns = "/\d+/"; //第一种
                        preg_match_all($patterns,$remark,$array);
                        $toolId = isset($array[0][0])?$array[0][0]:0;
                        if($toolId){
                            $toolName = Item::find()->where("itemid = $toolId")->asArray()->one()['name'];
                            $remark .= '商品名称：'.$toolName;
                        }
                        $model->remark = $remark;
                    }else{
                        $model->remark = str_replace('explain:','',$arr[5]);
                    }
                    $model->createTime = time();
                    $model->save();
                }
            }else{
                var_dump(33);
            }
            //统计元宝消耗 4-元宝充值
            $arr = YuanbaoRole::getTypes(2);//获取元宝操作类型
            foreach($arr as $t => $y){// 1-元宝兑换 2-时时彩下注 3-赠送元宝 4-充值元宝 5-用户送花 6-用户月卡
                if(in_array($t,[1])){//元宝兑换 可有增加
                    //增加
                    $add = YuanbaoRole::find()->where(" date = '{$date}' and serverId = '{$v['id']}' and type = $t and added = 1")->sum('money');
                    $model = new CurrencyData();
                    $model->date = $date;
                    $model->serverId = $v['id'];
                    $model->type = 1;//1-元宝
                    $model->typeObject = $t;
                    $model->number = $add?$add:0;
                    $model->added = 1;
                    $model->remark = $y;
                    $model->createTime = time();
                    $model->save();
                }
                //消耗
                $reduce = YuanbaoRole::find()->where(" date = '{$date}' and serverId = '{$v['id']}' and type = $t and added = 0")->sum('money');
                $model = new CurrencyData();
                $model->date = $date;
                $model->serverId = $v['id'];
                $model->type = 1;//1-元宝
                $model->typeObject = $t;
                $model->number = $reduce?$reduce:0;
                $model->added = 0;
                $model->remark = $y;
                $model->createTime = time();
                $model->save();
            }
            //记录元宝充值 收入  type 4
            $number = Recharge::find()->where("server_id = {$v['id']} and status = 2 and from_unixtime(createTime,'%Y-%m-%d') = '{$date}'")->sum('yuanbao');
            $model = new CurrencyData();
            $model->date = $date;
            $model->serverId = $v['id'];
            $model->type = 1;//1-元宝
            $model->typeObject = 4;
            $model->number = $number?$number:0;
            $model->added = 1;
            $model->remark = '元宝充值';
            $model->createTime = time();
            $model->save();
        }
    }
    /**
     * 格式数据获取
     */
    public static function getData($str){
        if($str){
            $str = trim($str);
            $arr = explode(':',$str);
            if(count($arr) ==2 ){
                return $arr[1];
            }else{
                return '';
            }
        }else{
            return '';
        }
    }
    /**
     * 公告内容判断
     * 超出公告时间清楚公告文件内容
     */
    public function actionCheckIndexNotice(){
        //查询最新公告
        $today = strtotime(date('Y-m-d'));
        $notice = Notice::find()->where(" unix_timestamp(beginTime) <= $today and $today <= unix_timestamp(endTime)")->orderBy('beginTime desc')->asArray()->one();
        if($notice){
            $content = $notice['content'];
        }else{
            $content = '';
        }
        $path = fopen(IndexDir.'/files/notice/indexNotice.txt','w');
        fwrite($path, mb_convert_encoding( $content, 'UTF-8', mb_detect_encoding($content) ));
        fclose($path);
    }
}