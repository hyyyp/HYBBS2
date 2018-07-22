<?php
namespace Model;
use HY\Model;
!defined('HY_PATH') && exit('HY_PATH not defined.');
class User extends Model {
    /**
     * 获取某用户 某字段值
     * @access public
     * @param int $uid 用户UID
     * @param string $name  字段名 [*=All]
     * @return array | boolean
    */
    public function get_row($uid,$name = '*'){
        return $this->find($name,['uid'=>$uid]);
    }
    /**
     * 获取某用户数据
     * @access public
     * @param int $uid 用户UID
     * @return array | boolean
    */
    public function read($uid){
        //{hook m_user_read_1}
        return $this->get_row($uid);
    }
    /**
     * 通过用户名获取用户数据
     * @access public
     * @param string $user 用户名
     * @return array | boolean
    */
    public function user_read($user){
        //{hook m_user_user_read_1}
        return $this->find("*",['user'=>$user]);
    }
    /**
     * 通过邮箱名称获取用户数据
     * @access public
     * @param string $email 邮箱名
     * @return array | boolean
    */
    public function email_read($email){
        //{hook m_user_email_read_1}
        return $this->find("*",['email'=>$email]);
    }
    /**
     * 通过用户UID 判断用户是否存在
     * @access public
     * @param int $uid 用户UID
     * @return boolean
    */
    public function is_uid($uid){
        //{hook m_user_is_uid_1}
        return $this->has(['uid'=>$uid]);
    }
    /**
     * 判断用户名是否存在
     * @access public
     * @param string $user 用户名
     * @return boolean
    */
    public function is_user($user){
        //{hook m_user_is_user_1}
        return $this->has(['user'=>$user]);
    }
    /**
     * 判断邮箱名是否存在
     * @access public
     * @param string $email 邮箱名
     * @return boolean
    */
    public function is_email($email){
        //{hook m_user_is_email_1}
        return $this->has(['email'=>$email]);
    }
    /**
     * 添加账号
     * @access public
     * @param string $email 邮箱名
     * @return array | boolean
    */
    public function add_user($user,$pass,$email,$gid = 2){
        //{hook m_user_add_user_1}
        $salt = substr(md5(mt_rand(10000000, 99999999).NOW_TIME), 0, 8);
        //{hook m_user_add_user_2}
        $this->insert(array(
            'user'=>$user,
            'pass'=>L("User")->md5_md5($pass,$salt),
            'email'=>$email,
            'salt'=>$salt,
            'atime'=>NOW_TIME,
            'gid'=>$gid,
            'ctime'=>NOW_TIME,
        ));
        return $this->id();
    }
    /**
     * 用户UID 转 用户名
     * @access public
     * @param int $uid 用户UID
     * @return string | boolean
    */
    public function uid_to_user($uid){
        //{hook m_user_uid_to_user_1}
        return $this->get_row($uid,'user');
    }
    /**
     * 用户名 转 用户UID
     * @access public
     * @param string $user 用户名
     * @return int | boolean
    */
    public function user_to_uid($user){
        //{hook m_user_user_to_id_1}
        return $this->find('uid',['user'=>$user]);
    }
    /**
     * 更新整数字段 (从原值加减值)
     * @access public
     * @param int $uid 用户UID
     * @param string $key 字段名
     * @param string $type [+ | -] 加减字段
     * @param int $size 加减值
     * @return array | boolean
    */
    public function update_int($uid,$key='gold',$type="+",$size=1){
        //{hook m_user_update_int_1}
        $key .= ($type=='+') ? '[+]' : '[-]';
        //{hook m_user_update_int_2}
        $this->update([
            $key=>$size
        ],[
            'uid'=>$uid
        ]);
        //{hook m_user_update_int_3}
    }
     //获取用户头像
    public function avatar($user){
        //{hook m_user_avatar_1}
        $path = INDEX_PATH . 'upload/avatar/' . md5($user);
        $path1 = 'upload/avatar/' . md5($user);
        //{hook m_user_avatar_2}
        if(!file_exists($path.'-a.jpg'))
            return array(
                'a'=>'public/images/user.gif',
                'b'=>'public/images/user.gif',
                'c'=>'public/images/user.gif',
            );
        //{hook m_user_avatar_3}
        return array(
            "a"=>$path1."-a.jpg",
            "b"=>$path1."-b.jpg",
            "c"=>$path1."-c.jpg"
        );
    }
    /**
     * 获取用户金币数量
     * @access public
     * @param int $uid 用户UID
     * @return int | boolean
    */
    public function get_gold($uid){
        //{hook m_user_get_gold_1}
        return $this->get_row($uid,'gold');
    }

    /**
     * 获取用户积分
     * @access public
     * @param int $uid 用户UID
     * @return int | boolean
    */
    public function get_credits($uid){
        //{hook m_user_get_credits_1}
        return $this->get_row($uid,'credits');
    }

    /**
     * 设置用户组GID 更改用户所在用户组
     * @access public
     * @param int $uid 用户UID
     * @param int $gid 用户组GID
     * @return int | boolean
    */
    public function set_gid($uid,$gid){
        //{hook m_user_set_gid_1}
        return $this->update(['gid'=>$gid],['uid'=>$uid]);
    }
    /**
     * 获取用户所在用户组ID 获取用户GID
     * @access public
     * @param int $uid 用户UID
     * @return int | boolean
    */
    public function get_gid($uid){
        //{hook m_user_get_group_1}
        return $this->find('gid',['uid'=>$uid]);
    }
    /**
     * 获取粉丝数量
     * @access public
     * @param int $uid 用户UID
     * @return int | boolean
    */
    public function get_fans($uid){
        //{hook m_user_get_fans_1}
        return $this->get_row($uid,'fans');
    }
    /**
     * 获取关注数量
     * @access public
     * @param int $uid 用户UID
     * @return int | boolean
    */
    public function get_follow($uid){
        //{hook m_user_get_follow_1}
        return $this->get_row($uid,'follow');
    }

    /**
     * 自动将数组内的uid转换为 user
     * @access public
     * @param array &$arr 二维数组
     * @param string $new_key 储存用户名的新key
     * @param string $key UID字段名
     * @return void
    */
    public function auto_add_user(&$arr,$new_key='user',$key='uid'){
        $user_tmp = array();
        foreach($arr as &$v){
            if(empty($user_tmp[$v[$key]])){
                $user_tmp[$v[$key]] = $this->uid_to_user($v[$key]);
            }

            $v[$new_key]=$user_tmp[$v[$key]];
            //print_r($v);
        }
        
    }

    //{hook m_user_fun}
    
}
