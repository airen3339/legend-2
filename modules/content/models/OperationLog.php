<?php


namespace app\modules\content\models;


use yii\db\ActiveRecord;

class OperationLog extends ActiveRecord
{

    public static  function tableName(){
        return '{{%operation_log}}';
    }
    /**
     * 日志记录添加
     * 操作记录
     * @param $remark  操作说明
     * @param $activityId  操作的活动id
     * @param int $type  操作类型  1-客服账号
     */
    public static function logAdd($remark,$object,$type=1){
        $uid = \Yii::$app->session->get('adminId');
        $model = new self();
        $model->adminId = $uid;
        $model->remark = $remark;
        $model->object = $object;
        $model->type = $type;
        $model->createTime = time();
        $model->save();
    }
}