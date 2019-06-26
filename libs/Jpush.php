<?php
namespace app\libs;
use yii;
use JPush\Client as Client;
class Jpush
{
    static public function push($type=1,$message='hello',$content='发帖',$alias='hublot',$informType=1,$num = 0,$plate=1){
        include_once($_SERVER['DOCUMENT_ROOT'].'/../libs/jpush/autoload.php');
        $app_key = '';
        $master_secret = '';
        $client = new Client($app_key, $master_secret);
        if($type == 1){
            $push_payload = $client->push()
                ->setPlatform('all')
                ->addAlias($alias)
                ->message($message, array(
                    'extras' => array(
                        'type' => $informType,
                        'message' => $content
                    ),
                ))
                ->options(array(
                    'apns_production' => true,
                ));
        }elseif($type==2){
            $push_payload = $client->push()
                ->setPlatform('all')
                ->addAlias($alias)
                ->iosNotification($message, array(
                    'badge' => $num,
                    'extras' => array(
                        'type' => $type,
                        'message' => $content,
                        'informType' => $informType,
                        'plate' => $plate
                    ),
                ))
                ->androidNotification($message, array(
                    'extras' => array(
                        'type' => $type,
                        'message' => $content,
                        'informType' => "$informType"
                    ),
                ))
                ->options(array(
                    'apns_production' => true,
                ));
        } else{
            $push_payload = $client->push()
                ->setPlatform('all')
                ->addAllAudience()
                ->iosNotification($message)
                ->androidNotification($message)
                ->options(array(
                    'apns_production' => true,
                ));
        }
        try {
            $response = $push_payload->send();
        }catch (\JPush\Exceptions\APIConnectionException $e) {
            // try something here
//            print $e;
        } catch (\JPush\Exceptions\APIRequestException $e) {
            // try something here
//            print $e;
        }
    }

    static public function androidPush($type=1,$message='hello',$content='发帖',$alias='hublot',$num = 0,$informType=1,$belong,$plate=1){
        include_once($_SERVER['DOCUMENT_ROOT'].'/../libs/jpush/autoload.php');
        if($belong==2){
            $app_key = '';
            $master_secret = '';
        } elseif($belong==3){
            $app_key = '';
            $master_secret = '';
        } else{
            $app_key = '';
            $master_secret = '';
        }
        $client = new Client($app_key, $master_secret);
        if($type == 1){
            $push_payload = $client->push()
                ->setPlatform('all')
                ->addAllAudience()
                ->message($message, array(
                    'extras' => array(
                        'type' => $type,
                        'message' => $content
                    ),
                ))
                ->options(array(
                    'apns_production' => true,
                ));
        }elseif($type==2){
            $push_payload = $client->push()
                ->setPlatform('all')
                ->addAlias($alias)
                ->iosNotification($message, array(
                    'badge' => $num,
                    'extras' => array(
                        'type' => $type,
                        'message' => $content,
                        'informType' => $informType
                    ),
                ))
                ->androidNotification($message, array(
                    'extras' => array(
                        'type' => $type,
                        'message' => $content,
                        'informType' => "$informType",
                        'plate' => $plate
                    ),
                ))
                ->options(array(
                    'apns_production' => true,
                ));
        } else{
            $push_payload = $client->push()
                ->setPlatform('all')
                ->addAllAudience()
                ->iosNotification($message)
                ->androidNotification($message)
                ->options(array(
                    'apns_production' => true,
                ));
        }
        try {
            $response = $push_payload->send();
        }catch (\JPush\Exceptions\APIConnectionException $e) {
            // try something here
//            print $e;
        } catch (\JPush\Exceptions\APIRequestException $e) {
            // try something here
//            print $e;
        }
    }
}