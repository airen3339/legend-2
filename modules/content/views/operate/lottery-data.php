<script type="text/javascript" src="/My97DatePicker/WdatePicker.js"></script>
<div class="span10" id="datacontent">
    <ul class="breadcrumb">
        <li><a href="/content/operate/index">运营数据</a> <span class="divider">/</span></li>
        <li class="active">时来运转奖池数据</li>
    </ul>
    <ul class="nav">
    </ul>
    <form action="/content/operate/lottery-data" method="get" class="form-horizontal">
        <table class="table">
            <tr>
                <td width="100">
                    日期：
                </td>
                <td>
                    <input class="input-small Wdate" autocomplete="off" onclick="WdatePicker()" type="text" size="10" id="beginTime"  name="beginTime" value="<?php echo isset($_GET['beginTime'])?$_GET['beginTime']:''?>"/>
                </td>
<!--                <td>-->
<!--                    结束日期：-->
<!--                </td>-->
<!--                <td>-->
<!--                    <input class="input-small Wdate" autocomplete="off" onclick="WdatePicker()"  size="10" type="text" id="endTime" name="endTime"  value="--><?php //echo isset($_GET['endTime'])?$_GET['endTime']:''?><!--"/>-->
<!--                </td>-->
                <td style="float: right">
                    <button class="btn" type="submit">查询</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="#" class="btn" onclick="lotteryExcel()">导出</a>
                </td>
                <td></td>
            </tr>
        </table>
    </form>
    <form action="/content/operate/lottery-data" method="post">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>期数</th>
                <th>开奖前奖池元宝数</th>
                <th>下注元宝数</th>
                <th>开奖前奖池元宝数+下注元宝数（抽水后）</th>
                <th>本期赔出的元宝数</th>
                <th>本期奖池剩余元宝数</th>
                <th>日期</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach($data as $kss => $v) {
                ?>
                <tr  class="text-item tdPad">
                    <td ><span ><?php echo $v['periods']?></span></td>
                    <td ><span ><?php echo $v['data1']?></span></td>
                    <td ><span ><?php echo $v['data2']?></span></td>
                    <td ><span ><?php echo $v['data3']?></span></td>
                    <td ><span ><?php echo $v['data4']?></span></td>
                    <td ><span ><?php echo $v['data5']?></span></td>
                    <td ><span><?php echo $v['times']?></span></td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </form>
    <div class="pagination pagination-right">
        <span style="font-size: 17px;position: relative;bottom: 7px;">共<?php echo $count;?>条&nbsp;</span>
        <?php use yii\widgets\LinkPager;
        echo LinkPager::widget([
            'pagination' => $page,
        ])?>
    </div>
</div>
<script>
    function lotteryExcel(){
        var date = $('#beginTime').val();
        if(!date){
            alert('请选择日期');return false;
        }
        if(confirm('确定导出吗？')){
            window.location.href = '/content/api/lottery-excel?date='+date;
        }else{
            return false;
        }
    }
</script>