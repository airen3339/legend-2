<?php

/**
 * 玩家相关模块
 */
namespace app\modules\content\controllers;


use app\libs\AdminController;
use app\modules\content\models\ActivityLog;
use app\modules\content\models\ChargeMoney;
use app\modules\content\models\CurrencyData;
use app\modules\content\models\Item;
use app\modules\content\models\MailReceive;
use app\modules\content\models\Player;
use app\modules\content\models\RoleActivity;
use app\modules\content\models\Server;
use app\modules\content\models\YuanbaoRole;
use app\modules\content\models\YuanbaoRoleLog;
use app\modules\content\models\YuanbaoType;
use app\modules\pay\models\Notify;
use app\modules\pay\models\Recharge;
use yii\data\Pagination;
use yii;

class PlayerController  extends AdminController
{
    public $enableCsrfValidation = false;
    public $layout = 'content';

    public function init(){
        parent::init();
        parent::setContentId('player');
    }
    public function actionIndex(){
        return $this->redirect('/content/index/index');
    }
    /**
     * 角色信息
     */
    public function actionRoleInformation(){
        $action = \Yii::$app->controller->action->id;
        parent::setActionId($action);
        $name = \Yii::$app->request->get('name','');
        $service = \Yii::$app->request->get('server');
        $roleId = \Yii::$app->request->get('roleId');
        $page = \Yii::$app->request->get('page',1);
        $userId = \Yii::$app->request->get('userId');//账号
        $where = ' 1=1 ';
        if($name){
            $where .= " and p.Name = '{$name}'";
        }
        if($service){
            $where .= " and u.WorldID = '{$service}'";
        }
        if($roleId){
            $where .= " and p.RoleID = '{$roleId}' ";
        }
        if($userId){
            $where .= " and p.UserID = '{$userId}'";
        }
        $sql = "select p.RoleID,p.UserID,p.LastLogin,p.CreateDate,u.PackageFlag,p.WorldID,p.Name from `user` u inner join player p on p.UserID = u.UserID where $where";
        $excel = Yii::$app->request->get('excel',0);//0-搜索 1-excel导出
        if($excel){//Excel数据导出
            Player::roleDownloadExcel($sql);die;
        }
        $count = \Yii::$app->db2->createCommand($sql)->queryAll();
        $count = count($count);
        $limit = " limit ".(20*($page-1)).",20";
        $pages = new Pagination(['totalCount'=>$count,'pageSize'=>20]);
        $sql .= $limit;
        $user = \Yii::$app->db2->createCommand($sql)->queryAll();
        $servers = Server::getServers();
        return $this->render('role-information',['user'=>$user,'page'=>$pages,'count'=>$count,'servers'=>$servers]);
    }
    /**
     * 详细信息
     */
    public function actionDetailInformation(){
        $action = \Yii::$app->controller->action->id;
        parent::setActionId($action);
        $uid = \Yii::$app->request->get('uid');
        $name = \Yii::$app->request->get('name','');
        $userId = \Yii::$app->request->get('userId');
        $where = ' 1=1 ';
        $wh = ' 1=1 ';
        if($uid || $name || $userId){
            if($uid){
                $where .= " and RoleID = '{$uid}' ";
            }
            if($name){
                $where .= " and Name = '{$name}' ";
            }
            if($userId){
                $where .= " and UserID = '{$userId}'";
            }
            $data = Player::find()->select("RoleID,UserID,WorldID,WorldName,Name,Level,Ingot,Cash,Money,CurHP,CurMP,Exp,Battle,Vital,MonsterKillNum,SoulScore,PkValue")->where($where)->asArray()->all();
            foreach($data as $k => $v){
                $roleId = $v['RoleID'];
                $wh .= " and status = 2 and RoleID = '{$roleId}'";
                //充值金额
                $money = ChargeMoney::find()->where($wh)->sum('chargenum');
                $data[$k]['rechargeMoney'] = $money?$money:0;
            }
        }else{
            $data = [];
        }
        return $this->render('detail-information',['data'=>$data]);
    }
    /**
     * 订单查询
     */
    public function actionOrderQuery(){
        $action = \Yii::$app->controller->action->id;
        parent::setActionId($action);
        $service = \Yii::$app->request->get('server',0);
        $uid = \Yii::$app->request->get('uid');
        $order = \Yii::$app->request->get('order');
        $status = \Yii::$app->request->get('status',0);
        $name = \Yii::$app->request->get('name','');
        $userId = \Yii::$app->request->get('userId');
        $where = ' 1=1 ';
        if($name){
            $roleId = Player::find()->where("Name = '{$name}'")->asArray()->one()['RoleID'];
            if($roleId){
                $where .= " and roleId = '{$roleId}'";
            }else{
                $where .= " and 1 > 2";
            }
        }
        if($service){
            $where .= " and worldID = '{$service}'";
        }
        if($uid){
            $where .= " and roleID = '{$uid}' ";
        }
        if($order){
            $where .= " and orderid = '{$order}'";
        }
        if($status ==1){
            $where .= " and unix_timestamp(finishTime) > 0 ";
        }elseif($status ==2){
            $where .= " and unix_timestamp(finishTime) = 0 ";
        }
        if($userId){
            $roles = Player::find()->select("RoleID")->asArray()->where("UserID = '{$userId}'")->all();
            $roleIds = "";
            foreach($roles as $k => $v){
                $roleIds .= "'".$v['RoleID']."',";
            }
            if($roleIds){
                $roleIds = trim($roleIds,',');
                $where .= " and roleID in ($roleIds)";
            }else{
                $where .= " and 1 > 2";
            }
        }
        $excel = Yii::$app->request->get('excel',0);//0-搜索 1-excel导出
        if($excel){//Excel数据导出
            Player::orderDownloadExcel($where);die;
        }
        $total = ChargeMoney::find()->where("$where")->count();
        $pages = new Pagination(['totalCount'=>$total,'pageSize'=>20]);
        $data = ChargeMoney::find()->where($where)->orderBy('createTime desc')->offset($pages->offset)->limit($pages->limit)->asArray()->all();
        foreach($data as $k => $v){
            $sql = "select u.Username,u.PackageFlag,p.Name,p.UserID from `user` u inner join player p on p.UserID = u.UserID inner join chargemoney c on c.roleID = p.RoleID where c.roleID = '{$v['roleID']}' ";
            $da = \Yii::$app->db2->createCommand($sql)->queryOne();
            $data[$k]['username'] = $da['Username'];
            $data[$k]['packageFlag'] = $da['PackageFlag'];
            $data[$k]['roleName'] = $da['Name'];
            $data[$k]['userId'] = $da['UserID'];
            //订单是否共存
            $order = Recharge::find()->where("orderNumber = '{$v['orderid']}'")->asArray()->one();
            if($order){
                $data[$k]['merOrder'] = $order['merOrder'];
                if($order['status'] ==0){
                    $data[$k]['hadOrder'] = 1;//php库有没有改订单 判断补单
                }else{
                    $data[$k]['hadOrder'] = 0;//php库有没有改订单 判断补单
                }
            }else{
                $data[$k]['merOrder'] = '';
                $data[$k]['hadOrder'] = 0;
            }
            //支付类型
            $orderTypeStr = '';
            $orderType = $v['type'];
            if($orderType==1){
                $orderTypeStr = '支付宝';
            }elseif($orderType == 2){
                $orderTypeStr = '微信';
            }
            $data[$k]['typeStr'] = $orderTypeStr;
        }
        $servers = Server::getServers();
        return $this->render('order-query',['data'=>$data,'page'=>$pages,'count'=>$total,'servers'=>$servers]);
    }
    
    /**
     * 货币消耗
     * 区服统计
     */
    public function actionMoneyUse(){
        $action = \Yii::$app->controller->action->id;
        parent::setActionId($action);
        CurrencyData::updateMoneyUse();
        $server = \Yii::$app->request->get('server',0);
        $type = \Yii::$app->request->get('type',0);
        $where = ' type = 1 ';
        if($server){
            $where .= " and serverId = '{$server}'";
        }
        if($type){
            $where .= " and typeObject = $type ";
        }
        $count = CurrencyData::find()->where($where)->groupBy("serverId,typeObject,added")->count();
        $page = new Pagination(['totalCount'=>$count]);
        $data = CurrencyData::find()->select("serverId,type,typeObject,remark,added,sum(number) as money")->where($where)->offset($page->offset)->limit($page->limit)->groupBy("serverId,typeObject,added")->orderBy('money desc')->asArray()->all();
        $servers = Server::getServers();
        $types = YuanbaoRoleLog::getTypes();
        return $this->render('money-use',['data'=>$data,'servers'=>$servers,'types'=>$types,'page'=>$page,'count'=>$count]);
    }
    /**
     * 日志查询
     */
    public function actionLogQuery(){
        $action = \Yii::$app->controller->action->id;
        parent::setActionId($action);
        $beginTime = \Yii::$app->request->get('beginTime');
        $endTime = \Yii::$app->request->get('endTime');
        $service = \Yii::$app->request->get('server');
        $uid = \Yii::$app->request->get('uid');
        $type = \Yii::$app->request->get('type');
        $added = \Yii::$app->request->get('added',99);
        $name = \Yii::$app->request->get('name','');
        $where = ' 1=1 ';
        if($beginTime){
            $begin = strtotime($beginTime);
            $where .= " and createTime >= $begin";
        }
        if($endTime){
            $end = strtotime($endTime) + 86399;
            $where .= " and createTime <= $end";
        }
        if($service){
            if($type ==4){
                $where .= " and server_id = '{$service}'";
            }else{
                $where .= " and serverId = '{$service}'";
            }
        }
        $str = '';
        if($uid){
            $where .= " and roleId = '{$uid}' ";
            $player = Player::find()->where("RoleID = '{$uid}'")->asArray()->one();
            if($player){
                $str = "账号：".$player['UserID']."&nbsp;&nbsp;&nbsp;   角色名：".$player['Name']."&nbsp;&nbsp;&nbsp;   角色ID：".$uid.'&nbsp;&nbsp;&nbsp;   总计：';
            }
        }
        if($name){
            $roleId = Player::find()->where("Name = '{$name}'")->asArray()->one()['RoleID'];
            if($roleId){
                $where .= " and roleId = '{$roleId}'";
            }else{
                $where .= " and 1 > 2";
            }

        }
        if($type && $type != 4){
            $where .= " and type = $type ";
        }
        if( $type != 4){//不是元宝充值
            if($added != 99){
                $where .= " and added = $added ";
            }
            $count = YuanbaoRoleLog::find()->where($where)->count();
            $page = new Pagination(['totalCount'=>$count]);
            $data = YuanbaoRoleLog::find()->where($where)->orderBy('dateTime desc')->offset($page->offset)->limit($page->limit)->orderBy('id desc')->asArray()->all();
            if($uid){
                $yuanbaoTotal = YuanbaoRoleLog::find()->where($where)->sum('money');
                $str .= $yuanbaoTotal;
            }

        }else{
            if($added == 0){//元宝充值没有支出
                $count = 0;
                $page = new Pagination(['totalCount'=>$count]);
                $data = [];
            }else{
                $count = Recharge::find()->where($where)->count();
                $page = new Pagination(['totalCount'=>$count]);
                $data = Recharge::find()->where($where)->orderBy('createTime desc')->offset($page->offset)->limit($page->limit)->asArray()->all();
            }
            if($uid){
                $yuanbaoTotal = Recharge::find()->where($where)->sum('money');
                $str .= $yuanbaoTotal;
            }
        }
        $servers = Server::getServers();
        $types = YuanbaoRoleLog::getTypes();
        foreach($data as $k => $v){
            $typeStr = '';
            if($type ==4){
                $typeStr = '元宝充值';
            }else{
                foreach($types as $t =>$w){
                    if($v['type'] == $w['id']){
                        $typeStr =  $w['name'];
                    }
                }
            }
            $data[$k]['typeStr'] = $typeStr;
        }
        return $this->render('log-query',['data'=>$data,'servers'=>$servers,'types'=>$types,'page'=>$page,'count'=>$count,'str'=>$str]);
    }

    /**
     * 角色邮件领取
     */
    public function actionMailReceive(){
        $action = \Yii::$app->controller->action->id;
        parent::setActionId($action);
        $name = \Yii::$app->request->get('name','');
        $serverId = \Yii::$app->request->get('server','');
        $where = " 1=1";
        if($serverId || $name){
            //获取实时日志记录
            MailReceive::getMailLog();
        }
        if($serverId){
            $where .= " and serverId = $serverId ";
        }
        if($name){
            $roleId = Player::find()->where("Name='{$name}'")->asArray()->one()['Name'];
            if($roleId){
                $where .= " and roleId = '{$roleId}'";
            }else{
                $where .= " and 1 > 2";
            }
        }
        $count = MailReceive::find()->where($where)->count();
        $page = new Pagination(['totalCount'=>$count]);
        $data = MailReceive::find()->where($where)->orderBy('id desc')->offset($page->offset)->limit($page->limit)->asArray()->all();
        foreach($data as $k => $v){
            $content = Item::find()->where("itemid = {$v['content']}")->asArray()->one()['name'];
            $data[$k]['content'] = $content.' '.$v['content'];
        }
        $servers = Server::getServers();
        return $this->render('mail-receive',['data'=>$data,'page'=>$page,'count'=>$count,'servers'=>$servers]);
    }
    /**
     * 等级排行
     * 前20名
     */
    public function actionLevelOrder(){
        $action = \Yii::$app->controller->action->id;
        parent::setActionId($action);
        $serverId = Yii::$app->request->get('server',0);
        if(!$serverId){
            $serverId = 1;//默认显示一区数据
        }
        $where = " WorldID = $serverId";
        //等级前20名 等级一样以经验排
        $player = Player::find()->select("RoleID,UserID,WorldID,WorldName,Name,Level,Ingot,Cash,Money,CurHP,CurMP,Exp,Battle,Vital,MonsterKillNum,SoulScore,PkValue")->where($where)->orderBy("Level desc,Exp desc")->limit(20)->asArray()->all();
        foreach($player as $k => $v){
            $roleId = $v['RoleID'];
            $wh = " status = 2 and RoleID = '{$roleId}'";
            //充值金额
            $money = ChargeMoney::find()->where($wh)->sum('chargenum');
            $player[$k]['rechargeMoney'] = $money?$money:0;
            //更新元宝消耗记录
        }
        $servers = Server::getServers();
        return $this->render('level-order',['data'=>$player,'servers'=>$servers]);
    }
    /**
     * 战力排行
     * 前20名
     */
    public function actionZlOrder(){
        $action = \Yii::$app->controller->action->id;
        parent::setActionId($action);
        $serverId = Yii::$app->request->get('server',0);
        if(!$serverId){
            $serverId = 1;//默认显示一区数据
        }
        $where = " WorldID = $serverId";
        //战力前20名
        $player = Player::find()->select("RoleID,UserID,WorldID,WorldName,Name,Level,Ingot,Cash,Money,CurHP,CurMP,Exp,Battle,Vital,MonsterKillNum,SoulScore,PkValue")->where($where)->orderBy("Battle  desc")->limit(20)->asArray()->all();
        foreach($player as $k => $v){
            $roleId = $v['RoleID'];
            $wh = " status = 2 and RoleID = '{$roleId}'";
            //充值金额
            $money = ChargeMoney::find()->where($wh)->sum('chargenum');
            $player[$k]['rechargeMoney'] = $money?$money:0;
            //更新元宝消耗记录
        }
        $servers = Server::getServers();
        return $this->render('zl-order',['data'=>$player,'servers'=>$servers]);
    }
    /**
     * 用户天中宝藏活动数据统计
     *
     */
    public function actionTzbzCount(){
        $action = \Yii::$app->controller->action->id;
        parent::setActionId($action);
        $beginTime = \Yii::$app->request->get('beginTime');
        $endTime = \Yii::$app->request->get('endTime');
        $name = \Yii::$app->request->get('name');
        $serverId = \Yii::$app->request->get('server');
        $where = " 1 = 1 ";

        //天和宝藏数据字段
        $arr = ActivityLog::tzbzReward();
        $percent = '0%';
        $hadRole = '';
        if($name){
            $roleId = Player::find()->where(" Name = '{$name}'")->asArray()->one()['RoleID'];
//            $roleId = '1539274448577280';
            if($roleId){
                $hadRole = $roleId;
                $today = strtotime(date('Y-m-d'));
                if($beginTime){
                    $begin = strtotime($beginTime);
                    $where .= " and unix_timestamp(dateTime) >= $begin";
                    if($begin >= $today){
                        //统计用户最新的活动数据
                        YuanbaoRoleLog::updateTzbzData($roleId);
                    }
                }
                if($endTime){
                    $end = strtotime($endTime);
                    $where .= " and unix_timestamp(dateTime) <= $end";
                    if($end >= $today){
                        //统计用户最新的活动数据
                        YuanbaoRoleLog::updateTzbzData($roleId);
                    }
                }else{
                    //统计用户最新的活动数据
                    YuanbaoRoleLog::updateTzbzData($roleId);
                }
                if($serverId){
                    $where .= " and serverId = $serverId ";
                }
                $where .= " and roleId = '{$roleId}' and type = 1";
                $total = 0;
//                $number = 0;//经验次数
                foreach($arr as $k => $v){//统计次数
                    $count = RoleActivity::find()->where("$where and contentId = {$v['id']}")->count();
                    $count = $count?$count:0;
//                    if($v['id'] == 444444){
//                        $number = $count;
//                    }
                    $total += $count;
                    $arr[$k]['count'] = $count;
                }
//                if($number && $total){
//                    $percent = (floor(100*($number/$total))/100).'%';
//                }
            }
        }
//        $totalArr = ['id'=>'','name'=>'经验次数概率','count'=>$percent];
//        $arr[] = $totalArr;
        $servers = Server::getServers();
        return $this->render('tzbz-count',['data'=>$arr,'servers'=>$servers,'hadRole'=>$hadRole]);
    }
    /**
     * 货币消耗
     * 角色统计
     */
    public function actionRoleMoneyUse(){
        $action = \Yii::$app->controller->action->id;
        parent::setActionId($action);
        $roleId = \Yii::$app->request->get('roleId','');
        $userId = \Yii::$app->request->get('userId');//账号
        $name = \Yii::$app->request->get('name');//角色名
        $type = \Yii::$app->request->get('type',0);
        $where = ' 1 = 1 ';
        $server = \Yii::$app->request->get('server',0);
        if($server){
            $where .= " and serverId = '{$server}'";
        }
        if($roleId){
            $where .= " and roleId = '{$roleId}'";
        }
        if($type){
            $where .= " and type = $type ";
        }
        if($userId){
            $roleIds = Player::find()->where("UserID = '{$userId}'")->asArray()->all();
            if($roleIds){
                $idStr = "";
                foreach($roleIds as $k => $v){
                    $idStr .= "'".$v['RoleID']."',";
                }
                $idStr = trim($idStr,',');
                $where .= " and roleId in ({$idStr})";
            }else{
                $where .= " and 1 > 2";
            }
        }
        if($name){
            $roleIds = Player::find()->where("Name like '%{$name}%'")->asArray()->all();
            if($roleIds){
                $idStr = "";
                foreach($roleIds as $k => $v){
                    $idStr .= "'".$v['RoleID']."',";
                }
                $idStr = trim($idStr,',');
                $where .= " and roleId in ({$idStr})";
            }else{
                $where .= " and 1 > 2";
            }
        }
        $count = YuanbaoRoleLog::find()->where($where)->groupBy('roleId,added')->count();
        $page = new Pagination(['totalCount'=>$count]);
        $types = YuanbaoRoleLog::getTypes();
        $data = YuanbaoRoleLog::find()->select("roleId,serverId,type,remark,added,sum(money) as money")->where($where)->offset($page->offset)->limit($page->limit)->orderBy('money desc')->groupBy('roleId,added')->asArray()->all();
        foreach($data as $k => $v){
            $player = Player::find()->where("RoleID = '{$v['roleId']}'")->asArray()->one();
            if($player){
                $data[$k]['userId'] = $player['UserID'];
                $data[$k]['name'] = $player['Name'];
            }else{
                $data[$k]['userId'] = '';
                $data[$k]['name'] = '';
            }
            $typeStr = '';
            foreach($types as $t => $r){
                if($v['type'] == $r['id']){
                    $typeStr = $r['name'];
                }
            }
            $data[$k]['typeStr'] = $typeStr;
        }
        $servers = Server::getServers();
        return $this->render('role-money-use',['data'=>$data,'servers'=>$servers,'types'=>$types,'page'=>$page,'count'=>$count]);
    }
    /**
     * 回调详情
     */
    public function actionNotifyDetail(){
        $orderId = \Yii::$app->request->get('orderId');
        if($orderId){
            $order = Notify::find()->where("id = '{$orderId}'")->asArray()->one();
        }else{
            $order = [];
        }
        return $this->render('notify-detail',['data'=>$order]);
    }
    /**
     * 回调信息数据
     */
    public function actionNotifyList(){
        $action = \Yii::$app->controller->action->id;
        parent::setActionId($action);
        $orderId = \Yii::$app->request->get('order');
        if($orderId){
            $where = " orderNumber = '{$orderId}'";
        }else{
            $where = '';
        }
        $count = Notify::find()->where($where)->count();
        $page = new Pagination(['totalCount'=>$count]);
        $data = Notify::find()->where($where)->offset($page->offset)->limit($page->limit)->orderBy('createTime desc')->asArray()->all();
        return $this->render('notify-list',['data'=>$data,'page'=>$page,'count'=>$count]);
    }
    /**
     * 活动类型
     */
    public function actionYuanbaoType(){
        $action = \Yii::$app->controller->action->id;
        parent::setActionId($action);
        $types = YuanbaoType::find()->asArray()->orderBy("rank desc")->all();
        return $this->render('yuanbao-type',['types'=>$types]);
    }
    /**
     * 类型编辑修改
     * 活动类型
     */
    public function actionYuanbaoTypeEdit(){
        if($_POST){
            $id = Yii::$app->request->post('id');
            $name = Yii::$app->request->post('name');
            $rank = Yii::$app->request->post('rank');
            $type = Yii::$app->request->post('type');
            $remark = Yii::$app->request->post('remark');
            if($id){
                $model = YuanbaoType::findOne($id);
                $had = YuanbaoType::find()->where("name = '{$name}' and id != $id")->one();
            }else{
                $model = new YuanbaoType();
                $had = YuanbaoType::find()->where("name = '{$name}'")->one();
            }
            if($had){
                echo "<script>alert('该类型名称已存在');setTimeout(function(){history.go(-1);},1000)</script>";die;
            }
            if(!$type){
                echo "<script>alert('请填写对应的元宝类型');setTimeout(function(){history.go(-1);},1000)</script>";die;
            }else{
                if($id ){
                    $where = " and id != $id";
                }else{
                    $where = '';
                }
                $had = YuanbaoType::find()->where("type = $type $where")->one();
                if($had){
                    echo "<script>alert('该元宝类型已存在');setTimeout(function(){history.go(-1);},1000)</script>";die;
                }
            }
            $model->name = $name;
            $model->rank = $rank?$rank:0;
            $model->type = $type;
            $model->remark = $remark;
            $model->createTime = time();
            $res = $model->save();
            if($res){
                echo "<script>alert('操作成功');setTimeout(function(){location.href='yuanbao-type';},1000)</script>";die;
            }else{
                echo "<script>alert('操作失败');setTimeout(function(){history.go(-1);},1000)</script>";die;
            }
        }else{
            $id= Yii::$app->request->get('id');
            if($id){
                $type = YuanbaoType::find()->where("id = $id")->asArray()->one();
            }else{
                $type = [];
            }
            return $this->render('yuanbao-edit',['type'=>$type]);
        }
    }
    /**
     * 活动类型
     * 删除
     */
    public function actionYuanbaoTypeDelete(){
        $id = Yii::$app->request->get('id');
        if($id){
            $res = YuanbaoType::deleteAll("id = $id");
            if($res ){
                echo "<script>alert('删除成功');setTimeout(function(){location.href='yuanbao-type';},1000)</script>";die;
            }else{
                echo "<script>alert('操作失败，请重试');setTimeout(function(){history.go(-1);},1000)</script>";die;
            }
        }else{
            echo "<script>alert('操作失败，请重试');setTimeout(function(){history.go(-1);},1000)</script>";die;
        }
    }
}