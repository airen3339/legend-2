<script type="text/javascript" src="/My97DatePicker/WdatePicker.js"></script>
<div class="span10" id="datacontent">
    <ul class="breadcrumb">
        <li><a href="/content/activity/activity-log">活动管理</a> <span class="divider">/</span></li>
        <li class="active">活动操作日志</li>
    </ul>
    <ul class="nav">

    </ul>
    <form action="/content/activity/activity-log" method="get" class="form-horizontal">
        <table class="table">
            <tr>
                <td>
                    操作者ID：
                </td>
                <td>
                    <input type="text" class="input-small" name="uid" value="<?php echo isset($_GET['uid'])?$_GET['uid']:''?>" />
                </td>
                <td>
                    类型：
                </td>
                <td>
                    <select name="type">
                        <option value="0">请选择</option>
                        <option value="1" <?php if(isset($_GET['type']) && $_GET['type'] == 1) echo 'selected';?>>每日单充</option>
                        <option value="2" <?php if(isset($_GET['type']) && $_GET['type'] == 2) echo 'selected';?>>累计消费</option>
                        <option value="3" <?php if(isset($_GET['type']) && $_GET['type'] == 3) echo 'selected';?>>五行运势</option>
                    </select>
                </td>
                <td>
                    <button class="btn btn-primary" type="submit">提交</button>
                </td>
            </tr>
        </table>
    </form>
    <form action="/content/activity/activity-log" method="post">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>ID</th>
                <th>操作者</th>
                <th>操作者ID</th>
                <th>操作说明</th>
                <th>活动ID</th>
                <th>活动类型</th>
                <th>操作时间</th>
            </tr>
            </thead>
            <tbody>
                <?php
                    foreach($data as $k => $v){
                ?>
                <tr  class="text-item tdBorder">
                    <td ><span ><?php echo $v['id']?></span></td>
                    <td ><span ><?php echo $v['operator']?></span></td>
                    <td ><span ><?php echo $v['operatorId']?></span></td>
                    <td ><span ><?php echo $v['remark']?></span></td>
                    <td ><span ><?php echo $v['activityId']?></span></td>
                    <td ><span ><?php echo $v['type']==1?'每日充值':($v['type']==2?'累计消费':'五行运势')?></span></td>
                    <td ><span ><?php echo date('Y-m-d H:i',$v['createTime'])?></span></td>
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