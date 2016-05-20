<?php
/**
 * Created by PhpStorm.
 * User: Monk
 * Date: 2016/2/19
 * Time: 9:40
 */
namespace web\controller\admin;


class Market extends \web\common\Controller{

    public function __construct(){
        parent::__construct();
        $this->addFreeAction('phoneUsed');
        $this->addRoleAction(1, 'add');
        $this->addRoleAction(1, 'index');
        $this->addRoleAction(3, 'index');
        $this->addRoleAction(1, 'edit');
        $this->addRoleAction(3, 'edit');
        $this->addRoleAction(1, 'detail');
        $this->addRoleAction(1, 'marketAuth');
        $this->addRoleAction(1, 'marketAuth2User');

    }

    public function index(){
        $model = new \model\logic\Market();
        $city = $_GET['city'];
        $size = 10;
        $page = intval($_GET['page']);
        $page = $page ? $page : 1;
        $city = $city ? $city : 0;

        $data = $model->search($city, $page, $size);

        $request = \web\common\Request::instance();

        $pagination_url = $request->makeURL('admin', 'market', 'index',array(
            'city'=>$city
        ));

        $pagination = new \web\common\Pagination($page, $size, $data['total'], $pagination_url);
        $this->view->assign('pagination', $pagination);

        $this->view->assign('markets', $data['data']);
        $this->view->css('bootstrap-select.min.css');
        $this->view->js('bootstrap-select.min.js');
        $this->view->js('common/city.js');
        $this->view->js('admin/market/index.js');
        $this->view->render();
    }

    private function postAdd(){
        $city = $_POST['city'];
        $district = $_POST['district'];
        $area = $_POST['area'];
        $boundaries = $_POST['boundaries'];
        $market_model = new \model\logic\Market();
        $message = '';
        $code = $market_model->add($city, $district, $area,$boundaries);
        if(0 != $code){
            $message = '系统忙，请稍候再试...';
        }

        $request = \web\common\Request::instance();
        $request->jsonOut($code, $message);
    }

    public function add(){
        $request = \web\common\Request::instance();
        if($request->isPOST()){
            $this->postAdd();
        }
        else{
            $this->view->js('admin/market/form.js');
            $this->view->js('jquery.validate.js');
            $this->view->js('common/city.js');
            $this->view->render('modules/web/view/admin/market/form.php');
        }
    }

    private function postEdit(){
        $request = \web\common\Request::instance();
        $id = $_POST['market_id'];
        $area = $_POST['area'];
        $market_model = new \model\logic\Market();

        $market = $market_model->getByID($id);
        if(!$market){
            $request->FOF();
        }

        $session = new \web\common\Session();
//        $user = $session->getUser();
        $user_roles = $session->getUserRole();
        if(in_array(1, $user_roles)){
//            $market_user_ids = $market_model->getMarketUserIDS($id);
//            if(!in_array($user['user_id'], $market_user_ids)){
//                $request->FOF();
//            }
            $code = $market_model->edit($id,$area);
            if(0 != $code){
                $message = '系统忙，请稍候再试...';
            }
        }
        $request->jsonOut(0, $message);
    }

    public function edit(){
        $request = \web\common\Request::instance();
        if($request->isPOST()){
            $this->postEdit();
        }
        else{
            $id = $_GET['id'];
            $market_model = new \model\logic\Market();
            $market = $market_model->getByID($id);
            if(!$market){
                $request->FOF();
            }
            $this->view->assign('market', $market);
            $this->view->js('admin/market/form.js');
            $this->view->js('jquery.validate.js');
            $this->view->js('common/city.js');
            $this->view->render('modules/web/view/admin/market/form.php');
        }
    }

    /**
     * 市场详情
     */
    public function detail(){
        $market_id = intval($_GET['id']);
        $market_model = new \model\logic\Market();
        $user_model = new \model\logic\User();
        $market = $market_model->getByID($market_id);
        $user = $market_model->getMarketUserIDS($market_id);
        if(!empty($user)){
            $userInfo = $user_model->getUserById($user[0]['user_id']);
        }
        if(!empty($userInfo)){
            $market['phone'] = $userInfo['phone'];
        }
        $this->view->assign('market', $market);
        $this->view->js('admin/market/detail.js');
        $this->view->render();
    }

    /**
     * 市场授权
     */
    public function marketAuth(){
        $market_id = intval($_POST['id']);
        $code = 0;
        $data = array(
            "user"=>'',
            "users"=>''
        );
        $user_model = new \model\logic\User();
        $market_model = new \model\logic\Market();

        $data['users'] = $user_model->getUserByRole(3,1);
        $data['user'] = $market_model->getMarketUserIDS($market_id);

        $request = \web\common\Request::instance();
        $request->jsonOut($code, $data);
    }

    public function marketAuth2User(){
        $market_id = intval($_POST['market_id']);
        $user_id = intval($_POST['user_id']);
        $message = '授权成功';
        $market_model = new \model\logic\Market();
        $market = $market_model->getMarketUserIDS($market_id);
        if(!empty($market)){
            $code = $market_model->marketAuth2User($market_id,$user_id,1);
        }else{
            $code = $market_model->marketAuth2User($market_id,$user_id,0);
        }

        if(0 != $code){
            $message = '系统忙，请稍候再试...';
        }
        $request = \web\common\Request::instance();
        $request->jsonOut($code, $message);
    }
}