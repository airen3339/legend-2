<?php


namespace app\modules\content\models;


use yii\db\ActiveRecord;

class RoleCatalog extends ActiveRecord
{
    public static function tableName(){
        return '{{%role_catalog}}';
    }
    /**
     * 添加admin账号权限
     *
     */
    public static function addToAdmin($cataId){
        $model = new self();
        $model->roleId = 1;
        $model->cataId = $cataId;
        $model->createTime = time();
        $model->save();
    }
    /**
     * 获取账号下的操作目录id
     * cy
     */
    public static function getRoleCatalogIds($roleId){
        $catalogIds = RoleCatalog::find()->select('group_concat(cataId) as ids')->asArray()->where("roleId = $roleId")->one()['ids'];
        return $catalogIds;
    }
    /**
     * 获取账号下的操作目录信息
     * cy
     */
    public static function getRoleCatalogName($roles){
        foreach($roles as $k => $v){
            $catalogIds = RoleCatalog::find()->select('group_concat(cataId) as ids')->asArray()->where("roleId = {$v['id']}")->one()['ids'];
            if($catalogIds){
                $catalog = Catalog::find()->select('group_concat(name) as cata')->asArray()->where("id in ({$catalogIds})")->one()['cata'];
            }else{
                $catalog = '';
            }
            $roles[$k]['catalog'] = $catalog;
        }
        return $roles;
    }
    /**
     * 记录用户账号目录
     *
     */
    public static function saveRoleCatalog($roleId,$catalogIds){
        //完善目录权限
        $cataArr = self::perfectCatalog($catalogIds);
        if($roleId && $catalogIds){
            self::deleteAll("roleId = $roleId");
            $time = time();
            foreach($cataArr as $k => $v){
                $model = new self();
                $model->roleId = $roleId;
                $model->cataId = $v;
                $model->createTime  = $time;
                $model->save();
            }
        }
        return true;
    }
    /**
     * 完善用户目录权限信息
     * 一级目录完善添加
     * 二级目录情况下
     */
    public static function perfectCatalog($catalogIds){
        if(empty($catalogIds) || !is_array($catalogIds)){
            return [];
        }
        foreach($catalogIds as $k => $v){
            $pid = Catalog::find()->where("id = $v")->asArray()->one()['pid'];
            if($pid && !in_array($pid,$catalogIds)){//父级目录存在并且不再勾选范围内
                $catalogIds[] = $pid;
            }
        }
        return $catalogIds;
    }
    /**
     * 获取用户权限目录
     * 二维
     * 两级目录
     */
    public static function getRoleCatalog($roleId){
        $sql = " select c.id,c.name,c.rule from {{%role_catalog}} rc left join {{%catalog}} c on c.id = rc.cataId where rc.roleId = $roleId and c.pid = 0 order by c.rank desc";
        $catalog = \Yii::$app->db->createCommand($sql)->queryAll();
        foreach($catalog as $k => $v){
            $sql = " select c.id,c.name,c.rule from {{%role_catalog}} rc left join {{%catalog}} c on c.id = rc.cataId where rc.roleId = $roleId and c.pid = {$v['id']} order by c.rank desc";
            $child = \Yii::$app->db->createCommand($sql)->queryAll();
            $catalog[$k]['child'] = $child;
        }
        return $catalog;
    }
}