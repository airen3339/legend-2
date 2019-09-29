<?php


namespace app\modules\content\models;


use yii\db\ActiveRecord;

class LTVMoney extends ActiveRecord
{

    public static  function tableName(){
        return '{{%ltv_money_data}}';
    }
    //统计ltv充值数据
    public static function recordLtvMoneyData($today){
//        $today = date('Y-m-d');
        $beginTime = strtotime($today);
        $endTime = $beginTime + 86399;
        $dates = LTV::find()->asArray()->all();
        foreach($dates as $k => $v){
            $ltvId = $v['id'];
            $deviceMsg = $v['deviceMsg'];
            $loginMsg = $v['loginMsg'];
            $channel = $v['channel'];
            if($deviceMsg){//记录设备充值数据
                $roleIds = LTV::deviceGetRoleId($deviceMsg);
                if($roleIds){
                    $sql = "select sum(c.chargenum) as money from chargemoney c inner join player p on p.RoleID = c.roleID inner join `user` u on u.UserID = p.UserID and u.PackageFlag = '{$channel}' and ( unix_timestamp(c.finishTime) between $beginTime and $endTime )  and c.status = 2 and c.roleId in ($roleIds)";
                    $deviceMoney = \Yii::$app->db2->createCommand($sql)->queryOne()['money'];
                }else{
                    $deviceMoney = 0;
                }
                $deviceMoney = $deviceMoney?$deviceMoney:0;
            }else{
                $deviceMoney = 0;
            }
            if($loginMsg){//记录账号充值数据
                $sql = "select sum(c.chargenum) as money from chargemoney c inner join player p on p.RoleID = c.roleID inner join `user` u on u.UserID = p.UserID and u.PackageFlag = '{$channel}' and ( unix_timestamp(c.finishTime) between $beginTime and $endTime )  and c.status = 2 and c.roleId in ($loginMsg)";
                $loginMoney = \Yii::$app->db2->createCommand($sql)->queryOne()['money'];
                $loginMoney = $loginMoney?$loginMoney:0;
            }else{
                $loginMoney = 0;
            }
            $model = new self();
            $model->date = $today;
            $model->ltvId = $ltvId;
            $model->channel = $channel;
            $model->deviceMoney = $deviceMoney;
            $model->loginMoney = $loginMoney;
            $model->createTime = time();
            $model->save();
        }
    }
}