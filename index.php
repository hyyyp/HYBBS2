<?php
if(version_compare(PHP_VERSION,'5.4.0','<')){
	header("Content-Type: text/html; charset=UTF-8");
	die('HYPHP2.0 不支持 5.4以下的PHP版本.  当前你的PHP版本：' . PHP_VERSION);
}

define('HYBBS_V'			,'2.2.2');
define('INDEX_PATH' 		, str_replace('\\', '/', dirname(__FILE__)).'/');
define('DEBUG'      ,(is_file(INDEX_PATH . 'DEBUG'))?false:true);
define('PLUGIN_ON'  ,true);
define('PLUGIN_ON_FILE',true);
define('PLUGIN_MORE_LANG_ON',true);

require  'HY/HYPHP.php';
/*

优化 - 更新升级论坛方式换新 采用单文件逐个更新，不再使用全量解压覆盖更新。
修复 - 个人中心私信头像问题
修复 - 管理员无法编辑用户帖子内的附件
修复 - 默认登录页面 新头像算法 不显示头像的问题
修复 - 更新HYEditor编辑器 悬浮菜单 不动的问题
修复 - 默认模板侧边栏悬浮突出底部的问题

框架 - 解决日志不换行问题


s上传文件记录数据库

收藏帖子

重新部署 互联登陆系统 统一页面 (例如 QQ 微信 等第三方登陆 统一页面)
重新部署 充值系统 统一页面
	HY-editor 自制HTML5富文本编辑器（后期移动端，APP端）都会采用此编辑器！

优化部分细节
未完待续

 */
