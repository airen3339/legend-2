<!-- 树形菜单选择 -->
<link rel="stylesheet" type="text/css" href="/easyui/themes/default/easyui.css">
<link rel="stylesheet" type="text/css" href="/easyui/themes/icon.css">
<script type="text/javascript" src="/easyui/jquery.easyui.min.js"></script>
<div class="span10" id="datacontent">
    <ul class="breadcrumb">
        <li><a href="/content/gm/index">GM工具</a> <span class="divider">/</span></li>
        <li class="active">商人排名编辑</li>
    </ul>
    <form action="/content/gm/merchant-order-add" method="post" class="form-horizontal" >
        <fieldset>
            <div class="control-group">
                <label for="modulename" class="control-label">账号</label>
                <div class="controls">
                    <input name="userId" type="text"   value="<?php echo isset($data['userId'])?$data['userId']:'';?>" readonly autocomplete="off"/>
                </div>
            </div>
            <div class="control-group">
            <div class="control-group">
                <label for="modulename" class="control-label">角色名</label>
                <div class="controls">
                    <input name="name" type="text"   value="<?php echo isset($data['name'])?$data['name']:'';?>" readonly autocomplete="off"/>
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label">角色id</label>
                <div class="controls">
                    <input name="roleId" type="text"   value="<?php echo isset($data['RoleID'])?$data['RoleID']:'';?>" readonly autocomplete="off"/>
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label">排名</label>
                <div class="controls">
                    <input name="rank" type="text"   value="<?php echo isset($data['Ingot'])?$data['Ingot']:'';?>"  autocomplete="off"/>
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label">区服</label>
                <div class="controls">
                    <select name="serverId">
                        <option value="0">请选择</option>
                        <?php foreach($servers as $k => $v){?>
                        <option value="<?php echo $v['id']?>" <?php if(isset($data['WorldID']) && $data['WorldID'] == $v['id']) echo 'selected';?>><?php echo $v['name']?></option>
                        <?php }?>
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
