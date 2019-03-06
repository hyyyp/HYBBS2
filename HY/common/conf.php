<?php
return array(
	'var_left_tpl'		=>	'{',
	'var_right_tpl'		=>	'}',
	'tpl_suffix'		=>	array('.html','.php'),
	'url_suffix'		=>	'.html',
	'tmp_file_suffix'		=>	'.php',
	'url_explode'		=>	'/',
	'tmp_del_time'		=>	0,
	'tmphtml_del_time'	=>	0,
	'DEBUG_PAGE'		=>	true,
	'HOOK_SUFFIX'		=>	".hook",
	'error_404'			=>	HY_PATH . "View/404.html",

	//多语言选择
	'default_lang'		=>	'zh-cn',
	'auto_lang'			=>	true,
	'lang_switch_on'	=>	false,
	'lang_var'			=>	'lang',

	'TMP_PATH_KEY'		=>	'',
	'TMP_PATH_PREFIX'	=>	'',


	//缓存类
	
	'DATA_CACHE_TYPE'	=>	'File',
	'DATA_CACHE_TIME'	=>	0,
	'DATA_CACHE_TABLE'	=>	'cache',
	'DATA_CACHE_PREFIX'	=>	'',
	'DATA_CACHE_COMPRESS'	=>	false, //开启缓存数据压缩 gzcompress
	'DATA_CACHE_PATH'	=>	TMP_PATH . 'cache',
	'DATA_CACHE_KEY'	=>	'',

	//数据库
	
	"SQL_IP"			=>	"localhost",
	'SQL_PREFIX'		=>	'hy_',
	'SQL_PORT' 			=>	3306,
	'SQL_CHARSET' 		=>	'utf8',
	'SQL_OPTION' 		=> array(
						        PDO::ATTR_CASE => PDO::CASE_NATURAL,
						        PDO::ATTR_PERSISTENT => true //长连接
						    ),

	//驱动导入
	'vendor'			=> array('vendor'),


	//CDN
	'CDN_IP'				=> false,

);