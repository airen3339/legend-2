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
                    类型：
                </td>
                <td>
                    <select name="type">
                        <option value="0">请选择</option>
                        <option value="1" <?php if(isset($_GET['type']) && $_GET['type'] == 1) echo 'selected';?>>活动推送</option>
                        <option value="2" <?php if(isset($_GET['type']) && $_GET['type'] == 2) echo 'selected';?>>五行运势</option>
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
                <tr  class="text-item">
                    <td ><span style="width: 80px; "><?php echo $v['id']?></span></td>
                    <td ><span style="width: 80px; "><?php echo $v['operator']?></span></td>
                    <td ><span style="width: 80px; "><?php echo $v['operatorId']?></span></td>
                    <td ><span style="width: 80px; "><?php echo $v['remark']?></span></td>
                    <td ><span style="width: 80px; "><?php echo $v['activityId']?></span></td>
                    <td ><span style="width: 80px; "><?php echo $v['type']==1?'活动推送':'五行运势'?></span></td>
                    <td ><span style="width: 80px; "><?php echo date('Y-m-d H:i',$v['createTime'])?></span></td>
                </tr>
                <?php
                    }
                ?>
            </tbody>
        </table>
    </form>

</div>