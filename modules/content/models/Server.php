<?php
//区服表

namespace app\modules\content\models;


use yii\db\ActiveRecord;

class Server extends ActiveRecord
{

    public static  function tableName(){
        return '{{%servers_game}}';
    }
    /**
     * 获取区服数据
     */
    public static function getServers(){
        $servers = self::find()->asArray()->all();
        $data = [];
        foreach($servers as $k => $v){
            $data[] = ['id'=>$v['game_id'],'realId'=>$v['game_real'],'name'=>$v['game_id'].'服'];
        }
        $data[] = ['id'=>'903','realId'=>'9030','name'=>'903服'];
        $data[] = ['id'=>'900','realId'=>'9000','name'=>'900服'];
        return $data;
    }
}