<?php
if(version_compare(PHP_VERSION,'5.4.0','<')){
	header("Content-Type: text/html; charset=UTF-8");
	die('HYPHP2.0 不支持 5.4以下的PHP版本.  当前你的PHP版本：' . PHP_VERSION);
}

define('HYBBS_V'			,'2.2');
define('INDEX_PATH' 		, str_replace('\\', '/', dirname(__FILE__)).'/');
define('DEBUG'      ,(is_file(INDEX_PATH . 'DEBUG'))?false:true);
define('PLUGIN_ON'  ,true);
define('PLUGIN_ON_FILE',true);
define('PLUGIN_MORE_LANG_ON',true);

require  'HY/HYPHP.php';
/*
优化 - 后台管理页面丰富各种数据搜索
优化 - 后台插件 增加强制 安装卸载函数 勾选
优化 - 增加开发函数 get_plugin_inc_v($plugin_name,$v) 获取某个插件配置的某个值
优化 - 论坛function.php 增加函数函数get_theme_version 以及 get_theme_inc 函数，用于获取主题版本和配置信息
优化 - Model增加DataModel 用于集合论坛快捷读写数据 相关API可阅读此文件/Model/Data.php
优化 - 修改数据缓存 post_data_$tid 将帖子数据错写为主题帖子ID的问题 虽然不会产生冲突 但关键字影响开发者判断
优化 - 评论帖子数据增加新缓存方式，以前是评论列表式缓存，现在在基础上多增加独立缓存，每个帖子数据都独立缓存 有助于引用回复读取效率
优化 - ZIP更新，解决以前出现的解压问题，更新为PclZip支持, Admin.php已经采用新ZIP支持
优化 - 删除主题并删除主题附件购买记录数据（%）
优化 - 前台、后台删除主题并删除附件信息、本地文件、主题投票数据、主题点评数据、主题购买数据

增加 - 引用回复
增加 - 用户组自定义颜色以及CSS（模板还未用上 目前后台可以设置）


修复 - 修改用户组被退回的问题
修复 - (Action)Post.php 上传图片 某变量缺少 _ 导致出错
修复 - 框架 X() 函数 返回默认值为空的问题
修复 - 编辑帖子删除附件 没有删除附件信息以及 附件文件的问题 此修复只针对附件，不针对图片附件，由于考虑到图片多次引用 一旦删除将会导致其他帖子对此图片失效
修复 - 编辑帖子附件信息 导致下载次数丢失的问题
修复 - 删除主题一并删除附件信息 以及附件文件 不包括图片附件
修复 - 缓存文件非法代码漏洞



框架 - 增加函数 strpos_array($string,$find_arr); strpos arr版
框架 - 增加插件诊断，插件报错诊断，显示具体插件、名称、具体文件路径 
框架 - 标签解析增加 {:phpinfo()} 相当解析为 <?php echo phpinfo(); ?>
框架 - 修复header 协议头重复修改 导致的调试错误显示异常位置



收藏帖子
个人中心 table
重写好友系统  分开为系统消息系统 和 私信系统
重新部署 互联登陆系统 统一页面 (例如 QQ 微信 等第三方登陆 统一页面)
重新部署 充值系统 统一页面
新移动端模板 (移除激进开发)
	HY-editor 自制HTML5富文本编辑器（后期移动端，APP端）都会采用此编辑器！
	#HYPHP增加插件诊断 （如果在插件内运行出错 会提示是哪个插件的问题） 更好的排查出错
用户组名称 增加颜色

优化部分细节
未完待续

ALTER TABLE `hy_post` ADD `rpid` INT UNSIGNED NOT NULL DEFAULT '0' AFTER `uid`;
ALTER TABLE `hy_usergroup` ADD `font_css` LONGTEXT NOT NULL AFTER `name`;
ALTER TABLE `hy_usergroup` ADD `font_color` VARCHAR(30) NOT NULL DEFAULT '' AFTER `name`;

 */
