<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/21
 * Time: 17:14
 */
class MY_Model extends CI_Model
{
    protected static $_ci_instance;

    public function __construct()
    {
        self::$_ci_instance=&get_instance();
        self::$_ci_instance->load->datebase();
    }
}