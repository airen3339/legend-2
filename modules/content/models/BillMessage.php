<?php
//公告记录

namespace app\modules\content\models;


use yii\db\ActiveRecord;

class BillMessage extends ActiveRecord
{

    public static  function tableName(){
        return '{{%bill_message}}';
    }

    /**
     * @return array
     * 单据类型
     */
    public  static function getBillTypes(){
        $billTypes = [
            ['id'=>1,'name'=>'普通单'],
            ['id'=>2,'name'=>'技术单'],
            ['id'=>3,'name'=>'投诉单'],
            ['id'=>4,'name'=>'预警单'],
        ];
        return $billTypes;
    }
    /**
     * @return array
     * 单据来源
     */
    public  static function getBillSources(){
        $billSources = [
            ['id'=>1,'name'=>'在线'],
            ['id'=>2,'name'=>'热线'],
        ];
        return $billSources;
    }
    /**
     * @return array
     * 游戏所属
     */
    public  static function getBillGames(){
        $billGames = [
            ['id'=>1,'name'=>'传奇']
        ];
        return $billGames;
    }
    /**
     * @return array
     * VIP等级
     */
    public  static function getvipLevels(){
        $vipLevels = [];
        return $vipLevels;
    }
}