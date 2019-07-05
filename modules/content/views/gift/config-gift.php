<script type="text/javascript" src="/My97DatePicker/WdatePicker.js"></script>
<div class="span10" id="datacontent">
    <ul class="breadcrumb">
        <li><a href="/content/gift/index">礼包管理</a> <span class="divider">/</span></li>
        <li class="active">配置礼包</li>
    </ul>
    <form action="/content/gift/config-gift" method="post" class="form-horizontal">
        <fieldset>
            <div class="control-group">
                <label for="modulename" class="control-label">区服</label>
                <div class="controls">
                    <select name="content[service]">
                        <option value="0" >对对对</option>
                        <option value="1" >GMAT</option>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label">渠道</label>
                <div class="controls">
                    <select name="content[channel]">
                        <option value="0" >对对对</option>
                        <option value="1" >GMAT</option>
                    </select>
                </div>
            </div>

            <div class="control-group">
                <label for="modulename" class="control-label">礼包名称</label>
                <div class="controls">
                    <input type="text"  name="content[name]" value=""  >
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label">评估</label>
                <div class="controls">
                    <input type="text"  name="content[assess]" value=""  >
                </div>
            </div>
            <div class="control-group">
                <label for="modulename" class="control-label">备注</label>
                <div class="controls">
                    <textarea name="content[remark]"></textarea>
                </div>
            </div>

            <div class="control-group">
                <label for="modulename" class="control-label">选择物品</label>
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
                <label for="modulename" class="control-label">选择货币</label>
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
