<?php
/**
 * Created by PhpStorm.
 * User: LegendFox
 * Date: 2016/3/4 0004
 * Time: 下午 1:20
 */

namespace web\controller\proxy;

use \web\common\Controller;
use web\common\Session;
use \web\common\Request;
use \model\logic\Category;

class Product extends Controller
{
    protected $_product_logic = null;
    protected $_category_logic = null;
    public function __construct(){
        parent::__construct();
        $this->addRoleAction(3,'index');
        $this->addRoleAction(3,'commodity');
        $this->addRoleAction(3,'edit');
        $this->addRoleAction(3,'cancel');
        $this->addRoleAction(3,'marketproduct');
        $this->addRoleAction(3,'productToUser');
        $this->addRoleAction(3,'userProducts');
        $this->addRoleAction(3,'productCancelToUser');

        $this->_product_logic = new \model\logic\Product();
        $this->_category_logic = new Category();
    }

    /**
     * 代理产品(3)
     */
    public function index(){

        $category = $_GET['category'];
        $title = $_GET['title'];
        $page = intval($_GET['page']);
        $page = $page ? $page : 1;

        $data = $this->productList($category, $title, $page,3);

        $categories =  $this->_category_logic->getMarketTopLevel();

        $this->view->assign('categories', $categories);
        $this->view->assign('products', $data['products']);
        $this->view->assign('pagination', $data['pagination']);
        $this->view->css('bootstrap-select.min.css');
        $this->view->js('bootstrap-select.min.js');
        $this->view->js('jquery.validate.js');
        $this->view->js('proxy/product/index.js');

        $this->view->render();

    }

    /**
     * 未代理产品(3)
     */
    public function commodity(){
        $request = \web\common\Request::instance();
        if($request->isPOST()){
            $this->postCommodity();
        }
        else {
            $category = $_GET['category'];
            $title = $_GET['title'];
            $page = intval($_GET['page']);
            $page = $page ? $page : 1;
            $data = $this->productList($category, $title, $page);

            $categories =  $this->_category_logic->getMarketTopLevel();
            $this->view->assign('categories', $categories);
            $this->view->assign('products', $data['products']);
            $this->view->assign('pagination', $data['pagination']);
            $this->view->js('proxy/product/commodity.js');

            $this->view->render();
        }
    }

    /**
     * 添加代理产品(3)
     */
    private function postCommodity(){

        $product_id = $_POST['product_id'];
        $product_id = intval($product_id);

        $code = $this->_product_logic->addProduct2Market($product_id);

        if(1 == $code){
            $message = '已代理此产品,请选择其他产品...';
        }

        $request = Request::instance();
        $request->jsonOut($code,$message);

    }

    /**
     * 产品列表
     * @param $category
     * @param $title
     * @param $page
     * @param $size
     * @param $role
     * @param $user_id
     * @param $type
     * @return array
     */
    public function productList($category,$title,$page,$role,$size=15,$user_id=null,$type=false){
        $result = array(
            'total'=>0,
            'products'=>array(),
            'pagination'=>''
        );

        $session = new Session();
        $market_id = $session->get('market_id');

        $data =  $role == 3 ? $this->_product_logic->search4ProxyProducts($category, $title, $page, $size,$market_id, $user_id,$type) : $this->_product_logic->searchProductsNoProxy($category, $title, $page, $size,$market_id) ;


        $result['total'] = $data['total'];
        $result['products'] = $data['data'];

        $request = Request::instance();

        $action  = (3 == $role && !$user_id) ? 'index' : ($user_id ? ( $type ? 'userProducts': 'marketproduct') : 'commodity') ;
        $params  = array(
            'category'=>$category,
            'title'=>$title
        );
        $user_id && $params['uid'] = $user_id;
        $pagination_url = $request->makeURL('proxy', 'product', $action,$params);

        $pagination = new \web\common\Pagination($page, $size, $data['total'],$pagination_url);
        $result['pagination'] = $pagination;

        return $result;
    }

    /**
     * 取消代理产品(3)
     */
    public function cancel(){

        $product_id = $_POST['product_id'];
        $product_id = intval($product_id);

        $code = $this->_product_logic->cancelAgent4Product($product_id);

        if(0 != $code){
            $message = '系统繁忙,请等待...';
        }

        $request = Request::instance();
        $request->jsonOut($code,$message);

    }


    /**
     * 修改产品信息(3)
     */
    public function edit(){
        $request = Request::instance();
        if($request->isPOST()){
            $this->postEdit();
        }
        else
        {
            $session = new Session();
            $market_id = $session->get('market_id');
            $product_id = $_GET['product'];
            $result = $this->_product_logic->getProductByID($product_id,$market_id);
            $path = shell_exec("ls public/static/upload/product/{$result['product_id']}.*");
            $ext = pathinfo($path,PATHINFO_EXTENSION );
            $result['path'] = $result['product_id'].".".$ext;
            $this->view->assign('product',$result);
            $this->view->css('jquery-ui.css');
            $this->view->js('jquery-ui.js');
            $this->view->js('proxy/product/edit.js');
            $this->view->js('jquery.validate.js');

            $this->view->render();
        }

    }

    /**
     * 修改产品信息(3)
     */
    private function postEdit(){
        $product_id = $_POST['product_id'];
        $price      = $_POST['price'];
        $inprice    = $_POST['inprice'];
        $start      = $_POST['start'];
        $end        = $_POST['end'];
        $activity   = $_POST['activity'];
        $tag   = $_POST['tag'];
        $discount   = $_POST['discount'];
        $code = $this->_product_logic->editProduct2Market($product_id,$price,$start,$end,$activity,$tag,$discount,$inprice);
        if(0 !==$code){
            $message = "系统繁忙，请等待...";
        }
        $request =Request::instance();
        $request->jsonOut($code, $message);
    }

    /**
     * 已代理的商品列表过滤了已经授权经营的产品（3）针对的是商户
     */
    public function marketproduct(){
        $category = $_GET['category'];
        $title = $_GET['title'];
        $user_id = $_GET['uid'];//商户的id
        $page = intval($_GET['page']);
        $page = $page ? $page : 1;

        $data = $this->productList($category, $title, $page,3,10,$user_id,false);

        $categories =  $this->_category_logic->getMarketTopLevel();

        $this->view->assign('categories', $categories);
        $this->view->assign('products', $data['products']);
        $this->view->assign('pagination', $data['pagination']);
        $this->view->css('bootstrap-select.min.css');
        $this->view->js('bootstrap-select.min.js');
        $this->view->js('jquery.validate.js');
        $this->view->js('proxy/product/index.js');
        $this->view->render();
    }

    /**
     * 授权给摸一个商户（100）
     */
    public function productToUser(){
        $user_id    = $_POST['uid'];//商户的id
        $product_id = $_POST['pid'];
        $product    = new \model\logic\Product();
        $code       = $product->productToUser($product_id,$user_id);
        if(0 !==$code){
            $message = "系统繁忙，请等待...";
        }
        $request =Request::instance();
        $request->jsonOut($code, $message);
    }

    /**
     * 取消产品的经营权利（100）
     */
    public function productCancelToUser(){
        $product_id = $_POST['pid'];
        $product    = new \model\logic\Product();
        $code       = $product->productCancelToUser($product_id);
        if(0 !==$code){
            $message = "系统繁忙，请等待...";
        }
        $request =Request::instance();
        $request->jsonOut($code, $message);
    }


    /**
     * 获取商品
     */
    public function userProducts(){
        $category = $_GET['category'];
        $title = $_GET['title'];
        $user_id = $_GET['uid'];
        $page = intval($_GET['page']);
        $page = $page ? $page : 1;

        $data = $this->productList($category, $title, $page,3,10,$user_id,true);

        $categories =  $this->_category_logic->getMarketTopLevel();

        $this->view->assign('categories', $categories);
        $this->view->assign('products', $data['products']);
        $this->view->assign('pagination', $data['pagination']);
        $this->view->css('bootstrap-select.min.css');
        $this->view->js('bootstrap-select.min.js');
        $this->view->js('jquery.validate.js');
        $this->view->js('proxy/product/index.js');
        $this->view->render('modules/web/view/proxy/product/product.php');
    }

}