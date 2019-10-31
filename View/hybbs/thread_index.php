<?php !defined('HY_PATH') && exit('HY_PATH not defined.'); ?>
{include header}
<div class="container" style="margin-top: 23px;">
	<div class="left">
		<div class="wrap-box">
			<a href="{#WWW}">{$_LANG['论坛首页']}</a>
			<?php $tmp_fid = forum($forum,$thread_data['fid'],'fid'); ?>
			<?php
			$tmp_str = '';
			while ($tmp_fid != -1) { 
				$tmp_str='<span class="grey1"> > </span><a href="' .HYBBS_URLA('forum','',forum($forum,$tmp_fid,'id')).'">'.forum($forum,$tmp_fid,'name').'</a>'.$tmp_str;
				if(forum($forum,$tmp_fid,'fid') != -1){
					$tmp_fid = forum($forum,$tmp_fid,'fid');
				}else{
					break; 
				}
			}
			echo $tmp_str;
			?>
			<span class="grey1"> > </span> 
			<a href="{php HYBBS_URL('forum','',forum($forum,$thread_data['fid'],'id'))}">{php echo forum($forum,$thread_data['fid'],'name');}</a>
		</div>
		<div class="wrap-box t-info">
			<div class="head">
		        <h1>
		        	{$thread_data.title}
		        	{if $thread_data['digest']}<i class="iconfont icon-jinghua" title="{$_LANG['精']}" style="color: tomato;    font-size: inherit;"></i>{/if}
		        	{if $thread_data['state']}<i class="iconfont icon-lock" title="{$_LANG['禁止回复']}" style="color: grey;    font-size: inherit;"></i>{/if}
		        </h1>
		        <div class="meta">
				<a href="{php HYBBS_URL('my',$thread_data['user']);}" target="_blank">
					{$thread_data.user}
				</a>
				&nbsp;&nbsp;·&nbsp;&nbsp;
				发表于 {php echo humandate($thread_data['atime']);}
				&nbsp;&nbsp;·&nbsp;&nbsp;
				<a href="{php HYBBS_URL('forum',$thread_data['fid']);}" >
					{php echo forum($forum,$thread_data['fid'],'name');}
				</a>
		        </div>
		        <a href="{php HYBBS_URL('my',$thread_data['user']);}" class="avatar" target="_blank">
					<img src="{#WWW}{$thread_data.avatar.b}" pos="left" width="60" height="60" class="circle js-info" uid="{$thread_data.uid}">
		        </a>
		      </div>
		      <div class="content typo editor-style thread-content">
		        <!--{hook t_thread_content_top}-->
		        {if $thread_data['show'] && $thread_data['gold_show']}
					{$post_data.content}
		        {else}
					{if $thread_data['gold_show']}
						<blockquote style="color: #B75C5C;font-weight: bold;">
						{$_LANG['内容隐藏提示']}
						</blockquote>
					{else}
						<blockquote style="color: #B75C5C;font-weight: bold;">
						{$_LANG['付费可见']} <a href="javascript:void(0);" onclick="buy_thread({$thread_data['tid']},{$thread_data['gold']})">({$_LANG['点击购买']})</a> {$_LANG['售价']}：{$thread_data['gold']} {$_LANG['金币']}
						</blockquote>
					{/if}
		        {/if}
		        <!--{hook t_thread_content_bottom}-->
		      </div>
		      <div class="actions">
				<button class="btn btn-info" onclick="tp('thread1',{$thread_data.tid},this)">
					<i class="iconfont icon-thumbsup1"></i> <span>{$thread_data.goods}</span>
				</button>
				<button class="btn btn-info" onclick="tp('thread2',{$thread_data.tid},this)">
					<i class="iconfont icon-thumbsdown1"></i> <span>{$thread_data.nos}</span>
				</button>
				<div style="width: 10px;display: inline-block;"></div>
				{if IS_LOGIN}
					<?php $arr = explode(",",forum($forum,$thread_data['fid'],'forumg')); ?>
			        {if $thread_data['uid'] == NOW_UID || NOW_GID == C("ADMIN_GROUP") || is_forumg($forum,NOW_UID,$thread_data['fid'])}
		            <a href="javascript:void(0);" class="btn btn-link" onclick="set_state({$thread_data.tid},{$thread_data.state})" >
		            <i class="iconfont icon-{if $thread_data['state']}un{/if}lock"></i> {if $thread_data['state']}{$_LANG['解锁帖子']}{else}{$_LANG['锁定帖子']}{/if}
		            </a>
		            {/if}

		            <a href="javascript:;" class="btn btn-link" onclick="star({$thread_data.tid},this)">
		            <i class="iconfont icon-star" ></i> {if $thread_data['star']}取消{/if}收藏
		            </a>
				{/if}
		        
		        
			</div>
			{if IS_LOGIN } 
			<?php $arr = explode(",",forum($forum,$thread_data['fid'],'forumg')); ?>
			{if $thread_data['uid'] == NOW_UID || NOW_GID == C("ADMIN_GROUP") || is_forumg($forum,NOW_UID,$thread_data['fid'])}
			<div style="border-top: 1px solid #F3F3F3;margin-top: 15px;padding-top: 10px;">
				
			            {if $thread_data['uid'] == NOW_UID || NOW_GID == C("ADMIN_GROUP") || is_forumg($forum,NOW_UID,$thread_data['fid'])}
			            
			            <a class="btn btn-link" href="{php HYBBS_URL('post','edit',['id'=>$post_data['pid']]); }">
			            <i class="iconfont icon-edit3" ></i> 编辑主题
			            </a>
			          
			            
			            <a href="javascript:void(0);" class="btn btn-link" onclick="del_thread({$thread_data.tid},'thread')" >
			            <i class="iconfont icon-delete"></i> {$_LANG['删除主题']}
			            </a>

		            {/if}
					
		            {if NOW_GID == C("ADMIN_GROUP")}
		          
		                {if $thread_data['top'] == 1}

		                <a href="javascript:void(0);" class="btn btn-info is-active" onclick="thread_top({$thread_data.tid},'off',1)" >
		                <i class="iconfont icon-top"></i> {$_LANG['取消板块置顶']}
		                </a>
		                {else}
		                <a href="javascript:void(0);" class="btn btn-link" onclick="thread_top({$thread_data.tid},'on',1)" >
		                <i class="iconfont icon-top"></i> {$_LANG['板块置顶']}
		                </a>
		                {/if}

		            
		                <!-- 管理员权限 -->
		                
		                {if $thread_data['top'] == 2}
		                <a href="javascript:void(0);" class="btn btn-info is-active" onclick="thread_top({$thread_data.tid},'off',2)" >
		                <i class="iconfont icon-top"></i> {$_LANG['取消全站置顶']}
		                </a>
		                {else}
		                <a href="javascript:void(0);" class="btn btn-link" onclick="thread_top({$thread_data.tid},'on',2)" >
		                <i class="iconfont icon-top"></i> {$_LANG['全站置顶']}
		                </a>
		                {/if}

		            {/if}
		            {if is_forumg($forum,NOW_UID,$thread_data['fid']) }
		                {if $thread_data['top'] == 1}
		                <a href="javascript:void(0);" class="btn" onclick="thread_top({$thread_data.tid},'off',1)" >{$_LANG['取消板块置顶']}</a>
		                {else}
		                <a href="javascript:void(0);" class="btn" onclick="thread_top({$thread_data.tid},'on',1)" >{$_LANG['板块置顶']}</a>
		                {/if}
		            {/if}
                      {if NOW_GID == C("ADMIN_GROUP") || is_forumg($forum,NOW_UID,$thread_data['fid'])}
                      {if $thread_data['digest'] == 1}
                      <a href="javascript:void(0);" class="btn btn-info is-active" onclick="thread_digest({$thread_data.tid},0)" >
                          <i class="iconfont icon-jinghua"></i> {$_LANG['取消加精']}
                      </a>
                      {else}
                      <a href="javascript:void(0);" class="btn btn-link" onclick="thread_digest({$thread_data.tid},1)" >
                          <i class="iconfont icon-jinghua"></i> {$_LANG['加精']}
                      </a>
                      {/if}
                      {/if}
				
			</div>
			{/if}
			{/if}
		</div>
		{if $thread_data['files']}
		<div class="wrap-box">
			<h2 style="border-bottom: solid #E6E6E6 1px;padding-bottom: 10px;">{$_LANG['附件列表']}</h2>
			{foreach $filelist as $v}
			{if $v['show']}
				<p style="padding:10px 0;font-size:18px">
				<a href="javascript:void(0);" onclick="hy_downfile({$v.fileid})">{$v.name}</a>
				<i style="color: grey;    font-size: 14px;">&nbsp;&nbsp;{$_LANG['文件大小']}:{php echo round($v['size']/1024/1024,3);}M ({$_LANG['下载次数']}：{$v.downs})</i>
				{if $v['gold']}
					<span style="color: brown;"> &nbsp;&nbsp;{$_LANG['售价']}:{$v.gold}</span>
				{/if}
				</p>
			{else}
				<p style="padding:10px 0;font-size:18px">
				<a href="javascript:void(0);" style="color: #c31d1d;">{$_LANG['附件隐藏']}</a>
				</p>
			{/if}
			{/foreach}
		</div>
		{/if}
		<div class="wrap-box comment-list">
			<div class="head">
				{$thread_data.posts} {$_LANG['条回复']} &nbsp;
				<span class="grey">|</span>
				&nbsp;{$_LANG['直到']} {php echo humandate($thread_data['btime']);}
				<span class="grey">|</span>
				{$thread_data['views']} {$_LANG['次浏览']}
				{if !empty($PostList)}
				<div class="pull-right">
					<!-- 
					<a href="{php HYBBS_URL('thread',$thread_data['tid'])}?order=desc">{$_LANG['最新回复']}</a>
					<span class="grey">|</span>
					<a href="{php HYBBS_URL('thread',$thread_data['tid'])}">{$_LANG['最早回复']}</a> -->
					<button id="thread-sort" class="btn btn-link">
					{if X('get.order')=='desc'}{$_LANG['最新回复']}{else}默认排序{/if} <i class="iconfont icon-sort"></i>
					</button>
					<div class="select-pop wrap-box">
					<button onclick="location.href='{php HYBBS_URL('thread',$thread_data['tid'])}'" class="btn select-option">默认排序</button>
					<button onclick="location.href='{php HYBBS_URL('thread',$thread_data['tid'])}?order=desc'" class="btn select-option">{$_LANG['最新回复']}</button>
					</div>
					<script type="text/javascript">
					$(document).ready(function(){
						$("#thread-sort,.post--sort").click(function() {

							var _this2 = $(this).next();
							_this2.addClass('select-pop-show');
							$(document).bind('mousedown.efscbarEvent',function(e){
								$(document).unbind("mousedown.efscbarEvent");
						        if(!$(e.target).is($('#btn')) && !$(e.target).is($('#box')) && $(e.target).parent('#box').length === 0){
						        	_this2.removeClass('select-pop-show');
						        }
						    });
						});
					});
					
					function create_post_post_page_btn(pid,pageid,posts,max,sort){
						$('#post--ul-'+pid).html('<li><img src="{#WWW}View/hybbs/loading.gif" style="margin: 0 auto;display: block;"><p style="text-align: center;color: #a3a3a3;">加载数据中...</p></li>');
						console.log(max);
						get_post_post(pid,pageid,sort);

						var tmp = (posts%max) ?(parseInt(posts/max)+1) : parseInt(posts/max);
						var page_count = (posts % max != 0)?(parseInt(posts/max)+1) : parseInt(posts/max);
						pageid = pageid || 1;
						var html = '';
						if(pageid != 1){
							html+='<button data-pageid="'+(pageid-1)+'" class="btn btn-primary">上一页</button>';
						}
						
						for (var i=(pageid-5 < 1) ? 1 : pageid -5; i< ((pageid + 5 > tmp) ? tmp+1 : pageid + 5) ; i++){
							html+='<button data-pageid="'+i+'" class="btn btn-primary '+(pageid == i ? 'disabled' : '')+'">'+i+'</a>';
						}
						if(pageid != page_count)
							html+='<button data-pageid="'+(pageid+1)+'" class="btn btn-primary">下一页</button>';
						//var tag = $(html);

						$('#post--btns-'+pid).html(html).find('button').click(function(){
							var _this = $(this);
							var _pageid = _this.data('pageid');
							location.href="#post--pos-"+pid;
							if(pageid == _pageid)
								return;
							//alert('sss');
							create_post_post_page_btn(pid,_pageid,posts,max,sort);

						});
					}
					function show_post_post_box(obj){
						var _this = $(obj);
						var state = _this.data('state');
						var pid = _this.data('pid');
						var posts = _this.data('posts');
						var sort = _this.data('sort');
						console.log(sort);
						if(state == 'hide'){ //Show
							_this.data('state','show');
							_this.html('<i class="iconfont icon-top2"></i> 收起列表');
							_this.addClass('active');
							$('#post--box-'+pid).show();
							$('#post--sort-s'+pid).text(_this.data('str'+sort));
							if(posts) //存在子评论 显示分页按钮
								create_post_post_page_btn(pid,1,posts,{php echo intval(BBSCONF('post_post_show_size'))},sort);
							else{
								$('#post--ul-'+pid).html('<li><p style="text-align: center;color: #a3a3a3;">暂无数据...</p><li>');
							}

						}else{//收起评论列表
							_this.data('state','hide');
							if(posts != 0)
								_this.html('<i class="iconfont icon-commentalt2fill"></i> '+posts+' 条点评');
							else
								_this.html('<i class="iconfont icon-commentalt2fill"></i> 点评');
							_this.removeClass('active');
							$('#post--box-'+pid).hide();
							
						}

					}
					
					</script>
				</div>
				{/if}
	        </div>
	        {php $DataModel = M('Data');$User = M('User');}
	        {foreach $PostList as $k => $v}
	        {if $v['rpid']}
	        	{php $quote_data = $DataModel->get_post_data($v['rpid'])}
	        {/if}
	        <div class="item" id="post-{$v.pid}">
				<a href="{php HYBBS_URL('my',$v['user']);}" class="avatar" target="_blank">
	            	<img class="circle js-unveil js-info" uid="{$v.uid}" pos="right" src="{#WWW}{$v.avatar.b}">
				</a>
				<div class="r">
					<p class="meta">
						<a href="{php HYBBS_URL('my',$v['user']);}" class="author" target="_blank">
							{$v.user}
						</a>
						<br>
						<span class="time">
							发表于 {$v.atime_str}
						</span>
	            	</p>
		            <div class="text typo editor-style">
		            <!--{hook t_post_content_top}-->
		            	{if $v['rpid'] && !empty($quote_data)}
		            	<div class="quote-bx quote-box" style="display: block;">
						    
						    <div class="quote-bx">
						        <div class="quote-left">
						            <img class="quote-avatar" src="{#WWW}{:get_avatar($quote_data['uid'])['b']}">
						        </div>
						        <div class="quote-info">
						            <p class="quote-user">{:$User->uid_to_user($quote_data['uid'])}</sppan>
						            <p class="quote-time">{:humandate($quote_data['atime'])}</p>
						        </div>
						    </div>
						    <div class="quote-content">
						    	{$quote_data.content}
						    </div>
						</div>
						{/if}
		            	<div id="pid-{$v.pid}" class="post-content">
						{$v.content}
						</div>
		            <!--{hook t_post_content_bottom}-->
		            </div>
		            <div class="p-foot">
						<button class="btn btn-info" onclick="tp('post1',{$v.pid},this)">
							<i class="iconfont icon-thumbsup1"></i> <span>{$v.goods}</span>
						</button>
						<button class="btn btn-info" onclick="tp('post2',{$v.pid},this)">
							<i class="iconfont icon-thumbsdown1"></i> <span>{$v.nos}</span>
						</button>

						<button class="btn btn-link" data-pid="{$v.pid}" data-uid="{$v.uid}" data-avatar="{#WWW}{$v.avatar.b}" data-user="{$v.user}" data-time="{$v.atime_str}" onclick="jump_post(this)">
                        	<i class="iconfont icon-marks"></i> 回复帖子
                        </button>

						<button style="float: right;line-height: 2.3;{if $v['posts']}color: #ef6464;{/if}"  id="post--start-{$v.pid}" class="btn btn-link" data-str0="默认排序" data-str1="{$_LANG['最新回复']}" data-sort="0" data-posts="{$v.posts}" data-state="hide" data-pid="{$v.pid}" onclick="show_post_post_box(this)">
                        	<i class="iconfont icon-commentalt2fill"></i> {if $v['posts']}{$v.posts} 条点评{else}点评{/if}
                        </button>

	                	{if IS_LOGIN }

	                    
	                    {if $v['uid'] == NOW_UID || NOW_GID == C("ADMIN_GROUP")}
	                        <!-- 帖子作者 或者 管理员 -->
	                        <a class="btn btn-link" href="{php HYBBS_URL('post','edit',['id'=>$v['pid']]); }">
	                        <i class="iconfont icon-edit3"></i> 编辑帖子
	                        </a>
	                    {/if}
	                    {if $v['uid'] == NOW_UID || NOW_GID == C("ADMIN_GROUP") || is_forumg($forum,NOW_UID,$thread_data['fid'])}
	                        <!-- 作者 与 管理员 判断 -->
	                        <a href="javascript:void(0);" class="btn btn-link" onclick="del_thread({$v.pid},'post')" >
	                        <i class="iconfont icon-delete"></i> {$_LANG['删除帖子']}
	                        </a>
	                    {/if}
	                    
	                	{/if}
	                </div>
	                <div id="post--box-{$v.pid}" class="post--box">
	                	<h2 id="post--pos-{$v.pid}">
	                		评论列表
	                		<button class="btn btn-link post--sort pull-right">
							<span id="post--sort-s{$v.pid}">{if X('get.order')=='desc'}{$_LANG['最新回复']}{else}默认排序{/if}</span> <i class="iconfont icon-sort"></i>
							</button>
							<div class="select-pop wrap-box">
							<button onclick="$('#post--start-{$v.pid}').data('state','hide').data('sort','0').click()" class="btn select-option">默认排序</button>
							<button onclick="$('#post--start-{$v.pid}').data('state','hide').data('sort','1').click()" class="btn select-option">{$_LANG['最新回复']}</button>
							</div>
	                	</h2>
						<ul id="post--ul-{$v.pid}">
							<li>
								<img src="{#WWW}View/hybbs/loading.gif" style="margin: 0 auto;display: block;">
								<p style="text-align: center;color: #a3a3a3;">加载数据中...</p>
							</li>
						</ul>
						<div id="post--btns-{$v.pid}" class="post--page-btns">
							
						</div>
						<div class="post--foot">
							<div id="post--loading-{$v.pid}" class="loading"></div>
							<span id="post--{$v.pid}" onfocus="if(this.textContent=='编写评论内容')this.textContent=''" onblur="if(this.textContent=='')this.textContent='编写评论内容'" type="text" class="input-text" contenteditable="true">编写评论内容</span>
							<button id="post--btn-{$v.pid}" class="btn btn-primary" onclick="post_post({$v.pid})">发表</button>
							<button onclick="$('#post--start-{$v.pid}').data('state','show').click()" class="btn pull-right"><i class="iconfont icon-top2"></i> 收起列表</button>
						</div>
	                </div>
	        	</div>
	        </div>
	        {/foreach}
		</div>
		<div class="wrap-box">
			<!--{hook t_thread_index_page_top}-->
			<a href="{if $pageid==1}javascript:void(0);{else}{php HYBBS_URL('thread',$thread_data['tid'], $pageid-1);}{php echo X("get.order")?"?order=desc":'';}{/if}"  class="btn bg-primary {if $pageid==1}disabled{/if}" >{$_LANG['上一页']}</a>
			<a href="{if $pageid==$page_count}javascript:void(0);{else}{php HYBBS_URL('thread',$thread_data['tid'], $pageid+1);}{php echo X("get.order")?"?order=desc":'';}{/if}" class="btn bg-primary large pull-right {if $pageid==$page_count}disabled{/if}" >{$_LANG['下一页']}</a>
			<!--{hook t_thread_index_page_bottom}-->
		</div>
		<div class="wrap-box">
			<!--{hook t_post_editer_top}-->
			{if IS_LOGIN}
			<!--{hook t_thread_index}-->
			{else}
			<a href="{php HYBBS_URL('user','login')}">{$_LANG['登录']}</a>{$_LANG['后才可发表内容']}
			{/if}
			<!--{hook t_post_editer_bottom}-->
      </div>
	</div>
{include footer}