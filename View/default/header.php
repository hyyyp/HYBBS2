<?php !defined('HY_PATH') && exit('HY_PATH not defined.'); ?>
<!doctype html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="{#WWW}public/bootstrap4/css/bootstrap.min.css">
    <link rel="stylesheet" href="{#WWW}public/css/public.css?ver={#HYBBS_V}">
    <link rel="stylesheet" href="{#WWW}View/default/css/app.css?ver={#HYBBS_V}">
    <title>{$title}{$conf.title2}</title>
    <script src="{#WWW}public/js/jquery3.min.js"></script>
    <script src="{#WWW}public/js/app.js"></script>
    <script src="{#WWW}View/default/js/app.js"></script>
    <script>
	var www = "{#WWW}{#RE}";
	var WWW = "{#WWW}";
	var exp = "{#EXP}";
	</script>
    <style type="text/css">
    	html{
    		position: relative;
    		min-height: 100%;
    	}
    	.sticky-body{
    		margin-bottom: 108px;
    	}
    	.footer{
		    position: absolute;
		    bottom: 0px;
		    width: 100%;
		    height: 88px;
		    box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.14), 0 3px 1px -2px rgba(0, 0, 0, 0.2), 0 1px 5px 0 rgba(0, 0, 0, 0.12);
		    -moz-box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.14), 0 3px 1px -2px rgba(0, 0, 0, 0.2), 0 1px 5px 0 rgba(0, 0, 0, 0.12);
		    -webkit-box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.14), 0 3px 1px -2px rgba(0, 0, 0, 0.2), 0 1px 5px 0 rgba(0, 0, 0, 0.12);
		    background: #fff;
		    padding: 10px 0;
    	}
    	.footer p {
		    color: #8590a6;
		    line-height: 1.8;
		    margin: 0;
		}
		.thread-list-title .badge{
			vertical-align: text-bottom;
		}
    </style>
</head>
<body class="sticky-body">
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm border-bottom">
    	<div class="container">
	        <a class="navbar-brand" href="{#WWW}">HYBBS</a>
	        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
	            <span class="navbar-toggler-icon"></span>
	        </button>
	        <div class="collapse navbar-collapse" id="navbarSupportedContent">
	            <ul class="navbar-nav mr-auto">
	                <li class="nav-item active">
	                    <a class="nav-link" href="{php HYBBS_URL('forum')}">全部板块</a>
	                </li>
	                <li class="nav-item active">
	                    <a class="nav-link" href="{php HYBBS_URL('search')}" style="line-height: 20px;">
	                    	<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" role="img" viewBox="0 0 24 24" focusable="false"><title>Search</title><circle cx="10.5" cy="10.5" r="7.5"></circle><path d="M21 21l-5.2-5.2"></path></svg>
	                    </a>
	                </li>
	               
	                
	            </ul>
	            <!-- <form class="form-inline my-2 my-lg-0">
	                <input class="form-control mr-sm-2" type="search" placeholder="搜索帖子、用户" aria-label="Search">
	                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">搜索</button>
	            </form> -->
	            {if IS_LOGIN}
				<nav class="my-2 my-md-0 mr-md-3">
					<a class=" text-dark badge badge-light" href="#">
						<img src="{#WWW}View/default/news.svg" alt="" width="30" height="30">
					</a>
					<a class="px-2 text-dark text-decoration-none badge badge-light" href="#">
						<img src="{#WWW}{$user.avatar.b}" class="rounded-circle mr-2" style="width: 30px;height: 30px">
						{$user.user}
					</a>
				</nav>

	            {else}
	            <div class="ml-2">
	            	<a href="{php HYBBS_URL('user','login');}" class="btn btn-light btn-sm">登录</a>
	            	<a href="{php HYBBS_URL('user','add');}" class="btn btn-warning btn-sm">注册</a>
	            </div>
	            {/if}
	        </div>
        </div>
    </nav>