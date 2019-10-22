<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <title>雷哥单词</title>
    <link rel="stylesheet" href="/cn/css/register.css?v=1.1">
</head>
<body>
<header>

</header>
<div class="content">
    <p>订单信息</p>

    <div class="input-group phone_register">
        <p class="input-item">
            <label>订单号</label>
            <input placeholder="" id="" value="<?php echo isset($order['orderNumber'])?$order['orderNumber']:''?>"  readonly>
        </p>
        <p class="input-item">
            <label>用户名</label>
            <input placeholder=""  value="<?php echo isset($order['username'])?$order['username']:''?>" readonly>
        </p>
        <p class="input-item get_msg">
            <label>商品</label>
            <input placeholder="" value="<?php echo isset($order['product'])?$order['product']:''?>"   readonly/>
        </p>
        <p class="input-item get_msg">
            <label>金额</label>
            <input placeholder="" value="<?php echo isset($order['money'])?$order['money']:''?>"   readonly/>

        </p>
        <p class="input-item get_msg">
            <label>元宝数</label>
            <input placeholder=""  value="<?php echo isset($order['yuanbao'])?$order['yuanbao']:''?>"    readonly/>

        </p>
    </div>
</div>

<script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript">

</script>

</body>
</html>