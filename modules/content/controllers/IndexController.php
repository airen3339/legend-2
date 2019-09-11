<?php


namespace app\modules\content\controllers;


use app\libs\AdminController;

class IndexController  extends AdminController
{
    public $layout = 'content';
    public function actionIndex(){
        \Yii::$app->session->set('contentId',0);
        return $this->render('index');
    }

}