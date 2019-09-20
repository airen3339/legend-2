<?php


namespace app\modules\content\models;


use yii\db\ActiveRecord;

class User extends ActiveRecord
{
    public static function getDb(){
        return \Yii::$app->db2;
    }
    public static  function tableName(){
        return '{{%user}}';
    }
    /**
     * 获取当前时间段的注册账号登录数
     * 一天时间
     */
    public static function  getTodayRegisterLogin($beginTime,$endTime,$where){
        $sql = "select count(p.UserID) as total from `user` as u inner join `player` as p on p.UserID = u.UserID where $where and (unix_timestamp(u.CreateDate) between $beginTime and $endTime) and ( p.LastLogin between $beginTime and $endTime ) ";
        $total = \Yii::$app->db2->createCommand($sql)->queryOne()['total'];
        return $total;
    }
    /**
     * 获取当前时间段的注册账号登录设备数
     * 一天时间
     */
    public static function  getTodayRegisterLoginDevice($beginTime,$endTime,$where){
        $sql = "select count(p.UserID) as total from `user` as u inner join `player` as p on p.UserID = u.UserID where $where and (unix_timestamp(u.CreateDate) between $beginTime and $endTime) and ( p.LastLogin between $beginTime and $endTime ) group by u.DevString";
        $total = \Yii::$app->db2->createCommand($sql)->queryOne()['total'];
        return $total?$total:0;
    }
    /**
     * 当日总设备登录数
     */
    public static function getTodayLoginDevice($beginTime,$endTime,$where){
        $sql = "select count(p.UserID) as total from `user` as u inner join `player` as p on p.UserID = u.UserID where $where and p.LastLogin between $beginTime and $endTime  group by u.DevString";
        $total = \Yii::$app->db2->createCommand($sql)->queryOne()['total'];
        return $total?$total:0;
    }
    /**
     * 获取版本渠道
     * user注册表分组获取
     */
    public static function getChannel(){
        $channel = [];
        $data = self::find()->select('PackageFlag')->groupBy('PackageFlag')->asArray()->all();
        foreach($data as $k => $v){
            $channel[]=$v['PackageFlag'];
        }
        return $channel;
    }
}
