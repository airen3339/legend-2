<?php


namespace app\modules\content\controllers;


use app\libs\Methods;
use app\modules\content\models\ActivityLog;
use app\modules\content\models\ActivityPush;
use app\modules\content\models\ActivityType;
use app\modules\content\models\Catalog;
use app\modules\content\models\CurrencyData;
use app\modules\content\models\GameError;
use app\modules\content\models\Item;
use app\modules\content\models\Notice;
use app\modules\content\models\OperationLog;
use app\modules\content\models\QuestionCategory;
use app\modules\content\models\RewardRecord;
use app\modules\content\models\Role;
use app\modules\content\models\RoleActivity;
use app\modules\content\models\RoleFeedback;
use app\modules\content\models\Server;
use app\modules\content\models\YinShang;
use app\modules\content\models\YuanbaoRole;
use app\modules\content\models\YuanbaoRoleLog;
use app\modules\pay\models\Lottery;
use app\modules\pay\models\Recharge;
use Hyperbolaa\Wechatpay\Facades\Jsapi;
use yii\base\Exception;
use yii\web\Controller;
use Yii;

header('Access-Control-Allow-Origin:*');

class ApiController extends  Controller
{
    public $enableCsrfValidation = false;
    /**
     * 获取分类
     * 左边导航目录
     */
    public function actionGetCategory(){
        $model = new Catalog();
        $pid = Yii::$app->request->get('pid','0');
        $id = Yii::$app->request->get('id','');
        $data = $model->getAllCate($pid,$id);
//        var_dump($data);
        echo json_encode($data);
        exit;
    }

    /**
     * 获取分类树包括一级分类
     * 左边导航目录
     */
    public function actionTree(){
        $model = new Catalog();
        $pid = Yii::$app->request->get('pid',0);
        $id = Yii::$app->request->get('id','');
        $data = $model->getTree($pid,$id);
        echo json_encode($data);
        exit;
    }
    /**
     * 获取分类树包括一级分类
     * 游戏区服数据
     */
    public function actionServer(){
        $id = Yii::$app->request->get('id');
        $data = Server::getServerData($id);
        echo json_encode($data);
        exit;
    }
    /**
     * 设置排序号
     * 左边导航目录
     */
    public function actionSetRank(){
        $id = Yii::$app->request->post("id");
        $rank = Yii::$app->request->post("rank");
        $res = Catalog::updateAll(['rank'=>$rank],"id = $id");
        if($res){
            $data = ['code'=>1,'message'=>'success'];
        }else{
            $data = ['code'=>0,'message'=>'fail'];
        }
        die(json_encode($data));
    }
    /**
     * 检查是否能够删除分类
     * 左边导航目录
     */
    public function actionCheckDelete(){
        $id = Yii::$app->request->post('id');
        $rowCate = Catalog::find()->where("pid=$id")->all();
        if(count($rowCate)>0 ){
            $code = 0;
        }else{
            $code = 1;
        }
        die(json_encode(['code' => $code]));
    }
    /**
     * 获取分类
     * 问题分类
     * 单据功能
     */
    public function actionGetQuestionCategory(){
        $model = new QuestionCategory();
        $pid = Yii::$app->request->get('pid','0');
        $id = Yii::$app->request->get('id','');
        $data = $model->getAllCate($pid,$id);
        echo json_encode($data);
        exit;
    }

    /**
     * 获取分类树包括一级分类
     * 问题分类
     * 单据功能
     */
    public function actionQuestionTree(){
        $model = new QuestionCategory();
        $pid = Yii::$app->request->get('pid',0);
        $id = Yii::$app->request->get('id','');
        $data = $model->getTree($pid,$id);
        echo json_encode($data);
        exit;
    }
    /**
     * 设置排序号
     * 问题分类
     * 单据功能
     */
    public function actionSetQuestionRank(){
        $id = Yii::$app->request->post("id");
        $rank = Yii::$app->request->post("rank");
        $res = QuestionCategory::updateAll(['rank'=>$rank],"id = $id");
        if($res){
            $data = ['code'=>1,'message'=>'success'];
        }else{
            $data = ['code'=>0,'message'=>'fail'];
        }
        die(json_encode($data));
    }
    /**
     * 检查是否能够删除分类
     * 问题分类
     * 单据功能
     */
    public function actionCheckQuestionDelete(){
        $id = Yii::$app->request->post('id');
        $rowCate = QuestionCategory::find()->where("pid=$id")->all();
        if(count($rowCate)>0 ){
            $code = 0;
        }else{
            $code = 1;
        }
        die(json_encode(['code' => $code]));
    }
    /**
     * 设置ip
     */
    public function actionSetIp(){
        $type = Yii::$app->request->post('type',1);
        if($type ==1){//外网
            $ip = 'http://139.9.238.82:8080';
        }else{//内网
            $ip = 'http://192.168.0.15:8080';
        }
        Yii::$app->session->set('ipAddress',$ip);
        $ipAddress = Yii::$app->session->get('ipAddress');
        $data = ['ipAddress'=>$ipAddress];
        die(json_encode($data));
    }
    /**
     * 获取商城道具信息
     */
    public function actionGetItem(){
        $name = Yii::$app->request->post('name','');
        $data = Item::find()->where("name like '%{$name}%'")->asArray()->limit(5)->all();
        die(json_encode($data));
    }
    /**
     * 修改客服账号状态
     */
    public function actionAlterStatus(){
        $id = Yii::$app->request->post('id');
        $type = Yii::$app->request->post('type',0);//0-离线状态  1-在线状态
        $status = $type ==1?0:1;
        $model = Role::findOne($id);
        $model->serviceStatus = $status;
        $model->save();
        $remark = $type==1?'修改请求客服qq状态（下线）':'修改请求客服qq状态（上线）';
        OperationLog::logAdd($remark,$model->qq,1);
        die(json_encode(['code'=>1,'message'=>'修改成功']));
    }
    /**
     * 客服端获取客服账号状态
     * type  1-在线 0-离线
     */
    public function actionGetServiceStatus(){
        $service = Role::find()->select("qq,serviceStatus")->where("service = 1")->asArray()->all();
        die(json_encode($service));
    }
    /**
     * 用户反馈
     * 客户端请求
     */
    public function actionRoleFeedback(){
        $post = Yii::$app->request->post();
        $poststr = json_encode($post);
        $request = json_decode($poststr);
        $content = get_object_vars($request);
        $key = key($content);
        $cont = json_decode($key,true);
        $model = new RoleFeedback();
        $model->roleId = $cont['roleId'];
        $model->roleName = $cont['roleName'];
        $model->serverId = $cont['serverId'];
        $model->feedback = $cont['feedback'];
        $model->feedTime = date('Y-m-d H:i:s');
        $model->createTime = time();
        $res = $model->save();
        if($res){
            $data = ['code'=>1];//成功
        }else{
            $data = ['code'=>0];//失败
        }
        die(json_encode($data));
    }
    /**
     * 用户反馈回复
     */
    public function actionFeedbackReply(){
        $id = Yii::$app->request->post('id');
        $reply = Yii::$app->request->post('reply');
        if($id && $reply){
            $replyId = Yii::$app->session->get('adminId');
            $replyName = Yii::$app->session->get('adminName');
            $time = date('Y-m-d H:i:s');
            $res = RoleFeedback::updateAll(['replyContent'=>$reply,'replyId'=>$replyId,'replyTime'=>$time],"id = $id");
            if($res){
                $data = ['code'=>1,'message'=>'回复成功','replyName'=>$replyName,'replyTime'=>$time];
                //回复成功 通知服务器
                $model = RoleFeedback::findOne($id);
                $content = ['MailTitle'=>'反馈回复','MailContent'=>$reply,'Hyperlink'=>'客服','HyperlinkText'=>'','RoleId'=>$model->roleId];
                Methods::GmFileGet($content,$model->serverId,6,4113);//4113 单人邮件
            }else{
                $data = ['code'=>0,'message'=>'回复失败，请重试'];
            }
        }else{
            $data = ['code'=>0,'message'=>'参数错误'];
        }
        die(json_encode($data));
    }

    /**
     * 获取问题二级分类
     */
    public function actionGetQuestionChild(){
        $pid = \Yii::$app->request->post('pid',0);
        if($pid){
            $data = QuestionCategory::find()->where("pid = $pid")->asArray()->all();
        }else{
            $data = [];
        }
        die(json_encode($data));
    }
    /**
     * 批量审核奖励
     */
    public function actionRewardCheck(){
        $type = Yii::$app->request->post('type',3);//3-通过 4-作废
        $ids = Yii::$app->request->post('ids','');
        $status = $type == 3 ? 1:-1;//1-通过 -1-作废
        $adminId = Yii::$app->session->get('adminId');
        if(!$ids){
            $data = ['code'=>0,'message'=>'操作id不能为空'];
        }else{
            $ids = trim($ids,'=');
            $idArr = explode('=',$ids);
            foreach($idArr as $k => $v){
                $res = RewardRecord::updateAll(['checker'=>$adminId,'status'=>$status],"id = $v");
                if(!$res){
                    $data = ['code'=>0,'message'=>'部分操作失败，请刷新重试'];
                    die(json_encode($data));
                }
                $reward = RewardRecord::findOne($v);
                if($status != 1){//作废
                    if($reward->type ==1){
                        OperationLog::logAdd('审核玩家奖励（作废）',$v,3);//3-玩家 4-区服
                    }else{
                        OperationLog::logAdd('审核区服奖励（作废）',$v,4);//3-玩家 4-区服
                    }
                }else{
                    $pushContent = json_decode($reward['prop'],true);
                    if($reward->type ==1){//玩家奖励
                        //推送服务端
                        $binding = $pushContent['bind'][0]==1?1:0;//1-绑定 0-未绑定
                        $content = ['MailTitle'=>$reward->title,'MailContent'=>$reward->content,'Hyperlink'=>$reward->sender,'HyperlinkText'=>$reward->contentOther,'ItemId'=>$pushContent['propId'][0],'ItemNum'=>$pushContent['number'][0],'RoleId'=>$reward->roleId,'binding'=>$binding];
                        Methods::GmFileGet($content,$reward->serverId,6,4113);//4113 单人邮件
                        OperationLog::logAdd('审核玩家奖励（通过并推送服务端）',$v,3);//3-玩家 4-区服
                    }else{//区服奖励
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
                        $idss = [];
                        $itemList = [];
                        foreach($propIds as $kk => $vk){
                            if(!in_array([$vk,$binds[$kk]],$idss)){
                                $idss[] = [$vk,$binds[$kk]];
                            }
                            $binding = $binds[$kk]==1?1:0;//1-绑定 0-未绑定
                            $itemList[] = ['ItemId'=>$vk,'ItemNum'=>$numbers[$kk],'binding'=>$binding];
                        }
                        $propNum = count($idss);
                        $content = ['SendTime'=>$sendTime,'MinLevel'=>$reward->minLevel,'MaxLevel'=>$reward->maxLevel,'MailTitle'=>$reward->title,'MailContent'=>$reward->content,'Hyperlink'=>$reward->sender,'ButtonContent'=>$reward->contentOther,'ItemList'=>$itemList,'ItemList_count'=>$propNum];
                        Methods::GmFileGet($content,$reward->serverId,6,4143);//4143 区服邮件
                        OperationLog::logAdd('审核区服奖励（通过并推送服务端）',$v,4);
                    }
                }
            }
            $data = ['code'=>1,'message'=>'操作成功'];
        }
        die(json_encode($data));
    }
    /**
     *清除当前公告
     */
    public function actionDeleteCurrentNotice(){
        $current = Notice::find()->where("type = 1 and current = 1")->one();
        if($current){
            $res = Notice::deleteAll("type = 1 and current = 1");
            if($res){
                $path = fopen(IndexDir.'/files/notice/indexNotice.txt','w');
                fwrite($path, '');
                fclose($path);
                $data = ['code'=>1,'message'=>'清除成功'];
            }else{
                $data = ['code'=>0,'message'=>'清除失败，请重试'];
            }
        }else{
            $data  = ['code'=>1,'message'=>'没有当前公告'];
        }
        die(json_encode($data));
    }
    /**
     * 添加银商联系
     */
    public function actionAddContact(){
        $userId = Yii::$app->request->post('userId');
        $contact = Yii::$app->request->post('contact');
        if(!$userId || !$contact){
            $data = ['code'=>0,'message'=>'参数错误'];
        }else{
            //通知服务端
            $content = ['UserID'=>$userId,'contact'=>$contact];
            $host = $_SERVER['HTTP_HOST'];
            if($host == 'www.6p39k.cn' || $host == '6p39k.cn'){
                $serverId = 1;
            }else{
                $serverId = 903;
            }
            Methods::GmFileGet($content,$serverId,6,4244);//4244 添加银商联系方式
            $data = ['code'=>1,'message'=>'添加成功'];
        }
        die(json_encode($data));
    }
    /**
     * 活动类型条件说明获取
     */
    public function actionTypeRemark(){
        $type  = Yii::$app->request->post('id');
        if($type){
            $remark =ActivityType::find()->where("type = $type")->asArray()->one()['remark'];
        }else{
            $remark = '';
        }
        die(json_encode($remark));
    }
    /**
     * 活动内容
     * 复制数据
     */
    public function actionDataSave(){
        $id = Yii::$app->request->post('id');
        if($id){
            $content = ActivityPush::findOne($id);
            if($content){
                $model = new ActivityPush();
                $model->serverId = 0;
                $model->beginTime = $content->beginTime;
                $model->endTime = $content->endTime;
                $model->pushContent = $content->pushContent;
                $model->createTime = time();
                $model->type = $content->type;
                $model->remark = $content->remark;
                $model->operator = $content->operator;
                $res = $model->save();
                if($res){
                    $data = ['code'=>1,'id'=>$model->id];
                }else{
                    $data = ['code'=>0,'message'=>'复制失败'];
                }
            }else{
                $data = ['code'=>0,'message'=>'活动不存在'];
            }
        }else{
            $data = ['code'=>0,'message'=>'参数错误'];
        }
        die(json_encode($data));
    }
    /**
     * 报错日志记录
     */
    public function actionGameError(){
        $content = file_get_contents('php://input');
        if(!is_string($content)){
            $content = json_encode($content);
        }
        $md5 = md5($content);
//        $had = GameError::find()->where("md = '{$md5}'")->one();
//        if($had){
//            $had->total = isset($had->total)?(1+$had->total):1;
//            $had->save();
//        }else{
            $model = new GameError();
            $model->content = $content;
            $model->createTime = time();
            $model->md = $md5;
            $model->total = 1;
            $model->save();
//        }
    }
    /**
     * 错误问题描述
     */
    public function actionSaveDescribe(){
        $describe = Yii::$app->request->post('descri');
        $id = Yii::$app->request->post('id');
        if(!$id){
            Methods::jsonData(0,'id不存在');
        }
        $res = GameError::updateAll(['describe'=>$describe]," id = $id");
        if($res){
            Methods::jsonData(1,'保存成功');
        }else{
            Methods::jsonData(0,'保存失败');
        }
    }
    /**
     * 商人排序
     */
    public function actionMerchantOrder(){
        $roleId = Yii::$app->request->post('roleId');
        $ingot = Yii::$app->request->post('ingot');
        $worldId = Yii::$app->request->post('worldId');
        if(!$roleId){
            Methods::jsonData(0,'角色id不存在');
        }
        $res = YinShang::updateAll(['Ingot'=>$ingot,'WorldID'=>$worldId],"RoleID = '{$roleId}'");
        if($res){
            Methods::jsonData(1,'保存成功');
        }else{
            Methods::jsonData(0,'保存失败');
        }
    }
    /**
     * 元宝消耗记录迁移
     */
    public function actionYuanbaoRecordMove(){
//        $createTime = 1577945249;//更新这个时间以前的数据
//        $sql = " select * from yuanbao_role where unix_timestamp(dateTime) <= $createTime";
//        $data = Yii::$app->db->createCommand($sql)->queryAll();
//        foreach($data as $k => $v){
//            $dateTime = $v['dateTime'];
//            $arr = explode(' ',$dateTime);
//            $model = new YuanbaoRoleLog();
//            $model->date = $v['date'];
//            $model->serverId = $v['serverId'];
//            $model->roleId = $v['roleId'];
//            $model->dateTime = isset($arr[1])?$arr[1]:'';
//            $model->money = $v['money'];
//            $model->type = $v['type'];
//            $model->added = $v['added'];
//            $model->remark = $v['remark'];
//            $model->createTime = strtotime($dateTime);
//            $model->save();
//        }
    }
    /**
     * 补单
     */
    public function actionAddMoney(){
        $orderId = Yii::$app->request->post('orderId','');
        if(!$orderId){
            Methods::jsonData(0,'id不存在');
        }
        $orderData = Recharge::find()->where("orderNumber = '{$orderId}'")->asArray()->one();
        if($orderData['status'] != 1){//订单未完成
            Recharge::updateAll(['status'=>1],"orderNumber='{$orderId}'");//修改订单状态
            //通知服务器处理后续
            $postData = ['uid'=>$orderData['roleId'],'pay_money'=>$orderData['money'],'ratio'=>$orderData['ratio'],'lucknum'=>$orderData['lucknum'],'server_id'=>$orderData['server_id'],'sign'=>$orderData['sign'],'order_no'=>$orderId,'ext_info'=>$orderData['extInfo']];
            $url = \Yii::$app->params['gameServerUrl'];
            Methods::post($url,$postData);
            Methods::jsonData(1,'补单成功');
        }else{
            Methods::jsonData(0,'订单已近是支付成功，不需要补单');
        }
    }
    public function actionTest(){
        Recharge::notifyLog('ceshi',1);
        Recharge::notifyLog('ceshi',2);
    }
    /**
     * 时时彩数据漏洞完善
     */
    public function actionSscDataAdd(){
        $date = Yii::$app->request->post('date');
        if($date){
            $url = "http://d.apiplus.net/daily.do?token=t02ed09c241ad2e34k&code=cqssc&format=json&date=".$date;
            $result = file_get_contents($url);
            $data = json_decode($result,true);
            if(isset($data['data'])){
                $code = $data['code'];
                $insert = $data['data'];
                foreach($insert as $k => $v){
                    $expect = $v['expect'];//开奖编码
                    $openCode = $v['opencode'];//开奖码
                    $openTime = $v['opentime'];//开奖时间   日期时间格式
                    $openUnixTime = $v['opentimestamp'];//开间时间 时间戳
                    $date = explode(' ',$openTime)[0];
                    $time = time();
                    //查看是否已有该条时彩数据
                    $isHad = Lottery::find()->where("expect = '{$expect}' and openCode = '{$openCode}' and code = '{$code}'")->one();
                    if($isHad){
                        continue;
                    }else{
                        $model = new Lottery();
                        $model->date = $date;
                        $model->code = $code;
                        $model->expect = $expect;
                        $model->openCode = $openCode;
                        $model->openTime = $openTime;
                        $model->openUnixTime = $openUnixTime;
                        $model->createTime = $time;
                        $model->save();
                    }
                }
            }
        }
    }
    /**
     * 天中宝藏数据补全
     */
    public function actionTzbzDataAdd(){
//        die;
        $date = Yii::$app->request->post('date');
        ini_set('user_agent','Mozilla/4.0 (compatible; MSIE 5.00; Windows 98)');
//        $date = date('Y-m-d');
        //删除当天的数据记录
        YuanbaoRoleLog::deleteAll("date = '{$date}' and type = 14");
//        CurrencyData::deleteAll("date = '{$date}' and type =14");
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
                    $type = YuanbaoRole::getData($arr[1]);
                    if($type != 14){
                        continue;
                    }
                    $model = new YuanbaoRoleLog();
                    $model->date = $date;
                    $model->serverId = $v['id'];
                    $model->roleId = YuanbaoRole::getData($arr[2]);
                    $model->dateTime = $arr[0];
                    $model->money = YuanbaoRole::getData($arr[4]);
                    $model->type = $type;
                    $model->added = YuanbaoRole::getData($arr[3]);
                    $model->remark = str_replace('explain:','',$arr[5]);
                    $time = $date." ".$arr[0];
                    $model->createTime = strtotime($time);
                    $model->save();
                }
            }catch(\Exception $e){
            }

        }
    }
    /**
     * 天中宝藏道具id统计补全
     */
    public function actionTzbzIdData(){
        $date = Yii::$app->request->post('date');
        if($date){
            RoleActivity::tzbzIdData($date);
        }
    }
    /**
     * item道具添加
     */
    public function actionItemAdd(){
        $data = [
            6908 => '火羽凤凰灵元',
            6909 => '蓬莱怒涛灵元',
            6910 => '冰魄暴熊灵元',
            6911 => '怒神啸光虎灵元',
            6912 => '星煞穷奇灵元',
            6937 => '赤焰鎏金麒麟灵元',
            6990 => '绿茵魅动灵元',
            6916 => '百炼海魂盾灵元',
            6918 => '凤凰血泪灵元',
            6917 => '盾牌炼化灵元',
            7020 => '龙吟鎏金盾灵元',
            6913 => '酒神葫芦灵元',
            6915 => '法宝进阶灵元',
            6914 => '法宝炼化灵元',
            6926 => '鸿鹄之翼灵元',
            6927 => '皇天辉耀仙翼灵元',
            6928 => '紫龙天尊仙翼灵元',
            6929 => '冰雪帝王翼灵元',
            6995 => '逍遥轻烟仙翼灵元',
            7035 =>  '业火焚神翼灵元',
            6919 => '奎木战旗灵元',
            6920 => '紫炎凤凰旗灵元',
            6921 => '瑶光麒麟旗灵元',
            6922 => '紫耀仙踪旗灵元',
            6923 => '玄兵·焰天灵元',
            6924 => '玄兵·业火灵元',
            6925 => '玄兵·遁龙桩灵元',
        ];
        foreach($data as $k => $v){
            $model = new Item();
            $model->itemid = $k;
            $model->name = $v;
            $model->save();
        }
    }
}