<?php !defined('HY_PATH') && exit('HY_PATH not defined.'); ?>
{include header}
<div class="container" style="margin-top: 23px;">
	<div class="left">
		<div class="wrap-box t-info">
			<div class="head">
		        <h1>
		        	<a href="{php HYBBS_URL('thread',$thread_data['tid'])}">返回 - {$thread_data.title}</a>
		        </h1>
		        <div class="meta">
				<a href="{php HYBBS_URL('my',$post_data['user']);}" target="_blank">
					{$post_data.user}
				</a>
				&nbsp;&nbsp;·&nbsp;&nbsp;
				发表于 {php echo humandate($post_data['atime']);}
				&nbsp;&nbsp;·&nbsp;&nbsp;
				<a href="{php HYBBS_URL('forum',$thread_data['fid']);}" >
					{php echo forum($forum,$thread_data['fid'],'name');}
				</a>
		        </div>
		        <a href="{php HYBBS_URL('my',$thread_data['user']);}" class="avatar" target="_blank">
					<img src="{#WWW}{$post_data.avatar.b}" pos="left" width="60" height="60" class="circle js-info" uid="{$thread_data.uid}">
		        </a>
		      </div>
		      <div class="content typo editor-style">
		        <!--{hook t_thread_post_content_top}-->
		        {$post_data.content}
		        <!--{hook t_thread_post_content_bottom}-->
		      </div>
		      <div class="actions">
				<button class="btn btn-info" onclick="tp('post1',{$post_data.pid},this)">
					<i class="iconfont icon-thumbsup1"></i> <span>{$post_data.goods}</span>
				</button>
				<button class="btn btn-info" onclick="tp('post2',{$post_data.pid},this)">
					<i class="iconfont icon-thumbsdown1"></i> <span>{$post_data.nos}</span>
				</button>
		        
		        {if IS_LOGIN } 
					<?php $arr = explode(",",forum($forum,$thread_data['fid'],'forumg')); ?>
		            {if $post_data['uid'] == NOW_UID || NOW_GID == C("ADMIN_GROUP") || is_forumg($forum,NOW_UID,$thread_data['fid'])}
		            
		            <a class="btn btn-link" href="{php HYBBS_URL('post','edit',['id'=>$post_data['pid']]); }">
		            <i class="iconfont icon-edit3" ></i> 编辑评论
		            </a>
		            <a href="javascript:void(0);" class="btn btn-link" onclick="del_thread({$post_data.pid},'post')" >
		            <i class="iconfont icon-delete"></i> {$_LANG['删除帖子']}
		            </a>

		            {/if}
		            
		          {/if}
		      </div>
		</div>
		
		<div class="wrap-box comment-list">
			<div class="head">
				{$post_data.posts} {$_LANG['条回复']} &nbsp;
				{if !empty($post_post_data)}
				<div class="pull-right">
					<button id="thread-sort" class="btn btn-link">
					{if X('get.order')=='desc'}{$_LANG['最新回复']}{else}默认排序{/if} <i class="iconfont icon-sort"></i>
					</button>
					<div class="select-pop wrap-box">
					<button onclick="location.href='{php HYBBS_URL('thread','post',$post_data['pid'])}'" class="btn select-option">默认排序</button>
					<button onclick="location.href='{php HYBBS_URL('thread','post',$post_data['pid'])}?order=desc'" class="btn select-option">{$_LANG['最新回复']}</button>
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
					
					</script>
				</div>
				{/if}
	        </div>
	        {foreach $post_post_data as $k => $v}
	        <div class="item">
				<a name="reply26"></a>
				<a href="{php HYBBS_URL('my',$v['user']);}" class="avatar" target="_blank">
	            	<img class="circle js-unveil js-info" uid="{$v.uid}" pos="right" src="{#WWW}{$v.avatar.b}">
				</a>
				<div class="r">
					<p class="meta">
						<a href="{php HYBBS_URL('my',$v['user']);}" class="author" target="_blank">
							{$v.user}
						</a>
						<br>
						<span class="time" title="test">
							发表于 {$v.atime_str}
						</span>
	            	</p>
		            <div class="text typo editor-style">
		            <!--{hook t_thread_post_content_top}-->
						{$v.content}
		            <!--{hook t_thread_post_content_bottom}-->
		            </div>
		            
	                
	        	</div>
	        </div>
	        {/foreach}
		</div>
		<div class="wrap-box">
			<!--{hook t_thread_post_page_top}-->
			<a href="{if $pageid==1}javascript:void(0);{else}{php HYBBS_URL('thread','post',[$post_data['pid']=>$pageid-1] );}{php echo X("get.order")?"?order=desc":'';}{/if}"  class="btn bg-primary {if $pageid==1}disabled{/if}" >{$_LANG['上一页']}</a>
			<a href="{if $pageid==$page_count}javascript:void(0);{else}{php HYBBS_URL('thread','post',[$post_data['pid']=>$pageid+1]);}{php echo X("get.order")?"?order=desc":'';}{/if}" class="btn bg-primary large pull-right {if $pageid==$page_count}disabled{/if}" >{$_LANG['下一页']}</a>
			<!--{hook t_thread_post_page_bottom}-->
		</div>
		<div class="wrap-box">
			<!--{hook t_thread_post_editer_top}-->
			{if IS_LOGIN}
			<span id="post--{$post_data['pid']}" onfocus="if(this.textContent=='编写评论内容')this.textContent=''" onblur="if(this.textContent=='')this.textContent='编写评论内容'" type="text" class="input-text" contenteditable="true">编写评论内容</span>
			<button style="    margin-top: 5px;" class="btn btn-primary" onclick="post_postA({$post_data.pid})">发表</button>
			{else}
			<a href="{php HYBBS_URL('user','login')}">{$_LANG['登录']}</a>{$_LANG['后才可发表内容']}
			{/if}
			<!--{hook t_thread_post_editer_bottom}-->
      </div>
	</div>
	<script type="text/javascript">
	function post_postA(pid){
		post_post(pid,function(e){
			swal(e.error ? "操作成功" : "操作失败", e.info, e.error ? "success" : "error");
			if(e.error) window.location.reload();
		},function(e){
			//swal(e.error ? "操作成功" : "操作失败", e.info, e.error ? "success" : "error");
		});
	}
	</script>
{include footer}