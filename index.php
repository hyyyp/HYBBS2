<?php
if(version_compare(PHP_VERSION,'5.4.0','<')){
	header("Content-Type: text/html; charset=UTF-8");
	die('HYPHP2.0 不支持 5.4以下的PHP版本.  当前你的PHP版本：' . PHP_VERSION);
}

define('HYBBS_V'			,'2.2.10');
define('INDEX_PATH' 		, str_replace('\\', '/', dirname(__FILE__)).'/');
define('DEBUG'      ,(is_file(INDEX_PATH . 'DEBUG'))?false:true);
define('PLUGIN_ON'  ,true);
define('PLUGIN_ON_FILE',true);
define('PLUGIN_MORE_LANG_ON',true);

require  'HY/HYPHP.php';
/*

修复 - 编辑帖子目录不存在的问题
修复 - 框架缓存类库一处代码没有正确判断缓存是否存在而删除的问题
修复 - Post 编辑帖子处理删除旧文件时 没有正确判断旧文件是否存在的问题
修复 - MYSQL8.0无法使用HYBBS的问题
修复 - 后台无法一键删除用户所有子评论、点评的问题
修复 - 后台用户一键删除主题、删除评论，没有删除附件的问题
修复 - 后台用户一键删除所有上传文件，增加删除主题，评论附件。
修复 - 后台删除用户，没有删除干净附件


ALTER TABLE `hy_fileinfo` CHANGE `gold` `gold` INT(10) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `hy_fileinfo` CHANGE `hide` `hide` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `hy_fileinfo` CHANGE `downs` `downs` INT(10) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `hy_fileinfo` CHANGE `mess` `mess` TEXT NULL DEFAULT NULL;

ALTER TABLE `hy_forum` CHANGE `forumg` `forumg` TEXT NULL DEFAULT NULL;
ALTER TABLE `hy_forum` CHANGE `json` `json` TEXT NULL DEFAULT NULL;
ALTER TABLE `hy_forum` CHANGE `html` `html` LONGTEXT NULL DEFAULT NULL;
ALTER TABLE `hy_forum` CHANGE `color` `color` VARCHAR(30) NOT NULL DEFAULT '';
ALTER TABLE `hy_forum` CHANGE `background` `background` VARCHAR(30) NOT NULL DEFAULT '';

ALTER TABLE `hy_log` CHANGE `gold` `gold` INT(11) NOT NULL DEFAULT '0';
ALTER TABLE `hy_log` CHANGE `credits` `credits` INT(11) NOT NULL DEFAULT '0';
ALTER TABLE `hy_log` CHANGE `content` `content` VARCHAR(32) NOT NULL DEFAULT '';

ALTER TABLE `hy_user` CHANGE `threads` `threads` INT(10) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `hy_user` CHANGE `posts` `posts` INT(10) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `hy_user` CHANGE `post_ps` `post_ps` INT(10) UNSIGNED NOT NULL DEFAULT '0';

ALTER TABLE `hy_usergroup` CHANGE `font_css` `font_css` LONGTEXT NULL DEFAULT NULL;
ALTER TABLE `hy_usergroup` CHANGE `json` `json` TEXT NULL DEFAULT NULL;



收藏帖子

重新部署 互联登陆系统 统一页面 (例如 QQ 微信 等第三方登陆 统一页面)
重新部署 充值系统 统一页面

优化部分细节
未完待续

 */
