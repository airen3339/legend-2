<script type="text/javascript" src="/My97DatePicker/WdatePicker.js"></script>
<div class="span10" id="datacontent">
    <ul class="breadcrumb">
        <li><a href="/content/rule/index">权限功能</a> <span class="divider">/</span></li>
        <li class="active">角色列表</li>
    </ul>
    <ul class="nav">
        <?php if(Yii::$app->session->get('createPower') ==1){?>
        <li class="dropdown pull-right">
            <a class="dropdown-toggle"
               href="/content/rule/role-add">添加角色</a>
        </li>
        <?php }?>
    </ul>
    <form action="/content/rule/role" method="get" class="form-horizontal">
        <table class="table">
            <tr>
                <td>
                    账号角色：
                </td>
                <td>
                    <input name="name" class="input-small" size="25" type="text" value="<?php echo isset($_GET['name'])?$_GET['name']:''?>"/>
                </td>
                <td>
                    创建权限：
                </td>
                <td>
                    <select name="createPower">
                        <option value="-99">请选择</option>
                        <option value="1" <?php if(isset($_GET['createPower']) && $_GET['createPower'] == 1) echo 'selected';?>>有</option>
                        <option value="2" <?php if(isset($_GET['createPower']) && $_GET['createPower'] == 2) echo 'selected';?>>无</option>
                    </select>
                </td>
                <td>
                    <button class="btn btn-primary" type="submit">提交</button>
                </td>
                <td></td>
            </tr>
        </table>
    </form>
    <form action="/content/rule/role" method="post">
        <table class="table table-hover add_defined">
            <thead>
            <tr>
                <th>ID</th>
                <th>角色账号</th>
                <th>创建权限</th>
                <th>操作内容</th>
                <th >操作</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach($role as $kss => $v) {
                if($kss == 10){
                    break;
                }
                ?>
                <tr>
                    <td ><span style="width: 80px; "><?php echo $v['id']?></span></td>
                    <td ><span style="width: 80px; "><?php echo $v['name']?></span></td>
                    <td ><span style="width: 80px; "><?php echo $v['createPower']==1?'有':'无'?></span></td>
                    <td ><span style="width: 80px; "><?php echo $v['catalog']?></span></td>

                    <td  class="notSLH" style="width: 247px;">
                        <div>
                            <a class="btn" href="/content/rule/role-add?id=<?php echo $v['id']; ?>">修改</a>
                            <a class="btn" href="JavaScript:if(confirm('确认删除该角色账号？')){location.href='/content/rule/role-delete?id=<?php echo $v['id']; ?>'}">删除</a>
                        </div>
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