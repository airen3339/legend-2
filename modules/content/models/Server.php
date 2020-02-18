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
            $data[] = ['id'=>$v['game_id'],'realId'=>$v['game_real'],'name'=>$v['game_id'].'区'];
        }
        return $data;
    }
    /**
     * 多选功能
     * 获取区服数据
     */
    public static function getServerData($id){
        $servers = self::find()->asArray()->all();
        if($id){
            $idArr = explode(",",$id);
        }
        foreach($servers as $k => $v){
            $servers[$k]['id'] = $v['game_id'];
            $servers[$k]['text'] = $v['game_id'].'服';
            if($id){
                if(in_array($v['game_id'],$idArr)){
                    $servers[$k]['checked'] = true;
                }
            }
        }
        array_unshift($servers,['id'=>0,'text'=>'全服']);
        return $servers;
    }
}