<?php
function A($name){
    $class="\Action\\{$name}";
    $obj = new $class;
    return $obj;
}
function X($name,$default = ''){
    $data = explode(".",$name);
    if(strpos($name,'.') === false){
        if(isset($_GET[$name]))
            return $_GET[$name];
        if(isset($_POST[$name]))
            return $_POST[$name];
    }

    if(count($data) == 2){
        $v = $data[1];
        if($data[0]=='get'){
            return isset($_GET[$v])?$_GET[$v]:$default;
        }elseif($data['0']=='post'){
            return isset($_POST[$v])?$_POST[$v]:$default;
        }elseif($data['0']=='session'){
            return isset($_SESSION[$v])?$_SESSION[$v]:$default;
        }elseif($data['0']=='cookie'){
            return isset($_COOKIE[$v])?$_COOKIE[$v]:$default;
        }elseif($data['0']=='server'){
            return isset($_SERVER[$v])?$_SERVER[$v]:$default;
        }
    }
    return $default;
}
//实例Model
function S($name,$more=''){
    $obj = new \HY\Model(strtolower($name),$more);
    return $obj;
}
//SQL实例
function M($name,$more=''){
    $class="\Model\\{$name}";
    $obj = new $class(strtolower($name),$more);
    return $obj;
}

//实例 Lib库
function L($name){
    $class = "Lib\\{$name}";
    $obj = new $class;
    return $obj;
}

//获取设置 配置文件
function C($name=null, $value=null,$default=null) {
    static $_config = array();
    // 无参数时获取所有
    if (empty($name)) {
        return $_config;
    }
    // 优先执行设置获取或赋值
    if (is_string($name)) {
        if (!strpos($name, '.')) {
            $name = strtoupper($name);
            if (is_null($value))
                return isset($_config[$name]) ? $_config[$name] : $default;
            $_config[$name] = $value;
            return null;
        }
        // 二维数组设置和获取支持
        $name = explode('.', $name);
        $name[0]   =  strtoupper($name[0]);
        if (is_null($value))
            return isset($_config[$name[0]][$name[1]]) ? $_config[$name[0]][$name[1]] : $default;
        $_config[$name[0]][$name[1]] = $value;
        return null;
    }
    // 批量设置
    if (is_array($name)){
        $_config = array_merge($_config, array_change_key_case($name,CASE_UPPER));
        return null;
    }
    return null; // 避免非法参数
}


function cookie($name='', $value='',$expire=0) {
    $name = str_replace('.', '_', $name);
    if ('' === $value) {
        if(''===$name){
            return $_COOKIE;
        }elseif(isset($_COOKIE[$name])){
            $value =    $_COOKIE[$name];
            return $value;
        }else{
            return null;
        }
    } else {
        if (is_null($value)) {
            setcookie($name, '', NOW_TIME - 3600,'/');
            unset($_COOKIE[$name]); // 删除指定cookie
        } else {
            // 设置cookie

            $expire = !empty($expire) ? NOW_TIME + intval($expire) : 0;
            setcookie($name, $value, $expire,'/');
            $_COOKIE[$name] = $value;
        }
    }
    return null;
}
function session($name='',$value='') {

    if('' === $value){
        if(''===$name){
            // 获取全部的session
            return $_SESSION;
        }elseif(0===strpos($name,'[')) { // session 操作
            if('[pause]'==$name || '[stop]'==$name){ // 暂停session
                session_write_close();
            }elseif('[start]'==$name){ // 启动session
                session_start();
            }elseif('[destroy]'==$name || '[end]'==$name ){ // 销毁session
                $_SESSION =  array();
                session_unset();
                session_destroy();
            }elseif('[regenerate]'==$name){ // 重新生成id
                session_regenerate_id();
            }
        }elseif(0===strpos($name,'?')){ // 检查session
            $name   =  substr($name,1);
            if(strpos($name,'.')){ // 支持数组
                list($name1,$name2) =   explode('.',$name);
                return isset($_SESSION[$name1][$name2]);
            }else{
                return isset($_SESSION[$name]);
            }
        }elseif(is_null($name)){ // 清空session

            $_SESSION = array();


        }else{
            if(strpos($name,'.')){
                list($name1,$name2) =   explode('.',$name);
                return isset($_SESSION[$name1][$name2])?$_SESSION[$name1][$name2]:null;
            }else{
                return isset($_SESSION[$name])?$_SESSION[$name]:null;
            }
        }
    }elseif(is_null($value)){ // 删除session
        if(strpos($name,'.')){
            list($name1,$name2) =   explode('.',$name);

                unset($_SESSION[$name1][$name2]);

        }else{

                unset($_SESSION[$name]);

        }
    }else{ // 设置session
		if(strpos($name,'.')){
			list($name1,$name2) =   explode('.',$name);

				$_SESSION[$name1][$name2]  =  $value;

		}else{

				$_SESSION[$name]  =  $value;

		}
    }
    return null;
}
function put_tmp_file($path,$content){
    if(!is_dir(TMP_PATH))
        return;
    file_put_contents($path,"<?php !defined('HY_PATH') && exit('HY_PATH not defined.'); ?>\r\n" . $content);

}
//URL生成
function URL($action,$method='',$age=[],$ext=''){ //age 参数 exp分隔符
    $action_arr = C("HY_URL.action");
    $method_arr = C("HY_URL.method");


    if(preg_match('/^[A-Za-z](\/|\w)*$/',$action))
        $url=(isset($action_arr[$action])?$action_arr[$action]:$action);
    else
        $url = $action;

    if(is_array($age)){
        $tmp='';
        $count = count($age);
        $i=0;
        foreach ($age as $key => $v) {
            $tmp.=$key.EXP.$v;
            if($count > ++$i){
                $tmp.=EXP;
            }
        }
        $age = $tmp;
    }

    if(preg_match('/^[A-Za-z](\/|\w)*$/',$method))
        $url.=(isset($method_arr[$action][$method]) ? EXP.$method_arr[$action][$method] : ($method===''?'':EXP.$method)). ($age===[] || $age ==='' || $age === false?'':EXP.$age) ;
    else
        $url.=($method===''?'':EXP.$method) . ($age===[] || $age ==='' || $age === false?'':EXP.$age) ;

    return $url . (empty($ext)?EXT:$ext);
}
function E($QQ,$save_log=true){
    // 如果你看到此错误,请不要详细查看此处, 此处无错误,请看上面的"错误信息"
    $GLOBALS['Exception_save_log']=$save_log;
    throw new \Exception($QQ,4174201);
}
//判断你手机访问 
function hy_is_mobile(){
 static $is_mobile;
 
 if( isset($is_mobile))
   return $is_mobile;
 
 if( empty($_SERVER['HTTP_USER_AGENT'])){
   $is_mobile =false;
 } 
 else if ( strpos($_SERVER['HTTP_USER_AGENT'],'Mobile')!==false
   || strpos($_SERVER['HTTP_USER_AGENT'],'Android')!==false
   || strpos($_SERVER['HTTP_USER_AGENT'],'Silk/')!==false
   || strpos($_SERVER['HTTP_USER_AGENT'],'Kindle')!==false
   || strpos($_SERVER['HTTP_USER_AGENT'],'BlackBerry')!==false
   || strpos($_SERVER['HTTP_USER_AGENT'],'Opera Mini')!==false){
   $is_mobile =true;
 }else{
   $is_mobile =false;
 }
 
 return $is_mobile;
}
//
function cache($name,$value='',$options=null){
    static $cache   =   '';
    if(is_array($options)){
        // 缓存操作的同时初始化
        $type       =   isset($options['type'])?$options['type']:'';
        $cache      =   HY\Lib\Cache::getInstance($type,$options);
    }elseif(is_array($name)) { // 缓存初始化
        $type       =   isset($name['type'])?$name['type']:'';
        $cache      =   HY\Lib\Cache::getInstance($type,$name);
        return $cache;
    }elseif(empty($cache)) { // 自动初始化
        $cache      =   HY\Lib\Cache::getInstance();
    }
    if(''=== $value){ // 获取缓存
        return $cache->get($name);
    }elseif(is_null($value)) { // 删除缓存
        return $cache->rm($name);
    }else { // 缓存数据
        if(is_array($options)) {
            $expire     =   isset($options['expire'])?$options['expire']:NULL;
        }else{
            $expire     =   is_numeric($options)?$options:NULL;
        }
        return $cache->set($name, $value, $expire);
    }
}
/**
 * 根据PHP各种类型变量生成唯一标识号
 * @param mixed $mix 变量
 * @return string
 */
function to_guid_string($mix) {
    if (is_object($mix)) {
        return spl_object_hash($mix);
    } elseif (is_resource($mix)) {
        $mix = get_resource_type($mix) . strval($mix);
    } else {
        $mix = serialize($mix);
    }
    return md5($mix);
}
//文件缓存
function F($name,$value=''){
    static $file_cache   =   '';
    if(empty($file_cache))
        $file_cache = cache(array('type'=>'file'));
    if(is_null($value)) //删除缓存吗
        return $file_cache->rm($name);
    elseif($value === '') //获取缓存
        return $file_cache->get($name);
    else
        return $file_cache->set($name,$value);
}
//映射驱动
function vendor($path){
    $vendor_arr = C('vendor');
    array_push($vendor_arr, $path);
    C('vendor',$vendor_arr);
}
//获取IP
function ip(){
    if(!C('CDN_IP')){
        return $_SERVER['REMOTE_ADDR'];
    }else{
        foreach (['HTTP_CDN_SRC_IP','HTTP_CLIENT_IP','HTTP_X_FORWARDED_FOR'] as $v) {
            if(isset($_SERVER[$v])) return $_SERVER[$v];
        }
        return $_SERVER['REMOTE_ADDR'];
    }
}
function set_now_run_plugin($info){
    $GLOBALS['NOW_RUN_PLUGIN'] = $info;
}
function get_now_run_plugin(){
    if(!isset($GLOBALS['NOW_RUN_PLUGIN']))
        $GLOBALS['NOW_RUN_PLUGIN']=[];
    if(empty($GLOBALS['NOW_RUN_PLUGIN'])) return false;
    return $GLOBALS['NOW_RUN_PLUGIN'];
}
function strpos_array($haystack, $needles) {
    if ( is_array($needles) ) {
        foreach ($needles as $str) {
            if ( is_array($str) ) {
                $pos = strpos_array($haystack, $str);
            } else {
                $pos = strpos($haystack, $str);
            }
            if ($pos !== FALSE) {
                return $pos;
            }
        }
    } else {
        return strpos($haystack, $needles);
    }
}