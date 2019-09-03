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
        $url = 'http://192.168.0.15:8080';
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
}