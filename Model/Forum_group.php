<?php
namespace Model;
use HY\Model;
!defined('HY_PATH') && exit('HY_PATH not defined.');
class Forum_group extends Model {
	
	public function read_all(){
		$data = $this->select("*");
		return $data;
	}
	public function read_all_cache(){
		$forum_group = cache('forum_group');
		if(empty($forum_group) || DEBUG){
			$forum_group = $this->read_all();
			cache('forum_group',$forum_group);
		}
		return $forum_group;
	}



	
}