<?php !defined('HY_PATH') && exit('HY_PATH not defined.'); ?>
{include header}
<div class="container" style="margin-top: 23px;">
	<div class="left">
	<?php 
	$forum_group = M('Forum_group')->read_all_cache();
	?>
	<div id="none-forum" class="wrap-box forum" style="margin-bottom:10px;display:none">
		<?php $has_none = true; ?>
		<ul>
			{foreach $forum as $key => $v}
		        <?php $has = false; ?>
		        {foreach $forum_group as $vv}
		            
		            {if $v['fgid'] == $vv['id']}
		                <?php $has = true;break; ?>
		            {/if}
		            
		        {/foreach}
		        {if !$has}
		        <?php $has_none = false; ?>
				<li> 
					<a href="<?php HYBBS_URL('forum',$v['id']); ?>">
		            	<i> 
		            		<img src="{#WWW}upload/forum{$key}.png" onerror="this.src='{#WWW}upload/de.png'" align="left" alt="" width="97" height="97">
			            </i> 
			            <strong>{$v.name}</strong>
			            {if view_form('hy_boss','forum_html_on')}
			            <p>{$v.html}</p>
			            {/if}
		            </a>
	            </li>
            	{/if}
		    {/foreach}
        </ul>
        {if !$has_none}<script type="text/javascript">$('#none-forum').show()</script>{/if}
	</div>
	{foreach $forum_group as $v}
	<div id="" class="wrap-box forum" style="margin-bottom:10px">
		<h3>{$v.name}</h3>
		<ul>
			{foreach $forum as $key => $vv}
			{if $vv['fgid'] == $v['id']}
			<li> 
				<a href="<?php HYBBS_URL('forum','',$vv['id']); ?>">
	            	<i> 
	            		<img src="{#WWW}upload/forum{$key}.png" onerror="this.src='{#WWW}upload/de.png'" align="left" alt="" width="97" height="97">
		            </i> 
		            <strong>{$vv.name}</strong>
		            {if view_form('hy_boss','forum_html_on')}
		            <p>{$vv.html}</p>
		            {/if}
	            </a>
            </li>
            {/if}
            {/foreach}
        </ul>
	</div>
	{/foreach}
	</div>
{include footer}