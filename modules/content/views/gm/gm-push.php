
<div class="span10" id="datacontent">
    <ul class="breadcrumb">
        <li><a href="/content/gm/index">GM工具</a> <span class="divider">/</span></li>
        <li class="active">命令推送</li>
    </ul>
    <form action="/content/gm/gm-push" method="post" class="form-horizontal" onsubmit="javascript:if(confirm('确定推送该命令吗？')){return true;}else{return false;}">
        <fieldset>
            <div class="control-group">
                <label for="modulename" class="control-label">区服</label>
                <div class="controls">
                    <select name="server">
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
                <label for="modulename" class="control-label">角色名</label>
                <div class="controls">
                    <input type="text"  name="name" value=""  >
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label">命令前缀</label>
                <div class="controls">
                    <input type="text"  name="prefix" value="" >
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label">命令参数</label>
                <div class="controls">
                    <input type="text"  name="params" value="" >
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
