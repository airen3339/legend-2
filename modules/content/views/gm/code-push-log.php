<script type="text/javascript" src="/My97DatePicker/WdatePicker.js"></script>
<div class="span10" id="datacontent">
    <ul class="breadcrumb">
        <li><a href="/content/gm/index">GM工具</a> <span class="divider">/</span></li>
        <li class="active">代码推送查询</li>
    </ul>
    <ul class="nav">
    </ul>
    <form action="/content/gm/code-push-log" method="get" class="form-horizontal">
        <table class="table">
            <tr>
                <td>
                    区服：
                </td>
                <td>
                    <select name="server">
                        <option value="0">全服</option>
                        <?php
                        foreach($servers as $k => $v){ ?>
                            <option value='<?php echo $v['id']?>' <?php if(isset($_GET['server']) && $_GET['server'] == $v['id']) echo 'selected';?>><?php echo $v['name']?></option>
                            <?php
                        }
                        ?>
                    </select>
                </td>
                <td>
                    <button class="btn btn-primary" type="submit">查询</button>
                </td>
            </tr>
        </table>
    </form>
    <form action="/content/gm/code-push-log" method="post">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>ID</th>
                <th>区服</th>
                <th>代码内容</th>
                <th>操作者</th>
                <th>操作时间</th>
<!--                <th >操作</th>-->
            </tr>
            </thead>
            <tbody>
            <?php
            foreach($data as $kss => $v) {
                ?>
                <tr  class="text-item tdBorder">
                    <td style="width: 30px;"><span ><?php echo $v['id']?></span></td>
                    <td style="width: 120px;"><span ><?php echo ($v['object']?$v['object']:'全').'服'?></span></td>
                    <td style="width: 500px;"><span style="line-height: 1.4em !important;word-break: break-word;" ><?php echo $v['content']?></span></td>
                    <td ><span><?php echo $v['createName']?></span></td>
                    <td ><span ><?php echo $v['createTime']?></span></td>
<!--                    <td  class="notSLH" style="width: 130px;">-->
<!--                        <a class="btn" href="/content/gm/--><?php //echo $v['type']==1?'index-notice':'' ?><!--?id=--><?php //echo $v['id'] ; ?><!--" >修改</a>-->
<!--                    </td>-->
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