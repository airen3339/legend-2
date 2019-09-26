<script type="text/javascript" src="/My97DatePicker/WdatePicker.js"></script>
<div class="span10" id="datacontent">
    <ul class="breadcrumb">
        <li><a href="/content/service/index">客户模块</a> <span class="divider">/</span></li>
        <li class="active">单据信息</li>
    </ul>
    <form action="/content/service/bill-message-add" method="post" class="form-horizontal" onsubmit="javascript:if(confirm('确定提交并推送客户端吗？')){return true}else{
        return false;
    }">
        <input type="hidden" name="id" value="<?php echo isset($notice['id'])?$notice['id']:''?>">
        <fieldset>
            <div class="control-group">
                <label for="modulename" class="control-label"><span style="color:red">* </span>单据类型</label>
                <div class="controls">
                    <select name="billType" >
                        <option value="0">请选择</option>
                        <?php
                        foreach($billTypes as $k => $v){ ?>
                            <option value='<?php echo $v['id']?>' <?php if(isset($bill['billType']) && $bill['billType'] == $v['id']) echo 'selected';?>><?php echo $v['name']?></option>";
                            <?php
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label"><span style="color:red">* </span>单据来源</label>
                <div class="controls">
                    <select name="billSource" >
                        <option value="0">请选择</option>
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
                <label for="modulename" class="control-label"><span style="color:red">* </span>游戏所属</label>
                <div class="controls">
                    <select name="billGame" >
                        <option value="0">请选择</option>
                        <?php
                        foreach($billGames as $k => $v){ ?>
                            <option value='<?php echo $v['id']?>' <?php if(isset($bill['billGame']) && $bill['billGame'] == $v['id']) echo 'selected';?>><?php echo $v['name']?></option>";
                            <?php
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label"><span style="color:red">* </span>一级分类</label>
                <div class="controls">
                    <select name="billGame">
                        <option value="0">请选择</option>
                        <?php
                        foreach($billQuesParent as $k => $v){ ?>
                            <option value='<?php echo $v['id']?>' <?php if(isset($bill['quesParent']) && $bill['quesParent'] == $v['id']) echo 'selected';?>><?php echo $v['name']?></option>";
                            <?php
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label"><span style="color:red">* </span>二级分类</label>
                <div class="controls">
                    <select name="billGame" >
                        <option value="0">请选择</option>
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
                <label for="modulename" class="control-label">龙渊账号</label>
                <div class="controls">
                    <input   type="text" id="account" name="account"  value="<?php echo isset($bill['account'])?$bill['account']:''?>" autocomplete="off"/>
                </div>
            </div>

            <div class="control-group">
                <label for="modulename" class="control-label">VIP等级</label>
                <div class="controls">
                    <select name="vipLevels" >
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
                <label for="modulename" class="control-label">游戏昵称</label>
                <div class="controls">
                    <input size="10" type="text" id="gameName" name="gameName"  value="<?php echo isset($bill['gameName'])?$bill['gameName']:''?>" autocomplete="off"/>
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label"><span style="color:red">* </span>游戏大区</label>
                <div class="controls">
                    <input  size="10" type="text" id="gameServer" name="gameServer"  value="<?php echo isset($bill['gameServer'])?$bill['gameServer']:''?>" autocomplete="off"/>
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label"><span style="color:red">* </span>游戏ID</label>
                <div class="controls">
                    <input  size="10" type="text" id="gameId" name="gameId"  value="<?php echo isset($bill['gameId'])?$bill['gameId']:''?>" autocomplete="off"/>
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label"><span style="color:red">* </span>下载渠道</label>
                <div class="controls">
                    <input  size="10" type="text" id="download" name="download"  value="<?php echo isset($bill['download'])?$bill['download']:''?>" autocomplete="off"/>
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label">设备型号</label>
                <div class="controls">
                    <input size="10" type="text" id="device" name="device"  value="<?php echo isset($bill['device'])?$bill['device']:''?>" autocomplete="off"/>
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label">系统版本</label>
                <div class="controls">
                    <input  size="10" type="text" id="version" name="version"  value="<?php echo isset($bill['version'])?$bill['version']:''?>" autocomplete="off"/>
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label">联系电话</label>
                <div class="controls">
                    <input  size="10" type="text" id="phone" name="phone"  value="<?php echo isset($bill['phone'])?$bill['phone']:''?>" autocomplete="off"/>
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label">联系QQ</label>
                <div class="controls">
                    <input  size="10" type="text" id="qq" name="qq"  value="<?php echo isset($bill['qq'])?$bill['qq']:''?>" autocomplete="off"/>
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label">联系邮箱</label>
                <div class="controls">
                    <input  size="10" type="text" id="email" name="email"  value="<?php echo isset($bill['email'])?$bill['email']:''?>" autocomplete="off"/>
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label">文件附件</label>
                <div class="controls">
                    <input  size="10" type="text" id="email" name="email"  value="<?php echo isset($bill['email'])?$bill['email']:''?>" autocomplete="off"/>
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label"><span style="color:red">* </span>详细描述</label>
                <div class="controls">
                    <textarea  id="detail" name="detail"><?php echo isset($bill['detail'])?$bill['detail']:''?></textarea>
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label"><span style="color:red">* </span>处理结果</label>
                <div class="controls">
                    <textarea  id="result" name="result"><?php echo isset($bill['result'])?$bill['result']:''?></textarea>
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
