<?php

/**
 * 玩家相关模块
 */
namespace app\modules\content\controllers;


use app\libs\AdminController;

class PlayerController  extends AdminController
{
    public $enableCsrfValidation = false;
    public $layout = 'content';

    public function init(){
        parent::init();
        parent::setContentId('player');
    }

}