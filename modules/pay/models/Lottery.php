<?php 
namespace app\modules\pay\models;
use yii\db\ActiveRecord;
class Lottery extends ActiveRecord {
    public $cateData;

    public static function tableName(){
            return '{{%lottery}}';
    }

}
