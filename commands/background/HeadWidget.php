<?php
/**
 * 主导航菜单组件
 */
    namespace app\commands\background;
    use app\modules\content\models\Role;
    use yii\base\Widget;
    use yii;
	class HeadWidget extends Widget  {
        public $headArr;
        /**
         * 定义函数
         * */
        public function init()
        {
            $headArr = Yii::$app->session->get('headArr');
            if(!$headArr){
                $adminId = Yii::$app->session->get('adminId');
                $createPower = Yii::$app->session->get('createPower');
                if($createPower == 1){
                    $head = 1;//  1-加上用户权限模块  0-排除用户权限模块
                }else{
                    $head = 0;
                }
                //头部导航
                $headArr = Role::getHeadArr($adminId,$head);
                Yii::$app->session->set('headArr',$headArr);
            }
            $contentId = Yii::$app->session->get('contentId');//头部导航所在模块id
//            if(!$contentId){
//                $contentId = isset($headArr[0]['id'])?$headArr[0]['id']:-1;
//                Yii::$app->session->set('contentId',$contentId);
//            }
            $this->headArr = $headArr;
        }

        /**
         * 运行覆盖程序
         * */
        public function run(){
            return $this->render('head',['headArr' => $this->headArr]);
        }
	}
?>