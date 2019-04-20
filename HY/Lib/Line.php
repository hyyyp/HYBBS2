<?php
namespace HY\Lib;
class Line{
    public static function run(){
        $url='';
        if(isset($_GET['s'])){
            $url = ltrim($_GET['s'], C("url_explode"));
        }
        else{
            $_GET['s'] = $_SERVER["QUERY_STRING"];
            if(empty($_GET['s'])){
                // if(!isset($_SERVER["REQUEST_URI"]))
                //     $_SERVER['REQUEST_URI']='';
                // $_GET['s'] = $_SERVER['REQUEST_URI'];
            }
            $url = ltrim($_GET['s'], C("url_explode"));
        }
        
        
        self::auto_get();
        
        $class = '';
        $Action = 'Index';
        $_Action = 'Index';
        $_Fun = 'Index';

        $_GET['HY_URL']=array('Index','Index');

        if (empty($url)) {
            //命令行运行
            if(isset($GLOBALS['argv'])){
                if(isset($GLOBALS['argv'][1]) && isset($GLOBALS['argv'][2]))
                    $class = '\\Action\\'.ucfirst($GLOBALS['argv'][1]);
                    $Action = $_Action = ucfirst($GLOBALS['argv'][1]);
                    $_Fun = $_Fun = ucfirst($GLOBALS['argv'][2]);
            }else{
                $class = '\\Action\\Index';
            }
        } else {
            $url_suffix = C("url_suffix");
            $info = $url;
            if(!empty($url_suffix)){
                $url = explode($url_suffix,$url)[0];
                $info = str_replace($url_suffix, '', $url);
            }
            
            if(strpos($info,'?') !== false){
                $info = substr($info, 0,strpos($info,'?'));
            }
            $info = $_GET['HY_URL'] = explode(C('url_explode'), $info);


            $Action = isset($info[0]) ? $info[0] : 'Index';
            $Action = strtolower($Action);
            $Fun = isset($info[1]) ? $info[1] : 'Index';
            $Fun = strtolower($Fun);

            $Action=trim($Action,'/');
            $Fun=trim($Fun,'/');

            $Action = $Action == '' ? 'Index' : $Action;
            $Fun = $Fun == '' ? 'Index' : $Fun;
            for ($i = 2; $i < count($info); $i++) {
                $_GET[$info[$i++]] = isset($info[$i]) ? $info[$i] : '';
            }
            $config['HY_URL'] = C('HY_URL');
            //检查重命名Action
            if(isset($config['HY_URL']['action'])){
                $z = array_search($Action,$config['HY_URL']['action']);
                if($z){
                    $Action = $z;
                    if(isset($config['HY_URL']['method'][$z])){
                        $b = array_search($Fun,$config['HY_URL']['method'][$z]);
                        if($b)
                            $Fun=$b;
                    }

                }
            }
            $_Action = $Action = ucfirst($Action);
            $_Fun = $Fun = ucfirst($Fun);
            $class = "\\Action\\{$_Action}";
        }
        define('ACTION_NAME', $_Action);
        define('METHOD_NAME', $_Fun);

       
        
        if (!file_exists(ACTION_PATH . "{$Action}.php")) {
            if (!file_exists(ACTION_PATH . 'No.php')) {
                E("{$Action}控制器不存在!");
            } else {
                $class = '\\Action\\No';
            }
        }
        if (file_exists(MYLIB_PATH . 'function.php')) {
            include MYLIB_PATH . 'function.php';
        }

        $module = new $class();
        if (!method_exists($module, $_Fun) || !preg_match('/^[A-Za-z](\/|\w)*$/',$_Fun)) {
            if (!method_exists($module, '_no')) {

                E("你的{$class}没有存在{$_Fun}操作方法");
            }
            $_Fun = '_no';
        }
        

        $method = new \ReflectionMethod($module, $_Fun);
        if ($method->isPublic() && !$method->isStatic()) {
            $class = new \ReflectionClass($module);
            $method->invoke($module);
        }

    }
    //自动转化get参数
    //http://127.0.0.1:86/?s=thread-11-2.html?order=desc&a=1
    public static function auto_get(){
        $query_string = $_SERVER["QUERY_STRING"];
        if(strpos($query_string,'?') !== false){
            foreach((array)explode('?',$query_string) as $v){
                foreach((array)explode('&',$v) as $vv){
                    $tmp = explode('=',$vv);
                    if(count($tmp)==2 && isset($tmp[0]) && isset($tmp[1])){
                        if(!isset($_GET[$tmp[0]]))
                            $_GET[$tmp[0]]=$tmp[1];
                    }
                }
            }
        }
    }
}   