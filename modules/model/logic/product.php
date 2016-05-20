<?php
/**
 * Created by PhpStorm.
 * User: LegendFox
 * Date: 2016/2/29 0029
 * Time: 上午 10:37
 */

namespace model\logic;

use model\database\Table;
use \model\database\View;
use web\common\Session;

class Product
{
    public function getAll(){
        $table = new Table('product');
        $filter = " WHERE 1=1";
        $fields = array('*');
        return $table->lists($filter,array(),$fields);
    }
    /**
     * 获取单条产品信息
     * @param $id
     * @return array|null
     */
    public function getProductByID($id,$market = NULL){
        $params = array();
        if(empty($id)){
            return $params;
        }
        $product_view = new View('v_product_category');
        $fields = array('*');
        $filter = ' WHERE product_id = ? ';
        $params = array($id);
        if($market){
            $filter .= " AND market = ? ";
            $params = array_merge($params,array($market));
        }

        $res    = $product_view->get($filter, $params, $fields);
        return $res;
    }

    /**
     * 添加产品
     * @param $title 名称
     * @param $slogan 广告语
     * @param $category 分类
     * @param $uri 图片地址
     * @return number
     */
    public function add($title,$slogan,$category,$uri){
        if(empty($title)||$this->checkString($title)){
            return false;
        }
        if(empty($category)||!is_numeric($category)){
            return false;
        }
        $table = new Table('product');
        $status = $table->add(array('title'=>$title,'category_id'=>$category,'slogan'=>$slogan));
        $current = $table->lastID();
        $code = $status ? $this->moveImg($uri,$current) : 1;
        $code = $code ? $code : $this->relation($current,$category,1);

        return $code;
    }

    /**
     * 编辑产品
     * @param Int $id
     * @param String $title
     * @param String $slogan
     * @param String $category
     * @param String $uri
     * @return int
     */
    public function edit($id,$title,$slogan,$category,$uri){
        if(empty($id)||!is_numeric($id)){
            return false;
        }
        if(empty($title)||$this->checkString($title)||empty($slogan)||$this->checkString($slogan)){
            return false;
        }
        if(empty($category)||!is_numeric($category)){
            return false;
        }
        $table = new Table('product');
        $status = $table->edit(array('title'=>$title,'category_id'=>$category,'slogan'=>$slogan),'WHERE product_id = ?',array($id));
        $code = $status ? $this->moveImg($uri,$id) : 1;
        $code = $code ? $code : $this->relation($id,$category,1);

        return $code;
    }

    /**
     * 添加产品关系表
     * @param $product
     * @param $category
     * @param $type
     * @return int|mixed
     */
    private function relation($product,$category,$type){
        $table = new Table('product_category');
        if(1 == $type){
            $status = $table->add(array('category_id'=>$category,'product_id'=>$product));
        }elseif(0 == $type){
            $status = $table->edit(array('category_id'=>$category),'WHERE product_id = ?',array($product));
        }
        $code = $status ? 0 : 1 ;

        return $code;
    }

    /**
     * 移动图片路径
     * @param $file
     * @param $current
     * @return int
     */
    private function moveImg($file,$current){

        $name = pathinfo($file,PATHINFO_BASENAME );
        $search = '/^xxj2016_/';
        if(!preg_match($search,$name)){
            return 0;
        }
        return rename('public'.$file,'public/static/upload/product/'.$current.'.JPG') ? 0 : 1;
    }

    /**
     * 按条件获取产品列表
     * @param $category
     * @param $title
     * @param $page
     * @param $size
     * @return array
     */
    public function search($category, $title, $page, $size)
    {
        $result = array(
            'total' => 0,
            'data' => array()
        );
        $category = intval($category);

        $page = intval($page);

        $size = intval($size);

        $page = $page ? $page : 1;

        $size = $size ? $size : 20;

        $params = array();

        $filter = " WHERE 1 = 1 ";

        $product_view = new \model\database\View('v_product_category');

        if (!empty($category)) {
            $filter .= ' AND category_id = ? ';
            $params[] = $category;
        }

        if (!empty($title)) {
            $title = str_replace('%', '\%', $title);
            $title = str_replace('_', '\_', $title);
            $filter .= ' AND title like CONCAT("%", ? , "%")';
            $params[] = $title;
        }

        //获取总数
        $result['total'] = $product_view->count($filter, $params, 'product_id');
        $filter .= ' GROUP BY product_id ';
        $filter .= ' ORDER BY product_id ASC';
        $fields = array('*');
        $start = $size * ($page - 1);
        $filter .= " LIMIT $start, $size";

        $result['data'] = $product_view->lists($filter, $params, $fields);

        return $result;
    }

    /**
     * 市场已代理产品
     * @param $category
     * @param $title
     * @param $page
     * @param $size
     * @param $market_id
     * @param $user_id
     * @param $type
     * @return array
     */
    public function search4ProxyProducts($category, $title, $page, $size,$market_id,$user_id,$type)
    {
        $result = array(
            'total' => 0,
            'data' => array()
        );
        $category = intval($category);
        $page = intval($page);
        $size = intval($size);
        $page = $page ? $page : 1;
        $size = $size ? $size : 20;

        $table = new Table('market_product');
        $filter = " LEFT JOIN v_product_category ON v_product_category.product_id = market_product.product_id ";
        $filter .= " WHERE market_id = ? ";
        $params[] = $market_id;

        if (!empty($category)) {
            $filter .= ' AND category_id = ? ';
            $params[] = $category;
        }

        if(!empty($user_id)){

            $filter .= $type ? " AND user_id = $user_id" : " AND user_id IS NULL";
        }

        if (!empty($title)) {
            $title = str_replace('%', '\%', $title);
            $title = str_replace('_', '\_', $title);
            $filter .= ' AND title like CONCAT("%", ? , "%")';
            $params[] = $title;
        }

        $filter .= ' GROUP BY product_id ';
        $filter .= ' ORDER BY stock,product_id ASC';
        $fields = array(
            'market_product.market_id',
            'market_product.product_id',
            'market_product.user_id',
            'market_product.price',
            'market_product.stock',
            'market_product.sales',
            'v_product_category.category_id',
            'v_product_category.title',
            'v_product_category.slogan',
            'v_product_category.`start`',
            'v_product_category.`end`',
            'v_product_category.`category_name`'
        );
        $result['total'] = count($table->lists($filter,$params,$fields));
        $start = $size * ($page - 1);
        $filter .= " LIMIT $start, $size";
        $result['data'] = $table->lists($filter, $params, $fields);
        return $result;
    }

    /**
     * 市场未代理的产品
     * @param $category
     * @param $title
     * @param $page
     * @param $size
     * @param $market_id
     * @return array
     */
    public function searchProductsNoProxy($category, $title, $page, $size,$market_id)
    {
        $result = array(
            'total' => 0,
            'data' => array()
        );

        $category = intval($category);
        $page = intval($page);
        $size = intval($size);
        $page = $page ? $page : 1;
        $size = $size ? $size : 20;

        $table = new Table('product');
        $filter = " LEFT JOIN category ON category.category_id = product.category_id WHERE product.product_id NOT IN ( %s ) AND product.category_id IN ( %s )";

        $market_product = "SELECT market_product.product_id FROM market_product WHERE market_product.market_id = ?";
        $params[] = $market_id;

        $category_category = "SELECT end FROM category_category WHERE start IN ( %s )";

        $session = new Session();
        $market_id = $session->get('market_id');
        $market_category = "SELECT category_id FROM market_category WHERE market_id = ? AND status = ? ";
        $params[] = $market_id;
        $params[] = 1;
        $category_category = sprintf($category_category,$market_category);

        $filter = sprintf($filter,$market_product,$category_category);

        $fields = array(
            'product_id',
            'category_name',
            'product.category_id',
            'title'
        );

        if (!empty($category)) {
            $filter .= ' AND product.category_id = ? ';
            $params[] = $category;
        }
        if (!empty($title)) {
            $title = str_replace('%', '\%', $title);
            $title = str_replace('_', '\_', $title);
            $filter .= ' AND product.title like CONCAT("%", ? , "%")';
            $params[] = $title;
        }

        $result['total'] = count($table->lists($filter, $params,$fields));
        $start = $size * ($page - 1);
        $filter .= " LIMIT $start, $size";
        $result['data'] = $table->lists($filter, $params, $fields);
        return $result;
    }

    /**
     * 添加代理商品
     * @param $product_id
     * @return int
     */
    public function addProduct2Market($product_id){
        $created_at = time();
        $end = "86400";
        $table = new Table('market_product');
        $session = new Session();
        $market_id = $session->get('market_id');
        $status = $table->add(array('market_id'=>$market_id,'product_id'=>$product_id,'end'=>$end,'created_at'=>$created_at));
        $code = $status ? 0 : 1;
        return $code;

    }

    /**
     * 修改代理商品
     * @param $product_id
     * @param $price
     * @param $start
     * @param $end
     * @param $activity
     * @param $tag
     * @param $discount
     * @param $inprice
     * @return int
     */
    public function editProduct2Market($product_id,$price,$start,$end,$activity,$tag,$discount,$inprice){
        $updated_at = time();
        $table = new Table('market_product');
        $session = new Session();
        $market_id = $session->get('market_id');
        $activity = $activity ? $tag : 0;
        $status     =  $table->edit(array('price'=>$price,'inprice'=>$inprice, 'start'=>$start,'end'=>$end,'updated_at'=>$updated_at,'activity'=>$activity,'discount'=>$discount),"WHERE product_id = ? AND market_id = ?",array($product_id,$market_id));
        $code = $status ? 0 : 1;
        return $code;

    }

    /**
     * 取消代理产品
     * @param $product_id
     * @return int
     */
    public function cancelAgent4Product($product_id){

        $session = new Session();
        $market_id = $session->get('market_id');

        $table = new Table('market_product');
        $status = $table->delete('WHERE market_id =? AND product_id = ?',array($market_id,$product_id));
        $code = $status ? 0 : 1;

        return $code;

    }

    /**
     * 授权商品给商户
     * @param $product_id
     * @param $user_id
     * @return int
     */
    public function productToUser($product_id,$user_id){
        $table = new Table('market_product');
        $session = new Session();
        $market_id = $session->get('market_id');
        $status = $table->edit(array('user_id'=>$user_id),"WHERE product_id = ? AND market_id = ?",array($product_id,$market_id));
        $code = $status ? 0 : 1;

        return $code;
    }

    /**
     * 授权商品给商户
     * @param $product_id
     * @return int
     */
    public function productCancelToUser($product_id){
        $table = new Table('market_product');
        $session = new Session();
        $market_id = $session->get('market_id');
        $status = $table->edit(array('user_id'=>NULL),"WHERE product_id = ? AND market_id = ?",array($product_id,$market_id));
        $code = $status ? 0 : 1;

        return $code;
    }

    /**
     * 检查提交的字符串的合法性
     * @param $str
     * @return int
     */
    private function checkString($str){
        return preg_match("/^[a-z0-9_x80-xff]+[^_]$/g",$str);
    }


}