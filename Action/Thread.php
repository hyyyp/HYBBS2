<?php
namespace Action;
use HY\Action;
!defined('HY_PATH') && exit('HY_PATH not defined.');
class Thread extends HYBBS {
    public function __construct(){
		parent::__construct();
        //{hook a_thread_init}
	}
    public function index(){
        //{hook a_thread_index_0}
        $this->message("没有该文章");
        //{hook a_thread_index_1}
    }
    //帖子页面
    public function _no(){
        //{hook a_thread_empty_1}
        if(IS_GET){
            $pageid=intval(isset($_GET['HY_URL'][2]) ? $_GET['HY_URL'][2] : 1) or $pageid=1;

            $tid = intval(METHOD_NAME);
            $this->v('tid',$tid);

            //{hook a_thread_empty_2}
            $Thread = M("Thread");
            $User   = M("User");

            //获取文章数据
            $thread_data = $this->CacheObj->get('thread_data_'.$tid);
            if(empty($thread_data) || DEBUG){
                //{hook a_thread_empty_cache_1}
                $thread_data = $Thread->read($tid);
                if(empty($thread_data))
                    return $this->message("不存在该主题");
                //获取文章作者用户名以及头像
                $thread_data['user']=$User->uid_to_user($thread_data['uid']);
                $thread_data['avatar']=$this->avatar($thread_data['user']);
                $this->CacheObj->set('thread_data_'.$tid,$thread_data);
                //{hook a_thread_empty_cache_2}
            }

            //权限判断 
            if(!L("Forum")->is_comp($thread_data['fid'],NOW_GID,'vthread',$this->_forum[$thread_data['fid']]['json']))
                return $this->message("你没有权限访问这个帖子");

            //{hook a_thread_empty_3}
            

            //添加网站描述
            $this->conf['description'] = $thread_data['summary'];
            if($thread_data['hide'] || $thread_data['gold']){
                $this->conf['description'] = '内容需要回复 或 付费 才可浏览.';
            }
            $this->v('conf',$this->conf);

            //{hook a_thread_empty_4}
            $Post = S("Post");
            

            //处理隐藏帖子
            $thread_data['show'] = true;
            if($thread_data['hide']){
                if(!IS_LOGIN)
                    $thread_data['show'] = false;
                else{
                    //判断用户是否回复过
                    if($Thread->is_user_post(NOW_UID,$tid))
                        $thread_data['show'] = true;
                    else
                        $thread_data['show'] = false;
                }
            }
            //{hook a_thread_empty_44}
            $thread_data['gold_show'] = true;
            if($thread_data['gold']){
                if(!IS_LOGIN)
                    $thread_data['gold_show'] = false;
                else{
                    if(S("Threadgold")->has(['AND'=>['uid'=>NOW_UID,'tid'=>$tid]]) || NOW_UID == $thread_data['uid'])
                        $thread_data['gold_show'] = true;
                    else
                        $thread_data['gold_show'] = false;
                }
            }
            //{hook a_thread_empty_55}
            //版主 与 管理员 直接显示隐藏主题 不需要付费
            if(is_forumg($this->_forum,NOW_UID,$thread_data['fid']) || NOW_GID == C("ADMIN_GROUP")){
                $thread_data['gold_show'] = true;
                $thread_data['show'] = true;
            }
            //当前用户组 拥有 不花金币特权 直接显示
            $UsergroupLib = L("Usergroup");
            if($UsergroupLib->read(NOW_GID,'nogold',$this->_usergroup)){
                $thread_data['gold_show'] = true;
            }
            //{hook a_thread_empty_5}
            
            //获取文章评论列表
            
            $order=$order_a='ASC';
            if(X("get.order")=='desc')
                $order = $order_a = 'DESC';
            
            $PostList = $this->CacheObj->get("post_list_{$tid}_{$order_a}_{$pageid}");

            if((empty($PostList) || DEBUG) && $thread_data['posts'] != 0){
                //{hook a_thread_empty_cache_5}
                $post_list_limit = $this->conf['postlist'];
                
                if($pageid == 1){
                    $post_list_size = (($pageid-1) * ($this->conf['postlist']+1));
                    $post_list_limit++;
                    
                }else{
                    $post_list_size = (($pageid-1) * $this->conf['postlist'])+1;
                }

                $PostList = $Post->select('*',[
                    'tid'   => $tid,
                    "ORDER" => ['pid'=>$order],
                    "LIMIT" => [ $post_list_size , $post_list_limit],
                ]);
                //评论列表实例化
                $i = 0;
                if(!empty($PostList) && is_array($PostList)){
                    $User->auto_add_user($PostList);
                    foreach ($PostList as $key => &$v) {
                        $v['atime_str']=humandate($v['atime']);
                        $v['key'] = (($pageid-1)*10) + (++$i);
                        $v['avatar']=$this->avatar($v['user']);
                        //$v['group_name'] = $User->get_gid($v['uid']);
                        if($v['isthread']==1)
                            unset($PostList[$key]);
                    }
                }else{
                    $PostList = array();
                }
                
                $this->CacheObj->set("post_list_{$tid}_{$order_a}_{$pageid}",$PostList);
                //{hook a_thread_empty_cache_6}
            }
            /*1.5修复PID*/
            if(!$thread_data['pid']){
                $pid = $thread_data['pid'] = $Post->find('pid',['AND'=>['tid'=>$tid,'isthread'=>1]]);
                $Thread->update(['pid'=>$pid],['tid'=>$tid]);
            }
            
            if(!$PostList)
                $PostList = array();

            //获取文章内容
            $PostData = $this->CacheObj->get('post_data_'.$tid);
            if(empty($PostData) || DEBUG){
                //{hook a_thread_empty_cache_3}
                $PostData = $Post->find("*",['pid'=>$thread_data['pid']]);
                if(empty($PostData))
                    return $this->message("文章内容没有找到");
                $this->CacheObj->set('post_data_'.$tid,$PostData);
                //{hook a_thread_empty_cache_4}
            }

            //{hook a_thread_empty_6}

            //附件处理
            $File = M("File");
            $Fileinfo = S("Fileinfo");
            $Filelist = $Fileinfo->select("*",['tid'=>$tid]);
            unset($v);
            //用户是否回复过帖子
            $is_post = false;
            if(IS_LOGIN){
                $is_post = $Thread->is_user_post(NOW_UID,$tid);

                //管理员 直接显示 或者版主 
                if(NOW_GID == C("ADMIN_GROUP") || is_forumg($this->_forum,NOW_UID,$thread_data['fid']))
                    $is_post = true;
            }
            //{hook a_thread_empty_66}


            
            foreach ($Filelist as $key => &$v) {

                //获取附件信息
                $File_Data = $File->read($v['fileid']);
                if(empty($File_Data)){
                    unset($Filelist[$key]);
                    continue;
                }
                $v['show'] = true;
                if($v['hide']){//隐藏附件
                    if(!$is_post) //如果用户没有回复过
                        $v['show'] = false;

                } 
                $v['size'] = $File_Data['filesize'];
                $v['name'] = $File_Data['filename'];

            }
            //{hook a_thread_empty_77}
            $this->v("filelist",$Filelist);
            //附件处理结束

            //增加主题点击数
            $Thread->update_int($tid,'views');

            $count = $thread_data['posts'];
    		$count = (!$count)?1:$count;
    		$page_count = ($count % $this->conf['postlist'] != 0)?(intval($count/$this->conf['postlist'])+1) : intval($count/$this->conf['postlist']);
            //{hook a_thread_empty_v}
            $this->v("title",$thread_data['title']);
            $this->v("post_data",$PostData);
            $this->v("pageid",$pageid);
            $this->v("page_count",$page_count);
            $this->v("thread_data",$thread_data);
            $this->v("PostList",$PostList);
            $this->display('thread_index');
        }elseif(IS_POST){
            //{hook a_thread_empty_7}
        }

    }
    //子评论页面
    public function post(){
        if(IS_POST && IS_AJAX){
            $pid = intval(X('post.pid'));
            $pageid=intval(X('post.pageid')) or $pageid=1;
            $sort = intval(X('post.sort'));
            if($sort !=0 && $sort !=1)
                $sort=0;
            $ORDER = ['id'=>'ASC'];
            if($sort)
                $ORDER = ['id'=>'DESC'];


            $Post = M('Post');
            if(!$Post->is_pid($pid))
                $this->json(['error'=>false,'info'=>'不存在该评论！']);

            $Post_post = M('Post_post');
            $User = M('User');

            $data = $Post_post->get_list($pid,$pageid,BBSCONF('post_post_show_size'),$ORDER);
            $User->auto_add_user($data);
            foreach ($data as $k => &$v) {
                $v['avatar']=$this->avatar($v['user']);
                $v['atime_str'] = humandate($v['atime']);
            }


            $this->json(['error'=>true,'info'=>$data]);

        }
        $this->v("title",'回复主题');

        $pid=intval(isset($_GET['HY_URL'][2]) ? $_GET['HY_URL'][2] : 1) or $pid=1;
        $pageid=intval(isset($_GET['HY_URL'][3]) ? $_GET['HY_URL'][3] : 1) or $pageid=1;

        $order = X('get.order');
        
        $ORDER = ['id'=>'ASC'];
        if($order == 'desc')
            $ORDER = ['id'=>'DESC'];
            

        
        $Post = M('Post');
        $post_data = $Post->read($pid);
        if(empty($post_data))
            return $this->message('不存在该评论');
        $User = M('User');
        $post_data['user'] = $User->uid_to_user($post_data['uid']);
        $post_data['avatar'] = $this->avatar($post_data['user']);

        
        $this->v('pageid',$pageid);
        $Post_post = M('Post_post');
        $Show_mun = intval(BBSCONF('post_post_show_size'));
        $post_post_data=$Post_post->get_list($pid,$pageid,$Show_mun,$ORDER);
        $count = $post_data['posts'];//$Post_post->count(array('pid'=>$pid));

        if(empty($post_post_data))
            $post_post_data = array();

        $User->auto_add_user($post_post_data);
        foreach ($post_post_data as &$v) {
            $v['avatar']=$this->avatar($v['user']);
            $v['atime_str'] = humandate($v['atime']);
        }

        $tid = $post_data['tid'];
        //获取文章数据
        $thread_data = $this->CacheObj->get('thread_data_'.$tid);
        if(empty($thread_data) || DEBUG){
            //{hook a_thread_empty_cache_1}
            $thread_data = M('Thread')->read($tid);
            if(empty($thread_data))
                return $this->message("不存在该主题");
            //获取文章作者用户名以及头像
            $thread_data['user']=$User->uid_to_user($thread_data['uid']);
            $thread_data['avatar']=$this->avatar($thread_data['user']);
            $this->CacheObj->set('thread_data_'.$tid,$thread_data);
            //{hook a_thread_empty_cache_2}
        }
        $page_count = ($post_data['posts'] % $Show_mun != 0)?(intval($post_data['posts']/$Show_mun)+1) : intval($post_data['posts']/$Show_mun);

        $this->v('page_count',$page_count);
        $this->v('thread_data',$thread_data);
        $this->v('post_data',$post_data);
        $this->v('count',$count);
        $this->v('post_post_data',$post_post_data);


        $this->display('thread_post');
    }
    //删除主题，  不是删除评论！
    public function del(){
        //{hook a_thread_del_1}
        if(!IS_LOGIN)
            $this->json(array('error'=>false,'info'=>'请登录'));

        //用户组权限判断 当前用户组是否允许删除主题
        $UsergroupLib = L("Usergroup");
		if(!$UsergroupLib->read(NOW_GID,'del',$this->_usergroup))
			return $this->json(array('error'=>false,'info'=>'你当前所在用户组无法删除主题'));

        //{hook a_thread_del_3}
        $tid = intval(X("post.id"));
        $Thread = M("Thread");

        //取出该主题ID的数据
        $t_data = $Thread->read($tid);
        if(empty($t_data))
            return $this->json(array('error'=>false,'info'=>'该文章无数据'));

        //版主
        $arr = explode(",",$this->_forum[$t_data['fid']]['forumg']);

        //{hook a_thread_del_4}
        //用户组不是 管理员 &&  用户不是文章作者 && 不是版主
        if(
            (NOW_GID != C("ADMIN_GROUP")) &&
            (NOW_UID != $t_data['uid']) &&
            
            !is_forumg($this->_forum,NOW_UID,$t_data['fid'])
        )
            return $this->json(array('error'=>false,'info'=>'你没有权限操作这个主题'));
        //{hook a_thread_del_55}
        //删除主题数据
        $Thread->del($tid);
        //删除属于该主题的消息
        S("Mess")->delete(array('tid'=>$tid));

        //删除主题下所有评论
        //if($t_data['posts']){ //存在评论
            $Post = M('Post');
            //删除当前主题的所有评论
            $Post->del_thread_all_post($tid);

        //}
        //帖子作者-1
        M("User")->update_int($t_data['uid'],'threads','-');
        //更新缓存
        $this->_forum[$t_data['fid']]['posts']--;
        $this->CacheObj->forum = $this->_forum;
        $this->_count['thread']--;
        $this->CacheObj->bbs_count = $this->_count;

        $this->CacheObj->rm('thread_data_'.$tid);

        if($t_data['posts'] != 0){
            $count = intval(($t_data['posts'] /  $this->conf['postlist']) + 1)+1;
            for ($i=0; $i < $count; $i++) {
                $this->CacheObj->rm("post_list_{$tid}_desc_{$i}");
                $this->CacheObj->rm("post_list_{$tid}__{$i}");
            }
        }
        
        if($t_data['top']==1) //如果是板块置顶帖子，清理该板块置顶帖子缓存
            $this->CacheObj->rm("forum_top_id_".$t_data['fid']);
        elseif($t_data['top']==2)
            $this->CacheObj->rm("top_data_2");

        if(NOW_UID != $t_data['uid']){
            M("Chat")->sys_send(
                $t_data['uid'],
                '你的帖子 ['.$t_data['title'].'] 被 '.NOW_USER.' 删除'
            );
        }

        //{hook a_thread_del_5}
        return $this->json(array('error'=>true,'info'=>'删除成功'));
    }
    //置顶主题
    public function top(){
        //{hook a_thread_top_1}
        if(!IS_LOGIN)
            return $this->json(array('error'=>false,'info'=>'请登录'));
        //{hook a_thread_top_2}


        $tid = intval(X("post.id"));
        $Thread = M("Thread");
        $data = $Thread->read($tid);
        if(empty($data))
            return $this->json(array('error'=>false,'info'=>'没有该文章'));
        //{hook a_thread_top_33}
        //版主权限
        $arr = explode(",",$this->_forum[$data['fid']]['forumg']);
        if(
            NOW_GID != C("ADMIN_GROUP") &&
            
            !is_forumg($this->_forum,NOW_UID,$data['fid'])
        )
            return $this->json(array('error'=>false,'info'=>'没有权限'));
        //{hook a_thread_top_3}

        $type = X("post.type");
        $top = X("post.top"); //1 = 板块置顶 2 = 全站置顶
        if($top < 0 || $top > 2){
            return $this->json(array('error'=>false,'info'=>'参数出错'));
        }
        if($top == 2){
            if(NOW_GID != C("ADMIN_GROUP"))
                return $this->json(array('error'=>false,'info'=>'你没有权限全站置顶'));
        }
        //{hook a_thread_top_4}
        $Thread->update([
            'top'=>($type=='on') ? $top : 0
        ],[
            'tid'=>$tid
        ]);
        if(NOW_UID != $data['uid']){
            M("Chat")->sys_send(
                $data['uid'],
                '你的帖子 <a href="'.HYBBS_URLA('thread',$data['tid']).'" target="_blank">['.$data['title'].']</a> 被 '.NOW_USER.' '.(($type=='on')? (($top==2)?'全站置顶':'板块置顶'):'取消置顶')
            );
        }
        $this->CacheObj->rm("top_data_2");
        $this->CacheObj->rm('thread_data_'.$tid);
        $this->CacheObj->rm('post_data_'.$tid);
        
        //{hook a_thread_top_5}
        return $this->json(array('error'=>true,'info'=>'置顶成功'));


    }
    //锁帖
    public function set_state(){
        if(IS_POST){
            $tid = intval(X("post.id"));
            $state = X("post.state");
            if($state == 1)
                $state = 0;
            else
                $state = 1;
            if(empty($tid))
                return $this->json(array('error'=>false,'info'=>'参数不正常!'));
            $Thread = M("Thread");

            $thread_data = $Thread->read($tid);
            if(empty($thread_data))
                return $this->json(array('error'=>false,'info'=>'主题不存在!'));
            if($thread_data['uid'] != NOW_UID && NOW_GID != C("ADMIN_GROUP") && !is_forumg($this->_forum,NOW_UID,$thread_data['fid']))
                return $this->json(array('error'=>false,'info'=>'你没有权限这样做!'));
            $Thread->update(['state'=>$state],['tid'=>$tid]);
            $this->CacheObj->rm('thread_data_'.$tid);
            return $this->json(array('error'=>true,'info'=>'操作成功!'));
        }
    }
    //{hook a_thread_fun}

}
