<?php
//公告记录

namespace app\modules\content\models;


use yii\db\ActiveRecord;

class ActivityType extends ActiveRecord
{

    public static  function tableName(){
        return '{{%activity_type}}';
    }
}