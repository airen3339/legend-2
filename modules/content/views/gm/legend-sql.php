<div class="span10" id="datacontent">
    <ul class="breadcrumb">
        <li><a href="/content/gm/index">GM工具</a> <span class="divider">/</span></li>
        <li class="active">SQL查询</li>
    </ul>
    <form action="/content/gm/legend-sql" method="post" class="form-horizontal" >
        <fieldset>

            <div class="control-group">
                <label for="modulename" class="control-label">sql语句</label>
                <div class="controls">
                    <textarea name="sql"><?php echo isset($sql)?$sql:''?></textarea>
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label">查询数据</label>
                <div class="controls">
                    <textarea name="sql"><?php var_dump(isset($data)?$data:'')?></textarea>
                </div>
            </div>
            <br/>
            <div class="control-group">
                <div class="controls">
                    <input type="submit" class="btn " value="提交">&nbsp;&nbsp;&nbsp;
                </div>
            </div>
        </fieldset>
    </form>
</div>
