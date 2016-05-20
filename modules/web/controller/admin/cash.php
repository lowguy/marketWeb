<?php
/**
 * Created by PhpStorm.
 * User: LegendFox
 * Date: 2016/5/17 0017
 * Time: 下午 3:29
 */

namespace web\controller\admin;


use model\logic\Balance;
use web\common\Controller;

class Cash extends Controller
{
    public function __construct(){
        parent::__construct();
        $this->addRoleAction(1, 'index');
        $this->addRoleAction(1, 'examine');
    }

    public function index(){
        $status_array = array(
            '0'=>'全部',
            '1'=>'审核中',
            '2'=>'已通过',
            '3'=>'已拒绝'
        );
        $status = intval($_GET['status']);
        $phone = $_GET['phone'];
        $page = intval($_GET['page']);
        $page = $page ? $page : 1;
        $size = 15;
        $balance = new Balance();
        $data     = $balance->search($page,$status-1,$phone);
        $request = \web\common\Request::instance();

        $pagination_url = $request->makeURL('admin', 'cash', 'index',array(
            'status'=>$status,
            'phone'=>$phone
        ));
        $pagination = new \web\common\Pagination($page, $size, $data['count'], $pagination_url);
        $this->view->assign('pagination', $pagination);
        $this->view->assign('data', $data['list']);
        $this->view->assign('status', $status_array);
        $this->view->js('admin/cash/index.js');
        $this->view->render();
    }

    public function examine(){
        $id = $_POST['id'];
        $comment = $_POST['comment'];
        $status = $_POST['status'];
        $balance = new Balance();
        $flag = $balance->examine($id,$comment,$status);
        $code = $flag ? 0 : 100;
        $request = \web\common\Request::instance();

        $request->jsonOut($code, '');
    }
}