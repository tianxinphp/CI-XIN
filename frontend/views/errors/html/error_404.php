<?php
defined('COREPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>404 Page Not Found</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <link rel="stylesheet" href="http://127.0.0.1/assets\public/css/layui/layui.css" type="text/css" media="all" charset="utf-8">
    <link rel="stylesheet" href="http://127.0.0.1/assets\public/css/public.css" type="text/css" media="all" charset="utf-8">
    <script src="http://127.0.0.1/assets\public/js/layui/layui.js" type="text/javascript" charset="utf-8"></script>
<!--    <link rel="stylesheet" href="--><?php //echo base_url('assets'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.'layui'.DIRECTORY_SEPARATOR.'layui.css') ?><!--" media="all" />-->
<!--    <link rel="stylesheet" href="--><?php //echo base_url('assets'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.'public.css') ?><!--" media="all" />-->
</head>
<body class="childrenBody">
<div class="noFind">
    <div class="ufo">
        <i class="seraph icon-test ufo_icon"></i>
        <i class="layui-icon page_icon">&#xe638;</i>
    </div>
    <div class="page404">
        <i class="layui-icon">&#xe61c;</i>
        <p><?php echo  $heading ?></p>
        <p><?php echo  $message? $message:'我勒个去，页面被外星人挟持了!' ?></p>
    </div>
</div>
<!--<link rel="stylesheet" href="--><?php //echo base_url('assets'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.'layui'.DIRECTORY_SEPARATOR.'layui.js') ?><!--" media="all" />-->
</body>
</html>