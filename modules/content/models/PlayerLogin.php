<?php


namespace app\modules\content\models;


use yii\db\ActiveRecord;

class PlayerLogin extends ActiveRecord
{
    public static  function tableName(){
        return '{{%player_login}}';
    }
}