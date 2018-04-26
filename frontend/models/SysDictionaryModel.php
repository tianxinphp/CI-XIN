<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/26
 * Time: 14:26
 */
class SysDictionaryModel extends MY_Model
{
    private $db;

    private $dbName=array('dictionary'=>'sys_dictionary');

    public function __construct()
    {
        parent::__construct();
        $this->db=self::$defaultDb;
    }

    /**
     * 获取字典项
     * @param $code     字典项code
     * @param bool $isGetAll 是否获取相同类型的全部code
     * @return mixed
     */
    public function getDictionary($code,$isGetAll=false){
        if(!$isGetAll){
            $result=$this->db->select(array('code','name','value'))->where(array('code'=>$code,'status'=>1))->get($this->dbName['dictionary']);
            return $result->row_array();
        }else{
            $sql='SELECT a.code, a.name, a.value FROM sys_dictionary as a JOIN (SELECT type_id from sys_dictionary WHERE code= ?) as b WHERE a.type_id = b.type_id';
            $result=$this->db->query($sql,array($code));
            return $result->result_array();
        }
    }
}