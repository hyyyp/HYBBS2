<?php
namespace Model;
use HY\Model;
!defined('HY_PATH') && exit('HY_PATH not defined.');
class Post_post extends Model {
    public function get_row($id,$name = '*'){
        return $this->find($name,['id'=>$id]);
    }
    /**
     * 获取子评论列表
     * @access public
     * @param int $pid 评论ID
     * @param int $pageid 数据页数
     * @param int $size 获取数量
     * @param array $order 排序方式
     * @return array | boolean
    */
    public function get_list($pid,$pageid,$size,$order = ['id'=>'DESC']){
    	return $this->select('*',[
            'pid'   => $pid,
            'ORDER' => $order,
            'LIMIT' => [
                ($pageid-1) * $size,
                $size
            ]
        ]);
    }
    //删除某评论下所有子评论 传入PID 评论ID
    public function del_list($pid){
    	return $this->delete([
            'pid'=>$pid
        ]);
    }


    

}