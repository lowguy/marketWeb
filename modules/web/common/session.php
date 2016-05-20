<?php
/**
 * Created by PhpStorm.
 * User: Monk
 * Date: 2016/1/20
 * Time: 16:07
 */
namespace web\common;

class Session{

    public function start(){
        \session_start();
        //$this->reID();
    }

    public function reID(){
        \session_regenerate_id(true);
    }

    public function destroy(){
        \session_destroy();
    }

    public function set($key, $value){
        $_SESSION[$key] = $value;
    }

    public function remove($key){
        unset($_SESSION[$key]);
    }

    public function setCode($value){

        $this->set('code', $value);
    }

    public function removeCode(){
        $this->remove('code');
    }

    public function setUser($value){
        $this->set('user', $value);
    }

    public function getUser(){
        return $this->get('user');
    }

    public function getUserID(){
        $user_id = null;
        $user = $this->getUser();
        if(null != $user){
            $user_id = $user['user_id'];
        }

        return $user_id;
    }

    public function getUserRole(){
        $role = array();
        $user = $this->getUser();
        if(null != $user){
            $role = $user['role'];
        }

        return $role;
    }

    /**
     * 设置当前登录用户的角色
     * @param $roles, array
     */
    public function setUserRole($roles){
        $user = $this->getUser();
        if(null != $user){
            $user['role'] = $roles;
        }
        $this->setUser($user);
    }

    public function getCode(){
        return $this->get('code');
    }

    public function get($key){

        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    public function isLogin(){
        $result = false;
        $user = $this->getUser();
        if(!empty($user)){
            $result = true;
        }

        return $result;
    }
}