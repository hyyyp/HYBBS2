<?php
namespace Action;
use HY\Action;
!defined('HY_PATH') && exit('HY_PATH not defined.');
class Post extends HYBBS {
	public $tid=0;
	public $pid=0;
	public $posts=0;
	public $title;
	public $content;
	public $ante_type=0;
	public function __construct() {
		parent::__construct();
		//{hook a_post_init}
		if(!IS_LOGIN){
			if(IS_AJAX && IS_POST){
				die($this->json(array('error'=>false,'info'=>'请登录后再操作')));
			}
			else{
				header("location: ". HYBBS_URLA('user','login'));
				die;
			}

		}
		
		
	}
	//发表评论
	public function Post(){
		//{hook a_post_post_1}
		$this->v('title','发表评论');
		if(!IS_POST)
			return;
		if($this->_user['ban_post']){
			$this->json(array('error'=>false,'info'=>'您的账号已被禁言!'));
		}

		$UsergroupLib = L("Usergroup");
		//用户组权限判断
		if(!$UsergroupLib->read(NOW_GID,'post',$this->_usergroup))
			return $this->json(array('error'=>false,'info'=>'你当前所在用户组无法发表评论'));

		//{hook a_post_post_2}
		$tid= intval(X("post.id"));
		if(empty($tid))
			return $this->json(array('error'=>false,'info'=>'文章ID不能为空'));
		if(!isset($_POST['content']))
			return $this->json(array('error'=>false,'info'=>'内容不能为空'));
		//{hook a_post_post_3}
		$content = X('post.content');
		if (get_magic_quotes_gpc())
  			$content = stripslashes($content);
		
		if(NOW_GID != C("ADMIN_GROUP")){
			$Kses =L("Kses");
        	$content = $Kses->Parse($content);
		}
		$content = preg_replace('/(<img.*?)((height)=[\'"]+[0-9]+[\'"]+)/is','$1', $content);
		$pattern="/\<img.*?src\=\"(.*?)\"[^>]*>/i";
		preg_match_all($pattern,$content,$match);
		$img_all=[];
		if(isset($match[1][0])){
			foreach ($match[1] as $v) {
				if(substr_count($v,'data:image/') || substr_count($v,';base64') || strpos($v,'/emoji/') !== FALSE || empty($v)){
					continue;
				}
				$img_all[]=$v;
			}
		}

		//去除泰文音标
		$content = preg_replace( '/\p{Thai}/u' , '' , $content );
		$tmp = str_replace('&nbsp;','',$content);
		$tmp = trim(strip_tags($tmp,'<img><iframe><embed><video><audio>'));
		if(empty($tmp) || $tmp == '&nbsp;')
			return $this->json(array('error'=>false,'info'=>'内容不能为空'));
		//{hook a_post_post_4}
		//获取文章数据
		$Thread = M('Thread');
		$thread_data = $Thread->read($tid);
		//锁帖判断
		if($thread_data['state'] && NOW_UID != $thread_data['uid'] && NOW_GID != C("ADMIN_GROUP") && !is_forumg($this->_forum,NOW_UID,$thread_data['fid']))
			return $this->json(array('error'=>false,'info'=>'帖子已经被锁定'));
		if(!L("Forum")->is_comp($thread_data['fid'],NOW_GID,'post',$this->_forum[$thread_data['fid']]['json']))
			return $this->json(array('error'=>false,'info'=>'你没有权限发表'));

		//{hook a_post_post_5}
		$this->tid = $tid;
		$this->posts = $thread_data['posts'];
		$this->title = $thread_data['title'];

		//发送消息摘要
		$this->content = mb_substr(trim(filter_html($content)), 0,$this->conf['summary_size']);

		//{hook a_post_post_6}
		
		//回复评论 非点评评论
		$rpid = intval(X('post.pid',0));

		//写入评论数据
		$Post = S("Post");

		//评论不存在 或 评论是主题内容 无法引用回复
		if(!$Post->has(['pid'=>$rpid]) || $thread_data['pid'] == $rpid)
			$rpid = 0;


		$Post->insert(array(
			'tid'	=> $tid,
			'fid'	=> $thread_data['fid'],
			'uid'	=> NOW_UID,
			'rpid'	=> $rpid,
			'content' => trim($content),
			'atime'	  => NOW_TIME,
			'etime'	  => NOW_TIME
		));
		$this->pid = $pid = $Post->id();
		//{hook a_post_post_61}

		//处理临时文件
		$UserTmpUploadPath 	= 	GetUserTmpUploadPath(NOW_UID);
		$tmp_file_expression= "/src=\"(.*?)\"/i";
		preg_match_all($tmp_file_expression,$content,$matchsrc);
		$SrcFileList=[];
		if(isset($matchsrc[1][0])){
			$SrcFileList=$matchsrc[1];
		}
		//{hook a_post_post_62}
		$MoveFileList = [];
		if(!empty($SrcFileList)){
			$StoragePostDir 		=	GetStoragePostDir($tid,$pid);
			foreach ($SrcFileList as $v) {
				$TmpFilePath = realpath(INDEX_PATH . str_replace(WWW,'',$v));
				$TmpFilePath = str_replace("\\",'/',$TmpFilePath);
				if(strpos($TmpFilePath,$UserTmpUploadPath) !== false){//确定为临时文件
					$NewFilePath = str_replace($UserTmpUploadPath,$StoragePostDir,$TmpFilePath);
					//移动临时文件到正式目录
					if(move_file($TmpFilePath, $NewFilePath)){
						$MoveFileList[] = $NewFilePath;
					}
					
				}
			}
			//替换临时文件路径为正式文件路径
			$content = str_replace($UserTmpUploadPath,$StoragePostDir,$content);
		}

		//{hook a_post_post_63}
		if(!empty($MoveFileList)){
        	$File=M('File');
        	foreach ($MoveFileList as $v) {
				$FileInfo = pathinfo($v);
				$FileName = $FileInfo['filename'];
				$File->update([
					'tid'	=> $tid,
					'pid'	=> $pid,
				],[
					'AND'=>['uid'=>NOW_UID,'md5'=>$FileName]
				]);
            }
        }
        //{hook a_post_post_64}
		//@用户
		$this->ante_type = 'post';
		if($UsergroupLib->read(NOW_GID,'mess',$this->_usergroup))
			$content = $this->ante($content);
		$Post->update(['content'=>$content],['pid'=>$pid]);


		//{hook a_post_post_66}
		//分类 评论数量+1
		M("Forum")->update_int($thread_data['fid'],'posts');
		$this->_forum[$thread_data['fid']]['posts']++;
		$this->CacheObj->forum = $this->_forum;
		$this->_count['post']++;
		$this->_count['day_post']++;
		$this->CacheObj->bbs_count = $this->_count;
		if($thread_data['top']==1) //如果是板块置顶帖子，清理该板块置顶帖子缓存
			$this->CacheObj->rm("forum_top_id_".$thread_data['fid']);
		elseif($thread_data['top']==2)
			$this->CacheObj->rm("top_data_2");
		//{hook a_post_post_7}
		//更新主题 回复帖子数
		$Thread->update([
			'posts'=>$Post->count(['tid'=>$tid])-1, //评论数+1
			'btime'=>NOW_TIME, // 最后评论过时间
			'buid'=>NOW_UID, //最后回复者用户ID
		],[
			'tid'=>$tid
		]);
		//{hook a_post_post_8}
		$User = M("User");
		//用户评论数+1
		$User->update_int(NOW_UID,'posts','+');
		//增加金币
		$User->update_int(NOW_UID,'gold','+',$this->conf['gold_post']);
		//增加积分
		$User->update_int(NOW_UID,'credits','+',$this->conf['credits_post']);
		$this->_user['posts']++;
		if($thread_data['uid'] != NOW_UID){
			M("Chat")->sys_send($thread_data['uid'],'<a href="'. HYBBS_URLA('my',NOW_USER).'" target="_blank">['.NOW_USER.']</a> 回复了你的主题 <a href="'. HYBBS_URLA('thread',$thread_data['tid']).'" target="_blank">['.$thread_data['title'].']</a>');
		}
		if($this->conf['gold_post'] != 0 || $this->conf['credits_post'] != 0){
			S("Log")->insert(array(
				'uid'=>NOW_UID,
				'gold'=>$this->conf['gold_post'],
				'credits'=>$this->conf['credits_post'],
				'content'=>'发表评论 文章ID['.$thread_data['tid'].']',
				'atime'=>NOW_TIME
			));
		}
		//{hook a_post_post_9}

		if($thread_data['top'] == 2)
			$this->CacheObj->rm("top_data_2");
		elseif($thread_data['top'] == 1)
			$this->CacheObj->rm("forum_top_id_".$thread_data['fid']);

		$this->CacheObj->rm("index_index_Btime_1");
		$this->CacheObj->rm("index_index_{$thread_data['fid']}_1_Btime");
		$this->CacheObj->rm('thread_data_'.$tid);
		//{hook a_post_post_10}
		$count = intval(($thread_data['posts'] /  $this->conf['postlist']) + 1)+1;
        for ($i=0; $i < $count; $i++) {
            $this->CacheObj->rm("post_list_{$tid}_DESC_{$i}");
            $this->CacheObj->rm("post_list_{$tid}_ASC_{$i}");
        }
        //{hook a_post_post_11}

		//用户组升级检测
		M('Usergroup')->check_up(NOW_UID);
		

		//{hook a_post_post_v}
		return $this->json(array('error'=>true,'info'=>'发表成功'));

	}
	//发表主题
	public function Index(){
		//{hook a_post_index_1}
		$this->v('title','发表主题');
		if($this->_user['ban_post']){
			return $this->message('您的账号已被禁言!');
		}
        if(IS_GET){ //显示发表主题模板
			//{hook a_post_index_2}
          
			//{hook a_post_index_3}
            
    		$this->display('post_index');
        }elseif(IS_POST){ //POST发表主题
			//{hook a_post_index_4}
			$UsergroupLib = L("Usergroup");

			if(!$UsergroupLib->read(NOW_GID,'thread',$this->_usergroup))
				return $this->json(array('error'=>false,'info'=>'你当前所在用户组无法发表主题'));


			//获取提交数据
            $forum = X("post.forum",'-1');
            $title = trim(X("post.title"));
            $title = htmlspecialchars($title);
            $tgold = intval(X("post.tgold"));
            $thide = intval(X("post.thide"));
            


            //{hook a_post_index_44}
            if(!$UsergroupLib->read(NOW_GID,'thide',$this->_usergroup)){
            	if($thide)
            		return $this->json(array('error'=>false,'info'=>'你所在用户组无法隐藏帖子'));
            	$thide = 0;
            }
            if(!$UsergroupLib->read(NOW_GID,'tgold',$this->_usergroup)){
            	if($tgold)
            		return $this->json(array('error'=>false,'info'=>'你所在用户组无法设置金币付费帖子'));
            	$tgold = 0;
            }
            //{hook a_post_index_55}

            //去除泰文音标
			$title = preg_replace( '/\p{Thai}/u' , '' , $title );
			$this->title=$title;

            $content = X('post.content');
            if (get_magic_quotes_gpc())
  				$content = stripslashes($content);
            
			if(NOW_GID != C("ADMIN_GROUP")){
				$Kses =L("Kses");
        		$content = $Kses->Parse($content);
			}
            $content=preg_replace( '/\p{Thai}/u' , '' , $content );

            //{hook a_post_index_5}
			$tmp = str_replace('&nbsp;','',$content);
			$tmp = trim(strip_tags($tmp,'<img><iframe><embed><video><audio>'));
            if(empty($tmp))
				return $this->json(array('error'=>false,'info'=>'内容不能为空'));

			//{hook a_post_index_6}
            if(mb_strlen($title) < $this->conf['titlemin'])
				return $this->json(array('error'=>false,'info'=>'标题长度不能小于'.$this->conf['titlemin'].'个字符'));
			if(mb_strlen($title) > $this->conf['titlesize'])
				return $this->json(array('error'=>false,'info'=>'标题长度不能大于'.$this->conf['titlesize'].'个字符'));
			if($forum < 0 ){
				return $this->json(array('error'=>false,'info'=>'请选择一个分类'));
			}
			//{hook a_post_index_7}
			//用户组在分类下的权限判断
			if(!isset($this->_forum[$forum])){
				if(empty($this->_forum[$forum]['id']))
					return $this->json(array('error'=>false,'info'=>'不存在该分类'));
			}
			//{hook a_post_index_8}
			if(!L("Forum")->is_comp($forum,NOW_GID,'thread',$this->_forum[$forum]['json']))
				return $this->json(array('error'=>false,'info'=>'你没有权限在该板块发表帖子'));
			//{hook a_post_index_88}
          	
          	//去除图片自定义高度
			$content = preg_replace('/(<img.*?)((height)=[\'"]+[0-9]+[\'"]+)/is','$1', $content);
			//{hook a_post_index_9}
            //获取所有图片地址
			$pattern="/\<img.*?src\=\"(.*?)\"[^>]*>/i";
			preg_match_all($pattern,$content,$match);
			$img = '';
			$sz=0;
			$img_all=[];
			if(isset($match[1][0])){
				foreach ($match[1] as $v) {
					if(substr_count($v,'data:image/') || substr_count($v,';base64') || strpos($v,'/emoji/') !== FALSE || empty($v)){
						continue;
					}
					$img_all[]=$v;
					if($sz++<$this->conf['post_image_size']){
						$img.=$v;
						$img.=",";
					}
				}
			}
			
			//发送消息 摘要
			$this->content = mb_substr(trim(filter_html($content)), 0,$this->conf['summary_size']);

			//{hook a_post_index_10}
			
			
			//主题数据
            $Thread = S("Thread");
            $Thread->insert(array(
                'fid'=>$forum,
                'uid'=>NOW_UID,
                'title'=>$title,
                'summary'=>mb_substr(trim(filter_html($content)), 0,$this->conf['summary_size']),
				'atime'	=>NOW_TIME,
				'etime'	=>NOW_TIME,
				'btime'	=>NOW_TIME,
				'img'	=>'',
				'img_count'	=>$sz,
				'hide'	=>$thide?1:0,
				'gold'	=>$tgold,
            ));
            $tid=0;
            $this->tid = $tid = $Thread->id();
            //{hook a_post_index_100}
            
            //@用户
			$this->ante_type = 'thread';
			if($UsergroupLib->read(NOW_GID,'mess',$this->_usergroup))
				$content = $this->ante($content); //@ 用户函数



			//处理临时文件
			$UserTmpUploadPath 	= 	GetUserTmpUploadPath(NOW_UID);
			$tmp_file_expression= "/src=\"(.*?)\"/i";
			preg_match_all($tmp_file_expression,$content,$matchsrc);
			$SrcFileList=[];
			if(isset($matchsrc[1][0])){
				$SrcFileList=$matchsrc[1];
			}
			//{hook a_post_index_101}
			$MoveFileList = [];
			if(!empty($SrcFileList)){
				$StorageThreadDir 		=	GetStorageThreadDir($tid);
				foreach ($SrcFileList as $v) {
					$TmpFilePath = realpath(INDEX_PATH . str_replace(WWW,'',$v));
					$TmpFilePath = str_replace("\\",'/',$TmpFilePath);
					if(strpos($TmpFilePath,$UserTmpUploadPath) !== false){//确定为临时文件
						$NewFilePath = str_replace($UserTmpUploadPath,$StorageThreadDir,$TmpFilePath);
						
						//移动临时文件到正式目录
						if(move_file($TmpFilePath, $NewFilePath)){ //移动成功
							$MoveFileList[] = $NewFilePath;
						}

					}

				}
				
				//替换临时文件路径为正式文件路径
				$content = str_replace($UserTmpUploadPath,$StorageThreadDir,$content);
				$img 	 = str_replace($UserTmpUploadPath,$StorageThreadDir,$img);
			}
			//{hook a_post_index_102}
            

            //主题帖子数据
            $Post = S("Post");
            $Post->insert(array(
				'tid'	=> $tid,
				'fid'	=>$forum,
				'uid'	=> NOW_UID,
				'isthread'=> 1,
				'content' => $content,
				'atime'	  => NOW_TIME
            ));
            $pid = $Post->id();

            if(!empty($MoveFileList)){
            	$File=M('File');
            	foreach ($MoveFileList as $v) {
					$FileInfo = pathinfo($v);
					$FileName = $FileInfo['filename'];
					$File->update([
						'tid'	=> $tid,
						'pid'	=> $pid,
					],[
						'AND'=>['uid'=>NOW_UID,'md5'=>$FileName]
					]);
	            }
            }
            

            //{hook a_post_index_11}

            $files=0;
            //是否有权限上传附件
            if($UsergroupLib->read(NOW_GID,'uploadfile',$this->_usergroup)){

	            //处理附件
	            $fileid 	= X("post.fileid");
	            $filegold 	= X("post.filegold");
	            $filemess 	= X("post.filemess");
	            $filehide 	= X("post.filehide");
	            //{hook a_post_index_12}

	            if(!empty($fileid)){
	            	//{hook a_post_index_13}
	            	$fileid_arr 	= explode("||",$fileid);
	            	$filegold_arr 	= explode("||",$filegold);
	            	$filemess_arr 	= explode("||",$filemess);
	            	$filehide_arr 	= explode("||",$filehide);

	            	if(count($fileid_arr)){
	            		//{hook a_post_index_14}

	            		$File = M("File");
	            		$Fileinfo = S("Fileinfo");
	            		$MoveFileList = [];
	            		foreach ($fileid_arr as $key => $v) {
	            			//{hook a_post_index_15}
	            			$v=intval($v);
	            			if(empty($v))
	            			{
	            				
	            				continue;
	            			}
	            			//判断附件ID 是否属于 发帖者
	            			if($File->is_comp($v,NOW_UID)){
	            				$files++;
	            				$Fileinfo->insert(array(
	            					'fileid'	=>	$v,
	            					'tid'		=>	$tid,
	            					'uid'		=>	NOW_UID,
	            					'gold'		=>	isset($filegold_arr[$key]) ? intval($filegold_arr[$key]) : 0,
	            					'hide'		=>	isset($filehide_arr[$key]) ? intval($filehide_arr[$key]) : 0,
	            					'mess'		=>	isset($filemess_arr[$key]) ? filter_html($filemess_arr[$key]) : '',
	            				));
	            				//{hook a_post_index_151}
	            				$FileMd5Name 	= $File->get_row($v,'md5name');
	            				$FileTmpPath 	= INDEX_PATH . $UserTmpUploadPath . $FileMd5Name;
	            				$StorageThreadFileDir = GetStorageThreadFileDir($tid);
	            				
	            				if(move_file($FileTmpPath, INDEX_PATH . $StorageThreadFileDir . $FileMd5Name)){
	            					$MoveFileList[] = INDEX_PATH . $StorageThreadFileDir . $FileMd5Name;
	            					$FileInfo = pathinfo($FileMd5Name);
									$FileName = $FileInfo['filename'];
		            				$File->update([
										'tid'	=> $tid,
										'pid'	=> $pid,
									],[
										'AND'=>['uid'=>NOW_UID,'md5'=>$FileName]
									]);
	            				}
	            				//{hook a_post_index_152}
	            			}
	            			//{hook a_post_index_153}
	            		}
	            		//{hook a_post_index_16}
	            	}
	            }//处理附件结束

            }
            $Thread->update(['pid'=>$pid,'img'=>$img,'files'=>$files],['tid'=>$tid]);
            //{hook a_post_index_17}



			$User = M("User");
			//用户增加 主题数
			$User->update_int(NOW_UID,'threads','+');

			//用户增加 金钱
			$User->update_int(NOW_UID,'gold','+',$this->conf['gold_thread']);
			//用户增加 积分
			$User->update_int(NOW_UID,'credits','+',$this->conf['credits_thread']);
			//{hook a_post_index_18}
			if($this->conf['gold_thread'] != 0 || $this->conf['credits_thread'] != 0){
				S("Log")->insert(array(
					'uid'=>NOW_UID,
					'gold'=>$this->conf['gold_thread'],
					'credits'=>$this->conf['credits_thread'],
					'content'=>'发表文章 文章ID['.$tid.']',
					'atime'=>NOW_TIME
				));
			}

			//分类板块 帖子数量++
			M("Forum")->update_int($forum);
			//{hook a_post_index_19}
			//更新分类缓存
			$this->_forum[$forum]['threads']++;
			$this->CacheObj->forum = $this->_forum;
			//更新统计缓存
			$this->_count['thread']++;
			$this->_count['day_thread']++;
			$this->CacheObj->bbs_count = $this->_count;

			$this->_user['threads']++;

			//删除第一页缓存
			$this->CacheObj->rm("index_index_New_1");
			$this->CacheObj->rm("index_index_Btime_1");

			$this->CacheObj->rm("index_index_{$forum}_1_Btime");
			$this->CacheObj->rm("index_index_{$forum}_1_New");
			
			//{hook a_post_index_20}

			//用户组升级检测
			M('Usergroup')->check_up(NOW_UID);

			//删除用户当前临时文件夹
			deldir($UserTmpUploadPath,false,true);

			//{hook a_post_index_v}
            $this->json(array('error'=>true,'info'=>'发表成功','id'=>$tid));

        }

	}

	//发表子评论
	public function post_post(){
		//{hook a_post_post_post_0}
		if(IS_POST && IS_AJAX){
			//{hook a_post_post_post_1}
			if($this->_user['ban_post']){
				$this->json(array('error'=>false,'info'=>'您的账号已被禁言!'));
			}
			//{hook a_post_post_post_2}
			$pid = intval(X('post.pid'));
			$content = trim(X('post.content'));
			$content = str_replace(['<div><br></div>','<div>','</div>','<p>','</p>'],"\n",$content);
			$content = strip_tags($content); //去除HTML标签并
			$content = preg_replace( '/\p{Thai}/u' , '' , $content );
			$content = str_replace(["\n\n","\n"],"<br>",$content);
			$content = str_replace("<br><br>","<br>",$content);
			$content = trim($content);
			//{hook a_post_post_post_3}
			
			if(substr($content,-4) == '<br>')
				$content = substr($content,0,-4);
			if($content == '' || !mb_strlen($content))
				$this->json(['error'=>false,'info'=>'请输入提交内容']);
			
			$Post = M('Post');
			if(!$Post->is_pid($pid))
				$this->json(['error'=>false,'info'=>'该帖子已被删除，无法评论！']);
			//{hook a_post_post_post_4}

			$post_data = $Post->get_row($pid,['tid','uid','content']);
			$tid = $post_data['tid'];

			$this->title = mb_substr(trim(strip_tags($post_data['content'])), 0,50);

			if(!$tid)
				$this->json(['error'=>false,'info'=>'无法找到原主题数据，无法评论！']);
			//{hook a_post_post_post_5}
			$this->pid = $pid;

			
			//@用户
			$this->ante_type = 'post_post';
			if(L("Usergroup")->read(NOW_GID,'mess',$this->_usergroup))
				$content = $this->ante($content);
			//{hook a_post_post_post_6}
			$Thread = M('Thread');
			$Post_post = S('Post_post');
			$Post_post->insert([
				'pid'=>$pid,
				'tid'=>$tid,
				'uid'=>NOW_UID,
				'content'=>$content,
				'atime'=>NOW_TIME,
			]);
			//{hook a_post_post_post_7}
			$Post->update(['posts[+]'=>1],['pid'=>$pid]);
			M('User')->update(['post_ps[+]'=>1],['uid'=>NOW_UID]);
			$data = [
				'avatar'=>$this->avatar(NOW_UID),
				'user'=>NOW_USER,
				'uid'=>NOW_UID,
				'content'=>$content
			];
			//{hook a_post_post_post_8}
			if(NOW_UID != $post_data['uid']){
				M("Chat")->sys_send(
					$post_data['uid'],
					'<a href="'. HYBBS_URLA('my',NOW_USER) .'" target="_blank">['.NOW_USER.']</a> 评论了你的回复 <a href="'. HYBBS_URLA('thread','post',$pid).'" target="_blank">['.mb_substr(strip_tags($post_data['content']),0,25).']</a>'
				);
			}
			//{hook a_post_post_post_v}
			$this->json(['error'=>true,'info'=>'发表成功！','data'=>$data]);
		}
	}

	//@事件
	private function ante($content){
		//{hook a_post_ante_1}
		return preg_replace_callback('/@([^:|： @<&])+/',array($this, 'ante_callback'),$content);
	}
	private function ante_callback($tagStr){
		//{hook a_post_ante_callback_1}
		if(is_array($tagStr)) $tagStr = $tagStr[0];

		$tagStr = stripslashes($tagStr);
		$user = substr($tagStr,1);
		$User = M("User");
		$Chat = M("Chat");
		//{hook a_post_ante_callback_2}
		static $tmp_user=array(); //@发送一次
		if($user != NOW_USER){ //不能发送给自己
			if(!isset($tmp_user[$user])){ //本帖未@过该用户名
				if($User->is_user($user)/* && isset($tmp_user[$user])*/){ //判断用户是否存在
					//{hook a_post_ante_callback_3}
					$tmp_user[$user]=true;
					if($this->ante_type == 'thread')
						$Chat->sys_send($User->user_to_uid($user),'<a href="'. HYBBS_URLA('my',NOW_USER).'" target="_blank">['.NOW_USER.']</a> 在发表主题 <a href="'. HYBBS_URLA('thread',$this->tid).'" target="_blank">['.$this->title.']</a> 的时候@了你');
					elseif($this->ante_type == 'post')
						$Chat->sys_send($User->user_to_uid($user),'<a href="'. HYBBS_URLA('my',NOW_USER).'" target="_blank">['.NOW_USER.']</a> 在评论 <a href="'. HYBBS_URLA('thread','post',$this->pid).'" target="_blank">['.$this->title.']</a> 的时候@了你');
					elseif($this->ante_type == 'post_post')
						$Chat->sys_send($User->user_to_uid($user),'<a href="'. HYBBS_URLA('my',NOW_USER).'" target="_blank">['.NOW_USER.']</a> 回复评论 <a href="'. HYBBS_URLA('thread','post',$this->pid).'" target="_blank">['.$this->title.']</a> 的时候@了你');
					//{hook a_post_ante_callback_4}
				}
			}
			//{hook a_post_ante_callback_5}
			return '<span class="label label-primary">'.$tagStr.'</span>';
		}
		return $tagStr;
	}
	//附件上传
	public function uploadfile(){
		//{hook a_post_uploadfile_1}
		//检测当前用户组是否有权限上传
		$UsergroupLib = L("Usergroup");
		if(!$UsergroupLib->read(NOW_GID,'uploadfile',$this->_usergroup))
			$this->json(array('error'=>false,'info'=>'你没有权限上传附件'));
		
		//{hook a_post_uploadfile_2}
		$UserTmpUploadPath = GetUserTmpUploadPath(NOW_UID);
		$upload = new \Lib\Upload();
        $upload->maxSize   	=  ($this->conf['uploadfilemax']*1024)*1024 ;// 设置附件上传大小，单位b
        $upload->exts      	=  explode(",",$this->conf['uploadfileext']);// 设置附件上传类型
        $upload->rootPath  	=  INDEX_PATH . $UserTmpUploadPath;; // 设置附件上传根目录
        $upload->replace    =  true;
        $upload->autoSub    =  false;
        $upload->saveName   =  md5(NOW_USER . NOW_TIME.mt_rand(1,9999));
        //{hook a_post_uploadfile_3}

        $info   =   $upload->upload();
        //{hook a_post_uploadfile_4}
        if($info) {
        	$file_size = $info['photo']['size'] / 1024; //得到kb单位
			if($file_size < 1 && $file_size > 0) //如果值为 0.x 则算作 1kb
				$file_size = 1;
        	if($this->_user['file_size'] + $file_size >= $this->_usergroup[NOW_GID]['space_size'])
				return $this->json(array("error"=>false,'info'=>"您已经没有空间上传文件了!需要提升用户组哦!"));

        	$File = S('File');
        	$File->insert(array(
        		'uid'		=>	NOW_UID,
        		'filename'	=>	filter_html(isset($info['photo'])?$info['photo']['name']:'未命名.'.$info['photo']['ext']),
        		'md5name'	=>	$upload->saveName.'.'.$info['photo']['ext'],
        		'md5'		=>	$upload->saveName,
        		'filesize'	=>	$info['photo']['size'],
        		'file_type' =>	2,//附件
        		'atime'		=>	NOW_TIME
        	));
        	$id = $File->id();
        	
			M("User")->update_int(NOW_UID,'file_size','+',$file_size);

        	//{hook a_post_uploadfile_5}
        	$this->json(array('error'=>true,'info'=>"上传成功",'id'=>$id,'name'=>$info['photo']['name']));
        }
        //{hook a_post_uploadfile_v}
        $this->json(array('error'=>false,"info"=>$upload->getError()));
        
	}
	//图片上传
	public function upload(){
		//{hook a_post_upload_1}
		$UsergroupLib = L("Usergroup");
		if(!$UsergroupLib->read(NOW_GID,'upload',$this->_usergroup))
			return $this->json(array("success"=>false,'msg'=>"用户组禁止上传图片!",'state'=>"用户组禁止上传图片!","file_path"=>''));

		//{hook a_post_upload_2}
		$UserTmpUploadPath = GetUserTmpUploadPath(NOW_UID);
		$upload = new \Lib\Upload();// 实例化上传类
        $upload->maxSize   	=  ($this->conf['uploadimagemax']*1024)*1024 ;// 设置附件上传大小，单位b
        $upload->exts      	=  explode(",",$this->conf['uploadimageext']);// 设置图片上传类型
        $upload->rootPath  	=  INDEX_PATH . $UserTmpUploadPath; // 设置图片上传根目录
        $upload->replace    =  true;
        $upload->autoSub    =  false;
        $upload->saveName   =  md5(NOW_USER . NOW_TIME.mt_rand(1,9999)); //保存文件名

        //{hook a_post_upload_22}

		//{hook a_post_upload_3}
		$info   =   $upload->upload();
		//{hook a_post_upload_4}
		
		$d=array("success"=>true,'msg'=>"上传成功!","file_path"=>'');
		if(!$info) {
			//{hook a_post_upload_41}
			$d['success']	= false;
        	$d['msg']		= $upload->getError();

		}else{ //上传成功
			//{hook a_post_upload_31}
			$file_size = $info['photo']['size'] / 1024;
			//得到kb单位
			if($file_size < 1 && $file_size > 0) //如果值为 0.x 则算作 1kb
				$file_size = 1;
			if($this->_user['file_size'] + $file_size  > $this->_usergroup[NOW_GID]['space_size']){
				return $this->json(array("success"=>false,'msg'=>"您已经没有空间上传文件了!需要提升用户组哦!",'state'=>"您已经没有空间上传文件了!需要提升用户组哦!","file_path"=>''));
			}
			//{hook a_post_upload_32}
			$File = S('File');
        	$File->insert(array(
        		'uid'		=>	NOW_UID,
        		'filename'	=>	filter_html(isset($info['photo'])?$info['photo']['name']:'未命名.'.$info['photo']['ext']),
        		'md5name'	=>	$upload->saveName.'.'.$info['photo']['ext'],
        		'md5'		=>	$upload->saveName,
        		'filesize'	=>	$info['photo']['size'],
        		'file_type'	=>	1,//图片
        		'atime'		=>	NOW_TIME
        	));
        	//{hook a_post_upload_33}
			$d['file_path'] = WWW . $UserTmpUploadPath .$info['photo']['savename'];
			
			M("User")->update_int(NOW_UID,'file_size','+',$file_size);

		}
		//{hook a_post_upload_v}
		if(X("post.geturl") == '1')
			die($d['file_path']);
		$this->json($d);

	}
	//视频上传
	public function uploadvideo(){
		//{hook a_post_uploadvideo_1}
		if(!$this->conf['allow_upload_video'])
			$this->json(["error"=>false,'info'=>"网站未开启视频上传功能!"]);
		$UsergroupLib = L("Usergroup");
		if(!$UsergroupLib->read(NOW_GID,'uploadvideo',$this->_usergroup))
			$this->json(["error"=>false,'info'=>"您所在用户组禁止上传视频!"]);

		//{hook a_post_uploadvideo_2}
		$UserTmpUploadPath = GetUserTmpUploadPath(NOW_UID);
		$upload = new \Lib\Upload();// 实例化上传类
        $upload->maxSize   	=  ($this->conf['upload_video_size']*1024)*1024 ;// 设置上传大小，单位b
        $upload->exts      	=  explode(",",$this->conf['upload_video_ext']);// 设置上传类型
        $upload->rootPath  	=  INDEX_PATH . $UserTmpUploadPath; // 设置上传根目录
        $upload->replace    =  true;
        $upload->autoSub    =  false;
        $upload->saveName   =  md5(NOW_USER . NOW_TIME . mt_rand(1,9999)); //保存文件名

		//{hook a_post_uploadvideo_3}
		$info   =   $upload->upload();
		//{hook a_post_uploadvideo_4}
		
		$json=array("error"=>true,'info'=>"上传成功!");
		if(!$info) {
			//{hook a_post_uploadvideo_31}
			$json['error']	= false;
        	$json['info']		= $upload->getError();

		}else{ //上传成功
			//{hook a_post_uploadvideo_32}
			$file_size = $info['video']['size'] / 1024;
			//得到kb单位
			if($file_size < 1 && $file_size > 0) //如果值为 0.x 则算作 1kb
				$file_size = 1;
			if($this->_user['file_size'] + $file_size  > $this->_usergroup[NOW_GID]['space_size']){
				return $this->json(["error"=>false,'info'=>"您已经没有空间上传文件了!需要提升用户组哦!"]);
			}
			//{hook a_post_uploadvideo_33}
			$File = S('File');
        	$File->insert(array(
        		'uid'		=>	NOW_UID,
        		'filename'	=>	filter_html(isset($info['video'])?$info['video']['name']:'未命名.'.$info['video']['ext']),
        		'md5name'	=>	$upload->saveName.'.'.$info['video']['ext'],
        		'md5'		=>	$upload->saveName,
        		'filesize'	=>	$info['video']['size'],
        		'file_type'	=>	3,//视频
        		'atime'		=>	NOW_TIME
        	));
			$json['file_path'] = WWW . $UserTmpUploadPath .$info['video']['savename'];
			//{hook a_post_uploadvideo_34}
			M("User")->update_int(NOW_UID,'file_size','+',$file_size);

		}
		$this->json($json);
		//{hook a_post_uploadvideo_v}
	}
	//音频上传
	public function uploadaudio(){
		//{hook a_post_uploadaudio_1}
		if(!$this->conf['allow_upload_audio'])
			$this->json(["error"=>false,'info'=>"网站未开启音频上传功能!"]);
		$UsergroupLib = L("Usergroup");
		if(!$UsergroupLib->read(NOW_GID,'uploadaudio',$this->_usergroup))
			$this->json(["error"=>false,'info'=>"您所在用户组禁止上传音频!"]);

		//{hook a_post_uploadaudio_2}
		$UserTmpUploadPath = GetUserTmpUploadPath(NOW_UID);
		$upload = new \Lib\Upload();// 实例化上传类
        $upload->maxSize   	=  ($this->conf['upload_audio_size']*1024)*1024 ;// 设置上传大小，单位b
        $upload->exts      	=  explode(",",$this->conf['upload_audio_ext']);// 设置上传类型
        $upload->rootPath  	=  INDEX_PATH . $UserTmpUploadPath; // 设置上传根目录
        $upload->replace    =  true;
        $upload->autoSub    =  false;
        $upload->saveName   =  md5(NOW_USER . NOW_TIME . mt_rand(1,9999)); //保存文件名

		//{hook a_post_uploadaudio_3}
		$info   =   $upload->upload();
		//{hook a_post_uploadaudio_4}
		
		$json=array("error"=>true,'info'=>"上传成功!");
		if(!$info) {
			//{hook a_post_uploadaudio_31}
			$json['error']	= false;
        	$json['info']		= $upload->getError();

		}else{ //上传成功
			//{hook a_post_uploadaudio_32}
			$file_size = $info['audio']['size'] / 1024;
			//得到kb单位
			if($file_size < 1 && $file_size > 0) //如果值为 0.x 则算作 1kb
				$file_size = 1;
			if($this->_user['file_size'] + $file_size  > $this->_usergroup[NOW_GID]['space_size']){
				return $this->json(["error"=>false,'info'=>"您已经没有空间上传文件了!需要提升用户组哦!"]);
			}
			//{hook a_post_uploadaudio_33}
			$File = S('File');
        	$File->insert(array(
        		'uid'		=>	NOW_UID,
        		'filename'	=>	filter_html(isset($info['audio'])?$info['audio']['name']:'未命名.'.$info['audio']['ext']),
        		'md5name'	=>	$upload->saveName.'.'.$info['audio']['ext'],
        		'md5'		=>	$upload->saveName,
        		'filesize'	=>	$info['audio']['size'],
        		'file_type'	=>	4,//音频
        		'atime'		=>	NOW_TIME
        	));
        	//{hook a_post_uploadaudio_34}
			$json['file_path'] = WWW . $UserTmpUploadPath .$info['audio']['savename'];
			
			M("User")->update_int(NOW_UID,'file_size','+',$file_size);

		}
		$this->json($json);
		//{hook a_post_uploadaudio_v}
	}
	//编辑帖子
	public function edit(){
		//{hook a_post_edit_1}
		$this->v('title','编辑帖子内容');
		if(IS_POST){
			//{hook a_post_edit_2}
			$pid = intval(X("post.id"));
			$content = X('post.content');
			if (get_magic_quotes_gpc())
  				$content = stripslashes($content);
			
			if(NOW_GID != C("ADMIN_GROUP")){
				$Kses 	 = L("Kses");
        		$content = $Kses->Parse($content);
			}

			//$content = preg_replace('/(<img.*?)((width)=[\'"]+[0-9]+[\'"]+)/is','$1', $content);
			$content = preg_replace('/(<img.*?)((height)=[\'"]+[0-9]+[\'"]+)/is','$1', $content);
			$content = preg_replace( '/\p{Thai}/u' , '' , $content );
			$tmp = str_replace('&nbsp;','',$content);
			$tmp = trim(strip_tags($tmp,'<img><iframe><embed><video><audio>'));
			if(empty($tmp))
				return $this->json(array('error'=>false,'info'=>'内容不能为空'));
			//{hook a_post_edit_3}
			$Post = M("Post");
			$post_data = $Post->read($pid);
			if(empty($post_data))
				return $this->json(array('error'=>false,'info'=>'评论不存在'));
        	//{hook a_post_edit_33}
			//评论数据不存在 或者 评论不属于当前登陆者 或者 登陆者不是管理员
			if(
				
				$post_data['uid'] != NOW_UID && //编辑者不属于帖子作者
				NOW_GID != C("ADMIN_GROUP") &&  //不属于管理员
				!is_forumg($this->_forum,NOW_UID,$post_data['fid']) //不是版主
			)
				return $this->json(array('error'=>false,'info'=>'太坏了,你居然想修改别人帖子'));

			$isthread 	= $post_data['isthread'];
			$tid 		= $post_data['tid'];
			//{hook a_post_edit_34}
			//修改主题 评论是主题内容
			if($isthread){
				//{hook a_post_edit_35}
				$fid = intval(X("post.fid"));
				$title = trim(X("post.title"));
				$title = htmlspecialchars($title);
				$title = preg_replace( '/\p{Thai}/u' , '' , $title );
				if(mb_strlen($title) < $this->conf['titlemin'])
					return $this->json(array('error'=>false,'info'=>'标题长度不能小于'.$this->conf['titlemin'].'个字符'));
				if(mb_strlen($title) > $this->conf['titlesize'])
					return $this->json(array('error'=>false,'info'=>'标题长度不能大于'.$this->conf['titlesize'].'个字符'));
				
				if($fid < 0 ){
					return $this->json(array('error'=>false,'info'=>'请选择一个分类,板块'));
				}
				//{hook a_post_edit_36}
	            if(!isset($this->_forum[$fid])){
					if(empty($this->_forum[$fid]['id']))
						return $this->json(array('error'=>false,'info'=>'不存在该分类'));
				}
				if(!L("Forum")->is_comp($fid,NOW_GID,'thread',$this->_forum[$fid]['json']))
					return $this->json(array('error'=>false,'info'=>'你没有权限在该板块发表帖子'));

				$tgold = intval(X("post.tgold"));
            	$thide = intval(X("post.thide"));
            	$UsergroupLib = L("Usergroup");
            	$User = M('User');
            	if(!$UsergroupLib->read(NOW_GID,'thide',$this->_usergroup)){
	            	$thide = 0;
	            }
	            if(!$UsergroupLib->read(NOW_GID,'tgold',$this->_usergroup)){
	            	$tgold = 0;
	            }
	            //{hook a_post_edit_37}

	            //获取所有图片地址
				$pattern="/\<img.*?src\=\"(.*?)\"[^>]*>/i";
				preg_match_all($pattern,$content,$match);
				$img = '';
				$sz=0;
				$img_all=[];
				if(isset($match[1][0])){
					foreach ($match[1] as $v) {
						if(substr_count($v,'data:image/')  || substr_count($v,';base64') || strpos($v,'/emoji/') !== FALSE || empty($v))
							continue;
						$img_all[]=$v;
						if($sz++<$this->conf['post_image_size']){
							$img.=$v;
							$img.=",";
						}
					}
				}

				
				
				//{hook a_post_edit_371}
				//处理临时文件
				$UserTmpUploadPath 	= GetUserTmpUploadPath(NOW_UID);
				$tmp_file_expression= "/src=\"(.*?)\"/i";
				preg_match_all($tmp_file_expression,$content,$matchsrc);
				$SrcFileList=[];
				if(isset($matchsrc[1][0])){
					$SrcFileList=$matchsrc[1];
				}
				//{hook a_post_edit_372}
				$MoveFileList = [];
				if(!empty($SrcFileList)){
					$StorageThreadDir 		=	GetStorageThreadDir($tid);
					foreach ($SrcFileList as $v) {
						$TmpFilePath = realpath(INDEX_PATH . str_replace(WWW,'',$v));
						$TmpFilePath = str_replace("\\",'/',$TmpFilePath);
						if(strpos($TmpFilePath,$UserTmpUploadPath) !== false){//确定为临时文件
							$NewFilePath = str_replace($UserTmpUploadPath,$StorageThreadDir,$TmpFilePath);
							//移动临时文件到正式目录
							if(move_file($TmpFilePath, $NewFilePath)){
								$MoveFileList[] = $NewFilePath;
							}

						}
					}
					//替换临时文件路径为正式文件路径
					$content = str_replace($UserTmpUploadPath,$StorageThreadDir,$content);
					$img 	 = str_replace($UserTmpUploadPath,$StorageThreadDir,$img);
				}
				//{hook a_post_edit_373}
				//更新文件使用帖子
				$File=M('File');
				if(!empty($MoveFileList)){
	            	foreach ($MoveFileList as $v) {
						$FileInfo = pathinfo($v);
						$FileName = $FileInfo['filename'];
						$File->update([
							'tid'	=> $tid,
							'pid'	=> $pid,
						],[
							'AND'=>['uid'=>NOW_UID,'md5'=>$FileName]
						]);
		            }
	            }
	            //{hook a_post_edit_374}
				//处理不使用的旧文件
				$StorageThreadDir = GetStorageThreadDir($tid,false);
				if(is_dir(INDEX_PATH . $StorageThreadDir)){
					$dh = opendir(INDEX_PATH . $StorageThreadDir);

					while ($filename = readdir($dh)) {
						$fullpath = INDEX_PATH . $StorageThreadDir . $filename;
						if ($filename != "." && $filename != ".." && !is_dir($fullpath)) {
							$IsDel = true;
							foreach ($SrcFileList as $v) {
								if(strpos($v,$filename) !== false){ //正在使用
									$IsDel = false;
									break;
								}
							}
							if($IsDel){ //删除不使用的文件
								delete_file($fullpath);
								$FileInfo = pathinfo($fullpath);
								$FileName = $FileInfo['filename'];
								$File->delete(['md5'=>$FileName]);
							}
						}
					}
				}

				//{hook a_post_edit_370}

            	//编辑主题数据
				$Thread = S("Thread");
				$Thread->update(array(
					'fid'		=>	$fid,
					'title'		=>	$title,
					'hide'		=>	$thide?1:0,
					'summary'	=>	mb_substr(trim(filter_html($content)), 0,$this->conf['summary_size']),
					'gold'		=>	$tgold,
					'img'		=>	$img,
					'img_count'	=>	$sz,
					'etime'		=>	NOW_TIME,
					'euid'		=>	NOW_UID
					),[
					'tid'		=>	$tid
				]);
				$this->CacheObj->rm('thread_data_'.$tid);
            	$this->CacheObj->rm('post_data_'.$post_data['pid']);
				//{hook a_post_edit_38}

				//判断是否有上传附件权限
				if($UsergroupLib->read(NOW_GID,'uploadfile',$this->_usergroup)){
					//{hook a_post_edit_39}
					//编辑附件
		            $fileid 	= X("post.fileid");
		            $filegold 	= X("post.filegold");
		            $filemess 	= X("post.filemess");
		            $filehide 	= X("post.filehide");
		            
		            $Fileinfo = S("Fileinfo");
		            $Filegold = S('Filegold');
		            //删除附件文件路径列表
		            $DelFileList=[];
		            //{hook a_post_edit_391}
		            if(!empty($fileid)){
		            	//{hook a_post_edit_40}

		            	$fileid_arr 	= explode("||",$fileid);
		            	$filegold_arr 	= explode("||",$filegold);
		            	$filemess_arr 	= explode("||",$filemess);
		            	$filehide_arr 	= explode("||",$filehide);

		            	if(count($fileid_arr)){
		            		//{hook a_post_edit_41}

		            		$FileinfoList = $Fileinfo->select('*',['tid'=>$tid]);
		            		if(empty($FileinfoList)) $FileinfoList=[];
		            		
		            		$tmp_arr=[];
		            		foreach($FileinfoList as $key => $v){
		            			$tmp_arr[$v['fileid']]=[
		            				'tid'	=>	$v['tid'],
		            				'uid'	=>	$v['uid'],
		            				'gold'	=>	$v['gold'],
		            				'hide'	=>	$v['hide'],
		            				'downs'	=>	$v['downs'],
		            				'mess'	=>	$v['mess'],
		            				//是否被删除
		            				'is_del'	=> true
		            			];
		            		}
		            		//{hook a_post_edit_411}

	            			foreach ($fileid_arr as $key => $fileid_v) {
		            			$fileid_v=intval($fileid_v);
		            			if(empty($fileid_v)) continue;
		            			//判断文件是否属于文章作者 跳过管理员 版主
		            			if($File->is_comp($fileid_v,$post_data['uid']) || NOW_GID == C('ADMIN_GROUP') || is_forumg($this->_forum,NOW_UID,$post_data['fid'])){
		            				//{hook a_post_edit_412}
		            				$tmp_arr[$fileid_v]=[
		            					'tid'	=>	$tid,
		            					'uid'	=>	$post_data['uid'],
		            					'gold'	=>	isset($filegold_arr[$key]) ? intval($filegold_arr[$key]) : 0,
		            					'hide'	=>	isset($filehide_arr[$key]) ? intval($filehide_arr[$key]) : 0,
		            					'downs'	=>	isset($tmp_arr[$fileid_v]) ? $tmp_arr[$fileid_v]['downs'] : 0,
		            					'mess'	=>	isset($filemess_arr[$key]) ?  filter_html($filemess_arr[$key]) : '',
		            					//是否被删除
		            					'is_del'	=>	false
		            				];
		            				
		            			}

		            		}
		            		$i = 0;
		            		//{hook a_post_edit_413}
		            		$MoveFileList = [];
		            		foreach($tmp_arr as $key => $v){
		            			//{hook a_post_edit_4131}
		            			if($v['is_del']){//删除附件
		            				//{hook a_post_edit_414}
		            				$Fileinfo->delete(['fileid'=>$key]);
		            				$Filegold->delete(['fileid'=>$key]);
		            				$FileData = $File->read($key,['uid','md5name','filesize']);
		            				if(!empty($FileData)){
		            					//删除数据记录
		            					$File->delete(['id'=>$key]);

		            					//更新用户上传字节
		            					$User->update([
		            						'file_size[-]'=>$FileData['filesize']
		            					],[
		            						'uid'=>$FileData['uid']
		            					]);
		            					//{hook a_post_edit_4141}
		            					//文件路劲
		            					$FilePath = INDEX_PATH . 'upload/userfile/' . $FileData['uid'] . '/' . $FileData['md5name'];
		            					if(is_file($FilePath)){
		            						unlink($FilePath);
		            					}
		            					$DelFileList[]='upload/userfile/' . $FileData['uid'] . '/' . $FileData['md5name'];
		            					//删除附件 兼容新版本
		            					$FilePath = INDEX_PATH . GetStorageThreadFileDir($tid,false) . $FileData['md5name'];
		            					if(is_file($FilePath)){
		            						unlink($FilePath);
		            					}
		            					$DelFileList[]=GetStorageThreadFileDir($tid,false) . $FileData['md5name'];
		            					//{hook a_post_edit_4142}

		            				}
		            			}else{ //更新或插入新附件
		            				//{hook a_post_edit_415}
		            				$i++;
		            				if($Fileinfo->has(['fileid'=>$key])){ //存在旧附件
		            					$Fileinfo->update([
		            						'tid'		=>	$v['tid'],
		            						'gold'		=>	$v['gold'],
		            						'hide'		=>	$v['hide'],
		            						'downs'		=>	$v['downs'],
		            						'mess'		=>	$v['mess']
		            					],[
		            						'fileid'	=>	$key
		            					]);
		            				}else{ //插入新附件
		            					$Fileinfo->insert([
		            						'fileid'	=>	$key,
		            						'tid'		=>	$v['tid'],
		            						'uid'		=>	$v['uid'],
		            						'gold'		=>	$v['gold'],
		            						'hide'		=>	$v['hide'],
		            						'downs'		=>	$v['downs'],
		            						'mess'		=>	$v['mess']
		            					]);
		            					//{hook a_post_edit_416}
		            					$FileMd5Name 	= $File->get_row($key,'md5name');
			            				$FileTmpPath 	= INDEX_PATH . $UserTmpUploadPath . $FileMd5Name;
			            				
			            				//{hook a_post_edit_417}
			            				$StorageThreadFileDir = GetStorageThreadFileDir($tid);
			            				if(move_file($FileTmpPath, INDEX_PATH . $StorageThreadFileDir . $FileMd5Name)){
			            					$MoveFileList[] = INDEX_PATH . $StorageThreadFileDir . $FileMd5Name;
				            				$FileInfo = pathinfo($FileMd5Name);
											$FileName = $FileInfo['filename'];
				            				$File->update([
												'tid'	=> $tid,
												'pid'	=> $pid,
											],[
												'AND'=>['uid'=>NOW_UID,'md5'=>$FileName]
											]);
										}
										//{hook a_post_edit_418}
		            				}
		            			}
		            			//{hook a_post_edit_419}
		            		}
		            		
		            		//{hook a_post_edit_43}
		            		$Thread->update(['files'=>$i],['tid'=>$tid]); //更新主题附件数量
		            	}
		            }else{ //清空附件
		            	//{hook a_post_edit_431}
		            	$FileinfoList = $Fileinfo->select('*',['tid'=>$tid]);
		            	if(empty($FileinfoList)) $FileinfoList=[];

		            	foreach($FileinfoList as $v){
		            		//{hook a_post_edit_432}
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
            					//{hook a_post_edit_433}
            					//文件路劲
            					$FilePath = INDEX_PATH . 'upload/userfile/' . $FileData['uid'] . '/' . $FileData['md5name'];
            					if(is_file($FilePath)){
            						unlink($FilePath);
            					}
            					$DelFileList[]='upload/userfile/' . $FileData['uid'] . '/' . $FileData['md5name'];
            					//删除附件 兼容新版本
            					$FilePath = INDEX_PATH . GetStorageThreadFileDir($tid,false) . $FileData['md5name'];
            					if(is_file($FilePath)){
            						unlink($FilePath);
            					}
            					$DelFileList[]=GetStorageThreadFileDir($tid,false) . $FileData['md5name'];
            					//{hook a_post_edit_434}

            				}
		            	}

	            		//{hook a_post_edit_435}
	            		$Thread->update(['files'=>0],['tid'=>$tid]); //更新主题附件数量

	            	}//结束附件相关
	            	//{hook a_post_edit_381}
				}//结束判断附件权限
				//{hook a_post_edit_382}
			}//修改主题结束
			else{ //编辑帖子不是主题
				//{hook a_post_edit_436}
				$thread_data_posts = S("Thread")->find('posts',['tid'=>$post_data['tid']]);

				$pattern="/\<img.*?src\=\"(.*?)\"[^>]*>/i";
				preg_match_all($pattern,$content,$match);
				$img_all=[];
				if(isset($match[1][0])){
					foreach ($match[1] as $v) {
						if(substr_count($v,'data:image/') || substr_count($v,';base64') || strpos($v,'/emoji/') !== FALSE || empty($v)){
							continue;
						}
						$img_all[]=$v;
					}
				}
				//{hook a_post_edit_437}

				//处理临时文件
				$UserTmpUploadPath 	= 	GetUserTmpUploadPath(NOW_UID);
				$tmp_file_expression= "/src=\"(.*?)\"/i";
				preg_match_all($tmp_file_expression,$content,$matchsrc);
				$SrcFileList=[];
				if(isset($matchsrc[1][0])){
					$SrcFileList=$matchsrc[1];
				}
				//{hook a_post_edit_438}
				$MoveFileList = [];
				if(!empty($SrcFileList)){
					$StoragePostDir 		=	GetStoragePostDir($tid,$pid);
					foreach ($SrcFileList as $v) {
						$TmpFilePath = realpath(INDEX_PATH . str_replace(WWW,'',$v));
						$TmpFilePath = str_replace("\\",'/',$TmpFilePath);
						if(strpos($TmpFilePath,$UserTmpUploadPath) !== false){//确定为临时文件
							$NewFilePath = str_replace($UserTmpUploadPath,$StoragePostDir,$TmpFilePath);
							//移动临时文件到正式目录
							if(move_file($TmpFilePath, $NewFilePath)){
								$MoveFileList[] = $NewFilePath;
							}
						}
					}
					//替换临时文件路径为正式文件路径
					$content = str_replace($UserTmpUploadPath,$StoragePostDir,$content);
				}

				//{hook a_post_edit_439}
				$File=M('File');
				if(!empty($MoveFileList)){
	            	foreach ($MoveFileList as $v) {
						$FileInfo = pathinfo($v);
						$FileName = $FileInfo['filename'];
						$File->update([
							'tid'	=> $tid,
							'pid'	=> $pid,
						],[
							'AND'=>['uid'=>NOW_UID,'md5'=>$FileName]
						]);
		            }
	            }

	            //{hook a_post_edit_461}

				//处理不使用的旧文件
				$StoragePostDir = GetStoragePostDir($tid,$pid,false);
				if(is_dir(INDEX_PATH . $StoragePostDir)){
					$dh = opendir(INDEX_PATH . $StoragePostDir);
					while ($filename = readdir($dh)) {
						$fullpath = INDEX_PATH . $StoragePostDir . $filename;
						if ($filename != "." && $filename != ".." && !is_dir($fullpath)) {
							//{hook a_post_edit_462}
							$IsDel = true;
							foreach ($SrcFileList as $v) {
								if(strpos($v,$filename) !== false){ //正在使用
									$IsDel = false;
									break;
								}
							}
							//{hook a_post_edit_463}
							if($IsDel){
								delete_file($fullpath);
								$FileInfo = pathinfo($fullpath);
								$FileName = $FileInfo['filename'];
								$File->delete(['md5'=>$FileName]);
							}
						}
					}
				}
				//{hook a_post_edit_464}


				$count = intval(($thread_data_posts /  $this->conf['postlist']) + 1)+1;
		        for ($i=0; $i < $count; $i++) {
		            $this->CacheObj->rm("post_list_{$post_data['tid']}_DESC_{$i}");
		            $this->CacheObj->rm("post_list_{$post_data['tid']}_ASC_{$i}");
		        }
		        $this->CacheObj->rm('post_data_'.$post_data['pid']);
			}
			//{hook a_post_edit_4}
			//修改评论内容
			$Post->update([
				'content'	=>	$content,
				'etime'		=>	NOW_TIME,
				'euid'		=>	NOW_UID,
			],[
				'pid'		=>	$pid
			]);


			return $this->json(array('error'=>true,'info'=>'修改成功'));
		} //End Post
		//{hook a_post_edit_5}

		//编辑器帖子
		$pid = intval(X("get.id"));
		$Post = M("Post");

		$data = $Post->read($pid);

		//{hook a_post_edit_66}
		if(empty($data))
			return $this->message('评论不存在');
		//不是帖子作者 并且 不是管理员 并且不是版主
		if(
			NOW_UID != $data['uid'] && 
			NOW_GID != C("ADMIN_GROUP") && 
			!is_forumg($this->_forum,NOW_UID,$data['fid'])
		)
			return $this->message('太坏了,你居然想修改别人帖子 E= 2');
		//{hook a_post_edit_6}
		//获取帖子数据
		

		//属于主题帖子
		if($data['isthread']){
			//{hook a_post_edit_77}
			$thread_data = M("Thread")->read($data['tid']);
			$this->v('thread_data',$thread_data);


			$Fileinfo = S("Fileinfo");
	
			$file_list = $Fileinfo->select("*",array(
				'tid'=>$data['tid'],
				'ORDER' => ['fileid' => 'DESC'],
			));
			//{hook a_post_edit_88}
			if(!empty($file_list)){
				$File = M("File");
				foreach ($file_list as &$v) {
					$v['filename']=$File->get_name($v['fileid']);
				}

			}
			$this->v("file_list",$file_list);
		}
		
		//{hook a_post_edit_7}
		
		$this->v('id',$pid);
		$this->v("data",$data);
        $this->display("edit_post");

	}
	//投票
	public function vote(){
		//{hook a_post_vote_1}
		if(!IS_LOGIN)
			return $this->json(["error"=>false,"info"=>"你需要登录才可投票"]);
		$id=intval(X("post.id")); // 提交ID
		$type = X("post.type"); //类型
		if(!in_array($type,['thread1','thread2','post1','post2']))
            return $this->json(["error"=>false,"info"=>"投票类型不符"]);
		$type1=substr($type,0,-1);
		//{hook a_post_vote_2}
		if($type1 == 'thread'){
			$Thread = S("Thread");
			if(!$Thread->has(['tid'=>$id]))
				return $this->json(["error"=>false,"info"=>"不存在该主题"]);
			//{hook a_post_vote_3}
			$obj = S("Vote_thread");
			if(!$obj->has([
				'AND'=>[
					'uid'=>NOW_UID,
					'tid'=>$id
					]
				]
			)){
				if($type == 'thread1')
					$Thread->update(['goods[+]'=>1],['tid'=>$id]);
				else
					$Thread->update(['nos[+]'=>1],['tid'=>$id]);
				//{hook a_post_vote_4}
				$obj->insert(array(
					'uid'	=>	NOW_UID,
					'tid'	=>	$id,
					'atime'	=>	NOW_TIME,
				));
				$this->CacheObj->rm('thread_data_'.$id);
				//{hook a_post_vote_5}
				return $this->json(["error"=>true,"info"=>"投票成功"]);
				

			}
			//{hook a_post_vote_6}
			return $this->json(["error"=>false,"info"=>"你投过了"]);
			
		}elseif($type1 == 'post'){
			//{hook a_post_vote_7}
			$Post = S("Post");
			if(!$Post->has(['pid'=>$id]))
				return $this->json(["error"=>false,"info"=>"不存在该评论"]);
			//{hook a_post_vote_8}
			$obj = S("Vote_post");
			if(!$obj->has([
				'AND'=>[
					'uid'=>NOW_UID,
					'pid'=>$id
					]
				]
			)){
				if($type == 'post1')
					$Post->update(['goods[+]'=>1],['pid'=>$id]);
				else
					$Post->update(['nos[+]'=>1],['pid'=>$id]);
				//{hook a_post_vote_9}
				$obj->insert([
					'uid'	=>	NOW_UID,
					'pid'	=>	$id,
					'atime'	=>	NOW_TIME,
				]);
				//{hook a_post_vote_10}
				return $this->json(["error"=>true,"info"=>"投票成功"]);
			}
			//{hook a_post_vote_11}
			return $this->json(["error"=>false,"info"=>"你投过了"]);
			
		}

	}
	
	//删除评论， 不是 删除主题！
	public function del(){
		//{hook a_post_del_1}
		if(!IS_LOGIN)
            $this->json(array('error'=>false,'info'=>'请登录'));

		//用户组权限判断
		$UsergroupLib = L("Usergroup");
		if(!$UsergroupLib->read(NOW_GID,'del',$this->_usergroup))
			return $this->json(array('error'=>false,'info'=>'你当前所在用户组无法删除评论'));
		//{hook a_post_del_2}
		$pid = intval(X("post.id"));
        $Post = M("Post");

		//获取 评论数据
        $post_data = $Post->read($pid);
        if(empty($post_data))
            return $this->json(array('error'=>false,'info'=>'不存在此评论'));
        //{hook a_post_del_3}
		$fid = $post_data['fid'];
		$tid = $post_data['tid'];

		//{hook a_post_del_4}
        //用户组不是 管理员 &&  用户不是文章作者
        if(
			(NOW_GID != C("ADMIN_GROUP")) &&
			(NOW_UID != $post_data['uid']) &&
			//array_search(NOW_UID,$arr) === false
			!is_forumg($this->_forum,NOW_UID,$fid)
		)
            return $this->json(array('error'=>false,'info'=>'你没有权限操作这个评论'));

        //{hook a_post_del_5}
        //删除该ID评论
        $Post->del($pid);
        //主题评论数-1
		$Thread = M('Thread');
		$Thread->update_int($tid,'posts','-');
		//帖子作者-1
		M("User")->update_int($post_data['uid'],'posts','-');
		//更新缓存
		$this->_forum[$fid]['posts']--;
		$this->CacheObj->forum = $this->_forum;
		$this->_count['post']--;
		$this->CacheObj->bbs_count = $this->_count;
		//{hook a_post_del_6}
		//发送删除帖子消息
        if(NOW_UID != $post_data['uid']){
	        M("Chat")->sys_send(
	            $post_data['uid'],
	            '您的评论被删除 所在主题<a href="'.HYBBS_URLA('thread',$tid).'" target="_blank">['.$Thread->get_title($tid).']</a> 操作者:'.NOW_USER
	        );
        }
        //{hook a_post_del_7}
      	//删除附件
      	$StoragePostDir = GetStoragePostDir($tid,$pid);
      	deldir(INDEX_PATH . $StoragePostDir,false,true);
      	S('File')->delete(['pid'=>$pid]);
      	
        //{hook a_post_del_8}
        //删除缓存
        $count = intval(($Thread->get_row($tid,'posts') /  $this->conf['postlist']) + 1)+1;
        for ($i=0; $i < $count; $i++) {
            $this->CacheObj->rm("post_list_{$tid}_DESC_{$i}");
            $this->CacheObj->rm("post_list_{$tid}_ASC_{$i}");
        }
        $this->CacheObj->rm("post_data_".$post_data['pid']);

		//{hook a_post_del_5}
        return $this->json(array('error'=>true,'info'=>'删除成功'));
	}
	//{hook a_post_fun}

}
