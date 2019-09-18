<script type="text/javascript" src="/My97DatePicker/WdatePicker.js"></script>
<div class="span10" id="datacontent">
    <ul class="breadcrumb">
        <li><a href="/content/gm/index">GM工具</a> <span class="divider">/</span></li>
        <li class="active">首页公告</li>
    </ul>
    <form action="/content/gm/index-notice" method="post" class="form-horizontal" onsubmit="javascript:if(confirm('确定提交并推送服务端吗？')){return true}else{
        return false;
    }">
        <input type="hidden" name="id" value="<?php echo isset($notice['id'])?$notice['id']:''?>">
        <fieldset>
            <div class="control-group">
                <label for="modulename" class="control-label">开始日期</label>
                <div class="controls">
                    <input class="input-small Wdate" onclick="WdatePicker()" size="10" type="text" id="beginTime" name="beginTime"  value="<?php echo isset($notice['beginTime'])?$notice['beginTime']:''?>" autocomplete="off"/>
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label">结束日期</label>
                <div class="controls">
                    <input class="input-small Wdate" onclick="WdatePicker()" size="10" type="text" id="endTime" name="endTime"  value="<?php echo isset($notice['endTime'])?$notice['endTime']:''?>" autocomplete="off"/>
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label">公告内容</label>
                <div class="controls">
                    <textarea name="content"><?php echo isset($notice['content'])?$notice['content']:''?></textarea>
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
