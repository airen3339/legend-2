<?php
/**
 * 登录管理
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-6-17
 * Time: 下午2:37
 */
namespace app\modules\content\controllers;

use app\libs\AdminController;
use app\modules\content\models\Catalog;
use app\modules\content\models\Role;
use app\modules\content\models\RoleCatalog;
use yii;

class RuleController extends  AdminController {
    public $enableCsrfValidation = false;
    public $layout = 'content';

    public function init(){
        parent::init();
        parent::setContentId('rule');
    }
    public function actionIndex(){
        return $this->redirect('/content/index/index');
    }

    /**
     * @return string
     * 角色信息
     */
    public function actionRole(){
        //设置actionId
        $action = Yii::$app->controller->action->id;
        parent::setActionId($action);
        $name = Yii::$app->request->get('name');
        $createPower = Yii::$app->request->get('createPower','-99');
        $where = ' id != 1 ';
        if($name){
            $where .= " and name = '{$name}'";
        }
        if($createPower == 1){
            $where .= " and createPower = 1";
        }elseif($createPower == 2){
            $where .= " and createPower != 1";
        }
        $total = Role::find()->where($where)->count();
        $page = new yii\data\Pagination(['totalCount'=>$total,'pageSize'=>20]);
        $role = Role::find()->where($where)->asArray()->offset($page->offset)->limit($page->limit)->all();
        $role = RoleCatalog::getRoleCatalogName($role);
        return $this->render('role',['page'=>$page,'role'=>$role,'count'=>$total]);
    }
    /**
     * 角色操作 编辑
     *
     */
    public function actionRoleAdd(){
        if($_POST){
            $id = Yii::$app->request->post('id');
            if($id){
                $model = Role::findOne($id);
                $where = " and id != $id";
            }else{
                $model = new Role();
                $model->password = md5(md5('123456'));
                $model->realPass = '123456';
                $where = '';
            }
            $time = time();
            $createUser = $this->adminId;
            $name = Yii::$app->request->post('name');
            $createPower = Yii::$app->request->post('createPower',0);
            $catalogIds = Yii::$app->request->post('catalogIds');
            if(!$catalogIds){
                die('<script>alert("请选择目录权限");history.go(-1);</script>');
            }
            $hadName = Role::find()->where("name = '{$name}' $where ")->one();
            if($hadName){
                die('<script>alert("已有该角色账号，请勿重复添加");history.go(-1);</script>');
            }
            $model->name = $name;
            $model->createUser = $createUser;
            $model->createPower = $createPower;
            $model->createTime = $time;
            $res = $model->save();
            if($res){
                //记录用户目录权限
                RoleCatalog::saveRoleCatalog($model->id,$catalogIds);
                echo "<script>alert('操作成功');setTimeout(function(){location.href='role'},1000)</script>";die;
            }else{
                echo "<script>alert('操作失败，请重试');history.go(-1);</script>";die;
            }
        }else{
            $id = Yii::$app->request->get('id');
            $data = [];
            if($id){
                $role = Role::find()->where("id = $id")->asArray()->one();
                $userCate = RoleCatalog::getRoleCatalogIds($id);
                $role['catalogIds'] = $userCate;
                $data['role'] = $role;
            }
            return $this->render('role-add',$data);
        }
    }
    /**
     * 角色账号删除
     */
    public function actionRoleDelete(){
        $id = Yii::$app->request->get('id');
        $res = Role::deleteAll("id = $id");
        if($res){
            //删除账号的目录权限
            RoleCatalog::deleteAll("roleId = $id");
            echo "<script>alert('删除成功');setTimeout(function(){location.href='role'},1000)</script>";die;
        }else{
            echo "<script>alert('删除失败，请重试');history.go(-1);</script>";die;
        }
    }
    /**
     * 目录结构
     *
     */
    public function actionCatalog(){
       $action = Yii::$app->controller->action->id;
       parent::setActionId($action);
       return $this->render('catalog');
    }
    /**
     * 添加分类与其基本信息
     * @return string
     */
    public function actionCatalogAdd(){
        parent::setActionId('catalog');
        if($_POST){
            $model = new Catalog();
            $categoryData = Yii::$app->request->post('category');
            $id = Yii::$app->request->post('id');
            if(empty($categoryData['name'])){
                die('<script>alert("请添加分类名称");history.go(-1);</script>');
            }
            if(empty($categoryData['rule'])){
                die('<script>alert("请添加分类规则");history.go(-1);</script>');
            }
            $where = '';
            if($id){
                $where .= " and id != $id";
            }
            $hadName = Catalog::find()->where("name='{$categoryData['name']}' $where")->one();
            if($hadName){
                die('<script>alert("已有该分类，请勿重复添加");history.go(-1);</script>');
            }
            $hadRule = Catalog::find()->where("rule='{$categoryData['rule']}' $where")->one();
            if($hadRule){
                die('<script>alert("已有该规则，请勿重复添加");history.go(-1);</script>');
            }
            if(empty($categoryData['pid'])){
                $categoryData['pid'] = 0;
            }
            if(empty($categoryData['rank'])){
                $categoryData['rank'] = 0;
            }
            if($id){
                $re = $model->updateAll($categoryData,'id = :id',[':id' => $id]);
                $catId = $id;
            }else{
                $categoryData['createTime'] = time();
                $re = Yii::$app->db->createCommand()->insert("{{%catalog}}",$categoryData)->execute();
                $catId = Catalog::find()->where("rule='{$categoryData['rule']}'")->asArray()->one()['id'];
            }
            if($re){
                if(!$id){//添加admin账号权限
                    RoleCatalog::addToAdmin($catId);
                }
                echo '<script>alert("成功")</script>';
                $this->redirect('/content/rule/catalog');
            }else{
                echo '<script>alert("失败，请重试");history.go(-1);</script>';
                die;
            }
        } else{
            $pid = Yii::$app->request->get('pid');
            return $this->render('catalog-add',['pid' => $pid]);
        }
    }
    /**
     * 修改分类
     * @return string
     * @Obelisk
     */
    public function actionCatalogUpdate(){
        parent::setActionId('catalog');
        $id = Yii::$app->request->get('id');
        $model = new Catalog();
        $cate = $model->find()->asArray()->all();
        $result = $model->find()->where("id= $id")->asArray()->one();
        return $this->render('catalog-add',array('data'=> $result,'pid' => $result['pid'],'id' => $id,'category'=>$cate));
    }
    /**
     * 删除分类
     * @return string
     */

    public function actionCatalogDelete(){
        parent::setActionId('catalog');
        $id = Yii::$app->request->get('id');
        $model = new Catalog();
        if($model->findOne($id)->delete()){
            RoleCatalog::deleteAll("cataId = $id");//删除对应的用户目录权限
            $this->redirect('/content/rule/catalog');
        }else{
            echo '<script>alert("失败，请重试");history.go(-1);</script>';
            die;
        }
    }
    public function actionUpdatePass(){
        $adminId = $this->adminId;
        $oldPass = Yii::$app->request->post('oldPass');
        $newPass = Yii::$app->request->post('newPass');
        if($oldPass == $newPass){
            die('<script>alert("新旧密码一致");history.go(-1);</script>');
        };
        $model = Role::find()->where("id = $adminId and realPass = '{$oldPass}'")->one();
        if($model){
            $md5Pass = md5(md5($newPass));
            $model->password = $md5Pass;
            $model->realPass = $newPass;
            $res = $model->save();
            if($res){
                $data = ['code'=>1,'message'=>'修改成功'];
            }else{
                $data = ['code'=>0,'message'=>'修改失败'];
            }
        }else{
            $data = ['code'=>0,'message'=>'旧密码错误，请重试'];
        }
        die(json_encode($data));
    }

}