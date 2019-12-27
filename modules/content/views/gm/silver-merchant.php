<script type="text/javascript" src="/My97DatePicker/WdatePicker.js"></script>
<div class="span10" id="datacontent">
    <ul class="breadcrumb">
        <li><a href="/content/gm/index">GM工具</a> <span class="divider">/</span></li>
        <li class="active">银商信息</li>
    </ul>
    <ul class="nav">

    </ul>
    <form action="/content/gm/silver-merchant" method="get" class="form-horizontal">
        <table class="table">
            <tr>
                <td width="70px">
                    角色账号：
                </td>
                <td>
                    <input style="width: 140px" size="10" type="text" id="userId" name="userId"  value="<?php echo isset($_GET['userId'])?$_GET['userId']:''?>"/>
                </td>
                <td style="float: right">
                    <button class="btn btn-primary" type="submit">查询</button>
                </td>
                <td></td>
            </tr>
        </table>
    </form>
    <form action="" >
        <table class="table table-hover ">
            <thead>
            <tr>
                <th>角色账号</th>
                <th >联系方式</th>
                <th >可进区服</th>
                <th >操作</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach($data as $kss => $v) {
                ?>
                <tr class="tdSpan1 tdBorder">
                    <td ><span><?php echo $v['UserID']?></span></td>
                    <td><span><?php echo $v['contact']?></span></td>
                    <td><span><?php echo $v['enterWorldID']?></span></td>
                    <td  class="notSLH" style="width: 130px;">
                        &nbsp;&nbsp;<a href='/content/gm/silver-merchant-add?userId=<?php echo $v['UserID'];?>'  class='btn'>修改</a>
                    </td>
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
