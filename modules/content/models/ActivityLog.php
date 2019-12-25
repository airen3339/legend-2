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
    /**
     * 天和宝藏奖励内容
     */
    public static function tzbzReward(){
        $dataArr = [
            ['id'=>444444,'name'=>'经验次数','count'=>0],
            ['id'=>1100,'name'=>'初级羽毛次数','count'=>0],
            ['id'=>1245,'name'=>'灵枢导引针次数','count'=>0],
            ['id'=>1047,'name'=>'攻击神水次数','count'=>0],
            ['id'=>2015,'name'=>'强化技能灵丹次数','count'=>0],
            ['id'=>62000091,'name'=>'仙翼技能灵丹次数','count'=>0],
            ['id'=>1417,'name'=>'中级羽毛次数','count'=>0],
            ['id'=>1418,'name'=>'高级羽毛次数','count'=>0],
            ['id'=>1456,'name'=>'天仙之羽次数','count'=>0],
            ['id'=>1457,'name'=>'天神之羽次数','count'=>0],
            ['id'=>22051,'name'=>'炫彩之力次数','count'=>0],
            ['id'=>1559,'name'=>'声望令牌次数','count'=>0],
            ['id'=>5018,'name'=>'点金石次数','count'=>0],
            ['id'=>1074,'name'=>'洗炼符次数','count'=>0],
            ['id'=>1245,'name'=>'防御神水次数','count'=>0],
            ['id'=>1510,'name'=>'炽焰麒麟次数','count'=>0],
        ];
        return $dataArr;
    }
}