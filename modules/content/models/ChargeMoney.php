<?php

// 充值金额记录表
namespace app\modules\content\models;


use yii\data\Pagination;
use yii\db\ActiveRecord;

class ChargeMoney extends ActiveRecord
{
    public static function getDb(){
        return \Yii::$app->db2;
    }
    public static  function tableName(){
        return '{{%chargemoney}}';
    }

    /**
     * 获取当日第一次充值的数据
     * 当日之前也没有进行过充值
     */
    public static function getTodayChargeData($beginTime,$endTime,$where)
    {
        //获取当日充值的角色ID
        $sql = "select c.roleID from chargemoney c inner join player p on p.RoleID = c.roleID inner join `user` u on u.UserID = p.UserID where $where and c.status = 2 and  unix_timestamp(c.finishTime) between $beginTime and $endTime group by c.roleID";
        $roleID = \Yii::$app->db2->createCommand($sql)->queryAll();
        $rechargeNum = 0;//充值人数
        $rechargeMoney = 0;//充值金额
        foreach ($roleID as $k => $v) {
            $roleId = $v['roleID'];
            $res = self::find()->where("unix_timestamp(finishTime) < $beginTime and roleID = '{$roleId}' and status = 2")->one();
            if (!$res) {//今天第一次充值
                $rechargeNum += 1;//
                $money = self::find()->where(" status = 2 and unix_timestamp(finishTime) between $beginTime and $endTime and roleId = '{$roleId}'")->orderBy('finishTime asc')->asArray()->one()['chargenum'];
                $rechargeMoney += $money;
            }
        }
        $data = ['rechargeNum' =>$rechargeNum, 'rechargeMoney' =>$rechargeMoney];
        return $data;
    }
    /**
     * 获取总充值金额 充值人数
     */
    public static function getChargeMoney($level,$where){
        $sql = "select sum(c.chargenum) as money from chargemoney c inner join player p on p.RoleID = c.roleID inner join `user` u on u.UserID = p.UserID where c.level = '{$level}' and c.status = 2 and $where";
        $money = \Yii::$app->db2->createCommand($sql)->queryOne()['money'];
        if($money){
            $sql = "select c.roleID from chargemoney c inner join player p on p.RoleID = c.roleID inner join `user` u on u.UserID = p.UserID where c.level = '{$level}' and c.status = 2 and $where group by c.roleID";
            $role = \Yii::$app->db2->createCommand($sql)->queryAll();
            $total = count($role);
        }else{
            $money = 0;
            $total = 0;
        }
        return ['money'=>$money,'total'=>$total];
    }
    /**
     * 获取充值排行查询
     */
    public static function getChargeRankQuery($where,$page=1,$pageSize=20){
        $sql = "select p.RoleID,p.Name,p.LastLogin,p.Ingot as currentYB,u.PackageFlag from chargemoney c inner join player p on p.RoleID = c.roleID inner join  `user` u on u.UserID = p.UserID where $where  group by c.roleID";
        $data = \Yii::$app->db2->createCommand($sql)->queryAll();
        $total = count($data);
        $pages = new Pagination(['totalCount'=>$total,'pageSize'=>20]);
        $limit = " limit ".($pageSize*($page-1)).",$pageSize";
        $sql = "select p.RoleID,p.Name,p.LastLogin,p.Ingot as currentYB,u.PackageFlag,sum(c.chargenum) as depositMoney from chargemoney c inner join player p on p.RoleID = c.roleID inner join  `user` u on u.UserID = p.UserID where $where and c.status = 2  group by c.roleID order by depositMoney desc ";
        $sql .= $limit;
        $data = \Yii::$app->db2->createCommand($sql)->queryAll();
        foreach($data as $k=> $v){
//            $depositMoney = ChargeMoney::find()->where(" status = 2 and roleID = '{$v['RoleID']}'")->sum('chargenum');//充值金额
            $depositMoney = $v['depositMoney'];//充值金额
            if($depositMoney){
                $lastRechTime = ChargeMoney::find()->where("status = 2 and roleID = '{$v['RoleID']}'")->orderBy('finishTime desc')->one()['finishTime'];
            }else{
                $depositMoney = 0;
                $lastRechTime = '';
            }
            $lastLogin = date('Y-m-d H:i',$v['LastLogin']);
            $data[$k]['depositMoney'] = $depositMoney;
            $data[$k]['lastRechTime'] = $lastRechTime;
            $data[$k]['lastLogin'] = $lastLogin;
        }
        return ['data'=>$data,'page'=>$pages,'count'=>$total];
    }
    /**
     * 获取今日充值人数
     */
    public static function getTodayChargeNum($dateTime,$end,$where){
        $sql = "select * from chargemoney c inner join player p on p.RoleID = c.roleID inner join `user` u on u.UserID = p.UserID where $where and c.status = 2 and  unix_timestamp(c.finishTime) between $dateTime and $end group by c.roleID";
        $data = \Yii::$app->db2->createCommand($sql)->queryAll();
        $total = count($data);
        return $total;
    }
    /**
     * 获取今日充值次数
     */
    public static function getTodayChargeCount($dateTime,$end,$where){
        $sql = "select c.roleID from chargemoney c inner join player p on p.RoleID = c.roleID inner join `user` u on u.UserID = p.UserID where $where and c.status = 2 and  unix_timestamp(c.finishTime) between $dateTime and $end";
        $data = \Yii::$app->db2->createCommand($sql)->queryAll();
        $total = count($data);
        return $total;
    }
    /**
     * 获取今日充值次数
     */
    public static function getTodayChargeMoney($dateTime,$end,$where){
        $sql = "select sum(c.chargenum) as money from chargemoney c inner join player p on p.RoleID = c.roleID inner join `user` u on u.UserID = p.UserID where $where and c.status = 2 and  unix_timestamp(c.finishTime) between $dateTime and $end";
        $data = \Yii::$app->db2->createCommand($sql)->queryOne();
        $total = $data['money']?$data['money']:0;
        return $total;
    }
}