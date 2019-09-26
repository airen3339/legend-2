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
        return $this->render('bill-message',['service'=>[]]);
    }
    /**
     * 单据信息编辑
     * cy
     */
    public function actionBillMessageAdd(){
        if($_POST){

        }else{
            $id = \Yii::$app->request->get('id');
            if($id){
                $data = [];
            }else{
                $data = [];
            }
            //单据类型
            $billTypes = [
                ['id'=>1,'name'=>'普通单'],
                ['id'=>2,'name'=>'技术单'],
                ['id'=>3,'name'=>'投诉单'],
                ['id'=>4,'name'=>'预警单'],
            ];
            //单据来源
            $billSources = [
                ['id'=>1,'name'=>'在线'],
                ['id'=>2,'name'=>'热线'],
            ];
            //游戏归属
            $billGames = [
                ['id'=>1,'name'=>'传奇']
            ];
            //问题一级分类
            $billQuesParent = [];
            //问题二级分级
            $billQuesChild = [];
            //VIP等级
            $vipLevels = [];
            $data = ['billTypes'=>$billTypes,'billSources'=>$billSources,'billGames'=>$billGames,'billQuesParent'=>$billQuesParent,'billQuesChild'=>$billQuesChild,'vipLevels'=>$vipLevels];
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