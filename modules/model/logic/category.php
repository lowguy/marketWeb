<?php
/**
 * Created by PhpStorm.
 * User: LegendFox
 * Date: 2016/2/24
 * Time: 16:51
 */

namespace model\logic;

use model\database\Table;
use web\common\Session;

class Category
{
    /**
     * Get a detailed information
     * @param $id
     * @return array|null
     */
    public function getByID($id){
        $category_table = new Table('category');
        $filter = " WHERE category_id = ?";
        $fields = array('*');
        $category = $category_table->get($filter, array($id), $fields);
        if(!empty($category)){
            $category['start'] = $this->getStartByEnd($id);
        }
        return $category;
    }

    private function getStartByEnd($end){
        $table = new Table('category_category');
        $filter = " WHERE end = ? and distance = 1";
        $fields = array('start');
        $category = $table->get($filter, array($end), $fields);
        return $category['start'];
    }

    /**
     * Add Category
     * @param String $category_name
     * @param int $parent
     * @param String $uri
     * @param $mark
     * @return number
     */
    public function add($parent,$category_name, $uri,$mark){

        $code = 0;
        if(!empty($category_name)){
            $table = new Table('category');
            $status = $table->add(array('category_name'=>$category_name,'mark'=>$mark));
            $current = $table->lastID();
            $code = $status ? $this->addRelation($current,$parent) : 1;
            $code = $code ? $code : $this->moveImg($uri,$current);
        }
        return $code;
    }

    /**
     * Edit Category
     * @param Int $id
     * @param String $name
     * @param String $uri
     * @param $mark
     * @return int
     */
    public function edit($id,$name, $uri,$mark){
        $code = 0;
        if(!empty($id)&&!empty($name)&&!empty($uri)){
            $table = new Table('category');
            $status = $table->edit(array('category_name'=>$name,'mark'=>$mark)," WHERE category_id = ?",array($id));
            $code = $status ? $this->moveImg($uri,$id) : 1;
        }
        return $code;
    }

    private function moveImg($file,$current){
        $name = pathinfo($file,PATHINFO_BASENAME );
        $search = '/^xxj2016_/';
        if(!preg_match($search,$name)){
            return 0;
        }
        $ext = pathinfo($file,PATHINFO_EXTENSION );
        return rename('public'.$file,'public/static/upload/category/'.$current.'.'.$ext)?0:1;
    }

    private function addRelation($current,$pid){
        $code=0;
        $table = new Table('category_category');
        $table->add(array("start"=>$current,"end"=>$current,'distance'=>0));
        $pdo = $table->getConnection();
        $sql = "INSERT INTO category_category (start,end,distance) SELECT start,$current,distance+1 FROM category_category WHERE end = ?";
        try{
            $statement = $pdo->prepare($sql);
            $statement->execute(array($pid));
        }
        catch(\Exception $e){
            $code = $e->getCode();
        }
        return $code;
    }

    /**
     * 获取子类信息
     * @param $start
     * @return array|null
     */
    public function getChildCategory($start){
        $category_table = new Table('category');
        $filter = " WHERE category_id IN (SELECT end FROM category_category where start=$start AND end !=$start)";
        $fields = array(
            'category_name',
            'category_id'
        );
        $tree = $category_table->lists($filter,array(),$fields);
        return $tree;
    }
    /**
     * Access to classified information by grade
     * @param $level=1
     * @return array|null
     */
    public function getCategoryLevel($level=1){
        $category_table = new Table('category');
        $filter = " WHERE category_id IN (SELECT end FROM category_category GROUP BY end HAVING COUNT(end)=$level)";
        $fields = array(
            'category_name',
            'category_id'
        );
        $category = $category_table->lists($filter,array(0),$fields);

        return $category;
    }

    /**
     * tree structure
     * @param $role
     * @return array
     */
    public function categoryList($role=1){
        if($role == 1){
            $category = $this->getCategoryLevel(1);
        }elseif($role == 100){

        }
        return $this->fancyTree($category,$role);
    }

    /**
     * FancyTree
     * @param $data
     * @return array
     */
    public function fancyTree($data,$role){
        $tree = array(
        );
        foreach($data as $k=>$v){
            $tree[$k]['icon'] = "/static/upload/category/".$v["category_id"].".png";
            $tree[$k]['title'] = $v['category_name'];
            $tree[$k]['expanded'] = 'true';
            $tree[$k]['folder'] = 'true';
            $tree[$k]['level'] = 1;
            $tree[$k]['data-id'] = $v['category_id'];
            ($role == 1) && $tree[$k]['children'] = $this->fancyTree2($v['category_id']);
        }
        return $tree;

    }

    /**
     * FancyTree2
     * @param $start
     * @return array
     */
    public function fancyTree2($start){
        $tree = array();
        $category_table = new Table('category');
        $filter = " WHERE category_id IN (SELECT end FROM category_category where start=$start AND end !=$start)";
        $fields = array(
            'category_name',
            'category_id'
        );
        $category = $category_table->lists($filter,array(0),$fields);
        foreach($category as $k=>$v){
            $tree[$k]['icon'] = "/static/upload/category/".$v["category_id"].".png";
            $tree[$k]['title'] = $v['category_name'];
            $tree[$k]['folder'] = 'false';
            $tree[$k]['level'] = 2;
            $tree[$k]['data-id'] = $v['category_id'];
        }
        return $tree;
    }
    /**
     * 以上代码需整理
     */

    /*********************************代理*********************************/
    /**
     * 代理一级菜单 FancyTree结构
     * @param $market_id
     * @return array
     */
    public function fancytree4Market($market_id){
        if(empty($this->marketCategoryTree($market_id))){
            return $this->tree($this->categoryTree());
        }else{
            return $this->tree($this->marketCategoryTree($market_id),1);
        }
    }

    /**
     * 市场分类
     * @return array|null
     */
    public function getMarketTopLevel($role=3){
        $session = new Session();
        $market_id = $session->get('market_id');
        $table = new Table('market_category');

        $filter = " LEFT JOIN category ON category.category_id = market_category.category_id WHERE market_id = ? ";
        ($role == 3) && $filter .= " AND status = 1";
        $filter .= " ORDER BY weight";
        $fields = array(
            'category.category_id',
            'category.category_name',
            'market_category.status'
        );
        $data = $table->lists($filter,array($market_id),$fields);
        return $data;
    }

    /**
     * 市场分类数据
     * @return mixed
     */
    private function marketCategoryTree(){
        $data = $this->getMarketTopLevel(0);
        $originData = $this->categoryTree();
        foreach($data as $key => $value){
            foreach($originData as $k => $v){
                if($value['category_id'] == $v['category_id']) unset($originData[$k]);
            }
        }
        return array_merge($data,$originData);
    }

    private function tree($data,$type){
        $tree =array();
        foreach($data as $k=>$v){
            $tree[$k]['icon'] = "/static/upload/category/".$v["category_id"].".png";
            $tree[$k]['title'] = $v['category_name'];
            $tree[$k]['expanded'] = 'true';
            $tree[$k]['folder'] = 'true';
            $tree[$k]['level'] = 1;
            ($type == 1) && $tree[$k]['selected'] = ($v['status']==1) ? true : false;
            $tree[$k]['data-id'] = $v['category_id'];
        }
        return $tree;
    }

    /**
     * 分类元数据
     * @return array
     */
    private function categoryTree(){
        $table = new Table('category');
        $filter = " WHERE category_id IN (SELECT end FROM category_category GROUP BY end HAVING COUNT(end) = ?)";
        $fields = array(
            'category_name',
            'category_id'
        );
        $data = $table->lists($filter,array(1),$fields);
        return $data;
    }


    /**
     * 删除市场分类
     * @param $market_id
     * @return int|mixed
     */
    private function delMarketCategory($market_id){
        $table = new Table('market_category');
        $status = $table->delete("WHERE market_id = ?",array($market_id));
        $code = $status ? 0 : 1;
        return $code;
    }

    /**
     * 添加市场分类
     * @param $market_id
     * @param $source
     * @return int|mixed
     */
    public function addMarketCategory($market_id,$source){
        $code = $this->delMarketCategory($market_id);
        if ($code != 0){
            return 1;
        }
        $weight = 1;
        $table = new Table('market_category');
        foreach($source['children'] as $k => $v){
            $status = ($v['selected']=="true")? 1 : 0;
            $status = $table->add(array('market_id'=>$market_id,'category_id'=>$v['data']['data-id'],'weight'=>$weight++,'status'=>$status));
            if(!$status){
                $code = 1;
                $this->delMarketCategory($market_id);
                break;
            }
        }
        return $code;
    }





}