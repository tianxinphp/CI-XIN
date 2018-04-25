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
define('BASEDIR',dirname(pathinfo(__DIR__,PATHINFO_DIRNAME)));
//本应用根目录
/**
 * pathinfo()获取路径信息
 *Array
 * (
 *   [dirname] => D:\wamp64\www\CI-XIN
 *   [basename] => index.php
 *   [extension] => php
 *   [filename] => test
 * )
 */
define('FRONTENDDIR',pathinfo(__DIR__,PATHINFO_DIRNAME));
//本文件名
/**
 * basename(path,suffix)
 * suffix是去掉的文件名后缀,默认不去
 */
define('SELF',basename(__FILE__));

//入口文件所在文件夹
define('INDEXDIR',dirname(__FILE__));

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

//本应用资源文件夹
$assets_folder=INDEXDIR.DIRECTORY_SEPARATOR.'assets';
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
    //文件夹路径存在
    $framework_core_folder=$framework_core_folder.DIRECTORY_SEPARATOR;
}else{
    $framework_core_folder = strtr(rtrim($framework_core_folder, '/\\'),'/\\',DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
}

//文件夹不存在
if(!is_dir($framework_core_folder)){
    /* 向客户端发送原始的 HTTP 报头 */
    header('HTTP/1.1 503 Service Unavailable.',true,503);
    echo 'Your system folder path does not appear to be set correctly. Please open the following file and correct this: '.SELF;
    exit(3); //3仅仅表示一个状态,是因为配置有问题导致程序退出
}

/**
 *CI核心代码路径
 */
define('COREPATH',$framework_core_folder);
/**
 * 项目路径
 */
define('BASEPATH',BASEDIR.DIRECTORY_SEPARATOR);

/**
 * 确认本应用程序文件夹的路径,同上
 */
if(is_dir($application_folder)){
    if($_temp=realpath($application_folder)!==FALSE){
        $application_folder=$application_folder.DIRECTORY_SEPARATOR;
    }else{
        $application_folder=strtr(rtrim($application_folder,'/\\'),'/\\',DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
    }
}else{
    header('HTTP/1.1 503 Service Unavailable',true,503);
    echo 'Your application folder path does not appear to be set correctly. Please open the following file and correct this: '.SELF;
    exit(3);
}

/**
 *应用文件夹路径
 */
define('FRONTENDPATH',$application_folder);

/**
 * 确认视图路径
 */
if(!isset($view_folder)&&empty($view_folder)&&is_dir(FRONTENDPATH.'views'.DIRECTORY_SEPARATOR)){
    //如果$view_folder不存在或为空,在应用文件夹下有
    $view_folder=FRONTENDPATH.'views'.DIRECTORY_SEPARATOR;
}else if(is_dir($view_folder)){
    if($_temp=realpath($view_folder)!==FALSE){
        $view_folder=$view_folder.DIRECTORY_SEPARATOR;
    }else{
        $view_folder=strtr(rtrim($view_folder,'/\\'),'/\\',DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
    }
}else{
    header('HTTP/1.1 503 Service Unavailable',true,503);
    echo 'Your view folder path does not appear to be set correctly. Please open the following file and correct this: '.SELF;
    exit(3);
}

/**
 * 定义视图常量
 */
define('VIEWPATH',$view_folder);

if(is_dir($assets_folder)){
    if(realpath($assets_folder)!==FALSE){
        $assets_folder=$assets_folder.DIRECTORY_SEPARATOR;
    }else{
        $assets_folder=strtr(rtrim($assets_folder,'/\\'),'/\\',DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
    }
}else{
    header('HTTP/1.1 503 Service Unavailable',true,503);
    echo 'Your assets folder path does not appear to be set correctly. Please open the following file and correct this: '.SELF;
    exit(3);
}

define('ASSETSPATH',$assets_folder);

//环境参数
$assign_to_config['defaultDatabase'] = 'frontend';
$assign_to_config['defaultDatabaseCacheDir'] = FRONTENDPATH.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.'db';

/**
 * 本页是应用入口页,干了这么几件事
 * 1.定义环境状态常量
 * 2.定义项目根目录与本应用根目录文件夹与本入口文件全名
 * 3.定义不同代码运行环境下的报错等级
 * 4.定义CI核心代码文件夹,应用程序文件夹,视图文件夹
 * 5.判断php是否是以cli模式运行(是,切换文件夹到根目录)
 * 6.确认CI核心代码文件夹路径
 * 7.定义CI核心代码文件路径,根目录路径常量
 * 8.确认本应用文件夹路径
 * 9.定义本应用文件夹路径常量
 * 10.定义视图文件夹路径
 * 11.定义视图文件夹路径常量
 * 12.载入CodeIgniter核心文件
 */

require_once COREPATH.'core/CodeIgniter.php';






