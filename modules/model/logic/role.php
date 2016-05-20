<?php
/**
 * Created by PhpStorm.
 * User: Monk
 * Date: 2016/1/8
 * Time: 14:18
 */

namespace model\logic;

use model\database\Table;

class Role{

    public function getRolesByUserID($id){
        $role_table = new Table('user_role');
        $filter = "WHERE user_id = ?";

        $roles = $role_table->lists($filter, array($id));

        return $roles;
    }
}