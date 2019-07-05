<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/3
 * Time: 11:12
 */
namespace app\modules\content;

use yii\base\Module;

class ContentModule extends Module {
    public  $controllerNamespace = 'app\modules\content\controllers';
    public function init(){
        parent::init();
    }
}