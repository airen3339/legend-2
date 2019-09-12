<script type="text/javascript" src="/My97DatePicker/WdatePicker.js"></script>
<div class="span10" id="datacontent">
    <ul class="breadcrumb">
        <li><a href="/content/gm/index">GM工具</a> <span class="divider">/</span></li>
        <li class="active">ssc活动</li>
    </ul>
    <form action="/content/gm/ssc-add" method="post" class="form-horizontal">
        <fieldset>

            <div class="control-group">
                <label for="modulename" class="control-label">开始日期</label>
                <div class="controls">
                    <input class="input-small Wdate" onclick="WdatePicker()" size="10" type="text" id="beginTime" name="beginTime" autocomplete="off"  value=""/>
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label">结束日期</label>
                <div class="controls">
                    <input class="input-small Wdate" onclick="WdatePicker()" size="10" type="text" autocomplete="off"  id="endTime" name="endTime"  value=""/>
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label">区服</label>
                <div class="controls">
                    <select name="server">
                        <option value="0">请选择</option>
                        <?php
                        foreach($servers as $k => $v){
                            echo "<option value='{$v['id']}'>{$v['name']}</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label">活动说明</label>
                <div class="controls">
                    <textarea name="remark"></textarea>
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
