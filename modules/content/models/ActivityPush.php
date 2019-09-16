<?php


namespace app\modules\content\models;


use app\libs\Methods;
use yii\db\ActiveRecord;

class ActivityPush extends ActiveRecord
{

    public static  function tableName(){
        return '{{%activity_push}}';
    }
}