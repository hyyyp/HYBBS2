<?php
if(version_compare(PHP_VERSION,'5.4.0','<')){
	header("Content-Type: text/html; charset=UTF-8");
	die('HYPHP2.0 不支持 5.4以下的PHP版本.  当前你的PHP版本：' . PHP_VERSION);
}

define('HYBBS_V'			,'2.2.3');
define('INDEX_PATH' 		, str_replace('\\', '/', dirname(__FILE__)).'/');
define('DEBUG'      ,(is_file(INDEX_PATH . 'DEBUG'))?false:true);
define('PLUGIN_ON'  ,true);
define('PLUGIN_ON_FILE',true);
define('PLUGIN_MORE_LANG_ON',true);

require  'HY/HYPHP.php';
/*

优化 - 更新机制 跳过重复执行SQL insert主键语句

修复 - 编辑帖子出现目录不存在的BUG
修复 - 新升级机制 可能下载文件为空 还保存 导致的更新空白文件的BUG
修复 - 新升级机制检查更新URL写错的问题 导致部分用户 没有伪静态环境的升级不了



需要修复 部分隐藏插件


收藏帖子

重新部署 互联登陆系统 统一页面 (例如 QQ 微信 等第三方登陆 统一页面)
重新部署 充值系统 统一页面
	HY-editor 自制HTML5富文本编辑器（后期移动端，APP端）都会采用此编辑器！

优化部分细节
未完待续


ALTER TABLE `hy_thread` ADD `euid` INT UNSIGNED NOT NULL DEFAULT '0' COMMENT '最后编辑UID' AFTER `etime`;
ALTER TABLE `hy_post` ADD `euid` INT UNSIGNED NOT NULL DEFAULT '0' COMMENT '最后编辑UID' AFTER `etime`;
UPDATE `hy_file` SET `file_type` = '2';
ALTER TABLE `hy_file` ADD `tid` INT UNSIGNED NOT NULL DEFAULT '0' AFTER `uid`, ADD INDEX (`tid`);
ALTER TABLE `hy_file` ADD `pid` INT UNSIGNED NOT NULL DEFAULT '0' AFTER `tid`, ADD INDEX (`pid`);

ALTER TABLE `hy_file` ADD `md5` CHAR(32) NULL DEFAULT NULL AFTER `md5name`, ADD UNIQUE (`md5`);
ALTER TABLE `hy_file` ADD UNIQUE (`uid`, `md5`);
INSERT INTO `hy_file_type` (`id`, `name`) VALUES ('3', '视频'), ('4', '音频');
ALTER TABLE `hy_usergroup` CHANGE `json` `json` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
 */
