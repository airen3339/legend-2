<?php

namespace app\controllers;

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
        echo 'test';
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
}
