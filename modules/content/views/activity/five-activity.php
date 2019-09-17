<script type="text/javascript" src="/My97DatePicker/WdatePicker.js"></script>
<div class="span10" id="datacontent">
    <ul class="breadcrumb">
        <li><a href="/content/activity/activity-push-list">活动管理</a> <span class="divider">/</span></li>
        <li class="active">五行运势活动列表</li>
    </ul>
    <ul class="nav">
        <li class="dropdown pull-right">
            <a class="dropdown-toggle"
               href="/content/activity/five-activity-add">添加活动</a>
        </li>
    </ul>
    <form action="/content/activity/five-activity" method="get" class="form-horizontal">
        <table class="table">
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
                    <button class="btn btn-primary" type="submit">提交</button>
                </td>
            </tr>
        </table>
    </form>
    <form action="/content/activity/five-activity" method="post">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>ID</th>
                <th>区服</th>
                <th>开始时间</th>
                <th>结束时间</th>
                <th>道具信息</th>
                <th>操作者</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
                <?php
                    foreach($data as $k => $v){
                ?>
                <tr  class="text-item">
                    <td ><span style="width: 80px; "><?php echo $v['id']?></span></td>
                    <td ><span style="width: 80px; "><?php echo $v['serverId']?></span></td>
                    <td ><span style="width: 80px; "><?php echo $v['beginTime']?></span></td>
                    <td ><span style="width: 80px; "><?php echo $v['endTime']?></span></td>
                    <td ><span><?php echo $v['pushContent']?></span></td>
                    <td ><span><?php echo $v['operatorName']?></span></td>
                    <td  class="notSLH" style="width: 130px;">
                        <a class="btn btn-primary" href="/content/activity/five-activity-add?id=<?php echo $v['id'] ; ?>" >修改</a>
                        <a href='#' class="btn btn-primary" onclick="javascript:if(confirm('确定删除吗？')){location.href='/content/activity/five-activity-delete?id=<?php echo $v['id']; ?>'}" >删除</a>
                    </td>
                </tr>
                <?php
                    }
                ?>
            </tbody>
        </table>
    </form>

</div>