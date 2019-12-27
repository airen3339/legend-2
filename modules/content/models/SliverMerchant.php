<?php
//银商信息表

namespace app\modules\content\models;


use yii\data\Pagination;
use yii\db\ActiveRecord;

class SliverMerchant extends ActiveRecord
{
    public static function getDb()
    {
        return \Yii::$app->db2;
    }

    public static  function tableName(){
        return '{{%yin_shang_user}}';
    }
    /**
     * 获取对应的银商数据信息
     */
    public static function getSliverMerchantMsg($where,$page=1,$pageSize = 20){
        $sql = " select * from {{%yin_shang_user}} where $where order by enterWorldID desc";
        $total = \Yii::$app->db2->createCommand($sql)->queryAll();
        $count = count($total);
        $pages = new Pagination(['totalCount'=>$count,'pageSize'=>$pageSize]);
        $limit = " limit ".($page-1)*$pageSize.",".$pageSize;
        $sql .=  $limit;
        $data = \Yii::$app->db2->createCommand($sql)->queryAll();
        return ['data'=>$data,'count'=>$count,'page'=>$pages];
    }
}