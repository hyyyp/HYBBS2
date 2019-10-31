<?php
// +----------------------------------------------------------------------
// | HYPHP2.0
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 http://hyphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | 框架开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | 框架作者: Krabs <krabs@live.cn>
// +----------------------------------------------------------------------
// | 作者致谢: ThinkPHP Medoo
// +----------------------------------------------------------------------
//----------------------------------
// 入口文件
//----------------------------------

//框架版本
define('HYPHP_VERSION','2.8');

//开始记录运行时间，运行内存
$GLOBALS['START_TIME'] = microtime(TRUE);
if(function_exists('memory_get_usage')) $GLOBALS['START_MEMORY'] = memory_get_usage();

//时区
date_default_timezone_set('PRC');

//声明编码 UTF8
header("Content-Type: text/html; charset=UTF-8");

//记录脚本访问开始时间，以及用户IP
$_SERVER['time'] = $_SERVER['REQUEST_TIME'];
$GLOBALS['LOAD_CLASS'] = $GLOBALS['SQL_LOG'] =array();

if(!isset($_SERVER["QUERY_STRING"])) $_SERVER["QUERY_STRING"]='';
if(!isset($_SERVER["REMOTE_ADDR"])) $_SERVER["REMOTE_ADDR"]='127.0.0.1';
if(!isset($_SERVER["SERVER_ADDR"])) $_SERVER["SERVER_ADDR"]='';

define('NOW_TIME',$_SERVER['REQUEST_TIME']);


//记录用户访问方式
define('IS_GET',$_SERVER['REQUEST_METHOD'] =='GET' ? true : false);
define('IS_POST',$_SERVER['REQUEST_METHOD'] =='POST' ? true : false);
define('IS_AJAX',
    ((isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') ||
    !empty($_POST['ajax']) ||
    !empty($_GET['ajax'])) ? true : false);


defined('PATH')         or define('PATH',          dirname($_SERVER['SCRIPT_FILENAME']).'/');//网站根目录

defined('ACTION_PATH')  or define('ACTION_PATH',   PATH.'Action/'); //Action目录
defined('VIEW_PATH')    or define('VIEW_PATH',     PATH.'View/'); //VIEW
defined('CONF_PATH')    or define('CONF_PATH',     PATH.'Conf/'); //CONF
defined('TMP_PATH')     or define('TMP_PATH',      PATH.'Tmp/'); //Tmp
//defined('TMPHTML_PATH') or define('TMPHTML_PATH',  PATH.'TmpHtml/'); //TmpHtml
defined('MYLIB_PATH')   or define('MYLIB_PATH',    PATH.'Lib/'); //Lib
defined('MODEL_PATH')   or define('MODEL_PATH',    PATH.'Model/'); //Model
defined('PLUGIN_PATH')  or define('PLUGIN_PATH',    PATH.'Plugin/'); //插件目录

defined('HY_PATH')      or define('HY_PATH',       __DIR__.'/'); //框架目录
defined('LIB_PATH')     or define('LIB_PATH',      realpath(HY_PATH.'Lib').'/'); // 系统核心类库目录
defined('DEBUG')        or define('DEBUG',         false); //是否调试
defined('PLUGIN_ON')    or define('PLUGIN_ON',     false); //插件机制开启

is_dir(ACTION_PATH)    or mkdir(ACTION_PATH);
is_dir(VIEW_PATH)      or mkdir(VIEW_PATH);
is_dir(CONF_PATH)      or mkdir(CONF_PATH);
is_dir(TMP_PATH)       or mkdir(TMP_PATH);
//is_dir(TMPHTML_PATH)   or mkdir(TMPHTML_PATH);
is_dir(MYLIB_PATH)     or mkdir(MYLIB_PATH);
is_dir(MODEL_PATH)     or mkdir(MODEL_PATH);
is_dir(PLUGIN_PATH)    or mkdir(PLUGIN_PATH);

is_file(CONF_PATH   . "config.php") or file_put_contents(CONF_PATH   . "config.php","<?php
return array(
	/*配置项*/
);");
is_file(ACTION_PATH . "Index.php" ) or file_put_contents(ACTION_PATH . "Index.php" ,"<?php
namespace Action;
use HY\Action;
class Index extends Action {
	public function Index(){
		echo 'HY框架';
	}
}");

//修稿WEB服务器 脚本语言声明
header('X-Powered-By:HYPHP');

//命令行运行
if(isset($argv) && count($argv) == 3)
	$GLOBALS['argv']=$argv;

require LIB_PATH.'HY.php';
Lib\HY::init();