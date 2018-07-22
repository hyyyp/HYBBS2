<?php !defined('HY_PATH') && exit('HY_PATH not defined.'); ?>
{include header}
<div class="container" style="margin-top: 23px;">
	<div class="left">
		<!--{hook t_top_left_box}-->
		<!--{hook t_index_index_top_left_box}-->
		<div class="wrap-box" style="position:relative">
			<div style="border-bottom: 1px solid #f0f0f0;padding-bottom: 14px;">
			<button id="thread-sort" class="btn btn-link" style="    margin-left: 0;">
			{if isset($_GET['HY_URL'][0])}{if $_GET['HY_URL'][0] != 'btime'}默认排序{else}{$_LANG['最新回复']}{/if}{/if} <i class="iconfont icon-sort"></i>

			</button>
			
			<div style="right:auto;z-index:3;left: -6px;top: -5px;" class="select-pop wrap-box">
			<button onclick="location.href='{#WWW}'" class="btn select-option">默认排序</button>
			<button onclick="location.href='{php HYBBS_URL('btime');}'" class="btn select-option">{$_LANG['最新回复']}</button>
			</div>
			{if IS_LOGIN}
			<div class="pull-right">
				<a href="{php HYBBS_URL('post');}" class="btn btn-link"><i style="font-size: 14px;margin-right: 5px;" class="iconfont icon-edit"></i>发表帖子</a>
			</div>
			{/if}
			</div>
			
			<script type="text/javascript">
			$("#thread-sort").click(function() {
				$(".select-pop").addClass('select-pop-show');
				$(document).bind('mousedown.efscbarEvent',function(e){
					$(document).unbind("mousedown.efscbarEvent");
			        if(!$(e.target).is($('#btn')) && !$(e.target).is($('#box')) && $(e.target).parent('#box').length === 0){
			        	$(".select-pop").removeClass('select-pop-show');
			        }
			    });
			});
			</script>
			<div class="thread-list">
				{foreach $top_list as $v}
			    {include thread_list}
			    {/foreach}
				{foreach $thread_list as $v}
			    {include thread_list}
			    {/foreach}
			</div>
		</div>
		<div class="wrap-box">
			<!--{hook t_index_index_page_top}-->
			<a href="{if $pageid==1}javascript:void(0);{else}{php echo HYBBS_URL($type,$pageid-1);}{/if}"  class="btn btn-primary {if $pageid==1}disabled{/if}" >上一页</a>
			<a href="{if $pageid==$page_count}javascript:void(0);{else}{php echo HYBBS_URL($type,$pageid+1);}{/if}" class="btn btn-primary pull-right {if $pageid==$page_count}disabled{/if}" >下一页</a>
			<!--{hook t_index_index_page_bottom}-->
		</div>
	</div>
{include footer}