<script type="text/javascript" src="/My97DatePicker/WdatePicker.js"></script>
<div class="span10" id="datacontent">
    <ul class="breadcrumb">
        <li><a href="/content/gm/index">GM工具</a> <span class="divider">/</span></li>
        <li class="active">区服添加奖励</li>
    </ul>
    <form action="/content/gm/service-add-notice" method="post" class="form-horizontal">
        <fieldset>
            <div class="control-group">
                <label for="modulename" class="control-label">区服</label>
                <div class="controls">
                    <select name="content[category]">
                        <option value="0" >对对对</option>
                        <option value="1" >GMAT</option>
                    </select>
                </div>
            </div>

            <div class="control-group">
                <label for="modulename" class="control-label">邮件标题</label>
                <div class="controls">
                    <input type="text"  name="content[emailTitle]" value="" datatype="emailTitle" >
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label">邮件内容</label>
                <div class="controls">
                    <textarea name="content[emailContent]"></textarea>
                </div>
            </div>

            <div class="control-group">
                <label for="modulename" class="control-label">截止日期</label>
                <div class="controls">
                    <input class="input-small Wdate" onclick="WdatePicker()" size="10" type="text" id="endTime" name="endTime"  value=""/>
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
