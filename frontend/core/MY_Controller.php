<?php

defined('COREPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/9
 * Time: 9:49
 */
class MY_Controller extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->loading=&load_class('Loading','core');//加载自ok的load加载方式
    }
}