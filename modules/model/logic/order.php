<?php
/**
 * Created by PhpStorm.
 * User: LegendFox
 * Date: 2016/4/28 0028
 * Time: 下午 3:22
 */

namespace model\logic;


use model\database\Table;

class Order
{
    /**
     * 市场运营查看订单
     * @param $market
     * @param $status
     * @param $order_no
     * @param $phone
     * @param $page
     * @param $size
     * @param $count
     * @return array|null
     */
    public function searchByProxy($market,$status = -1,$order_no = null,$phone = null,$page = 1,$size = 20,$count = 1){
        $table = new Table('`order`');
        $filter = " LEFT JOIN order_delivery ON order_delivery.order_id = order.order_id LEFT JOIN user ON user.user_id = order_delivery.user_id WHERE market_id = ?";
        $params = array($market);

        if($status >= 0 && !is_null($status)){
            if($status == 0 || !empty($status)){
            $filter .= " AND order.status = ?";
            $params = array_merge($params,array($status));
            }
        }

        if($order_no){
            $filter .= ' AND order.order_no like CONCAT("%", ? , "%")';
            $params = array_merge($params,array($order_no));
        }

        if($phone){
            $filter .= ' AND order.phone like CONCAT("%", ? , "%")';
            $params = array_merge($params,array($phone));
        }

        $start = $size * ($page - 1);
        if($count){
            $filter .= " LIMIT $start, $size";
        }

        $fields = array(
            'order.order_id',
            'order.order_no',
            'order.status',
            'user.phone',
            'order.address',
            'order.amount',
            'order_delivery.user_id',
            'order.phone as user_phone'
        );
        return $table->lists($filter,$params,$fields);
    }

    public function detail($market,$order){
        $data = array(
            'order'=>array(),
            'shop'=>array()
        );
        $table = new Table('`order`');
        $filter = " LEFT JOIN order_delivery ON order_delivery.order_id = order.order_id LEFT JOIN user ON user.user_id = order_delivery.user_id WHERE market_id = ? AND order.order_id = ?";
        $params = array($market,$order);

        $fields = array(
            'order.order_id',
            'order.order_no',
            'order.status',
            'user.phone',
            'order.address',
            'order.amount',
            'order_delivery.user_id',
            'order.phone as user_phone'
        );
        $data['order'] = $table->get($filter,$params,$fields);
        $data['shop'] = $this->recombine($market,$order);
        return $data;
    }

    public function recombine($market,$order){
        $table = new Table('order_product');
        $products = $table->lists(" WHERE order_id = ?",array($order));
        $userIDs = array_unique(array_column($products,'user_id'));
        $market_user = new Table('market_user');
        $filter = " WHERE market_id = ? AND user_id IN (%s)";
        $filter = sprintf($filter,implode(',',$userIDs));
        $userInfo = $market_user->lists($filter,array($market),array('address','user_id'));
        $shop = array();
        foreach($products as $item){
            $shop[$item['user_id']]['goods'][] = $item;
        }
        foreach($userInfo as $item){
            $shop[$item['user_id']]['merchant'] = $item;
        }
        return array_values($shop);
    }

    /**
     * @param $page
     * @param $size
     * @param null $market_id
     * @param null $year
     * @param null $month
     * @param null $day
     * @param null $payment
     * @param -1 $status
     * @param null $order_no
     * @return mixed
     */
    public function orderlist($page,$size,$market_id = null,$year = null,$month = null,$day = null,$payment = null,$status = -1,$order_no = null){
        $table = new Table('`order`');
        $filter = " WHERE 1 = 1";
        $params = array();
        if($market_id && !$order_no){
            $filter .= " AND market_id = ?";
            $params = array_merge($params,array($market_id));
        }
        if($year && !$order_no){
            $filter .= " AND c_year = ?";
            $params = array_merge($params,array($year));
        }
        if($month > 0 && !$order_no){
            $filter .= " AND c_month = ?";
            $params = array_merge($params,array($month));
        }
        if($day > 0 && !$order_no){
            $filter .= " AND c_day = ?";
            $params = array_merge($params,array($day));
        }
        if($payment && !$order_no){
            $filter .= " AND payment = ?";
            $params = array_merge($params,array($payment));
        }
        if($status >= 0 && !$order_no){
            $filter .= " AND status = ?";
            $params = array_merge($params,array($status));
        }
        if($order_no){
            $filter .= " AND order_no = ?";
            $params = array_merge($params,array($order_no));
        }

        $result['count'] = $table->count($filter,$params);

        $start = $size * ($page - 1);
        $filter .= " ORDER BY created_at DESC LIMIT $start,$size";

        $fields = array('*');
        $result['list'] = $table->lists($filter,$params,$fields);
        return $result;
    }
}