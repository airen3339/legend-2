<script type="text/javascript" src="/My97DatePicker/WdatePicker.js"></script>
<div class="span10" id="datacontent">
    <ul class="breadcrumb">
        <li><a href="/content/gm/index">GM工具</a> <span class="divider">/</span></li>
        <li class="active">银商信息</li>
    </ul>
    <ul class="nav">

    </ul>
    <form action="/content/service/role-feedback" method="get" class="form-horizontal">
        <table class="table">
            <tr>
                <td>
                    检索内容：
                </td>
                <td>
                    <input style="width: 100px" size="10" type="text" id="name" name="name"  value="<?php echo isset($_GET['name'])?$_GET['name']:''?>"/>
                </td>
                <td>
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
                <th>角色ID</th>
                <th>角色名</th>
                <th >元宝数</th>
                <th >联系方式</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach($data as $kss => $v) {
                ?>
                <tr class="tdSpan tdBorder">
                    <td ><span><?php echo $v['roleID']?></span></td>
                    <td ><span><?php echo $v['Name']?></span></td>
                    <td ><span><?php echo $v['Ingot']?></span></td>
                    <td><span><input type='text' value='<?php echo $v['contact']?>' />&nbsp;<a href='#' class='btn' onclick='addContact(this,<?php echo $v['roleID']?>)' >添加</a></span></td>
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

    function addContact(_this,roleId){
        if(confirm('确定添加该联系方式？')){
            var val = $(_this).siblings("input").val();
            if(val){
                $.post('/content/api/add-contact',{roleId:roleId,contact:val},function(e){
                    alert(e.message);
                    if(e.code !=1){//去掉添加的联系信息
                        $(_this).siblings("input").val('');
                    }
                },'json')
            }else{
                alert('请输入对应的联系方式');return false;
            }
        }
    }
</script>