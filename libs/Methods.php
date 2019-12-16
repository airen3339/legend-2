<?php
namespace app\libs;
use app\modules\test\models\SchoolTest;
use app\modules\test\models\User;
use app\modules\test\models\UserCategoryStatus;
use app\modules\test\models\UserOrNeedWords;
use app\modules\test\models\UserTodayModel;
use app\modules\test\models\UserWords;
use app\modules\test\models\Words;
use app\modules\test\models\WordsEnglish;
use app\modules\test\models\WordsLowSentence;
use app\modules\test\models\WordsOpposites;
use app\modules\test\models\WordsRoot;
use app\modules\test\models\WordsSentence;
use app\modules\test\models\WordsSimple;
use yii;
use yii\data\Pagination;
class Methods
{
    public static function jsonData($code,$message,$data=[]){
        $data = ['code'=>$code,'message'=>$message,'data'=>$data];
        $data = json_encode($data);
        die($data);
    }
    /**
     * 分页函数
     * @param array $config 分页配置
     * @return array 分页
     * @Obelisk
     */
    public static function getPagedRows($config=[])
    {
        $pages=new Pagination(['totalCount' => $config['count']]);
        if(isset($config['pageSize']))
        {
            $pages->setPageSize($config['pageSize'],true);
        }
        return $pages;
    }

    /**
     * post请求
     * @param $url
     * @param string $post_data
     * @param int $timeout
     * @return mixed
     * @Obelisk
     */
    public static  function post($url, $post_data = '', $timeout = 5){//curl

        $ch = curl_init();

        curl_setopt ($ch, CURLOPT_URL, $url);

        curl_setopt ($ch, CURLOPT_POST, 1);

        if(is_array($post_data)){
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
        }else{
            curl_setopt($ch, CURLOPT_POSTFIELDS,$post_data);//微信 xml数据
        }


        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

        curl_setopt($ch, CURLOPT_HEADER, false);
        //忽略证书
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $file_contents = curl_exec($ch);

        curl_close($ch);

        return $file_contents;

    }

    /**
     * CURL请求
     * @param $url 请求url地址
     * @param $method 请求方法 get post
     * @param null $postfields post数据数组
     * @param array $headers 请求header信息
     * @param bool|false $debug 调试开启 默认false
     * @return mixed
     */
    public static function httpRequest($url, $method, $postfields = null, $headers = array(), $debug = false) {
        $method = strtoupper($method);
        $ci = curl_init();
        /* Curl settings */
        curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($ci, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.2; WOW64; rv:34.0) Gecko/20100101 Firefox/34.0");
        curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 60); /* 在发起连接前等待的时间，如果设置为0，则无限等待 */
        curl_setopt($ci, CURLOPT_TIMEOUT, 7); /* 设置cURL允许执行的最长秒数 */
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, true);
        switch ($method) {
            case "POST":
                curl_setopt($ci, CURLOPT_POST, true);
                if (!empty($postfields)) {
                    $tmpdatastr = is_array($postfields) ? http_build_query($postfields) : $postfields;
                    curl_setopt($ci, CURLOPT_POSTFIELDS, $tmpdatastr);
                }
                break;
            default:
                curl_setopt($ci, CURLOPT_CUSTOMREQUEST, $method); /* //设置请求方式 */
                break;
        }
        $ssl = preg_match('/^https:\/\//i',$url) ? TRUE : FALSE;
        curl_setopt($ci, CURLOPT_URL, $url);
        if($ssl){
            curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
            curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, FALSE); // 不从证书中检查SSL加密算法是否存在
        }
        curl_setopt($ci, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ci, CURLOPT_MAXREDIRS, 2);/*指定最多的HTTP重定向的数量，这个选项是和CURLOPT_FOLLOWLOCATION一起使用的*/
        curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ci, CURLINFO_HEADER_OUT, true);
        $response = curl_exec($ci);
        $requestinfo = curl_getinfo($ci);
        if ($debug) {
            echo "=====post data======\r\n";
            var_dump($postfields);
            echo "=====info===== \r\n";
            print_r($requestinfo);
            echo "=====response=====\r\n";
            print_r($response);
        }
        curl_close($ci);
        return $response;
    }
    /**
     * @param $server_id   服务器id 区服
     * @param $command_content  发送内容
     * @param int $command 消息类型
     * @param int $command_cls 消息号
     * @param int $timeout
     * @return bool|string
     * 与服务端对接GM命令
     */
    public static function GmPost($command_content,$server_id=903,$command=6,$command_cls=4234,$timeout = 5){
        $url = '192.168.0.15:8080';
        $post_data = ['server_id'=>$server_id,'command'=>$command,'command_cls'=>$command_cls,'command_content'=>json_encode(['body'=>$command_content])];
        $ch = curl_init();

        curl_setopt($ch,CURLOPT_URL,$url);

        curl_setopt ($ch, CURLOPT_POST, 1);

        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));


        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

        curl_setopt($ch, CURLOPT_HEADER, false);
        //忽略证书
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $file_contents = curl_exec($ch);

        curl_close($ch);

        return $file_contents;
    }

    /**
     * @param $command_content
     * @param int $server_id
     * @param int $command
     * @param int $command_cls
     * @return bool|false|string
     * GM命令推送
     * PHP 推向 服务器
     * 4242 - 活动推送端口  4241-时时彩推送
     */
    public static function GmFileGet($command_content,$server_id=100,$command=6,$command_cls=4234){
        $url = Yii::$app->params['gameServerUrl'];
//        Methods::varDumpLog('push.txt',$url,'a');
        if(!$url){return 1;}//本地不推
        $post_data = ['server_id'=>$server_id,'command'=>$command,'command_cls'=>$command_cls,'command_content'=>json_encode(['body'=>$command_content])];
        $post_data = http_build_query($post_data);
        $aContext = array('http'=>array('method' => 'POST','header' =>'Content-type: application/x-www-form-urlencoded','content' =>$post_data));
        $cxContext = stream_context_create($aContext);
        $res = file_get_contents($url,true,$cxContext);
//        Methods::varDumpLog('push.txt',$url,'a');
        return $res;
    }
    /**
     * 公告推送
     * 跑马灯
     */
    public static function GmPushContent($param){
        $url = Yii::$app->params['gameServerUrl'];
        if(!$url){return 1;}//本地不推
        $content = json_encode($param);
        $pushContent = ['data_packet'=>$content];
        $post_data = http_build_query($pushContent);
        $aContext = array('http'=>array('method' => 'POST','header' =>'Content-type: application/x-www-form-urlencoded','content' =>$post_data));
        $cxContext = stream_context_create($aContext);
        $res = file_get_contents($url,true,$cxContext);
        return $res;
    }
    /**
     * @param $filename
     * @param $content
     * @param string $do
     * 日志打印
     */
    public static function varDumpLog($filename,$content,$do='w'){
        $path = fopen(IndexDir.'/files/log/'.$filename,$do);
        fwrite($path,$content);
        fclose($path);
    }
    /**
     * excel 数据生成
     * 数据导出
     * cy
     */
    public static function excelDownload($data,$title,$th){
        ini_set("memory_limit",-1);
        set_time_limit(0);
        date_default_timezone_set('PRC');
        header("Content-type: text/html; charset=utf-8");
        include_once "PHPExcel.class.php";//引入phpexcel
        $objPHPExcel = new \PHPExcel();//实例化PHPExcel类，
        $objSheet = $objPHPExcel->getActiveSheet(0);//获取当前活动sheet
        $objSheet->setTitle($title); //給sheet 标题命名
        foreach($th as $o => $r){
            $objSheet->setCellValue($r['column'].'1',$r['title']);//设定标题
        }
        $j = 2;
        foreach($data as $t => $r){
            foreach($th as $e => $q){
                if(preg_match('/=/',$r[$q['key']])){//特殊字符处理
                    $objSheet->setCellValue($q['column'].$j,' '.$r[$q['key']]);
                }else{
                    $objSheet->setCellValue($q['column'].$j,$r[$q['key']]);
                }
                $objSheet->getStyle($q['column'].$j)->getAlignment()->setWrapText(true);
            }
            $j++;
        }
        $objWriter=\PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5'); //设定输出文件格式
//              $objWriter->save($dir."/export_1.xls");                             //保存文件
#输出到浏览器
        self::browser_export('Excel',$title.'.xls');//输出到浏览器
        $objWriter->save('php://output'); //输出excel 文件到浏览器
    }

    public static function browser_export($type,$filename){
        if($type=="Excel5"){
            header('Content-Type: application/vnd.ms-excel'); //告诉浏览器将要输出excel03文件

        }else{
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');//告诉浏览器数据excel07文件

        }
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //告诉浏览器将输出文件的名称
        header('Cache-Control: max-age=0'); //禁止缓存
    }
}