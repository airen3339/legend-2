<?php 
namespace app\modules\content\models;
use yii\db\ActiveRecord;
class Recharge extends ActiveRecord {
    public $cateData;

    public static function tableName(){
            return '{{%recharge}}';
    }

}
