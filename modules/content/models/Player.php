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
}