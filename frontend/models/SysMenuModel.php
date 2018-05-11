<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/27
 * Time: 11:40
 */
class SysMenuModel extends MY_Model
{
    private $db;

    private $dbName=array('menu'=>'sys_menu');

    public function __construct()
    {
        parent::__construct();
        $this->db=self::$defaultDb;
    }


    /**
     * 获取顶部菜单
     * @return mixed
     */
    public function getTopMenu(){
        $result=$this->db->select('*')->where(array('visible'=>1,'parent_id'=>0))->get($this->dbName['menu']);
        return  $result->result_array();
    }

    public function getSideBarMenu(){
        $topMenuId=$this->input->get('topMenuId');
        $result=$this->db->select('*')->where(array('visible'=>1,'parent_id'=>$topMenuId))->get($this->dbName['menu']);
        return  $result->result_array();
    }


}