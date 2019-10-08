<script type="text/javascript" src="/My97DatePicker/WdatePicker.js"></script>
<div class="span10" id="datacontent">
    <ul class="breadcrumb">
        <li><a href="/content/activity/index">活动管理</a> <span class="divider">/</span></li>
        <li class="active">活动类型</li>
    </ul>
    <form action="/content/activity/activity-type-edit" method="post" class="form-horizontal">
        <input type="hidden" name="id" value="<?php echo isset($type['id'])?$type['id']:''?>" />
        <fieldset>
            <div class="control-group">
                <label for="modulename" class="control-label">类型名称</label>
                <div class="controls">
                    <input type="text" name="name" id="name" value="<?php echo isset($type['name'])?$type['name']:''?>" />
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label">对应类型</label>
                <div class="controls">
                    <input type="text" name="type" onkeyup="value = value.replace(/[^0-9]/g,'')" id="type" value="<?php echo isset($type['type'])?$type['type']:''?>" />
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label">活动排序</label>
                <div class="controls">
                    <input type="text" name="rank" id="rank" onkeyup="value = value.replace(/[^0-9]/g,'')" value="<?php echo isset($type['rank'])?$type['rank']:''?>" />
                </div>
            </div>
            <div class="control-group">
                <div class="controls">
                    <input type="submit" class="btn " value="提交">
                </div>
            </div>
        </fieldset>
    </form>
</div>
