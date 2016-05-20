<?php
/**
 * Created by PhpStorm.
 * User: LegendFox
 * Date: 2016/3/7 0007
 * Time: ÉÏÎç 11:54
 */

namespace web\controller\proxy;

use \web\common\Controller;
use web\common\Session;
use \web\common\Request;

class Market extends Controller
{
    public function __construct(){
        parent::__construct();
        $this->addRoleAction(3,'set');

    }

    public function set(){

        $market_id = $_GET['market_id'];

        $session = new Session();
        $session->set('market_id',$market_id);

        $request = Request::instance();

        $uri = explode('/', parse_url($_SERVER['HTTP_REFERER'],PHP_URL_PATH));
        $url = $request->makeURL('proxy', $uri['2'], $uri['3']);

        $request->rediect($url);
    }
}