<?php


namespace app\modules\content\models;


use yii\db\ActiveRecord;

class PlayerRegister extends ActiveRecord
{
    public static  function tableName(){
        return '{{%player_register}}';
    }
}