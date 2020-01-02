<script type="text/javascript" src="/My97DatePicker/WdatePicker.js"></script>
<div class="span10" id="datacontent">
    <ul class="breadcrumb">
        <li><a href="/content/player/index">玩家相关</a> <span class="divider">/</span></li>
        <li class="active">日志查询</li>
    </ul>
    <ul class="nav">
    </ul>
    <form action="/content/player/log-query" method="get" class="form-horizontal">
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
                    角色名：
                </td>
                <td>
                    <input  style="height: 20px;" type="text"  id="name"  name="name" value="<?php echo isset($_GET['name'])?$_GET['name']:''?>"/>
                </td>
                <td>
                    RoleID：
                </td>
                <td>
                    <input style="height: 20px;"   type="text" size="10" id="uid"  name="uid" value="<?php echo isset($_GET['uid'])?$_GET['uid']:''?>"/>
                </td>

            </tr>
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
                    属性：
                </td>
                <td>
                    <select name="object">
                        <option value="1">元宝</option>
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
                    收入支出：
                </td>
                <td>
                    <select name="added">
                        <option value="99">请选择</option>
                        <option value="1" <?php if(isset($_GET['added']) && $_GET['added'] == 1) echo 'selected';?>>收入</option>
                        <option value="0" <?php if(isset($_GET['added']) && $_GET['added'] == 0) echo 'selected';?>>支出</option>
                    </select>
                </td>
                <td>
                    <button class="btn btn-primary" type="submit">查询</button>
                </td>
                <td></td>
            </tr>
        </table>
    </form>
    <form action="/content/player/log-query" method="post">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>ID</th>
                <th>角色ID</th>
                <th>日期</th>
                <th>区服</th>
                <th>类型</th>
                <th>数量</th>
                <th>收入支出</th>
                <th>说明</th>
                <th>当前元宝</th>
                <th>历史元宝</th>
                <th>操作时间</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach($data as $kss => $v) {
                ?>
                <tr  class="text-item tdBorder">
                    <td ><span><?php echo $v['id']?></span></td>
                    <td ><span><?php echo $v['roleId']?></span></td>
                    <td ><span ><?php echo isset($v['type'])?$v['date']:date('Y-m-d H:i:s',$v['createTime'])?></span></td>
                    <td ><span ><?php echo isset($v['type'])?$v['serverId']:$v['server_id']?></span></td>
                    <td ><span ><?php echo $v['typeStr']?></span></td>
                    <td ><span ><?php echo $v['money']?></span></td>
                    <td ><span ><?php echo isset($v['type'])?($v['added']==1?'收入':'支出'):'收入'?></span></td>
                    <td style="width: 300px; "><span ><?php echo isset($v['remark'])?$v['remark']:'元宝充值'?></span></td>
                    <td ><span ><?php echo isset($v['upIngot'])?$v['upIngot']:''?></span></td>
                    <td ><span ><?php echo isset($v['History'])?$v['History']:''?></span></td>
                    <td ><span ><?php echo isset($v['dateTime'])?$v['dateTime']:date("Y-m-d",$v['createTime'])?></span></td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </form>
    <div class="pagination pagination-right">
        <span style="font-size: 17px;position: relative;bottom: 7px;">共<?php echo $count;?>条&nbsp;</span>
<!--        --><?php //if($count > 200){?>
<!--            <span style="font-size: 17px;position: relative;bottom: 5px;">-->
<!--            <a onclick="jumpPage()">Go</a>&nbsp;-->
<!--            <input type="text" style="width: 20px;height: 18px;" id="jumpPage">&nbsp;页-->
<!--        </span>-->
<!--        --><?php //}?>
        <?php use yii\widgets\LinkPager;
        echo LinkPager::widget([
            'pagination' => $page,
        ])?>
    </div>
</div>