<script type="text/javascript" src="/My97DatePicker/WdatePicker.js"></script>
<div class="span10" id="datacontent">
    <ul class="breadcrumb">
        <li><a href="/content/gift/index">礼包管理</a> <span class="divider">/</span></li>
        <li class="active">激活码列表</li>
    </ul>
    <ul class="nav">
    </ul>
    <form action="/content/gift/code-list" method="get" class="form-horizontal">
        <table class="table">
            <tr>
            </tr>
        </table>
    </form>
    <form action="/content/gift/code-list" method="post">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>配置时间</th>
                <th>平台</th>
                <th>礼包名称</th>
                <th>礼包内容</th>
                <th>礼包价值</th>
                <th>备注</th>
                <th>生成个数</th>
                <th >状态</th>
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
                    <td ><span style="width: 80px; "><?php echo $v['catalog']?></span></td>
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