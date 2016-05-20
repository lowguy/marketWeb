<?php
/**
 * Created by PhpStorm.
 * User: LegendFox
 * Date: 2016/2/26 0026
 * Time: 下午 4:41
 */
namespace web\controller\admin;
use \web\common\Controller;
use \web\common\Request;
class Product extends Controller{

    protected $_product_logic = null;
    public function __construct(){
        parent::__construct();
        $this->addRoleAction(1, 'index');
        $this->addRoleAction(1, 'add');
        $this->addRoleAction(1, 'delete');
        $this->addRoleAction(1, 'edit');

        $this->_product_logic = new \model\logic\Product();
    }

    /**
     * 产品首页
     */
    public function index(){

        $category = $_GET['category'];

        $title = $_GET['title'];

        $page = intval($_GET['page']);

        $page = $page ? $page : 1;

        $size = 10;

        $data = $this->_product_logic->search($category, $title, $page,$size);
        foreach($data['data'] as $k => $v){
            $path = shell_exec("ls public/static/upload/product/{$v['product_id']}.*");
            $ext = pathinfo($path,PATHINFO_EXTENSION );
            $data['data'][$k]['path'] = $v['product_id'].".".$ext;
        }
        $request = Request::instance();
        $pagination_url = $request->makeURL('admin', 'product', 'index',array(
            'category'=>$category,
            'title'=>$title
        ));
        $pagination = new \web\common\Pagination($page, $size, $data['total'], $pagination_url);

        $this->view->assign('products', $data['data']);
        $this->view->assign('pagination', $pagination);
        $this->view->js('admin/product/index.js');

        $this->view->render();
    }


    /**
     * 添加产品页
     */
    public function add(){

        $request = Request::instance();
        if($request->isPOST()){
            $this->postAdd();
        }
        else {
            $this->view->js('admin/category/jquery.ui.widget.js');
            $this->view->js('admin/category/jquery.iframe-transport.js');
            $this->view->js('admin/category/jquery.fileupload.js');
            $this->view->js('admin/product/form.js');
            $this->view->js('jquery.validate.js');

            $this->view->render('modules/web/view/admin/product/form.php');
        }

    }

    private function postAdd(){

        $uri = $_POST['uri'];
        $title = $_POST['title'];
        $slogan = $_POST['slogan'];
        $category = intval($_POST['category']);

        $code = $this->_product_logic->add($title,$slogan,$category,$uri);
        if(0 !== $code){
            $message = '系统忙，请稍候再试...';
        }

        $request = Request::instance();
        $request->jsonOut($code, $message);

    }

    /**
     * 编辑产品页
     */
    public function edit(){

        $request = Request::instance();
        if($request->isPOST()){
            $this->postEdit();
        }
        else{

            $id = intval($_GET['id']);
            $product = $this->_product_logic->getProductByID($id);

            $path = shell_exec("ls public/static/upload/product/{$product['product_id']}.*");
            $ext = pathinfo($path,PATHINFO_EXTENSION );
            $product['path'] = $product['product_id'].".".$ext;
            empty($product)&& $request->FOF();
            $this->view->assign('product', $product);
            $this->view->js('admin/category/jquery.ui.widget.js');
            $this->view->js('admin/category/jquery.iframe-transport.js');
            $this->view->js('admin/category/jquery.fileupload.js');
            $this->view->js('jquery.validate.js');
            $this->view->js('admin/product/form.js');

            $this->view->render('modules/web/view/admin/product/form.php');

        }
    }

    private function postEdit(){

        $id = intval($_POST['id']);
        $uri = $_POST['uri'];
        $title = $_POST['title'];
        $slogan = $_POST['slogan'];
        $category = intval($_POST['category']);

        $code = $this->_product_logic->edit($id,$title,$slogan,$category,$uri);

        if(0 !== $code){
            $message = '系统忙，请稍候再试...';
        }

        $request = Request::instance();
        $request->jsonOut($code, $message);

    }
}
