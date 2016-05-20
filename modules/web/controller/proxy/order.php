<?php
/**
 * Created by PhpStorm.
 * User: LegendFox
 * Date: 2016/4/28 0028
 * Time: 下午 2:14
 */

namespace web\controller\proxy;


use web\common\Controller;
use web\common\Request;
use web\common\Session;

class Order extends Controller
{
    public function __construct(){
        parent::__construct();
        $this->addRoleAction(3,'index');
        $this->addRoleAction(3,'detail');
    }

    public function index(){
        $order_no = $_GET['n'];
        $phone = $_GET['t'];
        $status = $_GET['s'];
        if($status == ''){
            $status = -1;
        }
        $page = intval($_GET['page']);
        $page = $page ? $page : 1;
        $size = 10;
        $order  = new \model\logic\Order();
        $session = new Session();
        $market_id  = $session->get('market_id');
        $request    = Request::instance();
        $data       = $order->searchByProxy($market_id,$status,$order_no,$phone,$page,$size);
        $res        = $order->searchByProxy($market_id,$status,$order_no,$phone,$page,$size,0);
        $pagination_url = $request->makeURL('proxy', 'order', 'index',array(
            's'=>$status,
            't'=>$phone,
            'n'=>$order_no
        ));
        $pagination = new \web\common\Pagination($page, $size, count($res), $pagination_url);

        $this->view->assign('order', $data);
        $this->view->assign('pagination',$pagination);
        $this->view->css('bootstrap-select.min.css');
        $this->view->js('bootstrap-select.min.js');
        $this->view->render();
    }

    public function detail(){
        $order_id   = $_GET['id'];
        $order      = new \model\logic\Order();
        $session    = new Session();
        $market_id  = $session->get('market_id');
        $data       = $order->detail($market_id,$order_id);
        $this->view->assign('order', $data);
        $this->view->css('bootstrap-select.min.css');
        $this->view->js('bootstrap-select.min.js');
        $this->view->render();
    }
}