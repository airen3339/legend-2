<?php 
namespace app\modules\pay\models;
use yii\db\ActiveRecord;
class Recharge extends ActiveRecord {
    public $cateData;

    public static function tableName(){
            return '{{%recharge}}';
    }

    /**
     * 回调日志记录
     * type 1-支付宝   2-微信
     */
    public static function  notifyLog($datas,$type=1){
        if($type == 1){
            if($datas){
                $data = json_decode($datas,true);
                $orderNo = isset($data['orderNo'])?$data['orderNo']:'';//商户订单号
                $model = new Notify();
                $model->createTime = time();
                $model->orderNumber = $orderNo;
                $model->notify = $datas;
                $model->remark = '支付宝回调内容';
                $model->save();
            }else{
                $model = new Notify();
                $model->createTime = time();
                $model->remark = '支付宝回调内容为空';
                $model->save();
            }
        }else{
            if($datas){
                $data = (array)simplexml_load_string($datas, 'SimpleXMLElement', LIBXML_NOCDATA); //将微信返回的XML转换成数组
                $orderNo = isset($data['out_trade_no'])?$data['out_trade_no']:'';//商户订单号//验证签名
                $model = new Notify();
                $model->createTime = time();
                $model->orderNumber = $orderNo;
                $model->notify = $datas;
                $model->remark = '微信回调内容';
                $model->save();
            }else{
                $model = new Notify();
                $model->createTime = time();
                $model->remark = '微信回调内容为空';
                $model->save();
            }
        }
    }
}
