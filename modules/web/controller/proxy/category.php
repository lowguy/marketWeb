<?php
/**
 * Created by PhpStorm.
 * User: LegendFox
 * Date: 2016/3/2 0002
 * Time: 下午 1:59
 */

namespace web\controller\proxy;

use \web\common\Controller;
use web\common\Session;
use \web\common\Request;

class Category extends Controller
{

    protected $_category_logic = null;

    public function __construct(){
        parent::__construct();
        $this->addRoleAction(3,'index');
        $this->addRoleAction(3,'categoryList');
        $this->addRoleAction(3,'addCategory2Market');

        $this->_category_logic = new \model\logic\Category();
    }

    /**
     * 首页
     */
    public function index(){
        $this->view->css('ui.fancytree.css');
        $this->view->js('jquery-ui.js');
        $this->view->js('jquery.fancytree.js');
        $this->view->js('jquery.fancytree.dnd.js');
        $this->view->js('jquery.fancytree.edit.js');
        $this->view->js('jquery.fancytree.table.js');
        $this->view->js('proxy/category/index.js');
        $this->view->render();
    }

    /**
     * 分类列表
     */
    public function categoryList(){
        $request = Request::instance();
        if($request->isPOST()){
            $this->postAdd();
        }
        else
        {
            $session = new Session();
            $market_id = $session->get('market_id');
            $result = $this->_category_logic->fancytree4Market($market_id);
            echo json_encode($result);

        }
    }

    /**
     * 添加分类
     */
    private function postAdd(){
        $session = new Session();
        $market_id = $session->get('market_id');
        $source = $_POST['source'];

        $code = $this->_category_logic->addMarketCategory($market_id,$source);
        $message = $code ? '系统忙，请稍候再试...':'保存成功...';
        $request = Request::instance();
        $request->jsonOut($code, $message);
    }

}