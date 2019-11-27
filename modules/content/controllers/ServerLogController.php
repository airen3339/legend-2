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
use app\modules\content\models\GameError;
use yii;
use yii\web\Controller;

class ServerLogController extends  AdminController {
    public $enableCsrfValidation = false;
    public $layout = 'content';

    public function init(){
        parent::init();
        parent::setContentId('server-log');
    }
    public function actionIndex(){
        return $this->redirect('/content/index/index');
    }
    /**
     * 游戏报错日志
     */
    public function actionErrorLog(){
        $action = Yii::$app->controller->action->id;
        parent::setActionId($action);
        $count = GameError::find()->count();
        $page = new yii\data\Pagination(['totalCount'=>$count,'pageSize'=>10]);
        $data = GameError::find()->orderBy('id desc')->offset($page->offset)->limit($page->limit)->asArray()->all();
        return $this->render('error-log',['count'=>$count,'page'=>$page,'data'=>$data]);
    }
    /**
     * 报错日志详情
     */
    public function actionErrorDetail(){
        $id = Yii::$app->request->get('id');
        $data = GameError::find()->where("id = $id")->asArray()->one();
        return $this->render('error-detail',['data'=>$data]);
    }


}