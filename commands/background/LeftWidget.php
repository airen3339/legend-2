<?php
/**
 * 后台左菜单组件
 */
    namespace app\commands\background;
    use app\modules\content\models\RoleCatalog;
    use yii\base\Widget;
    use yii;
	class LeftWidget extends Widget  {
        public $leftArr;
        /**
         * 定义函数
         * */
        public function init()
        {
            $adminId = Yii::$app->session->get('adminId');
            //获取用户权限目录
            $catalog = RoleCatalog::getRoleCatalog($adminId);
            $this->leftArr = $catalog;
        }

        /**
         * 运行覆盖程序
         * */
        public function run(){
            return $this->render('left',['leftArr' => $this->leftArr]);
        }
	}
?>