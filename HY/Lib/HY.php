<?php
namespace Lib;
class HY
{
	public static function init(){
		//自动加载类
		spl_autoload_register('Lib\\HY::autoload');
		if (DEBUG) {
            error_reporting(E_ALL | E_STRICT);
            //error_reporting(E_ALL & ~(E_NOTICE | E_STRICT));
            @ini_set('display_errors', 'ON');
        } else {
            error_reporting(0);
            @ini_set('display_errors', 'OFF');
        }
        //自定义的错误处理函数
        set_error_handler('Lib\\HY::hy_error');
        //自定义的异常处理函数
        set_exception_handler('Lib\\HY::hy_exception');
        

        $config = 
        include HY_PATH . 'common/conf.php';
        include HY_PATH . 'common/function.php';

        $config = array_merge($config,include CONF_PATH . 'config.php');

        C($config);

        define('EXT',C("url_suffix"));
        define('EXP',C("url_explode"));
        
        define('IS_MOBILE',hy_is_mobile());
        define('IS_SHOUJI',IS_MOBILE);
        define('IS_WAP',IS_MOBILE);

        $_SERVER['ip'] = ip();
        define('CLIENT_IP',$_SERVER['ip']);

        //路由器
        \HY\Lib\Line::run();

        $GLOBALS['END_TIME'] = microtime(TRUE);
        if (C('DEBUG_PAGE')) {
            $DEBUG_SQL = $GLOBALS['SQL_LOG'];
            if (empty($url)) {
                $url = '/';
            } else {
                $url = '/' . $url;
            }
            $DEBUG_CLASS = $GLOBALS['LOAD_CLASS'];
            require HY_PATH . 'View/Debug.php';
        }
	}
	public static function autoload($class){

        if (isset($GLOBALS['LOAD_CLASS'][$class])) {//加载过 
            //echo $class."\r\n";
            return;
        }
        $className = ltrim($class, '\\');  
        $filePath  = '';  
        $namespace = '';  
        if ($lastNsPos = strrpos($className, '\\')) {  
            $namespace = substr($className, 0, $lastNsPos);  
            $className = substr($className, $lastNsPos + 1);  
            $filePath  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;  
        }
        $filePath .= $className .'.php';
        

        
        if (!is_file(PATH . $filePath)) { //自动加载路劲不存在 启用映射搜索
            $vendor_bool = false;
            foreach (C('vendor') as $v) {
                $vendor_path = ltrim($v,'\\/') . DIRECTORY_SEPARATOR . $filePath;
                //echo PATH . $vendor_path." :\r\n<br>";
                if(is_file(PATH . $vendor_path)){
                    //echo PATH . $vendor_path." $\r\n<br>";
                    $filePath = $vendor_path;
                    $vendor_bool=true;
                    break;
                }
            }
            if(!$vendor_bool){
                //E('类库不存在 : ' . $class . ' 加载路径:'.$filePath);
                return false;
            }
                
        }
        $filePath = PATH . $filePath;
        //echo $filePath.' %<br>';
      	$info = explode('\\', $class);
        $agrs =count($info);
        if ($info[0] == 'Model') {
            \HY\Lib\Hook::$include_file[]=$filePath;
            \HY\Lib\hook::$file_type = 'Model';
            if (PLUGIN_ON) {
                $cache_filePath = TMP_PATH . $info[1] . '_' . MD5('Model/' . $info[1]) . C("tmp_file_suffix");
                \HY\Lib\Plugin::run($filePath,$cache_filePath,$class);
                $filePath = $cache_filePath;
            }
        } elseif ($info[0] == 'Action') {
            \HY\Lib\Hook::$include_file[]=$filePath;
            \HY\Lib\hook::$file_type = 'Action';
            if (PLUGIN_ON) {
                $cache_filePath = TMP_PATH . $info[1] . '_' . MD5('Action/' . $info[1]) . C("tmp_file_suffix");
                \HY\Lib\Plugin::run($filePath,$cache_filePath,$class);
                $filePath = $cache_filePath;
            }
        }

        if (empty($filePath)) {
            return false;
        }
       	//echo $filePath.'<br>';
        include_once $filePath;
        $GLOBALS['LOAD_CLASS'][$class] = true;
        return $filePath;
    }
    public static function hy_exception( $e){

        if(!isset($GLOBALS['Exception_save_log']))
            $GLOBALS['Exception_save_log'] = true;
        $file = $e->gettrace();
        $getFile = $e->getFile();
        $getLine = $e->getLine();
        
        if(isset($file[0]['args'][2])){
            $getFile = $file[0]['args'][2];
            if(isset($file[0]['args'][3])){
                if(!is_array($file[0]['args'][3]))
                    $getLine = $file[0]['args'][3];
            }
            
        }


        $s = '';
        $log = New \HY\Lib\Logs;

        if(is_array($getFile)) $getFile = '';
        $text = $e->getMessage() .'  #发生错误的文件位于: '. $getFile .' #行数: ' .$getLine . ' #发生时间: '.date("Y-m-d H:i:s").' ##发生URL: ' . $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] . "\r\n";

        $translate = include LIB_PATH . 'translate.php';
        foreach ($translate as $key => $value) {
            $text = str_replace($key,$value,$text);
        }
        if($GLOBALS['Exception_save_log']){

            $log->log($text);
            //die($text);
        }


        if (DEBUG) {
            if(!isset($GLOBALS['HEADER_STATE'])) $GLOBALS['HEADER_STATE']=0;
            if(!$GLOBALS['HEADER_STATE']){
                $GLOBALS['HEADER_STATE']=1;
                if(IS_AJAX){

                    header('HTTP/1.1 200 OK'); 
                    header('Content-Type:application/json; charset=utf-8');
                    die(json_encode(array('error'=>false,'info'=>$text,'data'=>$text)));
                }
                else{
                    header('HTTP/1.1 404 Not Found'); 
                    header('status: 404 Not Found');
                    $s = \HY\Lib\exception::to_html($e);
                    echo $s;
                    exit;
                }
            }
        } elseif($e->getCode()) {
            header('HTTP/1.1 404 Not Found'); 
            header('status: 404 Not Found');
            $s = $e->getMessage();
            include C("error_404");
            
        }else{
            header('HTTP/1.1 404 Not Found'); 
            header('status: 404 Not Found');
            $s = $e->getMessage();
            include C("error_404");
        }
        
    }
    public static function hy_error($Error_Type, $Error_str,$Error_file, $Error_line,$errcontext){
        


        if(isset($_SERVER['ob_start']) && DEBUG){
            unset($_SERVER['ob_start']);
            ob_end_clean();
        }
        

        
        //var_dump($s);
        
        if (DEBUG) {
            return self::hy_exception( new \ErrorException( $Error_str, 0, $Error_Type, $Error_file, $Error_line ) );
        } else {
            $Error_China = array(
                E_ERROR => '错误', 
                E_WARNING => '警告', 
                E_PARSE => '解析错误', 
                E_NOTICE => '注意', 
                E_CORE_ERROR => '核心错误', 
                E_CORE_WARNING => '核心警告', 
                E_COMPILE_ERROR => '编译错误', 
                E_COMPILE_WARNING => '编译警告', 
                E_USER_ERROR => '用户错误', 
                E_USER_WARNING => '用户警告', 
                E_USER_NOTICE => 'User Notice', 
                E_STRICT => 'Runtime Notice'
            );
            $s = "错误类型({$Error_Mun}) : {$Error_str}";
            $Error_Mun = isset($Error_China[$Error_Type]) ? $Error_China[$Error_Type] : '未知';
            $translate = include LIB_PATH . 'translate.php';
            foreach ($translate as $key => $value) {
                $s = str_replace($key,$value,$s);
            }
            $log = New \HY\Lib\Logs;
            $log->log($s.' #错误来自于:'.$Error_file.' #行数:'.$Error_line."\r\n");
        }


        return 0;
    }
}