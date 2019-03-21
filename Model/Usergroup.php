<?php
namespace Model;
use HY\Model;
!defined('HY_PATH') && exit('HY_PATH not defined.');
class Usergroup extends Model {
    public function get_row($gid,$name = '*'){
        return $this->find($name,['gid'=>$gid]);
    }
    // $id 用户组ID 返回权限数组
    public function read_json($gid){
        //{hook m_usergroup_read_json_1}
        $json = $this->select("json",[
            "gid"=>$gid
        ]);
        //{hook m_usergroup_read_json_2}
        return json_decode($json,true);
    }

    
    public function gid_to_name($gid){
        //{hook m_usergroup_id_to_name_1}
        return $this->get_row($gid,'name');
    }
    public function format(&$usergroup){
        //{hook m_usergroup_format_1}
        if(empty($usergroup))
            return;
        //{hook m_usergroup_format_2}
        $tmp = $usergroup;
        $usergroup = array();
        //{hook m_usergroup_format_3}
        foreach ($tmp as $k => $v) {
            $v['font_css']=str_replace("\n", '', $v['font_css']);
            $usergroup[intval($v['gid'])] = $v;

        }
    }
    //检测升级
    public function check_up($uid){
        $User = M('User');
        $data = $User->get_row($uid,['gid','credits']);
        $now_gid = $data['gid'];
        $credits = $data['credits'];
        //管理员无法触发升级
        if($now_gid != C('ADMIN_GROUP')){
            $usergroup = $this->select('*');
            $this->format($usergroup);
            //如果本身所在用户组是无法升级的 则跳过升级
            if($usergroup[$now_gid]['credits'] == -1 || $usergroup[$now_gid]['credits_max'] == -1)
                return false;
            //删除当前所在用户组
            unset($usergroup[$now_gid]);
            //是否被升级
            $up = false;
            //过滤无法升级的用户组
            foreach ($usergroup as $k => &$v) {
                if($v['credits'] == -1 || $v['credits_max'] == -1)
                    unset($usergroup[$k]);
            }
            //var_dump($usergroup);
            unset($v);
            foreach ($usergroup as $k => $v) {
                if($credits >= $v['credits'] && $credits <= $v['credits_max']){
                    $User->set_gid($uid,$v['gid']);
                    $up=true;
                    break;
                }
            }
            return $up;
        }
        return false;
    }
    //{hook m_usergroup_fun}
}
