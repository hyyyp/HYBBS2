<?php !defined('HY_PATH') && exit('HY_PATH not defined.'); ?>
{include header}
<div class="container mt-4">
	<div class="d-flex">
		<div class="flex-grow-1" style="margin-right: 20px;">
			<div class="border shadow-sm">
				<div class="p-2">
					<div class="btn-group">
						<button type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						{if isset($_GET['HY_URL'][0])}{if $_GET['HY_URL'][0] != 'btime'}最新主题{else}最新回复{/if}{/if}
						</button>
						<div class="dropdown-menu">
							<a class="dropdown-item {if isset($_GET['HY_URL'][0])}{if $_GET['HY_URL'][0] != 'btime'}active{/if}{/if}" href="{#WWW}">最新主题</a>
							<a class="dropdown-item {if isset($_GET['HY_URL'][0])}{if $_GET['HY_URL'][0] == 'btime'}active{/if}{/if}" href="{php HYBBS_URL('btime');}">最新回复</a>
						</div>
					</div>
					<a href="{php HYBBS_URL('post');}" class="btn btn-dark float-right rounded">发布帖子</a>
				</div>
				<div class="border-top">
					{foreach $thread_list as $v}
					<div class="d-flex border-bottom p-1">
						<div class="p-2" style="min-width: 70px">
							<img src="{#WWW}{$v.avatar.b}" style="width: 50px;height:50px" class="rounded">
						</div>
						<div class="flex-grow-1 pl-1">
							<div class="d-flex flex-column">
								<div class="pt-2">
									<a href="{php HYBBS_URL('forum',$v['fid']);}" class="badge badge-warning mr-1" style="    vertical-align: text-bottom;">{php echo forum($forum,$v['fid'],'name');}</a>
									<a class="text-decoration-none text-dark thread-list-title" href="{php HYBBS_URL('thread',$v['tid']);}">
										{$v.title}
										<span class="badge badge-danger ml-1" title="精华帖" data-toggle="tooltip" data-placement="top">&spades;</span>
										<span class="badge badge-secondary ml-1" title="已锁帖" data-toggle="tooltip" data-placement="top">&Theta;</span>
										<!-- <span class="badge badge-secondary ml-1" title="已锁帖" data-toggle="tooltip" data-placement="top">附件</span>
										<span class="badge badge-secondary ml-1" title="已锁帖" data-toggle="tooltip" data-placement="top">图片</span> -->
										
									</a>
								</div>
								<div class="pt-1" style="color: #b2b2b2;font-size: 12px;">
									{$v.user}&nbsp;&nbsp;·&nbsp;&nbsp;发表于 {php echo humandate($v['atime']);}&nbsp;&nbsp;{if isset($v['buser'])}·&nbsp;&nbsp;{$v.buser}&nbsp;&nbsp;·&nbsp;&nbsp;最后回复 {php echo humandate($v['btime']);}{/if}
								</div>
								<div class="pt-2">

								</div>
							</div>
						</div>
						<div class="p-2" style="min-width: 60px">
							<a href="#" class="badge badge-secondary pl-2 pr-2">{$v.posts}</a>
						</div>
					</div>
					{/foreach}
				</div>
			</div>
			<div class="mt-3">
				<!--{hook t_index_index_page_top}-->
				<nav aria-label="首页帖子列表分页按钮组">
					<ul class="pagination justify-content-center">
						<li class="page-item {if $pageid==1}disabled{/if}">
						<a class="page-link" href="{if $pageid==1}javascript:;{else}{php echo HYBBS_URL($type,$pageid-1);}{/if}" tabindex="-1" aria-disabled="true">上一页</a>
						</li>
						<li class="page-item {if $pageid==$page_count}disabled{/if}">
						<a class="page-link" href="{if $pageid==$page_count}javascript:;{else}{php echo HYBBS_URL($type,$pageid+1);}{/if}">下一页</a>
						</li>
					</ul>
				</nav>
				<!--{hook t_index_index_page_bottom}-->
			</div>
		</div>
		{include right_menu}
	</div>
</div>
{include footer}