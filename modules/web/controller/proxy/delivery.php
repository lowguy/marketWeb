<?php

/**
 * 商户管理基本类
 * Created by PhpStorm.
 * User: LegendFox
 * Date: 2016/3/2 0002
 * Time: 下午 1:50
 */
namespace web\controller\proxy;
use model\logic\Category;
use model\logic\MarketUser;
use model\logic\Product;
use web\common\Request;
use web\common\Session;

class Delivery extends \web\common\Controller
{

    private $_market_id = '';

    private static $_roles = array(
        array('role_id'=>0,'title'=>'全部'),
        array('role_id'=>100,'title'=>'商户'),
        array('role_id'=>101,'title'=>'配送员')
    );

    private static $_status_array = array(
        array('status'=>0,'title'=>'全部'),
        array('status'=>1,'title'=>'未审核'),
        array('status'=>2,'title'=>'已审核'),
        array('status'=>3,'title'=>'已拒绝')
    );

    public function __construct(){
        parent::__construct();

        $session = new Session();
        $this->_market_id = $session->get('market_id');

        $this->addRoleAction(3, 'index');
        $this->addRoleAction(3, 'auth');
        $this->addRoleAction(3, 'userList');
        $this->addRoleAction(3, 'approval');
    }

    /**
     * 商户首页
     */
    public function index(){

        $role = $_GET['role'];
        $status = intval($_GET['status']);
        $size = 10;
        $page = intval($_GET['page']);
        $page = $page ? $page : 1;
        $phone = $_GET['phone'];

        $user_model = new \model\logic\User();
        $data = $user_model->searchMarketUser($role, $phone, $status-1, $page, $size,$this->_market_id);

        $request = \web\common\Request::instance();
        $pagination_url = $request->makeURL('proxy', 'user', 'index',array(
            'role'=>$role,
            'status'=>$status,
            'phone'=>$phone
        ));
        $pagination = new \web\common\Pagination($page, $size, $data['total'], $pagination_url);

        $this->view->js('proxy/user/index.js');
        $this->view->assign('roles',self::$_roles);
        $this->view->assign('status_array', self::$_status_array);
        $this->view->assign('users', $data['data']);
        $this->view->assign('pagination', $pagination);

        $this->view->render();
    }

    /**
     * 商户列表
     */
    public function userList(){

        $code = 0;
        $role = $_POST['role'];
        $status = intval($_POST['status']);
        $phone = $_POST['phone'];
        $page = intval($_POST['page']);

        $role = $role ? $role : self::$_roles['1']['role_id'];
        $status = $status ? $status : self::$_status_array['1']['status'];
        $page = $page ? $page : 1 ;
        $size = 50;
        $user_model = new \model\logic\User();
        $data = $user_model->searchMarketUser($role, $phone, --$status, $page,$size,$this->_market_id);

        $request = \web\common\Request::instance();
        $request->jsonOut($code, $data);

    }

    /**
     * 商户信息
     */
    public function auth(){

        if(parse_url($_SERVER['HTTP_REFERER'],PHP_URL_PATH) != "/proxy/user/index"){
            die('非法操作');
        }

        $user_id = intval($_GET['user_id']);
        $status = intval($_GET['status']);

        $market_model = new \model\logic\Market();
        $market = $market_model->getByID($this->_market_id);

        $user_model = new \model\logic\User();
        $data = $user_model->searchMarketUser('', '', --$status, '', '',$this->_market_id,$user_id);

        $this->view->assign('market', $market);
        $this->view->assign('user', $data['data']);
        $this->view->js('admin/market/detail.js');
        $this->view->js('proxy/user/form.js');

        $this->view->render('modules/web/view/proxy/user/form.php');

    }

    public function approval(){
        $code      = 100;
        $status    = intval($_POST['status']);
        $user_id   = intval($_POST['id']);
        $address   = $_POST['address'];
        $lng       = $_POST['lng'];
        $lat       = $_POST['lat'];
        $user      = new MarketUser();
        $res       = $user->approval($status,$user_id,$address,$lng,$lat);
        if($res){
            $code  = 0;
        }
        $request   = Request::instance();
        $request->jsonOut($code);
    }
}