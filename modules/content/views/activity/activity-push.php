<script type="text/javascript" src="/My97DatePicker/WdatePicker.js"></script>
<div class="span10" id="datacontent">
    <ul class="breadcrumb">
        <li><a href="/content/activity/index">活动管理</a> <span class="divider">/</span></li>
        <li class="active">活动推送奖励</li>
    </ul>
    <form action="/content/activity/activity-push" method="post" class="form-horizontal" onsubmit="return propSubmit();">
        <fieldset>
            <div class="control-group">
                <label for="modulename" class="control-label">区服</label>
                <div class="controls">
                    <select name="server" id="server" style="width: 105px;">
                        <option value="0">请选择</option>
                        <?php
                        foreach($servers as $k => $v){
                            echo "<option value='{$v['id']}'>{$v['name']}</option>";
                        }
                        ?>
                    </select>
                </div>
            </div><div class="control-group">
                <label for="modulename" class="control-label">活动类型</label>
                <div class="controls">
                    <select name="remark" id="remark" style="width: 105px;">
                        <option value="">请选择</option>
                        <option value='1'>每日单充</option>;
                        <option value='2'>累计充值</option>;
                    </select>
                </div>
            </div>

<!--            <div class="control-group">-->
<!--                <label for="modulename" class="control-label">活动类型</label>-->
<!--                <div class="controls">-->
<!--                    <input type="text" class="input-small" onkeyup="value = value.replace(/[^0-9]/g,'')"  name="type" id="type" value=""  >-->
<!--                </div>-->
<!--            </div>-->
            <div class="control-group">
                <label for="modulename" class="control-label">开始日期</label>
                <div class="controls">
                    <input class="input-small Wdate" onclick="WdatePicker()" autocomplete="off" size="10" type="text" id="beginTime" name="beginTime"  value=""/>
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label">截止日期</label>
                <div class="controls">
                    <input class="input-small Wdate" onclick="WdatePicker()"  autocomplete="off" size="10" type="text" id="endTime" name="endTime"  value=""/>
                </div>
            </div>

            <div class="control-group">
                <label for="modulename" class="control-label">发放物品</label>
                <div class="controls">
                    领取条件：<input type="text" style="width:70px" name="condition" id='condition' value=""/>&nbsp;&nbsp;&nbsp;&nbsp;
                    道具ID：<input type="text" style="width:70px" name="propId" id='propId' value="" onkeyup="value = value.replace(/[^0-9]/g,'')" />&nbsp;&nbsp;&nbsp;&nbsp;
                    道具数量：<input type="text" style="width:70px" name="number" id="number" value="" onkeyup="value = value.replace(/[^0-9]/g,'')"  />&nbsp;&nbsp;&nbsp;&nbsp;
                    绑定状态：<select name="bind" id="bind" class="input-small">
                                <option value="0">请选择</option>
                                <option value="1">是</option>
                                <option value="2">否</option>
                            </select>&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;<a href="#" class="btn" onclick="addProp()">添加</a>
                </div>
            </div>

            <hr>
            <table id="addContent" >
                <div class="control-group">
                    <ul class="reward-head controls reward-ul">
                        <li>领取条件</li>
                        <li>道具ID</li>
                        <li>道具数量</li>
                        <li>绑定状态</li>
                        <li>是否删除</li>
                    </ul>
                </div>
            </table>
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
        var condition = $('#condition').val();
        var propId = $('#propId').val();
        var number = $('#number').val();
        var bind = $('#bind').val();
        if(!condition){
            alert('请填写领取条件');return false;
        }
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
            '                        <li class="liCondition">'+condition+'<input type="hidden" value="'+condition+'" name="liConditions[]"/></li>' +
            '                        <li class="lipropId">'+propId+'<input type="hidden" value="'+propId+'" name="propIds[]"/></li>' +
            '                        <li class="liNumber">'+number+'<input type="hidden" value="'+number+'" name="numbers[]"/></li>' +
            '                        <li class="liBind">'+bindStr+'<input type="hidden" value="'+bind+'" name="binds[]"/></li>' +
            '                        <li><a href="#" class="btn" onclick="deleteProp(this)">删除</a></li>' +
            '                    </ul>' +
            '                </div>';
        $('#addContent').append(addStr);
        $('#condition').val('');
        $('#propId').val('');
        $('#number').val('');
        $('#bind').val(0);
    }
    function deleteProp(_this){
        if(confirm('确认删除该条数据？')){
            $(_this).parents('div.propContent:first').remove();
        }
    }
    function propSubmit(){
        var server = $('#server').val();
        var remark = $('#remark').val();
        var type = $('#type').val();
        var beginTime = $('#beginTime').val();
        var endTime = $('#endTime').val();
        var condition = $('li.liCondition').html();
        if(server < 1){
            alert('请选择区服');return false;
        }
        if(!remark){
            alert('请选择活动说明');return false;
        }
        if(!type){
            alert('请填写活动类型');return false;
        }
        if(!beginTime){
            alert('请选择开始时间');return false;
        }
        if(!endTime){
            alert('请选择截止时间');return false;
        }
        if(!condition){
            alert('请添加发放物品');return false;
        }
    }
</script>
