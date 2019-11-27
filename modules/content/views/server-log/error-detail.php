<script type="text/javascript" src="/My97DatePicker/WdatePicker.js"></script>
<div class="span10" id="datacontent">
    <ul class="breadcrumb">
        <li><a href="/content/server-log/index">客户端日志</a> <span class="divider">/</span></li>
        <li class="active">报错日志详情</li>
    </ul>
    <form action="#" method="get" class="form-horizontal" >
        <input type="hidden" name="id" value="<?php echo isset($data['id'])?$data['id']:''?>">
        <fieldset>
            <div class="control-group">
                <label for="modulename" class="control-label">报错内容</label>
                <div class="controls">
                    <textarea name="content"><?php echo isset($data['content'])?$data['content']:''?></textarea>
                </div>
            </div>
            <br/>
            <div class="control-group">
                <div class="controls">
                    <a href="Javascript:history.go(-1);" class="btn" >返回</a>
                </div>
            </div>
        </fieldset>
    </form>
</div>
