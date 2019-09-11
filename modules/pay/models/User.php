<?php 
namespace app\modules\pay\models;
use yii\db\ActiveRecord;
class User extends ActiveRecord {
    public $cateData;

    public static function tableName(){
            return '{{%user}}';
    }

}
