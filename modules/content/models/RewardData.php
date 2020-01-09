<?php

//在线人数
namespace app\modules\content\models;


use yii\db\ActiveRecord;

class RewardData extends ActiveRecord
{

    public static function getDb(){
        return \Yii::$app->db3;
    }
    public static  function tableName(){
        return '{{%dice_log}}';
    }
}