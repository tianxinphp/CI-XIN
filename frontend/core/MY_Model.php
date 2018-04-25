<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/21
 * Time: 17:14
 */
class MY_Model extends CI_Model
{
    protected static $defaultDb;//默认连接数据库
    public function __construct()
    {
        self::$defaultDb=$this->load->database($this->config->item('defaultDatabase'),true);
    }

    /**
     * csrf隐藏域表单
     * @return string
     */
    public function getCsrfInput(){
        if($this->config->item('csrf_protection')){
            return '<input type="hidden" name="'.$this->security->get_csrf_token_name().'"  value="'.$this->security->get_csrf_hash().'" />';
        }else{
            return '';
        }
    }
}