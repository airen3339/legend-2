<script type="text/javascript" src="/My97DatePicker/WdatePicker.js"></script>
<div class="span10" id="datacontent">
    <ul class="breadcrumb">
        <li><a href="/content/player/index">玩家相关</a> <span class="divider">/</span></li>
        <li class="active">回调记录</li>
    </ul>
    <ul class="nav">
    </ul>
    <form action="/content/player/notify-list" method="get" class="form-horizontal">
        <table class="table">
            <tr>
                <td width="120">
                    订单号查询：
                </td>
                <td>
                    <input style="height: 20px"  type="text" size="10" id="order"  name="order" value="<?php echo isset($_GET['order'])?$_GET['order']:''?>"/>
                </td>
                <td>
                    <button class="btn btn-primary" type="submit">查询</button>
                </td>
                <td></td>
            </tr>
        </table>
    </form>
    <form action="/content/player/notify-list" method="post">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>ID</th>
                <th>订单号</th>
                <th>订单说明</th>
                <th>回调信息</th>
                <th >回调时间</th>
                <th >操作</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach($data as $kss => $v) {
                ?>
                <tr  class="text-item tdBorder">
                    <td ><span ><?php echo $v['id']?></span></td>
                    <td ><span ><?php echo $v['orderNumber']?></span></td>
                    <td ><span ><?php echo $v['remark']?></span></td>
                    <td ><span style="width: 870px; max-height: 50px !important;overflow-y: hidden !important;"><?php echo $v['notify']?></span></td>
                    <td style="width: 115px;"><span><?php echo $v['createTime']?date('Y-m-d H:i:s',$v['createTime']):''?></span></td>
                    <td ><span><a href='/content/player/notify-detail?orderId=<?php echo $v['id']?>'  class='' >回调详情</a></span></td>
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