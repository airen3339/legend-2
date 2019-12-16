<script type="text/javascript" src="/My97DatePicker/WdatePicker.js"></script>
<div class="span10" id="datacontent">
    <ul class="breadcrumb">
        <li><a href="/content/server-log/index">客户端日志</a> <span class="divider">/</span></li>
        <li class="active">报错日志</li>
    </ul>
    <ul class="nav">
    </ul>
    <form action="/content/server-log/error-log" method="get" class="form-horizontal">
        <table class="table">
            <tr>
            </tr>
        </table>
    </form>
    <form action="/content/server-log/error-log" method="post">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>ID</th>
                <th style="width: 460px;">报错内容</th>
                <th >次数</th>
                <th>记录时间</th>
                <th>错误描述</th>
                <th >操作</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach($data as $kss => $v) {
                ?>
                <tr  class="text-item tdBorder">
                    <td style="width: 30px;"><span ><?php echo $v['id']?></span></td>
                    <td ><span><?php echo $v['content']?></span></td>
                    <td style="width: 15px;" ><span><?php echo $v['total']?></span></td>
                    <td style="width: 70px;" ><span ><?php echo date('Y-m-d H:i',$v['createTime'])?></span></td>
                    <td style="width: 150px;" ><span>
                            <textarea style="margin-top: 10px;"><?php echo $v['describe']?></textarea>
                            <a href="#" class="btn" onclick="saveDescribe(this,<?php echo $v['id']?>,'<?php echo $v['describe']?>')">保存</a>
                        </span></td>
                    <td  class="notSLH" style="width: 130px;">
                        <a class="btn" style="margin-top: 10px;" href="/content/server-log/error-detail?id=<?php echo $v['id'] ; ?>" >详情</a>
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
    function saveDescribe(_this,id,old){
        var val = $(_this).siblings("textarea").val();
        if(val == old){
            alert('内容没有修改');return false;
        }
        if(confirm('确定保存吗？')){
            $.post('/content/api/save-describe',{id:id,descri:val},function(e){
                alert(e.message);
                if(e.code != 1){
                    $(_this).siblings("textarea").val(old);
                }
            },'json');
        }
    }
</script>