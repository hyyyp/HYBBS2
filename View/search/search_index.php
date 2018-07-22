<?php !defined('HY_PATH') && exit('HY_PATH not defined.'); ?>
<!DOCTYPE html>
<html>
<head lang="zh-cn">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<title>标题</title>
	<meta name="renderer" content="webkit">
	<meta http-equiv="Cache-Control" content="no-siteapp"/>
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-capable" content="yes">

	<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="email=no">
	<link rel="stylesheet" type="text/css" href="{#WWW}View/search/icon/iconfont.css">
	<link rel="stylesheet" type="text/css" href="{#WWW}View/search/app.css">

	<!--[if (gte IE 9)|!(IE)]><!-->
	<script src="{#WWW}public/js/jquery.min.js"></script>
	<!--<![endif]-->
	<!--[if lte IE 8 ]>
	<script src="{#WWW}public/js/jquery1.11.3.min.js"></script>
	<![endif]-->

	
</head>
<body>
	<div class="container {if !empty($key)}a{/if}">
		<div class="box">
			<button onclick="$('.container').toggleClass('a');" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
			
			<p class="title"><a href="{#WWW}">HYBBS</a></p>

			<form method="get" onsubmit="return search(this);">
			<div class="search-input">
				<div style="display: table-cell;">
					<input name="key" value="{$key}" type="" placeholder="搜索关键字、板块名称、用户名称">
				</div>
				<div style="display: table-cell;vertical-align: middle;width: 40px;">
					<button type="submit"><i class="iconfont icon-search"></i></button>
				</div>
			</div>
			<div class="search-radio">
				<input id="type-1" name="type" type="radio" value="1" {if X('get.type') != 2}checked{/if}> 
				<label {if X('get.type') != 2}class="a"{/if} for="type-1" onclick="tab_radio(this)">标准搜索</label>
				<input id="type-2" name="type" type="radio" value="2" {if X('get.type') == 2}checked{/if}>
				<label {if X('get.type') == 2}class="a"{/if} for="type-2" onclick="tab_radio(this)">全文搜索</label>
			</div>
			</form>
			<div style="position: relative;height: 100%;">
				<section class="search-page">
					{if !empty($forum_list)}
					<div class="search-radio">
						{foreach $forum_list as $v}
						<input type="radio">
						<label class="a"><a href="{php HYBBS_URL('forum',$v['id']);}">{$v.name}</a></label>
						{/foreach}
						<div style="clear: both;margin-bottom:0"></div>
					</div>
					{/if}
					{if !empty($user_list)}
					<div style="margin-bottom:5px">
						{foreach $user_list as $v}
						<a class="user-box" href="{php HYBBS_URL('my',$v['user']);}" target="_blank">
							<img src="{#WWW}{$v.avatar.b}">
							{$v.user}
						</a>
						{/foreach}
						<div style="clear: both;margin-bottom:0"></div>
					</div>
					{/if}

					{if !empty($data)}
					{foreach $data as $v}
					<div>
						<h4>
							<a href="{php HYBBS_URL('thread',$v['tid']);}" title="新窗口打开" target="_blank">{$v.title}</a>
						</h4>
						<p class="content"><span title="{php echo date('Y年m月d日 H:i',$v['atime'])}">{php echo humandateA($v['atime'])}&nbsp;-&nbsp;</span>{if !$v['hide'] && !$v['gold']}{$v.content}{else}文章内容被隐藏, 可能需要回复显示或付费显示!{/if}</p>
						<a title="新窗口打开" href="{php HYBBS_URL('thread',$v['tid']);}" class="content-bottom" target="_blank">{php HYBBS_URL('thread',$v['tid']);}</a>
						<a href="javascript:;" class="content-bottom" target="_blank">- 赞 {$v.goods}</a>
						<a href="javascript:;" class="content-bottom" target="_blank">- 踩 {$v.nos}</a>
						<a href="javascript:;" class="content-bottom" target="_blank">- 浏览 {$v.views}</a>
						<a href="javascript:;" class="content-bottom" target="_blank">- 帖子 {$v.posts}</a>
						{if $v['img_count']}
						<a href="javascript:;" class="content-bottom" target="_blank">- 图片 {$v.img_count}</a>
						{/if}
						{if $v['files']}
						<a href="javascript:;" class="content-bottom" target="_blank">- 附件 {$v.files}</a>
						{/if}

					</div>
					{/foreach}
					{else}
					<div style="background: #F0F0F0;padding: 37px 10px;border-radius: 5px;color: #454545;text-align: center;">{$message}</div>
					{/if}
					
					<a onclick="return more('{$key}',{$type},this);" class="more" href="{php HYBBS_URL('search','',['key'=>$key,'type'=>$type,'pageid'=>$pageid])}">更多内容...</a>
					
				</section>
			</div>


		</div>
		
		<ul class="bottom-ul">
			<li style="background: rgba(63,133,246,1);border-bottom-left-radius: 15px;">
				
			</li>
			<li style="background: rgba(235,66,52,1);">
				
			</li>
			<li style="background: rgba(251,189,3,1);">
				
			</li>
			<li style="background: rgba(63,133,246,1);">
				
			</li>
			<li style="background: rgba(50,169,82,1);">
				
			</li>
			<li style="background: rgba(235,66,52,1);border-bottom-right-radius: 15px;">
				
			</li>
		</ul>
	</div>
	<script type="text/javascript">
	function tab_radio(obj){
		$('.search-radio .a').removeClass('a');
		$(obj).addClass('a');


	}
	function search(obj){
		window.loading = false;
		$('.container').addClass('a');
		window.pageid = 0;
		$('section div').remove();
		more(obj.key.value,obj.type.value,'.more');

		return false;
	}
	window.pageid = {$pageid};
	window.loading = false;
	function more(key,type,obj){
		var _this = $(obj);
		if(window.loading)
			return;
		window.loading = true;
		_this.text('正在加载新数据...').addClass('a');
		window.history.pushState("","",'/?s=search&key='+key+'&type='+type+'&pageid='+(window.pageid+1));
		$.ajax({
			url:'{php HYBBS_URL('search','')}',
			type:'get',
			data:{key:key,type:type,pageid:++window.pageid},
			dataType:'html',
			success:function(data){
				window.loading = false;
				_this.text('更多内容...').removeClass('a');
				$(obj).after(data.match(/<section.*?>([\s\S]*?)<\/section>/)[1]).remove();

			},
			error:function(data){
				window.loading = false;
				_this.text('更多内容...').removeClass('a');
			}
		});
		return false;
	}
	</script>
</body>
</html>