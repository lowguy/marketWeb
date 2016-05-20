<?php
/**
 * Created by PhpStorm.
 * User: Monk
 * Date: 2016/1/10
 * Time: 18:09
 */
namespace web\common;

class View{

    private $data = array();
    private $js = array();
    private $css = array();
    private $version = '0.7';

    /**
     * 给模板文件传递变量和值
     * @param $key
     * @param $value
     */
    public function assign($key, $value){

        $this->data[$key] = $value;
    }

    private function favicon(){
        echo '/static/images/favicon.ico';
    }

    /**
     * 公用模块渲染
     * @param $path
     * @param $data
     */
    public function piece($view, $data=array()){
        \ob_start();
        \extract($data);
        $path = $this->getViewPath();
        include_once $path . DIRECTORY_SEPARATOR . $view;
        \ob_end_flush();
    }

    public function render($view=null){

        \ob_start();
        \extract($this->data);
        if(empty($view)){
            $view = $this->getDefaultView();
        }
        include_once $view;
        \ob_end_flush();
    }

    private function getDefaultView(){

        $request = Request::instance();
        $path = array(
            $this->getViewPath(),
            $request->getModule(),
            $request->getController(),
            $request->getAction()
        );

        $path = \implode(DIRECTORY_SEPARATOR, $path);
        $path .= '.php';
        $path = \strtolower($path);

        return $path;
    }

    private function getViewPath(){
        $path = array(
            'modules',
            'web',
            'view'
        );

        return \implode(DIRECTORY_SEPARATOR, $path);
    }


    public function js($path){

        $full = array(
            '',
            'static',
            'js',
            $path
        );

        $full = \implode('/', $full);
        $full .= '?version=' . $this->version;

        $this->js[] =  $full;
    }

    public function css($path){

        $full = array(
            '',
            'static',
            'css',
            $path
        );

        $full = \implode('/', $full);
        $full .= '?version=' . $this->version;

        $this->css[] =  $full;
    }

    public function cdn($file){

        return $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . $file;
    }
}