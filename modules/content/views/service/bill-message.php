<script type="text/javascript" src="/My97DatePicker/WdatePicker.js"></script>
<div class="span10" id="datacontent">
    <ul class="breadcrumb">
        <li><a href="/content/service/index">客服模块</a> <span class="divider">/</span></li>
        <li class="active">单据信息</li>
    </ul>
    <ul class="nav">
        <li class="dropdown pull-right">
            <a class="dropdown-toggle"
               href="/content/service/bill-message-add">添加单据</a>
        </li>
    </ul>
    <form action="/content/service/bill-message" method="get" class="form-horizontal" id="excel-form">
        <table class="table">
            <tr>
                <td>
                    开始日期：
                </td>
                <td>
                    <input class="input-small Wdate" autocomplete="off" onclick="WdatePicker()" type="text" size="10" id="beginTime"  name="beginTime" value="<?php echo isset($_GET['beginTime'])?$_GET['beginTime']:''?>"/>
                </td>
                <td>
                    结束日期：
                </td>
                <td>
                    <input class="input-small Wdate" autocomplete="off" onclick="WdatePicker()"  size="10" type="text" id="endTime" name="endTime"  value="<?php echo isset($_GET['endTime'])?$_GET['endTime']:''?>"/>
                </td>
                <td>
                    一级分类：
                </td>
                <td>
                    <select name="quesParent" id="quesParent" style="width: 100px" onchange="getChildCategory()">
                        <option value="0">请选择</option>
                        <?php
                        foreach($quesParent as $k => $v){ ?>
                            <option value='<?php echo $v['id']?>' <?php if(isset($_GET['quesParent']) && $_GET['quesParent'] == $v['id']) echo 'selected';?>><?php echo $v['name']?></option>";
                            <?php
                        }
                        ?>
                    </select>
                </td>
                <td>
                    二级分类：
                </td>
                <td>
                    <select name="quesChild" id="quesChild" style="width: 100px">
                        <option value="0">请选择</option>
                        <?php
                        foreach($quesChild as $k => $v){ ?>
                            <option value='<?php echo $v['id']?>' <?php if(isset($_GET['quesChild']) && $_GET['quesChild'] == $v['id']) echo 'selected';?>><?php echo $v['name']?></option>";
                            <?php
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    下载渠道：
                </td>
                <td>
                    <input style="width: 100px" size="10" type="text" id="download" name="download"  value="<?php echo isset($_GET['download'])?$_GET['download']:''?>"/>
                </td>
                <td>
                    区服：
                </td>
                <td>
                    <select name="server" style="width: 100px">
                        <option value="0">请选择</option>
                        <?php
                        foreach($servers as $k => $v){ ?>
                            <option value='<?php echo $v['id']?>' <?php if(isset($_GET['server']) && $_GET['server'] == $v['id']) echo 'selected';?>><?php echo $v['name']?></option>";
                            <?php
                        }
                        ?>
                    </select>
                </td>
                <td>
                    单据类型：
                </td>
                <td>
                    <select name="billType" style="width: 100px">
                        <option value="0">请选择</option>
                        <?php
                        foreach($billTypes as $k => $v){ ?>
                            <option value='<?php echo $v['id']?>' <?php if(isset($_GET['billType']) && $_GET['billType'] == $v['id']) echo 'selected';?>><?php echo $v['name']?></option>";
                            <?php
                        }
                        ?>
                    </select>
                </td>
                <td>
                    单据来源：
                </td>
                <td>
                    <select name="billSource" style="width: 100px">
                        <option value="0">请选择</option>
                        <?php
                        foreach($billSources as $k => $v){ ?>
                            <option value='<?php echo $v['id']?>' <?php if(isset($_GET['billSource']) && $_GET['billSource'] == $v['id']) echo 'selected';?>><?php echo $v['name']?></option>";
                            <?php
                        }
                        ?>
                    </select>
                </td>

            </tr>
            <tr>
                <td>
                    游戏昵称：
                </td>
                <td>
                    <input style="width: 100px" size="10" type="text" id="name" name="name"  value="<?php echo isset($_GET['name'])?$_GET['name']:''?>"/>
                </td>
                <td>
                    游戏账号：
                </td>
                <td>
                    <input style="width: 100px" size="10" type="text" id="account" name="account"  value="<?php echo isset($_GET['account'])?$_GET['account']:''?>"/>
                </td>
                <td>
                    游戏ID：
                </td>
                <td>
                    <input style="width: 100px" size="10" type="text" id="gameId" name="gameId"  value="<?php echo isset($_GET['gameId'])?$_GET['gameId']:''?>"/>
                </td>
                <td>
                    联系电话：
                </td>
                <td>
                    <input style="width: 100px" size="10" type="text" id="phone" name="phone"  value="<?php echo isset($_GET['phone'])?$_GET['phone']:''?>"/>
                </td>
            </tr>
            <tr>
                <td>
                    联系QQ：
                </td>
                <td>
                    <input style="width: 100px" size="10" type="text" id="qq" name="qq"  value="<?php echo isset($_GET['qq'])?$_GET['qq']:''?>"/>
                </td>
                <td>
                    联系邮箱：
                </td>
                <td>
                    <input style="width: 100px" size="10" type="text" id="email" name="email"  value="<?php echo isset($_GET['email'])?$_GET['email']:''?>"/>
                </td>
                <td>
                    操作人员：
                </td>
                <td>
                    <input style="width: 100px" size="10" type="text" id="creator" name="creator"  value="<?php echo isset($_GET['creator'])?$_GET['creator']:''?>"/>
                </td>
                <td>
                    <input type="hidden" value='0' name="excel" id="excel" />
                    <button class="btn btn-primary" type="submit">查询</button>&nbsp;&nbsp;
                    <a href="#" class="btn btn-primary" onclick="excelDownload()">导出</a>
                </td>
            </tr>
        </table>
    </form>
    <form action="/content/service/bill-message" method="post">
        <table class="table table-hover ">
            <thead>
            <tr>
                <th>ID</th>
                <th>单据类型</th>
                <th>单据来源</th>
                <th>一级分类</th>
                <th>二级分类</th>
                <th>游戏所属</th>
                <th>游戏账号</th>
                <th>游戏昵称</th>
                <th>游戏大厅</th>
                <th>下载渠道</th>
                <th>游戏ID</th>
<!--                <th>系统版本</th>-->
                <th>设备型号</th>
                <th>联系电话</th>
<!--                <th>联系QQ</th>-->
<!--                <th>联系邮箱</th>-->
                <th >操作</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach($bills as $kss => $v) {
                ?>
                <tr class="tdBorder text-item tdSpan">
                    <td style="width: 20px"><span ><?php echo $v['id']?></span></td>
                    <td ><span ><?php echo $v['billType']?></span></td>
                    <td ><span ><?php echo $v['billSource']?></span></td>
                    <td ><span ><?php echo $v['quesParent']?></span></td>
                    <td ><span ><?php echo $v['quesChild']?></span></td>
                    <td ><span ><?php echo $v['billGame']?></span></td>
                    <td ><span ><?php echo $v['account']?></span></td>
                    <td ><span ><?php echo $v['gameName']?></span></td>
                    <td style="width: 59px"><span ><?php echo $v['gameServer']?></span></td>
                    <td style="width: 80px"><span ><?php echo $v['download']?></span></td>
                    <td ><span ><?php echo $v['gameId']?></span></td>
<!--                    <td ><span >--><?php //echo $v['version']?><!--</span></td>-->
                    <td ><span ><?php echo $v['device']?></span></td>
                    <td ><span ><?php echo $v['phone']?></span></td>
<!--                    <td ><span >--><?php //echo $v['qq']?><!--</span></td>-->
<!--                    <td ><span >--><?php //echo $v['email']?><!--</span></td>-->
                    <td  class="notSLH" >
                        <div>
                            <a class="btn" id="serviceAlter"  href="/content/service/bill-message-add?id=<?php echo $v['id'];?>">修改</a>
                        </div>
                    </td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </form>
    <div class="pagination pagination-right">
        <span style="font-size: 17px;position: relative;bottom: 7px;">共<?php echo $count;?>条&nbsp;</span>
        <?php if($count > 200){?>
            <span style="font-size: 17px;position: relative;bottom: 5px;">
            <a onclick="jumpPage()">Go</a>&nbsp;
            <input type="text" style="width: 20px;height: 18px;" id="jumpPage">&nbsp;页
        </span>
        <?php }?>
        <?php use yii\widgets\LinkPager;
        echo LinkPager::widget([
            'pagination' => $page,
        ])?>
    </div>
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
    function alterStatus(id,type){//type 1-上线状态  0-离线状态
        $.post('/content/api/alter-status',{id:id,type:type},function(e){
            console.log(e);
            alert(e.message);
            if(e.code ==1){
                window.location.reload();
            }
        },'json')
    }
    function excelDownload(){
        if(confirm('确定导出数据吗？')){
            $('#excel').val(1);
            $('#excel-form').submit();
        }
    }
</script>