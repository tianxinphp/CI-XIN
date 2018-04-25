<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>首页--layui后台管理模板 2.0</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <?php echo $loadPublicJsAndCss ?>
</head>
<body class="childrenBody">
<blockquote class="layui-elem-quote layui-bg-green">
    <div id="nowTime"></div>
</blockquote>
<div class="layui-row layui-col-space10 panel_box">
    <div class="panel layui-col-xs12 layui-col-sm6 layui-col-md4 layui-col-lg4">
        <a href="javascript:;" data-url="https://github.com/BrotherMa/layuicms2.0" target="_blank">
            <div class="panel_icon layui-bg-black">
                <i class="layui-anim seraph icon-github"></i>
            </div>
            <div class="panel_word">
                <span>Github</span>
                <cite>模版下载链接</cite>
            </div>
        </a>
    </div>
    <div class="panel layui-col-xs12 layui-col-sm6 layui-col-md4 layui-col-lg4">
        <a href="javascript:;" data-url="https://gitee.com/layuicms/layuicms2.0" target="_blank">
            <div class="panel_icon layui-bg-red">
                <i class="layui-anim seraph icon-oschina"></i>
            </div>
            <div class="panel_word">
                <span>码云</span>
                <cite>模版下载链接</cite>
            </div>
        </a>
    </div>
    <div class="panel layui-col-xs12 layui-col-sm6 layui-col-md4 layui-col-lg4">
        <a href="javascript:;">
            <div class="panel_icon layui-bg-blue">
                <i class="layui-anim seraph icon-clock"></i>
            </div>
            <div class="panel_word">
                <span class="loginTime"></span>
                <cite>上次登录时间</cite>
            </div>
        </a>
    </div>
</div>
<div class="layui-row layui-col-space10">
    <div class="layui-col-lg6 layui-col-md12">
        <blockquote class="layui-elem-quote title">系统基本参数</blockquote>
        <table class="layui-table magt0">
            <colgroup>
                <col width="150">
                <col>
            </colgroup>
            <tbody>
            <tr>
                <td>当前版本</td>
                <td class="version"></td>
            </tr>
            <tr>
                <td>服务器环境</td>
                <td class="server"></td>
            </tr>
            <tr>
                <td>数据库版本</td>
                <td class="dataBase"></td>
            </tr>
            <tr>
                <td>最大上传限制</td>
                <td class="maxUpload"></td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="layui-col-lg6 layui-col-md12">
        <blockquote class="layui-elem-quote title">发展历程&更新日志</blockquote>
    </div>
</div>
<?php echo $loadJs ?>
</body>
</html>