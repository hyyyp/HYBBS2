<?php
if(version_compare(PHP_VERSION,'5.4.0','<')){
	header("Content-Type: text/html; charset=UTF-8");
	die('HYPHP2.0 不支持 5.4以下的PHP版本.  当前你的PHP版本：' . PHP_VERSION);
}

define('HYBBS_V'			,'2.2.1');
define('INDEX_PATH' 		, str_replace('\\', '/', dirname(__FILE__)).'/');
define('DEBUG'      ,(is_file(INDEX_PATH . 'DEBUG'))?false:true);
define('PLUGIN_ON'  ,true);
define('PLUGIN_ON_FILE',true);
define('PLUGIN_MORE_LANG_ON',true);

require  'HY/HYPHP.php';
/*

优化 - 修改头像获取方式 增加函数get_avatar($uid);
优化 - 个人中心增加tab导航
优化 - 个人中心增加用户组显示 且使用后台用户组新自定义颜色以及css
优化 - 个人中心增加用户组升级进度条
优化 - 个人中心增加私信页面

修复 - 百度编辑器多图上传 在Mac谷歌非https环境下 无法使用的问题

框架 - 优化文件缓存 修改缓存文件后缀名为.cache
框架 - 优化Model增加查询缓存，数据缓存

s上传文件记录数据库

收藏帖子

重新部署 互联登陆系统 统一页面 (例如 QQ 微信 等第三方登陆 统一页面)
重新部署 充值系统 统一页面
	HY-editor 自制HTML5富文本编辑器（后期移动端，APP端）都会采用此编辑器！

优化部分细节
未完待续

 */
