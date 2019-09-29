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
        $model->payType = 1;
        $model->yuanbao = $ratio*$amount+$luckNum;
        $model->save();
//        通知服务器
//        self::dataToServer($orderNumber,$productName,$amount,1,$date,$detail);
        $return = self::AliOrder($orderNumber,$productName,$amount,$dateTime,$province,$city,$area,$model->id);
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
//        通知服务器
//        self::dataToServer($orderNumber,$productName,$amount,2,$date,$detail);
        //本地数据记录
        $uid = $request->post('uid');
        $ratio = $request->post('ratio');//兑换元宝比例
        $lucknum = $request->post('lucknum');//额外奖励的元宝数
        $server_id = $request->post('server_id');//游戏服务器id

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
    public static  function AliOrder($orderNumber,$productName,$amount,$time,$province,$city,$area,$orderId){
        $appid = \Yii::$app->params['alipayAppid'];
        $key = \Yii::$app->params['alipayKey'];
        $dateTime = $time;
        $payType = 'SCANPAY_ALIPAY';
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
        $return = json_decode($return,true);
        if($return['code'] == 'success'){
            $returnData = json_decode($return['data'],true);
            $payUrl = $returnData['payUrl'];
            $data = ['code'=>1,'payUrl'=>$payUrl];//,'msg'=>'支付请求成功'
            //记录签名
            Recharge::updateAll(['paySign'=>$sign],"id = $orderId");
        }else{
            $data = ['code'=>-6];//,'msg'=>$return['message'] 支付请求错误
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
                    Recharge::updateAll(['status'=>1],"orderNumber='{$orderNo}'");//修改订单状态
                    //通知服务器处理后续
//                    $amount = $amount/100;//换成元
                    $postData = ['uid'=>$orderData['roleId'],'pay_money'=>$orderData['money'],'ratio'=>$orderData['ratio'],'lucknum'=>$orderData['lucknum'],'server_id'=>$orderData['server_id'],'sign'=>$orderData['sign'],'order_no'=>$orderNo,'ext_info'=>$orderData['extInfo']];
//                    $url = '192.168.0.15:8080';
                    $url = \Yii::$app->params['gameServerUrl'];
                    $res = Methods::post($url,$postData);
//                    Methods::varDumpLog('pay.txt',json_encode($postData),'a');
//                    Methods::varDumpLog('pay.txt',json_encode($res),'a');
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
    public function actionNotifyTest(){

        //验证签名
        $order = \Yii::$app->request->get('order','');
        $amount = \Yii::$app->request->get('amount',1);
        $orderData = Recharge::find()->where("orderNumber = '{$order}' and money = '{$amount}'")->asArray()->one();
//        if($orderData['status'] != 1){//订单未完成
            Recharge::updateAll(['status'=>1],"orderNumber='{$order}'");//修改订单状态
            //通知服务器处理后续
            $postData = ['uid'=>$orderData['roleId'],'pay_money'=>$amount,'ratio'=>$orderData['ratio'],'lucknum'=>$orderData['lucknum'],'server_id'=>$orderData['server_id'],'sign'=>$orderData['sign'],'order_no'=>$order,'ext_info'=>$orderData['extInfo']];
            $url = '192.168.0.15:8080';
            $res = Methods::post($url,$postData);
            var_dump($res);
//        }
        die('SUCCESS');
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
    public static function checkAlipaySign($orderNumber,$appid){
        $key = \Yii::$app->params['alipayKey'];
        $province = \Yii::$app->params['province'];
        $city = \Yii::$app->params['city'];
        $area = \Yii::$app->params['area'];
        $asynNotifyUrl = \Yii::$app->params['alipayNotify'];
        $payType = 'SCANPAY_ALIPAY';
        //查询数据库数据生成签名进行验证
        $orderData = Recharge::find()->where("orderNumber = '{$orderNumber}'")->asArray()->one();
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
