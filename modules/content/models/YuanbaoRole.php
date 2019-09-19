<?php

//在线人数
namespace app\modules\content\models;


use yii\db\ActiveRecord;

class YuanbaoRole extends ActiveRecord
{

    public static  function tableName(){
        return '{{%yuanbao_role}}';
    }

}