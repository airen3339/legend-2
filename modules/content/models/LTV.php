<?php


namespace app\modules\content\models;


use yii\db\ActiveRecord;

class LTV extends ActiveRecord
{

    public static  function tableName(){
        return '{{%ltv_data}}';
    }
}