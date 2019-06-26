<link rel="stylesheet" type="text/css" href="/easyui/themes/default/easyui.css">
<link rel="stylesheet" type="text/css" href="/easyui/themes/icon.css">
<script type="text/javascript" src="/easyui/jquery.easyui.min.js"></script>
<script src="https://cdn.bootcss.com/bootstrap/4.0.0/js/bootstrap.min.js" ></script>
<style>
    .choose_download ul {
        clear: both;
        overflow: hidden;
        margin: 0;
    }
    .choose_download ul li {
        list-style: none;
        float: left;
        width: calc(33% - 20px);
        padding-left: 20px;
        text-align: left;
        margin-bottom: 10px;
    }
    .choose_download ul li input {
        margin-top: 0;
        margin-right: 5px;
    }
</style>
<div class="span10" id="datacontent">
    <ul class="breadcrumb">
        <li><a href="/content/rule/index">权限功能</a> <span class="divider">/</span></li>
        <li class="active">目录分类</li>
    </ul>
    <ul class="nav nav-tabs">

        <li class="dropdown pull-right">
            <a href="/content/rule/catalog-add">添加分类</a>
        </li>
    </ul>
    <table width="100%" class="table table-hover easyui-treegrid" title="分类表" data-options="
				url: '/content/api/get-category',
				method: 'get',
				idField: 'id',
				treeField: 'name'
			">
        <thead>
        <tr>
            <th data-options="field:'id'" align="middle">ID</th>
            <th data-options="field:'name'" align="left">分类名称</th>
            <th data-options="field:'createTime'" align="middle">创建时间</th>
            <th data-options="field:'action'" align="middle">操作</th>
            <th data-options="field:'rank'" align="middle">排序</th>
        </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
    <div class="pagination pagination-right">
        <ul></ul>
    </div>
</div>

<script type="text/javascript">

    function addId(id) {
        $("#excelId").attr("value",id);
    }

    function checkDelete(id){
        $.post('/content/api/check-delete',{id:id},function(re){
            if(re.code == 1){
                if(confirm("确定删除词包吗")){
                    location.href = "/content/rule/catalog-delete?id="+id;
                }
            }else{
                alert("请先删除子类");
            }
        },"json")
    }

    function setRank(id,_this){
        var rank = $("#index"+id).val();
        $.post('/content/api/set-rank',{id:id,rank:rank},function(res){
            // if(res.code == 1){
            //     location.href = '/content/category/index';
            // }
        },"json")
    }
</script>
