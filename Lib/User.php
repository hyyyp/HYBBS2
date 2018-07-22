<?php
namespace Lib;

class User{
    //用户名检查 , 仅允许数字与字母 长度5-18   去前后空
	public function check_user(&$username){
		$username = trim($username);
		$username = strtolower($username);
		if(empty($username)) {
			return '用户名不能为空';
		} elseif(mb_strlen($username) > 18 || mb_strlen($username) < 2) {
			return '用户名长度不符合标准:'.mb_strlen($username);
		} elseif(str_replace(array("\t", "\r", "\n", ' ', '　', ',', '，', '-'), '', $username) != $username) {
			return '用户名中不能含有空格和 , - 等字符';
		} elseif(!preg_match('#^[\w\'\-\x7f-\xff]+$#', $username)) {
			return '用户名只允许: 数字,字母,中文!';
		} elseif(htmlspecialchars($username) != $username) {
			return '用户名中不能含有HTML字符';
		}
		if(($error = $this->have_badword($username))) {
			return '包含敏感词：'.$error;
		}
		return '';
	}
    public function check_pass($pass){
		if(strlen($pass) < 5)
			return false;
		return true;
	}
	public function check_email(&$email) {
		
		if(empty($email)) {
			return 'EMAIL 不能为空';
		} elseif(!preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $email)) {
			return 'Email 格式不对';
		} elseif(mb_strlen($email) < 6) {
			return 'Email 太短';
		}
		return '';
	}
	public function md5_md5($s, $salt = '') {
		return md5(md5($s).$salt);
	}
	public function set_cookie($data){
		$arr=array(
			'uid'=>$data['uid'],
			'user'=>$data['user'],
			'pass'=>$data['pass'],
			'gid'=>$data['gid'],
			'ip'=>CLIENT_IP
		);
		
		return L("Encrypt")->encrypt(json_encode($arr),C("MD5_KEY"));
	}
	public function get_cookie($cookie){
		$json = L("Encrypt")->decrypt($cookie,C("MD5_KEY"));
		return json_decode($json,true);
	}
    public function have_badword($user){
    	if(!BBSCONF('user_have_badword'))return;
		$badword = explode(',',BBSCONF('user_have_badword'));
		if(!empty($badword)) {
			foreach($badword as $v) {
				if(strpos($user, $v) !== FALSE) {
					return $v;
				}
			}
		}
		return '';
	}
}
