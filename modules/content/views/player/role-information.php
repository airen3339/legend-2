<script type="text/javascript" src="/My97DatePicker/WdatePicker.js"></script>
<div class="span10" id="datacontent">
    <ul class="breadcrumb">
        <li><a href="/content/player/index">玩家相关</a> <span class="divider">/</span></li>
        <li class="active">等级分布</li>
    </ul>
    <ul class="nav">
    </ul>
    <form action="/content/player/role-information" method="get" class="form-horizontal">
        <table class="table">
            <tr>
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
                    <input  style="height: 20px;" type="text"  id="roleId"  name="roleId" value="<?php echo isset($_GET['roleId'])?$_GET['roleId']:''?>"/>
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
                    <button class="btn btn-primary" type="submit">提交</button>
                </td>
                <td></td>
            </tr>
        </table>
    </form>
    <form action="/content/player/role-information" method="post">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>角色ID</th>
                <th>账号</th>
                <th>区服</th>
                <th>昵称</th>
                <th>渠道</th>
<!--                <th >渠道用户编号</th>-->
                <th >注册时间</th>
                <th >最后登录时间</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach($user as $kss => $v) {
                ?>
                <tr  class="text-item tdBorder">
                    <td ><span ><?php echo $v['RoleID']?></span></td>
                    <td style="width:180px"><span ></span><?php echo $v['UserID']?></span></td>
                    <td ><span ><?php echo $v['WorldID']?></span></td>
                    <td ><span ><?php echo $v['Name']?></span></td>
                    <td ><span ><?php echo $v['PackageFlag']?></span></td>
<!--                    <td ><span style="width: 80px; ">--><?php //echo '11'?><!--</span></td>-->
                    <td ><span ><?php echo $v['CreateDate']?></span></td>
                    <td ><span ><?php echo date('Y-m-d H:i:s',$v['LastLogin']);?></span></td>
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
        location.href = '/content/player/role-information?page='+page;
    }
</script>