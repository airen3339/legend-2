<script type="text/javascript" src="/My97DatePicker/WdatePicker.js"></script>
<div class="span10" id="datacontent">
    <ul class="breadcrumb">
        <li><a href="/content/operate/index">运营数据</a> <span class="divider">/</span></li>
        <li class="active">等级分布</li>
    </ul>
    <ul class="nav">
    </ul>
    <form action="/content/operate/login-online-list" method="get" class="form-horizontal">
        <table class="table">
            <tr>
                <td>
                    查询日期：
                </td>
                <td>
                    <input class="input-small Wdate" onclick="WdatePicker()" type="text" size="10" id="day"  name="day" value="<?php echo isset($_GET['beginTime'])?$_GET['beginTime']:''?>"/>
                </td>
                <td>
                    区服：
                </td>
                <td>
                    <select name="service">
                        <option value="-99">请选择</option>
                        <option value="1" <?php if(isset($_GET['createPower']) && $_GET['createPower'] == 1) echo 'selected';?>>有</option>
                        <option value="2" <?php if(isset($_GET['createPower']) && $_GET['createPower'] == 2) echo 'selected';?>>无</option>
                    </select>
                </td>
                <td>
                    <button class="btn btn-primary" type="submit">提交</button>
                </td>
                <td></td>
            </tr>
        </table>
    </form>
    <form action="/content/operate/login-online-list" method="post">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>等级</th>
                <th>当前等级用户数</th>
                <th>总用户占比</th>
                <th>等级滞留用户数</th>
                <th >等级滞留占比</th>
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