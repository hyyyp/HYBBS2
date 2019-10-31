<div class="item">
    <a title="{$v.user} - 个人主页" href="{php HYBBS_URL('my',$v['user']);}" target="_blank">
		<img class="js-info" uid="{$v.uid}" pos="right" src="{#WWW}{$v.avatar.b}">
	</a>
    <div class="middle text">
        <h4 class="title">
            {if !$v['top']}
			<a class="forum-name" href="{php HYBBS_URL('forum',$v['fid']);}">[ {php echo forum($forum,$v['fid'],'name');} ]</a>
            {/if}
			<a style="{if $v['top']==2}font-weight: bold;color: #09C;{elseif $v['top']==1}font-weight: bold;color: #CE792D;{/if}" class="thread-title" href="{php HYBBS_URL('thread',$v['tid']);}">{if $v['top']==2}<span class="qzd">{$_LANG['全站置顶']}</span>{elseif $v['top']==1}<span class="fzd">{$_LANG['本版置顶']}</span>{/if}{$v.title}{if $v['digest']}<i class="iconfont icon-jinghua" title="{$_LANG['精']}" style="color: tomato;margin-left: 5px"></i>{/if}{if $v['state']}<i class="iconfont icon-lock" style="margin-left: 5px;color: grey"></i>{/if}</a>

		</h4>
        <div class="meta">
        {$v.user}&nbsp;&nbsp;·&nbsp;&nbsp;发表于 {php echo humandate($v['atime']);}&nbsp;&nbsp;{if isset($v['buser'])}·&nbsp;&nbsp;{$v.buser}&nbsp;&nbsp;·&nbsp;&nbsp;最后回复 {php echo humandate($v['btime']);}{/if}
    	</div>
    	{if $v['posts']}
    	<div class="comment">
    		<span class="badge {if ($v['btime']+1800) > NOW_TIME}badge-success{/if}">{$v.posts}</span>
    	</div>
    	{/if}
	</div>
</div>