<?php
namespace Model;
use HY\Model;
!defined('HY_PATH') && exit('HY_PATH not defined.');
/**
 * 本Model没有对应数据表，用于集合论坛数据快捷读写，多数采用缓存。
 * 函数列表：
 * get_post_data($pid) //获取评论数据，评论不存在返回false，反之获得数据array
 * 
*/
class Data extends Model {
    public $CacheObj;
    public function __construct(){
        $this->CacheObj = cache(array());
    }
    /**
     * 获取某评论数据
     * @access public
     * @param int $pid 评论pid
     * @return array | boolean
    */
    public function get_post_data($pid){
        $PostData = $this->CacheObj->get('post_data_'.$pid);
        if(empty($PostData) || DEBUG){
            //{hook m_data_get_post_data_1}
            $PostData = S('Post')->find("*",['pid'=>$pid]);
            if(empty($PostData))
                return false;
            $this->CacheObj->set('post_data_'.$pid,$PostData);
            //{hook m_data_get_post_data_2}
        }
        //{hook m_data_get_post_data_3}
        return $PostData;
    }
   
    //{hook m_data_fun}
    
}
