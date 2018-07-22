<?php
namespace Action;
use HY\Action;
!defined('HY_PATH') && exit('HY_PATH not defined.');
class Ajax extends HYBBS {

    public function __construct() {
		parent::__construct();
        //{hook a_ajax_init}
        $this->view = 'admin';
    }
    
    //获取用户信息JSON
    //传入 GET [UID] 用户ID
    public function userjson(){
        //{hook a_ajax_userjson_1}
    	$data = array('error'=>true);
    	$uid = intval(X("get.uid"));
    	if(!$uid)
    		return $this->json(array('error'=>false,'info'=>'缺少用户UID参数'));
        //{hook a_ajax_userjson_2}
    	$User = M("User");
    	if(!$User->is_uid($uid))
    		return $this->json(array('error'=>false,'info'=>'输入的UID用户不存在'));
        //{hook a_ajax_userjson_3}
    	$ud = $User->read($uid);
    	$data['user']      = $ud['user'];
    	$data['avatar']    = $this->avatar($ud['user']);
    	$data['atime_str'] = humandate($ud['atime']);
    	$data['threads']   = $ud['threads'];
    	$data['posts']     = $ud['posts'];
    	$data['gid']       = $ud['gid'];
    	$data['groupname'] = $this->_usergroup[$ud['gid']]['name'];
    	$data['gold']      = $ud['gold'];
        $data['ps']        = $ud['ps'];
    	$data['href']      = HYBBS_URLA('my',$data['user']);
        $data['ol_atime']     = humandate(S('Online')->find('atime',['uid'=>$uid]));
        $data['ol']        = S('Online')->has(['AND'=>['uid'=>$uid,'atime[>]'=>NOW_TIME-BBSCONF('out_s')]]);
        // 好友系统
        $data['login'] = false;
        if(IS_LOGIN){
            if(NOW_UID != $uid){
                $Friend = M("Friend");

                $data['login'] = true;
                $data['friend_state'] = $Friend->get_state(NOW_UID,$uid);
            }
            

        }
        //{hook a_ajax_userjson_v}
    	return $this->json($data);
    }
    //传user账户名 返回头像JSON
    public function useravatar(){
        $user = X("get.user");
        //{hook a_ajax_useravatar_v}
        //print_r($this->avatar($user));return;
        return $this->json($this->avatar($user));
    }

    //下载附件
    public function Downfile(){
        //附件ID
        $fileid = intval(X("get.id"));
        //{hook a_ajax_downfile_1}
        //检测用户组是否有权限下载
        $UsergroupLib = L("Usergroup");

        if(!$UsergroupLib->read(NOW_GID,'down',$this->_usergroup))
            $this->json(array('error'=>false,'info'=>'你没有权限下载附件','errorid'=>0));

        if(!$fileid)
            $this->json(array('error'=>false,'info'=>'参数不完整','errorid'=>1));
        //{hook a_ajax_downfile_2}
        $Fileinfo = M("Fileinfo");
        $File = M("File");
        $fileinfo_data = $Fileinfo->read($fileid);
        $file_data = $File->read($fileinfo_data['fileid']);


        $fid = S("Thread")->find("fid",['tid'=>$fileinfo_data['tid']]);
        //{hook a_ajax_downfile_3}
        //板块权限判断 当前用户组是否能在该分类下 下载附件
        if(!is_group_forum($fid,NOW_GID,'downfile',$this->_forum))
            $this->json(array('error'=>false,'info'=>'你所在用户组无法在此分类下载附件','errorid'=>1));
        //{hook a_ajax_downfile_4}


        
        $nogold = $UsergroupLib->read(NOW_GID,'nogold',$this->_usergroup);
        $FileGold = S("Filegold");
        if(!IS_LOGIN) //未登录设置用户ID为 -1 
            $this->_user['uid'] = -1;
        //{hook a_ajax_downfile_5}
        //如果附件需要付费 并且 当前用户组无免金币权限 并且 不是附件主人
        if($fileinfo_data['gold'] > 0 && !$nogold && $this->_user['uid'] != $file_data['uid']){
            //{hook a_ajax_downfile_6}
            if(!IS_LOGIN)
                $this->json(array('error'=>false,'info'=>'请登录后购买下载','errorid'=>2));
            //当前用户是否购买了这个附件
            $is_buy = $FileGold->has(['AND'=>['uid'=>$this->_user['uid'],'fileid'=>$fileinfo_data['fileid']]]);

            //{hook a_ajax_downfile_7}
            //如果没有购买 则返回购买信息
            if(!$is_buy)
                $this->json(array('error'=>false,'info'=>$fileinfo_data['gold'],'errorid'=>3));


        }
        //{hook a_ajax_downfile_8}

        //{hook a_ajax_downfile_10}
        //附件下载链接
        $path = INDEX_PATH.'upload/userfile/'.$fileinfo_data['uid'].'/'.$file_data['md5name'];
        $downlink = WWW.'upload/userfile/'.$fileinfo_data['uid'].'/'.$file_data['md5name'];

        if(IS_AJAX){
            $this->json(array('error'=>true,'info'=>$downlink));
        }
        //{hook a_ajax_downfile_11}
        if(!is_file($path)) {
            $this->json(array('error'=>false,'info'=>'附件文件不见了!','errorid'=>4));
        }
        //获取附件大小
        //$filesize = filesize($path);
        //{hook a_ajax_downfile_12}
        if(stripos($_SERVER["HTTP_USER_AGENT"], 'MSIE') !== FALSE) {
            $file_data['filename'] = urlencode($file_data['filename']);
            $file_data['filename'] = str_replace("+", "%20", $file_data['filename']);
        }
        $Fileinfo->update(['downs[+]'=>1],['fileid'=>$fileinfo_data['fileid']]);

        //{hook a_ajax_downfile_13}
        $timefmt = date('D, d M Y H:i:s', $_SERVER['time']).' GMT';
        header('Date: '.$timefmt);
        header('Last-Modified: '.$timefmt);
        header('Expires: '.$timefmt);
           
        header('Cache-control: max-age=86400');
        header('Content-Transfer-Encoding: binary');
        header("Pragma: public");
        header('Content-Disposition: attachment; filename="'.$file_data['filename'].'"');
        header('Content-Type: application/octet-stream');
        //{hook a_ajax_downfile_14}
        
        readfile($path);
        exit;

    }
    //购买附件
    public function buyfile(){
        //{hook a_ajax_buyfile_1}
        if(!IS_LOGIN)
            return $this->json(array('error'=>false,'info'=>'请登录后购买'));
        //附件ID
        //{hook a_ajax_buyfile_2}
        $fileid = intval(X("post.id"));
        $User= M("User");
        $FileGold = S("FileGold");
        $Fileinfo = M("Fileinfo");
        //{hook a_ajax_buyfile_3}
        //检查是否重复购买
        if($FileGold->has(["AND"=>['uid'=>NOW_UID,'fileid'=>$fileid]]))
            return $this->json(array('error'=>false,'info'=>'你已经购买过了'));
        //{hook a_ajax_buyfile_4}
        //获取用户当前金币数量
        $gold = $User->get_gold(NOW_UID);

        //获取附件信息
        $file_data = $Fileinfo->read($fileid);

        if($file_data['gold'] > $gold)
            return $this->json(array('error'=>false,'info'=>'你并没足够的金币购买'));
        //{hook a_ajax_buyfile_5}
        //购买附件 扣费
        $User->update_int(NOW_UID,'gold','-',$file_data['gold']);
        //附件作者 加钱
        $User->update_int($file_data['uid'],'gold','+',$file_data['gold']);
        $FileGold->insert(array('uid'=>NOW_UID,'fileid'=>$fileid));
        S("Log")->insert(array(
            'uid'=>NOW_UID,
            'gold'=>"-{$file_data['gold']}",
            'credits'=>0,
            'content'=>'购买附件 文章ID['.$file_data['tid'].']',
            'atime'=>NOW_TIME
        ));
        S("Log")->insert(array(
            'uid'=>$file_data['uid'],
            'gold'=>"{$file_data['gold']}",
            'credits'=>0,
            'content'=>'购买附件作者收入 文章ID['.$file_data['tid'].']',
            'atime'=>NOW_TIME
        ));

        //{hook a_ajax_buyfile_v}
        return $this->json(array('error'=>true,'info'=>'购买成功'));




    }
    public function buythread(){
        //{hook a_ajax_buythread_1}
        if(!IS_LOGIN)
            return $this->json(array('error'=>false,'info'=>'请登录后购买'));
        //{hook a_ajax_buythread_2}
        //主题ID
        $tid = intval(X("post.id"));
        $User= M("User");
        $Threadgold = S("Threadgold");
        $Thread = M("Thread");
        if($Threadgold->has(array('AND'=>array('uid'=>NOW_UID,'tid'=>$tid))))
            return $this->json(array('error'=>false,'info'=>'你已经购买过了'));
        //{hook a_ajax_buythread_3}
        //获取用户当前金币数量
        $gold = $User->get_gold(NOW_UID);

        $thread_data = $Thread->read($tid);
        //{hook a_ajax_buythread_4}
        if($thread_data['gold'] > $gold)
            return $this->json(array('error'=>false,'info'=>'你并没足够的金币购买'));
        //{hook a_ajax_buythread_5}
        //购买主题 扣费
        $User->update_int(NOW_UID,'gold','-',$thread_data['gold']);
        //主题作者 加钱
        $User->update_int($thread_data['uid'],'gold','+',$thread_data['gold']);
        $Threadgold->insert(array('uid'=>NOW_UID,'tid'=>$tid));
        S("Log")->insert(array(
            'uid'=>NOW_UID,
            'gold'=>"-{$thread_data['gold']}",
            'credits'=>0,
            'content'=>'购买主题 文章ID['.$tid.']',
            'atime'=>NOW_TIME
        ));
        S("Log")->insert(array(
            'uid'=>$thread_data['uid'],
            'gold'=>"{$thread_data['gold']}",
            'credits'=>0,
            'content'=>'付费主题作者收入 文章ID['.$tid.']',
            'atime'=>NOW_TIME
        ));
        //{hook a_ajax_buythread_v}
        return $this->json(array('error'=>true,'info'=>'购买成功'));
    }
    public function clear_mess(){
        if(!IS_LOGIN)
            $this->json(array('error'=>false,'info'=>'请重新登录'));
        S("Chat_count")->update(array('c'=>0),array('uid'=>NOW_UID));
        S("Friend")->update(array('c'=>0),array('uid2'=>NOW_UID));
        return $this->json(array('error'=>true,'info'=>'清空成功'));
    }

    //{hook a_ajax_fun}
}
