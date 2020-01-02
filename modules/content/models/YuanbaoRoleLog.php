<?php

//在线人数
namespace app\modules\content\models;


use yii\db\ActiveRecord;

class YuanbaoRoleLog extends ActiveRecord
{

    public static function getDb(){
        return \Yii::$app->db3;
    }
    public static  function tableName(){
        return '{{%yuanbao_role}}';
    }
    /**
     * 元宝操作类型
     */
    public static function getTypes($type =1){
        if($type ==1){//所有类型
            $arr = [
                ['id'=>1,'name'=>'元宝兑换'],
                ['id'=>2,'name'=>'时时彩'],
                ['id'=>3,'name'=>'赠送元宝'],
                ['id'=>4,'name'=>'元宝充值'],
                ['id'=>5,'name'=>'用户送花'],
                ['id'=>6,'name'=>'商城购买'],
                ['id'=>7,'name'=>'混沌空间'],
                ['id'=>8,'name'=>'黑市商人'],
                ['id'=>9,'name'=>'经验树升级'],
                ['id'=>10,'name'=>'五行下注'],
                ['id'=>11,'name'=>'五行开奖'],
                ['id'=>12,'name'=>'优惠礼包'],
                ['id'=>13,'name'=>'沙城城主元宝奖励'],
                ['id'=>14,'name'=>'天中宝藏'],
                ['id'=>15,'name'=>'时时彩元宝返回'],
                ['id'=>16,'name'=>'混沌空间掉落'],
                ['id'=>17,'name'=>'时时彩开奖'],
                ['id'=>18,'name'=>'元宝充值到账'],
            ];
        }else{//用户定时统计 去除元宝充值
            $arr = [
                1=>'元宝兑换',
                2=>'时时彩下注',
                3=>'赠送元宝',
                5=>'用户送花',
                6=>'商城购买',
                7=>'混沌空间',
                8=>'黑市商人',
                9=>'经验树升级',
                10=>'五行下注',
                11=>'五行开奖',
                12=>'优惠礼包',
                13=>'沙城城主元宝奖励',
                14=>'天中宝藏',
                15=>'时时彩元宝返回',
                16=>'混沌空间掉落',
                17=>'时时彩开奖',
                18=>'元宝充值到账',
                ];
        }
        return $arr;
    }

    /**
     * 更新当天的用户天中宝藏数据
     */
    public static function updateTzbzData($roleId){
        $date = date('Y-m-d');
        if(!$roleId){
            return true;
        }else{
            $data = YuanbaoRoleLog::find()->where("type = 14 and roleId = '{$roleId}' and date = '{$date}'")->asArray()->all();//type 14 天和宝藏
            RoleActivity::deleteAll(" date = '{$date}' and roleId = '{$roleId}' and type = 1");
            $dataArr = ActivityLog::tzbzReward();
            foreach($data as $k => $v){
                $content = $v['remark'];
                foreach($dataArr as $t => $y){
                    $target = $y['id'];
                    if(preg_match("/=($target)[^\d]/",$content)){
                        $model = new RoleActivity();
                        $model->roleId = $roleId;
                        $model->date = $date;
                        $model->dateTime = $v['dateTime'];
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
        return true;
    }
}