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
    public $preLogName='pre_hook_logs-';

    function MyPreHookfunction($user_agent){
        $logDir=FRONTENDPATH.'logs';
        $hookDir=$logDir.DIRECTORY_SEPARATOR.'hooks';
        is_dir($hookDir) or mkdir($hookDir,0755,true);
        if(is_dir($hookDir)&&is_really_writable($hookDir)){
            $hookPath=$hookDir.DIRECTORY_SEPARATOR;
            self::$config=&get_config();
            $hookLogName=$hookPath.$this->preLogName.date('Y-m-d').'.'.(self::$config['log_file_extension']?ltrim(self::$config['log_file_extension'],','):'log');
            $fp=fopen($hookLogName,FOPEN_WRITE_CREATE);
            flock($fp,LOCK_EX);
            fwrite($fp,'钩子启动');
            flock($fp,LOCK_UN);
            if($hookLogName&&file_exists($hookLogName)){
                chmod($hookLogName,FILE_WRITE_MODE);
            }
        }else{
            set_status_header(500);
            log_message('ERROR'.'初始化失败');
            echo '初始化失败';
            exit(EXIT_CONFIG);
        }
    }
}