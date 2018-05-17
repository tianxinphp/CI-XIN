<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/17
 * Time: 18:53
 */
class PictureModel extends MY_Model
{
    private $db;

    private $dbName=array('picture'=>'sys_picture');

    public function __construct()
    {
        parent::__construct();
        $this->db=self::$defaultDb;
    }

    /**
     * 获取首页轮播图片
     */
    public function getLoginPicture(){
        $result=$this->db->select("*")->where(array('type_id'=>1))->order_by('sort','asc')->get($this->dbName['picture']);
        return $result->result_array();
    }



}