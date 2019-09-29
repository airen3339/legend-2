<script type="text/javascript" src="/My97DatePicker/WdatePicker.js"></script>
<div class="span10" id="datacontent">
    <ul class="breadcrumb">
        <li><a href="/content/activity/activity-push-list">活动管理</a> <span class="divider">/</span></li>
        <li class="active">活动推送列表</li>
    </ul>
    <ul class="nav">
        <li class="dropdown pull-right">
            <a class="dropdown-toggle"
               href="/content/activity/activity-push">添加活动推送</a>
        </li>
    </ul>
    <form action="/content/activity/activity-push-list" method="get" class="form-horizontal">
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
                    类型：
                </td>
                <td>
                    <select name="type">
                        <option value="0">请选择</option>
                        <option value='1' <?php if(isset($_GET['type']) && $_GET['type'] == 1) echo 'selected';?>>每日单充</option>";
                        <option value='2' <?php if(isset($_GET['type']) && $_GET['type'] == 2) echo 'selected';?>>累计消费</option>";
                        <option value='3' <?php if(isset($_GET['type']) && $_GET['type'] == 3) echo 'selected';?>>五行运势</option>";
                    </select>
                </td>
                <td>
                    <button class="btn btn-primary" type="submit">提交</button>
                </td>
            </tr>
        </table>
    </form>
    <form action="/content/activity/activity-push-list" method="post">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>ID</th>
                <th>区服</th>
                <th>说明</th>
<!--                <th>类型</th>-->
                <th>开始时间</th>
                <th>结束时间</th>
                <th>发放物品</th>
                <th>操作者</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
                <?php
                    foreach($data as $k => $v){
                ?>
                <tr  class="tdBorder">
                    <td style="width: 20px; "><span ><?php echo $v['id']?></span></td>
                    <td style="width: 40px; "><span ><?php echo $v['serverId']?></span></td>
                    <td ><span ><?php echo $v['remark']?></span></td>
<!--                    <td ><span >--><?php //echo $v['type']?><!--</span></td>-->
                    <td ><span ><?php echo $v['beginTime']?></span></td>
                    <td ><span ><?php echo $v['endTime']?></span></td>
                    <td style="width: 870px; max-height: 50px !important;overflow-y: hidden !important;" title="<?php echo $v['pushContent']?>"><span><?php echo $v['pushContent']?></span></td>
                    <td style="width: 40px; "><span><?php echo $v['operatorName']?></span></td>
                    <td  class="notSLH" style="width: 130px;">
                        <a class="btn " href="/content/activity/activity-push-edit?id=<?php echo $v['id'] ; ?>" >修改</a>
                        <a href='#' class="btn " onclick="javascript:if(confirm('确定删除吗？')){location.href='/content/activity/activity-push-delete?id=<?php echo $v['id']; ?>'}" >删除</a>
                    </td>
                </tr>
                <?php
                    }
                ?>
            </tbody>
        </table>
    </form>

</div>