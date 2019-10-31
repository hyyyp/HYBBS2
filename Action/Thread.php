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
                $thread_data['avatar']=$this->avatar($thread_data['uid']);
                $this->CacheObj->set('thread_data_'.$tid,$thread_data);
                //{hook a_thread_empty_cache_2}
            }

            //权限判断 
            if(!L("Forum")->is_comp($thread_data['fid'],NOW_GID,'vthread',$this->_forum[$thread_data['fid']]['json']))
                return $this->message("你没有权限访问这个帖子");

            //{hook a_thread_empty_3}

            //添加网站描述
            $this->conf['description'] = filter_html($thread_data['summary']);
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
                        $v['avatar']=$this->avatar($v['uid']);
                        $this->CacheObj->set('post_data_'.$v['pid'],$v);
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
            $PostData = M('Data')->get_post_data($thread_data['pid']);
            if(empty($PostData))
                return $this->message("文章内容没有找到");
                

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
            //if(NOW_UID != $thread_data['uid'])
                $Thread->update_int($tid,'views');

            //判断用户是否收藏
            $thread_data['star']=false;
            if(IS_LOGIN){
                $Thread_star = S('Thread_star');
                $thread_data['star']=$Thread_star->has(['AND'=>['uid'=>NOW_UID,'tid'=>$tid]]);
            }


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
                $v['avatar']=$this->avatar($v['uid']);
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
        $post_data['avatar'] = $this->avatar($post_data['uid']);

        
        $this->v('pageid',$pageid);
        $Post_post = M('Post_post');
        $Show_mun = intval(BBSCONF('post_post_show_size'));
        $post_post_data=$Post_post->get_list($pid,$pageid,$Show_mun,$ORDER);
        $count = $post_data['posts'];//$Post_post->count(array('pid'=>$pid));

        if(empty($post_post_data))
            $post_post_data = array();

        $User->auto_add_user($post_post_data);
        foreach ($post_post_data as &$v) {
            $v['avatar']=$this->avatar($v['uid']);
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
            $thread_data['avatar']=$this->avatar($thread_data['uid']);
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
        $thread_data = $Thread->read($tid);
        if(empty($thread_data))
            return $this->json(array('error'=>false,'info'=>'该文章无数据'));

        //版主
        $arr = explode(",",$this->_forum[$thread_data['fid']]['forumg']);

        //{hook a_thread_del_4}
        //用户组不是 管理员 &&  用户不是文章作者 && 不是版主
        if(
            (NOW_GID != C("ADMIN_GROUP")) &&
            (NOW_UID != $thread_data['uid']) &&
            
            !is_forumg($this->_forum,NOW_UID,$thread_data['fid'])
        )
            return $this->json(array('error'=>false,'info'=>'你没有权限操作这个主题'));
        //{hook a_thread_del_55}
        //删除主题数据
        $Thread->del($tid);
        //删除属于该主题的消息
        S("Mess")->delete(array('tid'=>$tid));

        $Post = M('Post');
        //删除当前主题的所有评论
        $Post->del_thread_all_post($tid);

        //帖子作者-1
        $User = M('User');
        $User->update_int($thread_data['uid'],'threads','-');

        //{hook a_thread_del_551}
        //删除附件
        $File = M("File");
        $Fileinfo = S("Fileinfo");
        $Filegold = S('Filegold');

        $FileinfoList = $Fileinfo->select('*',['tid'=>$tid]);
        if(empty($FileinfoList)) $FileinfoList=[];
        //{hook a_thread_del_552}
        foreach($FileinfoList as $v){
            //删除附件信息
            $Fileinfo->delete(['fileid'=>$v['fileid']]);
            //删除附件购买记录
            $Filegold->delete(['fileid'=>$v['fileid']]);
            $FileData = $File->read($v['fileid'],['uid','md5name','filesize']);
            if(!empty($FileData)){
                //删除数据记录
                $File->delete(['id'=>$v['fileid']]);

                //更新用户上传字节
                $User->update([
                    'file_size[-]'=>$FileData['filesize']
                ],[
                    'uid'=>$FileData['uid']
                ]);
                //{hook a_thread_del_553}
                //文件路劲
                $FilePath = INDEX_PATH . 'upload/userfile/' . $FileData['uid'] . '/' . $FileData['md5name'];
                if(is_file($FilePath)){
                    unlink($FilePath);
                }
                //删除附件 兼容新版本
                $FilePath = INDEX_PATH . GetStorageThreadFileDir($tid,false) . $FileData['md5name'];
                if(is_file($FilePath)){
                    unlink($FilePath);
                }
                //{hook a_thread_del_554}

            }
        }
        //{hook a_thread_del_555}
        //删除图片
        $StorageThreadDir = GetStorageThreadDir($tid);
        deldir(INDEX_PATH . $StorageThreadDir,false,true);
        S('File')->delete(['tid'=>$tid]);
        S('Vote_thread')    ->delete(['tid'=>$tid]);
        S('Threadgold')     ->delete(['tid'=>$tid]);
        S('Post_post')      ->delete(['tid'=>$tid]);


        //{hook a_thread_del_556}


        //更新缓存
        $this->_forum[$thread_data['fid']]['posts']--;
        $this->CacheObj->forum = $this->_forum;
        $this->_count['thread']--;
        $this->CacheObj->bbs_count = $this->_count;

        //删除缓存
        $this->CacheObj->rm('thread_data_'.$tid);
        $this->CacheObj->rm('post_data_'.$thread_data['pid']);
        //{hook a_thread_del_557}
        //删除评论列表缓存
        if($thread_data['posts'] != 0){
            $count = intval(($thread_data['posts'] /  $this->conf['postlist']) + 1)+1;
            for ($i=0; $i < $count; $i++) {
                $this->CacheObj->rm("post_list_{$tid}_desc_{$i}");
                $this->CacheObj->rm("post_list_{$tid}__{$i}");
            }
            //删除帖子独立缓存
            $pid_list = $Post->select('pid',['tid'=>$tid]);
            if(empty($pid_list)) $pid_list = [];
            foreach ($pid_list as $key => $v) {
                $this->CacheObj->rm('post_data_'.$v);
            }
        }
        //{hook a_thread_del_558}
        //置顶缓存
        if($thread_data['top']==1) //如果是板块置顶帖子，清理该板块置顶帖子缓存
            $this->CacheObj->rm("forum_top_id_".$thread_data['fid']);
        elseif($thread_data['top']==2)
            $this->CacheObj->rm("top_data_2");

        //{hook a_thread_del_559}
        //删除主题 消息通知
        if(NOW_UID != $thread_data['uid']){
            M("Chat")->sys_send(
                $thread_data['uid'],
                '你的帖子 ['.$thread_data['title'].'] 被 '.NOW_USER.' 删除'
            );
        }

        //{hook a_thread_del_5}
        return $this->json(array('error'=>true,'info'=>'删除成功'));
    }
    //置顶主题
    public function top(){
        //{hook a_thread_top_1}
        if(!IS_LOGIN)
            return $this->json(array('error'=>false,'info'=>'请登录再继续操作！'));
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
        $this->CacheObj->rm('post_data_'.$data['pid']);
        
        //{hook a_thread_top_5}
        return $this->json(array('error'=>true,'info'=>'置顶成功'));


    }
    //锁帖
    public function set_state(){
        if(IS_POST){
            if(!IS_LOGIN)
                $this->json(array('error'=>false,'info'=>'请登录再继续操作！'));

            $tid = intval(X("post.id"));
            $state = X("post.state");
            if($state == 1)
                $state = 0;
            else
                $state = 1;
            if(empty($tid))
                $this->json(array('error'=>false,'info'=>'参数不正常!'));
            $Thread = M("Thread");

            $thread_data = $Thread->read($tid);
            if(empty($thread_data))
                $this->json(array('error'=>false,'info'=>'主题不存在!'));
            if($thread_data['uid'] != NOW_UID && NOW_GID != C("ADMIN_GROUP") && !is_forumg($this->_forum,NOW_UID,$thread_data['fid']))
                $this->json(array('error'=>false,'info'=>'你没有权限这样做!'));
            $Thread->update(['state'=>$state],['tid'=>$tid]);
            $this->CacheObj->rm('thread_data_'.$tid);
            $this->json(array('error'=>true,'info'=>'操作成功!'));
        }
    }
    //帖子加精华
    public function digest(){
        if (IS_POST){
            if(!IS_LOGIN)
                $this->json(array('error'=>false,'info'=>'请登录再继续操作！'));

            $tid = intval(X("post.id"));
            $state = intval(X("post.state"));
            if (!in_array($state,[0,1]))
                $this->json(array('error'=>false,'info'=>'参数错误!'));
            if(empty($tid))
                $this->json(array('error'=>false,'info'=>'参数错误!'));
            $Thread = M("Thread");
            $thread_data = $Thread->read($tid);
            if(empty($thread_data))
                $this->json(array('error'=>false,'info'=>'主题不存在!'));
            if(NOW_GID != C("ADMIN_GROUP") && !is_forumg($this->_forum,NOW_UID,$thread_data['fid']))
                $this->json(array('error'=>false,'info'=>'你没有权限这样做!'));
            $Thread->update(['digest'=>$state],['tid'=>$tid]);
            $User = M('User');
            $gold = $state?$this->conf['gold_digest']:-$this->conf['gold_digest'];
            $credits = $state?$this->conf['credits_digest']:-$this->conf['credits_digest'];
            //用户增加 金钱
            $User->update_int(NOW_UID, 'gold', '+', $gold);
            //用户增加 积分
            $User->update_int(NOW_UID, 'credits', '+', $credits);
            if($this->conf['gold_digest'] != 0 || $this->conf['credits_digest'] != 0){
                S("Log")->insert(array(
                    'uid'=>$thread_data['uid'],
                    'gold'=>$gold,
                    'credits'=>$credits,
                    'content'=>$state?'帖子加精华 文章ID['.$tid.']':'帖子取消精华 文章ID['.$tid.']',
                    'atime'=>NOW_TIME
                ));
            }
            M("Chat")->sys_send(NOW_UID,'您的帖子 <a href="'. HYBBS_URLA('thread',$thread_data['tid']).'" target="_blank">['.$thread_data['title'].']</a>被管理员'.($state?'加精':'取消加精').($state?'获得':'扣除').'金钱:'.$gold.',积分:'.$credits);
            //{hook a_thread_digest_1}
            $this->CacheObj->rm('thread_data_'.$tid);
            $this->json(array('error'=>true,'info'=>'操作成功!'));
        }
    }
    public function star(){
        if(IS_POST){
            if(!IS_LOGIN)
                $this->json(array('error'=>false,'info'=>'请登录再继续操作！'));

            $tid = intval(X("post.tid"));
            if(empty($tid))
                $this->json(array('error'=>false,'info'=>'参数错误!'));

            $Thread = M("Thread");
            $thread_data = $Thread->read($tid);
            if(empty($thread_data))
                $this->json(array('error'=>false,'info'=>'主题不存在!'));

            $Thread_star = S('Thread_star');
            if($Thread_star->has(['AND'=>['uid'=>NOW_UID,'tid'=>$tid]])){
                $Thread_star->delete(['AND'=>['uid'=>NOW_UID,'tid'=>$tid]]);
                $this->json(array('error'=>true,'info'=>fale));
            }else{
                $Thread_star->insert(['uid'=>NOW_UID,'tid'=>$tid,'atime'=>NOW_TIME]);
                $this->json(array('error'=>true,'info'=>true));    
            }
            
        }
    }
    //{hook a_thread_fun}

}
