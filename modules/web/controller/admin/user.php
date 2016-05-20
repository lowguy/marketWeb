<?php
/**
 * Created by PhpStorm.
 * User: Monk
 * Date: 2016/1/26
 * Time: 16:53
 */

namespace web\controller\admin;

class User extends \web\common\Controller{

    public function __construct(){
        parent::__construct();
        $this->addFreeAction('phoneUsed');
        $this->addRoleAction(1, 'add');
        $this->addRoleAction(1, 'status');
        $this->addRoleAction(1, 'index');
    }

    public function index(){
        $roles = array(
            '0'=>'全部',
            '1'=>'系统管理员',
            '2'=>'信息管理员',
            '3'=>'运营管理员'
        );
        $status_array = array(
            '0'=>'全部',
            '2'=>'启用',
            '1'=>'禁用'
        );

        $this->view->assign('roles', $roles);
        $role = $_GET['role'];
        $status = intval($_GET['status']);

        $size = 10;

        $page = intval($_GET['page']);

        $page = $page ? $page : 1;

        $phone = $_GET['phone'];

        $user_model = new \model\logic\User();

        $data = $user_model->search($role, $phone, $status - 1, $page, $size);

        $this->view->js('admin/user/index.js');
        $this->view->assign('users', $data['data']);
        $this->view->assign('role', $role);
        $this->view->assign('status', $status);
        $this->view->assign('status_array', $status_array);
        $this->view->assign('phone', $phone);

        $request = \web\common\Request::instance();

        $pagination_url = $request->makeURL('admin', 'user', 'index',array(
            'role'=>$role,
            'status'=>$status,
            'phone'=>$phone
        ));
        $pagination = new \web\common\Pagination($page, $size, $data['total'], $pagination_url);
        $this->view->assign('pagination', $pagination);

        $this->view->render();
    }

    /**
     * 检查手机号是否被使用, POST请求
     * URL:/admin/user/phoneused
     */
    public function phoneUsed(){
        $result = true;

        $request = \web\common\Request::instance();
        if($request->isPOST()){

            $phone = $_POST['phone'];
            $user_model = new \model\logic\User();
            $user = $user_model->getUserByPhone($phone);
            if(null != $user){
                $result = false;
            }
            echo json_encode($result);
        }

        exit();
    }

    public function status(){
        $code = 1;
        $id = $_POST['id'];
        $id = intval($id);

        $user_model = new \model\logic\User();
        $rows = $user_model->toggleStatus($id);

        if($rows > 0){
            $code = 0;
        }
        $request = \web\common\Request::instance();

        $request->jsonOut($code, '');
    }

    /**
     * 添加用户，GET/POST请求
     * URL:/admin/user/add
     */
    public function add(){
        $request = \web\common\Request::instance();

        $user_model = new \model\logic\User();


        $allowed_roles = array(
          '1'=>'管理员',
          '3'=>'代理'
        );

        if($request->isPOST()){

            $phone = $_POST['phone'];
            $password = $_POST['password'];
            $role = $_POST['role'];
            $code = -1;
            $message = '系统忙，请稍候再试';
            if(array_key_exists($role, $allowed_roles)){

                $code = $user_model->add($phone, $password, $role);
                if($code == 23000){
                    $message = '手机号码已被注册';
                }
            }

            $request->jsonOut($code, $message);
        }
        else{
            $this->view->assign('roles', $allowed_roles);
            $this->view->js('admin/user/form.js');
            $this->view->js('jquery.validate.js');
            $this->view->js('validate.method.js');
            $this->view->render('modules/web/view/admin/user/form.php');
        }

    }
}