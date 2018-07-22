<?php
namespace Model;
use HY\Model;
!defined('HY_PATH') && exit('HY_PATH not defined.');
class Forum extends Model {
    public function get_row($id,$name = '*'){
        return $this->find($name,['id'=>$id]);
    }
    //修改整数数据
    //分类ID
    //更新字段
    //+ - 
    //数量
    public function update_int($id,$key='threads',$type="+",$size=1){
        //{hook m_forum_update_int_1}
        $key .= ($type=='+') ? '[+]' : '[-]';
        $this->update(array(
            $key=>$size
        ),array(
            'id'=>$id
        ));
        //{hook m_forum_update_int_2}
    }
    //判断用户组板块权限
    //$id = 分类ID
    //$group = 用户组ID
    //判断权限类型 vforum vthread trehad post  downfile
    public function is_comp($id,$group,$type){
        //{hook m_forum_is_comp_1}
        $json = json_decode(
            $this->find("json",array(
                "id"=>$id
            ))
        ,true);
        //{hook m_forum_is_comp_2}
        //echo $json[$type];
        $str = isset($json[$type]) ? $json[$type] : false ;
        $arr = explode(",",$str);
        //{hook m_forum_is_comp_3}
        foreach ($arr as $v) {
            //{hook m_forum_is_comp_4}
            if($v == $group)
                return false;
        }
        //{hook m_forum_is_comp_5}
        return true;
    }
    //获取分类数据
    //分类ID
    public function read($id){
        //{hook m_forum_read_1}
        return $this->get_row($id);
    }
    //获取全部分类数组
    public function read_all(){
        //{hook m_forum_read_all_1}
        //去除分类所有数据
        $forum = $this->select("*",['ORDER'=>['id'=>'ASC']]);
        if(empty($forum)) 
            $forum=array();
        //{hook m_forum_read_all_2}
        $tmp = array();
        //进行ID序列化
        foreach ($forum as $k => $v) {
            //{hook m_forum_read_all_3}
            $tmp[intval($v['id'])] = $v;
        }

        //{hook m_forum_read_all_4}
        return $tmp;
    }

    //子分类排序
    public function format(&$forum){
        //{hook m_forum_format_1}
        $tmp_forum = $forum;
        foreach ($forum as $key => &$v) {
            $v['z']=false;
            //{hook m_forum_format_2}
            if($v['fid']!= -1){
                //{hook m_forum_format_3}
                foreach ($tmp_forum as &$vv) {
                    //{hook m_forum_format_4}
                    if($v['fid'] == $vv['id']){
                        //{hook m_forum_format_5}
                        $forum[$v['fid']][$v['id']] = $v;
                        $forum[$v['fid']]['z'] = true;
                        //$vv[] = $v;
                        //echo $v['id'];
                        //unset($forum[$v['id']]);
                    }
                }

            }
        }
        //{hook m_forum_format_6}
        
    }

    //{hook m_forum_fun}
}
