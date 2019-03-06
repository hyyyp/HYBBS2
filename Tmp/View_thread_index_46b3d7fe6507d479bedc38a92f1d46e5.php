<?php !defined('HY_PATH') && exit('HY_PATH not defined.'); ?>
<!DOCTYPE html>
<html>
<head lang="zh-cn">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="<?php echo $conf['description'];?>">
  <meta name="keywords" content="<?php echo $conf['keywords'];?>">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <title><?php echo $title;?><?php echo $conf['title2'];?></title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="Cache-Control" content="no-siteapp"/>
  <link rel="alternate icon" type="image/png" href="<?php echo WWW;?>favicon.ico">
  <link rel="apple-touch-icon-precomposed" href="<?php echo WWW;?>favicon.ico">
  <meta name="apple-mobile-web-app-title" content="HYUI"/>
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  
  
    
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
  <meta name="format-detection" content="telephone=no">
  <meta name="format-detection" content="email=no">

  
  <script src="<?php echo WWW;?>public/js/jquery.min.js"></script>
  <link rel="stylesheet" href="<?php echo WWW;?>public/css/public.css?ver=<?php echo get_theme_version('hy_moblie');?>">
  <link rel="stylesheet" href="<?php echo WWW;?>hyui/ui.css?var=<?php echo HYBBS_V;?>"/>
  <link rel="stylesheet" href="<?php echo WWW;?>hyui/style.css?var=<?php echo HYBBS_V;?>"/>
  <link rel="stylesheet" href="<?php echo WWW;?>View/hy_moblie/app.css?var=2.4"/>
  
  <script>
  var www = "<?php echo WWW;?><?php echo RE;?>";
  var WWW = "<?php echo WWW;?>";
  var exp = "<?php echo EXP;?>";
  var action_name = "<?php echo ACTION_NAME;?>";
  var method_name = "<?php echo METHOD_NAME;?>";
  <?php if (IS_LOGIN): ?>
  window.hy_user = "<?php echo NOW_USER;?>";
  window.hy_avatar = "<?php echo $user['avatar']['a'];?>";
  <?php else: ?>
  window.hy_user = '';
  window.hy_avatar = '';
  <?php endif ?>
  
  </script>
  
  <script src="<?php echo WWW;?>hyui/hy.js?var=<?php echo HYBBS_V;?>"></script>
  <script src="<?php echo WWW;?>View/hy_moblie/app.js?var=2.4"></script>
  
  <script src="<?php echo WWW;?>public/js/app.js?var=<?php echo HYBBS_V;?>"></script>
  <link href="<?php echo WWW;?>public/css/alert.css?var=<?php echo HYBBS_V;?>" rel="stylesheet">
  
  
</head>
<body hide_size="0">

<header class="hy-header hy-fix-t hy-bo-b" style="background:#FFF;">

<a onclick="url_back()" class="hy-header-nav hy-header-left icon icon-chevron-small-left" style="color:#000"></a>

<h1 class="hy-header-title" onclick="$.hy.popover_bottom_show()"><?php echo $_LANG['帖子菜单'];?><span class="icon icon-circle-down" style="font-size: 17px;padding: 8px;" ></span></h1>


<?php if (ACTION_NAME != 'User'): ?>
<a class="hy-header-nav hy-header-right icon icon-dots-two-horizontal" style="font-size: 34px;padding: 7px;" onclick="$.hy.popover_bottom_show()"></a>
<?php endif ?>

</header>


<style type="text/css">
  .icon-chevron-small-left, .icon {
    color: #337ab7 !important;
}

</style>

<!-- 滑动 -->
<div class="hy-popover-bottom">
    
    <ul class="hy-table-view">
    
        <?php if (IS_LOGIN ): ?>
            <?php if (NOW_GID == C("ADMIN_GROUP")): ?>
            <li class="hy-table-view-cell">
            	<?php if ($thread_data['top'] == 2): ?>
                <a class="hy-font-danger" onclick="thread_top(<?php echo $thread_data['tid'];?>,'off',2)"><?php echo $_LANG['取消全站置顶'];?> </a>
                <?php else: ?>
                <a onclick="thread_top(<?php echo $thread_data['tid'];?>,'on',2)"><?php echo $_LANG['全站置顶'];?> </a>
                <?php endif ?>
            </li>
            <?php endif ?>
            <?php if (NOW_GID == C("ADMIN_GROUP") || is_forumg($forum,NOW_UID,$thread_data['fid'])): ?>
            <li class="hy-table-view-cell">
            	<?php if ($thread_data['top'] == 1): ?>
                <a class="hy-font-danger" onclick="thread_top(<?php echo $thread_data['tid'];?>,'off',1)"><?php echo $_LANG['取消板块置顶'];?> </a>
                <?php else: ?>
                <a onclick="thread_top(<?php echo $thread_data['tid'];?>,'on',1)"><?php echo $_LANG['板块置顶'];?> </a>
                <?php endif ?>
            </li> 
            <?php endif ?>
            <?php if ($thread_data['uid'] == NOW_UID || NOW_GID == C("ADMIN_GROUP") || is_forumg($forum,NOW_UID,$thread_data['fid'])): ?>
            <li class="hy-table-view-cell">
                <a class="hy-font-purple" href="<?php HYBBS_URL('post','edit',['id'=>$post_data['pid']]);  ?>"><?php echo $_LANG['编辑文章'];?> </a>
            </li>
            <li class="hy-table-view-cell">
                <a class="hy-font-warning" onclick="set_state(<?php echo $thread_data['tid'];?>,<?php echo $thread_data['state'];?>)"><?php if ($thread_data['state']): ?><?php echo $_LANG['解锁帖子'];?><?php else: ?><?php echo $_LANG['锁定帖子'];?><?php endif ?> </a>
            </li>
            
            <li class="hy-table-view-cell">
                <a class="hy-font-danger" onclick="del_thread(<?php echo $thread_data['tid'];?>,'thread')" ><?php echo $_LANG['删除帖子'];?> </a>
            </li>
            <?php endif ?>
        <?php endif ?>
        
    </ul> 
    
    <ul class="hy-table-view">
        
    	<li class="hy-table-view-cell">
    		<a href="<?php HYBBS_URL('thread',$thread_data['tid']) ?>?order=desc"><?php echo $_LANG['最新评论'];?> <?php if (X("get.order") == 'desc'): ?><span class="hy-lable hy-lable-success"><?php echo $_LANG['当前'];?></span><?php endif ?></a>
    	</li>
    	<li class="hy-table-view-cell">
    		<a href="<?php HYBBS_URL('thread',$thread_data['tid']) ?>"><?php echo $_LANG['最早评论'];?> <?php if (X("get.order") != 'desc'): ?><span class="hy-lable hy-lable-success"><?php echo $_LANG['当前'];?></span><?php endif ?></a>
    	</li>
        
    	
    </ul>
    
    
</div>


<section class="body" id="thread-body">
<div style="padding-bottom:5px">

	<div class="hy-box thread-index" style="margin-bottom: 0;padding-bottom: 0;padding-top: 0;">
	
		<h2><?php echo $thread_data['title'];?><?php if ($thread_data['state']): ?><span title="<?php echo $_LANG['禁止回复'];?>" style="    color: brown;"> - <?php echo $_LANG['已锁定'];?></span><?php endif ?></h2>
		
		<p class="cl">
			<a href="<?php HYBBS_URL('my',$thread_data['user']); ?>" class="user">
				<img src="<?php echo WWW;?><?php echo $thread_data['avatar']['b'];?>" class="avatar"><?php echo $thread_data['user'];?>
			</a>
			<em class=""><?php echo humandate($thread_data['atime']) ?></em>
		</p>
		
	</div>
	
	<div class="hy-box thread-cen">
	
		<?php if ($thread_data['show'] && $thread_data['gold_show']): ?>
		<?php echo $post_data['content'];?>
		<?php else: ?>
		<?php if ($thread_data['gold_show']): ?>
          <blockquote style="color: #B75C5C;font-weight: bold;">
          <?php echo $_LANG['内容隐藏提示'];?>
          </blockquote>
          <?php else: ?>
          <blockquote style="color: #B75C5C;font-weight: bold;">
          <?php echo $_LANG['内容需要付费'];?> <a href="javascript:void(0);" onclick="buy_thread(<?php echo $thread_data['tid'];?>,<?php echo $thread_data['gold'];?>)">(<?php echo $_LANG['点击购买'];?>)</a> <?php echo $_LANG['售价'];?>：<?php echo $thread_data['gold'];?> <?php echo $_LANG['金币'];?>
          </blockquote>
          <?php endif ?>
        <?php endif ?>
    
		<div class="baod">
		<ul class="hy-lable-group">
			<?php if ($forum[$thread_data['fid']]['fid'] != -1): ?>
	        <li style="word-spacing: -0.35em;">
	            <a style="background:<?php echo forum($forum,$forum[$thread_data['fid']]['fid'],'background'); ?>;color:<?php echo forum($forum,$forum[$thread_data['fid']]['fid'],'color'); ?>;" href="<?php HYBBS_URL('forum',$forum[$thread_data['fid']]['fid']) ?>" class="hy-lable hy-lable-zz"><?php echo forum($forum,$forum[$thread_data['fid']]['fid'],'name'); ?></a>
	        </li>
	        <?php endif ?>
	        <li>
	            <a style="background:<?php echo forum($forum,$thread_data['fid'],'background'); ?>;color:<?php echo forum($forum,$thread_data['fid'],'color'); ?>;border-top-right-radius: 3px;border-bottom-right-radius: 3px;"  href="<?php HYBBS_URL('forum',$thread_data['fid']) ?>" class="hy-lable hy-lable-zz"><?php echo forum($forum,$thread_data['fid'],'name'); ?></a>
	        </li>
	     </ul>
			
		</div>
	</div>
	<?php if ($thread_data['files']): ?>
	
	<div class="hy-box hy-bo-t" style="padding:10px">
		<h2 class="hy-bo-b" style="padding-bottom:5px"><?php echo $_LANG['附件列表'];?></h2>
		
		<?php foreach ($filelist as $v): ?>
		
	     <?php if ($v['show']): ?>
	      <p style="padding:10px 0;font-size:18px">
	        <a href="javascript:void(0);" onclick="hy_downfile(<?php echo $v['fileid'];?>)"><?php echo $v['name'];?></a>
	        <i style="color: grey;    font-size: 14px;">&nbsp;&nbsp;<?php echo $_LANG['文件大小'];?>:<?php echo round($v['size']/1024/1024,2); ?>M (<?php echo $_LANG['下载次数'];?>：<?php echo $v['downs'];?>)</i>
	        <?php if ($v['gold']): ?>
	        <span style="color: brown;"> &nbsp;&nbsp;<?php echo $_LANG['售价'];?>:<?php echo $v['gold'];?></span>
	        <?php endif ?>
	      </p>
	      <?php else: ?>
	      <p style="padding:10px 0;font-size:18px">
	        <a href="javascript:void(0);"  style="color: #c31d1d;"><?php echo $_LANG['附件隐藏提示'];?></a>
	      </p>
	     <?php endif ?>
	     
	     <?php endforeach ?>
	     
	</div>
	<?php endif ?>
	
	<div class="hy-box postlist">
	
	<table style="width: 100%;">
		<?php $DataModel = M('Data');$User = M('User'); ?>
		<?php foreach ($PostList as $k => $v): ?>
		<?php if ($v['rpid']): ?>
        	<?php $quote_data = $DataModel->get_post_data($v['rpid']) ?>
        <?php endif ?>
		
		<tr>
			
			<td class="user">
				<a href="<?php HYBBS_URL('my',$v['user']); ?>" class="avatar">
					<img src="<?php echo WWW;?><?php echo $v['avatar']['c'];?>">
		        </a>
			</td>
			
			<td class="content">
			    <div class="info">
			    	<h4 class="cl">
			        <a href="<?php HYBBS_URL('my',$v['user']); ?>" class="info-user"><?php echo $v['user'];?></a>
					</h4>
			    	<p class="time"><em># <?php echo $k+1;?><?php echo $_LANG['楼'];?></em> <?php echo humandate($v['atime']) ?> 
			    	<a class="ic" href="<?php HYBBS_URL('thread','post',$v['pid']); ?>" ajax="true" pos="right">
			        	
			        	<?php if ($v['posts']): ?>
			        	 <?php echo $v['posts'];?> 条点评
			        	<?php else: ?>
			        	 点评
			        	<?php endif ?>
			        </a>


			    	<?php if (IS_LOGIN ): ?>
			    		<a href="javascript:;" class="" data-pid="<?php echo $v['pid'];?>" data-uid="<?php echo $v['uid'];?>" data-avatar="<?php echo WWW;?><?php echo $v['avatar']['b'];?>" data-user="<?php echo $v['user'];?>" data-time="<?php echo $v['atime_str'];?>" onclick="jump_post(this)">回复帖子</a>
	                    <?php if ($v['uid'] == NOW_UID || NOW_GID == C("ADMIN_GROUP")): ?>
	                        <!-- 帖子作者 或者 管理员 -->
	                        <a class="" href="<?php HYBBS_URL('post','edit',['id'=>$v['pid']]);  ?>"><?php echo $_LANG['编辑'];?></a>
	                    <?php endif ?>
	                    <?php if ($v['uid'] == NOW_UID || NOW_GID == C("ADMIN_GROUP") || is_forumg($forum,NOW_UID,$thread_data['fid'])): ?>
	                        <!-- 作者 与 管理员 判断 -->
	                        <a href="javascript:void(0);" class="" onclick="del_thread(<?php echo $v['pid'];?>,'post')" ><?php echo $_LANG['删除帖子'];?></a>
	                    <?php endif ?>
	                    
	                <?php endif ?>
	                </p> 

			    </div>
  				<div class="ce">
  				
              		<?php if ($v['rpid']): ?>
	            	<div class="quote-bx quote-box" style="display: block;">
					    
					    <div class="quote-bx">
					        <div class="quote-left">
					            <img class="quote-avatar" src="<?php echo WWW;?>public/images/user.gif">
					        </div>
					        <div class="quote-info">
					            <p class="quote-user"><?php echo $User->uid_to_user($quote_data['uid']);?></sppan>
					            <p class="quote-time"><?php echo humandate($quote_data['atime']);?></p>
					        </div>
					    </div>
					    <div class="quote-content">
					    	<?php echo $quote_data['content'];?>
					    </div>
					</div>
					<?php endif ?>
  					<div id="pid-<?php echo $v['pid'];?>">
              		<?php echo $v['content'];?>
              		</div>
              	
				</div>
    
			</td>
			
		</tr>
		
		<?php endforeach ?>
		
	</table>
	
	</div>
	<div class="hy-box hy-bo-t" style="padding:10px">
	
		<a href="<?php if ($pageid==1): ?>javascript:void(0);<?php else: ?><?php HYBBS_URL('thread',$thread_data['tid'],$pageid-1); ?><?php echo X("get.order")?"?order=desc":''; ?><?php endif ?>" class="hy-btn hy-btn-danger l <?php if ($pageid==1): ?>disabled<?php endif ?>"><?php echo $_LANG['上一页'];?></a>
		
		<a href="<?php if ($pageid==$page_count): ?>javascript:void(0);<?php else: ?><?php HYBBS_URL('thread',$thread_data['tid'],$pageid+1); ?><?php echo X("get.order")?"?order=desc":''; ?><?php endif ?>" class="hy-btn hy-btn-danger r <?php if ($pageid==$page_count): ?>disabled<?php endif ?>"><?php echo $_LANG['下一页'];?></a>
		<div style="clear: both;"></div>
	
	</div>
</div>
</section>

<div class="hy-fix-b hy-bo-t" style="background: #f6f6f6;width:100%;padding:10px">

	<button type="button" onclick="open_post_box(this)" class="hy-btn hy-btn-danger hy-btn-outlined" style="border-radius: 15px;width:40%"><?php echo $_LANG['评论'];?></button>
	
	<div class="r post-div" style="width:19%">
		
		<a onclick="tp('thread2',<?php echo $thread_data['tid'];?>,this)">
			<i class="icon icon-thumbs-o-down"></i>
			<?php echo $_LANG['踩'];?> (<span><?php echo $thread_data['nos'];?></span>)
		</a>
		
	</div>
	
	<div class="r post-div"  style="width:19%">
		<a onclick="tp('thread1',<?php echo $thread_data['tid'];?>,this)">
			<i class="icon icon-thumbs-o-up"></i>
			<?php echo $_LANG['赞'];?> (<span><?php echo $thread_data['goods'];?></span>)
		</a>
	</div>
	
	<div class="r post-div"  style="width:19%">
		<a >
			<i class="icon icon-hand-pointer-o"></i>
			<?php echo $_LANG['查看'];?> (<span><?php echo $thread_data['views'];?></span>)
		</a>
	</div>

	
</div>
<div class="post-box  hy-bo-t">
	
        <?php if (IS_LOGIN): ?>
            
<?php //Hook ##START##a:3:{s:11:"plugin_name";s:17:"HY移动编辑器";s:8:"dir_name";s:16:"hy_moblie_editor";s:4:"path";s:83:"/Users/krabs/Documents/wwwroot/HYBBS2/Plugin/hy_moblie_editor/t_m_thread_index.hook";}## ?>
<a name="post"></a>
<div class="hy-box" style="font-size: 16px;">
	<div class="hy-input-box">
	<label style="font-weight: bold;
    font-size: 1.4rem;">内容 <a href="javascript:void(0)" onclick="hide_post_box()">关闭评论</a></label>
	</div>
    <div class="rep-bx rep-box">
        <div class="rep-close rep-right" onclick="stop_post(this)">×</div>
        <div class="rep-bx">
            <div class="rep-left">
                <img class="rep-avatar" src="<?php echo WWW;?>public/images/user.gif">
            </div>
            <div class="rep-info">
                <p class="rep-user">loading</sppan>
                <p class="rep-time">loading</p>
            </div>
        </div>
        <div class="rep-content"></div>
    </div>

    <div id="editor" class="hy-editor" contenteditable="true">
        <p>&nbsp;</p>
    </div>
    <div style="" id="upload">
        <label for="fileToUpload" class="hy-editor-btn" ><img src="<?php echo WWW;?>Plugin/hy_moblie_editor/image.png"></label>
        <label class="hy-editor-btn" ><img style=" width: 28px;height: 28px;" class="hy-editor-emoji" src="<?php echo WWW;?>Plugin/hy_moblie_editor/s.png" onclick="$('#emoji-box').toggleClass('emoji-box-show')"></label>
        <label class="hy-editor-btn" ><img style=" width: 28px;height: 28px;" class="hy-editor-emoji" src="<?php echo WWW;?>Plugin/hy_moblie_editor/video.png" onclick="$('#video-box').toggleClass('emoji-box-show')"></label>
        <input type="file" name="fileToUpload" id="fileToUpload" onchange="fileSelected('upload','fileToUpload');" style="display: none;">
    </div>
    <p style="height:1px"></p>
</div>
<div id="video-box" class="hy-box" style="padding:10px;display:none">
    <h3>插入视频</h3>
    <div class="hy-input-box" style="margin-bottom:10px">
        <input type="text" id="video-input" placeholder="输入视频地址 (.mp4)">
    </div>
   
    <button type="button" class="hy-btn hy-btn-primary" onclick="insertvideo($('#video-input').val())">插入视频</button>
</div>
<div id="emoji-box" class="hy-box" style="padding:10px;display:none">

</div>
<link rel="stylesheet" type="text/css" href="<?php echo WWW;?>Plugin/hy_moblie_editor/hy_moblie.css">
<script type="text/javascript" src="<?php echo WWW;?>Plugin/hy_moblie_editor/hy_edit.js"></script>




<div class="hy-box" style="padding:10px">
	<button type="button" id="post1" class="hy-btn hy-btn-danger" >发 布</button>
</div>


<script type="text/javascript"> 
//回复帖子
function jump_post(obj){
    var _this   = $(obj);
    var pid     = _this.data('pid');
    var user    = _this.data('user');
    var avatar  = _this.data('avatar');
    var time    = _this.data('time');
    var content = $('#pid-'+pid);

    window.rep_pid = pid;

    $('.rep-user').text(user);
    $('.rep-time').text(time);
    $('.rep-avatar').text(avatar);
    $('.rep-content').html(content.html());

    $('.rep-box').show();
    open_post_box(obj);

    // $("body,html").animate({
    //     scrollTop:$('.rep-box').offset().top //让body的scrollTop等于pos的top，就实现了滚动
    // });
}
function stop_post(){
    $('.rep-box').hide();
    window.rep_pid = 0;
}
function utf16toEntities(str) { 
    var patt=/[\ud800-\udbff][\udc00-\udfff]/g; // 检测utf16字符正则  
    str = str.replace(patt, function(char){  
            var H, L, code;  
            if (char.length===2) {  
                H = char.charCodeAt(0); // 取出高位  
                L = char.charCodeAt(1); // 取出低位  
                code = (H - 0xD800) * 0x400 + 0x10000 + L - 0xDC00; // 转换算法  
                return "&#" + code + ";";  
            } else {  
                return char;  
            }  
        });  
    return str;  
} 
$(function () {
 
    $("#post1").click(function(){
        var _obj = $(this);
        _obj.attr('disabled','disabled');
        _obj.text("提交中...");
        
        var forum = $("#forum").val();
        $.ajax({
         url: '<?php HYBBS_URL('post','post');?>',
         type:"POST",
         cache: false,
         data:{
             id:<?php echo $tid;?>,
             content:utf16toEntities($("#editor").html()),
             
         },
         dataType: 'json'
     }).then(function(e) {
         if(e.error){
            window.location.reload();
         }else{
            $.hy.warning( e.info);
         }
         _obj.removeAttr('disabled');
            _obj.text("发 布");
       }, function() {
         $.hy.warning( "请尝试重新提交");
         _obj.removeAttr('disabled');
            _obj.text("发 布");
       });
    })
});
</script>
<?php //Hook ##END##a:3:{s:11:"plugin_name";s:17:"HY移动编辑器";s:8:"dir_name";s:16:"hy_moblie_editor";s:4:"path";s:83:"/Users/krabs/Documents/wwwroot/HYBBS2/Plugin/hy_moblie_editor/t_m_thread_index.hook";}## ?>

        <?php else: ?>
            <a class="hy-btn hy-btn-block" href="<?php HYBBS_URL('user','login') ?>"><?php echo $_LANG['登陆后才可发表内容'];?></a>
        <?php endif ?>
      
</div>


</body>
</html>