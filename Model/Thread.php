<?php
namespace Model;
use HY\Model;
!defined('HY_PATH') && exit('HY_PATH not defined.');
class Thread extends Model {
    /**
     * 获取某字段值
     * @access public
     * @param int $tid 文章ID
     * @param string $name  字段名 [*=All]
     * @return array | boolean
    */
    public function get_row($tid,$name = '*'){
        //{hook m_thread_get_row_1}
        return $this->find($name,['tid'=>$tid]);
    }
    /**
     * 获取主题数据
     * @access public
     * @param int $tid 文章ID
     * @return array | boolean
    */
    public function read($tid){
        //{hook m_thread_read_1}
        return $this->get_row($tid);
    }
    //判断主题是否还存在
    public function is_tid($tid){
        //{hook m_thread_is_tid_1}
        return $this->has(['tid'=>$tid]);
    }
    /**
     * 获取主题标题
     * @access public
     * @param int $tid 文章ID
     * @return string | boolean
    */
    public function get_title($tid){
        //{hook m_thread_get_title_1}
        return $this->get_row($tid,'title');
    }
    /**
     * 删除主题 (不删除包含评论 以及 主题文章内容数据)
     * @access public
     * @param int $tid  文章ID
     * @return boolean
    */
    public function del($tid){
        //{hook m_thread_del_1}
        $this->delete(['tid'=>$tid]);
    }
    /**
     * 更新整数字段 (从原值加减值)
     * @access public
     * @param int $tid 文章ID
     * @param string $key 字段名
     * @param string $type [+ | -] 加减字段
     * @param int $size 加减值
     * @return boolean
    */
    public function update_int($tid,$key,$type = "+",$size = 1){
        //{hook m_thread_update_int_1}
        $key .= ($type=='+') ? '[+]' : '[-]';
        //{hook m_thread_update_int_2}
        $this->update([
            $key=>$size
        ],[
            'tid'=>$tid
        ]);
    }
    /**
     * 获取某用户主题列表
     * @access public
     * @param int $uid 用户ID
     * @param int $pageid 数据页数
     * @param int $size 获取数量
     * @param array $order 排序方式
     * @return array | boolean
    */
    public function get_user_thread_list($uid, $pageid = 1, $size = 10, $order = ['tid'=>'DESC']){
        //{hook m_get_user_thread_list_1}
        $where = [
            'AND'=>[
                'uid'=>$uid,
                //{hook m_get_user_thread_list_2}
            ],
            'ORDER'=>$order,
            'LIMIT' => [ ($pageid-1) * $size, $size ]
        ];
        //{hook m_get_user_thread_list_3}
        return $this->select('*',$where);
    }
    /**
     * 获取主题列表
     * @access public
     * @param int $pageid 数据页数
     * @param int $size 获取数量
     * @param array $order 排序方式
     * @return array | boolean
    */
    public function get_thread_list($pageid = 1 , $size = 10,$order = ['tid'=>'DESC']){
        //{hook m_get_thread_list_1}
        $where = [
            'AND'=>[
                //{hook m_get_thread_list_2}
            ],
            'ORDER' => $order,
            'LIMIT' => [
                ($pageid-1) * $size,
                $size
            ]
        ];
        //{hook m_get_thread_list_3}
        return $this->select('*',$where);
    }
    /**
     * 获取某分类主题列表
     * @access public
     * @param int $fid 分类ID
     * @param int $pageid 数据页数
     * @param int $size 获取数量
     * @param array $order 排序方式
     * @return array | boolean
    */
    public function get_forum_thread_list($fid, $pageid = 1 , $size = 10,$order = ['tid'=>'DESC']){
        //{hook m_get_forum_thread_list_1}
        $where = [
            'AND'=>[
                'fid'   => $fid,
                //{hook m_get_forum_thread_list_2}
            ],
            'ORDER' => $order,
            'LIMIT' => [
                ($pageid-1) * $size,
                $size
            ]
        ];
        //{hook m_get_forum_thread_list_3}
        return $this->select('*',$where);
    }
    /**
     * 获取全站置顶帖子
     * @access public
     * @return array | boolean
    */
    public function get_top_thread(){
        //{hook m_get_top_thread_1}
        $where = [
            'AND'=>[
                'top'=>2,
                //{hook m_get_top_thread_2}
            ]
            
        ];
        //{hook m_get_top_thread_3}
        return $this->select('*',$where);
    }
    /**
     * 获取某分类置顶帖子
     * @access public
     * @param int $fid 分类ID
     * @return array | boolean
    */
    public function get_forum_top_thread($fid){
        //{hook m_get_forum_top_thread_1}
        $where = [
            'AND'=>[
                'top'=>1,
                'fid'=>$fid,
                //{hook m_get_forum_top_thread_2}
            ]
        ];
        //{hook m_get_forum_top_thread_3}
        return $this->select('*',$where);
    }
    /**
     * 主题列表格式化
     * @access public
     * @param array &$thread_list 主题列表
     * @return void
    */
    public function format(&$thread_list){
        //{hook m_thread_format_1}
        if(empty($thread_list))
            return;
        static $user_tmp = array();
        $User = M("User");
        //{hook m_thread_format_2}
        foreach ($thread_list as  &$v){
            //{hook m_thread_format_3}
            if(empty($user_tmp[$v['uid']])){
                $user_tmp[$v['uid']] = $User->uid_to_user($v['uid']);
            }
            //{hook m_thread_format_4}
            if($v['buid']){
                if(empty($user_tmp[$v['buid']])){
                    $user_tmp[$v['buid']] = $User->uid_to_user($v['buid']);
                    
                }
                $v['buser'] = $user_tmp[$v['buid']];
                $v['buser_avatar'] =$User->avatar($v['buid']);
            }
            //{hook m_thread_format_5}
            //UID获取用户名
            $v['user'] = $user_tmp[$v['uid']];
            //摘要去掉标签
            $v['summary'] = strip_tags($v['summary']);
            //$v['atime'] = humandate($v['atime']);
            $v['avatar']=$User->avatar($v['uid']);
            if(!empty($v['img'])){
                $v['image']=explode(",", $v['img']);
                $v['image_count']=count($v['image'])-1;
            }
            //{hook m_thread_format_6}
        }

    }
    /**
     * 判断用户是否回复过某主题
     * @access public
     * @param int $uid 用户ID
     * @param int $tid 主题ID
     * @return bool
    */
    public function is_user_post($uid,$tid){
        //{hook m_thread_is_user_post_1}
        return S("Post")->has(["AND"=>['uid'=>$uid,'tid'=>$tid]]);
    }
    //{hook m_thread_fun}
}
