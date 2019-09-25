<script type="text/javascript" src="/My97DatePicker/WdatePicker.js"></script>
<div class="span10" id="datacontent">
    <ul class="breadcrumb">
        <li><a href="/content/gm/index">GM工具</a> <span class="divider">/</span></li>
        <li class="active">区服添加奖励</li>
    </ul>
    <form action="/content/gm/service-add-reward" method="post" class="form-horizontal" onsubmit="return submitData()">
        <fieldset>
            <div class="control-group">
                <label for="modulename" class="control-label">发放区服</label>
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
                <label for="modulename" class="control-label">发送时间</label>
                <div class="controls">
                    <input class="input-small Wdate" style="width: 135px" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" size="10" type="text" id="sendTime" name="sendTime"  value="" placeholder="默认当前时间发送" autocomplete="off"/>
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label">最低等级</label>
                <div class="controls">
                    <input class="input-small " size="10" style="width: 135px" type="text" id="minLevel" name="minLevel"  value="" placeholder="默认0级" onkeyup="value = value.replace(/[^0-9]/g,'')"/>
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label">最高等级</label>
                <div class="controls">
                    <input class="input-small " style="width: 135px" size="10" type="text" id="maxLevel" name="maxLevel"  value="" placeholder="默认70级" onkeyup="value = value.replace(/[^0-9]/g,'')"/>
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label">发放物品</label>
                <div class="controls">
                    物品名称：<div style="display: inline;">
                        <input type="text" style="width:120px" name="propId" id='propName' autocomplete="off" value="" onkeyup="getToolIds()"/>
                        <ul  class="nav nav-child nav-child-newt nav-stacked" id="propData" >
                        </ul>
                    </div>&nbsp;&nbsp;&nbsp;&nbsp;
                    物品ID：<input type="text" style="width:70px" name="propId" id='propId' value="" onkeyup="value = value.replace(/[^0-9]/g,'')" />&nbsp;&nbsp;&nbsp;&nbsp;
                    物品数量：<input type="text" style="width:70px" name="propNum" id="propNum" onkeyup="value = value.replace(/[^0-9]/g,'')" value=""/>&nbsp;&nbsp;&nbsp;&nbsp;
                    绑定状态：<select name="bind" id="bind" class="input-small">
                        <option value="0">请选择</option>
                        <option value="1">是</option>
                        <option value="2">否</option>
                    </select>&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;<a href="#" class="btn" onclick="addProp()">添加</a>
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
            <hr>
            <table id="addContent" >
                <div class="control-group">
                    <ul class="reward-head controls reward-ul">
                        <li>道具ID</li>
                        <li>道具数量</li>
                        <li>是否删除</li>
                    </ul>
                </div>
            </table>
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
    function addProp(){
        var propId = $('#propId').val();
        var number = $('#propNum').val();
        var bind = $('#bind').val();
        if(!propId){
            alert('请填写道具ID');return false;
        }
        if(!number){
            alert('请填写道具数量');return false;
        }
        if(bind < 1){
            alert('请选择绑定状态');return false;
        }
        var bindStr  = '';
        if(bind == 1){
            bindStr = '绑定';
        }else{
            bindStr = '未绑定';
        }

        var addStr = '<div class="control-group propContent">' +
            '                    <ul class="reward-child controls reward-ul">' +
            '                        <li class="lipropId">'+propId+'<input type="hidden" value="'+propId+'" name="propIds[]"/></li>' +
            '                        <li class="liNumber">'+number+'<input type="hidden" value="'+number+'" name="numbers[]"/></li>' +
            '                        <li class="liBind">'+bindStr+'<input type="hidden" value="'+bind+'" name="binds[]"/></li>' +
            '                        <li><a href="#" class="btn" onclick="deleteProp(this)">删除</a></li>' +
            '                    </ul>' +
            '                </div>';
        $('#addContent').append(addStr);
        $('#propId').val('');
        $('#propNum').val('');
        $('#bind').val(0);
        $('#propData').html('');
        $('#propName').val('');
    }
    function deleteProp(_this){
        if(confirm('确认删除该条数据？')){
            $(_this).parents('div.propContent:first').remove();
        }
    }
    function submitData(){
        if(confirm('确定给玩家推送改奖励？')){
            var server = $('#server').val();
            var emailTitle = $('#emailTitle').val();
            var emailContent = $('#emailContent').val();
            var condition = $('li.lipropId').html();
            if(server  < 1){
                alert('请选择区服');return false;
            }
            if(!condition){
                alert('请添加发放物品');return false;
            }
            if(!emailTitle){
                alert('请填写邮件标题');return false;
            }
            if(!emailContent){
                alert('请填写邮件内容');return false;
            }

        }else{
            return false;
        }
    }
</script>
