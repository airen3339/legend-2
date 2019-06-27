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
            </tr>
        </table>
    </form>
    <form action="/content/gm/notice-query" method="post">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>发布时间</th>
                <th>截止时间</th>
                <th>邮件标题</th>
                <th>状态</th>
                <th >操作</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach($data as $kss => $v) {
                ?>
                <tr  class="text-item">
                    <td ><span style="width: 80px; "><?php echo $v['id']?></span></td>
                    <td ><span style="width: 80px; "><?php echo $v['name']?></span></td>
                    <td ><span style="width: 80px; "><?php echo $v['createPower']==1?'有':'无'?></span></td>
                    <td ><span style="width: 80px; "><?php echo $v['catalog']?></span></td>
                    <td ><span style="width: 80px; "><?php echo $v['catalog']?></span></td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </form>
</div>