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
    /*
     *
     * 获取指定日期的新增数据
     * type 1-账号数 2-设备数
     */
    public static  function getAllMsg($date,$type =1){
        $dates = self::find()->where("date = '{$date}'")->asArray()->all();
        $countObject = [];
        if($dates){
            foreach($dates as $k => $v){
                $key = $type ==1?'loginMsg':'deviceMsg';
                if($v[$key]){
                    $countObject[] = $v[$key];
                }
            }
            $countObjectStr = implode(',',$countObject);
        }else{
            $countObjectStr = '';
        }
        return $countObjectStr;
    }
    /**
     * 设备号获取对应的角色id
     */
    public static function deviceGetRoleId($objectDevice){
        if($objectDevice){
            $deviceArr = explode(',',$objectDevice);
            $ids = [];
            foreach($deviceArr as $e => $t){
                $t = trim($t);
                $sql = "select p.RoleID from `user` u inner join player p on p.UserID = u.UserID where u.DevString = '{$t}'";
                $arr = \Yii::$app->db2->createCommand($sql)->queryAll();
                foreach($arr as $k => $v){
                    if($v['RoleID']){
                        $ids[] = $v['RoleID'];
                    }
                }
            }
            $ids = implode(',',$ids);
        }else{
            $ids = '';
        }
        return $ids;
    }
}