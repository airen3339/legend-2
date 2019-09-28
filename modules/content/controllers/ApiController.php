<?php


namespace app\modules\content\controllers;


use app\libs\Methods;
use app\modules\content\models\Catalog;
use app\modules\content\models\Item;
use app\modules\content\models\OperationLog;
use app\modules\content\models\QuestionCategory;
use app\modules\content\models\RewardRecord;
use app\modules\content\models\Role;
use app\modules\content\models\RoleFeedback;
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
    public function actionQuestionSetRank(){
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
                }
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
            $data = ['code'=>1,'message'=>'操作成功'];
        }
        die(json_encode($data));
    }
}