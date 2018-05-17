<?php
ini_set("display_errors",1);
error_reporting(-1);
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

    /**
     * 通过顶部菜单获取侧边菜单
     * @return mixed
     */
    public function getSideBarMenu($topMenuId){
        static $SideBarMenuArray=[];
        $result=$this->db->select('*')->where(array('visible'=>1,'parent_id'=>$topMenuId))->get($this->dbName['menu']);
        $firstSideBarMenuArray=$result->result_array();//得出所有一级父菜单
        if($firstSideBarMenuArray){
            foreach ($firstSideBarMenuArray as $firstSideBarMenu){
                if(array_key_exists($firstSideBarMenu['parent_id'],$SideBarMenuArray)){
                    $SideBarMenuArray[$firstSideBarMenu['parent_id']]['children'][$firstSideBarMenu['id']]=$firstSideBarMenu;
                }else{
                    $SideBarMenuArray[$firstSideBarMenu['id']]=$firstSideBarMenu;
                    $this->getSideBarMenu($firstSideBarMenu['id']);
                }
            }
        }
        return  $SideBarMenuArray;
    }

    /**
     * 获取二级菜单
     * @param $fatherMenuID
     */
    private function getChildBarMenu($fatherMenuID){
        $result=$this->db->select('*')->where(array('visible'=>1,'parent_id'=>$fatherMenuID))->get($this->dbName['menu']);
        $childSideBarMenuArray=$result->result_array();
        foreach ($childSideBarMenuArray as $childSideBarMenu){

        }
    }

}