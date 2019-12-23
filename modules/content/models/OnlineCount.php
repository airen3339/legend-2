<?php
//实时人数记录

namespace app\modules\content\models;


use yii\db\ActiveRecord;

class OnlineCount extends ActiveRecord
{

    public static  function tableName(){
        return '{{%online_Count}}';
    }
}