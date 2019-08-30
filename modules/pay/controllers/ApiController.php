<?php


namespace app\modules\pay\controllers;


use app\libs\Methods;
use yii\web\Controller;

header('Access-Control-Allow-Origin:*');

class ApiController extends Controller
{
    /**
     * 支付宝
     * 支付数据获取
     * 客户端请求
     * 请求参数
     * amount 金额
     * productName 商品名称
     * detail  支付详情数据
     * 支付逻辑  客户端发送订单数据  ==》 PHP接受数据 ==》将订单数据发送给服务端 ==》调用对应的支付接口获取支付二维码 ==》将二维码返给客户端
     * 支付成功  回调地址中 验证支付结果  ==》通知服务端
     */
    public function actionAlipayOrder(){
        $request = \Yii::$app->request;
        $productName = $request->post('productName','ceshi2');
        $amount = $request->post('amount',1);
        $detail = $request->post('detail','');
        $time = time();
        $dateTime = date('YmdHis',$time);
        $date = date('Y-m-d H:i:s',$time);
        $orderNumber = 'YCJ'.time();
        $province = $request->post('province',350000);
        $city = $request->post('city',350100);
        $area = $request->post('area',350102);
        //通知服务器
        self::dataToServer($orderNumber,$productName,$amount,1,$date,$detail);
        $return = self::AliOrder($orderNumber,$productName,$amount,$dateTime,$province,$city,$area);
        die(json_encode($return));
    }

    /**
     * 清逸支付
     */
    public function actionQingYiOrder(){
        $request = \Yii::$app->request;
        $productName = $request->post('productName','ceshi2');
        $amount = $request->post('amount',1);
        $detail = $request->post('detail','');
        $time = time();
        $date = date('Y-m-d H:i:s',$time);
        $orderNumber = 'YCJ'.time();
        $pay_type = $request->post('pay_type','');//支付类型
        $ip_add = $request->post('ip_add','');//客户端ip
        //通知服务器
        self::dataToServer($orderNumber,$productName,$amount,2,$date,$detail);
        $return = self::QingYiOrder($orderNumber,$productName,$amount,$time,$pay_type,$ip_add);
        die(json_encode($return));
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
    public static  function AliOrder($orderNumber,$productName,$amount,$time,$province,$city,$area){
        $appid = '982280b3587d4133912a8e9e47dc8f3b';
        $key = 'c43eaf9e8e284fae94bc245326473d3e';
        $dateTime = $time;
        $payType = 'SCANPAY_ALIPAY';
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
        return $data;
    }

    /**
     * 清逸支付
     * @param $orderNumber  订单号
     * @param $productName   商品名称
     * @param $amount       支付金额
     * @param $time         下单时间  格式时间戳
     */
    public static function QingYiOrder($orderNumber,$productName,$amount,$time,$pay_type,$ip_add){
        $mch_number = '';//商户编码
        $appname = '';//支付的app
        $key = '';//商户密钥
        $jump_url = '';//客户端通知地址
        $asyn_url = '';//异步通知地址 回调地址
        $goodsname = $productName;//商品名称
        $totle_amount = $amount*100;
        //获取签名
        $sign_info = self::signQingYi($mch_number,$orderNumber,$pay_type,$ip_add,$key);
        $url = 'http://121.14.17.174:61869/Sys/CodePool/gmsg1869/pay.aspx';
        $params = 'mch_number='.$mch_number.'&pay_type='.$pay_type.'&totle_amount='.$totle_amount.'&this_date='.$time.'&order_sn'.$orderNumber.'&jump_url='.$jump_url.'&asyn_url='.$asyn_url.'&ip_add='.$ip_add.'&appname='.$appname.'&goodsname='.$goodsname.'&sign_info='.$sign_info;
        $api = $url.'?'.$params;
        $result = file_get_contents($api);
        $result = json_decode($result,'true');
        if($result['resultCode'] == 200){
            $payUrl = $result['qrCode'];
            $qrUrl = $result['qrUrl'];
            $data =['code'=>1,'payUrl'=>$payUrl,'qrUrl'=>$qrUrl,'msg'=>'支付请求成功'];
        }else{
            $data = ['code'=>0,'msg'=>$result['resultMsg']];
        }
        return $data;
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

    /**
     * 支付宝回调
     * 支付结果通知处理
     * 支付宝
     * POST方式
     */
    public function actionAlipayNotify(){
        $data = $_POST['data'];
        $resultcode = $data['resultcode'];//支付状态
        $resultmessage = $data['resultmessage'];//支付信息
        $orderNo = $data['orderNo'];//商户订单号
        $payTrxNo = $data['payTrxNo'];//平台流水号
        $amount = $data['amount'];//支付金额 单位为分
        $paySign = $data['paySign'];//签名信息
        //验证签名
        $result = self::checkAlipaySign($paySign,$orderNo);
        if($result){
            if($resultcode == '0000'){
                //通知服务器处理后续
                $postData = ['orderNumber'=>$orderNo,'amount'=>($amount/100),'success'=>1,'platformNumber'=>$payTrxNo];
                $url = '';
                Methods::post($url,$postData);
                echo 'SUCCESS';
            }else{
                echo 'fail';
            }
        }else{
            echo 'fail,sign error';
        }
        die;
    }
    /**
     * 清逸支付回调
     * 支付结果通知处理
     * 清逸
     * GET方式
     */
    public function actionQingYiNotify(){
        $order_sn = $_GET['order_sn'];//商户订单号
        $platform_sn = $_GET['platform_sn'];//清逸系统订单号
        $totle_amount = $_GET['totle_amount'];//支付金额 单位分
        $status = $_GET['status'];//支付状态 1-成功 其他失败
        $sign_info = $_GET['sign_info'];//签名 签名方式：MD5(order_sn+platform_sn+商户密钥) 小写
        //验证签名
        $result = self::checkQingYiSign($sign_info,$order_sn,$platform_sn);
        if($result){
            if($status ==1){//支付成功
                //通知服务端 处理后续逻辑
                $postData = ['orderNumber'=>$order_sn,'amount'=>($totle_amount/100),'success'=>1,'platformNumber'=>$platform_sn];
                $url = '';
                Methods::post($url,$postData);
                echo 'ok';
            }else{
//                $postData = ['order_sn'=>$order_sn,'success'=>0];
                echo 'fail';
            }
        }else{
            echo 'fail,sign error';
        }
    }
    /**
     * 清逸
     * 签名验证
     * 回调地址
     * 签名方式：MD5(order_sn+platform_sn+商户密钥) 小写
     */
    public static function checkQingYiSign($sign_info,$order_sn,$platform_sn){
        $key = '';//商户密钥
        $str = $order_sn.$platform_sn.$key;
        $sign = md5($str);
        $sign = strtolower($sign);
        if($sign == $sign_info){
            return true;
        }else{
            return false;
        }
    }
    /**
     * 支付宝
     * 签名验证
     * 回调地址
     * 签名方式
     * 第一步，设所有发送或者接收到的数据为集合M，将集合M内非空参数值的参数按照参数名ASCII码从小到大排序（字典序），使用URL键值对的格式（即key1=value1&key2=value2…）拼接成字符串。
    第二步，在stringA最后拼接上应用key得到stringSignTemp字符串，并对stringSignTemp进行MD5运算，再将得到的字符串所有字符转换为小写，得到sign值signValue。
     */
    public static function checkAlipaySign($paySign,$orderNumber){
        //查询数据库数据生成签名进行验证
        return true;
    }
}
