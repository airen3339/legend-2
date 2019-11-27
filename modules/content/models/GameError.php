<?php
//公告记录

namespace app\modules\content\models;


use yii\db\ActiveRecord;

class GameError extends ActiveRecord
{

    public static  function tableName(){
        return '{{%game_error}}';
    }
}