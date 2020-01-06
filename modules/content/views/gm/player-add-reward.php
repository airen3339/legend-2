
<div class="span10" id="datacontent">
    <ul class="breadcrumb">
        <li><a href="/content/gm/index">GM工具</a> <span class="divider">/</span></li>
        <li class="active">区服添加奖励</li>
    </ul>
    <form action="/content/gm/player-add-reward" method="post" class="form-horizontal" onsubmit="return submitData()">
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
                <label for="modulename" class="control-label">角色名</label>
                <div class="controls">
                    <textarea type="text" id="name"  name="name" value="" placeholder="多个英文逗号隔开"  ></textarea>
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label">角色id</label>
                <div class="controls">
                    <textarea type="text" id="roleId"  name="roleId" value=""  onkeyup="value = value.replace(/[^0-9,]/g,'')" placeholder="多个英文逗号隔开"  ></textarea>
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label">邮件标题</label>
                <div class="controls">
                    <input type="text" id="emailTitle"  name="emailTitle" value=""  >
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label">邮件内容</label>
                <div class="controls">
                    <textarea name="emailContent" id="emailContent"></textarea>
                </div>
            </div>
<!--            <div class="control-group">-->
<!--                <label for="modulename" class="control-label">邮件附言</label>-->
<!--                <div class="controls">-->
<!--                    <textarea name="contentOther" ></textarea>-->
<!--                </div>-->
<!--            </div>-->

            <div class="control-group">
                <label for="modulename" class="control-label">发放物品</label>
                <div class="controls">
                    物品名称：<div style="display: inline;">
                        <input type="text" style="width:120px" name="propId" id='propName' autocomplete="off" value="" onkeyup="getToolIds()"/>
                        <ul  class="nav nav-child nav-child-newo nav-stacked" id="propData" >
                        </ul>
                    </div>&nbsp;&nbsp;&nbsp;&nbsp;
                    物品ID：<input type="text" style="width:70px" name="propId" id='propId' value="" onkeyup="value = value.replace(/[^0-9]/g,'')" />&nbsp;&nbsp;&nbsp;&nbsp;
                    物品数量：<input type="text" style="width:70px" name="propNum" id="propNum" onkeyup="value = value.replace(/[^0-9]/g,'')" value=""/>&nbsp;&nbsp;&nbsp;&nbsp;
                    绑定状态：<select name="bind" id="bind" class="input-small">
                        <option value="0">请选择</option>
                        <option value="1">是</option>
                        <option value="2">否</option>
                    </select>&nbsp;&nbsp;
                </div>
            </div>
            <br/>
            <br/>
            <br/>
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
        var server = $('#server').val();
        var roleId = $('#roleId').val();
        var emailTitle = $('#emailTitle').val();
        var emailContent = $('#emailContent').val();
        var propId = $('#propId').val();
        var propNum = $('#propNum').val();
        var bind = $('#bind').val();
        if(server  < 1){
            alert('请选择区服');return false;
        }
        if(!roleId){
            alert('请填写角色名');return false;
        }
        if(!emailTitle){
            alert('请填写邮件标题');return false;
        }
        if(!emailContent){
            alert('请填写邮件内容');return false;
        }
        if(!propId){
            alert('请填写物品Id');return false;
        }
        if(!propNum){
            alert('请填写物品数量');return false;
        }
        if(bind < 1){
            alert('请选择绑定状态');return false;
        }
    }
</script>
