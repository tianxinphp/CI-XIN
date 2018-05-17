<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/17
 * Time: 18:46
 */
class Picture extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('PictureModel');
    }

    /**
     * 首页图片轮播图
     */
    public function loginPicture(){
        $data['pictures']=$this->PictureModel->getLoginPicture();
        $this->load->view('picture/loginPicture',$data);
    }




}