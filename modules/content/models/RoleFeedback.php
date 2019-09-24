<?php


namespace app\modules\content\models;


use yii\db\ActiveRecord;

class RoleFeedback extends ActiveRecord
{

    public static  function tableName(){
        return '{{%role_feedback}}';
    }

}