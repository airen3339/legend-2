<script type="text/javascript" src="/My97DatePicker/WdatePicker.js"></script>
<div class="span10" id="datacontent">
    <ul class="breadcrumb">
        <li><a href="/content/operate/index">运营数据</a> <span class="divider">/</span></li>
        <li class="active">留存数据</li>
    </ul>
    <ul class="nav">
    </ul>
    <form action="/content/operate/retain-data" method="get" class="form-horizontal">
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
                    渠道：
                </td>
                <td>
                    <select name="channel">
                        <option value="99">请选择</option>
                        <?php
                        foreach($channel as $k => $v){ ?>
                            <option value='<?php echo $v?>' <?php if(isset($_GET['channel']) && $_GET['channel'] == $v) echo 'selected';?>><?php echo $v?></option>";
                            <?php
                        }
                        ?>
                    </select>
                </td>
                <td>
                    <button class="btn btn-primary" type="submit">提交</button>
                </td>
                <td></td>
            </tr>
        </table>
    </form>
    <form action="/content/operate/retain-data" method="post">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>日期</th>
                <th>新增账号登录数</th>
                <th >账号DAU</th>
                <th >老用户</th>
                <th >2日</th>
                <th >3日</th>
                <th >5日</th>
                <th >7日</th>
                <th >15日</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach($data as $kss => $v) {
                ?>
                <tr  class="text-item tdPad">
                    <td ><span style="width: 80px; "><?php echo $v['date']?></span></td>
                    <td ><span style="width: 80px; "><?php echo $v['total']?></span></td>
                    <td ><span style="width: 80px; "><?php echo $v['accountDau']?></span></td>
                    <td ><span style="width: 80px; "><?php echo $v['oldUser']?></span></td>
                    <td ><span style="width: 80px; "><?php echo $v['two']?></span></td>
                    <td ><span style="width: 80px; "><?php echo $v['three']?></span></td>
                    <td ><span style="width: 80px; "><?php echo $v['five']?></span></td>
                    <td ><span style="width: 80px; "><?php echo $v['seven']?></span></td>
                    <td ><span style="width: 80px; "><?php echo $v['fifteen']?></span></td>
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
        location.href = '/content/operate/retain-data?page='+page;
    }
</script>