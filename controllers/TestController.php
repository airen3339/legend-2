<?php

namespace app\controllers;

use app\libs\Methods;
use app\modules\content\models\LTVMoney;
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
