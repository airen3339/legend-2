<?php


namespace app\modules\pay\controllers;


use app\libs\Methods;
use app\modules\cn\models\MessageLook;
use app\modules\pay\models\Recharge;
use yii\web\Controller;

header('Access-Control-Allow-Origin:*');

class ApiController extends Controller
{
    public $enableCsrfValidation = false;
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
     * code返回类型 1 成功 -1 支付金额不能为零 -2 订单号不存在 -3  角色id不存在 -4 服务器id不存在  -5 用户名不存在 -6 支付请求错误
     */
    public function actionAlipayOrder(){
        $request = \Yii::$app->request->post();
        $poststr = json_encode($request);
        $date = date('Y-m-d');
        $logDay = 'payLog-'.$date.'.txt';
        Methods::varDumpLog($logDay,$poststr,'a');
        Methods::varDumpLog($logDay,"\n",'a');
        $request = json_decode($poststr);
        $content = get_object_vars($request);
        $key = key($content);
        $cont = json_decode($key,true);
        $productName = '元宝充值';
        $amount = $cont['amount'];
        if($amount <= 0){
            die(json_encode(['code'=>-1]));//,'msg'=>'支付金额不能为零'
        }
        $time = time();
        $dateTime = date('YmdHis',$time);
        $orderNumber = $cont['orderNumber'];
        if(!$orderNumber){
            die(json_encode(['code'=>-2]));//,'msg'=>'订单号不存在'
        }
        //签名地区参数 省 市 区
        $province = \Yii::$app->params['province'];
        $city = \Yii::$app->params['city'];
        $area = \Yii::$app->params['area'];
        $payType = 'JSAPI_ALIPAY';//支付宝
//        $payType = 'JSAPI_WEIXIN';//微信
        $roleId = $cont['roleId'];//用户角色id
        if(!$roleId){
            die(json_encode(['code'=>-3]));//,'msg'=>'角色id不存在'
        }
        $ratio = 500;//元宝比例
        $luckNum = 0;
        $extInfo = $cont['ext_info'];//其他扩展数据
        $server_id = $cont['server_id'];//服务器id
        if(!$server_id){
            die(json_encode(['code'=>-4]));//,'msg'=>'服务器id不存在'
        }
        $username = $cont['username'];
        if(!$username){
            die(json_encode(['code'=>-5]));//,'msg'=>'用户名不存在'
        }
        $sign = $cont['sign'];//验证签名字段
        //订单数据生成记录
        $model = new Recharge();
        $model->roleId = $roleId;
        $model->orderNumber = $orderNumber;
        $model->product = $productName;
        $model->money = $amount;
        $model->ratio = $ratio;
        $model->lucknum = $luckNum;
        $model->sign = $sign;
        $model->extInfo = $extInfo;
        $model->status = 0;
        $model->server_id = $server_id;
        $model->createTime = $time;
        $model->username = $username;
        $model->payType = 1;//1-支付宝 2-微信 h5
        $model->yuanbao = $ratio*$amount+$luckNum;
        $model->save();
        $return = self::AliOrder($orderNumber,$productName,$amount,$dateTime,$province,$city,$area,$model->id,$payType);
        die(json_encode($return));
    }

    /**
     * 支付宝支付请求发起
     * 支付扫码
     * H5
     */
    public static  function AliOrder($orderNumber,$productName,$amount,$time,$province,$city,$area,$orderId,$payType){
        $appid = \Yii::$app->params['alipayAppid'];
        $key = \Yii::$app->params['alipayKey'];
        $dateTime = $time;
//        $payType = 'SCANPAY_ALIPAY';
//        $payType = 'JSAPI_ALIPAY';//支付宝
//        $payType = 'JSAPI_WEIXIN';//微信
        $asynNotifyUrl = \Yii::$app->params['alipayNotify'];//商户异步通知地址
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
        $log = date('Y-m-d').'-aliPay.txt';
        Methods::varDumpLog($log,$return,'a');
        $return = json_decode($return,true);
        if($return['code'] == 'success'){
            $returnData = json_decode($return['data'],true);
            $payUrl = $returnData['payUrl'];
            $data = ['code'=>1,'payUrl'=>$payUrl];//,'msg'=>'支付请求成功'
            //记录签名
            $ip = self::getIp();
            Recharge::updateAll(['paySign'=>$sign,'ip'=>$ip],"id = $orderId");
        }else{
            $data = ['code'=>-6];//,'msg'=>$return['message'] 支付请求错误
        }
        return $data;
    }
    public static function getIP(){
        if($_SERVER['REMOTE_ADDR'])
            $ip = $_SERVER['REMOTE_ADDR'];
        else $ip = "Unknow";
        return $ip;
    }

    /**
     * 支付宝签名
     * 签名生成
     * @param $signArr
     * md5算法加密 转小写
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
     * 支付宝回调
     * 支付结果通知处理
     * 支付宝
     * POST方式
     */
    public function actionAlipayNotify(){
        $data = isset($_POST['data'])?$_POST['data']:'';
        Recharge::notifyLog($data,1);
        if(!$data){
            echo 'fail';die;
        }else{
            $data = json_decode($data,true);
        }
        $resultcode = $data['resultcode'];//支付状态
        $resultmessage = $data['resultmessage'];//支付信息
        $orderNo = $data['orderNo'];//商户订单号
        $payTrxNo = $data['payTrxNo'];//平台流水号
        $amount = $data['amount'];//支付金额 单位为分
        $paySign = $data['paySign'];//签名信息
        $appId = $data['appId'];
        //验证签名
        $result = self::checkAlipaySign($orderNo,$appId);
        if($result){
            if($resultcode == '0000'){
                $amount = $amount/100;//换成元
                $orderData = Recharge::find()->where("orderNumber = '{$orderNo}' and money = $amount")->asArray()->one();
                if($orderData['status'] != 1){//订单未完成
                    Recharge::updateAll(['status'=>1,'merOrder'=>$payTrxNo],"orderNumber='{$orderNo}'");//修改订单状态
                    //通知服务器处理后续
//                    $amount = $amount/100;//换成元
                    $postData = ['uid'=>$orderData['roleId'],'pay_money'=>$orderData['money'],'ratio'=>$orderData['ratio'],'lucknum'=>$orderData['lucknum'],'server_id'=>$orderData['server_id'],'sign'=>$orderData['sign'],'order_no'=>$orderNo,'ext_info'=>$orderData['extInfo']];
                    $url = \Yii::$app->params['gameServerUrl'];
                    Methods::post($url,$postData);
                }
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
     * 支付宝
     * 签名验证
     * 回调地址
     * 签名方式
     * 第一步，设所有发送或者接收到的数据为集合M，将集合M内非空参数值的参数按照参数名ASCII码从小到大排序（字典序），使用URL键值对的格式（即key1=value1&key2=value2…）拼接成字符串。
    第二步，在stringA最后拼接上应用key得到stringSignTemp字符串，并对stringSignTemp进行MD5运算，再将得到的字符串所有字符转换为小写，得到sign值signValue。
     */
    public static function checkAlipaySign($orderNumber,$appid){
        $key = \Yii::$app->params['alipayKey'];
        $province = \Yii::$app->params['province'];
        $city = \Yii::$app->params['city'];
        $area = \Yii::$app->params['area'];
        $asynNotifyUrl = \Yii::$app->params['alipayNotify'];
//        $payType = 'SCANPAY_ALIPAY';
        //查询数据库数据生成签名进行验证
        $orderData = Recharge::find()->where("orderNumber = '{$orderNumber}'")->asArray()->one();
        $payType = $orderData['payType'];
        if($payType ==2){
            $payType = 'JSAPI_WEIXIN';//微信
        }else{
            $payType = 'JSAPI_ALIPAY';//支付宝
        }
        $dateTime = date('YmdHis',$orderData['createTime']);
        $postData = ['amount'=>(100*$orderData['money']),'appid'=>$appid,'area'=>$area,'asynNotifyUrl'=>$asynNotifyUrl,'city'=>$city,'dateTime'=>$dateTime,'orderNo'=>$orderNumber,'payType'=>$payType,'productName'=>$orderData['product'],'province'=>$province,'returnUrl'=>''];
        ksort($postData);//生成签名
        $sign = self::signAlipay($postData,$key);
        $paySign = $orderData['paySign'];
        if($sign ==$paySign){
            return true;
        }else{
            return false;
        }
    }
}
