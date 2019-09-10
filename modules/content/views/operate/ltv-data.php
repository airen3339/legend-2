<script type="text/javascript" src="/My97DatePicker/WdatePicker.js"></script>
<div class="span10" id="datacontent">
    <ul class="breadcrumb">
        <li><a href="/content/operate/index">运营数据</a> <span class="divider">/</span></li>
        <li class="active">LTV数据</li>
    </ul>
    <ul class="nav">
    </ul>
    <form action="/content/operate/ltv-data" method="get" class="form-horizontal">
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
                    渠道：
                </td>
                <td>
                    <select name="channel">
                        <option value="">请选择</option>
                        <option value="official" <?php if(isset($_GET['channel']) && $_GET['channel'] == 'official') echo 'selected';?>>official</option>
                        <option value="my" <?php if(isset($_GET['channel']) && $_GET['channel'] == 'my') echo 'selected';?>>my</option>
                    </select>
                </td>
                <td>
                    LTV数据：
                </td>
                <td>
                    <select name="ltv">
                        <option value="1" <?php if(isset($_GET['ltv']) && $_GET['ltv'] == 1) echo 'selected';?>>新增账号</option>
                        <option value="2" <?php if(isset($_GET['ltv']) && $_GET['ltv'] == 2) echo 'selected';?>>新增设备</option>
                    </select>
                </td>
                <td>
                    <button class="btn btn-primary" type="submit">提交</button>
                </td>
                <td></td>
            </tr>
        </table>
    </form>
    <form action="/content/operate/ltv-data" method="post">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>日期</th>
                <th>新增</th>
                <th>1日</th>
                <th>2日</th>
                <th >3日</th>
                <th >5日</th>
                <th >7日</th>
                <th >15日</th>
                <th >30日</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach($data as $kss => $v) {
                ?>
                <tr  class="text-item">
                    <td ><span style="width: 80px; "><?php echo $v['date']?></span></td>
                    <td ><span style="width: 80px; "><?php echo $v['addNum']?></span></td>
                    <td ><span style="width: 80px; "><?php echo $v['one']?></span></td>
                    <td ><span style="width: 80px; "><?php echo $v['three']?></span></td>
                    <td ><span style="width: 80px; "><?php echo $v['five']?></span></td>
                    <td ><span style="width: 80px; "><?php echo $v['seven']?></span></td>
                    <td ><span style="width: 80px; "><?php echo $v['fifteen']?></span></td>
                    <td ><span style="width: 80px; "><?php echo $v['thirty']?></span></td>
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