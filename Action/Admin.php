<?php
namespace Action;
use HY\Action;
!defined('HY_PATH') && exit('HY_PATH not defined.');
class Admin extends HYBBS {
    public $menu_action =array();
    public function __construct(){
        parent::__construct();
        //{hook a_admin_init}
        //模板分组 admin 文件夹
        $this->view = 'admin';
        define('APP_WWW', 'http://app.hyphp.cn/');
        define("APP_KEY", $this->conf['key']);

        if(!IS_LOGIN){
            header('Location: '.HYBBS_URLA('user','login'));
            //exit('请登录前台!');
            exit;
        }

        if(NOW_GID != C("ADMIN_GROUP"))
            exit('你不是管理员!');
        session('[start]');
        $md5 = session('admin');

        //echo $md5.'|';
        if(empty($md5)){
            $this->login();
            exit();
        }
        if(IS_POST){ //过滤CSRF POST提交
            $url1 = X("server.HTTP_REFERER");
            $reg = '/\/\/([^\/]+)/i';  
            preg_match($reg, $url1,$res1);
            preg_match($reg, WWW,$res2);
            if(!isset($res1[1]) || !isset($res2[1]))
                return $this->out();
            if($res1[1] != $res2[1])
                return $this->out();
        }
        
        

        $this->menu_action = array(
            'index'=>'',
            'forum'=>'',
            'user'=>'',
            'thread'=>'',
            'view'=>'',
            'op'=>'',
            'code'=>''
        );
        $this->v("menu_action",$this->menu_action);


    }


    public function index(){
        //{hook a_admin_index_v}
        if(IS_POST){
            $one1 = X("post.one1"); //文件缓存
            $one2 = X("post.one2"); // 板块数组缓存
            $one3 = X("post.one3") ? true : false; //数据缓存
            $one4 = X("post.one4");
            $lang = X("post.lang"); //多语言文件缓存
            if(!empty($lang)){
                deldir(TMP_PATH.'/Lang');
            }

            if($one1){
                del_cache_file($this->conf);
            }
            if($one2){
                $Forum = S("Forum");
                $forum_data = $Forum->select("*");
                $Thread = S("Thread");
                $Post = S("Post");
                foreach ($forum_data as $v) {
                    $threads = $Thread->count(array('fid'=>$v['id']));
                    $posts = $Post->count(array('fid'=>$v['id']));
                    $Forum->update(array('threads'=>$threads,'posts'=>$posts),array(
                        'id'=>$v['id']));
                }
            }
            if($one3){
                del_cache_data($this->conf);
            }
            if($one4){
                if(is_file(TMP_PATH.'log.php'))
                    unlink(TMP_PATH.'log.php');
            }
            if(IS_AJAX)
                $this->json(['error'=>true,'info'=>'Success']);
            header('Location: '. HYBBS_URLA('admin'));
            exit;
        }

        $this->display('index');

    }
    public function login(){
        //{hook a_admin_index_1}
        if(NOW_GID != C("ADMIN_GROUP"))
            exit('你的账号不属于管理员!');
        if(IS_GET){
            //{hook a_admin_login_2}
            $this->display("login");
        }
        elseif(IS_POST){
            //{hook a_admin_login_3}
            $pass = X("post.pass");

            if(L("User")->md5_md5($pass, $this->_user['salt']) == $this->_user['pass']){


                session('admin','admin');

                header('Location: '. HYBBS_URLA('admin'));
                exit;
            }
            if(IS_AJAX)
                $this->json(['error'=>false,'info'=>'请重新登陆后台','data'=>'请重新登陆后台']);
            else
                echo '密码错误';
        }
    }
    public function out(){
        //{hook a_admin_out_v}
        session('[destroy]');
        header('Location: '. HYBBS_URLA('admin'));
        exit;

    }
    public function forum_group(){
        $Forum = S("Forum");
        $Forum_group = S("Forum_group");
        if(IS_POST){
            $gn = X('post.gn');
            if($gn == 'add'){ //添加大分组
                $fg_name = X("post.fg_name");
                if(empty($fg_name))
                    return $this->mess("名称无法设置为空.");
                if($Forum_group->insert(array('name'=>$fg_name)) === false)
                    return $this->mess("ID被占用 添加失败.");
                header('Location: '.HYBBS_URLA('admin','forum_group'));
                exit;
            }
            else if($gn == 'edit'){ //编辑大分组
                $fgid = X("post.fgid");

                $edit_id = X("post.edit_id");
                $edit_name = X("post.edit_name");

                if($Forum_group->has(array('id'=>$fgid))){
                    $Forum_group->update(array('id'=>$edit_id,'name'=>$edit_name),array('id'=>$fgid));
                }
                header('Location: '.HYBBS_URLA('admin','forum_group'));
                exit;
            }else if($gn == 'move'){ //移动分类到其他大分组
                $fid = X("post.fid");
                $move_fg = X("post.move_fg");
                $Forum->update(array('fgid'=>$move_fg),array('id'=>$fid));
                $this->CacheObj->forum = NULL;
                header('Location: '.HYBBS_URLA('admin','forum_group'));
                exit;
            }else if($gn == 'del'){ //删除大分组
                $id = X("post.id");
                $Forum_group->delete(['id'=>$id]);
                return $this->json(['error'=>true,'info'=>'删除成功']);
            }
            return $this->mess("缺少参数.");
            
        }

        $data = $Forum_group->select('*');
        $forum_data = $Forum->select('*');

        $this->v("data",$data);
        $this->v("forum_data",$forum_data);
        $this->display("forum_group");
    }
    public function forum(){
        //{hook a_admin_forum_1}
        $Forum = M("Forum");
        if(IS_POST){
            $gn         = X("post.gn");
            $id         = intval(X("post.id"));
            $name       = X("post.name");
            $name2      = X("post.name2");
            $color      = X("post.color");
            $background = X("post.background");
            $html       = X("post.html");
            $fid        = intval(X("post.fid"));
            //{hook a_admin_forum_2}
            if(empty($gn))
                return $this->mess("参数不完整");
            //删除缓存
            $this->CacheObj->rm('forum');
            if($gn == 'add'){ //添加分类
                if($Forum->has(array('id'=>$id)))
                    return $this->mess("该分类ID已存在");
                $Forum->insert(array(
                    'id'        => $id,
                    "name"      => $name,
                    "name2"     => $name2,
                    'fid'       => $fid,
                    'color'     => $color,
                    'background'=> $background,
                    'html'      => $html
                    )
                );
                return $this->mess("添加成功");
            }elseif($gn == 'edit'){ //修改分类
                $iid = intval(X("post.iid")); //修改的分类ID
                if($iid < 0 )
                    return $this->mess("参数不完整 Error = 22!");

                $data = $Forum->read($iid);

                if($id != $iid){ //修改ID
                    //帖子分类移动
                    S("Post")->update(array('fid'=>$id),array('fid'=>$iid));
                    S("Thread")->update(array('fid'=>$id),array('fid'=>$iid));
                    $Forum->update(array('fid'=>$id),array('fid'=>$iid));
                    
                }

                // if($fid != -1){ //父分类修改
                //     $Forum->update(array('zid'=>1),array('id'=>$fid));// 存在子分类
                // }else{ //$fid == -1
                //     $tmp_fid = S("Forum")->find("fid",array('id'=>$iid));
                //     echo $tmp_fid;
                //     if(!$Forum->count(array('fid'=>$tmp_fid))) //如果没有分类继承该主分类 设置为 无
                //         $Forum->update(array('zid'=>0),array('id'=>$tmp_fid));

                // }
                // 
                $Forum->update(array(
                    'id'=>$id,
                    'name'=>$name,
                    "name2"=>$name2,
                    'fid'=>$fid,
                    'color'     =>$color,
                    'background'=>$background,
                    'html'  =>  $html
                ),array('id'=>$iid));

                return $this->mess("修改成功");
            }elseif($gn == 'del'){ //删除分类
                
                S("Thread")->delete(array('fid'=>$id));
                S("Post")->delete(array('fid'=>$id));
                $Forum->delete(array('id'=>$id));
                
                return $this->json(array('error'=>true,"info"=>'删除成功'));
            }else if($gn == 'move'){ //合并板块
                $move_f1 = intval(X("post.move_f1"));
                $move_f2 = intval(X("post.move_f2"));
                $move_check = X("post.move_check");

                if($move_check != 'on')
                    return $this->mess('请勾选确认操作');
                if($move_f1 == $move_f2)
                    return $this->mess('别闹');

                S("Thread")->update(array('fid'=>$move_f2),array('fid'=>$move_f1));
                S("Post")->update(array('fid'=>$move_f2),array('fid'=>$move_f1));
                $Forum = S('Forum');
                $Forum->update(array('threads'=>0,'posts'=>0),array('id'=>$move_f1));
                $Forum->update(array('threads'=>S("Thread")->count(array('fid'=>$move_f2)),'posts'=>S("Post")->count(array('fid'=>$move_f2))),array('id'=>$move_f2));
                return $this->mess('移动完成');


            }
            return $this->mess("参数不完整 Error = 2");
        }else{
            //{hook a_admin_forum_3}
            $pageid=intval(X('get.pageid')) or $pageid=1;

            $data1 = $Forum->select("*");
            $data = $Forum->select("*",array(
                'ORDER'=>['id'=>'DESC'],
                "LIMIT" => array(($pageid-1) * $this->conf['adminforum'], $this->conf['adminforum'])
            ));
            $count = $Forum->count();
            $count = (!$count)?1:$count;
            $page_count = ($count % $this->conf['adminforum'] != 0)?(intval($count/$this->conf['adminforum'])+1) : intval($count/$this->conf['adminforum']);

            //{hook a_admin_forum_v}
            $this->v("pageid",$pageid);
            $this->v("page_count",$page_count);
            $this->v("data",$data);
            $this->v("data1",$data1);
            $this->display("forum");
        }



    }
    //用户管理
    public function user(){
        $User = M('User');
        //{hook a_admin_user_1}
        if(IS_POST){
            $gn = X("post.gn");
            if($gn=='add'){ //添加用户

                $user = X("post.user");
                $pass = X("post.pass");
                $email = X("post.email");
                $gid = X("post.group");

                //{hook a_admin_user_2}
                if($User->is_user($user))
                    return $this->mess("账号已经存在 Error = 1!");
                if($User->is_email($email))
                    return $this->mess("邮箱已经存在 Error = 2!");

                $User->add_user($user,$pass,$email,$gid);
                //{hook a_admin_user_3}
                return $this->mess("添加账号成功");

            }elseif($gn=='edit'){ //修改用户
                $uid = intval(X("post.id"));
                $user = X("post.user");
                $pass = X("post.pass");
                $gid = X("post.group");
                $email = X("post.email");
                $gold = X("post.gold");
                $credits = X("post.credits");

                //{hook a_admin_user_4}
                $data = $User->read($uid);

                if($data['user'] != $user){
                    if($User->is_user($user))
                        return $this->mess("账号已经存在 Error =3!");
                }

                if($data['email'] != $email){
                    if($User->is_email($email))
                        return $this->mess("邮箱已经存在 Error = 4!");
                }
                $xiu = array(
                    'user'=>$user,
                    'email'=>$email,
                    'gid'=>$gid,
                    'gold'=>$gold,
                    'credits'=>$credits
                );
                if(!empty($pass)){
                    $xiu['pass'] = L("User")->md5_md5($pass,$data['salt']);
                }
                $User->update($xiu,['uid'=>$uid]);
                //用户组升级检测
                M('Usergroup')->check_up($uid);
                //{hook a_admin_user_5}
                return $this->mess("修改成功");

            }elseif($gn == 'del'){ //删除用户
                //{hook a_admin_user_6}
                $uid = intval(X("post.id"));
                $User->delete(['uid'=>$uid]);

                $Thread = S('Thread');

                //获取主题TID列表
                $tid_list = $Thread->select('tid',['uid'=>$uid]);
                //删除帖子内的所有附件
                if(!empty($tid_list)){
                    foreach ($tid_list as $tid) {
                        $StorageThreadDir = GetStorageThreadDir($tid,false);
                        deldir(INDEX_PATH . $StorageThreadDir,false,true);
                    }
                }

                $Post = S('Post');
                //获取评论PID列表
                $pid_list = $Post->select(['pid','tid'],['uid'=>$uid]);
                if(!empty($pid_list)){
                    foreach ($pid_list as $v) {
                        $StoragePostDir = GetStoragePostDir($v['tid'],$v['pid'],false);
                        deldir(INDEX_PATH . $StoragePostDir,false,true);
                    }
                }



                $Thread->delete(array('uid'=>$uid));
                $Post->delete(array('uid'=>$uid));
                
                S("Chat")->delete(array('OR'=>array('uid1'=>$uid,'uid2'=>$uid)));
                S("Chat_count")->delete(array('uid'=>$uid));
                S("Chat_pm")->delete(array('OR'=>array('uid1'=>$uid,'uid2'=>$uid)));
                S("File")->delete(array('uid'=>$uid));
                S("Filegold")->delete(array('uid'=>$uid));
                S("Fileinfo")->delete(array('uid'=>$uid));
                S("Friend")->delete(array('OR'=>array('uid1'=>$uid,'uid2'=>$uid)));
                S("Ol")->delete(array('uid'=>$uid));
                S("Threadgold")->delete(array('uid'=>$uid));
                S("Vote_post")->delete(array('uid'=>$uid));
                S("Vote_thread")->delete(array('uid'=>$uid));
                deldir(INDEX_PATH. "upload/userfile/".$uid,false,true);
                
                $path_avatar = INDEX_PATH . 'upload/avatar/' . md5($uid);
                $path_user_avatar = 'upload/avatar/' . md5($uid);
                if(is_file($path_user_avatar.".jpg")){
                    unlink($path_user_avatar.".jpg");
                }
                if(is_file($path_user_avatar."-a.jpg")){
                    unlink($path_user_avatar."-a.jpg");
                }
                if(is_file($path_user_avatar."-b.jpg")){
                    unlink($path_user_avatar."-b.jpg");
                }
                if(is_file($path_user_avatar."-c.jpg")){
                    unlink($path_user_avatar."-c.jpg");
                }

                
                return $this->json(array('error'=>true,'info'=>'删除成功'));
            }elseif($gn == 'del_more'){ //删除勾选用户
                
                $uid = X('post.id');
                if(is_array($uid)){
                    $Thread = S("Thread");
                    $Post = S("Post");
                    $Chat = S("Chat");
                    $Chat_count = S("Chat_count");
                    $Chat_pm = S("Chat_pm");
                    $File = S("File");
                    $Filegold = S("Filegold");
                    $Fileinfo = S("Fileinfo");
                    $Friend = S("Friend");
                    $Ol = S("Online");
                    $Threadgold = S("Threadgold");
                    $Vote_post = S("Vote_post");
                    $Vote_thread = S("Vote_thread");
                    foreach ($uid as $v) {
                        $User->delete(['uid'=>$v]);
                        $tid_list = S("Thread")->select('tid',['uid'=>$v]);
                        if(!empty($tid_list)){
                            foreach ($tid_list as $tid) {
                                $StorageThreadDir = GetStorageThreadDir($tid,false);
                                deldir(INDEX_PATH . $StorageThreadDir,false,true);
                            }
                        }
                        
                        $Thread->delete(array('uid'=>$v));
                        $Post->delete(array('uid'=>$v));
                        $Chat->delete(array('OR'=>array('uid1'=>$v,'uid2'=>$v)));
                        $Chat_count->delete(array('uid'=>$v));
                        $Chat_pm->delete(array('OR'=>array('uid1'=>$v,'uid2'=>$v)));
                        $File->delete(array('uid'=>$v));
                        $Filegold->delete(array('uid'=>$v));
                        $Fileinfo->delete(array('uid'=>$v));
                        $Friend->delete(array('OR'=>array('uid1'=>$v,'uid2'=>$v)));
                        $Ol->delete(array('uid'=>$v));
                        $Threadgold->delete(array('uid'=>$v));
                        $Vote_post->delete(array('uid'=>$v));
                        $Vote_thread->delete(array('uid'=>$v));
                        deldir(INDEX_PATH. "upload/userfile/" . $v,false,true);

                        $path_avatar = INDEX_PATH . 'upload/avatar/' . md5($v);
                        $path_user_avatar = 'upload/avatar/' . md5($v);
                        if(is_file($path_user_avatar.".jpg")){
                            unlink($path_user_avatar.".jpg");
                        }
                        if(is_file($path_user_avatar."-a.jpg")){
                            unlink($path_user_avatar."-a.jpg");
                        }
                        if(is_file($path_user_avatar."-b.jpg")){
                            unlink($path_user_avatar."-b.jpg");
                        }
                        if(is_file($path_user_avatar."-c.jpg")){
                            unlink($path_user_avatar."-c.jpg");
                        }

                    }
                    header('Location: '. X("server.HTTP_REFERER"));
                    return $this->mess("删除完成");
                }
                   
                
                return $this->mess("删除勾选用户 你未勾选确认删除");
                
            }
            return $this->mess("参数错误");
        }

        //{hook a_admin_user_7}
        
        if(!isset($_SERVER['REQUEST_URI']))
            $_SERVER['REQUEST_URI']='';
        $x = explode('?uid',$_SERVER['REQUEST_URI']);
        if(isset($x[1]))
            $x = '?uid' . $x[1];
        else
            $x='';
        
        $this->v('x',$x);
        $pageid = intval(X('get.pageid',1));

        $this->v('pageid',$pageid);
        $len = $this->conf['adminuser'];
        $where = [
            'ORDER'=>['uid'=>'DESC'],
            "LIMIT" => [($pageid-1) * $len, $len]
        ];


        if(X('get.search_submit')){
            $where = [
                'AND'=>[],
                'ORDER'=>['uid'=>'DESC'],
                "LIMIT" => [($pageid-1) * $len, $len]
            ];
            $uid = X('get.uid');
            $user = X('get.user');
            $user_key = X('get.user_key');
            $email = X('get.email');
            $email_key = X('get.email_key');
            $gid = X('get.gid');
            $ban_login = X('get.ban_login');
            $ban_post = X('get.ban_post');
            
            
            

            if(!empty($uid)){
                $where['AND']['uid']=$uid;
            }
            if(!empty($user)){
                $where['AND']['user']=$user;
            }
            if(!empty($user_key) && empty($uid) && empty($user)){
                $where['AND']['user[~]']=$user_key;
            }
            if(!empty($email)){
                $where['AND']['email']=$email;
            }
            if(!empty($email_key) && empty($email)){
                $where['AND']['email[~]']=$email_key;
            }
            if(!empty($gid) && $gid != -1){
                $where['AND']['gid']=$gid;
            }
            if(!empty($ban_login) ){
                $where['AND']['ban_login']=$ban_login-1;
            }
            if(!empty($ban_post) ){
                $where['AND']['ban_post']=$ban_post-1;
            }

            
            
        }
        if(empty($where['AND']))
            unset($where['AND']);
        
        $data = $User->select('*',$where);
        unset($where['ORDER']);
        unset($where['LIMIT']);
        $count = $User->count($where);
        $count = (!$count)?1:$count;
        $page_count = ($count % $len != 0)?(intval($count/$len)+1) : intval($count/$len);

        $this->v("page_count",$page_count);
        $this->v("data",$data);
        $this->display('user');
    }
    //用户组
    public  function usergroup(){
        //{hook a_admin_usergroup_1}
        if(IS_GET){
            //{hook a_admin_usergroup_2}
            $data = S("Usergroup")->select("*");

            foreach ($data as &$v) {
                $v['json']=json_decode($v['json'],true);
                isset($v['json']['thread']) or $v['json']['thread'] = 0;
                isset($v['json']['post']) or $v['json']['post'] = 0;
                isset($v['json']['upload']) or $v['json']['upload'] = 0;
                isset($v['json']['mess']) or $v['json']['mess'] = 0;
                isset($v['json']['del']) or $v['json']['del'] = 0;
                isset($v['json']['del']) or $v['json']['del'] = 0;
                isset($v['json']['down']) or $v['json']['down'] = 0;
                isset($v['json']['uploadfile']) or $v['json']['uploadfile'] = 0;
                isset($v['json']['thide']) or $v['json']['thide'] = 0;
                isset($v['json']['tgold']) or $v['json']['tgold'] = 0;
                isset($v['json']['nogold']) or $v['json']['nogold'] = 0;

                isset($v['json']['uploadvideo']) or $v['json']['uploadvideo'] = 0;
                isset($v['json']['uploadaudio']) or $v['json']['uploadaudio'] = 0;
            }


            //{hook a_admin_usergroup_v}
            $this->v("data",$data);
            $this->display('usergroup');
        }elseif(IS_POST){
            //删除缓存
            $this->CacheObj->rm('usergroup');
            //{hook a_admin_usergroup_3}
            $gn = X("post.gn");
            if($gn == 'add'){ //添加用户组
                //{hook a_admin_usergroup_4}
                S("Usergroup")->insert(array(
                    'gid'=>intval(X("post.id")),
                    'name'=>X("post.name"),
                    'credits'=>X("post.credits"),
                    'credits_max'=>X("post.credits_max"),
                    'space_size'=>X("post.space_size"),
                    'chat_size'=>X("post.chat_size"),
                    'json'=>json_encode(array(
                        'thread'=>1,
                        'post'=>1,
                        'upload'=>1,
                        'mess'=>1,
                        'del'=>1,
                        'down'=>1,
                        'uploadfile'=>1,
                        'hide'=>1,
                        'thide'=>1,
                        'tgold'=>1,
                        'nogold'=>0,
                        'uploadvideo'=>0,
                        'uploadaudio'=>0
                    ))
                ));
                return $this->mess("添加成功");

            }elseif($gn == 'edit'){ //修改用户组
                //{hook a_admin_usergroup_5}
                $font_css = trim(X('post.font_css'));
                $font_css1 ='';
                foreach (explode("\n",$font_css) as $key => $v) {
                    $font_css1.=trim($v)."\n";
                }
                S("Usergroup")->update([
                    'gid'=>intval(X("post.id")),
                    'name'=>X("post.name"),
                    'credits'=>X('post.credits'),
                    'credits_max'=>X('post.credits_max'),
                    'space_size'=>X("post.space_size"),
                    'chat_size'=>X("post.chat_size"),
                    'font_color'=>X('post.font_color'),
                    'font_css'=>trim($font_css1)

                ],[
                    'gid'=>intval(X("post.iid"))
                ]);
                return $this->mess("修改成功");
            }elseif($gn == 'edit_permission'){ //编辑权限
                //{hook a_admin_usergroup_6}
                $gid = intval(X("post.id"));
                $type = X("post.type");
                $b = X("post.b");
                $Usergroup = S("Usergroup");
                $json = $Usergroup->find("json",[
                    'gid'=>$gid,
                ]);
                echo $json;
                if(empty($json))
                    $json='{}';
                    //return $this->json(array('error'=>false,'info'=>'修改失败'));
                $data = json_decode($json,true);

                $data[$type] = $b ? 0 : 1;
                $Usergroup->update(array(
                    'json'=>json_encode($data)
                ),[
                    'gid'=>$gid
                ]);
                return $this->json(array('error'=>true,'info'=>'修改成功'));

                //print_r($data);
            }elseif($gn == 'del'){ //删除用户组
                $gid = intval(X("post.id"));
                $Usergroup = S("Usergroup");
                $Usergroup->delete(['gid'=>$gid]);
                S("User")->update(['gid'=>2],['gid'=>$gid]);
                return $this->json(['error'=>true,'info'=>'删除成功']);

            }
        }
    }
    //文章管理
    public function thread(){
        $Thread = S("Thread");
        //{hook a_admin_thread_1}
        if(IS_POST){
            $gn = X("post.gn");
            if($gn == 'del'){ //删除主题
                $tid_list = X("post.id");
                if(!empty($tid_list)){
                    //{hook a_admin_thread_11}
                    $File       = M("File");
                    $Fileinfo   = S("Fileinfo");
                    $Filegold   = S('Filegold');
                    $User       = M('User');
                    $Post       = S('Post');
                    $Vote_thread= S('Vote_thread');
                    $Threadgold = S('Threadgold');
                    $Post_post  = S('Post_post');

                    foreach ($tid_list as $tid) {
                        //{hook a_admin_thread_12}
                        //删除附件
                        $FileinfoList = $Fileinfo->select('*',['tid'=>$tid]);
                        if(empty($FileinfoList)) $FileinfoList=[];
                        foreach($FileinfoList as $v){
                            //{hook a_admin_thread_13}
                            $Fileinfo->delete(['fileid'=>$v['fileid']]);
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
                                //{hook a_admin_thread_14}
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
                                //{hook a_admin_thread_15}

                            }
                        }
                        //{hook a_admin_thread_16}
                        //删除主题数据
                        $Thread->delete(['tid'=>$tid]);
                        //删除评论数据
                        $Post->delete(['tid'=>$tid]);

                        //删除所有图片
                        $StorageThreadDir = GetStorageThreadDir($tid,false);
                        deldir(INDEX_PATH . $StorageThreadDir,false,true);
                        $File->delete(['tid'=>$tid]);

                        //{hook a_admin_thread_16}
                        $Vote_thread    ->delete(['tid'=>$tid]);
                        $Threadgold     ->delete(['tid'=>$tid]);
                        $Post_post      ->delete(['tid'=>$tid]);
                        //{hook a_admin_thread_17}

                    }
                    
                }
            }
            //{hook a_admin_thread_3}
        }

        if(!isset($_SERVER['REQUEST_URI']))
            $_SERVER['REQUEST_URI']='';
        $x = explode('?uid',$_SERVER['REQUEST_URI']);
        if(isset($x[1]))
            $x = '?uid' . $x[1];
        else
            $x='';
        //var_dump($_SERVER);
        
        $this->v('x',$x);
        $pageid = intval(X('get.pageid',1));

        $this->v('pageid',$pageid);
        $len = $this->conf['adminthread'];
        $where = [
            'ORDER'=>['tid'=>'DESC'],
            "LIMIT" => [($pageid-1) * $len, $len]
        ];
        //{hook a_admin_thread_2}
        

        if(X('get.search_submit')){
            $where = [
                'AND'=>[],
                'ORDER'=>['tid'=>'DESC'],
                "LIMIT" => [($pageid-1) * $len, $len]
            ];
            $uid = X('get.uid');
            $username = X('get.username');
            $tid = X('get.tid');
            $title = X('get.title');
            $fid = X('get.fid');
            $top = X('get.top');
            $state = X('get.state');
            
            //{hook a_admin_thread_4}

            if(!empty($uid)){
                $where['AND']['uid']=$uid;
            }
            if(!empty($username)){
                $where['AND']['uid']=M('User')->user_to_uid($username);
            }
            if(!empty($tid)){
                $where['AND']['tid']=$tid;
            }
            
            if(!empty($title)){
                $where['AND']['title[~]']=$title;
            }
            if($fid != -1 && $fid !==''){
                $where['AND']['fid']=$fid;
            }
            if(!empty($top)){
                $where['AND']['top']=$top;
            }

            if($state != -1 && !empty($state)){
                $where['AND']['state']=$state-1;
            }
            //{hook a_admin_thread_5}
            
        }
        if(empty($where['AND']))
            unset($where['AND']);
        
        //{hook a_admin_thread_6}
        $data = $Thread->select('*',$where);
        unset($where['ORDER']);
        unset($where['LIMIT']);
        $count = $Thread->count($where);
        $count = (!$count)?1:$count;
        $page_count = ($count % $len != 0)?(intval($count/$len)+1) : intval($count/$len);

        //{hook a_admin_thread_v}
        $this->v("page_count",$page_count);
        $this->v('data',$data);
        $this->display('thread');
    

    }
    public function post(){
        //{hook a_admin_post_1}
        $Post = M('Post');
        if(IS_POST){
            $gn = X("post.gn");
            //{hook a_admin_post_2}
            if($gn == 'del'){ //删除评论
                $pid_list = X("post.id");
                if(!empty($pid_list)){
                    $File = S('File');
                    //{hook a_admin_post_4}
                    foreach ($pid_list as $pid) {
                        //{hook a_admin_post_5}
                        $tid = $Post->get_row($pid,'tid');
                        $StoragePostDir = GetStoragePostDir($tid,$pid,false);
                        deldir(INDEX_PATH . $StoragePostDir,false,true);
                        $File->delete(['pid'=>$pid]);
                        //{hook a_admin_post_8}
                    }
                    
                    //{hook a_admin_post_6}
                    $Post             ->delete(['pid'=>$pid_list]);
                    S("Vote_post")    ->delete(['pid'=>$pid_list]);
                    S("Post_post")    ->delete(['pid'=>$pid_list]);
                    //{hook a_admin_post_7}
                }
            }
            //{hook a_admin_post_3}
        }

        if(!isset($_SERVER['REQUEST_URI']))
            $_SERVER['REQUEST_URI']='';
        $x = explode('?uid',$_SERVER['REQUEST_URI']);
        if(isset($x[1]))
            $x = '?uid' . $x[1];
        else
            $x='';
        
        $this->v('x',$x);
        $pageid = intval(X('get.pageid',1));

        $this->v('pageid',$pageid);
        $len = $this->conf['admin_show_post'];
        $where = [
            'ORDER'=>['pid'=>'DESC'],
            "LIMIT" => [($pageid-1) * $len, $len]
        ];
        //{hook a_admin_post_9}


        if(X('get.search_submit')){
            $where = [
                'AND'=>[],
                'ORDER'=>['pid'=>'DESC'],
                "LIMIT" => [($pageid-1) * $len, $len]
            ];
            $uid = X('get.uid');
            $username = X('get.username');
            $pid = X('get.pid');
            $tid = X('get.tid');
            $content = X('get.content');
            $fid = X('get.fid');
            //{hook a_admin_post_10}
            
            

            if(!empty($uid)){
                $where['AND']['uid']=$uid;
            }
            if(!empty($username)){
                $where['AND']['uid']=M('User')->user_to_uid($username);
            }
            if(!empty($pid)){
                $where['AND']['pid']=$pid;
            }
            if(!empty($tid)){
                $where['AND']['tid']=$tid;
            }
            
            if(!empty($content)){
                $where['AND']['content[~]']=$content;
            }
            if($fid != -1 && $fid !==''){
                $where['AND']['fid']=$fid;
            }
            //{hook a_admin_post_11}
            
        }
        if(empty($where['AND']))
            unset($where['AND']);
        
        //{hook a_admin_post_12}
        $data = $Post->select('*',$where);
        unset($where['ORDER']);
        unset($where['LIMIT']);
        $count = $Post->count($where);
        $count = (!$count)?1:$count;
        $page_count = ($count % $len != 0)?(intval($count/$len)+1) : intval($count/$len);
        //{hook a_admin_post_v}
        $this->v("page_count",$page_count);
        $this->v("data",$data);
        $this->display('post');
    }
    public function post_post(){
        $Post_post = S("Post_post");
        if(IS_POST){
            $gn = X("post.gn");

            if($gn == 'del'){
                $id = X("post.id");
                if(!empty($id)){
                    foreach ($id as &$v) {
                        $v=intval($v);
                    }
                    $Post_post    ->delete(['OR'=>['id'=>$id]]);
                }
            }
        }


        if(!isset($_SERVER['REQUEST_URI']))
            $_SERVER['REQUEST_URI']='';
        $x = explode('?uid',$_SERVER['REQUEST_URI']);
        if(isset($x[1]))
            $x = '?uid' . $x[1];
        else
            $x='';
        //var_dump($_SERVER);
        
        $this->v('x',$x);
        $pageid = intval(X('get.pageid',1));

        $this->v('pageid',$pageid);
        $len = $this->conf['admin_show_post'];
        $where = [
            'ORDER'=>['pid'=>'DESC'],
            "LIMIT" => [($pageid-1) * $len, $len]
        ];


        if(X('get.search_submit')){
            $where = [
                'AND'=>[],
                'ORDER'=>['pid'=>'DESC'],
                "LIMIT" => [($pageid-1) * $len, $len]
            ];
            $uid = X('get.uid');
            $username = X('get.username');
            $id = X('get.id');
            $pid = X('get.pid');
            $tid = X('get.tid');
            $content = X('get.content');
            //$fid = X('get.fid');
            //$top = X('get.top');
            //$state = X('get.state');
            
            

            if(!empty($uid)){
                $where['AND']['uid']=$uid;
            }
            if(!empty($username)){
                $where['AND']['uid']=M('User')->user_to_uid($username);
            }
            if(!empty($id)){
                $where['AND']['id']=$id;
            }
            if(!empty($pid)){
                $where['AND']['pid']=$pid;
            }
            if(!empty($tid)){
                $where['AND']['tid']=$tid;
            }
            
            if(!empty($content)){
                $where['AND']['content[~]']=$content;
            }
            /*if($fid != -1 && $fid !==''){
                $where['AND']['fid']=$fid;
            }*/
            /*if(!empty($top)){
                $where['AND']['top']=$top;
            }*/

            /*if($state != -1 && !empty($state)){
                $where['AND']['state']=$state-1;
            }*/
            
        }
        if(empty($where['AND']))
            unset($where['AND']);
        
        $data = $Post_post->select('*',$where);
        unset($where['ORDER']);
        unset($where['LIMIT']);
        $count = $Post_post->count($where);
        $count = (!$count)?1:$count;
        $page_count = ($count % $len != 0)?(intval($count/$len)+1) : intval($count/$len);

        $this->v("page_count",$page_count);
        $this->v("data",$data);
        $this->display('post_post');
    }
    private function mess($a){
        //{hook a_admin_mess_v}
        $this->v('mess',$a);
        $this->display("message");
    }
    public function view(){

        if(IS_POST){ //生成模板目录
            $gn = X('post.gn');
            if($gn == 'create_view'){
                $name = X("post.name");
                $name2= X("post.name2");
                $user = X("post.user");
                $mess = X("post.mess");
                $code = X("post.code");
                if(empty($name) || empty($name2) || empty($mess))
                    return $this->json(array('error'=>false,'info'=>'参数不完整'));

                if(is_dir(VIEW_PATH . $name2))
                    return $this->json(array('error'=>false,'info'=>"英文名已经存在\r\n如果你想覆盖,请手动到目录中删除".$name2));
                create_dir(VIEW_PATH . $name2);
                file_put_contents(VIEW_PATH . $name2 . '/conf.php',"<?php
    return array(
        'name' => '{$name}',
        'user' => '{$user}',
        'mess' => '{$mess}',
        'code' => '{$code}',
        'version' => '1.0',
    );");


                return $this->json(array('error'=>true,'info'=>'建立成功'));
            }else if($gn == 'op'){//提交模板配置
                $json = array();
                $op = X("get.op");
                if(is_file(VIEW_PATH . "/{$op}/inc.php")){
                    $file = file(VIEW_PATH . "/{$op}/inc.php");
                    $json = isset($file[1]) ? json_decode($file[1],true) : array();
                }
                

                foreach ($_POST as $k => $v) {
                    $json[$k] = $v;
                }
                put_tmp_file(VIEW_PATH . "/{$op}/inc.php",json_encode($json));
            }else if($gn == 'apply'){
                //{hook a_admin_view_1}
                $theme = X('post.theme');
                $conf = $this->conf;
                if(!is_dir(VIEW_PATH . $theme))
                    $this->json(array('error'=>false,'info'=>"修改失败,{$theme} :模板不存在"));
                    
                $type = X("post.type");
                file_put_contents(VIEW_PATH . $theme . '/on','');
                if($type =='pc'){
                    
                    $conf['theme']=$theme;
                }else{

                    $conf['wapview']        = 
                    $conf['wapuserview']    =
                    $conf['wapuserview2']   =
                    $conf['wapmessview']    = $theme;
                    
                }

                $conf['title2'] = str_replace(" - Powered by HYBBS",'',$conf['title2']);
                file_put_contents(CONF_PATH . 'conf.php' , "<?php die(); ?>\r\n".json_encode($conf));
                del_cache_file($this->conf);
                $this->json(array('error'=>true,'info'=>"应用成果"));
            }
            
        }

        //显示模板配置
        $op = X("get.op");
        if(!empty($op)){
            $this->display("view_op");
            return;
        }
        
        //{hook a_admin_view_2}
        $ml = array();
        $dh = opendir(VIEW_PATH);
        $qj = array();
        $all_data = array();
        while (($dir = readdir($dh)) !== false) {

            if(!is_dir(VIEW_PATH . $dir))
                continue;

            $conf_path = VIEW_PATH.$dir.'/conf.php';
            if(!is_file($conf_path)){
                continue;
            }


            if($dir!='.'&&$dir!='..'&&is_dir(VIEW_PATH . $dir)){
                $qj[$dir] = include $conf_path;
                isset($qj[$dir]) or $qj[$dir] = array();
                isset($qj[$dir]['name']) or $qj[$dir]['name'] = '空';
                $all_data[] = array('value'=>$dir,'name'=>$qj[$dir]['name']);//iconv('gbk', 'utf-8', $dir);
            }
            
            if($dir=='.'||$dir=='..'||$dir=='install'||$dir=='admin'||$dir=='hy_user'||$dir=='hy_message'||!is_dir(VIEW_PATH . $dir)){
                
                continue;
            }
            
            $ml[$dir]=$dir;
            
            
            isset($qj[$dir]['user']) or $qj[$dir]['user'] = '空';
            isset($qj[$dir]['mess']) or $qj[$dir]['mess'] = '空';
            isset($qj[$dir]['code']) or $qj[$dir]['code'] = '';
            isset($qj[$dir]['version']) or $qj[$dir]['version'] = '1.0';
        }
        $this->v("qj",$qj);
        
        //{hook a_admin_view_v}
        $this->v('data',$ml);
        $this->v('all_data',$all_data);
        $this->display("view");
    }
    public function viewol(){
        //下载
        if(IS_POST){
            $down = X("post.down");
            $gn = X('post.gn');
            if(!empty($down)){

                $name = $down;
                if($gn == 'update'){

                    $inc = get_view_inc($name);

                    if(!deldir(VIEW_PATH . $down,false,true))
                        $this->json(array('error'=>false,'data'=>"无法删除旧模板,请手动删除" . VIEW_PATH . $down ));
                }

                if(is_dir(VIEW_PATH . $down))
                    $this->json(array('error'=>false,'data'=>'模板目录已有相同名称模板,如果你要重新下载,需要手动删除模板'));
                $down_path = TMP_PATH . $down . '.zip';
                if(is_file($down_path))
                    unlink($down_path);
                if(is_file($down_path))
                    $this->json(array('error'=>false,'data'=>'下载模板,权限出现问题,无法删除旧压缩包,请检查目录权限'));
                
                $json = http_get_app(APP_WWW . 'json/get_down_path1',array('name'=>$name));

                if(empty($json))
                    $this->json(array('error'=>false,'data'=>'访问远程服务器失败.'));
                $json = json_decode($json,true);
                if(!$json['error'])
                    $this->json(array('error'=>false,'data'=>$json['data']));
                
                $down = APP_WWW . 'app/' . $name . '/' .$json['data'];
                //下载模板
                http_down( $down_path, $down);
                if(!is_file($down_path))
                    $this->json(array('error'=>true,'data'=>'没有下载到模板压缩包'));
                
                //解压模板
                $zip = L("Zip");
                $un_info = $zip->unzip($down_path, VIEW_PATH);
                if($un_info !== true){
                    $this->json(array('error'=>false,'data'=>'模板解压失败，'.$un_info));
                }


                if(is_dir(VIEW_PATH . $name)){
                    if(is_file(VIEW_PATH . $name . '/on'))
                        unlink(VIEW_PATH . $name . '/on');

                    
                    $inc1 = get_view_inc($name);
                    if(!empty($inc1) && !empty($inc)){

                        foreach ($inc1 as $k => &$v) {
                            if(isset($inc[$k])){
                                if(!empty($inc[$k])){
                                    $v = $inc[$k];
                                }
                            }
                            
                        }


                    }
                    put_tmp_file(VIEW_PATH . "{$name}/inc.php",json_encode($inc1));

                    $this->json(array('error'=>true,'data'=>'下载完成'));
                }
                    
                $this->json(array('error'=>true,'data'=>'模板解压失败'));
            }
        }

        $dh = opendir(VIEW_PATH);
        $ml = array();
        $qj = array();


        while (($dir = readdir($dh)) !== false) {
            $conf_path = VIEW_PATH.$dir.'/conf.php';
            if($dir=='.' || $dir=='..' || !is_dir(VIEW_PATH . $dir)){
                
                continue;
            }
            if(!is_file($conf_path)){
                
                continue;
            }
            $qj[$dir] = include $conf_path;
            isset($qj[$dir]) or $qj[$dir] = array();
            $qj[$dir]['name2'] = $dir;
            isset($qj[$dir]['name']) or $qj[$dir]['name'] = '空';
            isset($qj[$dir]['user']) or $qj[$dir]['user'] = '空';
            isset($qj[$dir]['mess']) or $qj[$dir]['mess'] = '空';
            isset($qj[$dir]['code']) or $qj[$dir]['code'] = '';

            isset($qj[$dir]['version']) or $qj[$dir]['version'] = '1.0';
        }
        $this->v("qj",json_encode($qj));

        //$this->v('data',json_encode($ml));
        $this->display("viewol");
    }
    public function op(){
        //{hook a_admin_op_1}
        if(IS_POST){
            //{hook a_admin_op_2}
            $title      = X("post.title");
            $logo       = X("post.logo");
            $title2     = X("post.title2");
            $keywords   = X("post.keywords");
            $de         = X("post.de");
         

            $gold_thread    = intval(X("post.gold_thread"));
            $gold_post      = intval(X("post.gold_post"));
            $credits_thread = intval(X("post.credits_thread"));
            $credits_post   = intval(X("post.credits_post"));
            $gold_digest = intval(X("post.gold_digest"));
            $credits_digest   = intval(X("post.credits_digest"));
            
            
            $homelist     = intval(X("post.homelist"));
            $forumlist    = intval(X("post.forumlist"));
            $postlist     = intval(X("post.postlist"));

            $send_email_s = intval(X('post.send_email_s'));
            $out_s        = intval(X('post.out_s'));
            $mp3_friend   = X('post.mp3_friend');
            $mp3_system   = X('post.mp3_system');
            $user_have_badword = X('post.user_have_badword');
            $on_edit_user = X('post.on_edit_user');



            $searchlist      = intval(X("post.searchlist"));
            $search_key_size = intval(X('post.search_key_size'));


            $titlesize      = intval(X("post.titlesize"));
            $titlemin       = intval(X("post.titlemin"));
            $summary_size   = intval(X("post.summary_size"));
            $emailhost      = X("post.emailhost");
            $emailuser      = X("post.emailuser");
            $emailpass      = X("post.emailpass");
            $emailport      = intval(X("post.emailport"));
            $emailtitle     = X("post.emailtitle");
            $emailcontent   = X("post.emailcontent");


            $post_image_size    = X("post.post_image_size");
            $uploadimageext     = X("post.uploadimageext");
            $uploadfileext      = X("post.uploadfileext");
            $uploadimagemax     = X("post.uploadimagemax");
            $uploadfilemax      = X("post.uploadfilemax");
            $allow_upload_video = X('post.allow_upload_video');
            $upload_video_ext   = X('post.upload_video_ext');
            $upload_video_size  = X('post.upload_video_size');
            $allow_upload_audio = X('post.allow_upload_audio');
            $upload_audio_ext   = X('post.upload_audio_ext');
            $upload_audio_size  = X('post.upload_audio_size');

            $adminforum         = X("post.adminforum");

        

            $cache_type     = X("post.cache_type");
            $cache_table    = X("post.cache_table");
            $cache_key      = X("post.cache_key");
            $cache_time     = X("post.cache_time");
            $cache_pr       = X("post.cache_pr");
            $cache_ys       = X("post.cache_ys");
            $cache_outtime      = X("post.cache_outtime");
            $cache_redis_ip     = X("post.cache_redis_ip");
            $cache_redis_port   = X("post.cache_redis_port");
            $cache_mem_ip       = X("post.cache_mem_ip");
            $cache_mem_port     = X("post.cache_mem_port");
            $cache_memd_ip      = X("post.cache_memd_ip");

            $debug_page     = X("post.debug_page");
            $debug          = X("post.debug");

            

            $adminthread    = X("post.adminthread");
            $admin_show_post    = X("post.admin_show_post");
            $admin_show_post_post    = X("post.admin_show_post_post");
            $post_post_show_size    = X("post.post_post_show_size");

            
            
            $adminuser      = X("post.adminuser");

            $key    = trim(X("post.key"));
            
            if(!$debug)
                file_put_contents(INDEX_PATH . 'DEBUG','');
            else{
                if(is_file(INDEX_PATH. 'DEBUG'))
                    unlink(INDEX_PATH. 'DEBUG');


            }

            $this->conf['title']        =  $title;
            $this->conf['logo']         =  $logo;
            $this->conf['title2']       =  $title2;
            $this->conf['keywords']     =  $keywords;
            $this->conf['description']  =  $de;

            $this->conf['send_email_s']     =   $send_email_s;
            $this->conf['out_s']            =   $out_s;
            $this->conf['mp3_system']       =   $mp3_system;
            $this->conf['mp3_friend']       =   $mp3_friend;
            $this->conf['user_have_badword']=   $user_have_badword;
            $this->conf['on_edit_user']     =   $on_edit_user;
            
            

            $this->conf['gold_thread']      =   $gold_thread;
            $this->conf['gold_post']        =   $gold_post;
            $this->conf['gold_digest']      =   $gold_digest;
            $this->conf['credits_thread']   =   $credits_thread;
            $this->conf['credits_post']     =   $credits_post;
            $this->conf['credits_digest']   =   $credits_digest;

            $this->conf['homelist']         =   $homelist;
            $this->conf['forumlist']        =   $forumlist;
            $this->conf['postlist']         =   $postlist;
            $this->conf['searchlist']       =   $searchlist;
            $this->conf['search_key_size']  =   $search_key_size;


            
            $this->conf['titlesize']        =   $titlesize;
            $this->conf['titlemin']         =   $titlemin;
            $this->conf['summary_size']     =   $summary_size;
            $this->conf['emailhost']        =   $emailhost;
            $this->conf['emailuser']        =   $emailuser;
            $this->conf['emailpass']        =   $emailpass;
            $this->conf['emailport']        =   $emailport;
            $this->conf['emailtitle']       =   $emailtitle;
            $this->conf['emailcontent']     =   $emailcontent;

            $this->conf['post_image_size']  =   $post_image_size;
            $this->conf['uploadfileext']    =   $uploadfileext;
            $this->conf['uploadimageext']   =   $uploadimageext;
            $this->conf['uploadimagemax']   =   $uploadimagemax;
            $this->conf['uploadfilemax']    =   $uploadfilemax;

            $this->conf['allow_upload_video']   =   $allow_upload_video;
            $this->conf['upload_video_ext']     =   $upload_video_ext;
            $this->conf['upload_video_size']    =   $upload_video_size;
            $this->conf['allow_upload_audio']   =   $allow_upload_audio;
            $this->conf['upload_audio_ext']     =   $upload_audio_ext;
            $this->conf['upload_audio_size']    =   $upload_audio_size;

            $this->conf['adminforum']   =   $adminforum;


            //$this->conf['wapview']=$wapview;
            //$this->conf['wapmessview']=$wapmessview;
            //$this->conf['wapuserview2']=$wapuserview2;
            //$this->conf['wapuserview']=$wapuserview;
            

            $this->conf['cache_type']       =   $cache_type;
            $this->conf['cache_table']      =   $cache_table;
            $this->conf['cache_key']        =   $cache_key;
            $this->conf['cache_time']       =   $cache_time;
            $this->conf['cache_pr']         =   $cache_pr;
            $this->conf['cache_ys']         =   $cache_ys;
            $this->conf['cache_outtime']    =   $cache_outtime;
            $this->conf['cache_redis_ip']   =   $cache_redis_ip;
            $this->conf['cache_redis_port'] =   $cache_redis_port;
            $this->conf['cache_mem_ip']     =   $cache_mem_ip;
            $this->conf['cache_mem_port']   =   $cache_mem_port;
            $this->conf['cache_memd_ip']    =   $cache_memd_ip;

            $this->conf['debug']        =   $debug;
            $this->conf['debug_page']   =   $debug_page;


            

            $this->conf['adminthread']          =   $adminthread;
            $this->conf['admin_show_post']      =   $admin_show_post;
            $this->conf['admin_show_post_post'] =   $admin_show_post_post;
            $this->conf['post_post_show_size']  =   $post_post_show_size;
            
            $this->conf['adminuser']    =   $adminuser;
            $this->conf['key']          =   $key;


            //{hook a_admin_op_3}
            file_put_contents(CONF_PATH . 'conf.php' , "<?php die(); ?>\r\n".json_encode($this->conf));
            $this->json(array('error'=>true,'info'=>'修改配置成功'));
        }//END IF
        //{hook a_admin_op_v}

        $this->conf['title2'] = str_replace(" - Powered by HYBBS",'',$this->conf['title2']);

        $this->v("conf",$this->conf);
        $this->display('op');
    }

    public function codeol(){

        if(IS_POST){ // 下载压缩包
            $name = X("post.name");
            $gn = X("post.gn");

            if($gn == 'get_down'){
                $json = http_get_app(APP_WWW . 'json/get_down_path1',array('name'=>$name));
                if(empty($json))
                    $this->json(array('error'=>false,'data'=>'访问远程服务器失败.'));

                $json = json_decode($json,true);
                if($json['error'])
                    $this->json(array('error'=>true,'data'=>$json['data']));
                $this->json(array('error'=>false,'data'=>$json['data']));
            }
            //if($gn == 'down'){
            $down = APP_WWW . 'app/' . $name . '/' . X("post.www");

            //}
            //$this->json(array('error'=>false,'data'=>''));

            $on = false; //是否已经开始
            $install = false; //是否已经安装
            $inc = array();
            if($gn=='update'){ //更新插件
                //del_cache_file($this->conf);
                if(is_file(PLUGIN_PATH . $name . '/on'))
                    $on = true;
                if(is_file(PLUGIN_PATH . $name . '/install'))
                    $install = true;

                $inc = get_plugin_inc($name);

                if(!deldir(PLUGIN_PATH . $name,false,true))
                    $this->json(array('error'=>false,'data'=>"无法删除旧插件,请手动删除" . PLUGIN_PATH . $name ));
                    


            }
            


            //下载插件

            if(is_dir(PLUGIN_PATH . $name) && is_file(PLUGIN_PATH . $name . '/conf.php') ){
                $this->json(array('error'=>false,'data'=>'当前插件已经存在,无法覆盖安装,你需要手动删除!'));
                
            }
            $zip = L("Zip");
            //下载插件 ZIP
            $path = TMP_PATH . md5(APP_KEY.$name) .'.zip';
            if(is_file($path))
                unlink($path);
            if(is_file($path))
                $this->json(array('error'=>false,'data'=>'权限出现问题! 无法删除历史插件包 : ' . $path));
            
            //$down = C("PLUGIN_DOWN")."downplugin/".$name . '.zip';
            //echo $down;
            (http_down($path,$down));
            if(!is_file($path))
                $this->json(array('error'=>false,'data'=>'插件下载失败.'));

            $un_info = $zip->unzip($path,PLUGIN_PATH);
            if($un_info !== true){
                $this->json(array('error'=>false,'data'=>'插件解压失败，'.$un_info));
            }

            if(is_dir(PLUGIN_PATH . $name)){ //解压成功
                if(is_file(PLUGIN_PATH . $name . '/on'))
                    unlink(PLUGIN_PATH . $name . '/on');
                if(is_file(PLUGIN_PATH . $name . '/install'))
                    unlink(PLUGIN_PATH . $name . '/install');

                if($on)
                    file_put_contents(PLUGIN_PATH . $name . '/on','');
                if($install)
                    file_put_contents(PLUGIN_PATH . $name . '/install','');

                //$file = file(PLUGIN_PATH . "/{$name}/inc.php");
                //$json = isset($file[1]) ? json_decode($file[1],true) : array();
                $inc1 = get_plugin_inc($name);
                if(!empty($inc1) && !empty($inc)){

                    foreach ($inc1 as $k => &$v) {
                        if(isset($inc[$k])){
                            if(!empty($inc[$k])){
                                $v = $inc[$k];
                            }
                        }
                        
                    }


                }
                put_tmp_file(PLUGIN_PATH . "{$name}/inc.php",json_encode($inc1));


                $this->json(array('error'=>true,'data'=>'安装完成，请手动开启该插件.'));
            }
            $this->json(array('error'=>false,'data'=>'插件安装失败, 失败因素: 1.Tmp，Plugin无写入权限、2.下载安装包可能不完整或损坏、3.可能远程服务器不存在该插件安装包！'));
        }
        $ml = array();
        $dh = opendir(PLUGIN_PATH);
        $conf = array();
        while (($dir = readdir($dh)) !== false) {
            $conf_path = PLUGIN_PATH.$dir.'/conf.php';
            if($dir=='.'||$dir=='..'||!is_dir(PLUGIN_PATH.'/'.$dir) || !is_file($conf_path)){
                continue;
            }
            $tmp = include $conf_path;
            $tmp['name'] = $dir;
            $conf[]=$tmp;
        }
        unset($v);
        foreach ($conf as &$v) {
            isset($v['version']) or $v['version'] = '1.0';
        }
        $this->v('data',json_encode($conf));
        $this->display('codeol');

    }
    //上传、更新插件压缩包
    public function update_code(){
        if(IS_POST){
            $upload = new \Lib\Upload();// 实例化上传类
            $upload->maxSize   =  0;// 设置附件上传大小 

            $upload->exts      =    array('zip');// 设置图片上传类型
            $upload->rootPath  =    TMP_PATH; // 设置图片上传根目录
            $upload->autoSub    =   false;
            $info   =   $upload->upload();
            if($info){
                $file_path = TMP_PATH . $info['photo']['savename'];
                $zip = L("Zip");
                $zip->unzip($file_path, PLUGIN_PATH);
                $this->json(array('error'=>true));
            }
        }
        $this->json(array('error'=>true,'data'=>$upload->getError()));
        
    }
    //上传、更新模板压缩包
    public function update_view(){
        if(IS_POST){
            $upload = new \Lib\Upload();// 实例化上传类
            $upload->maxSize   =  0;// 设置附件上传大小 

            $upload->exts      =    array('zip');// 设置图片上传类型
            $upload->rootPath  =    TMP_PATH; // 设置图片上传根目录
            $upload->autoSub    =   false;
            $info   =   $upload->upload();
            if($info){
                $file_path = TMP_PATH . $info['photo']['savename'];
                $zip = L("Zip");
                $zip->unzip($file_path, VIEW_PATH);
                $this->json(array('error'=>true));
            }
            $this->json(array('error'=>true,'data'=>$upload->getError()));
        }            
    }
    public function code(){

        if(IS_POST){
            $name = X("post.name");
            $gn = X("post.gn");
            if($gn == 'op'){//修改插件配置
                if(!is_file(PLUGIN_PATH . "/{$name}/inc.php"))
                    return $this->mess("这个插件没有配置功能");

                $file = file(PLUGIN_PATH . "/{$name}/inc.php");
                $json = isset($file[1]) ? json_decode($file[1],true) : array();

                foreach ($_POST as $k => $v) {
                    $json[$k] = $v;
                }

                put_tmp_file(PLUGIN_PATH . "/{$name}/inc.php",json_encode($json));
                $this->json(array('error'=>true));
            }elseif($gn == 'install'){ //安装插件 执行安装函数
                $path = PLUGIN_PATH . "/{$name}/function.php";
                if(!is_file($path))
                    return $this->mess('这个插件 没有安装功能');

                include $path;
                $result = plugin_install();
                $verify = X('post.verify');
                if($result === true || $verify == 'on' ){
                    file_put_contents(PLUGIN_PATH . "/{$name}/install",'');
                    del_cache_file($this->conf);
                    return $this->mess('安装成功');
                }
                else{
                    if($result !== false)
                        return $this->mess('安装失败,报错原因:'.$result);
                    else
                        return $this->mess('安装失败.无具体原因');
                }


 
            }elseif($gn == 'uninstall'){ //卸载插件 执行卸载函数
                $path = PLUGIN_PATH . "/{$name}/function.php";
                if(!is_file($path))
                    return $this->mess('这个插件 没有安装功能');

                include $path;
                $result = plugin_uninstall();
                $verify = X('post.verify');
                if($result === true || $verify == 'on' ){
                    //return $this->mess('这个插件并没有安装,你不需要卸载');
                    if(is_file(PLUGIN_PATH . "/{$name}/install"))
                        unlink(PLUGIN_PATH . "/{$name}/install");
                    del_cache_file($this->conf);
                    return $this->mess('卸载成功');
                }
                else{
                    if($result !== false)
                        return $this->mess('卸载失败,报错原因:'.$result);
                    else
                        return $this->mess('卸载失败.无具体原因');
                }
            }elseif($gn == 'del'){ //删除插件 删除插件目录
                deldir(PLUGIN_PATH . "{$name}",false,true);
                del_cache_file($this->conf);
                return $this->mess('删除成功');
            }elseif($gn == 'add'){ //添加插件 建立插件目录
                $name   = X("post.name"); //插件名
                $name2  = X("post.name2"); //插件英文名
                $user   = X("post.user"); //作者
                $icon   = X("post.icon"); //fa图标
                $mess   = X("post.mess"); //插件描述
                $inc    = X("post.inc"); //是否开启配置功能
                $fun    = X("post.fun"); //是否支持函数

                if(is_dir(PLUGIN_PATH . $name2))
                    return $this->mess("已存在相同英文名的插件");
                create_dir(PLUGIN_PATH . $name2);
                file_put_contents(PLUGIN_PATH . $name2 . '/conf.php',"<?php
return array(
    'name' => '{$name}',
    'user' => '{$user}',
    'icon' => '{$icon}',
    'mess' => '{$mess}',
    'version' => '1.0',
);");
                if($inc){
                    put_tmp_file(PLUGIN_PATH . $name2 . '/inc.php','{}');
                    file_put_contents(PLUGIN_PATH . $name2 . '/conf.html','在这里输入你的HTML表单');
                }
                if($fun){
                    file_put_contents(PLUGIN_PATH . $name2 . '/function.php','<?php
function plugin_install(){
    return true;
}
function plugin_uninstall(){
    return true;
}
                    ');
                }

                return $this->mess("插件建立成功,请打开" . PLUGIN_PATH . $name2 . '进行开发吧');
            }elseif($gn == 'apply_code'){ //修改插件开启状态
                $name = X("post.name");
                $state = X("post.state");

                $path = PLUGIN_PATH . "/{$name}/function.php";
                if(is_file($path))
                    include $path;

                if($state == 'on'){//关闭插件
                    if(is_file(PLUGIN_PATH . $name . '/on'))
                        unlink(PLUGIN_PATH . $name . '/on');
                    if(function_exists('plugin_off'))
                        plugin_off();
                }
                else{//开启插件
                    file_put_contents(PLUGIN_PATH . $name . '/on','');
                    if(function_exists('plugin_on'))
                        plugin_on();
                }


                del_cache_file($this->conf);
                
                $this->json(array('error'=>true,'info'=>'修改成功'));
            }
            if(IS_AJAX)
                $this->json(array('error'=>false,'info'=>'缺少参数，提交无效'));
            return $this->mess("未知参数1");


        }

        if(IS_AJAX){
            
            $name = X("get.name");
            $gn = X("get.gn");

            if(!empty($name)){ //加载插件配置
                if($gn == 'op'){ // 显示插件配置模板
                    $conf = PLUGIN_PATH . "/{$name}/conf.html";
                    if(!is_file($conf))
                        die('这个插件没有配置功能');

                    $file = file(PLUGIN_PATH . "/{$name}/inc.php");
                    $this->v('inc',isset($file[1]) ? json_decode($file[1],true) : array());
                    C("DEBUG_PAGE",false);
                    return $this->display("plugin.{$name}::conf");
                }elseif($gn == 'install'){ //显示插件安装配置
                    $path = PLUGIN_PATH . "/{$name}/function.php";
                    if(!is_file($path))
                        die('这个插件 没有安装功能');

                        die (str_replace('<?php','','<div class="alert alert-danger alert-custom alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                            <i class="fa fa-times-circle m-right-xs"></i> <strong>警告!</strong>插件的安装与卸载可能会做一些危险动作,请慎重执行!
                        </div><pre>'.file_get_contents($path).'</pre><div class="form-group" style="text-align: right;line-height: 1.2;"><div class="custom-checkbox"><input name="verify" type="checkbox" id="chk1"><label for="chk1"></label></div>强制安装</div>'));


                }elseif($gn == 'uninstall'){
                    $path = PLUGIN_PATH . "/{$name}/function.php";
                    if(!is_file($path))
                        die('这个插件 没有安装功能');

                    die (str_replace('<?php','','<div class="alert alert-danger alert-custom alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                        <i class="fa fa-times-circle m-right-xs"></i> <strong>警告!</strong>插件的安装与卸载可能会做一些危险动作,请慎重执行!
                    </div><pre>'.file_get_contents($path).'</pre><div class="form-group" style="text-align: right;line-height: 1.2;"><div class="custom-checkbox"><input name="verify" type="checkbox" id="chk1"><label for="chk1"></label></div>强制卸载</div>'));
                }

            }
            return $this->mess("未知参数2");


        }


        $dh = opendir(PLUGIN_PATH);
        $ml = array();
        $qj = array();

        while (($dir = readdir($dh)) !== false) {

            $conf_path = PLUGIN_PATH.$dir.'/conf.php';
            if($dir=='.' || $dir=='..' || !is_dir(PLUGIN_PATH.$dir) || !is_file($conf_path)){
                
                continue;
            }
            $ml[$dir]=$dir;

            $qj[$dir] = include $conf_path;
            //$qj[$key]['path'] =
            if(is_file(PLUGIN_PATH.$dir.'/on'))
                $qj[$dir]['on'] = true;

        }
        unset($v);
        foreach ($qj as &$v) {
            isset($v['version']) or $v['version'] = '1.0';
        }



        $this->v("conf",$qj);
        $this->v('data',$ml);
        $this->display('code');

    }
    //插件优先级
    private function code_op_loop($path,&$v){
        $dh1 = opendir($path);
        while (($dir1 = readdir($dh1)) !== false) {
            $file_location = str_replace('//', '/', $path . '/' .$dir1);
            if($dir1=='.'||$dir1=='..'){
                continue;
            }
            if(is_dir($file_location)){
                $this->code_op_loop($file_location,$v);
            }else{
                $v['hook'][]=$dir1;
            }
            
        }
    }
    //插件优先级
    public function code_op(){
        if(IS_POST){
            $hook = X("post.hook");
            $code = X("post.code");
            $value = X("post.value");

            //if(!is_file(PLUGIN_PATH . "/{$name}/inc.php"))
                //return $this->mess("这个插件没有配置功能");
            $file=array();
            if(is_file(PLUGIN_PATH . "{$code}/p.php"))
                $file = file(PLUGIN_PATH . "{$code}/p.php");
            $json = isset($file[1]) ? json_decode($file[1],true) : array();

            $json[$hook] = $value;
            
            put_tmp_file(PLUGIN_PATH . "{$code}/p.php",json_encode($json));
            $this->json(array('error'=>true));
        }

        $this->ml = array();
        $this->dh = opendir(PLUGIN_PATH);
        $this->qj = array();

        while (($dir = readdir($this->dh)) !== false) {

            $conf_path = PLUGIN_PATH.$dir.'/conf.php';
            if($dir=='.'||$dir=='..'||!is_dir(PLUGIN_PATH.$dir) || !is_file($conf_path)){
        
                continue;
            }
            
            
            if(is_file(PLUGIN_PATH.$dir.'/on'))
                $this->qj[$dir]['on'] = true;

            $v=array('name'=>$dir,'p'=>array());
            $v['hook']=array();
            $this->code_op_loop(PLUGIN_PATH.$dir,$v);

            

            foreach ($v['hook'] as $k=> &$vv) {
                if(substr(strrchr($vv, '.'), 1) != 'hook')
                    unset($v['hook'][$k]);
            }
            if(empty($v['hook'])){
                
                continue;
            }
            

            $v['conf'] = include $conf_path;
            isset($v['conf']['version']) or $v['conf']['version'] = '1.0';
            if(is_file(PLUGIN_PATH.$v['name'] . "/p.php")){
                $file = file(PLUGIN_PATH.$v['name'] ."/p.php");
                $v['p'] = isset($file[1]) ? json_decode($file[1],true) : array();

            }
            $this->ml[$dir]=$v;

            

        }

        
        // unset($v);
        // foreach ($qj as &$v) {
        //     isset($v['version']) or $v['version'] = '1.0';
        // }
        //$this->v("conf",$qj);
        $this->v('data',$this->ml);
        //print_r($ml);
        $this->display('code_op');
            
    }
    //分类版主
    public function forumg(){

        if(IS_POST){
            //删除缓存
            $this->CacheObj->rm('forum');
            $gn = X("post.gn");
            $id = X("post.id");
            $user = X("post.user");
            if($gn == 'forumg'){
                S("Forum")->update(array(
                    'forumg'=>$user
                ),array(
                    'id'=>$id
                ));
                return $this->mess('修改完成');
            }else{
                $forum = M("Forum")->read_all();
                $arr = json_decode($forum[$id]['json'],true);
                $arr[$gn] = $user;
                S("Forum")->update(array(
                    'json'=>json_encode($arr)
                ),array(
                    'id'=>$id
                ));

            }
        }
        if(IS_AJAX){ //显示版主编辑模板
            $id = X("get.id");
            $gn = X("get.gn");

            if($gn == 'forumg'){
                if($id > -1){
                    $user = S("Forum")->find("forumg",array(
                        'id'=>$id
                    ));
                    $this->v("user",$user);
                    $this->v("id",$id);
                    $this->display("ajax_forum");
                    exit;
                }
            }else{
                $forum = M("Forum")->read_all();
                $arr = json_decode($forum[$id]['json'],true);
                $this->v("user",isset($arr[$gn])?$arr[$gn]:'');
                $this->v("id",$id);
                $this->display("ajax_forum");
                exit;
            }


        }


        $Forum = S("Forum");
        $data = $Forum->select("*");

        $User = M("User");
        foreach ($data as &$v) {
            $tmp = explode(",",$v['forumg']);
            if(!count($tmp))
                continue;
            $v['user'] = array();
            foreach ($tmp as $vv) {
                $v['user'][]=$User->uid_to_user(intval($vv));

            }
            unset($tmp);
        }

        $this->v("data",$data);
        $this->display('forumg');
    }
    public function forum_json(){

        if(IS_POST){
            //删除缓存
            $this->CacheObj->rm('forum');
            $gn = X("post.gn");
            $id = X("post.id");
            $user = X("post.user");
            if($gn == 'forumg'){
                S("Forum")->update([
                    'forumg'=>$user
                ],[
                    'id'=>$id
                ]);
                $this->CacheObj->rm('forum');
                return $this->mess('修改完成');
            }else{
                $forum = M("Forum")->read_all();
                $arr = json_decode($forum[$id]['json'],true);
                $arr[$gn] = $user;
                S("Forum")->update(array(
                    'json'=>json_encode($arr)
                ),array(
                    'id'=>$id
                ));
                $this->CacheObj->rm('forum');

            }
        }

        
        if(IS_AJAX){
            $id = X("get.id");
            $gn = X("get.gn");

            if($gn == 'forumg'){
                if($id > -1){
                    $user = S("Forum")->find("forumg",array(
                        'id'=>$id
                    ));
                    $this->v("user",$user);
                    $this->v("id",$id);
                    $this->display("ajax_forum1");
                    exit;
                }
            }else{
                $forum = M("Forum")->read_all();
                $arr = json_decode($forum[$id]['json'],true);
                $this->v("user",isset($arr[$gn])?$arr[$gn]:'');
                $this->v("id",$id);
                $this->display("ajax_forum1");
                exit;
            }


        }


        $Forum = S("Forum");
        $data = $Forum->select("*");
        $Usergroup = M("Usergroup");
        foreach ($data as &$v) {
            $arr = json_decode($v['json'],true);
            $v['jsonarr'] = array(
                "vforum"=>array(),
                'vthread'=>array(),
                'thread'=>array(),
                'post'=>array(),
                'downfile'=>array(),
            );

            if(is_array($arr)){
                foreach ($arr as $key=>$value) {
                    $v['jsonarr']["$key"]=array();
                    //分割 json
                    $tmp = explode(",",$arr["$key"]);
                    if(!count($tmp))
                        continue;

                    foreach ($tmp as $vv) {
                        $v['jsonarr']["$key"][]=$Usergroup->gid_to_name(intval($vv));
                    }
                    unset($tmp);
                }
            }
        }
        $this->v("data",$data);
        $this->display('forum_json');
    }
    //分类图标上传
    public function forumupload(){
        if(IS_POST){
            $upload = new \Lib\Upload();// 实例化上传类
            $upload->maxSize   =     3145728 ;// 设置附件上传大小  3M
            $upload->exts      =     explode(",",$this->conf['uploadimageext']);// 设置图片上传类型
            $upload->rootPath  =     INDEX_PATH. "upload/"; // 设置图片上传根目录
            $upload->replace    =   true;
            $upload->autoSub    =   false;
            $upload->saveName   =   'forum'.X("post.forum"); //保存文件名
            $upload->saveExt    =   'png';
            if(!is_dir(INDEX_PATH. "upload"))
                create_dir(INDEX_PATH. "upload");
            $info   =   $upload->upload();
            if(!$info) {
                return $this->mess("上传失败 Error : " . $upload->getError());
            }
            else{
                header('Location: '. HYBBS_URLA('admin','forum'));
                exit;
            }
        }
    }
    //获取更新
    public function hybbsupdate2(){
        $data = file_get_contents(C("PLUGIN_DOWN").'ajax/update2');
        $info = file_get_contents(C("PLUGIN_DOWN").'ajax/update_info');
        
        if(!empty($data)){
            $json = json_decode($data,true);
            if($data != HYBBS_V)
                $this->json(['error'=>true,'info'=>$data,'json'=>json_decode($info)]);

        }
        $this->json(['error'=>false,'info'=>'无更新']);
    }
    public function GetNewVersion(){
        $json_version = file_get_contents(C("PLUGIN_DOWN").'ajax/update3?version='.HYBBS_V);
        //$json_info = file_get_contents(C("PLUGIN_DOWN").'ajax/update3_info?version='.HYBBS_V);
        
        if(!empty($json_version)){
            $json = json_decode($json_version,true);
            if($json['error']){
                if(empty($json['sql'])) $json['sql']=[];
                foreach ($json['sql'] as $key => &$value) {
                    $value = str_replace('#SQL_STORAGE_ENGINE#',C('SQL_STORAGE_ENGINE'),$value);
                }
                if(empty($json['sql'])) $json['sql']=false;
                $this->json(['error'=>true,'info'=>$json]);
            }
        }
        $this->json(['error'=>false,'info'=>'无更新']);
    }
    public function UpdateNewFile(){
        $version    = X('post.version');
        $num        = intval(X('post.num'));

        $json_version = file_get_contents(C("PLUGIN_DOWN").'ajax/update3?version='.$version);
        if(!empty($json_version)){
            $json = json_decode($json_version,true);
            if($json['error']){
                if(empty($json['file']))
                    $this->json(['error'=>true,'info'=>'ok']);

                if(!isset($json['file'][$num]))
                    $this->json(['error'=>false,'info'=>'找不到这个文件ID：'.$num]);
                if(http_down(INDEX_PATH . trim($json['file'][$num]['file'],'/'),$json['file'][$num]['downurl']) === false)
                    $this->json(['error'=>false,'info'=>'这个文件下载失败，检查网络以及本地文件夹权限！']);

                if(count($json['file']) == $num +1)
                    $this->json(['error'=>true,'info'=>'ok']);
                $this->json(['error'=>true,'info'=>'下载完成']);
            }
            $this->json(['error'=>false,'info'=>'无更新']);
        }

        $this->json(['error'=>false,'info'=>'更新内容获取失败']);
    }
    public function UpdateNewSql(){
        $version    = X('post.version');
        $num        = intval(X('post.num'));

        $json_version = file_get_contents(C("PLUGIN_DOWN").'ajax/update3?version='.$version);
        if(!empty($json_version)){
            $json = json_decode($json_version,true);
            if($json['error']){
                if(empty($json['sql']))
                    $this->json(['error'=>true,'info'=>'更新完成','end'=>true]);

                if(!isset($json['sql'][$num]))
                    $this->json(['error'=>false,'info'=>'找不到这条ID：'.$num,'end'=>false]);

                $query = $json['sql'][$num];
                $query = str_replace('#SQL_STORAGE_ENGINE#',C('SQL_STORAGE_ENGINE'),$query);

                $sql = S("Plugin");
                $result = $sql->query($query);
                $end = false;
                if(count($json['sql']) == $num +1)
                    $end=true;

                if($result->errorCode() != 0){
                    if(strpos($result->errorInfo()[2],'Duplicate column') !== false || strpos($result->errorInfo()[2],'already exists') !== false || strpos($result->errorInfo()[2],'Duplicate entry') !== false){
                        $this->json(['error'=>true,'info'=>'重复','message'=>$result->errorInfo()[2],'code'=>$result->errorCode(),'end'=>$end]);
                    }
                    $this->json(['error'=>false,'info'=>'SQL报错['.$result->errorCode().']：'.$result->errorInfo()[2],'end'=>$end]);
                }
                
                $this->json(['error'=>true,'info'=>'更新完成','end'=>$end]);
            }
            $this->json(['error'=>false,'info'=>'无更新','end'=>false]);
        }
        $this->json(['error'=>false,'info'=>'更新SQL获取失败','end'=>false]);
    }
    public function UpdateNewIndex(){
        $version    = X('post.version');
        $json_version = file_get_contents(C("PLUGIN_DOWN").'ajax/update3?version='.$version);
        if(!empty($json_version)){
            $json = json_decode($json_version,true);
            if($json['error']){
                if(!isset($json['index']))
                    $this->json(['error'=>false,'info'=>'找不到这个版本号：'.$version]);
                http_down(INDEX_PATH . 'index.php',$json['index']);
                $this->json(['error'=>true,'info'=>'更新版本号完成']);
            }
            $this->json(['error'=>false,'info'=>'无更新']);
        }

        $this->json(['error'=>false,'info'=>'更新内容获取失败']);
    }
    public function update2(){
        if(IS_POST){
            $gn = X("post.gn");
            if($gn == 'down'){//下载最新压缩包
                $data = file_get_contents(C("PLUGIN_DOWN").'ajax/update2');
                if(empty($data))
                    $this->json(array('error'=>false,'info'=>'获取不到最新论坛版本! #down'));
                if(!is_file(TMP_PATH . $data .'.zip'))
                    //unlink(TMP_PATH . $data .'.zip');
                    http_down(TMP_PATH . $data .'.zip',C("PLUGIN_DOWN") .'downplugin/' .  $data.'.zip');
                if(is_file(TMP_PATH . $data .'.zip'))
                    $this->json(array('error'=>true,'info'=>'下载完成，开始自动升级！'));
                $this->json(array('error'=>false,'info'=>'下载失败！'));
            }elseif($gn == 'unzip'){ //解压压缩包
                $data = file_get_contents(C("PLUGIN_DOWN").'ajax/update2');
                if(empty($data))
                    $this->json(array('error'=>false,'info'=>'获取不到最新论坛版本! #unzip'));
                if(!is_file(TMP_PATH . $data .'.zip'))
                    $this->json(array('error'=>false,'info'=>'解压失败，压缩包不存在！'));
                $zip = L("Zip");
                
                $un_info = $zip->unzip(TMP_PATH . $data .'.zip', TMP_PATH);
                if($un_info !== true){
                    $this->json(array('error'=>false,'data'=>'升级包解压失败，，'.$un_info));
                }
                
                if(!is_dir(TMP_PATH . $data))
                    $this->json(array('error'=>false,'info'=>'下载的压缩包是损坏的,请清理缓存重新升级!'));

                if(!is_file(TMP_PATH . $data.'/sql.php'))
                    $this->json(array('error'=>false,'info'=>'升级失败：没有找到sql升级文件！'));

                include TMP_PATH . $data.'/sql.php';
                if(!function_exists('bbs_install'))
                    $this->json(array('error'=>false,'info'=>'升级失败：Sql文件丢失内容！'));

                if(!bbs_install())
                    $this->json(array('error'=>false,'info'=>'升级失败：Sql执行失败！'));
                del_cache_file($this->conf);
                $this->json(array('error'=>true,'info'=>'解压完成，进行安装！','url'=>WWW.'Tmp/'.$data.'/update.php'));

            }elseif($gn == 'sql'){ //执行SQL
                del_cache_file($this->conf);
                $this->json(array('error'=>true,'info'=>'论坛升级完成！'));
            }
        }
        $this->json(array('error'=>false,'info'=>'丢失参数!'));
    }
    public function get_code_json(){
        $json = http_get_app(APP_WWW . 'json/code?time=' .NOW_TIME);
        die($json);
    }
    public function get_theme_json(){
        $json = http_get_app(APP_WWW . 'json/theme?time=' .NOW_TIME);
        die($json);
    }
    public function get_view_inc(){
        $name = X("get.name");
        $this->view = $name;
        $this->display('conf');
    }
    public function getip(){
        $json = file_get_contents(APP_WWW . 'json/get_ip');
        die($json);
    }
    //模板高级配置提交
    public function ajax_edit_view(){
        if(IS_POST){
            $name = X('post.name');
            $v = X('post.value');
            $this->conf[$name] = $v;
            file_put_contents(CONF_PATH . 'conf.php' , "<?php die(); ?>\r\n".json_encode($this->conf));
            $this->json(array('error'=>true,'info'=>'修改配置成功'));
        }
    }
    //日志页面
    public function log(){
        $Log = S('Log');


        if(!isset($_SERVER['REQUEST_URI']))
            $_SERVER['REQUEST_URI']='';
        $x = explode('?uid',$_SERVER['REQUEST_URI']);
        if(isset($x[1]))
            $x = '?uid' . $x[1];
        else
            $x='';
        
        $this->v('x',$x);
        $pageid = intval(X('get.pageid',1));

        $this->v('pageid',$pageid);
        $len = 10;
        $where = [
            'ORDER'=>['id'=>'DESC'],
            "LIMIT" => [($pageid-1) * $len, $len]
        ];


        if(X('get.search_submit')){
            $where = [
                'AND'=>[],
                'ORDER'=>['id'=>'DESC'],
                "LIMIT" => [($pageid-1) * $len, $len]
            ];
            $uid = X('get.uid');
            $user = X('get.user');
            
            
            
            

            if(!empty($uid)){
                $where['AND']['uid']=$uid;
            }
            if(!empty($user)){
                $where['AND']['uid']=M('User')->user_to_uid($user);
            }
            
            

            
            
        }
        if(empty($where['AND']))
            unset($where['AND']);
        
        $data = $Log->select('*',$where);
        unset($where['ORDER']);
        unset($where['LIMIT']);
        $count = $Log->count($where);
        $count = (!$count)?1:$count;
        $page_count = ($count % $len != 0)?(intval($count/$len)+1) : intval($count/$len);

        $this->v("page_count",$page_count);
        $this->v("data",$data);
        $this->display("log");
    }
    public function log_php(){
        $this->display("log_php");
    }
    //更改用户 允许登陆 
    public function ajax_user_switch(){
        if(IS_POST){
            $type = X('post.type');
            $uid = X('post.uid');

            $User = M('User');

            if($type == 'login'){
                $state = $User->get_row($uid,'ban_login') == 0 ? 1 : 0; //交换状态
                
                $User->update(['ban_login'=>$state],['uid'=>$uid]);
                $this->json(['error'=>true,'info'=>'success','state'=>$state]);
            }elseif($type == 'post'){
                $state = $User->get_row($uid,'ban_post') == 0 ? 1 : 0;
                $User->update(['ban_post'=>$state],['uid'=>$uid]);
                $this->json(['error'=>true,'info'=>'success','state'=>$state]);
            }
        }
        $this->json(['error'=>false,'info'=>'参数丢失']);
    }
    public function ajax_clean_user(){
        if(IS_POST){
            $type = X('post.type');
            $uid = X('post.uid');
            $User = M('User');

            if($type == 'del_thread'){//删除所有文章
                $Thread = S('Thread');

                //获取所有主题TID 删除主题内所有附件
                $tid_list = $Thread->select('tid',['uid'=>$uid]);
                if(!empty($tid_list)){
                    foreach ($tid_list as $tid) {
                        $StorageThreadDir = GetStorageThreadDir($tid,false);
                        deldir(INDEX_PATH . $StorageThreadDir,false,true);
                    }
                }



                S('Post')->delete(['tid'=>$tid_list]);
                $Thread->delete(['uid'=>$uid]);
                $User->update(['threads'=>0,'posts'=>0],['uid'=>$uid]);
                $this->json(['error'=>true,'info'=>'删除文章成功']);
            }elseif($type == 'del_post'){//删除所有评论
                $Post = S('Post');

                //获取所有主题TID 删除主题内所有附件
                $pid_list = $Post->select(['pid','tid'],['uid'=>$uid]);
                if(!empty($pid_list)){
                    foreach ($pid_list as $v) {
                        $StoragePostDir = GetStoragePostDir($v['tid'],$v['pid'],false);
                        deldir(INDEX_PATH . $StoragePostDir,false,true);
                    }
                }


                $Post->delete(['AND'=>['uid'=>$uid,'isthread'=>0]]);
                $User->update(['posts'=>0],['uid'=>$uid]);
                $this->json(['error'=>true,'info'=>'删除评论成功']);
            }elseif($type == 'del_post_post'){
                S('Post_post')->delete(['uid'=>$uid]);
                $this->json(['error'=>true,'info'=>'删除子评论成功']);
            }elseif($type == 'del_file') {
                $Thread = S('Thread');
                $tid_list = $Thread->select('tid',['uid'=>$uid]);
                if(!empty($tid_list)){
                    foreach ($tid_list as $tid) {
                        $StorageThreadDir = GetStorageThreadDir($tid,false);
                        deldir(INDEX_PATH . $StorageThreadDir,true,false);
                    }
                }
                $Post = S('Post');
                $pid_list = $Post->select(['pid','tid'],['uid'=>$uid]);
                if(!empty($pid_list)){
                    foreach ($pid_list as $v) {
                        $StoragePostDir = GetStoragePostDir($v['tid'],$v['pid'],false);
                        deldir(INDEX_PATH . $StoragePostDir,false,true);
                    }
                }


                S('File')->delete(['uid'=>$uid]);
                S('Fileinfo')->delete(['uid'=>$uid]);
                deldir(INDEX_PATH. "upload/userfile/" . $uid,false,true);
                $this->json(['error'=>true,'info'=>'删除文件成功']);
            }elseif($type == 'del_follow'){
                S('Friend')->delete(['uid1'=>$uid]);
                $this->json(['error'=>true,'info'=>'清空关注列表成功']);
            }elseif($type == 'del_chat'){
                //$Chat->delete(['uid1'=>$uid]);
                //$Chat_count->delete(array('uid'=>$v));
                $this->json(['error'=>true,'info'=>'该功能被停用 无法清理聊天记录!']);
            }
        }
        $this->json(['error'=>false,'info'=>'参数丢失!']);
    }
    public function is_rewrite(){
        die('on');
    }
    public function updatebbs(){

        $this->display('updatebbs');
    }


    //{hook a_admin_fun}
}
