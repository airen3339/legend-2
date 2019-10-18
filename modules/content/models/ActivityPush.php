<?php


namespace app\modules\content\models;


use app\libs\Methods;
use yii\db\ActiveRecord;

class ActivityPush extends ActiveRecord
{

    public static  function tableName(){
        return '{{%activity_push}}';
    }
    /**
     * 活动推送服务端
     * @param $serverId  区服id
     * @param $activityId  活动id
     * @param $type 活动类型
     * @param $beginTime 开始时间
     * @param $endTime 结束时间
     * @param $pushContent 发放物品
     */
    public static function pushActivity($serverId,$activityId,$type,$beginTime,$endTime,$pushContent){
        if(empty($activityId) || empty($type) || empty($beginTime) || empty($endTime) || empty($pushContent)){
            return false;
        }else{
            $pushContent = json_decode($pushContent,true);
            $data = [];//对应条件的数据
            foreach($pushContent['condition'] as $k => $v){
                $key = 'condition'.$v;//条件键区分
                $propId = intval($pushContent['propId'][$k]);
                if($propId>0){
                    $data[$key][] = ['id'=>intval($pushContent['propId'][$k]),'count'=>intval($pushContent['number'][$k]),'binding'=>intval($pushContent['bind'][$k])];
                }else{
                    $data[$key][] = [];
                }
            }
            //构造推送数据
            $AwardList = [];
            foreach($data as $l => $t){
                $con = str_replace('condition','',$l);
                $con = [$con];
                $con = ['con'=>$con];
                if(!empty($t)){
                    $con['awd']=$t;
                }
                $AwardList[] = $con;
            }
            $push = [
                'MainID' => $activityId,
                'ActivityType' => intval($type),
                'BeginTime' => $beginTime,
                'EndTime' => $endTime,
                'AwardList'=> $AwardList,
            ];
            //推送
            Methods::GmFileGet($push,$serverId,6,4242);//4242 -活动推送奖励
            return true;
        }
    }
}