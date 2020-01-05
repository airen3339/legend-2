<script type="text/javascript" src="/My97DatePicker/WdatePicker.js"></script>
<div class="span10" id="datacontent">
    <ul class="breadcrumb">
        <li><a href="/content/player/index">订单模块</a> <span class="divider">/</span></li>
        <li class="active">订单回调</li>
    </ul>
    <form action="/content/gm/home-notice" method="" class="form-horizontal">
        <fieldset>

            <div class="control-group">
                <label for="modulename" class="control-label">订单号</label>
                <div class="controls">
                    <input type="text" value="<?php echo isset($data['orderNumber'])?$data['orderNumber']:''?>" />
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label">订单说明</label>
                <div class="controls">
                    <input type="text" value="<?php echo isset($data['remark'])?$data['remark']:''?>" />
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label">回调时间</label>
                <div class="controls">
                    <input type="text" value="<?php echo isset($data['createTime'])?date('Y-m-d H:i:s',$data['createTime']):''?>" />
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label">回调内容</label>
                <div class="controls">
                    <textarea name="content"><?php echo isset($data['notify'])?$data['notify']:''?></textarea>
                </div>
            </div>
            <br/>
            <div class="control-group">
                <div class="controls">
                    <a href="javascript:history.go(-1)" class="btn" >返回</a>
                </div>
            </div>
        </fieldset>
    </form>
</div>
<script>
    function deleteCurrent(){
        if(confirm('确定清除当前公告')){
            $.post('/content/api/delete-current-notice',{},function(e){
                alert(e.message);
                if(e.code==1){
                    window.location.reload();
                }
            },'json');
        }
    }
</script>
