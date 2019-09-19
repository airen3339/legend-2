<?php

//在线人数
namespace app\modules\content\models;


use yii\db\ActiveRecord;

class LoginRole extends ActiveRecord
{

    public static  function tableName(){
        return '{{%login_role}}';
    }

}