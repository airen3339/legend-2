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
        <li><a href="/content/service/index">客服模块</a> <span class="divider">/</span></li>
        <li><a href="/content/service/question-category">问题分类</a> <span class="divider">/</span></li>
        <li class="active">添加分类</li>
    </ul>

    <form action="/content/service/category-add" method="post" class="form-horizontal" onsubmit="return dataSubmit()">
        <fieldset>
            <div class="control-group">
                <label for="modulename" class="control-label">父级分类</label>
                <div class="controls">
                    <select style="width: 400px" id="contentcatid"  url='/content/api/question-tree?pid=0<?php echo isset($data["id"])?"&id=".$data["id"]:""?>' class="main autocombox input-medium easyui-combotree">
                    </select><br/><br/>
                    <input type="hidden" name="category[pid]" value="<?php echo isset($_GET['pid'])?$_GET['pid']:''?><?php echo isset($data['pid'])?$data['pid']:''?>" >
                </div>
            </div>

            <div class="control-group">
                <label for="modulename" class="control-label">分类名称</label>
                <div class="controls">
                    <input type="text" id="name" name="category[name]" value="<?php echo isset($data['name'])?$data['name']:''?>">
                    <span class="help-block">请输入分类名称</span>
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label">排序</label>
                <div class="controls">
                    <input type="text" id="rank" name="category[rank]" value="<?php echo isset($data['rank'])?$data['rank']:''?>">
                </div>
            </div>
            <div class="control-group">
                <div class="controls">
                    <input name="id" type="hidden" value="<?php echo isset($id)?$id:''?>">
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
            alert('请填写分类名称');
            return false;
        }
    }
</script>