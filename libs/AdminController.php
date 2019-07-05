<?php
/**
 * 后台接口基础类
 * by Obelisk
 */
	namespace app\libs;
    use app\modules\content\models\Catalog;
    use yii;
    use yii\web\Controller;
	class AdminController extends Controller {
	    public $adminId;
	    public $createPower;//编辑角色权限能力 1-有 0-无
        public function init() {
            $adminId = Yii::$app->session->get('adminId');
            if($adminId){
                $this->adminId = $adminId;
                $this->createPower = Yii::$app->session->get('createPower');
            }else{
                die('<script>alert("您没有此访问权限");history.go(-1);</script>');
            }
        }
        //设置actionId
        public function setActionId($action){
            $actionId = Catalog::find()->where("rule='{$action}'")->asArray()->one()['id'];
            Yii::$app->session->set('actionId',$actionId);
        }
        //设置contentId
        public function setContentId($action){
            $actionId = Catalog::find()->where("rule='{$action}'")->asArray()->one()['id'];
            Yii::$app->session->set('contentId',$actionId);
        }
        //获取账号下默认的目录
        public function getDefaultCatalog($control){
            $adminId = $this->adminId;
            $controlId = Catalog::find()->where("rule = '{$control}'")->asArray()->one()['id'];
            $cataIds = Catalog::find()->select('group_concat(id) as ids')->where("pid = $controlId")->asArray()->one()['ids'];
            $sql = "select c.id,c.rule from {{%role_catalog}} rc left join {{%catalog}} c on c.id = rc.cataId where c.id in ($cataIds) and rc.roleId = $adminId order by rc.id asc";
            $cata = Yii::$app->db->createCommand($sql)->queryOne();
            $action = $cata['rule'];
            if(!$action){
                die("<script>alert('您没有操作权限');history.go(-1);</script>");
            }
            $actionId = $cata['id'];
            Yii::$app->session->set('actionId',$actionId);
            return $this->redirect("/content/$control/$action");
        }
	}
?>