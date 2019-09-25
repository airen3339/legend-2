<?php


namespace app\modules\content\models;


use function GuzzleHttp\Psr7\str;
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
    /**
     * 设置首页公告内容
     * 文件保存
     * 便于客户端获取
     */
    public static function setNoticeLog($content,$beginTime,$endTime){
        $today = time();//当前时间
        $beginTime = strtotime($beginTime);
        $endTime = strtotime($endTime) + 86399;
        if($today >= $beginTime && $today < $endTime){//当前时间在公告时间段内
            //写入文件
            $path = fopen(IndexDir.'/files/notice/indexNotice.txt','w');
            fwrite($path,$content);
            fclose($path);
        }
    }
}