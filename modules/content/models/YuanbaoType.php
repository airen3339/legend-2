<?php
//公告记录

namespace app\modules\content\models;


use yii\db\ActiveRecord;

class YuanbaoType extends ActiveRecord
{

    public static  function tableName(){
        return '{{%yuanbao_type}}';
    }
}