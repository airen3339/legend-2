<?php
//公告记录

namespace app\modules\content\models;


use yii\db\ActiveRecord;

class RoleActivity extends ActiveRecord
{

    public static  function tableName(){
        return '{{%role_activity}}';
    }
}