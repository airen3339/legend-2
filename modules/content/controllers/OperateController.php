<?php

/**
 * 运营数据模块
 */
namespace app\modules\content\controllers;


use app\libs\AdminController;
use Yii;

class OperateController  extends AdminController
{
    public $enableCsrfValidation = false;
    public $layout = 'content';

    public function init(){
        parent::init();
        parent::setContentId('operate');
    }
    /**
     * 数据查询
     */
    public function actionDataQuery(){
        $beginTime = Yii::$app->request->get('beginTime');
        $endTime = Yii::$app->request->post('endTime');
        $service = Yii::$app->request->get('service');
        $channel = Yii::$app->request->get('channel');
        $where = " 1=1 ";
        if($beginTime){
            $begin = strtotime($beginTime);
            $where .=  " and createTime >= $begin";
        }
        if($endTime){
            $end = strtotime($endTime) + 86399;
            $where .= " and createTime <= $end";
        }
        if($service){
            $where .= " and service = '{$service}'";
        }
        if($channel){
            $where .= " and channel = '{$channel}'";
        }
        $data = [];
        return $this->render('data-query',['data'=>$data]);
    }

}