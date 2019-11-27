<?php

namespace app\controllers;

use app\libs\Methods;
use app\modules\content\models\CurrencyData;
use app\modules\content\models\Item;
use app\modules\content\models\LoginData;
use app\modules\content\models\LoginRole;
use app\modules\content\models\LTVMoney;
use app\modules\content\models\Server;
use app\modules\content\models\YuanbaoRole;
use app\modules\pay\models\Recharge;
use Yii;
use yii\web\Controller;


class TestController extends Controller
{

    public function init()
    {
        require_once IndexDir.'/../libs/protobuf/out/ItemProtocol.php';
        require_once IndexDir.'/../libs/protobuf/out/PBItemGroup.php';
        require_once IndexDir.'/../libs/protobuf/out/PBItem.php';
        require_once IndexDir.'/../libs/protobuf/out/DigMineProtocol.php';
        require_once IndexDir.'/../libs/protobuf/out/SkillProtocol.php';
        require_once IndexDir.'/../libs/protobuf/out/PBSkill.php';
        require_once IndexDir.'/../libs/protobuf/out/PBShortCutKey.php';
        require_once IndexDir.'/../libs/protobuf/out/ActivityProtocol.php';
        require_once IndexDir.'/../libs/protobuf/out/TaskProtocol.php';
        require_once IndexDir.'/../libs/protobuf/out/PbBranch.php';
    }

    public function actionIndex()
    {
        ini_set('user_agent','Mozilla/4.0 (compatible; MSIE 5.00; Windows 98)');
        $date = date('Y-m-d');
        $servers = Server::getServers();//获取区服
        $url = 'http://192.168.0.30/logs/TLog/';
        foreach($servers as $k => $v) {
            $dat = str_replace('-','',$date);
            //获取日志文件并统计
            $fileName = 'Tlog.' . $v['id'] . '.0_' . $dat . '.log';
            $path = $url . $fileName;
            try{
                $file = file_get_contents($path);
                $file = str_replace(array("\n","\r","\t"),'',$file);
                preg_match_all('/PlayerLogin(\|([^\|]+))+(\|\|)([^|])((\|)([^\|]+))+(\|\|)([^|]+)(\|\|\|\|)([^|]+)((\|)([^\|]+))+MoneyFlow/', $file, $arrLogin);
                $login = $arrLogin[0];
                $loginTime = [];//登录时间
                preg_match_all('/PlayerLogout(\|([^\|]+))+(\|\|)([^|])((\|)([^\|]+))+(\|\|)([^|]+)(\|\|\|\|)([^|]+)((\|)([^\|]+))+MoneyFlow/', $file, $arrLoginOut);
                $loginOut = $arrLoginOut[0];
                $loginOutData = [];//退出数据
                var_dump($login);
                var_dump($loginOut);die;
                //退出时间处理
                foreach ($loginOut as $pp => $oo) {//解析退出数据统计
                    $oo = str_replace('MoneyFlow','',$oo);
                    $loginOutArr = explode('|', $oo);//键值对应 1-区服 2-时间 6-设备号 24-角色id
                    $key = 'role' . $loginOutArr[24];
                    $loginOutData[$key][] = ['serverId' => $loginOutArr[1], 'loginOutTime' => $loginOutArr[2], 'roleId' => $loginOutArr[18]];
                }
                $site = 0;//对应的登出位置
                //登录数据处理 解析 对应退出 读入数据库
                foreach ($login as $p => $o) {//解析登录数据统计
                    $o = str_replace('MoneyFlow','',$o);
                    $loginArr = explode('|', $o);//键值对应 1-区服 2-时间 6-设备号 18-角色id 19-姓名 29-登录ip
                    $currTime = strtotime($loginArr[2]);//当前登录时间
                    $roleId = $loginArr[18];
                    $key = 'role' . $roleId;
                    $code = 0;//数据库记录识别 0-不记录 1-记录
                    if (isset($loginTime[$key])) {//已有该角色id登录数据
                        if (($loginTime[$key] + 60) < $currTime) {//如果该角色下次登录时间大于上次登录时间一分钟才算
                            $code = 1;
                            $site++;
                            $loginTime[$key] = $currTime;//更新登录时间 方便下次比较
                        }
                    } else {
                        $code = 1;
                        $site = 0;
                        $loginTime[$key] = $currTime;//更新登录时间 方便下次比较
                    }
                    //数据记录
                    if ($code == 1) {
                        $loginOutTime = '';
                        //获取对应的登出时间
                        if (isset($loginOutData[$key][$site])) {//是否有对应位置的登出时间
                            $loginOutTime = $loginOutData[$key][$site]['loginOutTime'];
                            $begin = strtotime($loginArr[2]);
                            $end = strtotime($loginOutTime);
                            if ($begin > $end) {//对应的登录时间小于于登出时间 记录该条数据
                                $loginOutTime = '';
                            }
                        } else {//没有默认一直在线到第二天
                            if (isset($loginOutData[$key][$site - 1])) {//对应的登出记录是否有上一条
                                $loginOutTime = date("Y-m-d 23:59:59");//有上一条可记录 没有的话上一条的登出时间已记录到凌晨 不需要再记录
                            }
                        }
                        if ($loginOutTime) {
                            $model = new LoginRole();
                            $model->roleId = $roleId;
                            $model->date = $date;
                            $model->loginTime = $loginArr[2];
                            $model->loginOutTime = $loginOutTime;
                            $model->serverId = $loginArr[1]/10;
                            $model->device = $loginArr[6];
                            $model->ip = $loginArr[29];
                            $model->name = $loginArr[19];
                            $model->createTime = time();
                            $model->save();
                        }
                    }
                }
            }catch(\Exception $e){

            }
            //统计当天各个小时的在线人数
            $data = [];
            for($i=1;$i<=24;$i++){
                $beginTime = strtotime($date)+($i-1)*3600;
                $endTime = $beginTime + 3600;
                //登录时间在时间段内 或者 退出时间在此时间段内 或者 登录退出时间在此时间段外
                $where = " date = '{$date}' and serverId = {$v['id']} and ( ( unix_timestamp(loginTime) between $beginTime and $endTime ) or ( unix_timestamp(loginOutTime) between $beginTime and $endTime ) or ( unix_timestamp(loginTime) < $beginTime and unix_timestamp(loginOutTime) > $endTime ) )";
                $number = LoginRole::find()->where($where)->groupBy('roleId')->count();
                $data[] = $number?$number:0;
            }
            $model = new LoginData();
            $model->serverId = $v['id'];
            $model->date = $date;
            $model->data = implode(',',$data);
            $model->createTime = time();
            $model->save();
        }
    }
    public function actionTest1(){
        $strTest = Yii::$app->db2->createCommand("select * from digmine limit 0,1")->queryOne()['datas'];
        var_dump($strTest);
        $digMine = new \DigMineProtocol();
        $digMine->setExchangeCount(12);
        $digMine->setExchangeTime(300);
        $digMine->setOut(23);
        $digMine->setNew('false');
        $str = $digMine->serializeToString();
        var_dump($str);
        $sig = new \DigMineProtocol();
        $sig->mergeFromString($str);
        var_dump('Count:'.$sig->getExchangeCount().' Time:'.$sig->getExchangeTime().' Out:'.$sig->getOut().' New:'.$sig->getNew());die;
    }
    public function actionTest2(){

        $skill = new \SkillProtocol();

        $pbSkill = new \PBSkill();
        $pbSkill->setId(1);
        $pbSkill->setKey(12);
        $pbSkill->setLevel(33);
        $pbSkill->setExp(40000);
        $pbSkill->setCdTime(15828043607);

        $cutKey = new \PBShortCutKey();
        $cutKey->setProtoid(111);
        $cutKey->setPrototype(1);
        $cutKey->setPtotokey(11);

        $cutKey2 = new \PBShortCutKey();
        $cutKey2->setProtoid(222);
        $cutKey2->setPrototype(2);
        $cutKey2->setPtotokey(22);


        $skill->setSkills([$pbSkill]);
        $skill->setShortCutKey([$cutKey,$cutKey2]);
        $str = $skill->serializeToString();
        var_dump($str);
        echo '<br/>';
        $skill = new \SkillProtocol();

        $skill->mergeFromString($str);
        var_dump($skill->getSkills()[0]->getId());
        var_dump($skill->getSkills()[0]->getKey());
        var_dump($skill->getSkills()[0]->getLevel());
        var_dump($skill->getSkills()[0]->getExp());
        var_dump($skill->getSkills()[0]->getCdTime());
        echo '<br/>';
        var_dump($skill->getShortCutKey()[0]->getProtoid());
        var_dump($skill->getShortCutKey()[0]->getPrototype());
        var_dump($skill->getShortCutKey()[0]->getPtotokey());
        echo '<br/>';
        var_dump($skill->getShortCutKey()[1]->getProtoid());
        var_dump($skill->getShortCutKey()[1]->getPrototype());
        var_dump($skill->getShortCutKey()[1]->getPtotokey());

        die;


    }
    public function actionTest3(){
        $data = Yii::$app->db2->createCommand("select * from activity where modelID =5 and activityID = 6")->queryOne();
        $str = $data['datas'];

        var_dump($str);echo '<br/>';

        $group = new \ActivityProtocol();
        $group->mergeFromString($str);

//        var_dump($group);
        var_dump('modelId:'.$group->getModelID().' activityId:'.$group->getActivityID().'datas:'.$group->getDatas());

        echo '<hr/>';

        $data = Yii::$app->db2->createCommand("select * from task ")->queryOne();
        $str = $data['datas'];

        var_dump($str);echo '<br/>';

        $group = new \TaskProtocol();
        $group->mergeFromString($str);

//        var_dump($group);
        var_dump('modelId:'.$group->getMainTaskId().' activityId:'.$group->getMaintaskState());

        echo '<hr/>';

    }

    public function actionTestItem(){
        $content = Yii::$app->db2->createCommand("select * from item limit 0,1")->queryOne()['datas'];
        $item = new \ItemProtocol();

        $itemGroup = new \PBItemGroup();
        $itemGroup->setId(2);
        $itemGroup->setCapacity(22);

        $itempb = new \PBItem();
        $itempb->setExp('20000');
        $itempb->setLevel(50);
        $itempb->setCount(20);

        $itemGroup->setItems([$itempb]);

        $item->setGroups([$itemGroup]);
        $return = $item->serializeToString();

        $item = new \ItemProtocol();
//        $item->mergeFromJsonString($content);
        $item->mergeFromString($content);
        var_dump($item->getGroups()[0]->getId());
    }
    public function actionTestLog(){
        $type = Yii::$app->request->get('type',1);
        $date = Yii::$app->request->get('date',date('Y-m-d'));
        if($type ==1){
            $date = str_replace('-','',$date);
            $url = "http://192.168.0.30/logs/TLog/Tlog.100.0_$date.log";
        }else{
            $url = "http://192.168.0.30/logs/TLog/lua_log-100-$date.txt";
        }
//        $url = IndexDir.'/files/lua_log-100-2019-09-25.txt';
//        $fp = fopen($url,"r");
//        $str = "";
//        $buffer = 1024;//每次读取 1024 字节
//        while(!feof($fp)){//循环读取，直至读取完整个文件
//            $str .= fread($fp,$buffer);
//        }
////        $str = fread($fp,filesize($url));//指定读取大小，这里把整个文件内容读取出来
//        var_dump($str);
//        fclose($fp);
        $res = file_get_contents($url);
        var_dump($res);
    }
}
