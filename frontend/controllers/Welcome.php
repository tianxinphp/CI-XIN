<?php
defined('COREPATH') OR exit('No direct script access allowed');

class Welcome extends MY_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('LoginModel');
        $this->load->library('session');
        $this->load->model('SysDictionaryModel');
        $this->load->model('SysMenuModel');
    }


    public function index()
	{
        $data['loadPublicJsAndCss']= $this->loading->getLoadPublicJsAndCssTag();//加载公共js与css
        $data['loadJs']= $this->loading->addJs('js/welcome/index');//加载js
        $data['loadCss']= $this->loading->addCss('css/welcome/index');//加载js
        $data['logo']=$this->LoginModel->getLogo();
        $data['userName']=$this->session->userdata('userName');
        $data['system_info']=$this->getSystem_info();
        $data['topMenu']=$this->SysMenuModel->getTopMenu();
		$this->load->view('welcome/index',$data);
	}

	public function main(){
        $data['loadPublicJsAndCss']= $this->loading->getLoadPublicJsAndCssTag();//加载公共js与css
        $data['loadJs']= $this->loading->addJs('js/welcome/main.js');//加载js
        $data['system_info']=$this->getSystem_info();
        $data['topMenu']=$this->SysMenuModel->getTopMenu();
        $this->load->view('welcome/main',$data);
    }

    public function getSystem_info(){
        $system_name=$this->SysDictionaryModel->getDictionary('SYSTEM_NAME');
        $system_version=$this->SysDictionaryModel->getDictionary('SYSTEM_VERSION');
        return $system_name['value'].' '.$system_version['value'];
    }

    public function getTopMenu(){
        $topMenuArray=$this->SysMenuModel->getTopMenu();
        echo  json_encode($topMenuArray);
    }

}
