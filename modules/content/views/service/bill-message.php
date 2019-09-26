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
    <form action="/content/service/bill-message" method="get" class="form-horizontal">
        <table class="table">
            <tr>
            </tr>
        </table>
    </form>
    <form action="/content/service/bill-message" method="post">
        <table class="table table-hover add_defined">
            <thead>
            <tr>
                <th>ID</th>
                <th>客服账号</th>
                <th>客服QQ</th>
                <th>账号状态</th>
                <th >操作</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach($service as $kss => $v) {
                if($kss == 10){
                    break;
                }
                ?>
                <tr>
                    <td ><span style="width: 80px; "><?php echo $v['id']?></span></td>
                    <td ><span style="width: 80px; "><?php echo $v['name']?></span></td>
                    <td ><span style="width: 100px; "><?php echo $v['qq']?></span></td>
                    <td ><span style="width: 115px;color: <?php echo $v['serviceStatus']==1?"blue":'red'?>;" id="serviceStatus"><?php echo $v['serviceStatus']==1?"在线":'离线'?></span></td>
                    <td  class="notSLH" style="width: 247px;">
                        <div>
                            <a class="btn" id="serviceAlter" href="#" onclick="alterStatus(<?php echo $v['id'];?>,<?php echo $v['serviceStatus'];?>)"><?php echo $v['serviceStatus']==1?"下线":'上线'?></a>
                        </div>
                    </td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </form>
</div>
<script>

    function alterStatus(id,type){//type 1-上线状态  0-离线状态
        $.post('/content/api/alter-status',{id:id,type:type},function(e){
            console.log(e);
            alert(e.message);
            if(e.code ==1){
                window.location.reload();
            }
        },'json')
    }
</script>