<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/21
 * Time: 17:08
 */
class LoginModel extends MY_Model
{
    private $db;

    private $dbName=array('picture'=>'sys_picture','user'=>'sys_user');

    public function __construct()
    {
        parent::__construct();
        $this->db=self::$defaultDb;
    }

    /**
     *取首页背景图片
     * @return mixed
     */
    function getIndexPicture()
    {
          $result=$this->db->select(array('base_url','url','path','caption'))->where(array('type_id'=>1,'status'=>1))->order_by('sort','asc')->get($this->dbName['picture']);
          return $result->result_array();
    }

    /**
     * 获取logo图片,只取一行
     * @return mixed
     */
    function getLogo()
    {
        $result=$this->db->select(array('base_url','url','path','caption'))->where(array('type_id'=>2,'status'=>1))->order_by('sort','asc')->get($this->dbName['picture']);
        return $result->row_array();
    }


    /**
     *验证密码正确
     * @param $username 用户名
     * @param $password 密码
     * @return bool     是否验证成功
     */
    function passwordAuthentification($username,$password){
        $result=$this->db->select('password_hash')->where('username',$username)->get($this->dbName['user']);
        $row=$result->row_array();
        if(!empty($row)){
            if(password_verify($password,$row['password_hash'])){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
}