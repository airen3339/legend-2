<?php

//在线人数
namespace app\modules\content\models;


use yii\db\ActiveRecord;

class CurrencyData extends ActiveRecord
{

    public static  function tableName(){
        return '{{%currency_data}}';
    }

}