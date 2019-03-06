<?php !defined('HY_PATH') && exit('HY_PATH not defined.'); ?>
<!DOCTYPE html>
<html>
<head lang="zh-cn">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="<?php echo $conf['description'];?>">
  <meta name="keywords" content="<?php echo $conf['keywords'];?>">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <title><?php echo $title;?><?php echo $conf['title2'];?></title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="Cache-Control" content="no-siteapp"/>
  <link rel="alternate icon" type="image/png" href="<?php echo WWW;?>favicon.ico">
  <link rel="apple-touch-icon-precomposed" href="<?php echo WWW;?>favicon.ico">
  <meta name="apple-mobile-web-app-title" content="HYUI"/>
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  
  
    
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
  <meta name="format-detection" content="telephone=no">
  <meta name="format-detection" content="email=no">

  
  <script src="<?php echo WWW;?>public/js/jquery.min.js"></script>
  <link rel="stylesheet" href="<?php echo WWW;?>public/css/public.css?ver=<?php echo get_theme_version('hy_moblie');?>">
  <link rel="stylesheet" href="<?php echo WWW;?>hyui/ui.css?var=<?php echo HYBBS_V;?>"/>
  <link rel="stylesheet" href="<?php echo WWW;?>hyui/style.css?var=<?php echo HYBBS_V;?>"/>
  <link rel="stylesheet" href="<?php echo WWW;?>View/hy_moblie/app.css?var=2.4"/>
  
  <script>
  var www = "<?php echo WWW;?><?php echo RE;?>";
  var WWW = "<?php echo WWW;?>";
  var exp = "<?php echo EXP;?>";
  var action_name = "<?php echo ACTION_NAME;?>";
  var method_name = "<?php echo METHOD_NAME;?>";
  <?php if (IS_LOGIN): ?>
  window.hy_user = "<?php echo NOW_USER;?>";
  window.hy_avatar = "<?php echo $user['avatar']['a'];?>";
  <?php else: ?>
  window.hy_user = '';
  window.hy_avatar = '';
  <?php endif ?>
  
  </script>
  
  <script src="<?php echo WWW;?>hyui/hy.js?var=<?php echo HYBBS_V;?>"></script>
  <script src="<?php echo WWW;?>View/hy_moblie/app.js?var=2.4"></script>
  
  <script src="<?php echo WWW;?>public/js/app.js?var=<?php echo HYBBS_V;?>"></script>
  <link href="<?php echo WWW;?>public/css/alert.css?var=<?php echo HYBBS_V;?>" rel="stylesheet">
  
  
</head>
<body hide_size="0">

<header class="hy-header hy-fix-t">

<a href="javascript:history.back(-1)" class="hy-header-nav hy-header-left icon icon-chevron-small-left" ></a>

<h1 class="hy-header-title"><?php echo $_LANG['提示信息'];?></h1>

<?php if (ACTION_NAME != 'User'): ?>
<a class="hy-header-nav hy-header-right icon icon-menu" onclick="$.hy.canvas_show1('right')"></a>
<?php endif ?>

</header>


<section class="body">
	<div class="hy-box" style="margin-top:10px;padding:10px;text-align: center;">
	<p class="hy-font-danger" style="font-size:18px;font-weight: bold;margin-bottom: 10px;"><?php echo $title;?></p>
	
	<span style="font-size:16px"><?php echo $msg;?></span>
	</div>
	<div class="hy-box" style="margin-top:10px;padding:10px">
		
		<button type="button" class="hy-btn hy-btn-danger " onclick="window.location.href='<?php echo WWW;?>'"><?php echo $_LANG['前往论坛'];?></button>
		<button type="button" class="hy-btn hy-btn-danger r hy-btn-outlined" onclick="window.location.href=document.referrer"><?php echo $_LANG['返回上一页'];?></button>
		
	</div>
</section>


</body>
</html>