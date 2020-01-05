<?php 
namespace app\modules\pay\models;
use yii\db\ActiveRecord;
class Notify extends ActiveRecord {
    public $cateData;

    public static function tableName(){
            return '{{%notify}}';
    }

}
