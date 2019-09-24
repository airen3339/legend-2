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
use app\modules\content\models\Role;

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
        $service = Role::find()->where("service = 1")->asArray()->all();
        return $this->render('service-status',['service'=>$service]);
    }

}