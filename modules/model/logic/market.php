<?php
/**
 * Created by PhpStorm.
 * User: Monk
 * Date: 2016/1/20
 * Time: 15:53
 */
namespace model\logic;

use model\database\Table;
use web\common\Session;

class Market{

    private $_market_id = '';

    public function __construct(){
        $session = new Session();
        $this->_market_id = $session->get('market_id');
    }

    /**
     * @param $id
     * @return array|null
     */
    public function getByID($id){
        if(empty($id)){
            $id = $this->_market_id;
        }
        $market_table = new Table('market');

        $filter = "WHERE market_id = ?";
        $fields = array(
            '*',
            'AsText(free_area) as free_area'
        );
        $market = $market_table->get($filter, array($id), $fields);
        if(!empty($market)){
            $market['free_area'] =  str_replace(',', ';', $market['free_area']);
            $market['free_area'] = str_replace(' ', ',', $market['free_area']);
            preg_match('/^POLYGON\(\((.*?)\)\)$/', $market['free_area'], $matches);
            $market['free_area'] = $matches[1];
        }
        return $market;
    }

    /**
     * 获取市场运营人员的ID数组
     * @param $market_id
     * @return array
     */
    public function getMarketUserIDS($market_id){
        $market_user_table = new Table('market_user');
        $filter = "WHERE market_id = ?";
        $fields = array(
            'user_id'
        );
        $result = $market_user_table->lists($filter, array($market_id), $fields);
        return $result;
    }

    /**
     * 按城市搜索市场信息
     * @param $city
     * @param $page
     * @param $size
     * @return array
     */
    public function search($city, $page=1, $size=20){
        $result = array(
            'total'=>0,
            'data'=>array()
        );
        $filter = " WHERE 1= 1";

        $params = array();


        if($city){
            $filter .= ' AND city = ?';
            $params[] = $city;
        }

        $table = new \model\database\Table('market');

        $result['total'] = $table->count($filter, $params);

        $start = $size * ($page - 1);

        $filter .= " LIMIT $start, $size";

        $fields = array(
            '*',
            'AsText(free_area) as free_area'
        );
        $result['data'] = $table->lists($filter, $params, $fields);

        foreach($result['data'] as $key => $item){

            $tmp =  str_replace(',', ';', $item['free_area']);
            $tmp = str_replace(' ', ',', $tmp);
            preg_match('/^POLYGON\(\((.*?)\)\)$/', $tmp, $matches);
            $tmp = $matches[1];
            $result['data'][$key]['free_area'] = $tmp;

        }

        return $result;
    }

    /**
     * 添加市场
     * @param $city, 城市名称
     * @param $district, 区/县名称
     * @param $free_area, 免费配送区域
     * @param $boundaries 区域边界值
     * @return int, 0表示成功
     */
    public function add($city, $district, $free_area,$boundaries){
        $code = 0;
        if(!empty($city) && !empty($district) && !empty($free_area)){
            $table = new \model\database\Table('market');
            $pdo = $table->getConnection();

            $free_area = str_replace(',', ' ', $free_area);
            $free_area = str_replace(';', ',', $free_area);
            $free_area = 'Polygon((' . $free_area . '))';
            $free_area = $pdo->quote($free_area);
            $format = "PolygonFromText(%s)";
            $free_area = sprintf($format, $free_area);

            $boundaries = str_replace(',', ' ', $boundaries);
            $boundaries = str_replace(';', ',', $boundaries);
            $boundaries = 'Polygon((' . $boundaries . '))';
            $boundaries = $pdo->quote($boundaries);
            $format = "PolygonFromText(%s)";
            $boundaries = sprintf($format, $boundaries);

            $sql = "INSERT INTO market (city, district, free_area,boundary)";
            $sql .= " VALUES(?, ?, $free_area,$boundaries)";

            try{
                $statement = $pdo->prepare($sql);

                $statement->execute(array($city, $district));
            }
            catch(\Exception $e){
                $code = $e->getCode();
            }

        }

        return $code;
    }

    public function edit($market_id,$free_area){
        $code = 0;
        if(!empty($free_area)){
            $table = new \model\database\Table('market');
            $pdo = $table->getConnection();

            $free_area = str_replace(',', ' ', $free_area);
            $free_area = str_replace(';', ',', $free_area);
            $free_area = 'Polygon((' . $free_area . '))';
            $free_area = $pdo->quote($free_area);

            $format = "PolygonFromText(%s)";

            $free_area = sprintf($format, $free_area);

            $sql = "UPDATE market SET free_area = $free_area";
            $sql .= " WHERE market_id = ?";

            try{
                $statement = $pdo->prepare($sql);

                $statement->execute(array($market_id));
            }
            catch(\Exception $e){
                $code = $e->getCode();
            }

        }

        return $code;
    }

    /**
     * 授权
     * @param $market_id
     * @param $user_id
     * @return Number
     */
    public function marketAuth2User($market_id,$user_id,$mark){
        $code = 0;
        if(!empty($market_id)&&intval($market_id)&&!empty($user_id)&&intval($user_id)){
            $table = new \model\database\Table('market_user');
            $pdo = $table->getConnection();
            if(0==$mark){
                $sql = "INSERT INTO market_user (user_id,market_id,role_id,status)";
                $sql .=" VALUES (?,?,?,?)";
            }elseif(1==$mark){
                $sql = "UPDATE market_user SET user_id = ?";
                $sql .=" WHERE market_id = ? AND role_id = ? AND status = ?";
            }
            try{
                $stat = $pdo->prepare($sql);
                $stat->execute(array($user_id,$market_id,3,1));
            }catch (\Exception $e){
                $code = $e->getCode();
            }
        }
        return $code;
    }

    /**
     * 市场信息
     * @param $user_id
     * @return array|null
     */
    public  function getMarketByProxy($user_id){
        $table = new Table('market');
        $filter = " WHERE market_id IN ( %s )";
        $market_user = "SELECT market_id FROM market_user WHERE user_id = ? AND role_id = ?";
        $filter = sprintf($filter,$market_user);
        $params = array($user_id,3);
        $fields = array('*');
        return $table->lists($filter,$params,$fields);
    }

    public function getMarketByCityName($name){
        $table = new Table('market');
        return $table->lists(' WHERE 1=1',array(),array('market_id','district'));
    }

}