<?php
namespace Lib;
class Usergroup{
	// 获取 某用户组的 某权限真假 return bool 
    public function read($id,$type,$usergroup){

    	if(!isset($usergroup[$id]['json'])) return false;
        $json = json_decode($usergroup[$id]['json'],true);
        return isset($json[$type])?$json[$type]:false;
    }
}