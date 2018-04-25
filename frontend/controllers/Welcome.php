<?php
defined('COREPATH') OR exit('No direct script access allowed');

class Welcome extends MY_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('LoginModel');
        $this->load->library('session');
    }


    public function index()
	{
        $data['loadPublicJsAndCss']= $this->loading->getLoadPublicJsAndCssTag();//加载公共js与css
        $data['loadJs']= $this->loading->addJs('js/welcome/index');//加载js
        $data['loadCss']= $this->loading->addCss('css/welcome/index');//加载js
        $data['logo']=$this->LoginModel->getLogo();
        $data['userName']=$this->session->userdata('userName');
		$this->load->view('welcome/index',$data);
	}

	public function main(){
        $data['loadPublicJsAndCss']= $this->loading->getLoadPublicJsAndCssTag();//加载公共js与css
        $data['loadJs']= $this->loading->addJs('js/welcome/main.js');//加载js
        $this->load->view('welcome/main',$data);
    }
}
