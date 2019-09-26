<?php
//公告记录

namespace app\modules\content\models;


use yii\db\ActiveRecord;

class QuestionCategory extends ActiveRecord
{

    public static  function tableName(){
        return '{{%question_category}}';
    }

    /**
     * 获取所有分类
     * @param $pid
     * @param $status
     * @param string $catId
     * @param array $data
     * @return array
     * @cy
     */
    public function getAllCate($pid,$catId="",$data=array()){

        $objData = $this->find()->where("pid=$pid ")->orderBy('rank DESC')->all();
        foreach($objData as $k => $v){
            $catId = $v['id'];
            $data[$k] = $v->attributes;
            $str = "";
            $str .= ' <a onclick="checkDelete('.$v->id.')" class="categoryDelete" href="javascript:;">删除</a> ';
            $str .=' <a href="'.'/content/service/category-update?id='.$v->id.'">修改</a> ';
            if($v['pid'] ==0){
                $str .=' <a href="'.'/content/service/category-add?pid='.$v->id.'" >添加子分类</a> ';
            }
            $data[$k]['action'] = $str;
            $data[$k]['rank'] = '<input type="text" value="'.$v->rank.'" id="index'.$v->id.'"  style="width:30px;position:relative;margin-top:4px;" onkeyup="setRank('.$v->id.',this)"/>';
            $data[$k]['createTime'] = date('Y-m-d H:i');
        }
        foreach($data as $k => $v){
            $childData = $this->getAllCate($v['id'],$catId);
            if(count($childData) > 0){
                $data[$k]['children'] = $childData ;
            }

        }
        return $data;
    }

    /**
     * 获取树形菜单
     * @param $pid
     * @param $id  选中的分类Id
     * @param  $type 是否递归查询
     * @param array $data
     * @return array
     * @Obelisk
     */
    public function getTree($pid){
        $data = \Yii::$app->db->createCommand('select id,name as text from {{%question_category}} where   pid='.$pid)->queryAll();
        return $data;
    }
}