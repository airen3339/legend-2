<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>系统</title>
    <link rel="stylesheet" href="/cn/css/course/bootstrap-4.0.0-dist/css/bootstrap.css">
    <link rel="stylesheet" href="/cn/css/course/login.css">
</head>
<body>
<div class=" left-title">
    <h1 class="text-center"></h1>
    <img src="/cn/images/course/login/pic_1.png">
</div>
<div class="login-form">
    <p class="form-head text-center mb-0">USER LOGIN</p>
    <form action="/cn/index/login-in" method="post">
        <div class="form-group">
            <label><img src="/cn/images/course/login/user.png"> </label>
            <input placeholder="用户名" name="username">
        </div>
        <div class="form-group">
            <label><img src="/cn/images/course/login/mima.png"> </label>
            <input type="password" placeholder="密码" name="password">
        </div>
        <button type="submit" class="submit">登录</button>
    </form>
    <img class="shadow" src="/cn/images/course/login/shadow.png">
</div>
</body>
</html>
<script>
    sessionStorage.setItem('notice',0);
</script>