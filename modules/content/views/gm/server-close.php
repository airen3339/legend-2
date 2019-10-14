<script type="text/javascript" src="/My97DatePicker/WdatePicker.js"></script>
<div class="span10" id="datacontent">
    <ul class="breadcrumb">
        <li><a href="/content/gm/index">GM工具</a> <span class="divider">/</span></li>
        <li class="active">服务器关服</li>
    </ul>
    <form action="/content/gm/server-close" method="post" class="form-horizontal" onsubmit="return submitData();">
        <fieldset>
            <div class="control-group">
                <label for="modulename" class="control-label">区服</label>
                <div class="controls">
                    <select name="server" id="server">
                        <option value="0">全服</option>
                        <?php
                        foreach($servers as $k => $v){ ?>
                            <option value='<?php echo $v['id']?>' <?php if(isset($_GET['server']) && $_GET['server'] == $v['id']) echo 'selected';?>><?php echo $v['name']?></option>";
                            <?php
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label">关服通知</label>
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
        var content = $('#content').val();
        if(!content){
            alert('请填写关服通知');return false;
        }
        if(confirm('确定添加该公告并推送给服务端吗？')){
            return true;
        }else{
            return false;
        }
    }
</script>
