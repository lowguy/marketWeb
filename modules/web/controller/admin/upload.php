<?php
/**
 * Created by PhpStorm.
 * User: LegendFox
 * Date: 2016/2/29 0029
 * Time: 上午 10:32
 */
namespace web\controller\admin;

class Upload extends \web\common\Controller{

    private $path = "./public/static/upload/temp/";
    private $allowtype = array('image/gif','image/jpeg','image/bmp','image/png');
    private $maxsize = 2000000;
    private $fileType;
    private $newFileName;
    private $uri = "/static/upload/temp/";

    public function __construct(){
        parent::__construct();
        $this->addRoleAction(1, 'uploadImg');
    }
    /**
     * 上传图片
     */
    public function uploadImg(){
        if($_FILES["icon_uri"]["error"]!=0){
            $result = array('status'=>0,'msg'=>'上传错误');
            echo json_encode($result);exit();
        }

        if( !in_array($_FILES["icon_uri"]["type"], $this->allowtype)){
            $result = array('status'=>0,'msg'=>'图片格式错误');
            echo json_encode($result);exit();
        }

        if($_FILES["icon_uri"]["size"] > $this->maxsize){//图片大小不能大于2M
            $result = array('status'=>0,'msg'=>'图片大小超过限制');
            echo json_encode($result);exit();
        }

        $this->newFileName = 'xxj2016_'.substr(md5(time()),0,10).mt_rand(1,10000);

        $this->fileType = pathinfo($_FILES["icon_uri"]["name"], PATHINFO_EXTENSION);

        $localName = $this->path.$this->newFileName.'.'.$this->fileType;

        if (move_uploaded_file($_FILES["icon_uri"]["tmp_name"], $localName) == true) {
            $this->uri .= $this->newFileName.'.'.$this->fileType;
            $result  = array('status'=>1,'msg'=>$this->uri);
        }else{
            $result  = array('status'=>0,'msg'=>'error');
        }
        echo json_encode($result);
    }
}