<?php


namespace app\modules\content\models;


use yii\db\ActiveRecord;

class SscActivity extends ActiveRecord
{
    public static function getDb(){
        return \Yii::$app->db;
    }
    public static  function tableName(){
        return '{{%ssc_activity}}';
    }
}