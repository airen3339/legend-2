<?php
/**
 * 定时任务api
 */

namespace app\modules\content\controllers;


use app\modules\content\models\ChargeMoney;
use app\modules\content\models\LTV;
use app\modules\content\models\Player;
use app\modules\content\models\PlayerChannelRegister;
use app\modules\content\models\PlayerLogin;
use app\modules\content\models\PlayerRegister;
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
        //获取今日新增的用户数据
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
        $channels = ['official','my','self'];
        foreach($channels as $l => $t){
            $sql = " select p.RoleID from player p inner join `user` u on u.UserID = p.UserID where u.PackageFlag = '{$t}' and p.RoleID in ($roleIds)";
            $roles = \Yii::$app->db2->createCommand($sql)->queryAll();
            $ids = [];
            foreach($roles as $e => $q){
                $ids[] = $q['RoleID'];
            }
            $channel_roleIds = implode(',',$ids);
            if($channel_roleIds){
                //当前渠道今日新增账号登录数
                $channel_user = Player::find()->select("RoleID")->where("( unix_timestamp(CreateDate) between $begin and $end )  and RoleID in ($channel_roleIds)")->asArray()->all();
                $channel_total = count($channel_user);
                //当前渠道今日登录用户
                $channel_loginUser = Player::find()->select("RoleID")->where("( LastLogin between $begin and $end ) and RoleID in ($channel_roleIds)")->asArray()->all();
                $loginTotal = count($channel_loginUser);
            }else{
                $channel_total = 0;
                $loginTotal = 0;
            }
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
        $channel = ['official','my','self'];
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
}