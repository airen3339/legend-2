<?php

//在线人数
namespace app\modules\content\models;


use yii\db\ActiveRecord;

class LoginData extends ActiveRecord
{

    public static  function tableName(){
        return '{{%login_data}}';
    }

}