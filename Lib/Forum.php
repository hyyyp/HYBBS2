<?php
namespace Lib;

class Forum{
    public function is_comp($id,$group,$type,$data){
        $json = json_decode($data,true);

        $str = isset($json[$type]) ? $json[$type] : false ;
        $arr = explode(",",$str);
        
        foreach ($arr as $v) {
            if($v == $group)
                return false;
        }
        return true;
    }
}
