<?php !defined('HY_PATH') && exit('HY_PATH not defined.'); ?>
{include header}
<div class="container mt-4">
	<div class="d-flex flex-wrap border shadow">
		{foreach $forum as $v}
		<div class="border-right border-bottom forum-item">
			<a class="text-decoration-none forum-item-name" href="{php HYBBS_URL('forum',$v['id'])}">
				<h5 class="h5 d-flex align-items-center" style="font-size: 18px;color: #000;font-weight: 400">
					<img src="{#WWW}upload/forum{$v.id}.png" onerror="this.src='{#WWW}upload/de.png'" alt="" width="28" height="28" style="margin-right: 5px">
					{$v.name}
				</h5>
				<p style="color: rgba(0, 0, 0, 0.7);font-size: 14px;">{$v.html}</p>
			</a>
			<a class="foum-item-footer" href="">
				版块最新帖子
			</a>
			<!-- <div class="media">
				<img src="{#WWW}upload/forum{$v.id}.png" onerror="this.src='{#WWW}upload/de.png'" class="mr-3" width="64" height="64">
				<div class="media-body">
					<h5 class="mt-0">{$v.name}</h5>
					{$v.html}
				</div>
			</div> -->
		</div>
		{/foreach}
	</div>
</div>
<style type="text/css">
	.forum-item{
		width: 33.3333333%;
	}
	.forum-item:nth-child(3n+0){
		border-right: 0!important;
	}
	.forum-item-name{
		height: 158px;
		display: block;
		padding: 20px 20px 0 20px;
		transition: background .2s;
	}
	.forum-item-name:hover{
		background: rgba(0, 0, 0, 0.05);
	}
	.foum-item-footer{
		color: rgba(0, 0, 0, 0.7);
		height: 42px;
	    padding: 7px 0;    border-top: 1px solid rgba(0, 0, 0, 0.15);
	    white-space: nowrap;
	    text-overflow: ellipsis;
	    overflow: hidden;
	    line-height: 21px;
	    font-size: 12px;
	    display: block;
	    margin: 0 20px;
	}
</style>
{include footer}