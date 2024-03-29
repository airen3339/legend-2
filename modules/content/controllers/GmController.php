<?php

/**
 * GM工具模块
 */
namespace app\modules\content\controllers;


use app\libs\AdminController;
use app\libs\Methods;
use app\modules\content\models\ForbiddenRecord;
use app\modules\content\models\Item;
use app\modules\content\models\Notice;
use app\modules\content\models\OperationLog;
use app\modules\content\models\Player;
use app\modules\content\models\RewardRecord;
use app\modules\content\models\Role;
use app\modules\content\models\Server;
use app\modules\content\models\SliverMerchant;
use app\modules\content\models\User;
use app\modules\content\models\YinShang;
use Think\Exception;
use Yii;
use yii\data\Pagination;

class GmController  extends AdminController
{
    public $enableCsrfValidation = false;
    public $layout = 'content';

    public function init(){
        parent::init();
        parent::setContentId('gm');
    }
    public function actionIndex(){
        return $this->redirect('/content/index/index');
    }
    /**
     * 区服添加奖励
     */
    public function actionServiceAddReward(){
        $action = Yii::$app->controller->action->id;
        parent::setActionId($action);
        if($_POST){
            $adminId = Yii::$app->session->get('adminId');
            $server = Yii::$app->request->post('server');
            $sendTime = Yii::$app->request->post('sendTime');
            $minLevel = Yii::$app->request->post('minLevel',0);
            $maxLevel = Yii::$app->request->post('maxLevel',70);
            $propIds = Yii::$app->request->post('propIds');//道具id
            $numbers = Yii::$app->request->post('numbers');//道具数量
            $binds = Yii::$app->request->post('binds');//道具数量
            $emailTitle = Yii::$app->request->post('emailTitle');
            $emailContent = Yii::$app->request->post('emailContent');
//            $contentOther = Yii::$app->request->post('contentOther','');
            $contentOther = '';
            if( count($propIds) == count($numbers) && (count($numbers) == count($binds)) && count($propIds) > 0  ){
                $pushContent = ['propId'=>$propIds,'number'=>$numbers,'bind'=>$binds];
                $pushContent = json_encode($pushContent);
                //统计道具物品数量
                $ids = [];
                foreach($propIds as $k => $v){
                    if(!in_array([$v,$binds[$k]],$ids)){
                        $ids[] = [$v,$binds[$k]];
                    }
                    //判断是否为元宝
                    if($v == 222222){
                        //判断账号权限
                        $admin = Role::findOne($adminId);
                        if($admin->currency !=1){
                            echo "<script>alert('你没有元宝操作权限');setTimeout(function(){history.go(-1);},1000)</script>";die;
                        }
                    }
                }
                $propNum = count($ids);
            }else{
                echo "<script>alert('发放物品数据不正确');setTimeout(function(){history.go(-1);},1000)</script>";die;
            }
            if(!$minLevel)$minLevel = 0;
            if(!$maxLevel)$maxLevel = 70;
            if($server  && $emailContent && $emailTitle && $pushContent){
                $model = new RewardRecord();
                $model->type = 2;//1-玩家 2-区服
                $model->serverId = $server;
                $model->title = $emailTitle;
                $model->content = $emailContent;
                $model->contentOther = $contentOther;
                $model->sender = '系统';
                $model->prop = $pushContent;
                $model->propNum = $propNum;
                $model->sendTime = $sendTime;
                $model->minLevel = $minLevel;
                $model->maxLevel = $maxLevel;
                $model->createTime = time();
                $model->creator = $adminId;
                $res = $model->save();
                if($res){
                    //日志记录
                    OperationLog::logAdd('添加区服奖励',$model->id,4);//3-玩家奖励 4-区服奖励
                    echo "<script>alert('添加奖励成功');setTimeout(function(){location.href='service-add-reward';},1000)</script>";die;
                }else{
                    echo "<script>alert('保存失败，请重试');setTimeout(function(){history.go(-1);},1000)</script>";die;
                }
            }else{
                echo "<script>alert('参数错误');setTimeout(function(){history.go(-1);},1000)</script>";die;
            }
        }else{
            $servers = Server::getServers();
            return $this->render('service-add-reward',['servers'=>$servers]);
        }
    }
    /**
     * 玩家添加奖励
     */
    public function actionPlayerAddReward(){
        $action = Yii::$app->controller->action->id;
        parent::setActionId($action);
        if($_POST){
            $server = Yii::$app->request->post('server');
            $name = Yii::$app->request->post('name','');
            $emailTitle = Yii::$app->request->post('emailTitle');
            $emailContent = Yii::$app->request->post('emailContent');
            $roleId = Yii::$app->request->post('roleId','');
//            $contentOther = Yii::$app->request->post('contentOther');
            $contentOther = '';
            $propId = Yii::$app->request->post('propId');
            $propNum = Yii::$app->request->post('propNum');
            $binding = Yii::$app->request->post('bind');
            //获取roleID
            $roleArr = [];
            if($name){
                $nameArr = explode(',',$name);
                foreach($nameArr as $t => $y){
                    $y = trim($y);
                    $roId = Player::find()->where("Name = '{$y}'")->asArray()->one()['RoleID'];
                    if(!$roId){
                        echo "<script>alert('没有该玩家（".$y."）');setTimeout(function(){history.go(-1);},1000)</script>";die;
                    }
                    $roleArr[] = $roId;
                }
            }
            if($roleId){
                $ids = explode(',',$roleId);
                foreach($ids as $e => $w){
                    $w = trim($w);
                    if(!in_array($w,$roleArr)){
                        $roleArr[] = $w;
                    }
                }
            }
            if($server && $roleArr && $emailContent && $emailTitle && $propId && $propNum && $binding){
                $adminId = Yii::$app->session->get('adminId');
                //判断是否为元宝
                if($propId == 222222){
                    //判断账号权限
                    $admin = Role::findOne($adminId);
                    if($admin->currency !=1){
                        echo "<script>alert('你没有元宝操作权限');setTimeout(function(){history.go(-1);},1000)</script>";die;
                    }
                }
                $prop = ['propId'=>[$propId],'number'=>[$propNum],'bind'=>[$binding]];
                $saveId = [];
                foreach($roleArr as $r => $w){
                    $model = new RewardRecord();
                    $model->type = 1;//1-玩家 2-区服
                    $model->serverId = $server;
                    $model->title = $emailTitle;
                    $model->content = $emailContent;
                    $model->contentOther = $contentOther;
                    $model->sender = '系统';
                    $model->prop = json_encode($prop);
                    $model->propNum = 1;
                    $model->roleId = $w;
                    $model->createTime = time();
                    $model->creator = $adminId;
                    $res = $model->save();
                    if($res) {
                        $saveId[] = $model->id;
                        //日志记录
                        OperationLog::logAdd('添加玩家奖励', $model->id, 3);//3-玩家奖励 4-区服奖励
                    }else{
                        $saveStr = implode(',',$saveId);
                        RewardRecord::deleteAll("id in ({$saveStr})");
                        echo "<script>alert('保存失败，请重试');setTimeout(function(){history.go(-1);},1000)</script>";die;
                    }
                }
                echo "<script>alert('添加奖励成功');setTimeout(function(){location.href='player-add-reward';},1000)</script>";die;
            }else{
                echo "<script>alert('参数错误');setTimeout(function(){history.go(-1);},1000)</script>";die;
            }
        }else{
            $servers = Server::getServers();
            return $this->render('player-add-reward',['servers'=>$servers]);
        }
    }
    /**
     * 奖励审核
     */
    public function actionRewardCheck(){
        $action = Yii::$app->controller->action->id;
        parent::setActionId($action);
        if($_POST){
            $id = Yii::$app->request->post('id');
            $status = Yii::$app->request->post('status');//1-通过 -1-作废
            $adminId = Yii::$app->session->get('adminId');
            $res = RewardRecord::updateAll(['checker'=>$adminId,'status'=>$status],"id = $id");
            if(!$res){
                die(json_encode(['code'=>0,'message'=>'操作失败，请刷新重试']));
            }
            $reward = RewardRecord::findOne($id);
            if($status != 1){//作废
                if($reward->type ==1){
                    OperationLog::logAdd('审核玩家奖励（作废）',$id,3);//3-玩家 4-区服
                }else{
                    OperationLog::logAdd('审核区服奖励（作废）',$id,4);//3-玩家 4-区服
                }
                die(json_encode(['code'=>1,'message'=>'操作成功']));
            }
            $pushContent = json_decode($reward['prop'],true);
            if($reward->type ==1){//玩家奖励
                //推送服务端
                $binding = $pushContent['bind'][0]==1?1:0;//1-绑定 0-未绑定
                $content = ['MailTitle'=>$reward->title,'MailContent'=>$reward->content,'Hyperlink'=>$reward->sender,'HyperlinkText'=>$reward->contentOther,'ItemId'=>$pushContent['propId'][0],'ItemNum'=>$pushContent['number'][0],'RoleId'=>$reward->roleId,'binding'=>$binding];
                Methods::GmFileGet($content,$reward->serverId,6,4113);//4113 单人邮件
                OperationLog::logAdd('审核玩家奖励（通过并推送服务端）',$id,3);//3-玩家 4-区服
                die(json_encode(['code'=>1,'message'=>'操作成功']));
            }elseif($reward->type ==2){//区服奖励
                //推送服务端
                if($reward->sendTime){
                    $sendTime = strtotime($reward->sendTime);
                    if($sendTime < time()){//小于当前时间
                        $sendTime = 0;
                    }
                }else{
                    $sendTime = 0;
                }
                $propIds = $pushContent['propId'];
                $numbers = $pushContent['number'];
                $binds = $pushContent['bind'];
                $ids = [];
                $itemList = [];
                foreach($propIds as $k => $v){
                    if(!in_array([$v,$binds[$k]],$ids)){
                        $ids[] = [$v,$binds[$k]];
                    }
                    $binding = $binds[$k]==1?1:0;//1-绑定 0-未绑定
                    $itemList[] = ['ItemId'=>$v,'ItemNum'=>$numbers[$k],'binding'=>$binding];
                }
                $propNum = count($ids);
                $content = ['SendTime'=>$sendTime,'MinLevel'=>$reward->minLevel,'MaxLevel'=>$reward->maxLevel,'MailTitle'=>$reward->title,'MailContent'=>$reward->content,'Hyperlink'=>$reward->sender,'ButtonContent'=>$reward->contentOther,'ItemList'=>$itemList,'ItemList_count'=>$propNum];
                Methods::GmFileGet($content,$reward->serverId,6,4143);//4143 区服邮件
                OperationLog::logAdd('审核区服奖励（通过并推送服务端）',$id,4);
                die(json_encode(['code'=>1,'message'=>'操作成功']));
            }else{
                die(json_encode(['code'=>0,'message'=>'奖励类型错误']));
            }
        }else{
            $type = Yii::$app->request->get('type',0);//1-玩家 2-区服
            $roleId = Yii::$app->request->get('uid','');
            $serverId = Yii::$app->request->get('server');
            $name = Yii::$app->request->get('name','');
            $where = " status = 0 ";//未审核
            if($name){
                $roleId = Player::find()->where("Name = '{$name}'")->asArray()->one()['RoleID'];
                if($roleId){
                    $where .= " and roleId = '{$roleId}'";
                }else{
                    $where .= " and 1 > 2";
                }
            }
            if($type){
                $where .= " and type = $type";
            }
            if($serverId){
                $where .= " and serverId = $serverId ";
            }
            if($roleId){
                $where .= " and roleId = '{$roleId}'";
            }
            $count = RewardRecord::find()->where($where)->count();
            $page = new Pagination(['totalCount'=>$count]);
            $data = RewardRecord::find()->where($where)->orderBy('id desc')->offset($page->offset)->limit($page->limit)->asArray()->all();
            $servers = Server::getServers();
            foreach($data as $k => $v){
                if($v['roleId']){
                $roleName = Player::find()->where("RoleID = '{$v['roleId']}'")->asArray()->one()['Name'];
                }else{
                    $roleName = '';
                }
                $data[$k]['roleName'] = $roleName;
                $pushContent = json_decode($v['prop'],true);
                $content = [];
                foreach($pushContent['propId'] as $t => $r){
                    $propName = Item::find()->where("itemid = $r")->asArray()->one()['name'];
                    $bindStr = $pushContent['bind'][$t]==1?'绑定':'未绑定';
                    $content[] = $propName.'-'.$r.'-'.$pushContent['number'][$t].'-'.$bindStr.'    ';
                }
                $data[$k]['pushContent'] = implode("\n",$content);
            }
            return $this->render('reward-check',['data'=>$data,'page'=>$page,'count'=>$count,'servers'=>$servers]);
        }
    }
    /**
     * 发奖操作记录
     */
    public function actionRewardRecord(){
        $action = Yii::$app->controller->action->id;
        parent::setActionId($action);
        $service = \Yii::$app->request->get('server');
        $uid = \Yii::$app->request->get('uid');
        $name = Yii::$app->request->get('name','');
        $where = ' 1=1 ';
        if($service){
            $where .= " and serverId = '{$service}'";
        }
        if($uid){
            $where .= " and roleId = $uid ";
        }
        if($name){
            $roleId = Player::find()->where("Name = '{$name}'")->asArray()->one()['RoleID'];
            if($roleId){
                $where .= " and roleId = '{$roleId}'";
            }else{
                $where .= " and 1 > 2";
            }
        }
        $count = RewardRecord::find()->where($where)->count();
        $page = new Pagination(['totalCount'=>$count]);
        $data = RewardRecord::find()->where($where)->orderBy('id desc')->offset($page->offset)->limit($page->limit)->asArray()->all();
        foreach($data as $k => $v){
            if($v['roleId']){
                $roleName = Player::find()->where("RoleID = '{$v['roleId']}'")->asArray()->one()['Name'];
            }else{
                $roleName = '';
            }
            $data[$k]['roleName'] = $roleName;
            //操作者
            $data[$k]['adminName'] = Role::find()->where("id = {$v['creator']}")->asArray()->one()['name'];
            //审核人
            if($v['status'] != 0){
                $checkName = Role::find()->where("id = {$v['checker']}")->asArray()->one()['name'];
                $statusStr = $v['status']==1?'审核通过':'审核作废';
            }else{
                $checkName = '';
                $statusStr = '待审核';
            }
            $data[$k]['statusStr'] = $statusStr;
            $data[$k]['checkName'] = $checkName;
            $pushContent = json_decode($v['prop'],true);
            $content = [];
            foreach($pushContent['propId'] as $t => $r){
                $propName = Item::find()->where("itemid = $r")->asArray()->one()['name'];
                $bindStr = $pushContent['bind'][$t]==1?'绑定':'未绑定';
                $content[] = $propName.'-'.$r.'-'.$pushContent['number'][$t].'-'.$bindStr.'    ';
            }
            $data[$k]['pushContent'] = implode("\n",$content);
         }
        $servers = Server::getServers();
        return $this->render('reward-record',['data'=>$data,'count'=>$count,'page'=>$page,'servers'=>$servers]);
    }
    /**
     * 区服添加公告
     */
    public function actionServiceAddNotice(){
        $action = Yii::$app->controller->action->id;
        parent::setActionId($action);
        if($_POST){

        }else{
            return $this->render('service-add-notice',[]);
        }
    }
    /**
     * 公告查询
     */
    public function actionNoticeQuery(){
        $action = Yii::$app->controller->action->id;
        parent::setActionId($action);
        $serverId = Yii::$app->request->get('server',0);
        $type = Yii::$app->request->get('type');//1-首页公告 2-跑马灯
        $where = " 1 = 1 ";
        if($serverId){
            $where .= " and serverId = $serverId";
        }
        if($type){
            $where .= " and type = $type";
        }
        $count = Notice::find()->where($where)->count();
        $page = new Pagination(['totalCount'=>$count]);
        $data = Notice::find()->where($where)->orderBy('id desc')->offset($page->offset)->limit($page->limit)->asArray()->all();
        foreach($data as $k => $v){
            $data[$k]['createName'] = Role::find()->where("id = {$v['creator']}")->asArray()->one()['name'];
        }
        $servers = Server::getServers();
        return $this->render('notice-query',['data'=>$data,'count'=>$count,'page'=>$page,'servers'=>$servers]);
    }
    /**
     * 首页公告
     */
    public function actionHomeNotice(){
        $action = Yii::$app->controller->action->id;
        parent::setActionId($action);
        if($_POST){
            $id = Yii::$app->request->post('id',0);
            $beginTime = Yii::$app->request->post('beginTime');
            $endTime = Yii::$app->request->post('endTime');
            $content = Yii::$app->request->post('content');
            if(!$content){
                echo "<script>alert('请填写公告内容');setTimeout(function(){history.go(-1);},1000)</script>";die;
            }
            if($id){
                $model = Notice::findOne($id);
                $remark = '修改首页公告';
            }else{
                $remark = '添加首页公告';
                $model = new Notice();
            }
            $model->content = $content;
            $model->beginTime = $beginTime;
            $model->endTime = $endTime;
            $model->creator = Yii::$app->session->get('adminId');
            $model->type = 1;//1-首页公告
            $model->createTime = time();
            $res = $model->save();
            if($res){
                OperationLog::logAdd($remark,$model->id,6);//6-首页公告
                //文件记录 便于客户端获取公告内容
                OperationLog::setNoticeLog($content,$beginTime,$endTime,$model->id);
                echo "<script>alert('操作成功');setTimeout(function(){location.href='notice-query';},1000)</script>";die;
            }else{
                echo "<script>alert('添加失败，请重试');setTimeout(function(){history.go(-1);},1000)</script>";die;
            }
        }else{
            $id = Yii::$app->request->get('id',0);
            if($id){
                $notice = Notice::find()->where("id = $id")->asArray()->one();
            }else{
                $notice = Notice::find()->where("type = 1 and current = 1")->asArray()->one();
            }
            $data = ['notice'=>$notice];
            return $this->render('index-notice',$data);
        }
    }
    /**
     * 公告删除
     */
    public function actionNoticeDelete(){
        $id = Yii::$app->request->get('id');
        if($id){
            $notice = Notice::findOne($id);
            $eventId = $notice->eventId;
            $endTime = strtotime($notice->endTime);
            $serverId = $notice->serverId;
            $type = $notice->type;
            $res = Notice::deleteAll("id = $id");
            if($res ){
                if($type == 2){//跑马灯公告
                    $current = time();
                    if($endTime > $current){//未到结束时间 需推送服务端进行公告显示删除
                        //推送服务端
                        $pushContent = ['head'=>['Cmdid'=>4145],'body'=>['Partition'=>intval($serverId),'Type'=>1,'EventId'=>$eventId]];
                        Methods::GmPushContent($pushContent);
                    }
                }
                $remark = $type==1?"删除首页公告":"删除跑马灯公告";
                $logType = $type==1?6:5;//5-跑马灯 6-首页
                OperationLog::logAdd($remark,$id,$logType);
                echo "<script>alert('删除成功');setTimeout(function(){location.href='notice-query';},1000)</script>";die;
            }else{
                echo "<script>alert('操作失败，请重试');setTimeout(function(){history.go(-1);},1000)</script>";die;
            }
        }else{
            echo "<script>alert('操作失败，请重试');setTimeout(function(){history.go(-1);},1000)</script>";die;
        }
    }
    /**
     * 命令推送
     */
    public function actionGmPush(){
        $action = Yii::$app->controller->action->id;
        parent::setActionId($action);
        if($_POST){
            $server = Yii::$app->request->post('server');
//            $roleId = Yii::$app->request->post('roleId');
            $name = Yii::$app->request->post('name','');
            $prefix = Yii::$app->request->post('prefix');
            $params = Yii::$app->request->post('params');
            if(!$server){
                echo "<script>alert('请选择区服');setTimeout(function(){history.go(-1);},1000)</script>";die;
            }
            if(!$prefix){
                echo "<script>alert('请填写命令前缀');setTimeout(function(){history.go(-1);},1000)</script>";die;
            }
            if(!$params){
                echo "<script>alert('请填写命令参数');setTimeout(function(){history.go(-1);},1000)</script>";die;
            }
            //获取roleID
            if($name){
                $roleId = Player::find()->where("Name = '{$name}'")->asArray()->one()['RoleID'];
            }else{
                $roleId = '';
            }
            //记录日志并推送服务端
            OperationLog::logAdd("推送".$server."服GM命令 $prefix $params",$roleId,2);//2-gm命令
            $content = ['GMInstruct'=>$prefix,'GMParam'=>$params];
            if($roleId){
                $content['RoleId'] = $roleId;
            }
            Methods::GmFileGet($content,$server,6,4233);//4233 推送gm命令
            echo "<script>alert('推送成功');setTimeout(function(){location.href='gm-push';},1000)</script>";die;
        }else{
            $servers = Server::getServers();
            return $this->render('gm-push',['servers'=>$servers]);
        }
    }
    /**
     * 跑马灯公告
     */
    public function actionRollNotice(){
        $action = Yii::$app->controller->action->id;
        parent::setActionId($action);
        if($_POST){
//            $server = Yii::$app->request->post('server',0);//0-区服
            $servers = Yii::$app->request->post('serverIds',0);//0-区服
            $beginTime = Yii::$app->request->post('beginTime',0);
            $endTime = Yii::$app->request->post('endTime',0);
            $intervalTime = Yii::$app->request->post('intervalTime',30);
            $content = Yii::$app->request->post('content');
            if(!$servers){
                $servers = [0];
            }elseif($servers[0] ==0){
                $servers = [0];
            }
            if(!$beginTime){
                echo "<script>alert('请选择开始时间');setTimeout(function(){history.go(-1);},1000)</script>";die;
            }else{
                $begin = strtotime($beginTime);
            }
            if(!$endTime){
                echo "<script>alert('请选择结束时间');setTimeout(function(){history.go(-1);},1000)</script>";die;
            }else{
                $end = strtotime($endTime);
                $current = time();
                if($current > $end){
                    echo "<script>alert('结束时间必须大于当前时间');setTimeout(function(){history.go(-1);},1000)</script>";die;
                }
            }
            if(!$intervalTime || $intervalTime < 30){
                echo "<script>alert('请填写正确的间隔时间（30秒起步）');setTimeout(function(){history.go(-1);},1000)</script>";die;
            }
            if(!$content){
                echo "<script>alert('请填写公告内容');setTimeout(function(){history.go(-1);},1000)</script>";die;
            }
            $count = 0;
            foreach($servers as $t => $y){
                $model = new Notice();
                $model->content = $content;
                $model->serverId = $y;
                $model->creator = Yii::$app->session->get('adminId');
                $model->beginTime = $beginTime;
                $model->endTime = $endTime;
                $model->type = 2;//1-首页  2-跑马灯
                $model->createTime = time();
                $model->intervalTime = $intervalTime;
                $res = $model->save();
                if($res){
                    $count += 1;
                    OperationLog::logAdd('添加跑马灯公告并推送服务端',$model->id,5);//5-跑马灯公告
                    //推送服务端
                    $pushContent = ['head'=>['Cmdid'=>4137],'body'=>['Partition'=>intval($y),'BeginTime'=>$begin,'EndTime'=>$end,'RollingIntervalTime'=>intval($intervalTime),'NoticeContent'=>$content]];
                    $return = Methods::GmPushContent($pushContent);//服务端推送
                    //推送成功后记录对应的eventId 便于后续删除
                    $return = json_decode($return,true);
                    $eventId = $return['body']['EventId'];
                    Notice::updateAll(['eventId'=>$eventId],"id = {$model->id}");
                }
            }
            if($count){
                echo "<script>alert('操作成功');setTimeout(function(){location.href='roll-notice';},1000)</script>";die;
            }else{
                echo "<script>alert('添加失败，请重试');setTimeout(function(){history.go(-1);},1000)</script>";die;
            }
        }else{
            $servers = Server::getServers();
            return $this->render('roll-notice',['servers'=>$servers]);
        }
    }
    /**
     * 跑马灯公告
     */
    public function actionServerClose(){
        $action = Yii::$app->controller->action->id;
        parent::setActionId($action);
        if($_POST){
            $server = Yii::$app->request->post('server',0);//0-区服
            $content = Yii::$app->request->post('content');
            $server = $server?$server:0;
            if(!$content){
                echo "<script>alert('请填写关服通知');setTimeout(function(){history.go(-1);},1000)</script>";die;
            }
            $model = new Notice();
            $model->content = $content;
            $model->serverId = $server;
            $model->creator = Yii::$app->session->get('adminId');
            $model->type = 3;//1-首页  2-跑马灯 3-服务器关服
            $model->createTime = time();
            $res = $model->save();
            if($res){
                OperationLog::logAdd('添加服务器关服通知并推送服务端',$model->id,5);//5-跑马灯公告
                //推送服务端
                Methods::GmFileGet($content,$server,6,4243);//4243 服务器关服
                echo "<script>alert('操作成功');setTimeout(function(){location.href='server-close';},1000)</script>";die;
            }else{
                echo "<script>alert('添加失败，请重试');setTimeout(function(){history.go(-1);},1000)</script>";die;
            }
        }else{
            $servers = Server::getServers();
            return $this->render('server-close',['servers'=>$servers]);
        }
    }
    /**
     * 银商数据
     */
    public function actionSilverMerchant(){
        $action = Yii::$app->controller->action->id;
        parent::setActionId($action);
        $userId  = Yii::$app->request->get('userId');
        $page = Yii::$app->request->get('page',1);
        $where = ' 1 = 1 ';
        if($userId){
            $where .=" and sm.UserID = '{$userId}'";
        }
        $relation = SliverMerchant::getSliverMerchantMsg($where,$page);
        return $this->render('silver-merchant',$relation);
    }
    /**
     * 银商数据
     * 商人数据编辑修改
     */
    public function actionSilverMerchantAdd(){
        if($_POST){
            $userId = Yii::$app->request->post('userId');
            $contact = Yii::$app->request->post('contact');
            $servers = Yii::$app->request->post('serverIds');
            if(!$userId){
                echo "<script>alert('商人不存在');setTimeout(function(){history.go(-1);},1000)</script>";die;
            }
            if($servers){
                if($servers[0] == 0){//全服
                    $serverIds = Server::find()->select("group_concat(game_id) as ids")->asArray()->one()['ids'];
                }else{
                    $serverIds = implode(',',$servers);
                }
            }else{
                $serverIds = '';
            }
            //通知服务端
            $content = ['UserID'=>$userId,'contact'=>$contact,'enterID'=>$serverIds];
            $host = $_SERVER['HTTP_HOST'];
            if($host == 'www.6p39k.cn' || $host == '6p39k.cn'){
                $serverId = 999999;
            }else{
                $serverId = 903;
            }
            Methods::GmFileGet($content,$serverId,6,4244);//4244 添加银商联系方式
            echo "<script>alert('保存成功');setTimeout(function(){location.href='silver-merchant';},1000)</script>";die;
        }else{
            $userId = Yii::$app->request->get('userId');
            if($userId){
                $data = SliverMerchant::find()->where(" UserID = '{$userId}'")->asArray()->one();
                if($data['enterWorldID']){
                    $data['enterWorldID'] = trim($data['enterWorldID'],',');
                }
            }else{
                $data = [];
            }
            return $this->render('silver-merchant-add',['data'=>$data]);
        }
    }
    /**
     * 代码推送
     * id 4246
     */
    public function actionCodePush(){
        $action = Yii::$app->controller->action->id;
        parent::setActionId($action);
        if($_POST){
            $server = Yii::$app->request->post('server');
            $info = Yii::$app->request->post('info');
            if(!$server){
                echo "<script>alert('请选择区服');setTimeout(function(){history.go(-1);},1000)</script>";die;
            }elseif($server == -99){
                $server = 0;
            }
            if(!$info){
                echo "<script>alert('请填写内容');setTimeout(function(){history.go(-1);},1000)</script>";die;
            }
            //记录日志并推送服务端
            OperationLog::logAdd("推送".$server."服代码命令：$info",$server,7);//7-代码推送
            $content = ['info'=>$info];
            Methods::GmFileGet($content,intval($server),6,4246);//4246 推送代码命令
            echo "<script>alert('推送成功');setTimeout(function(){location.href='code-push';},1000)</script>";die;
        }else{
            $servers = Server::getServers();
            return $this->render('code-push',['servers'=>$servers]);
        }
    }
    /**
     * 公告查询
     * 代码推送日志
     */
    public function actionCodePushLog(){
        $action = Yii::$app->controller->action->id;
        parent::setActionId($action);
        $serverId = Yii::$app->request->get('server',0);
        $where = " type = 7 ";//代码推送日志
        if($serverId){
            $where .= " and object = $serverId";
        }
        $count = OperationLog::find()->where($where)->count();
        $page = new Pagination(['totalCount'=>$count]);
        $data = OperationLog::find()->where($where)->orderBy('id desc')->offset($page->offset)->limit($page->limit)->asArray()->all();
        foreach($data as $k => $v){
            $data[$k]['createName'] = Role::find()->where("id = {$v['adminId']}")->asArray()->one()['name'];
            $data[$k]['createTime'] = date('Y-m-d H:i:s',$v['createTime']);
            $content = explode('服代码命令：',$v['remark']);
            $data[$k]['content'] = isset($content[1])?$content[1]:'';
        }
        $servers = Server::getServers();
        return $this->render('code-push-log',['data'=>$data,'count'=>$count,'page'=>$page,'servers'=>$servers]);
    }
    /**
     * 商人排名
     */
    public function actionMerchantOrder(){
        $action = Yii::$app->controller->action->id;
        parent::setActionId($action);
        $roleId = Yii::$app->request->get('roleId');
        $userId = Yii::$app->request->get('userId');
        $name = Yii::$app->request->get('name');
        $serverId = Yii::$app->request->get('server');
        $where = " 1 = 1" ;
        if($roleId){
            $where .= " and RoleID = '{$roleId}'";
        }
        if($name){//游戏名
            $nameRoleId = Player::find()->where("Name = '{$name}'")->asArray()->one()['RoleID'];
            if($nameRoleId){
                $where .= " and RoleID = '{$nameRoleId}'";
            }else{
                $where .= " and 1 > 2 ";
            }
        }
        if($userId){//账号
            $userIds = Player::find()->where("UserID = '{$userId}'")->asArray()->all();
            $roleIds = "";
            foreach($userIds as $k => $v){
                $roleIds .= "'".$v['RoleID']."',";
            }
            if($roleIds){
                $roleIds = trim($roleIds,',');
                $where .= " and roleID in ($roleIds)";
            }else{
                $where .= " and 1 > 2";
            }
        }
        if($serverId){
            $where .= " and WorldID = $serverId";
        }
        $total = YinShang::find()->where($where)->count();
        $page = new Pagination(['totalCount'=>$total]);
        $data = YinShang::find()->where($where)->offset($page->offset)->limit($page->limit)->asArray()->orderBy('Ingot desc')->all();
        foreach($data as $k => $v){
            $role = Player::find()->where("RoleID = '{$v['RoleID']}'")->asArray()->one();
            $data[$k]['userId'] = $role['UserID'];
            $data[$k]['name'] = $role['Name'];
        }
        $servers = Server::getServers();
        return $this->render('merchant-order',['data'=>$data,'count'=>$total,'page'=>$page,'servers'=>$servers]);

    }
    /**
     * 商人排名修改
     */
    public function actionMerchantOrderAdd(){
        if($_POST){
            $roleId = Yii::$app->request->post('roleId');
            $rank = Yii::$app->request->post('rank',0);
            $servers = Yii::$app->request->post('serverIds');
            if($servers){
                if($servers[0] == 0){//全服
                    $serverIds = Server::find()->select("group_concat(game_id) as ids")->asArray()->one()['ids'];
                }else{
                    $serverIds = implode(',',$servers);
                }
            }else{
                $serverIds = '';
            }
            if(!$roleId){
                echo "<script>alert('角色id不存在');setTimeout(function(){history.go(-1);},1000)</script>";die;
            }
            $res = YinShang::updateAll(['Ingot'=>$rank,'WorldID'=>$serverIds],"RoleID = '{$roleId}'");
            if($res){
                echo "<script>alert('操作成功');setTimeout(function(){location.href='merchant-order';},1000)</script>";die;
            }else{
                echo "<script>alert('操作失败，请重试');setTimeout(function(){history.go(-1);},1000)</script>";die;
            }
        }else{
            $roleId = Yii::$app->request->get('roleId');
            $data = YinShang::find()->where("RoleID = '{$roleId}'")->asArray()->one();
            $player = Player::find()->where("RoleID = '{$roleId}'")->asArray()->one();
            $data['userId'] = $player['UserID'];
            $data['name'] = $player['Name'];
            $servers = Server::getServers();
            return $this->render('merchant-order-add',['data'=>$data,'servers'=>$servers]);
        }
    }
    /**
     * 禁言解封
     */
    public function actionForbidden(){
        $action = Yii::$app->controller->action->id;
        parent::setActionId($action);
        $roleId = Yii::$app->request->get('roleId');
        $userId = Yii::$app->request->get('userId');
        $name = Yii::$app->request->get('name');
        $type = Yii::$app->request->get('type',0);//1-禁言 2-封号 3-禁言解封 4-封号解封
        $where = " 1 = 1" ;
        if($userId){
            $where .= " and userId = '{$userId}'";
        }
        if($name){
            $userId = Player::find()->where("Name = '{$name}'")->asArray()->one()['UserID'];
            if($userId){
                $where .= " and userId = '{$userId}'";
            }else{
                $where .= " and 1 > 2 ";
            }
        }
        if($roleId){
            $userId = Player::find()->where("RoleID = '{$roleId}'")->asArray()->one()['UserID'];
            if($userId){
                $where .= " and userId = '{$userId}'";
            }else{
                $where .= " and 1 > 2 ";
            }
        }
        if($type){
            $where .= " and type = $type";
        }
        $total = ForbiddenRecord::find()->where($where)->count();
        $page = new Pagination(['totalCount'=>$total]);
        $data = ForbiddenRecord::find()->where($where)->asArray()->orderBy('id desc')->offset($page->offset)->limit(20)->all();
        foreach($data as $k => $v){
            $data[$k]['createName'] = Role::find()->where("id = {$v['createUser']}")->asArray()->one()['name'];
            $data[$k]['createTime'] = date('Y-m-d H:i:s',$v['createTime']);
            $typeStr = ''; // 1-禁言 2-封号 3-禁言解封 4-封号解封
            if($v['type'] ==1){
                $typeStr = '账号禁言';
            }elseif($v['type'] == 2){
                $typeStr = '账号封号';
            }elseif($v['type'] == 3){
                $typeStr = '账号禁言解封';
            }elseif($v['type'] == 4){
                $typeStr = '账号封号解封';
            }
            $data[$k]['typeStr'] = $typeStr;
        }
        return $this->render('forbidden-record',['data'=>$data,'count'=>$total,'page'=>$page]);
    }
    /**
     * 账号禁言封号操作
     */
    public function actionForbiddenAdd(){
        if($_POST){
            $roleMsg = Yii::$app->request->post('userId');
            $type = Yii::$app->request->post('type',0);// 1-禁言 2-封号 3-禁言解封 4-封号解封
            $day = Yii::$app->request->post('day',0);//禁言封号天数
            if(!$roleMsg){
                echo "<script>alert('请填写角色信息');setTimeout(function(){history.go(-1);},1000)</script>";die;
            }else{
                $userId = Player::find()->where("Name = '{$roleMsg}' or RoleID = '{$roleMsg}' or UserID = '{$roleMsg}'")->asArray()->one()['UserID'];
                if(!$userId){//角色表查找不到再在注册表里查找
                    $userId = User::find()->where("Username = '{$roleMsg}' or UserID = '{$roleMsg}'")->asArray()->one()['UserID'];
                    if(!$userId){
                        echo "<script>alert('没有该账号');setTimeout(function(){history.go(-1);},1000)</script>";die;
                    }
                }
            }
            if(!$type){
                echo "<script>alert('请选择操作类型');setTimeout(function(){history.go(-1);},1000)</script>";die;
            }
            $remark = '';
            if($type == 1){
                $day = Yii::$app->request->post('jyday',0);//禁言封号天数
                $remark = '禁言账号（'.$userId.'）'.$day.'天';
                if(!$day){
                    echo "<script>alert('请选择禁言时间');setTimeout(function(){history.go(-1);},1000)</script>";die;
                }
            }elseif($type == 2){
                $day = Yii::$app->request->post('fhday',0);//禁言封号天数
                $remark = '账号（'.$userId.'）封号'.$day.'天';
                if(!$day){
                    echo "<script>alert('请选择封号时间');setTimeout(function(){history.go(-1);},1000)</script>";die;
                }
            }elseif($type ==3){
                $remark = '账号（'.$userId.'）禁言解封';
            }elseif($type ==4){
                $remark = '账号（'.$userId.'）封号解封';
            }
            $now = time();
            $model = new ForbiddenRecord();
            $model->userId = $userId;
            $model->type = $type;
            $model->day  = $day;
            $model->remark = $remark;
            $model->createTime = $now;
            $model->createUser = Yii::$app->session->get('adminId');
            $res = $model->save();
            if($res){
                $content = ['UserID'=>$userId];
                //推送服务器
                if($type ==1){//禁言
                    $SpeakTime = 86400*$day + $now;
                    $content['SpeakTime'] = $SpeakTime;
                }
                if($type ==2){//封号
                    $OnlineTime = 86400*$day + $now;
                    $content['OnlineTime'] = $OnlineTime;
                }
                if($type ==3 ){//禁言解封
                    $SpeakTime = 0;
                    $content['SpeakTime'] = $SpeakTime;
                }
                if($type ==4 ){//封号解封
                    $OnlineTime = 0;
                    $content['OnlineTime'] = $OnlineTime;
                }
                $servers = Server::getServers();
                foreach($servers as $k => $v){
                     Methods::GmFileGet($content,$v['id'],6,4247);//4247 禁言封号
                }
                echo "<script>alert('操作成功');setTimeout(function(){location.href='forbidden';},1000)</script>";die;
            }else{
                echo "<script>alert('操作成功');setTimeout(function(){history.go(-1);},1000)</script>";die;
            }
        }else{
            return $this->render('forbidden-add');
        }
    }
    /**
     * 游戏数据库sql查询
     */
    public function actionLegendSql(){
        $action = Yii::$app->controller->action->id;
        parent::setActionId($action);
        if($_POST){
            $sql = Yii::$app->request->post('sql');
            if(!$sql){
                echo "<script>alert('请输入sql语句');setTimeout(function(){history.go(-1);},1000)</script>";die;
            }
            $data = Yii::$app->db2->createCommand($sql)->queryAll();
            return $this->render('legend-sql',['data'=>$data,'sql'=>$sql]);
        }else{
            return $this->render('legend-sql');
        }
    }
}