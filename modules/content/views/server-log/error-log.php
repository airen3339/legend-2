<script type="text/javascript" src="/My97DatePicker/WdatePicker.js"></script>
<div class="span10" id="datacontent">
    <ul class="breadcrumb">
        <li><a href="/content/server-log/index">客户端日志</a> <span class="divider">/</span></li>
        <li class="active">报错日志</li>
    </ul>
    <ul class="nav">
    </ul>
    <form action="/content/server-log/error-log" method="get" class="form-horizontal">
        <table class="table">
            <tr>
            </tr>
        </table>
    </form>
    <form action="/content/server-log/error-log" method="post">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>ID</th>
                <th style="width: 460px;">报错内容</th>
                <th>记录时间</th>
                <th >操作</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach($data as $kss => $v) {
                ?>
                <tr  class="text-item tdBorder">
                    <td style="width: 30px;"><span ><?php echo $v['id']?></span></td>
                    <td ><span><?php echo $v['content']?></span></td>
                    <td ><span ><?php echo date('Y-m-d H:i',$v['createTime'])?></span></td>
                    <td  class="notSLH" style="width: 130px;">
                        <a class="btn" href="/content/server-log/error-detail?id=<?php echo $v['id'] ; ?>" >详情</a>
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