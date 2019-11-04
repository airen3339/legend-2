<script type="text/javascript" src="/My97DatePicker/WdatePicker.js"></script>
<div class="span10" id="datacontent">
    <ul class="breadcrumb">
        <li><a href="/content/activity/index">活动管理</a> <span class="divider">/</span></li>
        <li class="active">活动类型</li>
    </ul>
    <ul class="nav">
        <li class="dropdown pull-right">
            <a class="dropdown-toggle"
               href="/content/activity/activity-type-edit">添加活动类型</a>
        </li>
    </ul>
    <form action="/content/activity/activity-type-edit" method="get" class="form-horizontal">
        <table class="table">
            <tr>

            </tr>
        </table>
    </form>
    <form action="/content/activity/activity-type-edit" method="post">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>ID</th>
                <th>名称</th>
                <th>游戏类型</th>
                <th>排序</th>
                <th>条件说明</th>
                <th>创建时间</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
                <?php
                    foreach($types as $k => $v){
                ?>
                <tr  class="text-item tdBorder">
                    <td ><span><?php echo $v['id']?></span></td>
                    <td ><span ><?php echo $v['name']?></span></td>
                    <td ><span ><?php echo $v['type']?></span></td>
                    <td ><span ><?php echo $v['rank']?></span></td>
                    <td ><span ><?php echo $v['remark']?></span></td>
                    <td ><span ><?php echo date('Y-m-d H:i:s',$v['createTime']);?></span></td>
                    <td  class="notSLH" style="width: 130px;">
                        <a class="btn " href="/content/activity/activity-type-edit?id=<?php echo $v['id'] ; ?>" >修改</a>
                        <a href='#' class="btn " onclick="javascript:if(confirm('确定删除吗？')){location.href='/content/activity/activity-type-delete?id=<?php echo $v['id']; ?>'}" >删除</a>
                    </td>
                </tr>
                <?php
                    }
                ?>
            </tbody>
        </table>
    </form>

</div>