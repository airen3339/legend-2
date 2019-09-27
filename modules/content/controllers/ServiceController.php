<?php
/**
 * 客服模块
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-6-17
 * Time: 下午2:37
 */
namespace app\modules\content\controllers;


use app\libs\AdminController;
use app\modules\content\models\BillMessage;
use app\modules\content\models\QuestionCategory;
use app\modules\content\models\Role;
use app\modules\content\models\RoleFeedback;
use app\modules\content\models\Server;
use yii\data\Pagination;
use Yii;

class ServiceController extends  AdminController {
    public $enableCsrfValidation = false;
    public $layout = 'content';

    public function init(){
        parent::init();
        parent::setContentId('service');
    }
    public function actionIndex(){
        return $this->redirect('/content/index/index');
    }

    /**
     * 客服账号状态
     */
    public function actionServiceStatus(){
        $action = \Yii::$app->controller->action->id;
        parent::setActionId($action);
        $service = Role::find()->where("service = 1")->asArray()->all();
        return $this->render('service-status',['service'=>$service]);
    }

    /**
     * 用户反馈
     */
    public function actionRoleFeedback(){
        $action = \Yii::$app->controller->action->id;
        parent::setActionId($action);
        $beginTime = \Yii::$app->request->get('beginTime');
        $endTime = \Yii::$app->request->get('endTime');
        $server = \Yii::$app->request->get('serverId');
        $content = \Yii::$app->request->get('content');
        $where  = ' 1=1 ';
        if($beginTime){
            $begin = strtotime($beginTime);
            $where .= " and unix_timestamp(feedTime) >= $begin";
        }
        if($endTime){
            $end = strtotime($endTime) + 86399;
            $where .= " and unix_timestamp(feedTime) <= $end";
        }
        if($server){
            $where .= " and serverId = $server";
        }
        if($content){
            $where .= " and (  feedback like '%{$content}%'  or replyContent like '%{$content}%' )" ;
        }
        $count = RoleFeedback::find()->where($where)->count();
        $page = new Pagination(['totalCount'=>$count,'pageSize'=>20]);
        $data = RoleFeedback::find()->where($where)->offset($page->offset)->limit($page->limit)->asArray()->all();
        foreach($data as $k => $v){
            $data[$k]['replyName'] = Role::find()->where("id = {$v['replyId']}")->asArray()->one()['name'];
        }
        $servers = Server::getServers();
        return $this->render('role-feedback',['data'=>$data,'page'=>$page,'count'=>$count,'servers'=>$servers]);
    }

    /**
     * 单据数据
     */
    public function actionBillMessage(){
        $action = \Yii::$app->controller->action->id;
        parent::setActionId($action);
        $where = " 1=1 ";
        $count = BillMessage::find()->where($where)->count();
        $page = new Pagination(['totalCount'=>$count]);
        $bill = BillMessage::find()->where($where)->orderBy('id desc')->asArray()->offset($page->offset)->limit($page->limit)->all();
        $billTypes = BillMessage::getBillTypes();
        $billSources = BillMessage::getBillSources();
        $billGames = BillMessage::getBillGames();
        foreach($bill as $k =>$v){
            //单据类型
            foreach($billTypes as $p => $t){
                if($t['id'] == $v['billType']){
                    $bill[$k]['billType'] = $t['name'];
                    break;
                }
            }
            //单据来源
            foreach($billSources as $p => $t){
                if($t['id'] == $v['billSource']){
                    $bill[$k]['billSource'] = $t['name'];
                    break;
                }
            }
            //游戏所属
            foreach($billGames as $p => $t){
                if($t['id'] == $v['billGame']){
                    $bill[$k]['billGame'] = $t['name'];
                    break;
                }
            }
            //一级分类
            $bill[$k]['quesParent'] = QuestionCategory::find()->where("id = {$v['quesParent']}")->asArray()->one()['name'];
            //二级分类
            $bill[$k]['quesChild'] = QuestionCategory::find()->where("id = {$v['quesChild']}")->asArray()->one()['name'];
        }
        return $this->render('bill-message',['bills'=>$bill,'page'=>$page,'count'=>$count]);
    }
    /**
     * 单据信息编辑
     * cy
     */
    public function actionBillMessageAdd(){
        if($_POST){
            $request = Yii::$app->request;
            $id = $request->post('id',0);
            $billType = $request->post('billType',0);
            $billSource = $request->post('billSource',0);
            $quesParent = $request->post('quesParent');
            $quesChild = $request->post('quesChild');
            $billGame = $request->post('billGame','');
            $vipLevel = $request->post('vipLevel','');
            $account = $request->post('account','');
            $gameName = $request->post('gameName','');
            $gameServer = $request->post('gameServer',0);
            $download = $request->post('download','');
            $gameId = $request->post('gameId','');
            $version = $request->post('version','');
            $device = $request->post('device','');
            $email = $request->post('email','');
            $phone = $request->post('phone','');
            $qq = $request->post('qq','');
            $detail = $request->post('detail');
            $result = $request->post('result');
            $image = $request->post('imageFiles','');
            if(!$billType){
                echo "<script>alert('请选择单据类型！');setTimeout(function(){history.go(-1);},1000)</script>";die;
            }
            if(!$billSource){
                echo "<script>alert('请选择单据来源！');setTimeout(function(){history.go(-1);},1000)</script>";die;
            }
            if(!$quesParent){
                echo "<script>alert('请选择一级分类！');setTimeout(function(){history.go(-1);},1000)</script>";die;
            }
            if(!$quesChild){
                echo "<script>alert('请选择二级分类！');setTimeout(function(){history.go(-1);},1000)</script>";die;
            }
            if(!$billGame){
                echo "<script>alert('请选择游戏所属！');setTimeout(function(){history.go(-1);},1000)</script>";die;
            }
            if(!$gameServer){
                echo "<script>alert('请选择游戏大厅！');setTimeout(function(){history.go(-1);},1000)</script>";die;
            }
            if(!$download){
                echo "<script>alert('请填写下载渠道！');setTimeout(function(){history.go(-1);},1000)</script>";die;
            }
            if(!$gameId){
                echo "<script>alert('请填写游戏ID！');setTimeout(function(){history.go(-1);},1000)</script>";die;
            }
            if(!$detail){
                echo "<script>alert('请填写详细描述！');setTimeout(function(){history.go(-1);},1000)</script>";die;
            }
            if(!$result){
                echo "<script>alert('请填写处理结果！');setTimeout(function(){history.go(-1);},1000)</script>";die;
            }
            if($id){
                $model = BillMessage::findOne($id);
                $model->updateTime = time();
            }else{
                $model  = new BillMessage();
                $model->createTime = time();
            }
            $image = json_encode($image);
            $model->billType = $billType;
            $model->billSource = $billSource;
            $model->billGame = $billGame;
            $model->quesParent = $quesParent;
            $model->quesChild = $quesChild;
            $model->account = $account;
            $model->vipLevel = $vipLevel;
            $model->gameName = $gameName;
            $model->gameServer = $gameServer;
            $model->gameId = $gameId;
            $model->download = $download;
            $model->device = $device;
            $model->version = $version;
            $model->phone = $phone;
            $model->qq = $qq;
            $model->email = $email;
            $model->imageFile = $image;
            $model->detail = $detail;
            $model->result = $result;
            $model->creator = Yii::$app->session->get('adminId');
            $res = $model->save();
            if($res){
                echo "<script>alert('保存成功！');setTimeout(function(){location.href='bill-message';},1000)</script>";
            }else{
                echo "<script>alert('保存失败，请重试！');setTimeout(function(){history.go(-1);},1000)</script>";
            }
        }else{
            $id = \Yii::$app->request->get('id');
            if($id){
                $bill = BillMessage::find()->where("id = $id")->asArray()->one();
                $imageFile = json_decode($bill['imageFile'],true);
                $bill['imageFile'] = is_array($imageFile)?$imageFile:[];
            }else{
                $bill = [];
            }
            //单据类型
            $billTypes = BillMessage::getBillTypes();
            //单据来源
            $billSources = BillMessage::getBillSources();
            //游戏归属
            $billGames = BillMessage::getBillGames();
            //问题一级分类
            $billQuesParent = QuestionCategory::find()->where("pid = 0")->asArray()->all();
            //问题二级分级
            if($id){//修改页面 获取对应的二级分类
                $billQuesChild = QuestionCategory::find()->where("pid = {$bill['quesParent']}")->asArray()->all();
            }else{
                if(isset($billQuesParent[0]['id'])){
                    $billQuesChild = QuestionCategory::find()->where("pid = {$billQuesParent[0]['id']}")->asArray()->all();
                }else{
                    $billQuesChild = [];
                }
            }

            //VIP等级
            $vipLevels = BillMessage::getVipLevels();
            //游戏大区
            $servers = Server::getServers();
            $data = ['billTypes'=>$billTypes,'billSources'=>$billSources,'billGames'=>$billGames,'billQuesParent'=>$billQuesParent,'billQuesChild'=>$billQuesChild,'vipLevels'=>$vipLevels,'servers'=>$servers,'bill'=>$bill];
            return $this->render('bill-message-add',$data);
        }
    }
    /**
     * 目录结构
     *问题分类
     */
    public function actionQuestionCategory(){
        $action = \Yii::$app->controller->action->id;
        parent::setActionId($action);
        return $this->render('question-category');
    }

    /**
     * 添加分类与其基本信息
     * @return string
     */
    public function actionCategoryAdd(){
        if($_POST){
            $model = new QuestionCategory();
            $categoryData = Yii::$app->request->post('category');
            $id = Yii::$app->request->post('id');
            if(empty($categoryData['name'])){
                die('<script>alert("请添加分类名称");history.go(-1);</script>');
            }
            $where = '';
            if($id){
                $where .= " and id != $id";
            }
            $hadName = QuestionCategory::find()->where("name='{$categoryData['name']}' $where")->one();
            if($hadName){
                die('<script>alert("已有该分类，请勿重复添加");history.go(-1);</script>');
            }
            if(empty($categoryData['pid'])){
                $categoryData['pid'] = 0;
            }
            if(empty($categoryData['rank'])){
                $categoryData['rank'] = 0;
            }
            if($id){
                $re = $model->updateAll($categoryData,'id = :id',[':id' => $id]);
            }else{
                $categoryData['createTime'] = time();
                $re = Yii::$app->db->createCommand()->insert("{{%question_category}}",$categoryData)->execute();
            }
            if($re){
                echo '<script>alert("成功")</script>';
                $this->redirect('/content/service/question-category');
            }else{
                echo '<script>alert("失败，请重试");history.go(-1);</script>';
                die;
            }
        } else{
            $pid = Yii::$app->request->get('pid');
            return $this->render('category-add',['pid' => $pid]);
        }
    }
    /**
     * 修改分类
     * @return string
     * @Obelisk
     */
    public function actionCategoryUpdate(){
        $id = Yii::$app->request->get('id');
        $model = new QuestionCategory();
        $cate = $model->find()->asArray()->all();
        $result = $model->find()->where("id= $id")->asArray()->one();
        return $this->render('category-add',array('data'=> $result,'pid' => $result['pid'],'id' => $id,'category'=>$cate));
    }
    /**
     * 删除分类
     * @return string
     */

    public function actionCategoryDelete(){
        $id = Yii::$app->request->get('id');
        $model = new QuestionCategory();
        if($model->findOne($id)->delete()){
            QuestionCategory::deleteAll("id = $id");//删除对应的用户目录权限
            $this->redirect('/content/service/question-category');
        }else{
            echo '<script>alert("失败，请重试");history.go(-1);</script>';
            die;
        }
    }
}