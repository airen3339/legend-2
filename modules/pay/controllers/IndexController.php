<?php
/**
 * 首页
 * Created by PhpStorm.
 * User: obelisk
 */
namespace app\modules\pay\controllers;

use yii;

class IndexController extends yii\web\Controller {
    public $enableCsrfValidation = false;

    /**
     * 排课首页
     * cy
     */
    public function actionIndex(){
        return $this->renderPartial("login");
    }
    /**
     * 退出
     * cy
     */
    public function actionLoginOut(){
        Yii::$app->session->removeAll();
        return $this->redirect('/cn/index/index');
    }
}