<?php
/**
 * Created by PhpStorm.
 * User: LegendFox
 * Date: 2016/5/18 0018
 * Time: 下午 2:11
 */

namespace web\controller\admin;


use model\logic\Market;
use web\common\Controller;

class Order extends Controller
{
    public function __construct(){
        parent::__construct();
        $this->addRoleAction(1, 'index');
    }
    public function index(){
        $payments = array(
            array('id'=>0,'title'=>'全部'),
            array('id'=>1,'title'=>'微信支付'),
            array('id'=>2,'title'=>'支付宝支付'),
            array('id'=>3,'title'=>'积分支付'),
            array('id'=>4,'title'=>'货到付款')
        );
        $order_status = array(
            array('id'=>'-1','title'=>'全部'),
            array('id'=>'0','title'=>'自动取消'),
            array('id'=>'1','title'=>'未支付'),
            array('id'=>'2','title'=>'已支付'),
            array('id'=>'3','title'=>'派送中'),
            array('id'=>'4','title'=>'已完成')
        );
        $year = $_GET['year'];
        $month = $_GET['month'];
        $day = $_GET['day'];
        $market_id = intval($_GET['market']);
        $status = intval($_GET['status']);
        $payment = intval($_GET['payment']);
        $order_no = intval($_GET['order']);
        $page = intval($_GET['page']);
        $page = $page ? $page : 1;
        $size = 15;
        if($status == '' && $status != 0){
            $status = -1;
        }
        $market = new Market();
        $markets = $market->getMarketByCityName();
        if(!$market_id){
            $market_id = $markets['0']['market_id'];
        }

        $order = new \model\logic\Order();
        $data  = $order->orderlist($page,$size,$market_id,$year,$month,$day,$payment,$status,$order_no);

        $request = \web\common\Request::instance();
        if($order_no){
            $selectedYear = $data['list']['0']['c_year'];
            $selectedMonth = $data['list']['0']['c_month'];
            $selectedDay = $data['list']['0']['c_day'];
            $market_id = $data['list']['0']['market_id'];
            $selectedPayment = $data['list']['0']['payment'];
            $selectedStatus = $data['list']['0']['status'];
        }else{
            $selectedYear = $year ? $year : date('Y',time());
            $selectedMonth = $month ? $month : ($month == -1 ? -1 : date('m',time()));
            $selectedDay = $day ? $day : ($day == -1 ? -1 : date('d',time()));
            $selectedPayment = $payment ? $payment : 0;
            $selectedStatus = $status ? $status : ($status == 0 ? 0 : -1);
        }

        $count = date("t",strtotime("$selectedYear-$selectedMonth"));
        $pagination_url = $request->makeURL('admin', 'order', 'index',array(
            'market'=>$market_id,
            'status'=>$status,
            'payment'=>$payment,
            'year'=>$year,
            'month'=>$month,
            'day'=>$day,
            'order'=>$order_no
        ));
        $pagination = new \web\common\Pagination($page, $size, $data['count'], $pagination_url);
        $this->view->assign('pagination', $pagination);
        $this->view->assign('selectedYear', $selectedYear);
        $this->view->assign('selectedMonth', $selectedMonth);
        $this->view->assign('selectedDay', $selectedDay);
        $this->view->assign('count', $count);
        $this->view->assign('markets', $markets);
        $this->view->assign('selectedMarket', $market_id);
        $this->view->assign('payments', $payments);
        $this->view->assign('selectedPayment', $selectedPayment);
        $this->view->assign('order_status', $order_status);
        $this->view->assign('selectedStatus', $selectedStatus);
        $this->view->assign('order',$data['list']);
        $this->view->js('admin/order/index.js');
        $this->view->render();
    }
}