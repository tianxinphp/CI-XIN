<!DOCTYPE html>
<html class="loginHtml">
<head>
    <meta charset="utf-8">
    <title>登录</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <link rel="icon" href="<?php echo base_url('assets'.DIRECTORY_SEPARATOR.'favicon.ico')?>">
    <?php echo $loadPublicJsAndCss ?>
</head>
<body class="loginBody">
<div class="layui-carousel" id="login_bk">
    <div carousel-item="">
        <?php
            foreach ($pictures as $picture ){
                echo '<div><img src="'.$picture['base_url'].$picture['url'].$picture['path'].'"></div>';
            }
        ?>
    </div>
</div>
<form class="layui-form" id="loginForm">
    <div class="login_face"><img src="<?php echo $logo['base_url'].$logo['url'].$logo['path']  ?>" class="userAvatar"></div>
    <div class="layui-form-item input-item">
        <label for="userName">用户名</label>
        <input type="text" placeholder="请输入用户名" autocomplete="off" name="userName" id="userName" class="layui-input" lay-verify="required|username">
    </div>
    <div class="layui-form-item input-item">
        <label for="password">密码</label>
        <input type="password" placeholder="请输入密码" autocomplete="off" name="password" id="password" class="layui-input" lay-verify="required|password">
    </div>
    <div class="layui-form-item input-item" id="imgCode">
        <label for="code">验证码</label>
        <input type="text" placeholder="请输入验证码" autocomplete="off" name="code" id="code" maxlength="5" lay-verify="required" class="layui-input">
        <?php  echo $verification ?>
    </div>
    <div class="layui-form-item">
        <button class="layui-btn layui-block" lay-filter="login" lay-submit>登录</button>
    </div>
    <div class="layui-form-item layui-row">
        <a href="javascript:;" class="seraph icon-qq layui-col-xs4 layui-col-sm4 layui-col-md4 layui-col-lg4"></a>
        <a href="javascript:;" class="seraph icon-wechat layui-col-xs4 layui-col-sm4 layui-col-md4 layui-col-lg4"></a>
        <a href="javascript:;" class="seraph icon-sina layui-col-xs4 layui-col-sm4 layui-col-md4 layui-col-lg4"></a>
    </div>
    <?php echo $csrf ?>
</form>
<?php echo $loadJs?>
</body>
</html>