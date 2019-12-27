<script type="text/javascript" src="/My97DatePicker/WdatePicker.js"></script>
<div class="span10" id="datacontent">
    <ul class="breadcrumb">
        <li><a href="/content/gm/index">GM工具</a> <span class="divider">/</span></li>
        <li class="active">禁言封号</li>
    </ul>
    <ul class="nav">

        <a class="btn btn-primary" style="width: 58px;" href="/content/gm/forbidden-add">禁言封号</a><br/>
    </ul>
    <form action="/content/gm/forbidden" method="get" class="form-horizontal">
        <table class="table">
            <tr>
                <td width="70px">
                    账号：
                </td>
                <td>
                    <input style="width: 140px;" size="10" type="text" id="userId" name="userId"  value="<?php echo isset($_GET['userId'])?$_GET['userId']:''?>"/>
                </td>
                <td>
                    角色名：
                </td>
                <td>
                    <input  style="height: 20px;" type="text"  id="name"  name="name" value="<?php echo isset($_GET['name'])?$_GET['name']:''?>"/>
                </td>
                <td>
                    RoleID：
                </td>
                <td>
                    <input style="height: 20px" type="text" size="10" id="roleId" onkeyup="value = value.replace(/[^0-9]/g,'')"  name="roleId" value="<?php echo isset($_GET['roleId'])?$_GET['roleId']:''?>"/>
                </td>
                <td>
                    操作类型：
                </td>
                <td>
                    <select name="type" id="type" >
                        <option value=0>请选择</option>
                        <option value="1" <?php if(isset($_GET['type']) && $_GET['type'] ==1)echo 'selected';?>>账号禁言</option>
                        <option value="2" <?php if(isset($_GET['type']) && $_GET['type'] ==2)echo 'selected';?>>账号封号</option>
                        <option value="3" <?php if(isset($_GET['type']) && $_GET['type'] ==3)echo 'selected';?>>账号禁言解封</option>
                        <option value="4" <?php if(isset($_GET['type']) && $_GET['type'] ==4)echo 'selected';?>>账号封号解封</option>
                    </select>
                </td>
                <td style="float: right">
                    <button class="btn btn-primary" type="submit">查询</button>
                </td>
                <td></td>
            </tr>
        </table>
    </form>
    <form action="" >
        <table class="table table-hover ">
            <thead>
            <tr>
                <th>账号</th>
                <th>操作</th>
                <th>操作说明</th>
                <th>操作者</th>
                <th>操作时间</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach($data as $kss => $v) {
                ?>
                <tr class="tdSpan tdBorder">
                    <td ><span><?php echo $v['userId']?></span></td>
                    <td ><span><?php echo $v['typeStr']?></span></td>
                    <td ><span><?php echo $v['remark']?></span></td>
                    <td ><span><?php echo $v['createName']?></span></td>
                    <td ><span><?php echo $v['createTime']?></span></td>
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

    function saveYinShang(site){
        var ingotStr = '#rank'+site;
        var serverStr = '#ser'+site;
        var roleStr = '#role'+site;
        var roleId = $(roleStr).val();
        console.log(roleStr,roleId);
        if(confirm('确定保存改商人信息？')){
            var ingot = $(ingotStr).val();
            var worldId = $(serverStr).val();
            $.post('/content/api/merchant-order',{roleId:roleId,ingot:ingot,worldId:worldId},function(e){
                alert(e.message);
                // if(e.code !=1){//去掉添加的联系信息
                // }
                window.location.reload();
            },'json')
        }
    }
</script>