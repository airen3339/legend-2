<script type="text/javascript" src="/My97DatePicker/WdatePicker.js"></script>
<!-- 树形菜单选择 -->
<link rel="stylesheet" type="text/css" href="/easyui/themes/default/easyui.css">
<link rel="stylesheet" type="text/css" href="/easyui/themes/icon.css">
<script type="text/javascript" src="/easyui/jquery.easyui.min.js"></script>
<div class="span10" id="datacontent">
    <ul class="breadcrumb">
        <li><a href="/content/gm/index">GM工具</a> <span class="divider">/</span></li>
        <li class="active">跑马灯</li>
    </ul>
    <form action="/content/gm/roll-notice" method="post" class="form-horizontal" onsubmit="return submitData();">
        <fieldset>
            <div class="control-group">
                <label for="modulename" class="control-label">区服</label>
                <div class="controls">
                    <select style="width: 222px"
                            data-options="url:'/content/api/server?id=<?php echo isset($_GET['serverIds']) ? $_GET['serverIds'] : ''?>',method:'get',cascadeCheck:false"
                            multiple class="vice easyui-combotree" id="serverIds" name="serverIds[]">
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label">开始时间</label>
                <div class="controls">
                    <input class="input-small Wdate"   style="width: 145px" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" size="10" type="text" id="beginTime" name="beginTime"  value="" autocomplete="off"/>
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label">结束时间</label>
                <div class="controls">
                    <input class="input-small Wdate"  style="width: 145px" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" size="10" type="text" id="endTime" name="endTime"  value="" autocomplete="off"/>
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label">间隔时间</label>
                <div class="controls">
                    <input class="input-small" style="width: 145px" size="10" type="text" id="intervalTime" name="intervalTime"  value="" autocomplete="off" onkeyup="value = value.replace(/[^0-9]/g,'')" placeholder="单位秒 最低间隔30秒"/>
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label">公告内容</label>
                <div class="controls">
                    <textarea name="content" id="content"></textarea>
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
<script>
    function submitData(){
        var beginTime = $('#beginTime').val();
        var endTime = $('#endTime').val();
        var intervalTime = $('#intervalTime').val();
        var content = $('#content').val();

        if(!beginTime){
            alert('请选择开始时间');return false;
        }
        if(!endTime){
            alert('请选择结束时间');return false;
        }else{//判断结束时间
            var end = (new Date(endTime)).getTime();
            var begin = (new Date(beginTime)).getTime();
            if(begin >= end){
                alert('请选择正确的结束时间（结束时间必须大于开始时间）');return false;
            }
        }
        console.log(intervalTime);
        if(!intervalTime || intervalTime < 30){
            alert('请填写正确的间隔时间');return false;
        }
        if(!content){
            alert('请填写公告内容');return false;
        }
        if(confirm('确定添加该公告并推送给服务端吗？')){
            return true;
        }else{
            return false;
        }
    }
</script>

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
