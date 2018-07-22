<?php
namespace Model;
use HY\Model;
!defined('HY_PATH') && exit('HY_PATH not defined.');
class File extends Model {
	public function get_row($id,$name = '*'){
        return $this->find($name,['id'=>$id]);
    }
	//获取文件信息
	public function read($id){
		//{hook m_file_read_1}
		return $this->get_row($id);
	}
	//判断附件是否属于该UID
	public function is_comp($id,$uid){
		//{hook m_file_is_comp_1}
		return $this->has([
			'AND'=>[
				'id'=>$id,
				'uid'=>$uid
			]
		]);
	}
	public function get_name($id){
		//{hook m_file_get_name_1}
		return $this->get_row($id,'filename');
	}
	//{hook m_file_fun}
}