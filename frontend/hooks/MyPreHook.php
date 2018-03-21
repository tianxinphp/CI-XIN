<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/21
 * Time: 21:18
 */
class MyPreHook
{
    static  $config=array();
    public $preLogName='pre_hook_logs_2018-03-21';

    function MyPreHookfunction($user_agent){
        $logDir=FRONTENDPATH.'logs';
        $hookDir=$logDir.DIRECTORY_SEPARATOR.'hooks';
        is_dir($logDir) or mkdir($hookDir,0755,true);
        if(is_dir($hookDir)&&is_really_writable($hookDir)){
            self::$config=&config_item();

            $hookLogName='pre_hook'
            fopen();
        }else{
            set_status_header(500);
            log_message('ERROR'.'初始化失败');
            echo '初始化失败';
            exit(EXIT_CONFIG);
        }
    }
}