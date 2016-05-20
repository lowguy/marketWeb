<?php
/**
 * Created by PhpStorm.
 * User: LegendFox
 * Date: 2016/2/24
 * Time: 9:40
 */
namespace web\controller\admin;

use \web\common\Controller;
use \web\common\Request;
class Category extends Controller{

    protected $_category_logic = null;

    public function __construct(){
        parent::__construct();
        $this->addRoleAction(1, 'getCategoryByPid');
        $this->addRoleAction(3, 'getCategoryByPid');
        $this->addRoleAction(1, 'categoryTree');
        $this->addRoleAction(3, 'categoryTree');
        $this->addRoleAction(1, 'getTopCategory');
        $this->addRoleAction(1, 'uploadImg');
        $this->addRoleAction(1, 'add');
        $this->addRoleAction(1, 'edit');
        $this->addRoleAction(1, 'index');

        $this->_category_logic = new \model\logic\Category();

    }


    /**
     * 分类首页
     */
    public function index(){
        $this->view->css('ui.fancytree.css');
        $this->view->js('jquery-ui.js');
        $this->view->js('jquery.fancytree.js');
        $this->view->js('jquery.fancytree.dnd.js');
        $this->view->js('jquery.fancytree.edit.js');
        $this->view->js('jquery.fancytree.table.js');
        $this->view->js('admin/category/index.js');
        $this->view->render();

    }

    /**
     * 分类fancytree格式
     */
    public function categoryTree(){

        $result = $this->_category_logic->categoryList();
        die(json_encode($result));

    }

    /**
     * 添加分类
     */
    public function add(){

        $request = Request::instance();
        if($request->isPOST()){
            $this->postAdd();
        }
        else
        {
            $this->view->js('admin/category/jquery.ui.widget.js');
            $this->view->js('admin/category/jquery.iframe-transport.js');
            $this->view->js('admin/category/jquery.fileupload.js');
            $this->view->js('admin/category/form.js');
            $this->view->js('jquery.validate.js');

            $this->view->render('modules/web/view/admin/category/form.php');

        }
    }

    /**
     * 添加数据处理
     */
    private function postAdd(){

        $category_name   = $_POST['category_name'];
        $uri    = $_POST['uri'];
        $parent = $_POST['parent'];
        $mark   = $_POST['mark'];

        $code   = $this->_category_logic->add($parent,$category_name,$uri,$mark);

        if(0 !== $code){
            $message = '系统忙，请稍候再试...';
        }

        $request = Request::instance();
        $request->jsonOut($code, $message);

    }

    /**
     * 编辑
     */
    public function edit(){

        $request = Request::instance();
        if($request->isPOST()){
            $this->postEdit();
        }
        else
        {
            $id = intval($_GET['id']);

            $category = $this->_category_logic->getByID($id);
            if(!$category){
                $request->FOF();
            }

            $this->view->assign('category', $category);
            $this->view->js('admin/category/jquery.ui.widget.js');
            $this->view->js('admin/category/jquery.iframe-transport.js');
            $this->view->js('admin/category/jquery.fileupload.js');
            $this->view->js('jquery.validate.js');
            $this->view->js('admin/category/form.js');

            $this->view->render('modules/web/view/admin/category/form.php');

        }
    }

    /**
     * 编辑数据处理
     */
    private function postEdit(){

        $id   = $_POST['category_id'];
        $name = $_POST['category_name'];
        $uri  = $_POST['uri'];
        $mark = $_POST['mark'];
        $mark = $mark != null? $mark : 0;
        $message = '';
        $code = $this->_category_logic->edit($id,$name,$uri,$mark);
        if(0 !== $code){
            $message = '系统忙，请稍候再试...';
        }

        $request = Request::instance();
        $request->jsonOut($code, $message);

    }

    /**
     * 按等级获取分类信息
     */
    public function getTopCategory(){
        $code = 1;
        $message = '';
        $category = $this->_category_logic->getCategoryLevel(1);
        if(!empty($category)){
            $code = 0;
            $message =$category;
        }

        $request = Request::instance();
        $request->jsonOut($code, $message);
    }

    /**
     * 获取子类
     * @return array
     */
    public function getCategoryByPid(){

        $code    =0;
        $message = array();
        $pid     = $_POST['pid'];


        $category = $this->_category_logic->getChildCategory($pid);
        if(!empty($category)){
            $message = $category;
        }

        $request = Request::instance();
        $request->jsonOut($code, $message);
    }


}