<?php


namespace app\modules\content\models;


use yii\db\ActiveRecord;

class Role extends ActiveRecord
{
    public static  function tableName(){
        return '{{%role}}';
    }
    /**
     * 获取头部导航内容
     * 用户对应权限
     * cy
     */
    public static function getHeadArr($adminId,$head){
        $sql = "select * from  {{%role_catalog}} rc  left join {{%catalog}} c on c.id = rc.cataId where rc.roleId = $adminId and c.pid = 0";
        if($head != 1){
            $sql .= " and c.id != 1";
        }
        $headArr = \Yii::$app->db->createCommand($sql)->queryAll();
        return $headArr;
    }
    /**
     * 获取左部导航内容
     * 用户对应权限内容
     * cy
     */
    public static function getLeftArr($contentId){
        if(!$contentId || $contentId < -1){
            return [];
        }else{
            $leftArr = Catalog::find()->where('pid != 0 and pid ='.$contentId)->asArray()->all();
            return $leftArr;
        }
    }
    /**
     * 获取用户的默认目录
     * cy
     */
    public static function getAction($adminId,$contentId){
        $actionId = Catalog::find()->select('group_concat(id) as ids')->where("pid = $contentId")->asArray()->one()['ids'];
        if($actionId){
            echo $actionId;
            $sql = " select ca.`rule`,ca.id from {{%role_catalog}} rc  left join {{%catalog}} ca on rc.cataId = ca.id where rc.roleId = $adminId and ca.id in($actionId) order by rc.id asc ";
            $action = \Yii::$app->db->createCommand($sql)->queryOne();
        }else{
            $action = [];
        }
        return $action;
    }
    /**
     * 获取默认contentId
     * 控制器
     */
    public static function getContentId($adminId){
        $sql = " select c.id from {{%role_catalog}} rc left join {{%catalog}} c on c.id = rc.cataId where rc.roleId = $adminId and c.pid = 0";
        $contentId = \Yii::$app->db->createCommand($sql)->queryOne()['id'];
        return $contentId;
    }
}