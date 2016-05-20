<?php
/**
 * Created by PhpStorm.
 * User: Monk
 * Date: 2016/1/20
 * Time: 16:20
 */
namespace model\logic;

use model\database\Table;

class User {


    /**
     * 登录用户检测
     * @param $phone
     * @param $password
     * @return array|null
     */
    public function login($phone, $password){

        $filter = "WHERE phone = ? AND password = ?";
        $user_table = new Table('user');
        $user = $user_table->get($filter, array($phone, md5($password)));

        return $user;
    }

    /**
     * 通过手机号码获取用户信息
     * @param $phone
     * @return array|null
     */
    public function getUserByPhone($phone){
        $filter = "WHERE phone = ?";

        $user_table = new Table('user');
        $user = $user_table->get($filter, array($phone));

        return $user;
    }
    /**
     * 通过ID获取用户信息
     * @param $user_id
     * @return array|null
     */
    public function getUserById($user_id){
        $filter = "WHERE user_id = ?";
        $user_table = new Table('user');
        $user = $user_table->get($filter, array($user_id));

        return $user;
    }

    /**
     * 添加用户
     * @param $phone
     * @param $password
     * @param $role
     * @return int, 0成功， 其他表示数据库错误code
     */
    public function add($phone, $password, $role){
        $code = 0;

        $user_table = new Table('user');
        $user_role = new Table('user_user');
        $connection = $user_table->getConnection();
        $data = array(
            'phone'=>$phone,
            'password'=>md5($password),
            'created_at'=>time()
        );
        try{
            $connection->beginTransaction();
            $user_table->add($data);
            $user_id = $user_table->lastID();
            $user_role->add(array('start'=>$user_id,'end'=>$user_id,'distance'=>0));
            $data = array(
                'user_id' => $user_id,
                'role_id' => $role
            );
            if($role){
                $user_role_table = new Table('user_role');
                $user_role_table->add($data);
                $connection->commit();
            }
        }
        catch(\Exception $e){
            $code = $e->getCode();
        }

        return $code;
    }

    /**
     * 搜索用户
     * @param int $role, 角色
     * @param string $phone, 手机号码
     * @param int $status, 状态
     * @param int $page, 页数
     * @param int $size, 每页大小
     * @return array
     */
    public function search($role, $phone, $status = -1, $page=1, $size = 20){
        $result = array(
            'total'=>0,
            'data'=>array()
        );

        $role = intval($role);
        $status = intval($status);
        $page = intval($page);
        $size = intval($size);
        $page = $page ? $page : 1;
        $size = $size ? $size : 20;

        $params = array();

        $filter = "WHERE 1 = 1 AND role_id IS NOT NULL ";
        $user_role_view = new \model\database\View('v_user_role');
        if($role != 0){
            $filter .= 'AND role_id = ? ';
            $params[] = $role;
        }

        if(in_array($status, array(0, 1))){
            $filter .= ' AND status = ?';
            $params[] = $status;
        }
        if(!empty($phone)){
            $phone = str_replace('%', '\%', $phone);//escape wildcard
            $phone = str_replace('_', '\_', $phone);
            $filter .= ' AND phone like CONCAT("%", ? , "%")';
            $params[] = $phone;
        }
        //获取总数
        $result['total'] = $user_role_view->count($filter, $params, 'user_id');

        $filter .= ' GROUP BY user_id ';

        $filter .= ' ORDER BY user_id DESC';

        $fields = array(
            '*',
            'GROUP_CONCAT(role_id) as role_ids',
            'GROUP_CONCAT(role_name) as role_names'
        );

        $start = $size * ($page - 1);

        $filter .= " LIMIT $start, $size";

        $result['data'] = $user_role_view->lists($filter, $params, $fields);

        return $result;
    }

    /**
     * 搜索市场用户
     * @param int $role, 角色
     * @param string $phone, 手机号码
     * @param int $status, 状态
     * @param int $page, 页数
     * @param int $size, 每页大小
     * @param int $market_id
     * @param int $user_id 商户ID
     * @return array
     */
    public function searchMarketUser($role, $phone, $status = -1, $page=1, $size = -1,$market_id,$user_id){
        $result = array(
            'total'=>0,
            'data'=>array()
        );

        $role = intval($role);
        $status = intval($status);
        $page = intval($page);
        $size = intval($size);
        $user_id = intval($user_id);
        $page = $page ? $page : 1;

        $table = new Table('market_user');
        $filter = " LEFT JOIN role ON role.role_id = market_user.role_id LEFT JOIN user ON user.user_id = market_user.user_id";
        $filter .= " WHERE market_id = ? AND market_user.role_id !=3 AND  market_user.role_id !=102 ";
        $params[] = $market_id;

        if(!empty($user_id)){
            $filter .= ' AND market_user.user_id = ?';
            $params[] = $user_id;
        }

        if(in_array($role, array(100, 101))){
            $filter .= ' AND market_user.role_id = ?';
            $params[] = $role;
        }

        if(in_array($status, array(0, 1, 2))){
            $filter .= ' AND market_user.status = ?';
            $params[] = $status;
        }

        if(!empty($phone)){
            $phone = str_replace('%', '\%', $phone);
            $phone = str_replace('_', '\_', $phone);
            $filter .= ' AND user.phone like CONCAT("%", ? , "%")';
            $params[] = $phone;
        }

        $filter .= ' GROUP BY market_user.user_id ';

        $filter .= ' ORDER BY market_user.user_id DESC';

        $fields = array(
            'market_user.user_id',
            'market_user.role_id',
            'market_user.status',
            'market_user.address',
            'user.phone',
            'user.created_at',
            'user.money',
            'user.score',
            'user.device',
            'GROUP_CONCAT(role.role_name) as role_names'
        );

        if(is_int($size) && $size>0){
            $start = $size * ($page - 1);
            $filter .= " LIMIT $start, $size";
        }

        $result['data'] = $table->lists($filter, $params, $fields);
        $result['total'] = count($result['data']);

        return $result;
    }

    /**
     * 启用/禁用
     * @param $id
     * @return number
     */
    public function toggleStatus($id){
        $table = new \model\database\Table('user');

        $pdo = $table->getConnection();

        $sql = 'UPDATE user SET status = ABS(status - 1) WHERE user_id = ?';
        $statement = $pdo->prepare($sql);
        return $statement->execute(array($id));

    }

    /**
     * 获取用户列表
     * @param $role
     * @param $status
     * @return array
     */
    public function getUserByRole($role,$status){
        $params = array();
        $filter = "WHERE 1 = 1 ";
        $user_role_view = new \model\database\View('v_user_role');
        if($role != 0){
            $filter .= 'AND role_id = ?  ';
            $params[] = $role;
        }
        if(in_array($status, array(0, 1))){
            $filter .= ' AND status = ?';
            $params[] = $status;
        }
        $filter .= ' GROUP BY user_id ';

        $filter .= ' ORDER BY user_id DESC';

        $fields = array(
            '*',
            'GROUP_CONCAT(role_id) as role_ids',
            'GROUP_CONCAT(role_name) as role_names'
        );
        $result = $user_role_view->lists($filter, $params, $fields);
        return $result;
    }


}