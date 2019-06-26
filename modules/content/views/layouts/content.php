<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="title" content="后台">
    <meta name="description" content="后台">
    <meta name="keywords" content="后台">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <title>后台</title>
    <!-- Le styles -->
    <link href="/css/coreCss/bootstrap-combined.min.css" rel="stylesheet">
    <link href="/css/coreCss/layoutit.css" rel="stylesheet">
    <link href="/css/coreCss/plugin.css" rel="stylesheet">
    <link href="/css/site.css" rel="stylesheet">
    <script type="text/javascript" src="/js/jquery-1.7.2.js"></script>
    <style>
        .choose_download {
            padding-top: 20px;
            top: 50%;
            width: 350px;
            height: 290px;
            left: 60%;
            text-align: center;
            padding-right: 0 !important;
            display: none;
        }

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

        .download_submit {
            width: 80px;
            height: 30px;
            margin: 5px auto;
        }
            border-right: 1px red;
        }
    </style>
</head>
<body>
<div class="navbar navbar-inverse">
    <div class="navbar-inner">
        <div class="container-fluid">
            <button data-target=".nav-collapse" data-toggle="collapse" class="btn btn-navbar" type="button"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
            <a href="/content/index/index" target="_blank" class="brand"><img src="/css/coreCss/img/favicon.png">后台</a>
            <div class="nav-collapse navbar-responsive-collapse collapse">
                <?php use app\commands\background\HeadWidget;?>
                <?php HeadWidget::begin();?>
                <?php HeadWidget::end();?>
            </div>

        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span2">
            <?php use app\commands\background\LeftWidget;?>
            <?php LeftWidget::begin();?>
            <?php LeftWidget::end();?>
            <!-- $content变量的值 就是子页面渲染之后的代码。也就是说子页面中的内容将输出到这个地方-->
        </div>
        <?=$content ?>
    </div>
</div>
<div class="modal choose_download updatePass  " id="myModal">
    <input type="hidden" name="excelId" id="excelId" value="0" />
    <ul>
        <li>旧密码：<input type="password" id="oldPass"  name="oldPass"  autocomplete="off" /></li>
    </ul>
    <ul>
        <li>新密码：<input type="password" id="newPass" name="newPass"  autocomplete="off" /></li>
    </ul>
    <ul>
        <li>确认密码：<input type="password" id="surePass" name="surePass"  autocomplete="off" /></li>
    </ul>
    <button class="download_submit"  onclick="updatePass()">修改</button><br/>
    <button class="download_submit"  onclick="cancelPass()">取消</button>
</div>
</body>
</html>