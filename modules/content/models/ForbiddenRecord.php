<?php
//禁言封号记录

namespace app\modules\content\models;


use yii\db\ActiveRecord;

class ForbiddenRecord extends ActiveRecord
{

    public static  function tableName(){
        return '{{%forbidden_record}}';
    }
}