<!-- 树形菜单选择 -->
<link rel="stylesheet" type="text/css" href="/easyui/themes/default/easyui.css">
<link rel="stylesheet" type="text/css" href="/easyui/themes/icon.css">
<script type="text/javascript" src="/easyui/jquery.easyui.min.js"></script>
<div class="span10" id="datacontent">
    <ul class="breadcrumb">
        <li><a href="/content/gm/index">GM工具</a> <span class="divider">/</span></li>
        <li class="active">商人排名编辑</li>
    </ul>
    <form action="/content/gm/merchant-order-add" method="post" class="form-horizontal" >
        <fieldset>
            <div class="control-group">
                <label for="modulename" class="control-label">账号</label>
                <div class="controls">
                    <input name="userId" type="text"   value="<?php echo isset($data['userId'])?$data['userId']:'';?>" readonly autocomplete="off"/>
                </div>
            </div>
            <div class="control-group">
            <div class="control-group">
                <label for="modulename" class="control-label">角色名</label>
                <div class="controls">
                    <input name="name" type="text"   value="<?php echo isset($data['name'])?$data['name']:'';?>" readonly autocomplete="off"/>
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label">角色id</label>
                <div class="controls">
                    <input name="roleId" type="text"   value="<?php echo isset($data['RoleID'])?$data['RoleID']:'';?>" readonly autocomplete="off"/>
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label">排名</label>
                <div class="controls">
                    <input name="rank" type="text"   value="<?php echo isset($data['Ingot'])?$data['Ingot']:'';?>"  autocomplete="off"/>
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label">区服</label>
                <div class="controls">
                    <select style="width: 222px"
                            data-options="url:'/content/api/server?id=<?php echo isset($data['WorldID']) ? $data['WorldID'] : ''?>',method:'get',cascadeCheck:false"
                            multiple class="vice easyui-combotree" id="serverIds" name="serverIds[]">
                    </select>
                </div>
            </div>
            <div class="control-group">
                <div class="controls">
                    <input type="submit" class="btn btn-primary" value="提交">
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
