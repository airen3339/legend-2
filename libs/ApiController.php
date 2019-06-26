<?php
/**
 * 托福接口基础类
 * by Obelisk
 */
	namespace app\libs;
    use app\modules\cn\models\User;
    use yii;
    use yii\web\Controller;
	class ApiController extends Controller {
        public function init() {
            $uid = Yii::$app->session->get('uid');
            $action = Yii::$app->request->getUrl();
            $a = strrpos($action,'/');
            $action = substr($action,$a+1);
            if($uid){
                if($action != 'pk-matching'){
                    User::updateAll(['pk' => 0,'pkTime' => ''],"uid=$uid");
                }
            }
        }
	}
?>