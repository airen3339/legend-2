<script type="text/javascript" src="/ueditor/ueditor.config.js"></script>
<!-- 编辑器源码文件 -->
<script type="text/javascript" src="/ueditor/ueditor.all.min.js"></script>
<!-- 编辑器公式插件 -->
<!-- 树形菜单选择 -->
<link rel="stylesheet" type="text/css" href="/easyui/themes/default/easyui.css">
<link rel="stylesheet" type="text/css" href="/easyui/themes/icon.css">
<script type="text/javascript" src="/easyui/jquery.easyui.min.js"></script>

<div class="span10" id="datacontent">
    <ul class="breadcrumb">
        <li><a href="/content/rule/index">权限功能</a> <span class="divider">/</span></li>
        <li><a href="/content/rule/role">角色账号</a> <span class="divider">/</span></li>
        <li class="active">添加角色</li>
    </ul>

    <form action="/content/rule/role-add" method="post" class="form-horizontal" onsubmit="return dataSubmit()">
        <fieldset>


            <div class="control-group">
                <label for="modulename" class="control-label">角色账号</label>
                <div class="controls">
                    <input type="text" id="name" name="name" value="<?php echo isset($role['name'])?$role['name']:''?>">
                    <span class="help-block">请输入角色账号</span>
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label">创建权限</label>
                <div class="controls">
                    <label for="yes" style="display: inline">
                        <input  type="radio" name="createPower" id="yes" value="1" <?php if(isset($role['createPower']) && $role['createPower'] == 1) echo 'checked';?> /> 有
                    </label> &nbsp;&nbsp;
                    <label for="no" style="display: inline">
                        <input  type="radio" name="createPower" id="no" value="0" <?php if(isset($role['createPower']) && $role['createPower'] == 0) echo 'checked';?>  /> 无
                    </label>
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label">目录权限</label>
                <div class="controls">
                    <select style="width: 222px"
                            data-options="url:'/content/api/tree?id=<?php echo isset($role['catalogIds']) ? $role['catalogIds'] : ''?>',method:'get',cascadeCheck:false"
                            multiple class="vice easyui-combotree" id="catalogIds" name="catalogIds[]">
                    </select>
                </div>
            </div>
            <div class="control-group">
                <div class="controls">
                    <input name="id" type="hidden" value="<?php echo isset($role['id'])?$role['id']:''?>">
                    <input type="submit"  class="btn btn-primary" value="提交">
                </div>
            </div>

        </fieldset>
    </form>
</div>
<?php
if(isset($id)){
    ?>
    <script>
        $('.main').tree({
            onLoadSuccess: function (newValue, oldValue) {
                $('.main').combotree('setValue', <?php echo isset($data['pid'])?$data['pid']:''?>);
            }
        })
    </script>
    <?php
}
?>
<?php
if(isset($_GET['pid'])) {
    ?>
    <script>
        $('.main').tree({
            onLoadSuccess: function (newValue, oldValue) {
                $('.main').combotree('setValue', <?php echo isset($_GET['pid'])?$_GET['pid']:''?>);
            }
        })
    </script>
    <?php
}
?>
<script>
    $('.main').combotree({
        onClick: function (node) {
            $("input[name='category[pid]']").val(node.id);
        }
    })

    $('.vice').combotree({
        onCheck:function(newValue,oldValue){
            var nodes = $('.vice').combotree('getValues');
            $("input[name='category[secondClass]']").val(nodes);
        }
    });
</script>
<script>
    function dataSubmit(){
        var name = $('#name').val();
        if(!name){
            alert('请填写角色账号名称');
            return false;
        }
    }
</script>