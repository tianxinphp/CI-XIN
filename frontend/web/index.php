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
//项目根目录文件夹
defined('BASEDIR') or define('BASEDIR',dirname(pathinfo(__DIR__,PATHINFO_DIRNAME)));
defined('FRONTENDDIR') or define('FRONTENDDIR',pathinfo(__DIR__,PATHINFO_DIRNAME));
/******************************************************************/
switch (ENVIRONMENT) {//CI为了兼顾5.3以下版本做的一些环境配置
    //以下为页面显示报错级别
    case 'development'://开发环境
        /* 打印异常到页面 */
        error_reporting(-1);//-1和E_ALL都是显示全部报错内容,最好写-1,为了php版本兼容,0为不显示任何报错,其它等级报错都有定义好的常量
        ini_set('display_errors', 1);//display_errors的权限等级比error_reporting()大
        break;
    case 'testing';//测试环境,以后再说
    case 'production':
        /* 设置 PHP 的报错级别并返回当前级别 */
        ini_set('display_errors', 0);//不显示任何错误
        if (version_compare(PHP_VERSION, '5.3', '>=')) {//比较版本是否大于5.3,对5.3及以下版本的兼容
            //展示所有错误除了E_NOTICE,E_STRICT,E_DEPRECATED,E_STRICT,E_USER_NOTICE,E_USER_DEPRECATED以外
            /********
             *5.3版本以上新增了E_DEPRECATED与E_USER_DEPRECATED报错参数
             *E_DEPRECATED 当函数因为php版本原因被废弃时会报此类错误
             *E_USER_DEPRECATED 所有带user的报错常量都是用户自定义触发的
             *通过trigger_error('报错内容',E_USER_DEPRECATED);主动触发报错函数
             */
            error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
        } else {
            //展示所有错误除了E_NOTICE,E_STRICT,E_USER_NOTICE以外
            error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
        }
        break;

    default:
        /* 向客户端发送原始的 HTTP 报头 */
        /**
         * header() 函数向客户端发送原始的 HTTP 报头
         * header(string,replace,code)
         * string是报文内容,可以的话可以直接写协议,返回code,内容
         * replace是是否要替代之前的所有报文头信息
         * code是强制本次报文返回http_code为一个值
         */
        header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
        echo 'The application environment is not set correctly.';
        exit(1); // 非正常运行导致退出程序,正常运行退出程序是exit(0)die()与exit()中可填string类型数字
}

/**
 * 框架核心代码文件夹
 */
$framework_core_folder = BASEDIR.DIRECTORY_SEPARATOR.'framework_core';

/**
 * 本应用程序文件夹
 */
$application_folder=BASEDIR.DIRECTORY_SEPARATOR.'frontend';

/**
 *本应用视图文件夹
 */
$view_folder=FRONTENDDIR.DIRECTORY_SEPARATOR.'views';

/**
 * 判断是否以php_cli(命令行模式)运行
 *
 * php_cli模式运行的常量
 * STDIN     标准输入
 * STDOUT    标准输出
 * STDERR    标准错误流
 */
if(defined('STDIN')){
    //切换当前工作目录为根目录
    chdir(BASEDIR);
}

/**
 * 确认框架核心代码文件夹和本应用程序文件夹的路径
 */
if($_temp=realpath($framework_core_folder)!==FALSE){
    $framework_core_folder=$framework_core_folder.DIRECTORY_SEPARATOR;
}else{

}
