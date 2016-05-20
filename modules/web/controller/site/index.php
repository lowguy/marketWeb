<?php
/**
 * Created by PhpStorm.
 * User: Monk
 * Date: 2016/1/8
 * Time: 13:58
 */
namespace web\controller\site;

use web\common\Captcha;

class Index extends \web\common\Controller{

    public function __construct(){
        parent::__construct();
        $this->addFreeAction('captcha');
        $this->addRoleAction(1, 'index');
        $this->addRoleAction(3, 'index');
    }

    public function index(){

        $this->view->render();
    }

    public function captcha(){
        $captcha = new Captcha();
        $captcha->out();
    }
}