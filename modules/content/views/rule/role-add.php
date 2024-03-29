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
                <label for="modulename" class="control-label">使用者</label>
                <div class="controls">
                    <input type="text" id="getName" name="getName" value="<?php echo isset($role['getName'])?$role['getName']:''?>">
                    <span class="help-block">请输入使用者姓名</span>
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
                <label for="modulename" class="control-label">元宝权限</label>
                <div class="controls">
                    <label for="yes" style="display: inline">
                        <input  type="radio" name="currency" id="cYes" value="1" <?php if(isset($role['currency']) && $role['currency'] == 1) echo 'checked';?> /> 有
                    </label> &nbsp;&nbsp;
                    <label for="no" style="display: inline">
                        <input  type="radio" name="currency" id="cNo" value="0" <?php if(isset($role['currency']) && $role['currency'] == 0) echo 'checked';?>  /> 无
                    </label>
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label">目录权限</label>
                <div class="controls">
                    <select style="width: 222px"
                            data-options="url:'/content/api/tree?rand=<?php echo rand(1,1000000);?>&id=<?php echo isset($role['catalogIds']) ? $role['catalogIds'] : ''?>',method:'get',cascadeCheck:false"
                            multiple class="vice easyui-combotree" id="catalogIds" name="catalogIds[]">
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label">客服账号</label>
                <div class="controls">
                    <label for="yes" style="display: inline">
                        <input  type="radio" name="service" id="sYes" value="1" <?php if(isset($role['service']) && $role['service'] == 1) echo 'checked';?> /> 是
                    </label> &nbsp;&nbsp;
                    <label for="no" style="display: inline">
                        <input  type="radio" name="service" id="sNo" value="0" <?php if(isset($role['service']) && $role['service'] == 0) echo 'checked';?>  /> 否
                    </label>
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label">客服QQ</label>
                <div class="controls">
                    <select name="qq">
                        <option value="0">请选择</option>
                        <option value="1041411666" <?php if(isset($role['qq']) && $role['qq'] == '1041411666') echo 'selected';?>>1041411666</option>
                        <option value="1040499666" <?php if(isset($role['qq']) && $role['qq'] == '1040499666') echo 'selected';?>>1040499666</option>
                        <option value="1047477666" <?php if(isset($role['qq']) && $role['qq'] == '1047477666') echo 'selected';?>>1047477666</option>
                        <option value="1072722666" <?php if(isset($role['qq']) && $role['qq'] == '1072722666') echo 'selected';?>>1072722666</option>
                    </select>
                    <span class="help-block">客服账号必填</span>
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
        var getName = $('#getName').val();
        if(!name){
            alert('请填写角色账号名称');
            return false;
        }
        if(!getName){
            alert('请填写使用者姓名');
            return false;
        }
    }
</script>