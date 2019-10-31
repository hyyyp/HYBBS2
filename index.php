<?php
if(version_compare(PHP_VERSION,'5.4.0','<')){
	header("Content-Type: text/html; charset=UTF-8");
	die('HYPHP2.0 不支持 5.4以下的PHP版本.  当前你的PHP版本：' . PHP_VERSION);
}

define('HYBBS_V'			,'2.3');
define('INDEX_PATH' 		, str_replace('\\', '/', dirname(__FILE__)).'/');
define('DEBUG'      ,(is_file(INDEX_PATH . 'DEBUG'))?false:true);
define('PLUGIN_ON'  ,true);
define('PLUGIN_ON_FILE',true);
define('PLUGIN_MORE_LANG_ON',true);

require  'HY/HYPHP.php';
/*
优化 - 加入精华帖
优化 - 支持用户修改用户名
优化 - 增加收藏帖子
优化 - 后台主题评论页面 优化时间显示 显示最后编辑时间 最后编辑人

修复 - 后台删除评论 删除附带附件时产生的额外的目录

框架 - Medoo1.4版本升级到1.7.6
框架 - DEBUG_PAGE 优化数据库操作记录，显示的SQL语句可以直接拿到数据库执行。		

ALTER TABLE `hy_thread` ADD COLUMN `digest` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否精华' AFTER `hide`;

CREATE TABLE if not exists `hy_thread_star` (
  `uid` int(10) UNSIGNED NOT NULL,
  `tid` int(10) UNSIGNED NOT NULL,
  `atime` int(10) UNSIGNED NOT NULL,
  UNIQUE KEY `uid_tid` (`uid`,`tid`),
  INDEX `atime` (`atime`)
) ENGINE = #SQL_STORAGE_ENGINE# DEFAULT CHARSET=utf8;


ALTER TABLE `hy_thread` ADD `recycling` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' AFTER `state`;
ALTER TABLE `hy_thread` DROP `recycling`;




重新部署 互联登陆系统 统一页面 (例如 QQ 微信 等第三方登陆 统一页面)
重新部署 充值系统 统一页面

优化部分细节
未完待续

 */
