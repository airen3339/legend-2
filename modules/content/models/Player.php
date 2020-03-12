<?php


namespace app\modules\content\models;


use app\libs\Methods;
use app\modules\pay\models\Recharge;
use yii\db\ActiveRecord;

class Player extends ActiveRecord
{
    public static function getDb(){
        return \Yii::$app->db2;
    }
    public static  function tableName(){
        return '{{%player}}';
    }
    /**
     * 获取今日登录账号数
     */
    public static function getTodayLogin($dateTime,$end,$where){
        $sql = "select p.UserID from `user` u inner join player p on p.UserID = u.UserID where $where and LastLogin between $dateTime and $end";
        $data = \Yii::$app->db2->createCommand($sql)->queryAll();
        $total = count($data);
        return $total;
    }
    /**
     * 单据Excel数据导出
     */
    public static function roleDownloadExcel($sql){
        $data = \Yii::$app->db2->createCommand($sql)->queryAll();
        foreach($data as $k => $v){
            $data[$k]['LastLogin'] = date('Y-m-d H:i:s',$v['LastLogin']);
            $data[$k]['RoleID'] = $v['RoleID'].' ';
        }
        $th_content = [
            ['column'=>'A','title'=>'角色ID','key'=>'RoleID'],
            ['column'=>'B','title'=>'账号','key'=>'UserID'],
            ['column'=>'C','title'=>'区服','key'=>'WorldID'],
            ['column'=>'D','title'=>'角色名','key'=>'Name'],
            ['column'=>'E','title'=>'渠道','key'=>'PackageFlag'],
            ['column'=>'F','title'=>'注册时间','key'=>'CreateDate'],
            ['column'=>'G','title'=>'最后登录时间','key'=>'LastLogin'],
        ];
        Methods::excelDownload($data,'角色信息',$th_content);
        die;
    }
    /**
     * 单据Excel数据导出
     */
    public static function orderDownloadExcel($where){
        $data = ChargeMoney::find()->where($where)->orderBy('createTime desc')->asArray()->all();
        foreach($data as $k => $v){
            $sql = "select u.Username,u.PackageFlag,p.Name,p.UserID from `user` u inner join player p on p.UserID = u.UserID inner join chargemoney c on c.roleID = p.RoleID where c.roleID = '{$v['roleID']}' ";
            $da = \Yii::$app->db2->createCommand($sql)->queryOne();
            $data[$k]['username'] = $da['Username'];
            $data[$k]['packageFlag'] = $da['PackageFlag'];
            $data[$k]['roleName'] = $da['Name'];
            $data[$k]['userId'] = $da['UserID'].' ';
            $data[$k]['roleID'] = $v['roleID'].' ';
            //订单是否共存
            $order = Recharge::find()->where("orderNumber = '{$v['orderid']}'")->asArray()->one();
            if($order){
                $data[$k]['merOrder'] = $order['merOrder'];
            }else{
                $data[$k]['merOrder'] = '';
            }
            //支付类型
            $orderTypeStr = '';
            $orderType = $v['type'];
            if($orderType==1){
                $orderTypeStr = '支付宝';
            }elseif($orderType == 2){
                $orderTypeStr = '微信';
            }
            $data[$k]['typeStr'] = $orderTypeStr;
        }
        foreach($data as $k => $v){
            $data[$k]['finishTimeStr'] = $v['finishTime']>0?'已完成':'未完成';
        }
        $th_content = [
            ['column'=>'A','title'=>'角色ID','key'=>'roleID'],
            ['column'=>'B','title'=>'区服','key'=>'worldID'],
            ['column'=>'C','title'=>'角色名','key'=>'roleName'],
            ['column'=>'D','title'=>'账号','key'=>'userId'],
            ['column'=>'E','title'=>'渠道','key'=>'packageFlag'],
            ['column'=>'F','title'=>'订单号','key'=>'orderid'],
            ['column'=>'G','title'=>'平台订单号','key'=>'merOrder'],
            ['column'=>'H','title'=>'金额','key'=>'chargenum'],
            ['column'=>'I','title'=>'充值渠道','key'=>'typeStr'],
            ['column'=>'J','title'=>'充值时间','key'=>'createTime'],
            ['column'=>'K','title'=>'领取时间','key'=>'finishTime'],
            ['column'=>'L','title'=>'状态','key'=>'finishTimeStr'],
        ];
        Methods::excelDownload($data,'订单查询',$th_content);
        die;
    }
}