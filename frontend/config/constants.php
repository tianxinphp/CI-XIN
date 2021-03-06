<?php
/**
 * Created by PhpStorm.
 * User: tianxin
 * Date: 2018/3/13
 * Time: 17:13
 */

defined('COREPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| 显示调试回溯
|--------------------------------------------------------------------------
|
| 如果设置为TRUE，则会显示一个backtrace和php错误。如果
| error_reporting是禁用的，backtrace将不会显示，不考虑这个设置
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
|--------------------------------------------------------------------------
| 文件文件夹处理模式
| 是八进制
*/
defined('FILE_READ_MODE')  OR   define('FILE_READ_MODE', 0644);//drw-w--w--
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);//drw-rw-rw-
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);//drwxr-xr-x
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);//drwxr-xr-x

/*
|--------------------------------------------------------------------------
| 文件流处理模式
|--------------------------------------------------------------------------
|
|
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| 程序退出定义常量
|--------------------------------------------------------------------------
|
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code
