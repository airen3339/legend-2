<?php

//在线人数
namespace app\modules\content\models;


use yii\db\ActiveRecord;

class LotteryData extends ActiveRecord
{

    public static function getDb(){
        return \Yii::$app->db3;
    }
    public static  function tableName(){
        return '{{%lottery_log}}';
    }
}