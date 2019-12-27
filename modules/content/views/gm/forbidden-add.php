
<div class="span10" id="datacontent">
    <ul class="breadcrumb">
        <li><a href="/content/gm/index">GM工具</a> <span class="divider">/</span></li>
        <li class="active">禁言封号编辑</li>
    </ul>
    <form action="/content/gm/forbidden-add" method="post" class="form-horizontal" onsubmit="javascript:if(confirm('确定进行该操作吗？')){return true;}else{return false;}">
        <fieldset>
            <div class="control-group">
                <label for="modulename" class="control-label">角色信息</label>
                <div class="controls">
                    <input type="text"  name="userId" value=""  >
                    <span class="help-block">请输入游戏账号或角色名或角色id</span>
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label">操作类型</label>
                <div class="controls">
                    <select name="type" id="type" onchange="typeChange()">
                        <option value=0>请选择</option>
                        <option value="1">账号禁言</option>
                        <option value="2">账号封号</option>
                        <option value="3">账号禁言解封</option>
                        <option value="4">账号封号解封</option>
                    </select>
                </div>
            </div>
            <div class="control-group jyDiv" style="display: none">
                <label for="modulename" class="control-label">禁言时间</label>
                <div class="controls">
                    <select name="jyday" id="type">
                        <option value='1'>1天</option>
                        <option value="3">3天</option>
                        <option value="7">1周</option>
                        <option value="30">1个月</option>
                        <option value="365">1年</option>
                    </select>
                </div>
            </div>
            <div class="control-group fhDiv" style="display: none">
                <label for="modulename" class="control-label">封号时间</label>
                <div class="controls">
                    <select name="fhday" id="type">
                        <option value='30'>1个月</option>
                        <option value="90">3个月</option>
                        <option value="150">5个月</option>
                        <option value="180">半年</option>
                        <option value="365">1年</option>
                    </select>
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
<script>
    function typeChange(){
        var type = $('#type').val();
        if(type == 1){//禁言
            $('.jyDiv').css('display','block');
            $('.fhDiv').css('display','none');
        }
        if(type == 2){//封号
            $('.fhDiv').css('display','block');
            $('.jyDiv').css('display','none');
        }
        if(type > 2 || type < 1){
            $('.fhDiv').css('display','none');
            $('.jyDiv').css('display','none');
        }
    }
</script>
