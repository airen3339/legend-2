<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="title" content="雷哥GMAT在线后台">
    <meta name="description" content="雷哥GMAT在线后台">
    <meta name="keywords" content="雷哥GMAT在线后台">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <title>雷哥GMAT在线后台</title>
    <!-- Le styles -->
    <link href="/css/coreCss/bootstrap-combined.min.css" rel="stylesheet">
    <link href="/css/coreCss/layoutit.css" rel="stylesheet">
    <link href="/css/coreCss/plugin.css" rel="stylesheet">

    <script type="text/javascript" src="/easyui/jquery.min.js"></script>
</head>
<body>

<div class="navbar navbar-inverse">
    <div class="navbar-inner">
        <div class="container-fluid">
            <button data-target=".nav-collapse" data-toggle="collapse" class="btn btn-navbar" type="button"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
            <a href="index.php" target="_blank" class="brand"><img src="app/core/styles/images/favicon.png">雷哥GMAT在线后台</a>
            <div class="nav-collapse navbar-responsive-collapse collapse">
                <ul class="nav pull-right">
                    <li>
                        <a href="index.php" target="_blank">网站首页</a>
                    </li>
                    <li class="divider-vertical">
                    </li>
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#"><strong class="caret"></strong></a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="index.php?core-master-logout">退出管理</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>

        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row-fluid">
        <link rel="stylesheet" type="text/css" href="/easyui/themes/default/easyui.css">
        <link rel="stylesheet" type="text/css" href="/easyui/themes/icon.css">

        <script type="text/javascript" src="/easyui/jquery.easyui.min.js"></script>
        <div class="span10" id="datacontent">
            <legend></legend>
            <table class="table table-hover easyui-treegrid" title="模块"  data-options="
				url: '/basic/api/block?pid=1&status=1',
				method: 'get',
				rownumbers: true,
				idField: 'id',
				treeField: 'name'
			">
                <thead>
                <tr>
                    <th data-options="field:'name'"  align="middle" >模块名称</th>
                    <th data-options="field:'status'"  align="middle" >模块状态</th>
                    <th data-options="field:'action'"  align="middle">操作</th>
                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
            <div class="pagination pagination-right">
                <ul></ul>
            </div>
        </div>

    </div>
</div>
</body>
</html>

