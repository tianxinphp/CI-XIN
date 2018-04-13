<?php
defined('COREPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/13
 * Time: 10:43
 */
class Login extends MY_Controller{
    public function index($a,$c)
    {
        $this->output->enable_profiler(TRUE);
        $b['pram1']=$a;
        $b['pram2']=urldecode($c);
        $this->load->view('login/index',$b);
    }
}