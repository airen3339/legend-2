<script type="text/javascript" src="/My97DatePicker/WdatePicker.js"></script>
<div class="span10" id="datacontent">
    <ul class="breadcrumb">
        <li><a href="/content/gm/index">GM工具</a> <span class="divider">/</span></li>
        <li class="active">跑马灯</li>
    </ul>
    <form action="/content/gm/roll-notice" method="post" class="form-horizontal" onsubmit="javascript:if(confirm('确定提交并推送服务端吗？')){return true}else{
        return false;
    }">
        <fieldset>
            <div class="control-group">
                <label for="modulename" class="control-label">区服</label>
                <div class="controls">
                    <select name="server" id="server">
                        <option value="0">请选择</option>
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
                <label for="modulename" class="control-label">开始日期</label>
                <div class="controls">
                    <input class="input-small Wdate"  style="width: 145px" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" size="10" type="text" id="beginTime" name="beginTime"  value="" autocomplete="off"/>
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label">结束日期</label>
                <div class="controls">
                    <input class="input-small Wdate"  style="width: 145px" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" size="10" type="text" id="endTime" name="endTime"  value="" autocomplete="off"/>
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label">间隔时间</label>
                <div class="controls">
                    <input class="input-small" style="width: 145px" size="10" type="text" id="intervalTime" name="intervalTime"  value="" autocomplete="off" onkeyup="value = value.replace(/[^0-9]/g,'')"/>
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label">公告内容</label>
                <div class="controls">
                    <textarea name="content"></textarea>
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
