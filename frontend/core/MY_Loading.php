<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/9
 * Time: 11:12
 */
defined('COREPATH') OR exit('No direct script access allowed');

class MY_Loading extends CI_Loader{
    public function __construct()
    {
        $this->getLoadPublicJsAndCssTag();//初始化加载
        log_message('info', 'MY_Loader Class Initialized');
    }

    static $publicJsAndCss=array(

    );

    static $publicJsAndCssTag;


    private  static $isLoadNew=False;
    /**
     * 加载js
     * @param $js_path
     * @return string
     */
    public function addJs($js_path){
        static $loadJs=array();
        if(! is_array($js_path)) {//不是一个数组
            if (is_string($js_path)) {
                $jsFileName=strtolower(pathinfo($js_path,PATHINFO_EXTENSION))=='js'?$js_path:$js_path.'.js';//可以加尾号或者不加
                if(file_exists(ASSETSPATH.$jsFileName)){//文件存在且为js结尾文件
                    $jsFileName=ASSETSPATH.$jsFileName;
                    array_push($loadJs,$jsFileName);
                }else{
                    show_error('Please load a JS file',EXIT_ERROR);
                }
            } else {
                show_error('The parameters to be passed should be an array or a string type',EXIT_ERROR);
            }
        }else{
            //只有数组能走到这里
            foreach ($js_path as $_js_path){
                $this->addJs($_js_path);//递归加js文件
            }
        }
        !empty($loadJs)&&array_unique($loadJs);//数组不为空去重
        if(empty($loadJs)){
            show_error('JS cannot be loaded',EXIT_ERROR);
        }else{
            $scriptTags='';
            foreach ($loadJs as $jsFile){
              $scriptTags.='<script src="'.$jsFile.'" type="text/javascript" charset="utf-8"></script>'.PHP_EOL;
            }
        }
        return $scriptTags;
    }

    /**
     * 加载css
     * @param $css_path
     * @return string
     */
    public function addCss($css_path){
        static $loadCss=array();
        if(! is_array($css_path)) {//不是一个数组
            if (is_string($css_path)) {
                $cssFileName=strtolower(pathinfo($css_path,PATHINFO_EXTENSION))=='css'?$css_path:$css_path.'.css';//可以加尾号或者不加
                if(file_exists(ASSETSPATH.$cssFileName)){//文件存在且为css结尾文件
                    $cssFileName=ASSETSPATH.$cssFileName;
                    array_push($loadCss,$cssFileName);
                }else{
                    show_error('Please load a CSS file',EXIT_ERROR);
                }
            } else {
                show_error('The parameters to be passed should be an array or a string type',EXIT_ERROR);
            }
        }else{
            //只有数组能走到这里
            foreach ($css_path as $_css_path){
                $this->addCss($_css_path);//递归加js文件
            }
        }
        !empty($loadCss)&&array_unique($loadCss);//数组不为空去重
        if(empty($loadCss)){
            show_error('CSS cannot be loaded',EXIT_ERROR);
        }else{
            $styleTags='';
            foreach ($loadCss as $cssFile){
                $styleTags.='<link rel="stylesheet" href="'.$cssFile.'" type="text/css">'.PHP_EOL;
            }
        }
        return $styleTags;
    }

    /**
     * 公共js和css
     * @param $filePath     文件路径
     * @param $isOverWrite  如果碰到一样的是否覆盖
     */
    public  function  registerPublicJsAndCss($filePath,$isOverWrite){
        if(!is_array($filePath)){
            if(is_string($filePath)){
                $fileName=strtolower(pathinfo($filePath,PATHINFO_EXTENSION));
                if(in_array($fileName,array('js','css'),TRUE)){
                    if(file_exists(ASSETSPATH.$fileName)){//文件存在
                        if(in_array($fileName,self::$publicJsAndCss)&&!$isOverWrite){
                            show_error('This file:'.$fileName.'already exists',EXIT_CONFIG);
                        }else{
                            self::$isLoadNew=TRUE;
                            array_push(self::$publicJsAndCss,ASSETSPATH.$fileName);
                            array_merge(self::$publicJsAndCss);//后面的覆盖前面的
                        }
                    }else{
                        show_error('Please load a JS or CSS file',EXIT_ERROR);
                    }
                }else{
                    show_error('Please register the JS or CSS files');
                }
            }else{
                show_error('Please load a file',EXIT_ERROR);
            }
        }else{
            //只有数组能走到这里
            foreach ($filePath as $_filePath){
                $this->registerPublicJsAndCss($_filePath);//递归加js文件
            }
        }
    }

    /**
     * 返回加载好的公共js与css标签
     * @return mixed
     */
    public function getLoadPublicJsAndCssTag(){
        if(self::$isLoadNew){
            return self::$publicJsAndCssTag;
        }else{
            if(empty(self::$publicJsAndCssTa)){
                return;
            }else{
                foreach (self::$publicJsAndCssTag as $tagName){
                    if(strtolower(pathinfo($tagName,PATHINFO_EXTENSION))=='js'){
                        self::$publicJsAndCssTag.='<script src="'.$tagName.'" type="text/javascript" charset="utf-8"></script>'.PHP_EOL;
                    }else if(strtolower(pathinfo($tagName,PATHINFO_EXTENSION))=='css'){
                        self::$publicJsAndCssTag.='<link rel="stylesheet" href="'.$tagName.'" type="text/css">'.PHP_EOL;
                    }
                }
            }
        }
    }

}