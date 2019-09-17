<?php


namespace app\modules\content\models;


use yii\db\ActiveRecord;

class LTV extends ActiveRecord
{

    public static  function tableName(){
        return '{{%ltv_data}}';
    }
    /**
     * 获取区服数据
     */
    public static function getServers(){
        $servers = [
            ['id' => 100, 'name' => '外服'],
            ['id' => 900, 'name' => '品鉴'],
            ['id' => 903, 'name' => '刘佳林'],
        ];
        return $servers;
    }
}