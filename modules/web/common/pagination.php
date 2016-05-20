<?php
/**
 * Created by PhpStorm.
 * User: Monk
 * Date: 2016/1/27
 * Time: 17:23
 */
namespace web\common;

class Pagination{

    private $current;
    private $size;
    private $total;
    private $url;

    /**
     * @param int $current, 当前页
     * @param int $size, 每页大小
     * @param int $total, 总数
     * @param string $url
     */
    public function __construct($current, $size, $total,$url=null){

        $this->current = $current;
        $this->size = $size;
        $this->total=$total;
        $this->url = $url;
        if(strpos('?', $this->url) !== FALSE){
            $this->url .= '?page=';
        }
        else{
            $this->url .= '&page=';
        }
    }

    public function render(){

        $length = 10;//显示几个分页条目

        $total_length = ceil($this->total / $this->size);//总页数

        $numbers = array();

        for($i=$this->current; $i <= $total_length; $i++){
            $numbers[] = $i;
            if(count($numbers) == $length){
                break;
            }
        }

        if(count($numbers) < $length){
            for($i=$this->current-1; $i > 0; $i--){
                array_unshift($numbers, $i);
                if(count($numbers) == $length){
                    break;
                }
            }
        }

        \ob_start();
        require_once 'modules/web/view/common/pagination.php';
        \ob_end_flush();
    }
}