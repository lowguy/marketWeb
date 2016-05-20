<?php
/**
 * Created by PhpStorm.
 * User: Monk
 * Date: 2016/1/20
 * Time: 16:47
 */

namespace web\common;

class Controller{
    protected $view = null;

    private $free_action=array();

    private $role_action=array();

    public function __construct(){

        $this->view = new View();
        $this->view->js('jquery-1.11.3.js');
        $this->view->js('bootstrap.js');
        $this->view->css('bootstrap.css');
        $this->view->css('font-awesome.css');
        $this->view->css('bootstrap-theme.css');
        $this->view->css('base.css');
        $this->view->js('common/form.js');
        $session = new Session();

        $roles = $session->getUserRole();
        $user_id = $session->getUserID();
        $menu = new \model\logic\Menu();
        $menus = $menu -> getMenus($roles);
        $this->view->assign('menu',$menus);
        if(in_array(3,$roles)){

            $market = new \model\logic\Market();
            $markets = $market->getMarketByProxy($user_id);
            foreach($markets as $key => $item){
                $markets[$key]['url']='/proxy/market/set?market_id='.$item['market_id'];
            }
            $this->view->assign('market',$markets);

            $market_id = $session->get('market_id');
            if(!$market_id){
                $session->set('market_id',$markets['0']['market_id']);
            }

            $currentMarket = array_filter($markets, function($currentMarket) use ($market_id) { return $currentMarket['market_id'] == $market_id; });
            $currentMarket = array_values($currentMarket);
            $this->view->assign('currentMarket',$currentMarket['0']['district']);
        }
    }

    protected function addFreeAction($action){
        $this->free_action[] = strtolower($action);
    }

    protected function addRoleAction($role_id, $action){
        $this->role_action[$role_id][] = strtolower($action);
    }

    public function checkPermission(){

        $session = new Session();
        $request = Request::instance();
        $action = $request->getAction();
        $action = strtolower($action);

        if(!in_array($action, $this->free_action)){
            if(!$session->isLogin()){
                $url = $request->makeURL('user', 'sign', 'in');
                $request->rediect($url);
            }
            else{

                $role_id = $session->getUserRole();

                $allowed = false;
                foreach($role_id as $role){
                    if(in_array($action,$this->role_action[$role])){
                        $allowed = true;
                        break;
                    }
                }
                if(!$allowed){
                    $request->FOF();
                }
            }
        }
    }
}