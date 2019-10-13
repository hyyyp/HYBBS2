<?php
namespace Action;
use HY\Action;
!defined('HY_PATH') && exit('HY_PATH not defined.');
class User extends HYBBS {
    public $menu_action;
    public function __construct(){
		parent::__construct();
        //{hook a_user_init}
        $this->view = IS_MOBILE ? $this->conf['wapuserview2'] : $this->conf['userview2'];
    }
    public function _no(){
        header("location: ".WWW);
        exit;
    }
    //消息跳转 设置已读 
    public function mess(){
        //{hook a_user_mess_1}
        if(!IS_LOGIN)
            return $this->message('请登录');
        //{hook a_user_mess_2}
        $id = intval(X("get.id") );
        if(empty($id))
            return $this->message('ID参数不完整');
        //{hook a_user_mess_3}
        $Mess = M("Mess");
        $data = $Mess->read($id);
        if(empty($data))
            return $this->message('不存在该消息');
        if($data['uid'] != NOW_UID)
            return $this->message('这条消息不属于你');
        //{hook a_user_mess_4}
        //设置已读
        if(!$data['view']) //如果是未读状态
        {
            $Mess->set_state($id);
            //未读消息 -1
            M("User")->update_int($data['uid'],'mess',"-");
        }
        //{hook a_user_mess_v}

        header("location: ". HYBBS_URLA('thread',$data['tid']) );
        exit;
    }
    public function Edit(){
        //{hook a_user_edit_1}
        if(!IS_LOGIN)
            return $this->message('请登录');

        $gn = X('post.gn');
        if($gn == 'ps'){
            $ps = htmlspecialchars(strip_tags(X("post.ps")));
            if(!empty($ps)){
                S("User")->update(array(
                    'ps'=>$ps
                ),[
                    'uid'=>NOW_UID
                ]);
                return $this->message('保存成功',true);
            }
        }elseif($gn == 'pass'){
            $pass0 = X("post.pass0");
            $pass1 = X("post.pass1");
            $pass2 = X("post.pass2");
            //{hook a_user_edit_2}
            if($pass1 != $pass2)
                return $this->message("两次密码不一致");
            $UserLib = L("User");
            if(!$UserLib->check_pass($pass1))
                return $this->message('密码不符合规则');
            //{hook a_user_edit_3}
            if($UserLib->md5_md5($pass0,$this->_user['salt']) != $this->_user['pass'])
                return $this->message('原密码不正确');
            $newpass = $UserLib->md5_md5($pass1,$this->_user['salt']);
            $this->_user['pass'] = $newpass;
            S("User")->update(array(
                'pass'=>$this->_user['pass']
            ),[
                'uid'=>NOW_UID
            ]);
            //{hook a_user_edit_4}
            cookie('HYBBS_HEX',$UserLib->set_cookie($this->_user));
            return $this->message("修改成功",true);
        }elseif ($gn == 'edit_username'){
            if(!$this->conf['on_edit_user'])
                return $this->message('管理员已关闭修改用户名功能!');
            $username = trim(X('post.username'));
            if (empty($username)){
                return $this->message('用户名不能为空!');
            }
            $userModel = M("User");
            if($userModel->is_user($username)){
                return $this->message('该用户名以存在!');
            }
            $UserLib = L("User");
            $msg = $UserLib->check_user($username);
            //检查用户名格式是否正确
            if(!empty($msg))
                return $this->message($msg);

            //{hook a_user_edit_5}

            $userModel->update(array(
                'user'=>$username
            ),[
                'uid'=>NOW_UID
            ]);
            $this->_user['user'] = $username;
            cookie('HYBBS_HEX',$UserLib->set_cookie($this->_user));
            $encode = mb_detect_encoding($username, array("ASCII",'UTF-8',"GB2312","GBK",'BIG5'));
            $username = mb_convert_encoding($username, 'UTF-8', $encode);
            return header("Location: " . HYBBS_URLA('my',$username,'op'));
        }

        
        return $this->message("提交出错");
        

    }


    //找回密码
    public function repass(){
        //{hook a_user_repass_1}
        $this->v("title","找回密码");
        if(IS_LOGIN)
            return $this->message("你已经登录,请注销后找回密码?");
        //{hook a_user_repass_2}
        if(IS_GET){
            
            $this->display('user_repass');
        }
    }
    //提交更改密码
    public function recode2(){
        //{hook a_user_recode2_1}
        $email = X("post.email");
        $code = strtoupper(X("post.code"));
        $pass1=X("post.pass1");
        $pass2=X("post.pass2");
        //{hook a_user_recode2_2}
        if(empty($email)||empty($code)||empty($pass1)||empty($pass2))
            $this->json(array('error'=>false,'info'=>'参数不完整,请填写好表单!'));
        if($pass1 != $pass2)
            $this->json(array('error'=>false,'info'=>'确认密码不一致'));
        //{hook a_user_recode2_3}
        $UserLib = L("User");
        if(!$UserLib->check_pass($pass1))
            $this->json(array('error'=>false,'info'=>'新密码不符合规则,必须大于等于5位'));
        //{hook a_user_recode2_4}
        $User = M("User");

        if(!$User->is_email($email))
            $this->json(array('error'=>false,'info'=>'邮箱不存在!'));
        $data = $User->email_read($email);
        if(empty($data))
            $this->json(array('error'=>false,'info'=>'邮箱不存在.'));
        //{hook a_user_recode2_5}
        if(strlen($code) != 6)
            $this->json(array('error'=>false,'info'=>'验证码是6位的.'));
        //{hook a_user_recode2_6}
        $cookie = cookie("HY_EMAIL");
        if(empty($cookie))
            $this->json(array('error'=>false,'info'=>'验证码已经过期,请获取新验证码,紧急请联系管理员.'));

        //{hook a_user_recode2_7}
        $Encrypt = L("Encrypt");
        $cr = $Encrypt->decrypt($cookie,$data['salt'].C("MD5_KEY"));
        if($cr != $code)
            $this->json(array('error'=>false,'info'=>'验证码错误.'));
        //{hook a_user_recode2_8}
        $User->update(array('pass'=>L("User")->md5_md5($pass1,$data['salt'])),array('uid'=>$data['uid']));
        cookie('HY_EMAIL',null);
        $this->json(array('error'=>true,'info'=>'修改成功.'));


    }
    //发送验证码
    public function recode(){
        //{hook a_user_recode_1}
        $email = X("post.email");
        

        $emailhost = $this->conf['emailhost'];
        $emailport = $this->conf['emailport'];
        $emailuser = $this->conf['emailuser'];
        $emailpass = $this->conf['emailpass'];
        //{hook a_user_recode_2}

        if(empty($emailhost) || empty($emailport))
            $this->json(array('error'=>false,'info'=>'网站没开启邮箱功能,请联系网站管理员'));
        //{hook a_user_recode_3}
        $User = M("User");
        if(!$User->is_email($email))
            $this->json(array('error'=>false,'info'=>'该邮箱不存在!'));
        //{hook a_user_recode_4}
        $data = $User->email_read($email);
        if(empty($data))
            $this->json(array('error'=>false,'info'=>'该邮箱不存在.'));
        //{hook a_user_recode_5}
        if($data['etime'] > NOW_TIME)
            $this->json(array('error'=>false,'info'=>($data['etime'] - NOW_TIME). '秒后才能发送验证码.'));

        //{hook a_user_recode_6}
        $code = rand_code(6);

        $Email = L("Email");

        $Encrypt = L("Encrypt");

        //{hook a_user_recode_7}
        $Email->init($emailhost,$emailport,true,$emailuser,$emailpass);

        if(!$Email->sendmail($email,$emailuser,$this->conf['emailtitle'],sprintf($this->conf['emailcontent'],$data['user'],$code),'HTML'))
            $this->json(array('error'=>false,'info'=>'发送失败,具体原因:'.$Email->error_mess));
        cookie('HY_EMAIL',$Encrypt->encrypt($code,$data['salt'].C("MD5_KEY")),300); //有效期5分钟
        $User->update(['etime'=>NOW_TIME+BBSCONF('send_email_s')],['uid'=>$data['uid']]);
        $this->json(['error'=>true,'info'=>'发送成功!']);
        

    }

    //登录账号
    public function Login(){
        //{hook a_user_login_1}
        $this->v("title","登录页面");
        if(IS_LOGIN)
            return $this->message("你都已经登录了,登录那么多次干嘛");

        if(IS_GET){
            //{hook a_user_login_2}
            $re_url = X("server.HTTP_REFERER");
            if($re_url=='')
                $re_url=WWW;
            if(strpos($re_url,WWW)!= -1 && strpos($re_url,'user')===false)
                cookie('re_url',$re_url);
            
            $this->display('user_login');
        }
        elseif(IS_POST){
            $user = X("post.user");
            $pass = X("post.pass");

            $UserLib = L("User");
            //{hook a_user_login_3}

            //$msg = $UserLib->check_user($user);
            //检查用户名格式是否正确
            //if(!empty($msg))
                //return $this->message($msg);

            if(!$UserLib->check_pass($pass))
                return $this->message('密码不符合规则');
            //{hook a_user_login_4}
            $User = M("User");
            if(!$User->is_user($user))
                return $this->message("账号不存在!");

            $data = $User->user_read($user);
            //{hook a_user_login_5}
            if(!empty($data)){
                //{hook a_user_login_51}
                //密码正确
                if($data['pass'] == $UserLib->md5_md5($pass,$data['salt'])){//登录成功
                    if($data['ban_login']){
                        return $this->message("账号已经被管理员锁定，禁止登陆!");
                    }
                    $Friend = S("Friend");
                    $sum = $Friend->sum("c",array('uid1'=>$data['uid']));
                    M("Chat_count")->update(array('c'=>$sum),array('uid'=>$data['uid']));

                    //{hook a_user_login_52}
                    //更新用户所有缓存 一个星期更新缓存
                    if($data['ctime']+(86400*7) < NOW_TIME){

                        $count1 = $Friend->count(array('AND'=>array('uid1'=>$data['uid'],'OR'=>array('state'=>array(1,2)))));
                        $count2 = $Friend->count(array('AND'=>array('uid2'=>$data['uid'],'OR'=>array('state'=>array(1,2)))));

                        $User->update([
                            'ctime'=>NOW_TIME,
                            'threads'=>S("Thread")->count(['uid'=>$data['uid']]),
                            'posts'=>S("Post")->count(['uid'=>$data['uid']]),
                            'post_ps'=>S("Post_post")->count(['uid'=>$data['uid']]),
                            'follow'=>$count1,
                            'fans'=>$count2,
                        
                        ],[
                            'uid'=>$data['uid']
                        ]);
                    }
                   
                    //{hook a_user_login_53}
                    //在线用户结束
                    cookie('HYBBS_HEX',$UserLib->set_cookie($data));
                    $this->init_user();
                    //{hook a_user_login_54}
                    $re_url = cookie('re_url');
                    if($re_url=='')
                        $re_url='';
                    cookie('re_url',null);
                    return $this->message("登录成功 !",true,$re_url);
                }else{
                    //{hook a_user_login_56}
                    return $this->message("密码错误!");
                }
            }else{
                return $this->message('账号数据不存在!');
            }
        }
        //{hook a_user_login_6}
    }
    //注册账号
    public function Add(){
        //{hook a_user_add_1}

        $this->v("title","注册用户");
        if(IS_LOGIN)
            return $this->message("你都已经登录了,还注册那么多账号干嘛");
        if(IS_GET){
            //{hook a_user_add_2}
            $re_url = X("server.HTTP_REFERER");
            if($re_url=='')
                $re_url=WWW;
            if(strpos($re_url,WWW)!= -1 && strpos($re_url,'user')===false)
                cookie('re_url',$re_url);
            $this->display('user_add');
        }
        elseif(IS_POST){
            $user = X("post.user");
            $pass1 = X("post.pass1");
            $pass2 = X("post.pass2");
            $email = X("post.email");
            //{hook a_user_add_3}
            if($pass1 != $pass2)
                return $this->message("两次密码不一致");

            $UserLib = L("User");
            $msg = $UserLib->check_user($user);
            //检查用户名格式是否正确
            if(!empty($msg))
                return $this->message($msg);

            if(!$UserLib->check_pass($pass1))
                return $this->message('密码不符合规则');

            //{hook a_user_add_4}

            $msg = $UserLib->check_email($email);

            if(!empty($msg))
                return $this->message($msg);

            //{hook a_user_add_5}
            $User = M("User");
            if($User->is_user($user))
                return $this->message("账号已经存在!");

            if($User->is_email($email))
                return $this->message("邮箱已经存在!");


            //{hook a_user_add_6}
            $uid = $User->add_user($user,$pass1,$email);

            cookie('HYBBS_HEX',$UserLib->set_cookie($User->read($uid)));
            //{hook a_user_add_v}
            $this->_count['user']++;
            $this->_count['day_user']++;
            $this->CacheObj->bbs_count = $this->_count;
            $re_url = cookie('re_url');
            if($re_url=='')
                $re_url='';
            cookie('re_url',null);

            return $this->message("账号注册成功",true,$re_url);
        }
        //{hook a_user_add_7}
    }
    //上传头像
    public function ava(){
        //{hook a_user_ava_1}
        $this->v("title","更改头像");
        if(!IS_LOGIN) 
            return $this->message("请登录后操作!");

        //{hook a_user_ava_2}

        L("Upload");
        //{hook a_user_ava_3}
        $upload = new \Lib\Upload();
        $upload->maxSize   =     3145728 ;// 设置附件上传大小  3M
        $upload->exts      =     array('jpg', 'bmp', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =     INDEX_PATH . 'upload/avatar/'; // 设置附件上传根目录
        $upload->saveExt    =   "jpg";
        $upload->replace    =   true;
        $upload->autoSub    =   false;
        $upload->saveName   =   md5(NOW_UID);
        if(!is_dir($upload->rootPath))
            create_dir($upload->rootPath);
        //{hook a_user_ava_4}
        $info   =   $upload->upload();
        
        if(!$info)
            return $this->message("上传失败!");

        //{hook a_user_ava_5}
        $image = new \Lib\Image();
        $image->open(INDEX_PATH . 'upload/avatar/'.$upload->saveName.".jpg");
        // 生成一个缩放后填充大小150*150的缩略图并保存为thumb.jpg
        $image->thumb(250, 250,$image::IMAGE_THUMB_CENTER)->save(INDEX_PATH . 'upload/avatar/'.$upload->saveName."-a.jpg");
        $image->thumb(150, 150,$image::IMAGE_THUMB_CENTER)->save(INDEX_PATH . 'upload/avatar/'.$upload->saveName."-b.jpg");
        $image->thumb(50  , 50,$image::IMAGE_THUMB_CENTER)->save(INDEX_PATH . 'upload/avatar/'.$upload->saveName."-c.jpg");
        //$image->thumb(150, 150,\Think\Image::IMAGE_THUMB_CENTER)
        //{hook a_user_ava_v}
        return $this->message("上传成功!",true);

    }
    public function out(){
        //{hook a_user_out_1}
        if(!IS_LOGIN)
            $this->message('退出成功',true);
        //{hook a_user_out_v}
        $this->v("title","注销用户");
        cookie('HYBBS_HEX',null);
        //{hook a_user_out_2}
        $this->init_user();
        $re_url = X("server.HTTP_REFERER");
        if(strpos($re_url,WWW)!= -1 && strpos($re_url,'/user')===false)
            return header("location: ".$re_url);
        
        $this->message('退出成功',true);


    }
    public function isuser(){
        //{hook a_user_isuser_v}
        $user = X("post.user");
        $bool = M("User")->is_user($user);
        return $this->json(array('error'=>$bool));
    }
    public function isemail(){
        //{hook a_user_isemail_v}
        $email = X("post.email");
        $bool = M("User")->is_email($email);
        return $this->json(array('error'=>$bool));
    }

    //{hook a_user_fun}
}
