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