<?php !defined('HY_PATH') && exit('HY_PATH not defined.'); ?>
{include header}
<div class="container" style="margin-top: 23px;">
	<div class="left">
		<!--{hook t_top_left_box}-->
		<!--{hook t_forum_thread_top_left_box}-->
		<div class="wrap-box" style="    color: #8590a6;">
			<a href="{#WWW}">{$_LANG['论坛首页']}</a>
			<?php $tmp_fid = $forum[$fid]['fid']; ?>
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
				echo '<span class="grey1"> > </span><a href="';
				HYBBS_URL('forum','',$v['id']);
				echo '">'.$v['name'].'</a>';
			}
			?>
			<span class="grey1"> > </span>
			{php echo forum($forum,$fid,'name');}
		</div>

		<div class="wrap-box" style="position:relative">
			<div style="    border-bottom: 1px solid #f0f0f0;padding-bottom: 14px;">
			<button id="thread-sort" class="btn btn-link" style="    margin-left: 0;">
			{if isset($_GET['HY_URL'][2])}{if $_GET['HY_URL'][2] == 'new'}默认排序{else}{$_LANG['最新回复']}{/if}{else}默认排序{/if} <i class="iconfont icon-sort"></i>

			</button>
			
			<div style="right:auto;z-index:3;left: -6px;top: -4px;" class="select-pop wrap-box">
			<button onclick="location.href='{php HYBBS_URL('forum',$fid);}'" class="btn select-option">默认排序</button>
			<button onclick="location.href='{php HYBBS_URL('forum',$fid,'btime');}'" class="btn select-option">{$_LANG['最新回复']}</button>
			</div>
			{if IS_LOGIN}
			<div class="pull-right">
				<a href="{php HYBBS_URL('post');}?fid={$fid}" class="btn btn-link"><i style="font-size: 14px;margin-right: 5px;" class="iconfont icon-edit"></i>发表帖子</a>
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
			    {foreach $top_f_data as $v}
			    {include thread_list}
			    {/foreach}
				{foreach $data as $v}
			    {include thread_list}
			    {/foreach}
			</div>
		</div>
		<div class="wrap-box">
			<!--{hook t_forum_thread_page_top}-->
			<a href="{if $pageid==1}javascript:void(0);{else}{php HYBBS_URL('forum',$fid,[$type=>$pageid-1]);}{/if}"  class="btn btn-primary {if $pageid==1}disabled{/if}" >上一页</a>
			<a  href="{if $pageid==$page_count}javascript:void(0);{else}{php HYBBS_URL('forum',$fid,[$type=>$pageid+1]);}{/if}" class="btn btn-primary pull-right {if $pageid==$page_count}disabled{/if}" >下一页</a>
			<!--{hook t_forum_thread_page_bottom}-->
		</div>
	</div>
{include footer}