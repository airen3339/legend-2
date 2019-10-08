<?php
//公告记录

namespace app\modules\content\models;


use app\libs\Methods;
use yii\db\ActiveRecord;

class BillMessage extends ActiveRecord
{

    public static  function tableName(){
        return '{{%bill_message}}';
    }

    /**
     * @return array
     * 单据类型
     */
    public  static function getBillTypes(){
        $billTypes = [
            ['id'=>1,'name'=>'普通单'],
            ['id'=>2,'name'=>'技术单'],
            ['id'=>3,'name'=>'投诉单'],
            ['id'=>4,'name'=>'预警单'],
        ];
        return $billTypes;
    }
    /**
     * @return array
     * 单据来源
     */
    public  static function getBillSources(){
        $billSources = [
            ['id'=>1,'name'=>'在线'],
            ['id'=>2,'name'=>'热线'],
        ];
        return $billSources;
    }
    /**
     * @return array
     * 游戏所属
     */
    public  static function getBillGames(){
        $billGames = [
            ['id'=>1,'name'=>'传奇']
        ];
        return $billGames;
    }
    /**
     * @return array
     * VIP等级
     */
    public  static function getvipLevels(){
        $vipLevels = [];
        return $vipLevels;
    }
    /**
     * 单据Excel数据导出
     */
    public static function downloadExcel($where){
        $bill = BillMessage::find()->where($where)->asArray()->all();
        $billTypes = self::getBillTypes();
        $billSources = self::getBillSources();
        $billGames = self::getBillGames();
        foreach($bill as $k => $v){
            //单据类型
            foreach($billTypes as $p => $t){
                if($t['id'] == $v['billType']){
                    $bill[$k]['billType'] = $t['name'];
                    break;
                }
            }
            //单据来源
            foreach($billSources as $p => $t){
                if($t['id'] == $v['billSource']){
                    $bill[$k]['billSource'] = $t['name'];
                    break;
                }
            }
            //游戏所属
            foreach($billGames as $p => $t){
                if($t['id'] == $v['billGame']){
                    $bill[$k]['billGame'] = $t['name'];
                    break;
                }
            }
            //一级分类
            $bill[$k]['quesParent'] = QuestionCategory::find()->where("id = {$v['quesParent']}")->asArray()->one()['name'];
            //二级分类
            $bill[$k]['quesChild'] = QuestionCategory::find()->where("id = {$v['quesChild']}")->asArray()->one()['name'];
            $bill[$k]['createTime'] = date('Y-m-d H:i',$v['createTime']);
            $bill[$k]['updateTime'] = date('Y-m-d H:i',$v['updateTime']);
            $bill[$k]['createName'] = Role::find()->where("id = {$v['creator']}")->asArray()->one()['name'];
        }
        $th_content = [
            ['column'=>'A','title'=>'游戏账号','key'=>'account'],
            ['column'=>'B','title'=>'游戏昵称','key'=>'gameName'],
            ['column'=>'C','title'=>'游戏ID','key'=>'gameId'],
            ['column'=>'D','title'=>'单据类型','key'=>'billType'],
            ['column'=>'E','title'=>'单据来源','key'=>'billSource'],
            ['column'=>'F','title'=>'游戏所属','key'=>'billGame'],
            ['column'=>'G','title'=>'一级分类','key'=>'quesParent'],
            ['column'=>'H','title'=>'二级分类','key'=>'quesChild'],
            ['column'=>'I','title'=>'游戏区服','key'=>'gameServer'],
            ['column'=>'J','title'=>'下载渠道','key'=>'download'],
            ['column'=>'K','title'=>'设备型号','key'=>'device'],
            ['column'=>'L','title'=>'系统版本','key'=>'version'],
            ['column'=>'M','title'=>'联系电话','key'=>'phone'],
            ['column'=>'N','title'=>'联系QQ','key'=>'qq'],
            ['column'=>'O','title'=>'联系邮箱','key'=>'email'],
            ['column'=>'P','title'=>'详细信息','key'=>'detail'],
            ['column'=>'Q','title'=>'处理结果','key'=>'result'],
            ['column'=>'R','title'=>'操作者','key'=>'createName'],
            ['column'=>'S','title'=>'创建时间','key'=>'createTime'],
            ['column'=>'T','title'=>'修改时间','key'=>'updateTime'],
        ];
        Methods::excelDownload($bill,'单据数据下载',$th_content);
        die;
    }
}