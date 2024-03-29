<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <title>微信h5支付</title>
    <link rel="stylesheet" href="/cn/css/register.css?v=1.2">
    <style>
        .wxBtn{
            background: rgba(55, 188, 133, 1);
            width: 85px;
            height: 43px;
            border-radius: 9px;
            display: block;
            margin: 0 auto;
        }
    </style>
</head>
<body>
<div class="content">
    <br/>
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

        <div>
            <button class="wxBtn" onclick="getWxpayUrl(<?php echo isset($order['id'])?$order['id']:'0';?>)">立即支付</button>
        </div>
        <br/>
        <p>

        </p>
    </div>
</div>

<script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript">
    function getWxpayUrl(orderId){
        if(confirm('确定安装了微信？')){
            $.post('/pay/wx/wx-pay',{orderId:orderId},function(e){
                if(e.code ==1){
                    location.href=e.payUrl
                }else{
                    alert(e.code);
                }
            },'json');
        }
    }
</script>

</body>
</html>