
<div class="span10" id="datacontent">
    <ul class="breadcrumb">
        <li><a href="/content/gm/index">GM工具</a> <span class="divider">/</span></li>
        <li class="active">区服添加奖励</li>
    </ul>
    <form action="/content/gm/service-add-reward" method="post" class="form-horizontal">
        <fieldset>
            <div class="control-group">
                <label for="modulename" class="control-label">区服</label>
                <div class="controls">
                    <select name="content[category]">
                        <option value="0" >对对对</option>
                        <option value="1" >GMAT</option>
                    </select>
                </div>
            </div>

            <div class="control-group">
                <label for="modulename" class="control-label">UID</label>
                <div class="controls">
                    <input type="text"  name="content[uid]" value=""  >
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label">邮件标题</label>
                <div class="controls">
                    <input type="text"  name="content[emailTitle]" value="" datatype="emailTitle" >
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label">邮件内容</label>
                <div class="controls">
                    <textarea name="content[emailContent]"></textarea>
                </div>
            </div>

            <div class="control-group">
                <label for="modulename" class="control-label">选择发放物品</label>
                <div class="controls">
                    <select>
                        <option>1</option>
                        <option>2</option>
                    </select>&nbsp;&nbsp;
                    数量：<input type="text" style="width:70px" name="goodsNum" value=""/>
                    &nbsp;&nbsp;&nbsp;&nbsp;<a href="#" class="btn">确认</a>
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label">选择发放货币</label>
                <div class="controls">
                    <select>
                        <option>1</option>
                        <option>2</option>
                    </select>&nbsp;&nbsp;
                    数量：<input type="text" style="width:70px" name="moneyNum" value=""/>
                    &nbsp;&nbsp;&nbsp;&nbsp;<a href="#" class="btn">确认</a>
                </div>
            </div>
            <hr>
            <form action="#">
                <div class="control-group">
                    <ul class="reward-head controls reward-ul">
                        <li>奖品</li>
                        <li>数据</li>
                        <li>是否删除</li>
                    </ul>
                </div>
                <div class="control-group">
                    <ul class="reward-child controls reward-ul">
                        <li>1</li>
                        <li>11</li>
                        <li><a href="#" class="btn">删除</a></li>
                    </ul>
                </div>
                <div class="control-group">
                    <ul class="reward-child controls reward-ul">
                        <li>1</li>
                        <li>11</li>
                        <li><a href="#" class="btn">删除</a></li>
                    </ul>
                </div>
            </form>
            <div class="control-group">
                <div class="controls">
                    <input type="submit" class="btn btn-primary" value="提交">
                </div>
            </div>
        </fieldset>
    </form>
</div>
