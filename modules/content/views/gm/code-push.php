
<div class="span10" id="datacontent">
    <ul class="breadcrumb">
        <li><a href="/content/gm/index">GM工具</a> <span class="divider">/</span></li>
        <li class="active">代码推送</li>
    </ul>
    <form action="/content/gm/code-push" method="post" class="form-horizontal" onsubmit="javascript:if(confirm('确定推送该内容吗？')){return true;}else{return false;}">
        <fieldset>
            <div class="control-group">
                <label for="modulename" class="control-label">区服</label>
                <div class="controls">
                    <select name="server">
                        <option value="0">请选择</option>
                        <option value="-99">全服</option>
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
                <label for="modulename" class="control-label">代码内容</label>
                <div class="controls">
                    <textarea name="info" id="info"></textarea>
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
