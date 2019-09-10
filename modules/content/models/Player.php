<?php


namespace app\modules\content\models;


use yii\db\ActiveRecord;

class Player extends ActiveRecord
{
    public static function getDb(){
        return \Yii::$app->db2;
    }
    public static  function tableName(){
        return '{{%player}}';
    }
    /**
     * 获取今日登录账号数
     */
    public static function getTodayLogin($dateTime,$end,$where){
        $sql = "select p.UserID from `user` u inner join player p on p.UserID = u.UserID where $where and LastLogin between $dateTime and $end";
        $data = \Yii::$app->db2->createCommand($sql)->queryAll();
        $total = count($data);
        return $total;
    }
}