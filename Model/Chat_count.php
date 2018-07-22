<?php
namespace Model;
use HY\Model;
!defined('HY_PATH') && exit('HY_PATH not defined.');
class Chat_count extends Model{
	public function update_int($uid,$type="+",$size=1){
		//{hook m_chat_count_update_int_1}
		if($this->has(array('uid'=>$uid))){
			//{hook m_chat_count_update_int_2}
			if($type==="+")
				$this->update(array("c[{$type}]"=>$size,'atime'=>NOW_TIME),array('uid'=>$uid));
			else
				$this->update(array("c[{$type}]"=>$size),array('uid'=>$uid));
			$this->get_c($uid);
		}
		//{hook m_chat_count_update_int_3}
		$this->insert(array('uid'=>$uid,'c'=>1,'atime'=>NOW_TIME));
		//{hook m_chat_count_update_int_4}
	}
	//获取未读消息数量
	public function get_c($uid){
		//{hook m_chat_count_get_c_1}
		$c = $this->find('c',['uid'=>$uid]);
		if($c < 0)
			$this->clear_c($uid);
		//{hook m_chat_count_get_c_2}
		return ($c < 0 )?0:$c;
	}
	public function clear_c($uid){
		$this->update(['c'=>0],['uid'=>$uid]);
	}
	public function get_time($uid){
		//{hook m_chat_count_get_time_1}
		$atime = $this->find('atime',array('uid'=>$uid));
		//{hook m_chat_count_get_time_2}
		return (!$atime)?0:$atime;
	}
	//{hook m_chat_count_fun}
}