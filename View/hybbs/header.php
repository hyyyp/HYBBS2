<?php !defined('HY_PATH') && exit('HY_PATH not defined.'); ?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="renderer" content="webkit" />
	<meta name="viewport" content="width=device-width, user-scalable=yes" />
	<title>{$title}{$conf.title2}</title>
	<meta name="keywords" content="{$conf.keywords}">
	<meta name="description" content="{$conf.description}">
	<link rel="shortcut icon" href="{#WWW}favicon.ico">
	<link rel="stylesheet" href="{#WWW}View/hybbs/icon/iconfont.css?ver={:get_theme_version('hybbs')}">
	<link rel="stylesheet" href="{#WWW}View/hybbs/app.css?ver={:get_theme_version('hybbs')}">
	<link rel="stylesheet" href="{#WWW}public/css/public.css?ver={:get_theme_version('hybbs')}">
	<script>
	var www = "{#WWW}{#RE}";
	var WWW = "{#WWW}";
	var exp = "{#EXP}";
	</script>
	<!--[if (gte IE 9)|!(IE)]><!-->
	<script src="{#WWW}public/js/jquery.min.js"></script>
	<!--<![endif]-->
	<!--[if lte IE 8 ]>
	<script src="{#WWW}public/js/jquery1.11.3.min.js"></script>
	<![endif]-->
	<script src="{#WWW}View/hybbs/jquery.darktooltip.js"></script>
	<script src="{#WWW}View/hybbs/app.js"></script>

	{if IS_LOGIN}
	<!-- 好友系统资源文件 -->
	<link href="{#WWW}public/css/friend.css?ver={:get_theme_version('hybbs')}" rel="stylesheet">
	<script src="{#WWW}public/js/friend.js?ver={:get_theme_version('hybbs')}"></script>
	{/if}
	<script src="{#WWW}public/js/app.js"></script>
	
	<style type="text/css">
	{if view_form('hybbs','menu_fix')}
	header{
		    position: fixed;
	    top: 0;
	    left: 0;
	    right: 0;
	    z-index: 4;
	}
	body>.container{
		    margin-top: 76px !important
	}
	{/if}
	
	{if view_form('hybbs','width')}
		.container{
			width:{php echo view_form('hybbs','width')};
		}
		.left{
			width: <?php echo (intval(view_form('hybbs','width')) - 300-20) ?>px !important;
		}
	{/if}
	{if view_form('hybbs','css')}
		{php echo view_form('hybbs','css')}
	{/if}
	
	</style>

	
</head>
<body>
<header>
	<div id="header" class="container">
		<a style="color:#0f88eb;font-size: 26px;" href="{#WWW}">{php echo view_form('hybbs','logo')}</a>
		<nav>
			{if view_form('hybbs','menu_forum')}
			{foreach $forum as $v}
			<a href="{php HYBBS_URL('forum',$v['id']);}">{$v.name}</a>
			{/foreach}
			{/if}
			{if view_form('hybbs','diy_link')}
				{php $tmp = explode("\r\n",view_form('hybbs','diy_link'))}
				{foreach $tmp as $v}
					{php $tmp1 = explode(",",$v)}
					<a href="{$tmp1[0]}"  {if $tmp1[2]==1}target="_blank"{/if}>{$tmp1[1]}</a>
					
				{/foreach}
			{/if}
		</nav>
		<form method="get" action="{php HYBBS_URL('search')}" class="searchBar" >
			<input type="hidden" name="s" value="search">
			<div data-reactid="20">
				<div class="Popover">
					<div class="searchBar-input ">
						<input type="text" name="key" value="" autocomplete="off" placeholder="搜索帖子，用户">
							<button class="btn" aria-label="搜索" type="submit">
							<svg viewBox="0 0 16 16" class="Icon Icon--search" style="height:16px;width:16px;" width="16" height="16" aria-hidden="true" data-reactid="26"><title></title><g><path d="M12.054 10.864c.887-1.14 1.42-2.57 1.42-4.127C13.474 3.017 10.457 0 6.737 0S0 3.016 0 6.737c0 3.72 3.016 6.737 6.737 6.737 1.556 0 2.985-.533 4.127-1.42l3.103 3.104c.765.46 1.705-.37 1.19-1.19l-3.103-3.104zm-5.317.925c-2.786 0-5.053-2.267-5.053-5.053S3.95 1.684 6.737 1.684 11.79 3.95 11.79 6.737 9.522 11.79 6.736 11.79z"></path></g></svg>
							</button>
						
					</div>
				</div>
			</div>
		</form>

		<div class="pull-right menu-box">
			{if !IS_LOGIN}
				<a href="{php HYBBS_URL('user','login');}" style="margin-right: 5px;" class="btn">{$_LANG['登录']}</a>
				<a href="{php HYBBS_URL('user','add');}" style="margin-right: 5px;" class="btn btn-primary">{$_LANG['注册']}</a>
				
			{else}
				<a href="javascript:void(0);" style="margin-right:10px" onclick="$('.friend-box').toggleClass('friend-box-a');">
					<img style="border-radius:50%;vertical-align: middle;" width="35" height="35" src="{#WWW}{$user.avatar.b}">
					<span class="xx " style="{if !$user['mess']}display:none{/if}">{$user.mess}</span>
				</a>
			{/if}
			<!--{hook t_header_4}-->
		</div>
	</div>
</header>
<!--{hook t_top_box}-->