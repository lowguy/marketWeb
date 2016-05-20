<?php

namespace web\controller\manager;

use model\logic\Category;
use model\logic\Product;
use web\common\Controller;
use Apfelbox\FileDownload\FileDownload;

class App extends Controller
{

    public function __construct(){
        parent::__construct();
        $this->addRoleAction(1, 'category');
        $this->addRoleAction(1, 'goods');
        $this->addRoleAction(1, 'productPic');
        $this->addRoleAction(1, 'categoryPic');
    }

    public function category(){
        $data = array();
        $file = "category.json";
        $category = new Category();
        $top_categories = $category->getCategoryLevel(1);
        foreach($top_categories as $k => $v){
            $data[$k]['id'] = $v['category_id'];
            $data[$k]['name'] = $v['category_name'];
            $res = $category->getChildCategory($v['category_id']);
            foreach($res as $key => $value){
                $data[$k]['children'][$key]['id'] = $value['category_id'];
                $data[$k]['children'][$key]['name'] = $value['category_name'];
            }
        }
        $fileDownload = FileDownload::createFromString(json_encode($data));
        $fileDownload->sendDownload($file);
    }

    public function goods(){
        $data = array();
        $file = "goods.json";
        $product = new Product();
        $res = $product->getAll();
        foreach($res as $k => $v){
            $data[$k]['id'] = $v['product_id'];
            $data[$k]['title'] = $v['title'];
            $data[$k]['slogan'] = $v['slogan'];
            $data[$k]['category_id'] = $v['category_id'];
        }
        $fileDownload = FileDownload::createFromString(json_encode($data));
        $fileDownload->sendDownload($file);
    }

    public function productPic(){
        $pack = shell_exec("tar zcvf  public/static/upload/product/product.tar.gz  public/static/upload/product/*");
        if($pack){
            $lastPackName = 'public/static/upload/product/product.tar.gz';
            $fileDownload = FileDownload::createFromFilePath($lastPackName);
            $fileDownload->sendDownload("product.tar.gz");
        }
    }

    public function categoryPic(){
        $pack = shell_exec("tar zcvf  public/static/upload/category/category.tar.gz  public/static/upload/category/");
        if($pack){
            $lastPackName = 'public/static/upload/category/category.tar.gz';
            $fileDownload = FileDownload::createFromFilePath($lastPackName);
            $fileDownload->sendDownload("category.tar.gz");
        }
    }



}