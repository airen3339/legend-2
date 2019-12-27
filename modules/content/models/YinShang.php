<?php
//银商信息表

namespace app\modules\content\models;


use yii\db\ActiveRecord;

class YinShang extends ActiveRecord
{

    public static  function tableName(){
        return '{{%yin_shang}}';
    }

}