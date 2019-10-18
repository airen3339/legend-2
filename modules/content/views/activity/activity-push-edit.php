<script type="text/javascript" src="/My97DatePicker/WdatePicker.js"></script>
<div class="span10" id="datacontent">
    <ul class="breadcrumb">
        <li><a href="/content/activity/index">活动管理</a> <span class="divider">/</span></li>
        <li class="active"><?php echo $data['remark'];?></li>
    </ul>
    <form action="/content/activity/activity-push" method="post" class="form-horizontal" onsubmit="return propSubmit();">
        <input type="hidden" value="<?php echo $data['id'];?>" name="pushId" />
        <fieldset>
            <div class="control-group">
                <label for="modulename" class="control-label">区服</label>
                <div class="controls">
                    <select name="server" id="server" style="width: 105px;">
                        <option value="0">请选择</option>
                        <?php
                        foreach($servers as $k => $v){
                            if($v['id'] == $data['serverId']){
                                echo "<option value='{$v['id']}' selected >{$v['name']}</option>";
                            }else{
                                echo "<option value='{$v['id']}' >{$v['name']}</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
            </div><div class="control-group">
                <label for="modulename" class="control-label">活动类型</label>
                <div class="controls">
                    <select name="type" id="type" style="width: 105px;">
                        <option value="">请选择</option>
                        <?php foreach($types as $k => $v){?>
                            <option value='<?php echo $v['type'];?>' <?php if( $data['type'] == $v['type']) echo 'selected';?>><?php echo $v['name'];?></option>
                        <?php }?>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label">开始日期</label>
                <div class="controls">
                    <input class="input-small Wdate" onclick="WdatePicker()" size="10" type="text" id="beginTime" name="beginTime"  value="<?php echo $data['beginTime'];?>"/>
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label">截止日期</label>
                <div class="controls">
                    <input class="input-small Wdate" onclick="WdatePicker()" size="10" type="text" id="endTime" name="endTime"  value="<?php echo $data['endTime'];?>"/>
                </div>
            </div>

            <div class="control-group">
                <label for="modulename" class="control-label">发放物品</label>
                <div class="controls">
                    领取条件：<input type="text" style="width:70px" name="condition" id='condition' value=""/>&nbsp;&nbsp;&nbsp;&nbsp;
                    道具名称：<div style="display: inline;">
                        <input type="text" style="width:120px" name="propId" id='propName' autocomplete="off" value="" onkeyup="getToolIds()"/>
                        <ul  class="nav nav-child nav-child-new nav-stacked" id="propData" >
                        </ul>
                    </div>&nbsp;&nbsp;&nbsp;&nbsp;
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
                        <li >操作</li>
                    </ul>
                </div>
                <?php if(isset($data['pushContent']['condition'])){
                    $content = $data['pushContent'];
                    foreach($data['pushContent']['condition'] as $k => $v){
                        ?>
                        <div class="control-group propContent">
                            <ul class="reward-child controls reward-ul">
                                <li class="liCondition ">
                                    <span class=""><?php echo $v?></span>
                                    <input class="input-small inputHid" value="<?php echo $v?>" name="liConditions[]"/>
                                </li>
                                <li class="lipropId">
                                    <span><?php echo $content['propId'][$k]?></span>
                                    <input class="input-small inputHid" value="<?php echo $content['propId'][$k]?>" name="propIds[]"/>
                                </li>
                                <li class="liNumber">
                                    <span><?php echo $content['number'][$k]?></span>
                                    <input  class="input-small inputHid" value="<?php echo $content['number'][$k]?>" name="numbers[]"/>
                                </li>
                                <li class="liBind">
                                    <span><?php echo $content['bind'][$k] == 1?'是':($content['bind'][$k] == 2?'否':'')?></span>
                                    <select name="binds[]" class="input-small inputHid" style="height: 27px">
                                        <option value=""></option>
                                        <option value="1" <?php if($content['bind'][$k] ==1)echo 'selected';?>>是</option>
                                        <option value="2" <?php if($content['bind'][$k] ==2)echo 'selected';?>>否</option>
                                    </select>
                                <li style="width: 120px;!important;height: 27px">
                                    <a href="#" class="btn" onclick="deleteProp(this)">删除</a>
                                    <a href="#" class="btn" onclick="editProp(this)">修改</a>
                                </li>
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
        var condition = $('#condition').val();
        var propId = $('#propId').val();
        var number = $('#number').val();
        var bind = $('#bind').val();
        if(!condition){
            alert('请填写领取条件');return false;
        }
        // if(!propId){
        //     alert('请填写道具ID');return false;
        // }
        // if(!number){
        //     alert('请填写道具数量');return false;
        // }
        // if(bind < 1){
        //     alert('请选择绑定状态');return false;
        // }
        var bindStr  = '';
        var binYes = '';
        var binNo = '';
        if(bind == 1){
            bindStr = '是';
            binYes = 'selected';
        }else if(bind == 2){
            bindStr = '否';
            binNo = 'selected';
        }
        var addStr = '<div class="control-group propContent">' +
    '                    <ul class="reward-child controls reward-ul">' +
    '                        <li class="liCondition"><span>'+condition+'</span><input class="input-small inputHid" value="'+condition+'" name="liConditions[]"/></li>' +
    '                        <li class="lipropId"><span>'+propId+'</span><input class="input-small inputHid" value="'+propId+'" name="propIds[]"/></li>' +
    '                        <li class="liNumber"><span>'+number+'</span><input class="input-small inputHid" value="'+number+'" name="numbers[]"/></li>' +
    '                        <li class="liBind"><span>'+bindStr+'</span>' +
    '                           <select name="binds[]" class="input-small inputHid" style="height: 27px">'+
    '                               <option value=""  ></option>'+
    '                               <option value="1" '+binYes+' >是</option>'+
    '                               <option value="2"  '+binNo+'>否</option>'+
    '                           </select>' +
    '                        <li style="width: 120px;!important;height: 27px">' +
    '                           <a href="#" class="btn" onclick="deleteProp(this)">删除</a>' +
    '                           <a href="#" class="btn" onclick="editProp(this)">修改</a>' +
    '                           </li>' +
    '                    </ul>' +
    '                </div>';
        $('#addContent').append(addStr);
        // $('#condition').val('');
        $('#propId').val('');
        $('#number').val('');
        $('#bind').val(0);
        $('#propData').html('');
        $('#propName').val('');
    }
    function deleteProp(_this){
        if(confirm('确认删除该条数据？')){
            $(_this).parents('div.propContent:first').remove();
        }
    }
    function editProp(_this){
        console.log($(_this).parents("li").siblings('li').find("span"));
        $(_this).parents("li").siblings('li').find("span").addClass('spanHid');
        $(_this).parents("li").siblings('li').find("input").removeClass('inputHid');
        $(_this).parents("li").siblings('li').find("select").removeClass('inputHid');
    }
    function propSubmit(){
        var server = $('#server').val();
        var type = $('#type').val();
        var beginTime = $('#beginTime').val();
        var endTime = $('#endTime').val();
        var condition = $('li.liCondition').html();
        if(server < 1){
            alert('请选择区服');return false;
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
        if(confirm('确定修改并推送服务器？')){
            return true;
        }else{
            return false;
        }
    }
</script>
