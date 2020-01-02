<script type="text/javascript" src="/My97DatePicker/WdatePicker.js"></script>
<div class="span10" id="datacontent">
    <ul class="breadcrumb">
        <li><a href="/content/player/index">运营数据</a> <span class="divider">/</span></li>
        <li class="active">货币消耗</li>
    </ul>
    <ul class="nav">
    </ul>
    <form action="/content/player/money-use" method="get" class="form-horizontal">
        <table class="table">
            <tr>
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
                    类型：
                </td>
                <td>
                    <select name="type">
                        <option value="0">请选择</option>
                        <?php
                        foreach($types as $k => $v){ ?>
                            <option value='<?php echo $v['id']?>' <?php if(isset($_GET['type']) && $_GET['type'] == $v['id']) echo 'selected';?>><?php echo $v['name']?></option>";
                            <?php
                        }
                        ?>
                    </select>
                </td>
                <td>
                    <button class="btn btn-primary" type="submit">查询</button>
                </td>
                <td></td>
            </tr>
        </table>
    </form>
    <form action="/content/player/money-use" method="post">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>区服</th>
<!--                <th>操作说明</th>-->
                <th>操作类型</th>
                <th>元宝数量</th>
                <th>收入支出</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach($data as $kss => $v) {
                ?>
                <tr  class="text-item tdBorder">
                    <td ><span ><?php echo $v['serverId']?></span></td>
                    <td ><span ><?php echo $v['remark']?></span></td>
                    <td ><span ><?php echo $v['money']?></span></td>
                    <td ><span ><?php echo $v['added']==1?'收入':'消耗'?></span></td>
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
