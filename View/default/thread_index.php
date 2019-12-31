<?php !defined('HY_PATH') && exit('HY_PATH not defined.'); ?>
{include header}
<div class="container mt-4">
	<div class="d-flex">
		<div class="flex-grow-1" style="margin-right: 20px;">
			<div class="border shadow-sm mb-3">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb rounded-0 mb-0 bg-white">
						<li class="breadcrumb-item"><a href="{#WWW}">首页</a></li>
						<?php $tmp_fid = $forum[$thread_data['fid']]['fid']; ?>
						<?php
						$head_forum = [];
						while ($tmp_fid != -1) {
							array_unshift($head_forum,['id'=>$forum[$tmp_fid]['id'],'name'=>$forum[$tmp_fid]['name']]);
							if($forum[$tmp_fid]['fid'] != -1){
								$tmp_fid = $forum[$tmp_fid]['fid'];
							}else{
								break; 
							}
						}
						foreach($head_forum as $v){
							echo '<li class="breadcrumb-item"><a href="';
							HYBBS_URL('forum','',$v['id']);
							echo '">'.$v['name'].'</a></li>';
						}
						?>
						<li class="breadcrumb-item active" aria-current="page">
							<a href="{php HYBBS_URL('forum',$thread_data['fid'])}">{php echo forum($forum,$thread_data['fid'],'name');}</a>
						</li>
					
					</ol>
				</nav>
			</div>
			<div class="border shadow-sm bg-white mb-3">
				<div class="d-flex border-bottom p-2">
					<div class="flex-grow-1 pl-1">
						<div class="d-flex flex-column">
							<div class="pt-2">
								<h4 class="h4">
									{$thread_data.title}
								</h4>
							</div>
							<div class="pt-1" style="color: #b2b2b2;font-size: 12px;">
								<a href="{php HYBBS_URL('my',$thread_data['user']);}" target="_blank">
									{$thread_data.user}
								</a>
								<span class="mx-2">·</span>
								发表于 {php echo humandate($thread_data['atime']);}
								<span class="mx-2">·</span>
								<a href="{php HYBBS_URL('forum',$thread_data['fid']);}" >
									{php echo forum($forum,$thread_data['fid'],'name');}
								</a>
							</div>
							<div class="pt-2">

							</div>
						</div>
					</div>
					<div class="p-2" style="min-width: 70px">
						<img src="{#WWW}{$thread_data.avatar.b}" style="width: 50px;height:50px" class="rounded">
					</div>
				</div>
				<div class="p-2 thread-content">
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
				<div class="p-2 thread-content-btn">
					<button type="button" class="btn btn-primary btn-sm">赞同 <span>{$thread_data.goods}</span></button>
					<button type="button" class="btn btn-primary btn-sm">反对 <span>{$thread_data.nos}</span></button>
					{if IS_LOGIN}
						<?php $arr = explode(",",forum($forum,$thread_data['fid'],'forumg')); ?>
				        {if $thread_data['uid'] == NOW_UID || NOW_GID == C("ADMIN_GROUP") || is_forumg($forum,NOW_UID,$thread_data['fid'])}
			            <button class="btn btn-light btn-sm" onclick="set_state({$thread_data.tid},{$thread_data.state})" >
			            	{if $thread_data['state']}解锁帖子{else}锁定帖子{/if}
			            </button>
			            {/if}

			            <button class="btn btn-light btn-sm" onclick="star({$thread_data.tid},this)">
			            {if $thread_data['star']}取消{/if}收藏
			            </button>
					{/if}
				</div>
				{if IS_LOGIN } 
				<?php $arr = explode(",",forum($forum,$thread_data['fid'],'forumg')); ?>
				{if $thread_data['uid'] == NOW_UID || NOW_GID == C("ADMIN_GROUP") || is_forumg($forum,NOW_UID,$thread_data['fid'])}
				<div class="p-2 thread-content-footer border-top">
					{if $thread_data['uid'] == NOW_UID || NOW_GID == C("ADMIN_GROUP") || is_forumg($forum,NOW_UID,$thread_data['fid'])}
			            <a class="btn btn-light btn-sm" href="{php HYBBS_URL('post','edit',['id'=>$post_data['pid']]); }">
			            编辑主题
			            </a>
			            <button class="btn btn-light btn-sm" onclick="del_thread({$thread_data.tid},'thread')" >
			            删除主题
			            </button>
		            {/if}
					
		            {if NOW_GID == C("ADMIN_GROUP")}
		          
		                {if $thread_data['top'] == 1}

		                <button class="btn btn-secondary btn-sm" onclick="thread_top({$thread_data.tid},'off',1)" >
		                取消板块置顶
		                </button>
		                {else}
		                <button class="btn btn-light btn-sm" onclick="thread_top({$thread_data.tid},'on',1)" >
		                板块置顶
		                </button>
		                {/if}

		            
		                <!-- 管理员权限 -->
		                
		                {if $thread_data['top'] == 2}
		                <button class="btn btn-secondary btn-sm" onclick="thread_top({$thread_data.tid},'off',2)" >
		                取消全站置顶
		                </button>
		                {else}
		                <button class="btn btn-light btn-sm" onclick="thread_top({$thread_data.tid},'on',2)" >
		                全站置顶
		                </button>
		                {/if}

		            {/if}
		            {if is_forumg($forum,NOW_UID,$thread_data['fid']) }
		                {if $thread_data['top'] == 1}
		                <button class="btn btn-secondary btn-sm" onclick="thread_top({$thread_data.tid},'off',1)" >取消板块置顶</button>
		                {else}
		                <button class="btn btn-light btn-sm" onclick="thread_top({$thread_data.tid},'on',1)" >板块置顶</button>
		                {/if}
		            {/if}
                    {if NOW_GID == C("ADMIN_GROUP") || is_forumg($forum,NOW_UID,$thread_data['fid'])}
                     	{if $thread_data['digest'] == 1}
                     	<button class="btn btn-secondary btn-sm" onclick="thread_digest({$thread_data.tid},0)" >
                         	取消加精
                    	</button>
                    	{else}
                      	<button class="btn btn-light btn-sm" onclick="thread_digest({$thread_data.tid},1)" >
                        	加精
                      	</button>
                      	{/if}
                    {/if}
					
				</div>
				{/if}
				{/if}
			</div>
			{if !empty($filelist)}
			<div class="card shadow-sm mb-3 border rounded-0">
				<div class="card-header">附件列表</div>
				<ul class="list-group">
				{foreach $filelist as $v}
				{if $v['show']}
					<li class="list-group-item">
						<a href="javascript:void(0);" onclick="hy_downfile({$v.fileid})">{$v.name}</a>
						<i style="color: grey;    font-size: 14px;">&nbsp;&nbsp;文件大小:{php echo round($v['size']/1024/1024,3);}M 下载次数：{$v.downs})</i>
						{if $v['gold']}
							<span style="color: brown;"> &nbsp;&nbsp;售价:{$v.gold}</span>
						{/if}
					</li>
				{else}
					<li class="list-group-item">
						<a href="javascript:void(0);" style="color: #c31d1d;">隐藏附件回帖可见</a>
					</li>
				{/if}
				{/foreach}
				</ul>
			</div>
			{/if}
			<div class="card shadow-sm mb-3 border rounded-0">
				<div class="card-header">
					{$thread_data.posts} 条评论
					<span class="mx-2">·</span>
					{$thread_data['views']} 次浏览
					<span class="mx-2">·</span>
					最后评论于 {php echo humandate($thread_data['btime']);}

					<div class="btn-group float-right mb-n2 mt-n1">
						<button type="button" class="btn btn-light btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						{if X('get.order')=='desc'}最新回复{else}默认排序{/if}
						</button>
						<div class="dropdown-menu">
							<a class="dropdown-item {if X('get.order')!='desc'}active{/if}" href="{php HYBBS_URL('thread',$thread_data['tid'])}">默认排序</a>
							<a class="dropdown-item {if X('get.order')=='desc'}active{/if}" href="{php HYBBS_URL('thread',$thread_data['tid'])}?order=desc">最新评论</a>
						</div>
					</div>
				</div>
				{if !empty($PostList)}
				<script type="text/javascript">
				function create_post_post_page_btn(pid,pageid,posts,max,sort){
					$('#post--ul-'+pid).html('<img class="mx-auto d-block" src="{#WWW}View/hybbs/loading.gif"><p class="text-center text-secondary">加载数据中...</p>');
					get_post_post(pid,pageid,sort);

					var tmp = (posts%max) ?(parseInt(posts/max)+1) : parseInt(posts/max);
					var page_count = (posts % max != 0)?(parseInt(posts/max)+1) : parseInt(posts/max);
					pageid = pageid || 1;
					var html = '';
					if(pageid != 1){
						html+='<li class="page-item"><a class="page-link" href="javascript:;" data-pageid="'+(pageid-1)+'" tabindex="-1" aria-disabled="true">上一页</a></li>';
					}
					
					for (var i=(pageid-5 < 1) ? 1 : pageid -5; i< ((pageid + 5 > tmp) ? tmp+1 : pageid + 5) ; i++){
						html+='<li class="page-item '+(pageid == i ? 'active' : '')+'"><a class="page-link" href="javascript:;" data-pageid="'+i+'">'+i+'</a></li>';
					}
					if(pageid != page_count)
						html+='<li class="page-item"><a class="page-link" href="javascript:;" data-pageid="'+(pageid+1)+'">下一页</a></li>';
					

					$('#post--btns-'+pid).html(html).find('.page-link').click(function(){
						var _this = $(this);
						var _pageid = _this.data('pageid');
						location.href="#post--pos-"+pid;
						if(pageid == _pageid)
							return;
						create_post_post_page_btn(pid,_pageid,posts,max,sort);

					});
				}
				function show_post_post_box(obj){
					var _this = $(obj);
					var state = _this.data('state');
					var pid = _this.data('pid');
					var posts = _this.data('posts');
					var sort = _this.data('sort');
					if(state == 'hide'){ //Show
						_this.data('state','show');
						_this.html('收起列表');
						_this.addClass('active');
						$('#post--box-'+pid).removeClass('d-none').addClass('d-block');
						$('#post--sort-s'+pid).text(_this.data('str'+sort));
						if(posts) //存在子评论 显示分页按钮
							create_post_post_page_btn(pid,1,posts,{php echo intval(BBSCONF('post_post_show_size'))},sort);
						else{
							$('#post--ul-'+pid).html('<p class="text-center text-secondary mt-3">暂无数据...</p>');
						}

					}else{//收起评论列表
						_this.data('state','hide');
						if(posts != 0)
							_this.html(posts+' 条点评');
						else
							_this.html('点评');
						_this.removeClass('active');
						$('#post--box-'+pid).removeClass('d-block').addClass('d-none');
						
					}

				}
				
				</script>
				<div class="post-list">
					{php $DataModel = M('Data');$User = M('User');}
			        {foreach $PostList as $k => $v}
			        {if $v['rpid']}
			        	{php $quote_data = $DataModel->get_post_data($v['rpid'])}
			        {/if}
			        <div class="media border-bottom px-3 pt-3">
						<img src="{#WWW}{$v.avatar.b}" class="mr-3 rounded" style="width: 48px;height: 48px">
						<div class="media-body">
							<div class="">
								<a href="{php HYBBS_URL('my',$v['user']);}" class="h5 d-block text-decoration-none mb-0" target="_blank" style="font-size: 16px">{$v.user}</a>
							</div>
							<div class="mb-2">
								<span class="post-time" style="color: #8590a6;font-size: .5em;">发表于 {$v.atime_str}</span>
							</div>
							{if $v['rpid'] && !empty($quote_data)}
							<div class="card mb-3">
								<div class="media p-2">
									<img src="{#WWW}{:get_avatar($quote_data['uid'])['b']}" class="mr-3 rounded" style="width: 48px;height: 48px">
									<div class="media-body">
										<div class="">
											<a href="javascript:;" class="h5 d-block text-decoration-none mb-0" target="_blank" style="font-size: 16px">{:$User->uid_to_user($quote_data['uid'])}</a>
										</div>
										<div class="mb-2">
											<span class="post-time" style="color: #8590a6;font-size: .5em;">发表于 {:humandate($quote_data['atime'])}</span>
										</div>
										<div class="post-content">
										{$quote_data.content}
										</div>
									</div>

								</div>
							</div>
							{/if}
			            	<div id="pid-{$v.pid}" class="post-content">
							{$v.content}
							</div>
							<div class="post-footer my-3">
								<button type="button" class="btn btn-primary btn-sm" onclick="tp('post1',{$v.pid},this)">赞同 <span>{$v.goods}</span></button>
								<button type="button" class="btn btn-primary btn-sm" onclick="tp('post2',{$v.pid},this)">反对 <span>{$v.nos}</span></button>
								<button class="btn btn-light btn-sm" data-pid="{$v.pid}" data-uid="{$v.uid}" data-avatar="{#WWW}{$v.avatar.b}" data-user="{$v.user}" data-time="{$v.atime_str}" onclick="jump_post(this)">
		                        	回复帖子
		                        </button>

								<button id="post--start-{$v.pid}" class="btn btn-{if $v['posts']}warning{else}light{/if} btn-sm float-right" data-str0="默认排序" data-str1="最新回复" data-sort="0" data-posts="{$v.posts}" data-state="hide" data-pid="{$v.pid}" onclick="show_post_post_box(this)">
		                        	{if $v['posts']}{$v.posts} 条点评{else}点评{/if}
		                        </button>

			                	{if IS_LOGIN }

			                    
			                    {if $v['uid'] == NOW_UID || NOW_GID == C("ADMIN_GROUP")}
			                        <!-- 帖子作者 或者 管理员 -->
			                        <a class="btn btn-light btn-sm" href="{php HYBBS_URL('post','edit',['id'=>$v['pid']]); }">
			                        	编辑帖子
			                        </a>
			                    {/if}
			                    {if $v['uid'] == NOW_UID || NOW_GID == C("ADMIN_GROUP") || is_forumg($forum,NOW_UID,$thread_data['fid'])}
			                        <!-- 作者 与 管理员 判断 -->
			                        <button class="btn btn-light btn-sm" onclick="del_thread({$v.pid},'post')" >
			                        删除帖子
			                        </button>
			                    {/if}
			                    
			                	{/if}
							</div>
							<div id="post--box-{$v.pid}" class="card mb-3 d-none">
								<div id="post--pos-{$v.pid}" class="card-header">
									点评列表
			                		<div class="btn-group float-right mb-n2 mt-n1">
										<button id="post--sort-s{$v.pid}" type="button" class="btn btn-light btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										默认排序
										</button>
										<div class="dropdown-menu">
											<a class="dropdown-item" href="javascript:;" onclick="$('#post--start-{$v.pid}').data('state','hide').data('sort','0').click()">默认排序</a>
											<a class="dropdown-item" href="javascript:;" onclick="$('#post--start-{$v.pid}').data('state','hide').data('sort','1').click()">最新回复</a>
										</div>
									</div>
								</div>
								<div id="post--ul-{$v.pid}">
									<img class="mx-auto d-block" src="{#WWW}View/hybbs/loading.gif">
									<p class="text-center text-secondary">加载数据中...</p>
									
								</div>

								<nav class="border-bottom">
									<ul id="post--btns-{$v.pid}" class="pagination justify-content-center my-3">
										
									</ul>
								</nav>
								<div class="input-group p-2">
									<input id="post--{$v.pid}" type="text" class="form-control" placeholder="编写点评内容">
									<div class="input-group-append">
										<button id="post--btn-{$v.pid}" class="btn btn-outline-secondary" type="button" onclick="post_post({$v.pid})">发表</button>
									</div>
								</div>
			                </div>
						</div>
					</div>
			        {/foreach}
				</div>
				{else}
				<div class="p-3">
					暂无评论
				</div>
				{/if}
			</div>
			<div class="mt-3">
				<!--{hook t_thread_index_page_top}-->
				<nav aria-label="评论分页按钮组">
					<ul class="pagination justify-content-center">
						<li class="page-item {if $pageid==1}disabled{/if}">
							<a class="page-link" href="{if $pageid==1}javascript:;{else}{php HYBBS_URL('thread',$thread_data['tid'], $pageid-1);}{php echo X("get.order")?"?order=desc":'';}{/if}" tabindex="-1" aria-disabled="true">上一页</a>
						</li>
						<li class="page-item {if $pageid==$page_count}disabled{/if}">
							<a class="page-link" href="{if $pageid==$page_count}javascript:;{else}{php HYBBS_URL('thread',$thread_data['tid'], $pageid+1);}{php echo X("get.order")?"?order=desc":'';}{/if}">下一页</a>
						</li>
					</ul>
				</nav>
				<!--{hook t_thread_index_page_bottom}-->
			</div>
			<div class="mb-3">
				<!--{hook t_post_editer_top}-->
				{if IS_LOGIN}
				<!--{hook t_thread_index}-->
				{else}
				<div class="p-3 border shadow-sm">
					<a href="{php HYBBS_URL('user','login')}">登录</a>后才可发表内容
				</div>
				{/if}
				<!--{hook t_post_editer_bottom}-->
			</div>
		</div>
		{include right_menu}
	</div>
</div>
{include footer}