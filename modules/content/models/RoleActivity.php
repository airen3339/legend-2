<?php
//公告记录

namespace app\modules\content\models;


use yii\db\ActiveRecord;

class RoleActivity extends ActiveRecord
{

    public static  function tableName(){
        return '{{%role_activity}}';
    }
    /**
     * 统计数据次数
     */
    public static function tzbzIdData($date){
        $data = YuanbaoRoleLog::find()->where("type = 14  and date = '{$date}'")->asArray()->all();//type 14 天和宝藏
        RoleActivity::deleteAll(" date = '{$date}'  and type = 1");
        $dataArr = ActivityLog::tzbzReward();
        foreach($data as $k => $v){
            $roleId = $v['roleId'];
            $content = $v['remark'];
            foreach($dataArr as $t => $y){
                $target = $y['id'];
                if(preg_match("/=($target)[^\d]/",$content)){
                    $model = new RoleActivity();
                    $model->roleId = $roleId;
                    $model->date = $date;
                    $model->dateTime = $date.' '.$v['dateTime'];
                    $model->content = $y['name'];
                    $model->type = 1;
                    $model->contentId = $y['id'];
                    $model->serverId = $v['serverId'];
                    $model->createTime = time();
                    $model->save();
                }
            }
        }
    }
}