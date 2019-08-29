<?php


namespace app\modules\pay\controllers;


use app\libs\Methods;
use yii\web\Controller;

header('Access-Control-Allow-Origin:*');

class ApiController extends Controller
{
    /**
     * 支付数据获取
     * 客户端请求
     * 请求参数
     * amount 金额
     * productName 商品名称
     * detail  支付详情数据
     * type  支付类型  1-支付宝 2-清逸支付
     * 支付逻辑  客户端发送订单数据  ==》 PHP接受数据 ==》将订单数据发送给服务端 ==》调用对应的支付接口获取支付二维码 ==》将二维码返给客户端
     * 支付成功  回调地址中 验证支付结果  ==》通知服务端
     */
    public function actionOrderDataGet(){
        $request = \Yii::$app->request;
        $productName = $request->post('productName','ceshi2');
        $amount = $request->post('amount',1);
        $type = $request->post('type',1);
        $detail = $request->post('detail','');
        $time = time();
        $dateTime = date('YmdHis',$time);
        $date = date('Y-m-d H:i:s',$time);
        $orderNumber = 'YCJ'.time();
        //通知服务器
//        self::dataToServer($orderNumber,$productName,$amount,$type,$date,$detail);
        if($type == 1){
            $return = self::AliOrder($orderNumber,$productName,$amount,$dateTime);
        }elseif($type == 2){
            $return = self::QingYiOrder($orderNumber,$productName,$amount,$time);
        }else{
            $return = '';
        }
    }
    /**
     * 通知服务端
     * 支付请求之前
     * 数据记录
     * @param $orderNumber  订单号
     * @param $productName  商品名称
     * @param $amount  支付金额
     * @param $type     支付类型  1-支付宝 2-清逸
     * @param $time     订单时间
     * @param $detail   订单详情  其他数据
     */
    public static function dataToServer($orderNumber,$productName,$amount,$type,$time,$detail){
        $postData = [];
        $postData['orderNumber'] = $orderNumber;
        $postData['productName'] = $productName;
        $postData['amount'] = $amount;
        $postData['payType'] = $type==1?'支付宝':'清逸支付';
        $postData['orderTime'] = $time;
        $postData['detail'] = $detail;
        $url = '';//服务器接受地址
        Methods::post($url,$postData);
        return true;
    }
    /**
     * 支付宝支付请求发起
     * 支付扫码
     * H5
     */
    public static  function AliOrder($orderNumber,$productName,$amount,$time){
        $appid = '982280b3587d4133912a8e9e47dc8f3b';
        $key = 'c43eaf9e8e284fae94bc245326473d3e';
        $dateTime = $time;
        $payType = 'SCANPAY_ALIPAY';
        $province = 350000;
        $city = 350100;
        $area = 350102;
        $asynNotifyUrl = 'https://www.baidu.com';//商户异步通知地址
        $returnUrl = '';//商户前端返回页面地址
        $amount = $amount*100;//金额处理 单位为分
        //生成签名
        $postData = ['amount'=>$amount,'appid'=>$appid,'area'=>$area,'asynNotifyUrl'=>$asynNotifyUrl,'city'=>$city,'dateTime'=>$dateTime,'orderNo'=>$orderNumber,'payType'=>$payType,'productName'=>$productName,'province'=>$province,'returnUrl'=>$returnUrl];
        $signArr = $postData;
        ksort($signArr);
        $sign = self::signAlipay($signArr,$key);
        //请求支付
        $postData['sign'] = $sign;
        $url = 'https://pay.quanyuwenlv.com/ts/scanpay/pay';
        $return = Methods::post($url,$postData);
        $return = json_decode($return,true);
        if($return['code'] == 'success'){
            $returnData = json_decode($return['data'],true);
            $payUrl = $returnData['payUrl'];
            $data = ['code'=>1,'payUrl'=>$payUrl,'msg'=>'支付请求成功'];
        }else{
            $data = ['code'=>0,'msg'=>$return['message']];
        }
        die(json_encode($data));
    }
    /**
     * 清逸支付
     *
     * @param $orderNumber  订单号
     * @param $productName   商品名称
     * @param $amount       支付金额
     * @param $time         下单时间  格式时间戳
     */
    public static function QingYiOrder($orderNumber,$productName,$amount,$time){
        $mch_number = '';//商户编码
        $pay_type = '';//支付类型
        $total_amount = $amount*100;
        $jump_url = '';//客户端通知地址
        $asyn_url = '';//异步通知地址 回调地址
        $ip_add = '';//客户端ip
        $appname = '';//支付的app
        $goodsname = $productName;//商品名称
        $key = '';//商户密钥
        //获取签名
        $sign_info = self::signQingYi($mch_number,$orderNumber,$pay_type,$ip_add,$key);
        $url = 'http://121.14.17.174:61869/Sys/CodePool/gmsg1869/pay.aspx';
        $params = 'mch_number='.$mch_number.'&pay_type='.$pay_type.'&totle_amount='.$total_amount.'&this_date='.$time.'&order_sn'.$orderNumber.'&jump_url='.$jump_url.'&asyn_url='.$asyn_url.'&ip_add='.$ip_add.'&appname='.$appname.'&goodsname='.$goodsname.'&sign_info='.$sign_info;
        $api = $url.'?'.$params;
        $result = file_get_contents($url);
        $result = json_decode($result,'true');
        if($result['resultCode'] == 200){
            $payUrl = $result['qrCode'];
            $qrUrl = $result['qrUrl'];
            $data =['code'=>1,'payUrl'=>$payUrl,'qrUrl'=>$qrUrl,'msg'=>'支付请求成功'];
        }else{
            $data = ['code'=>0,'msg'=>$result['resultMsg']];
        }
        die(json_encode($data));
    }

    /**
     * 支付宝签名
     * 签名生成
     * @param $signArr
     * md5算法加密
     */
    public static function signAlipay($signArr,$key){
        $signStr = '';
        foreach($signArr as $k => $v){
            if($v != ''){
                $signStr .= $k.'='.$v.'&';
            }
        }
        $signStr.='key='.$key;
        $signStr = md5($signStr);
        $signStr = strtolower($signStr);
        return $signStr;
    }

    /**
     * 清逸支付签名
     * 签名生成
     * md5加密 转小写
     * @param $mch_number  商户编码
     * @param $order_sn  订单号
     * @param $pay_type 支付类型代码
     * @param $ip_add 客户端真实IP
     * @param $key  商户密钥
     */
    public static function signQingYi($mch_number,$order_sn,$pay_type,$ip_add,$key){
        $str = $mch_number.$order_sn.$pay_type.$ip_add.$key;
        $signStr = md5($str);
        $signStr = strtolower($signStr);
        return $signStr;
    }
}
