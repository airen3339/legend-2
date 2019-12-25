<?php

//在线人数
namespace app\modules\content\models;


use app\modules\pay\models\Recharge;
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
                ['id'=>6,'name'=>'商城购买'],
                ['id'=>7,'name'=>'混沌空间'],
                ['id'=>8,'name'=>'黑市商人'],
                ['id'=>9,'name'=>'经验树升级'],
                ['id'=>10,'name'=>'五行下注'],
                ['id'=>11,'name'=>'五行开奖'],
            ];
        }else{//用户定时统计 去除元宝充值
            $arr = [1=>'元宝兑换',2=>'时时彩下注',3=>'赠送元宝',5=>'用户送花',6=>'商城购买',7=>'混沌空间',8=>'黑市商人',9=>'经验树升级',10=>'五行下注',11=>'五行开奖'];
        }
        return $arr;
    }
    /**
     * 读取日志
     * 记录用户元宝消耗记录
     */
    public static function getYuanbaoData(){
        ini_set('user_agent','Mozilla/4.0 (compatible; MSIE 5.00; Windows 98)');
        $date = date('Y-m-d');
        //删除当天的数据记录
        YuanbaoRole::deleteAll("date = '{$date}'");
        CurrencyData::deleteAll("date = '{$date}'");
        $servers = Server::getServers();//获取区服
        $url = \Yii::$app->params['legendLogUrl'];
        foreach($servers as $k => $v) {
            $fileName = "lua_log-{$v['id']}-$date.txt";
            $path = $url.$fileName;
            try{
                $content = file_get_contents($path);
                if(!$content){
                    continue;
                }
                $content = trim($content);
                $content = explode("legend",$content);
                foreach($content as $p => $m){
                    if(!trim($m)){
                        continue;
                    }
                    $arr = explode('@',trim($m));//键值 0-时间 1-type类型 2-角色id 3-增加减少 4-金额 5-说明
                    //记录用户消费数据
                    $type = self::getData($arr[1]);
                    $model = new YuanbaoRole();
                    $model->date = $date;
                    $model->serverId = $v['id'];
                    $model->roleId = self::getData($arr[2]);
                    $model->dateTime = $date." ".$arr[0];
                    $model->money = self::getData($arr[4]);
                    $model->type = $type;
                    $model->added = self::getData($arr[3]);
                    if($type == 6){//商城购买
                        $remark = str_replace('explain:','',$arr[5]);
                        $patterns = "/\d+/"; //第一种
                        preg_match_all($patterns,$remark,$array);
                        $toolId = isset($array[0][0])?$array[0][0]:0;
                        if($toolId){
                            $toolName = Item::find()->where("itemid = $toolId")->asArray()->one()['name'];
                            $remark .= '商品名称：'.$toolName;
                        }
                        $model->remark = $remark;
                    }else{
                        $model->remark = str_replace('explain:','',$arr[5]);
                    }
                    $model->createTime = time();
                    $model->save();
                }
            }catch(\Exception $e){
            }

            //统计元宝消耗 4-元宝充值
            $arr = YuanbaoRole::getTypes(2);//获取元宝操作类型
            foreach($arr as $t => $y){// 1-元宝兑换 2-时时彩下注 3-赠送元宝 4-充值元宝 5-用户送花 6-用户月卡 7-混沌空间 8-黑市商人 9=>'经验树升级',10=>'五行下注',11=>'五行开奖'
                if(in_array($t,[1,7,9,11])){//元宝兑换 可有增加
                    //增加
                    $add = YuanbaoRole::find()->where(" date = '{$date}' and serverId = '{$v['id']}' and type = $t and added = 1")->sum('money');
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
                }
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
    /**
     * 格式数据获取
     */
    public static function getData($str){
        if($str){
            $str = trim($str);
            $arr = explode(':',$str);
            if(count($arr) ==2 ){
                return $arr[1];
            }else{
                return '';
            }
        }else{
            return '';
        }
    }
    /**
     * 更新当天的用户天中宝藏数据
     */
    public static function updateTzbzData($roleId){
        $date = date('Y-m-d');
        if(!$roleId){
            return true;
        }else{
            $data = YuanbaoRole::find()->where(" date = '{$date}'")->asArray()->all();//当天有没有更新数据
            if(!$data){
                self::getYuanbaoData();
            }
            $data = YuanbaoRole::find()->where("type = 14 and roleId = '{$roleId}' and date = '{$date}'")->asArray()->all();//type 14 天和宝藏
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