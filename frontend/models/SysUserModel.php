<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/25
 * Time: 16:45
 */
class SysUserModel extends MY_Model
{
    private $db;

    private $dbName=array('user'=>'sys_user');

    public function __construct()
    {
        parent::__construct();
        $this->db=self::$defaultDb;
    }

    /**
     *设置用户信息
     */
    public function setUserInfo(){
        $userName=$this->input->post('userName');
        if($userName){
            $this->session->set_userdata('userName',$userName);//将验证码放入session
        }else{
            show_error('Unknown user please login again.');
        }

    }

}