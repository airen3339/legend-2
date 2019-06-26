<?php

namespace app\models;

use yii\db\ActiveRecord;

class Select extends ActiveRecord
{
    public static function tableName(){
        return '{{%select}}';
    }
}
