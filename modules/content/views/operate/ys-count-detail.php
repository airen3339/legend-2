<script type="text/javascript" src="/My97DatePicker/WdatePicker.js"></script>
<div class="span10" id="datacontent">
    <ul class="breadcrumb">
        <li><a href="/content/operate/index">运营数据</a> <span class="divider">/</span></li>
        <li class="active"><?php if($type ==1)echo '赠送';else echo '收入';?>元宝详情</li>
    </ul>
    <ul class="nav">
    </ul>
    <form action="/content/operate/ys-count-detail" method="get" class="form-horizontal">
        <table class="table">
            <tr>
                <td></td>
            </tr>
        </table>
    </form>
    <form action="/content/operate/ys-count-detail" method="post">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>角色账号</th>
                <th>区服</th>
                <th>roleId</th>
                <th>元宝数</th>
                <th>详情</th>
                <th>时间</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach($data as $kss => $v) {
                ?>
                <tr class="text-item tdBorder">
                    <td ><span ><?php echo $userId?></span></td>
                    <td ><span ><?php echo $v['serverId']?></span></td>
                    <td ><span ><?php echo $v['roleId']?></span></td>
                    <td ><span ><?php echo $v['money']?></span></td>
                    <td width="300px"><span ><?php echo $v['remark']?></span></td>
                    <td ><span ><?php echo $v['dateTime']?></span></td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </form>
    <div class="pagination pagination-right" style="margin: 10px !important;">
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
</script>