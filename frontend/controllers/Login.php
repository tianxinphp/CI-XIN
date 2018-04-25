<?php
defined('COREPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/13
 * Time: 10:43
 */
class Login extends MY_Controller{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('captcha');
        $this->load->library('session');
        $this->load->library('encryption');
        $this->load->model('LoginModel');
        $this->load->model('SysUserModel');
    }

    /**
     * 初始化方法
     */
    public function index()
    {
        $data['verification']=$this->createVerification();//<img>tag
        $data['loadPublicJsAndCss']= $this->loading->getLoadPublicJsAndCssTag();//加载公共js与css
        $data['loadJs']= $this->loading->addJs('js/login/login');//加载js
        $data['pictures']=$this->LoginModel->getIndexPicture();
        $data['logo']=$this->LoginModel->getLogo();
        $data['csrf']=$this->LoginModel->getCsrfInput();
        $this->load->view('login/index',$data);//加载页面
    }

    /**
     * 生成验证码,存session
     * @return mixed
     */
    public function createVerification(){
        $val= array(
            'img_path'  => ASSETSPATH.'captcha/',
            'img_url'   => base_url('assets'.DIRECTORY_SEPARATOR.'captcha/'),
            'img_width' => '116',
            'img_height'    =>'36',
            'expiration'    => 7200,
            'word_length'   => 5,
            'font_size' => 16,
            'img_id'    => 'verification_code',
            'pool'      => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
            // White background and border, black text and red grid
            'colors'    => array(
                'background' => array(255, 255, 255),
                'border' => array(255, 255, 255),
                'text' => array(0, 0, 0),
                'grid' => array(255, 40, 40)
            )
        );
        $captcha = create_captcha($val);
        $this->session->set_userdata('verification_code',strtolower($captcha['word']));//将验证码放入session
        if($this->input->is_ajax_request()){
            echo $captcha['image'];
        }else{
            return $captcha['image'];
        }
    }

    /**
     * 登陆
     */
    public function login(){
        $userName=$this->input->post('userName');
        $password=$this->input->post('password');
        $verification_code=$this->input->post('code');
        if(strtolower($verification_code)==$this->session->userdata('verification_code')){
            if(!isset($userName,$password)||empty($userName)||empty($password)){
                echo json_encode(['result'=>false,'msg'=>'账号或密码不能为空'],JSON_UNESCAPED_UNICODE);
            }else{
                $result=$this->LoginModel->passwordAuthentification($userName,$password);
                if($result){
                    $this->SysUserModel->setUserInfo();
                    echo json_encode(['result'=>true,'msg'=>'登陆成功'],JSON_UNESCAPED_UNICODE);
                }else{
                    echo json_encode(['result'=>false,'msg'=>'密码或账号不正确'],JSON_UNESCAPED_UNICODE);
                }
            }
        }else{
            echo json_encode(['result'=>false,'msg'=>'验证码输入有误,请重试'],JSON_UNESCAPED_UNICODE);
        }
    }


    /**
     * 表单csrf
     * @return mixed
     */
    public function refreshCsrf(){
       echo  $this->LoginModel->getCsrfInput();
    }

}