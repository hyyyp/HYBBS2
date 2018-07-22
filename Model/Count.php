<?php
namespace Model;
use HY\Model;
!defined('HY_PATH') && exit('HY_PATH not defined.');
class Count extends Model {

    public function _get($name){
        //{hook m_count__get_1}
        $this->update(array('v[+]'=>1),array("name"=>$name));
        //{hook m_count__get_2}
        return $this->find('v',array('name'=>$name));
    }
    public function xget($name){
        //{hook m_count_xget_1}
        return $this->find('v',array('name'=>$name));
    }
    public function _set($name,$v){
        //{hook m_count__set_1}
    	if(!$this->has(array('name'=>$name))) //如果不存在该条数据 则创建
    		return $this->insert(array('name'=>$name,'v'=>$v));
        //{hook m_count__set_2}
    	return $this->update(array('v'=>$v),array('name'=>$name));
    }
    //{hook m_count_fun}
    
}
