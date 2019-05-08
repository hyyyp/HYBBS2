<?php
namespace Model;
use HY\Model;
!defined('HY_PATH') && exit('HY_PATH not defined.');
class Chat extends Model{
	// $uid1= 接收者
	// $uid2= 发送者 , 0=系统消息
	public function send($uid1,$uid2,$content){
		//{hook m_chat_send_1}
		//插入聊天内容
		$this->insert(
			[
				'uid1'=>$uid1,
				'uid2'=>$uid2,
				'content'=>$content,
				'atime'=>NOW_TIME
			]
		);
		//{hook m_chat_send_2}
		$Friend = M("Friend");
        $Friend->update_int($uid1,$uid2);
        //{hook m_chat_send_3}
        $Chat_count = M("Chat_count");
        $Chat_count->update_int($uid1);
        //{hook m_chat_send_4}
	}
	//系统消息
	public function sys_send($uid,$content){
		//{hook m_chat_sys_send_1}
		return $this->send($uid,0,$content);
	}
	//{hook m_chat_fun}
}