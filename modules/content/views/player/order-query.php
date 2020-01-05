<script type="text/javascript" src="/My97DatePicker/WdatePicker.js"></script>
<div class="span10" id="datacontent">
    <ul class="breadcrumb">
        <li><a href="/content/player/index">玩家相关</a> <span class="divider">/</span></li>
        <li class="active">订单查询</li>
    </ul>
    <ul class="nav">
    </ul>
    <form action="/content/player/order-query" method="get" class="form-horizontal">
        <table class="table">
            <tr>
                <td>
                    账号：
                </td>
                <td>
                    <input  style="height: 30px;" type="text"  id="userId"  name="userId" value="<?php echo isset($_GET['userId'])?$_GET['userId']:''?>"/>
                </td>
                <td>
                    角色名：
                </td>
                <td>
                    <input  style="height: 30px;"  type="text"  id="name"  name="name" value="<?php echo isset($_GET['name'])?$_GET['name']:''?>"/>
                </td>
                <td>
                    RoleID：
                </td>
                <td>
                    <input style="height: 30px" type="text" size="10" id="uid"  name="uid" value="<?php echo isset($_GET['uid'])?$_GET['uid']:''?>"/>
                </td>
                <td>
                    订单号查询：
                </td>
                <td>
                    <input style="height: 30px"  type="text" size="10" id="order"  name="order" value="<?php echo isset($_GET['order'])?$_GET['order']:''?>"/>
                </td>
            </tr>
            <tr>
                <td>
                    区服：
                </td>
                <td>
                    <select name="server">
                        <option value="0">请选择</option>
                        <?php
                        foreach($servers as $k => $v){ ?>
                            <option value='<?php echo $v['id']?>' <?php if(isset($_GET['server']) && $_GET['server'] == $v['id']) echo 'selected';?>><?php echo $v['name']?></option>";
                            <?php
                        }
                        ?>
                    </select>
                </td>
                <td>
                    充值状态：
                </td>
                <td>
                    <select name="status">
                        <option value="0">请选择</option>
                        <option value="1" <?php if(isset($_GET['status']) && $_GET['status'] == 1) echo 'selected';?>>已完成</option>
                        <option value="2" <?php if(isset($_GET['status']) && $_GET['status'] == 2) echo 'selected';?>>未完成</option>
                    </select>
                </td>
                <td>
                    <button class="btn btn-primary" type="submit">查询</button>
                </td>
                <td></td>
            </tr>
        </table>
    </form>
    <form action="/content/player/order-query" method="post">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>角色ID</th>
                <th>区服</th>
                <th>角色名</th>
<!--                <th>账号名称</th>-->
                <th>账号</th>
                <th>渠道</th>
                <th>订单号</th>
                <th>金额</th>
                <th>充值渠道</th>
                <th >充值时间</th>
                <th >领取时间</th>
                <th >状态</th>
                <th >操作</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach($data as $kss => $v) {
                ?>
                <tr  class="text-item tdBorder">
                    <td ><span ><?php echo $v['roleID']?></span></td>
                    <td ><span ><?php echo $v['worldID']?></span></td>
                    <td ><span ><?php echo $v['roleName']?></span></td>
                    <td ><span ><?php echo $v['userId']?></span></td>
<!--                    <td ><span >--><?php //echo $v['username']?><!--</span></td>-->
                    <td ><span ><?php echo $v['packageFlag']?></span></td>
                    <td ><span ><?php echo $v['orderid']?></span></td>
                    <td ><span><?php echo $v['chargenum']?></span></td>
                    <td ><span><?php echo $v['typeStr']?></span></td>
                    <td style="width: 115px;"><span ><?php echo $v['createTime']?></span></td>
                    <td style="width: 115px;"><span><?php echo $v['finishTime']?></span></td>
                    <td ><span><?php echo strtotime($v['finishTime'])>0?'已完成':'未完成'?></span></td>
                    <td ><span>
                            <?php  if(strtotime($v['finishTime'])<0){?>
                                <input type="hidden" value="<?php echo $v['orderid']?>" id="mon<?php echo $kss?>" />
                                <button onclick="addMoney(<?php echo $kss?>)">补单</button>
                            <?php }?>
                        </span></td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </form>
    <div class="pagination pagination-right">
        <span style="font-size: 17px;position: relative;bottom: 7px;">共<?php echo $count;?>条&nbsp;</span>
        <?php if($count > 200){?>
            <span style="font-size: 17px;position: relative;bottom: 5px;">
            <a onclick="jumpPage()">Go</a>&nbsp;
            <input type="text" style="width: 20px;height: 18px;" id="jumpPage">&nbsp;页
        </span>
        <?php }?>
        <?php use yii\widgets\LinkPager;
        echo LinkPager::widget([
            'pagination' => $page,
        ])?>
    </div>
</div>
<script>
    function jumpPage(){
        var page = $("#jumpPage").val();
        if(isNaN(page) || page <= 0 || !page){
            alert('请输入正确的数值');
            return false;
        }
        location.href = '/content/rule/role?page='+page;
    }
    function addMoney(site){
        if(confirm('确定进行补单？')){
            var str = '#mon'+site;
            var orderId = $(str).val();
            if(!orderId){
                alert('订单id不存在');return false;
            }
            $.post('/content/api/add-money',{orderId:orderId},function(e){
                alert(e.message);
                if(e.code == 1){
                    window.location.reload();
                }
            },'json');
        }else{
            return false;
        }
    }
</script>