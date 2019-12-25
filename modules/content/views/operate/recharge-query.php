<script type="text/javascript" src="/My97DatePicker/WdatePicker.js"></script>
<div class="span10" id="datacontent">
    <ul class="breadcrumb">
        <li><a href="/content/operate/index">运营数据</a> <span class="divider">/</span></li>
        <li class="active">充值查询</li>
    </ul>
    <ul class="nav">
    </ul>
    <form action="/content/operate/recharge-query" method="get" class="form-horizontal">
        <table class="table">
            <tr>
                <td>
                    开始日期：
                </td>
                <td>
                    <input class="input-small Wdate" autocomplete="off" onclick="WdatePicker()" type="text" size="10" id="beginTime"  name="beginTime" value="<?php echo isset($_GET['beginTime'])?$_GET['beginTime']:''?>"/>
                </td>
                <td>
                    结束日期：
                </td>
                <td>
                    <input class="input-small Wdate" autocomplete="off" onclick="WdatePicker()"  size="10" type="text" id="endTime" name="endTime"  value="<?php echo isset($_GET['endTime'])?$_GET['endTime']:''?>"/>
                </td>
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
                    充值类型：
                </td>
                <td>
                    <select name="payType">
                        <option value="0">请选择</option>
                        <option value='1' <?php if(isset($_GET['payType']) && $_GET['payType'] == 1) echo 'selected';?>>支付宝</option>
                        <option value='2 <?php if(isset($_GET['payType']) && $_GET['payType'] == 2) echo 'selected';?>'>微信</option>
                    </select>
                </td>
                <td>
                    <button class="btn btn-primary" type="submit">查询</button>
                </td>
                <td></td>
            </tr>
        </table>
    </form>
    <form action="/content/operate/recharge-query" method="post">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>日期</th>
                <th >充值次数</th>
                <th >充值金额</th>
            </tr>
            </thead>
            <tbody>
            <tr  class="text-item tdPad">
                <td ><span >数据合计</span></td>
                <td ><span ><?php echo $totalCount;?></span></td>
                <td ><span ><?php echo $totalMoney;?></span></td>
            </tr>
            <?php
            foreach($data as $kss => $v) {
                ?>
                <tr  class="text-item tdPad">
                    <td ><span ><?php echo $v['date']?></span></td>
                    <td ><span ><?php echo $v['count']?></span></td>
                    <td ><span ><?php echo $v['recharge']?></span></td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </form>
</div>