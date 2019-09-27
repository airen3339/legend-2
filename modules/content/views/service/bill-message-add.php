<script type="text/javascript" src="/My97DatePicker/WdatePicker.js"></script>
<div class="span10" id="datacontent">
    <ul class="breadcrumb">
        <li><a href="/content/service/index">客户模块</a> <span class="divider">/</span></li>
        <li class="active">单据信息</li>
    </ul>
    <form action="/content/service/bill-message-add" method="post" class="form-horizontal" onsubmit="return billSubmit()">
        <input type="hidden" name="id" value="<?php echo isset($bill['id'])?$bill['id']:''?>">
        <fieldset>
            <div class="control-group">
                <label for="modulename" class="control-label"><span style="color:red">* </span>单据类型</label>
                <div class="controls">
                    <select name="billType" id="billType">
                        <option value="0">请选择</option>
                        <?php
                        foreach($billTypes as $k => $v){ ?>
                            <option value='<?php echo $v['id']?>' <?php if(isset($bill['billType']) && $bill['billType'] == $v['id']) echo 'selected';?>><?php echo $v['name']?></option>";
                            <?php
                        }
                        ?>
                    </select>

                    <label style="display: inline;margin-left: 180px;margin-right: 20px"><span style="color:red">* </span>单据来源</label>
                    <select name="billSource" id="billSource" >
                        <option value=0>请选择</option>
                        <?php
                        foreach($billSources as $k => $v){ ?>
                            <option value='<?php echo $v['id']?>' <?php if(isset($bill['billSource']) && $bill['billSource'] == $v['id']) echo 'selected';?>><?php echo $v['name']?></option>";
                            <?php
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label  for="modulename" class="control-label"><span style="color:red">* </span>一级分类</label>
                <div class="controls">
                    <select name="quesParent" id="quesParent" onchange="getChildCategory()">
                        <option value=0>请选择</option>
                        <?php
                        foreach($billQuesParent as $k => $v){ ?>
                            <option value='<?php echo $v['id']?>' <?php if(isset($bill['quesParent']) && $bill['quesParent'] == $v['id']) echo 'selected';?>><?php echo $v['name']?></option>";
                            <?php
                        }
                        ?>
                    </select>

                    <label style="display: inline;margin-left: 180px;margin-right: 20px"><span style="color:red">* </span>二级分类</label>
                    <select name="quesChild" id="quesChild">
                        <option value=0>请选择</option>
                        <?php
                        foreach($billQuesChild as $k => $v){ ?>
                            <option value='<?php echo $v['id']?>' <?php if(isset($bill['quesChild']) && $bill['quesChild'] == $v['id']) echo 'selected';?>><?php echo $v['name']?></option>";
                            <?php
                        }
                        ?>
                    </select>
                </div>

            </div>
            <div class="control-group">
                <label for="modulename" class="control-label"><span style="color:red">* </span>游戏所属</label>
                <div class="controls">
                    <select name="billGame" id="billGame">
                        <option value="0">请选择</option>
                        <?php
                        foreach($billGames as $k => $v){ ?>
                            <option value='<?php echo $v['id']?>' <?php if(isset($bill['billGame']) && $bill['billGame'] == $v['id']) echo 'selected';?>><?php echo $v['name']?></option>";
                            <?php
                        }
                        ?>
                    </select>

                    <label style="display: inline;margin-left: 190px;margin-right: 26px">VIP等级</label>
                    <select name="vipLevel" id="vipLevel">
                        <option value="0">请选择</option>
                        <?php
                        foreach($vipLevels as $k => $v){ ?>
                            <option value='<?php echo $v['id']?>' <?php if(isset($bill['vipLevel']) && $bill['vipLevel'] == $v['id']) echo 'selected';?>><?php echo $v['name']?></option>";
                            <?php
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label">游戏账号</label>
                <div class="controls">
                    <input   type="text" id="account" name="account"  value="<?php echo isset($bill['account'])?$bill['account']:''?>" autocomplete="off"/>

                    <label style="display: inline;margin-left: 190px;margin-right: 20px">游戏昵称</label>
                    <input size="10" type="text" id="gameName" name="gameName"  value="<?php echo isset($bill['gameName'])?$bill['gameName']:''?>" autocomplete="off"/>
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label"><span style="color:red">* </span>游戏大区</label>
                <div class="controls">
                    <select name="gameServer" id="gameServer">
                        <option value="0">请选择</option>
                        <?php
                        foreach($servers as $k => $v){ ?>
                            <option value='<?php echo $v['id']?>' <?php if(isset($bill['gameServer']) && $bill['gameServer'] == $v['id']) echo 'selected';?>><?php echo $v['name']?></option>";
                            <?php
                        }
                        ?>
                    </select>

                    <label style="display: inline;margin-left: 180px;margin-right: 20px"><span style="color:red">* </span>下载渠道</label>
                    <input  size="10" type="text" id="download" name="download"  value="<?php echo isset($bill['download'])?$bill['download']:''?>" autocomplete="off"/>
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label"><span style="color:red">* </span>游戏ID</label>
                <div class="controls">
                    <input  size="10" type="text" id="gameId" name="gameId"  value="<?php echo isset($bill['gameId'])?$bill['gameId']:''?>" autocomplete="off"/>

                    <label style="display: inline;margin-left: 190px;margin-right: 20px">系统版本</label>
                    <input  size="10" type="text" id="version" name="version"  value="<?php echo isset($bill['version'])?$bill['version']:''?>" autocomplete="off"/>
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label">设备型号</label>
                <div class="controls">
                    <input size="10" type="text" id="device" name="device"  value="<?php echo isset($bill['device'])?$bill['device']:''?>" autocomplete="off"/>

                    <label style="display: inline;margin-left: 190px;margin-right: 20px">联系邮箱</label>
                    <input  size="10" type="text" id="email" name="email"  value="<?php echo isset($bill['email'])?$bill['email']:''?>" autocomplete="off"/>
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label">联系电话</label>
                <div class="controls">
                    <input  size="10" type="text" id="phone" name="phone"  value="<?php echo isset($bill['phone'])?$bill['phone']:''?>" autocomplete="off"/>

                    <label style="display: inline;margin-left: 190px;margin-right: 25px">联系QQ</label>
                    <input  size="10" type="text" id="qq" name="qq"  value="<?php echo isset($bill['qq'])?$bill['qq']:''?>" autocomplete="off"/>
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label">图片文件</label>
                <div class="controls">
                    <input  size="10" type="text" id="imageFile" name="imageFile"  value="<?php echo isset($bill['email'])?$bill['email']:''?>" autocomplete="off"/>
                </div>
            </div>
            <br/>
            <div class="control-group">
                <label for="modulename" class="control-label"><span style="color:red">* </span>详细描述</label>
                <div class="controls">
                    <textarea style="width: 702px;height: 100px" id="detail" name="detail"><?php echo isset($bill['detail'])?$bill['detail']:''?></textarea>
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label"><span style="color:red">* </span>处理结果</label>
                <div class="controls">
                    <textarea style="width: 702px;height: 100px"  id="result" name="result"><?php echo isset($bill['result'])?$bill['result']:''?></textarea>
                </div>
            </div><br/>
            <div class="control-group">
                <div class="controls">
                    <input type="submit" class="btn btn-primary" value="提交">
                </div>
            </div>
        </fieldset>
    </form>
</div>
<script>
    function getChildCategory(){
        var pid = $('#quesParent').val();
        if(pid !=0){
            $.post('/content/api/get-question-child',{pid:pid},function(e){
                var str = '<option value=0>请选择</option>';
                for(var i=0;i<e.length;i++){
                    str += '<option value="'+e[i].id+'">'+e[i].name+'</option>';
                }
                $('#quesChild').html(str);
            },'json');
        }
    }
    function billSubmit(){
        var billType = $('#billType').val();
        var billSource = $('#billSource').val();
        var quesParent = $('#quesParent').val();
        var quesChild = $('#quesChild').val();
        var billGame = $('#billGame').val();
        var gameServer = $('#gameServer').val();
        var download = $('#download').val();
        var gameId = $('#gameId').val();
        var detail = $('#detail').val();
        var result = $('#result').val();
        if(!billType || billType < 1){
            alert('请选择单据类型');return false;
        }
        if(!billSource || billSource < 1){
            alert('请选择单据来源');return false;
        }
        if(!quesParent || quesParent < 1){
            alert('请选择一级分类');return false;
        }
        if(!quesChild || quesChild < 1){
            alert('请选择二级分类');return false;
        }
        if(!billGame){
            alert('请选择游戏所属');return false;
        }
        if(!gameServer || gameServer < 1){
            alert('请选择游戏大厅');return false;
        }
        if(!download){
            alert('请填写下载渠道');return false;
        }
        if(!gameId){
            alert('请选择游戏ID');return false;
        }
        if(!detail){
            alert('请填写详细描述');return false;
        }
        if(!result){
            alert('请填写处理结果');return false;
        }
        if(confirm('确定提交数据吗？')){
            return true;
        }else{
            return false;
        }
    }
</script>
