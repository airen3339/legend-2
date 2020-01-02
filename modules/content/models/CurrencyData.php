<?php

//在线人数
namespace app\modules\content\models;


use yii\db\ActiveRecord;

class CurrencyData extends ActiveRecord
{

    public static  function tableName(){
        return '{{%currency_data}}';
    }
    /**
     *更新元宝消耗统计
     */
    public static function updateMoneyUse()
    {
        $date = date('Y-m-d');
        CurrencyData::deleteAll("date = '{$date}'");
        $servers = Server::getServers();//获取区服
        foreach ($servers as $k => $v) {
            //统计元宝消耗 4-元宝充值
            $arr = YuanbaoRoleLog::getTypes(2);//获取元宝操作类型
            foreach($arr as $t => $y){// 1-元宝兑换 2-时时彩下注 3-赠送元宝 4-充值元宝 5-用户送花 6-用户月卡 7-混沌空间 8-黑市商人 9=>'经验树升级',10=>'五行下注',11=>'五行开奖'
                //增加
                $add = YuanbaoRoleLog::find()->where(" date = '{$date}' and serverId = '{$v['id']}' and type = $t and added = 1")->sum('money');
                $model = new CurrencyData();
                $model->date = $date;
                $model->serverId = $v['id'];
                $model->type = 1;//1-元宝
                $model->typeObject = $t;
                $model->number = $add?$add:0;
                $model->added = 1;
                $model->remark = $y;
                $model->createTime = time();
                $model->save();
                //消耗
                $reduce = YuanbaoRole::find()->where(" date = '{$date}' and serverId = '{$v['id']}' and type = $t and added = 0")->sum('money');
                $model = new CurrencyData();
                $model->date = $date;
                $model->serverId = $v['id'];
                $model->type = 1;//1-元宝
                $model->typeObject = $t;
                $model->number = $reduce?$reduce:0;
                $model->added = 0;
                $model->remark = $y;
                $model->createTime = time();
                $model->save();
            }
            //记录元宝充值 收入  type 4
            $number = Recharge::find()->where("server_id = {$v['id']} and status = 2 and from_unixtime(createTime,'%Y-%m-%d') = '{$date}'")->sum('yuanbao');
            $model = new CurrencyData();
            $model->date = $date;
            $model->serverId = $v['id'];
            $model->type = 1;//1-元宝
            $model->typeObject = 4;
            $model->number = $number?$number:0;
            $model->added = 1;
            $model->remark = '元宝充值';
            $model->createTime = time();
            $model->save();
        }
    }

}