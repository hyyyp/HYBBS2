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
优化 - Upload组件 检查php.ini环境是否正常 给出正常提示
优化 - Post.php增加最后编辑帖子UID 以前只记录时间，这次为了区别非文章作者修改而记录
优化 - 文章文件上传管理更严谨
优化 - 编辑帖子，如果文章删除不使用图片会随着编辑删除
优化 - 删除帖子，会删除帖子下所有文件包括图片以及附件
优化 - 后台批量删除文章，删除文章所有包含图片以及附件
优化 - 后台批量删除评论，同上
优化 - 后台批量删除用户，同上
优化 - 编辑器重写上传图片插件 支持网络图片 优化拉伸图片组件
优化 - 增加视频，音频上传
优化 - 后台支持独立开关视频音频上传开关。
优化 - 后台用户组权限增加 上传视频音频权限



修复 - 删除自己帖子会发送被删除消息的BUG
修复 - 删除主题，删除缓存代码中出现两次$$的BUG
修复 - 编辑器点按钮没有插入内容的问题
修复 - 后台编辑用户组权限 由于SQL结构问题长度太小 导致编辑权限恢复为默认的问题
修复 - 个人中心消息页面头像显示问题
修复 - 移动端编辑器 Modal对话框 显示在底部 无法弹出中间的问题

插件新特性 - 插件function.php函数可以增加 plugin_on 和 plugin_off 函数，当用户启动插件会触发 plugin_on函数 反之触发off函数
论坛开发 - function增加多个函数 具体详情需自己查看文件

框架 - 优化X函数 如果X函数第一参数不输入前缀 则默认查看GET 再看POST是否有内容 从而返回。例：X('get.user') 现在可以 X('user')

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
