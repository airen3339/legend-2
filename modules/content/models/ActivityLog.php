<?php


namespace app\modules\content\models;


use yii\db\ActiveRecord;

class ActivityLog extends ActiveRecord
{

    public static  function tableName(){
        return '{{%activity_log}}';
    }
    /**
     * 日志记录添加
     * @param $remark  操作说明
     * @param $activityId  操作的活动id
     * @param int $type  操作类型  1-活动推送  2-五行运势 3-首页公告
     */
    public static function logAdd($remark,$activityId,$type=1){
        $uid = \Yii::$app->session->get('adminId');
        $name = \Yii::$app->session->get('adminName');
        $model = new self();
        $model->operator = $name;
        $model->operatorId = $uid;
        $model->remark = $remark;
        $model->activityId = $activityId;
        $model->type = $type;
        $model->createTime = time();
        $model->save();
    }
}