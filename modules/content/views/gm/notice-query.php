<script type="text/javascript" src="/My97DatePicker/WdatePicker.js"></script>
<div class="span10" id="datacontent">
    <ul class="breadcrumb">
        <li><a href="/content/gm/index">GM工具</a> <span class="divider">/</span></li>
        <li class="active">公告查询</li>
    </ul>
    <ul class="nav">
    </ul>
    <form action="/content/gm/notice-query" method="get" class="form-horizontal">
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
                        <option value='1' <?php if(isset($_GET['type']) && $_GET['type'] == 1) echo 'selected';?>>首页公告</option>";
                    </select>
                </td>
                <td>
                    <button class="btn btn-primary" type="submit">提交</button>
                </td>
            </tr>
        </table>
    </form>
    <form action="/content/gm/notice-query" method="post">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>ID</th>
                <th>发布时间</th>
                <th>截止时间</th>
                <th>公告内容</th>
                <th>公告类型</th>
                <th>操作者</th>
                <th>操作时间</th>
                <th >操作</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach($data as $kss => $v) {
                ?>
                <tr  class="text-item">
                    <td ><span style="width: 80px; "><?php echo $v['id']?></span></td>
                    <td ><span style="width: 80px; "><?php echo $v['beginTime']?></span></td>
                    <td ><span style="width: 80px; "><?php echo $v['endTime']?></span></td>
                    <td ><span style="width: 80px; "><?php echo $v['content']?></span></td>
                    <td ><span style="width: 80px; "><?php echo $v['type']==1?'首页公告':'区服公告'?></span></td>
                    <td ><span style="width: 80px; "><?php echo $v['createName']?></span></td>
                    <td ><span style="width: 80px; "><?php echo date('Y-m-d H:i',$v['createTime'])?></span></td>
                    <td  class="notSLH" style="width: 130px;">
                        <a class="btn btn-primary" href="/content/gm/<?php echo $v['type']==1?'index-notice':'' ?>?id=<?php echo $v['id'] ; ?>" >修改</a>
                        <a href='#' class="btn btn-primary" onclick="javascript:if(confirm('确定删除吗？')){location.href='/content/activity/notice-delete?id=<?php echo $v['id']; ?>'}" >删除</a>
                    </td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </form>
</div>