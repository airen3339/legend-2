<script type="text/javascript" src="/My97DatePicker/WdatePicker.js"></script>
<div class="span10" id="datacontent">
    <ul class="breadcrumb">
        <li><a href="/content/service/index">客服模块</a> <span class="divider">/</span></li>
        <li class="active">用户反馈</li>
    </ul>
    <ul class="nav">

    </ul>
    <form action="/content/service/role-feedback" method="get" class="form-horizontal">
        <table class="table">
            <tr>
                <td>
                    开始日期：
                </td>
                <td>
                    <input class="input-small Wdate" onclick="WdatePicker()" type="text" size="10" id="beginTime"  name="beginTime" value="<?php echo isset($_GET['beginTime'])?$_GET['beginTime']:''?>"/>
                </td>
                <td>
                    结束日期：
                </td>
                <td>
                    <input class="input-small Wdate" onclick="WdatePicker()"  size="10" type="text" id="endTime" name="endTime"  value="<?php echo isset($_GET['endTime'])?$_GET['endTime']:''?>"/>
                </td>
                <td>
                    区服：
                </td>
                <td>
                    <select name="serverId" style="width: 100px">
                        <option value="0">请选择</option>
                        <?php
                        foreach($servers as $k => $v){ ?>
                            <option value='<?php echo $v['id']?>' <?php if(isset($_GET['serverId']) && $_GET['serverId'] == $v['id']) echo 'selected';?>><?php echo $v['name']?></option>";
                            <?php
                        }
                        ?>
                    </select>
                </td>
                <td>
                    检索内容：
                </td>
                <td>
                    <input style="width: 100px" size="10" type="text" id="content" name="content"  value="<?php echo isset($_GET['content'])?$_GET['content']:''?>"/>
                </td>
                <td>
                    <button class="btn btn-primary" type="submit">提交</button>
                </td>
                <td></td>
            </tr>
        </table>
    </form>
    <form action="" >
        <table class="table table-hover ">
            <thead>
            <tr>
                <th width="80">反馈时间</th>
                <th width="165">角色ID</th>
                <th width="80">角色名</th>
                <th width="45">区服ID</th>
                <th width="350">反馈内容</th>
                <th width="320">回复内容</th>
                <th width="75">操作账号</th>
                <th width="80">回复时间</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach($data as $kss => $v) {
                ?>
                <tr class="tdSpan">
                    <td ><span ><?php echo $v['feedTime']?></span></td>
                    <td ><span><?php echo $v['roleId']?></span></td>
                    <td ><span><?php echo $v['roleName']?></span></td>
                    <td ><span><?php echo $v['serverId']?></span></td>
                    <td ><span><?php echo $v['feedback']?></span></td>
                    <td><span><?php echo $v['replyContent']?$v['replyContent']:"<input type='text' style='margin: 0 6px' value='' />&nbsp;<a href='#' class='btn' onclick='replyContent(this,".$v['id'].")' >回复</a>"?></span></td>
                    <td ><span id="replyName<?php echo $v['id'];?>"><?php echo $v['replyName']?></span></td>
                    <td ><span id="replyTime<?php echo $v['id'];?>"><?php echo $v['replyTime']?></span></td>
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

    function alterStatus(id,type){//type 1-上线状态  0-离线状态
        $.post('/content/api/alter-status',{id:id,type:type},function(e){
            console.log(e);
            alert(e.message);
            if(e.code ==1){
                window.location.reload();
            }
        },'json')
    }
    function replyContent(_this,id){
        if(confirm('确定回复并发送邮件吗？')){
            var val = $(_this).siblings("input").val();
            if(val){
                $.post('/content/api/feedback-reply',{id:id,reply:val},function(e){
                    alert(e.message);
                    if(e.code==1){//修改对应的信息
                        $(_this).parents("span").html(val);//回复内容
                        var nameId = '#replyName'+id;
                        var timeId = '#replyTime'+id;
                        $(nameId).html(e.replyName);//回复人
                        $(timeId).html(e.replyTime);//回复时间
                    }
                },'json')
            }else{
                alert('请输入对应的回复内容');return false;
            }
        }
    }
</script>