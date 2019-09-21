<?php

//在线人数
namespace app\modules\content\models;


use yii\db\ActiveRecord;

class YuanbaoRole extends ActiveRecord
{

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
                ['id'=>6,'name'=>'用户月卡'],
            ];
        }else{//用户定时统计 去除元宝充值
            $arr = [1=>'元宝兑换',2=>'时时彩下注',3=>'赠送元宝',5=>'用户送花',6=>'用户月卡'];
        }
        return $arr;
    }
}