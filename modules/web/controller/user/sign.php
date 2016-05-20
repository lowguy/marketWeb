<?php
/**
 * Created by PhpStorm.
 * User: Monk
 * Date: 2016/1/25
 * Time: 10:31
 */
namespace web\controller\user;
class Sign extends \web\common\Controller{
    public function __construct(){
        parent::__construct();
        $this->addFreeAction('in');
        $this->addFreeAction('out');
        $this->addFreeAction('checkCode');
    }

    private function login(){
        $request = \web\common\Request::instance();
        $status = 1;
        $data = '用户名/密码错误';
        $code = $_POST['code'];
        $session = new \web\common\Session();
        $session_code = $session->getCode();

        if($code == $session_code){
            $phone = $_POST['phone'];
            $password = $_POST['password'];
            $operator_model = new \model\logic\User();
            $operator = $operator_model->login($phone, $password);
            if(null != $operator){
                $session->reID();
                unset($operator['password']);
                $role_model = new \model\logic\Role();
                $roles = $role_model->getRolesByUserID($operator['user_id']);
                if(!empty($roles)){
                    $role_ids = array_column($roles, 'role_id');

                    $session->setUser($operator);
                    $session->setUserRole($role_ids);

                    $status = 0;
                    $data = '';
                    $session->removeCode();
                }
                else{
                    $status = 2;
                    $data = '您没有权限使用此系统';
                }

            }
        }
        else{
            $status = 3;
            $data = '验证码错误';
        }

        $request->jsonOut($status, $data);
    }

    /**
     * 用户登录, POST/GET
     * URI:/user/sign/in
     */
    public function in(){
        $request = \web\common\Request::instance();
        if($request->isPOST()){

            $this->login();
        }
        else{

            $this->view->js('jquery.validate.js');
            $this->view->js('validate_zh.js');
            $this->view->css('user/login.css');
            $this->view->js('validate.method.js');
            $this->view->render();
        }

    }
    /**
     * 用户登出, POST/GET
     * URI:/user/sign/out
     */
    public function out(){
        $session = new \web\common\Session();

        $session->destroy();

        $request = \web\common\Request::instance();
        $login_url = $request->makeURL('user', 'sign', 'in');
        $request->rediect($login_url);
    }

    /**
     * 验证码校验, POST
     * URI:/user/sign/checkcode
     */
    public function checkCode(){
        $result = false;
        $code = $_POST['code'];
        $session = new \web\common\Session();
        $session_code = $session->getCode();
        if($code == $session_code){
            $result = true;
        }

        echo json_encode($result);
    }
}