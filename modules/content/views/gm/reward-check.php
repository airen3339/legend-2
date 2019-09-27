<script type="text/javascript" src="/My97DatePicker/WdatePicker.js"></script>
<div class="span10" id="datacontent">
    <ul class="breadcrumb">
        <li><a href="/content/gm/index">GM工具</a> <span class="divider">/</span></li>
        <li class="active">奖励审核</li>
    </ul>
    <ul class="nav">
    </ul>
    <form action="/content/gm/reward-check" method="get" class="form-horizontal">
        <table class="table">
            <tr>
                <td>
                    角色名：
                </td>
                <td>
                    <input  style="height: 20px;" type="text"  id="name"  name="name" value="<?php echo isset($_GET['name'])?$_GET['name']:''?>"/>
                </td>
                <td>
                    roleId：
                </td>
                <td>
                    <input style="height: 20px;"   type="text" size="10" id="uid"  name="uid" value="<?php echo isset($_GET['uid'])?$_GET['uid']:''?>"/>
                </td>
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
                    类型：
                </td>
                <td>
                    <select name="type" id="type" style="width: 105px;">
                        <option value="0">请选择</option>
                            <option value='1' <?php if(isset($_GET['type']) && $_GET['type'] ==1) echo 'selected';?>>玩家奖励</option>;
                            <option value='2' <?php if(isset($_GET['type']) && $_GET['type'] ==2) echo 'selected';?>>区服奖励</option>;
                    </select>
                </td>
                <td>
                    <button class="btn btn-primary" type="submit">提交</button>
                </td>
                <td></td>
            </tr>
        </table>
    </form>
    <form action="/content/gm/reward-check" method="post">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>添加时间</th>
                <th>奖励类型</th>
                <th>区服</th>
                <th >邮件标题</th>
                <th >邮件说明</th>
                <th >奖励内容</th>
                <th >审核</th>
            </tr>
            </thead>
            <tbody>
                <?php foreach($data as $k => $v){?>
                <tr  class="text-item tdBorder">
                    <td ><span ><?php echo date('Y-m-d',$v['createTime'])?></span></td>
                    <td ><span ><?php echo $v['type']==1?'玩家奖励 ('.$v['roleName'].')':'区服奖励';?></span></td>
                    <td ><span ><?php echo $v['serverId'];?></span></td>
                    <td ><span ><?php echo  $v['title'];?></span></td>
                    <td ><span ><?php echo $v['content'];?></span></td>
                    <td style="width: 380px"><span ><?php echo $v['pushContent'];?></span></td>
                    <td  class="notSLH" >
                        <div>
                            <a class="btn" href="#" onclick="rewardCheck(<?php echo $v['id']?>,1)">通过</a>
                            <a class="btn" href="#"  onclick="rewardCheck(<?php echo $v['id']?>,2)">作废</a>
                        </div>
                    </td>
                </tr>
                <?php }?>
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
    //type 1-通过 2-作废
    function rewardCheck(id,type){
        if(confirm('确定进行该操作')){
            if(type != 1){
                type = -1;
            }
            $.post('/content/gm/reward-check',{id:id,status:type},function(e){
                alert(e.message);
                if(e.code ==1){
                    window.location.reload();
                }
            },'json');
        }
    }
</script>