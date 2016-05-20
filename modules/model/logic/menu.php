<?php
/**
 * Created by PhpStorm.
 * User: Monk
 * Date: 2016/1/25
 * Time: 13:31
 */
namespace model\logic;

class Menu{
    private $config = null;

    public function __construct(){

        $this->config =array(
            array(
                'name'=>'市场管理',
                'url'=>'/admin/market/index',
                'roles'=>array(1),
            ),
            array(
                'name'=>'用户管理',
                'url'=>'/admin/user/index',
                'roles'=>array(1)
            ),
            array(
                'name'=>'分类管理',
                'url'=>'/admin/category/index',
                'roles'=>array(1)
            ),
            array(
                'name'=>'商品管理',
                'url'=>'/admin/product/index',
                'roles'=>array(1)
            ),
            array(
                'name'=>'订单管理',
                'url'=>'/admin/order/index',
                'roles'=>array(1)
            ),
            array(
                'name'=>'提现申请',
                'url'=>'/admin/cash/index',
                'roles'=>array(1)
            ),
            array(
                'name'=>'分类管理',
                'url'=>'/proxy/category/index',
                'roles'=>array(3)
            ),
            array(
                'name'=>'产品管理',
                'url'=>'/proxy/product/index',
                'roles'=>array(3)
            ),
            array(
                'name'=>'商户管理',
                'url'=>'/proxy/user/index',
                'roles'=>array(3)
            ),
            array(
                'name'=>'订单管理',
                'url'=>'/proxy/order/index',
                'roles'=>array(3)
            ),
        );
    }

    /**
     * @param $roles, array
     * @return array
     */
    public function getMenus($roles){

        $menu = array();

        foreach($this->config as $item){
            foreach($roles as $role){
                if(in_array($role, $item['roles'])){
                    $menu[] = $item;
                    break;
                }
            }
        }

        return $menu;
    }
}