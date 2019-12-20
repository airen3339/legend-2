<script type="text/javascript" src="/My97DatePicker/WdatePicker.js"></script>
<div class="span10" id="datacontent">
    <ul class="breadcrumb">
        <li><a href="/content/operate/index">运营数据</a> <span class="divider">/</span></li>
        <li class="active">商人赠送数据</li>
    </ul>
    <ul class="nav">
    </ul>
    <form action="/content/operate/ys-count" method="get" class="form-horizontal">
        <table class="table">
            <tr>
                <td>
                    角色账号：
                </td>
                <td>
                    <input  style="height: 20px;width: 92px;"  type="text"  id="userId"  name="userId" value="<?php echo isset($_GET['userId'])?$_GET['userId']:''?>"/>
                </td>
                <td>
                    角色名：
                </td>
                <td>
                    <input style="height: 20px" type="text" size="10" id="name"  name="name" value="<?php echo isset($_GET['name'])?$_GET['name']:''?>"/>
                </td>
                <td>
                    <button class="btn btn-primary" type="submit">查询</button>
                </td>
                <td></td>
            </tr>
        </table>
    </form>
    <form action="/content/operate/ys-count" method="post">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>角色账号</th>
                <th>角色名</th>
                <th>当前元宝数</th>
                <th>赠送元宝</th>
                <th>收入元宝</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach($data as $kss => $v) {
                ?>
                <tr class="text-item tdBorder">
                    <td ><span ><?php echo $v['UserID']?></span></td>
                    <td ><span ><?php echo $v['Name']?></span></td>
                    <td ><span ><?php echo $v['Ingot']?></span></td>
                    <td >
                        <span ><?php echo $v['out']?></span>
                        <?php if($v['out'] > 0){?>
                            <a style="float: right; position: relative;right: 10px;" href="/content/operate/ys-count-detail?type=1&roleId=<?php echo $v['RoleID']?>">赠送详情</a>
                        <?php }?>
                    </td>
                    <td >
                        <span ><?php echo $v['in']?></span>
                        <?php if($v['in'] > 0){?>
                            <a style="float: right; position: relative;right: 10px;" href="/content/operate/ys-count-detail?type=2&roleId=<?php echo $v['RoleID']?>">收入详情</a>
                        <?php }?>
                    </td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </form>
    <div class="pagination pagination-right" style="margin: 10px !important;">
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