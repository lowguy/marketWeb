<?php
/**
 * Created by PhpStorm.
 * User: LegendFox
 * Date: 2016/5/5 0005
 * Time: 下午 1:56
 */

namespace model\logic;


use model\database\Table;

class Balance
{
    public function search($page,$status=-1,$phone=null){
        $result  = array(
            'count'=>0,
            'list'=>array()
        );
        $page    = $page ? $page : 1;
        $size    = 15;
        $table   = new Table('balance_apply');
        $filter  = " LEFT JOIN card ON balance_apply.card_id = card.card_id  LEFT JOIN user ON user.user_id = balance_apply.user_id WHERE 1=1";
        $params  = array();
        if($status >= 0){
            $filter .= " AND balance_apply.status = ?";
            $params = array_merge($params,array($status));
        }
        if($phone){
            $filter .= ' AND user.phone LIKE CONCAT("%", ? , "%")';
            $params = array_merge($params,array($phone));
        }

        $filter .= " ORDER BY balance_apply.status ASC,balance_apply.created_at DESC";
        $result['count'] = $table->count($filter,$params);
        $start   = $size * ($page -1);
        $filter .= " LIMIT $start, $size";
        $fields  = array(
            'balance_apply.id',
            'balance_apply.status',
            'balance_apply.amount',
            'balance_apply.comment',
            'FROM_UNIXTIME(balance_apply.created_at,"%Y-%m-%d") AS created_at',
            'balance_apply.confirmed_at',
            'balance_apply.type',
            'card.card_id',
            'card.name',
            'card.account',
            'card.phone',
            'card.bank',
            'card.type as c_type',
            'user.phone as user'
        );
        $result['list'] = $table->lists($filter,$params,$fields);
        return $result;
    }

    public function examine($id,$comment,$status){
        $table = new Table('balance_apply');
        return $table->edit(array('status'=>$status,'comment'=>$comment,'confirmed_at'=>time()),' WHERE id =? ',array($id));
    }
}