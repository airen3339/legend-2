<?php

/**
 * 礼包管理模块
 */
namespace app\modules\content\controllers;


use app\libs\AdminController;

class GiftController  extends AdminController
{
    public $enableCsrfValidation = false;
    public $layout = 'content';

    public function init(){
        parent::init();
        parent::setContentId('gift');
    }

}