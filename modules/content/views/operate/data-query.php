<script type="text/javascript" src="/My97DatePicker/WdatePicker.js"></script>
<div class="span10" id="datacontent">
    <ul class="breadcrumb">
        <li><a href="/content/operate/index">运营数据</a> <span class="divider">/</span></li>
        <li class="active">数据查询</li>
    </ul>
    <ul class="nav">
    </ul>
    <form action="/content/operate/data-query" method="get" class="form-horizontal">
        <table class="table">
            <tr>
                <td>
                    开始日期：
                </td>
                <td>
                    <input class="input-small Wdate" onclick="WdatePicker()" type="text" size="10" id="beginTime"  name="beginTime" value="<?php echo isset($_GET['beginTime'])?$_GET['beginTime']:''?>"/>
                </td>
                <td>
                    结束日期：
                </td>
                <td>
                    <input class="input-small Wdate" onclick="WdatePicker()"  size="10" type="text" id="endTime" name="endTime"  value="<?php echo isset($_GET['endTime'])?$_GET['endTime']:''?>"/>
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
                    渠道：
                </td>
                <td>
                    <select name="channel">
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
    <form action="/content/operate/data-query" method="post">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>日期</th>
                <th>总注册数</th>
                <th>新增设备</th>
                <th>新增账号登录</th>
                <th >设备DAU</th>
                <th >账号DAU</th>
                <th >账号老用户</th>
                <th >付费率</th>
                <th >充值人数</th>
                <th >充值次数</th>
                <th >充值金额</th>
                <th >ARPU</th>
                <th >ARPPU</th>
                <th >新增充值人数</th>
                <th >新增充值金额</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach($data as $kss => $v) {
                ?>
                <tr  class="text-item tdPad">
                    <td ><span style="width: 80px; "><?php echo $v['date']?></span></td>
                    <td ><span style="width: 80px; "><?php echo $v['newRegister']?></span></td>
                    <td ><span style="width: 80px; "><?php echo $v['newDevice']?></span></td>
                    <td ><span style="width: 80px; "><?php echo $v['newLogin']?></span></td>
                    <td ><span style="width: 80px; "><?php echo $v['deviceDau']?></span></td>
                    <td ><span style="width: 80px; "><?php echo $v['accountDau']?></span></td>
                    <td ><span style="width: 80px; "><?php echo $v['oldUser']?></span></td>
                    <td ><span style="width: 80px; "><?php echo $v['payRate']?></span></td>
                    <td ><span style="width: 80px; "><?php echo $v['rechargeUser']?></span></td>
                    <td ><span style="width: 80px; "><?php echo $v['rechargeCount']?></span></td>
                    <td ><span style="width: 80px; "><?php echo $v['rechargeMoney']?></span></td>
                    <td ><span style="width: 80px; "><?php echo $v['arpu']?></span></td>
                    <td ><span style="width: 80px; "><?php echo $v['arppu']?></span></td>
                    <td ><span style="width: 80px; "><?php echo $v['newRechargeUser']?></span></td>
                    <td ><span style="width: 80px; "><?php echo $v['newRechargeMoney']?></span></td>
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
<script>
    function jumpPage(){
        var page = $("#jumpPage").val();
        if(isNaN(page) || page <= 0 || !page){
            alert('请输入正确的数值');
            return false;
        }
        location.href = '/content/rule/role?page='+page;
    }
</script>