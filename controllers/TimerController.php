<?php
/**
 * 定时任务api
 */

namespace app\controllers;


use app\modules\content\models\Player;
use app\modules\content\models\PlayerLogin;
use app\modules\content\models\PlayerRegister;

class TimerController
{
    /**
     * 当天用户登录数据获取
     * 数据写入
     * 留存数据
     *
     */
    public function actionRetain(){
        $today = date('Y-m-d');
        $begin = strtotime($today);
        $end = $begin + 86399;
        //获取今日新增的用户数据
        $user = Player::find()->select("RoleID")->where("unix_timestamp(CreateDate) between $begin and $end")->asArray()->all();
        $total = count($user);
        $time = time();
        //今日登录用户
        $loginUser = Player::find()->select("RoleID")->where("LastLogin between $begin and $end")->asArray()->all();
        $loginTotal = count($loginUser);
        //开启事务
        $tr = \Yii::$app->db->beginTransaction();
        $save = 0;
        $roleIds = '';
        foreach($user as $t => $r){
            $roleIds .= ','.$r['RoleID'];
        }
        $roleIds = trim($roleIds,',');
        foreach($loginUser as $k => $v){
            $login = new PlayerLogin();
            $login->date = $today;
            $login->roleId = $v['RoleID'];
            $login->createTime = $time;
            $res = $login->save();
            if($res){
                $save++;
            }else{
                break;
            }
        }
        if($save == $loginTotal){
            $register = new PlayerRegister();
            $register->date = $today;
            $register->roleIds = $roleIds;
            $register->total = $total;
            $register->accountDau = $loginTotal;
            $register->createTime = $time;
            $result = $register->save();
            if($result){
                $tr->commit();
            }else{
                $tr->rollBack();
            }
        }else{
            $tr->rollBack();
        }
    }
}