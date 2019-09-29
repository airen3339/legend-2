<?php
//公告记录

namespace app\modules\content\models;


use yii\db\ActiveRecord;

class MailReceive extends ActiveRecord
{

    public static  function tableName(){
        return '{{%mail_receive}}';
    }
    /**
     * 记录用户邮件领取信息
     */
    public static function getMailLog(){
        ini_set('user_agent','Mozilla/4.0 (compatible; MSIE 5.00; Windows 98)');
        $date = date('Y-m-d');
        //删除当天的数据记录
        self::deleteAll("date = '{$date}'");
        $servers = Server::getServers();//获取区服
//        $url = IndexDir.'/files/';
        $url = 'http://192.168.0.30/logs/TLog/';
        foreach($servers as $k => $v) {
            $dat = str_replace('-','',$date);
            //获取日志文件并统计
            $fileName = 'Tlog.' . $v['id'] . '.0_' . $dat . '.log';
            $path = $url . $fileName;
            try{
                $file = file_get_contents($path);
                $file = str_replace(array("\n","\r","\t"),'',$file);
                preg_match_all('/KeyItemFlow(\|([^\|]+))+(\|\|)([^|])((\|)([^\|]+))/', $file, $mailArr);
                $mails = $mailArr[0];
                foreach($mails as $e => $q){//键值对应 1-区服  2-时间 3-角色id 4-道具id 5-道具数量 8-邮件状态（1-已接收 2-已领取）
                    $arr = explode('|',$q);
                    $model = new MailReceive();
                    $model->roleId = $arr[3];
                    $model->receive = $arr[8];
                    $model->content = $arr[4];
                    $model->serverId = $v['id'];
                    $model->receiveTime = $arr[2];
                    $model->number = $arr[5];
                    $model->date = $date;
                    $model->type = 1;//1-道具
                    $model->createTime = time();
                    $model->save();
                }
            }catch(\Exception $e){

            }
        }
    }
}