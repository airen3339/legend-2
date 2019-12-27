<script type="text/javascript" src="/My97DatePicker/WdatePicker.js"></script>
<div class="span10" id="datacontent">
    <ul class="breadcrumb">
        <li><a href="/content/gm/index">GM工具</a> <span class="divider">/</span></li>
        <li class="active">商人排名</li>
    </ul>
    <ul class="nav">

    </ul>
    <form action="/content/gm/merchant-order" method="get" class="form-horizontal">
        <table class="table">
            <tr>
                <td width="70px">
                    账号：
                </td>
                <td>
                    <input style="width: 140px;" size="10" type="text" id="userId" name="userId"  value="<?php echo isset($_GET['userId'])?$_GET['userId']:''?>"/>
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
                    <input style="height: 20px" type="text" size="10" id="roleId"  name="roleId" value="<?php echo isset($_GET['roleId'])?$_GET['roleId']:''?>"/>
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
                <th>账号</th>
                <th>角色名</th>
                <th>角色ID</th>
                <th>排名值</th>
                <th>区服</th>
                <th >操作</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach($data as $kss => $v) {
                ?>
                <tr class="tdSpan tdBorder">
                    <td ><span><?php echo $v['name']?></span></td>
                    <td ><span><?php echo $v['userId']?></span></td>
                    <td ><span><?php echo $v['RoleID']?></span></td>
                    <td ><span><?php echo $v['Ingot']?></span></td>
                    <td ><span><?php echo $v['WorldID']?></span></td>
                    <td  class="notSLH" style="width: 130px;">
                        <a href='/content/gm/merchant-order-add?roleId=<?php echo $v['RoleID']?>'  class='btn'  >修改</a>
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