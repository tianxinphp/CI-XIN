<?php
/**
 * Created by PhpStorm.
 * User: tianxin
 * Date: 2018/2/23
 * Time: 16:19
 */
/**
 * 入口文件
 * ---------------------------------------------
 * 设定常量,定义本程序为开发环境,暂时还有production,testing环境
 */
defined('ENVIRONMENT') or define('ENVIRONMENT','development');
/******************************************************************/
switch (ENVIRONMENT) {//CI为了兼顾5.3以下版本做的一些环境配置
    //以下为页面显示报错级别
    case 'development'://开发环境
        /* 打印异常到页面 */
        error_reporting(-1);//-1和1都是显示全部报错内容,0为不显示任何报错
        ini_set('display_errors', 1);
        break;
    case 'testing';
    case 'production':
        /* 设置 PHP 的报错级别并返回当前级别 */
        ini_set('display_errors', 0);
        if (version_compare(PHP_VERSION, '5.3', '>=')) {
            error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
        } else {
            error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
        }
        break;

    default:
        /* 向客户端发送原始的 HTTP 报头 */
        header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
        echo 'The application environment is not set correctly.';
        exit(1); // EXIT_ERROR
}
