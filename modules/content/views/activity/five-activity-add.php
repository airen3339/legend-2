<script type="text/javascript" src="/My97DatePicker/WdatePicker.js"></script>
<div class="span10" id="datacontent">
    <ul class="breadcrumb">
        <li><a href="/content/activity/index">活动管理</a> <span class="divider">/</span></li>
        <li class="active">五行运势活动编辑</li>
    </ul>
    <form action="/content/activity/five-activity-add" method="post" class="form-horizontal" onsubmit="return propSubmit();">
        <input type="hidden" value="<?php echo isset($data['id'])?$data['id']:0;?>" name="pushId" />
        <fieldset>
            <div class="control-group">
                <label for="modulename" class="control-label">区服</label>
                <div class="controls">
                    <select name="server" id="server" style="width: 105px;">
                        <option value="0">请选择</option>
                        <?php
                        foreach($servers as $k => $v){
                            if(isset($data['serverId']) && ($v['id'] == $data['serverId'])){
                                echo "<option value='{$v['id']}' selected >{$v['name']}</option>";
                            }else{
                                echo "<option value='{$v['id']}' >{$v['name']}</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="control-group">
                <label for="modulename" class="control-label">开始日期</label>
                <div class="controls">
                    <input class="input-small Wdate" onclick="WdatePicker()" size="10" type="text" id="beginTime" name="beginTime"  value="<?php echo isset($data['beginTime'])?$data['beginTime']:'';?>" autocomplete="off"/>
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label">截止日期</label>
                <div class="controls">
                    <input class="input-small Wdate" onclick="WdatePicker()" size="10" type="text" id="endTime" name="endTime"  value="<?php echo isset($data['endTime'])?$data['endTime']:'';?>" autocomplete="off"/>
                </div>
            </div>

            <div class="control-group">
                <label for="modulename" class="control-label">活动道具</label>
                <div class="controls">
                    道具名称：<input type="text" style="width:70px" name="toolName" id='toolName' value=""/>&nbsp;&nbsp;&nbsp;&nbsp;
                    道具ID：<input type="text" style="width:70px" name="toolId" id='toolId' value="" onkeyup="value = value.replace(/[^0-9]/g,'')" />&nbsp;&nbsp;&nbsp;&nbsp;
                    道具数量：<input type="text" style="width:70px" name="number" id="number" value="" onkeyup="value = value.replace(/[^0-9]/g,'')"  />&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;<a href="#" class="btn" onclick="addProp()">添加</a>
                </div>
            </div>

            <hr>
            <table id="addContent" >
                <div class="control-group">
                    <ul class="reward-head controls reward-ul">
                        <li>道具名称</li>
                        <li>道具ID</li>
                        <li>道具数量</li>
                        <li>是否删除</li>
                    </ul>
                </div>
                <?php if(isset($data['pushContent']['toolName'])){
                    $content = $data['pushContent'];
                    foreach($data['pushContent']['toolName'] as $k => $v){
                        ?>
                        <div class="control-group propContent">
                            <ul class="reward-child controls reward-ul">
                                <li class="toolName"><?php echo $v?><input type="hidden" value="<?php echo $v?>" name="toolName[]"/></li>
                                <li class="toolId"><?php echo $content['toolId'][$k]?><input type="hidden" value="<?php echo $content['toolId'][$k]?>" name="toolId[]"/></li>
                                <li class="liNumber"><?php echo $content['number'][$k]?><input type="hidden" value="<?php echo $content['number'][$k]?>" name="numbers[]"/></li>
                                <li><a href="#" class="btn" onclick="deleteProp(this)">删除</a></li>
                            </ul>
                        </div>
                        <?php
                    }
                }?>
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
        var toolName = $('#toolName').val();
        var toolId = $('#toolId').val();
        var number = $('#number').val();
        if(!toolName){
            alert('请填写道具名称');return false;
        }
        if(!toolId){
            alert('请填写道具ID');return false;
        }
        if(!number){
            alert('请填写道具数量');return false;
        }
        var addStr = '<div class="control-group propContent">' +
            '                    <ul class="reward-child controls reward-ul">' +
            '                        <li class="toolName">'+toolName+'<input type="hidden" value="'+toolName+'" name="toolName[]"/></li>' +
            '                        <li class="toolId">'+toolId+'<input type="hidden" value="'+toolId+'" name="toolId[]"/></li>' +
            '                        <li class="liNumber">'+number+'<input type="hidden" value="'+number+'" name="numbers[]"/></li>' +
            '                        <li><a href="#" class="btn" onclick="deleteProp(this)">删除</a></li>' +
            '                    </ul>' +
            '                </div>';
        $('#addContent').append(addStr);
        $('#toolName').val('');
        $('#toolId').val('');
        $('#number').val('');
    }
    function deleteProp(_this){
        if(confirm('确认删除该条数据？')){
            $(_this).parents('div.propContent:first').remove();
        }
    }
    function propSubmit(){
        var server = $('#server').val();
        var beginTime = $('#beginTime').val();
        var endTime = $('#endTime').val();
        var condition = $('li.toolName').html();
        if(server < 1){
            alert('请选择区服');return false;
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
