<script type="text/javascript" src="/My97DatePicker/WdatePicker.js"></script>
<div class="span10" id="datacontent">
    <ul class="breadcrumb">
        <li><a href="/content/gm/index">GM工具</a> <span class="divider">/</span></li>
        <li class="active">发奖操作记录</li>
    </ul>
    <ul class="nav">
    </ul>
    <form action="/content/gm/reward-record" method="get" class="form-horizontal">
        <table class="table">
            <tr>
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
                    <select name="server" id="server" style="width: 105px;">
                        <option value="0">请选择</option>
                        <?php
                        foreach($servers as $k => $v){
                            echo "<option value='{$v['id']}'>{$v['name']}</option>";
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
    <form action="/content/gm/reward-record" method="post">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>操作时间</th>
                <th>奖励类型</th>
                <th>区服</th>
                <th >邮件标题</th>
                <th >邮件说明</th>
                <th >奖励内容</th>
                <th >操作者</th>
<!--                <th >处理人</th>-->
            </tr>
            </thead>
            <tbody>
                <?php foreach($data as $k => $v){?>
                <tr  class="text-item">
                    <td ><span style="width: 80px; "><?php echo date('Y-m-d',$v['createTime'])?></span></td>
                    <td ><span style="width: 80px; "><?php echo $v['type']==1?'玩家奖励':'区服奖励';?></span></td>
                    <td ><span style="width: 80px; "><?php echo $v['serverId'];?></span></td>
                    <td ><span style="width: 80px; "><?php echo  $v['title'];?></span></td>
                    <td ><span style="width: 80px; "><?php echo $v['content'];?></span></td>
                    <td ><span style="width: 80px; "><?php echo $v['pushContent'];?></span></td>
                    <td ><span style="width: 80px; "><?php echo $v['adminName'];?></span></td>
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