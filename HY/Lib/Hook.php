<?php
namespace HY\Lib;
/**
 * Hook Hook插件机制 Re插件机制
*/
class hook {
    static public $file = array();
    static public $include_file =array();
    static public $re_php = array();
    static public $i=0;
    static public $file_type = '';
    static public function init_file(){
        
        self::tree(PLUGIN_PATH);

        self::tree(VIEW_PATH);
        foreach (self::$file as $key => &$value) {
            ksort($value);
        }
        //print_r(self::$file);

    }
    //过滤多余路劲
    static public function filter_file(){
        foreach (self::$include_file as &$v) {
            $v = self::str_replace_path($v);
            
        }
    }
    static public function str_replace_path($a){
        return str_replace(array(PATH,'//','\\'),array('','/','/'),$a);
    }
    //初始化 re.php 替换文件
    static public function init_re(){

    }
    /*
    re机制处理
    $code代码内容

     */
    static public function re($code,$file_path){ // code
        //echo $file_path;
        $key = self::str_replace_path($file_path);
        //echo $key."\r\n";
        //print_r(self::$re_php);
        
        if(empty(self::$file))
            self::init_file();
        foreach(self::$re_php as $v){

            if(isset($v[$key])){//唯一针对
                foreach ($v[$key] as $key1 => $value) {
                    
                    //var_dump($key1,$value);
                    if(is_file(PATH . $key1) && is_file(PATH . $value)){
                        $code = str_replace(
                            file_get_contents(PATH . $key1),
                            self::set_plugin_name(PATH . $value).file_get_contents(PATH . $value).self::set_plugin_name(null),
                            $code
                        );

                    }
                }
            }else{

                foreach ($v as $aa => $vv) { //筛选 任一匹配
                    if(strpos($aa,'*') !== false){ //伪正则匹配
                        
                        $aa = str_replace(['.','*','/'],['\.','.*','\/'],$aa);
                        if(preg_match('/'.$aa.'/s',$key) > 0 ){
                            //var_dump($aa,$key,$vv);
                            foreach ($vv as $key1 => $value) {

                                if(is_file(PATH . $key1) && is_file(PATH . $value)){
                                    $code = str_replace(
                                        file_get_contents(PATH . $key1),
                                        self::set_plugin_name(PATH . $value).file_get_contents(PATH . $value).self::set_plugin_name(null),
                                        $code
                                    );

                                }
                            }
                        }
                        

                    }
                }
            }

            

        }
        return $code;       
    }
    static public function set_plugin_name($file_path){
        if($file_path === null){
            if(!isset($GLOBALS['PREV_RUN_PLUGIN_INFO']))
                $GLOBALS['PREV_RUN_PLUGIN_INFO']='';
            $info = $GLOBALS['PREV_RUN_PLUGIN_INFO'];
            if(self::$file_type=='Tpl'){
                return "\r\n<?php //Hook ##END##{$info}## ?>\r\n";
            }elseif(self::$file_type=='Action' || self::$file_type=='Model'){
                return "\r\n//Hook ##END##{$info}##\r\n";
            }
        }
        if(empty($file_path)) return false;

        $plugin_name = str_replace(PLUGIN_PATH,'',$file_path);
        $pos = strpos_array($plugin_name,['/','\\']);
        $dir_name = substr($plugin_name, 0, $pos);
        $plugin_name = get_plugin_conf_v($dir_name,'name');
        
        $info = serialize(['plugin_name'=>$plugin_name,'dir_name'=>$dir_name,'path'=>$file_path]);
        $GLOBALS['PREV_RUN_PLUGIN_INFO']=$info;
        if(self::$file_type=='Tpl'){
            return "\r\n<?php //Hook ##START##{$info}## ?>\r\n";
        }elseif(self::$file_type=='Action' || self::$file_type=='Model'){
            return "\r\n//Hook ##START##{$info}##\r\n";
        }
        //echo self::$file_type.'<br>';
    }
    static public function encode($code){ //code contents
        //echo $code;
        //echo '插件开启\r\n';
        if(empty(self::$file))
            self::init_file();
        $content = preg_replace_callback('/\/\/{hook (.+?)}/is','self::parseTag',$code);
        $content = preg_replace_callback('/<!--{hook (.+?)}-->/is','self::parseTag',$content);
        $content = preg_replace_callback('/{hook (.+?)}/is','self::parseTag',$content);
        return $content;
    }
    static public function parseTag($tagStr){
        $tag = isset($tagStr[1]) ? $tagStr[1] : '';
        //echo $tag."\r\n";
        $content='';
        if(isset(self::$file[$tag])){
            foreach (self::$file[$tag] as $v) {
                $content.=self::set_plugin_name($v).file_get_contents($v).self::set_plugin_name(null);
            }
        }
        $content = preg_replace_callback('/\/\/{hook (.+?)}/is','self::parseTag',$content);
        $content = preg_replace_callback('/<!--{hook (.+?)}-->/is','self::parseTag',$content);
        $content = preg_replace_callback('/{hook (.+?)}/is','self::parseTag',$content);
        return $content;

    }
    //写入缓存
    static public function put($contents,$path){
        
        file_put_contents($path,$contents);
    }

    //扫描插件hook文件 以及处理re.php
    //$directory 扫描目录
    //$vi_on 是否检测开启
    static public function tree($directory,$vi_on=false){
        
        $dh = opendir($directory);
        
    	while (($file = readdir($dh)) !== false) {
    		$file_location=$directory."/".$file;//生成路径
            //echo $file_location . "\r\n";
    		if(is_dir($file_location) && $file!="." &&$file!=".."){ //判断是不是文件夹
                if(PLUGIN_ON_FILE && !$vi_on){ //开启插件是否开启机制
                    if(is_file($file_location .'/on')){
                        
                        self::tree($file_location,true);
                        if(is_file($file_location . '/re.php')){
                            self::$re_php[self::$i]=include $file_location . '/re.php';
                            

                            foreach (self::$re_php[self::$i] as &$v) {
                                $tmp=array();
                                foreach ($v as $key => $vv) {
                                    $tmp[self::str_replace_path($file_location .'/' . $key)] = self::str_replace_path($file_location .'/'. $vv);
                                
                                }
                                $v=$tmp;
                            }
                            self::$i++;

                        }
                        
                    }  
                }else{

                    self::tree($file_location,true);
                }
                    

    		}else{ //非文件夹
                
                if(self::exec($file) == 'hook'){
                    
                    
                    //删除后缀的hook文件
                    $sy = self::unexe($file);

                    //遍历当前插件目录
                    $mun_path = str_replace('//', '/', dirname($file_location));

                    
                    
                    
                    //插件优先级处理
                    $json = array();
                    if(is_file($mun_path .'/p.php')){
                        $json = file($mun_path .'/p.php');
                    }
                    //$file = 文件名
                    //获取p文件优先级json
                    $json = isset($json[1]) ? json_decode($json[1],true) : array();
                    $p = isset($json[$file]) ? intval($json[$file]) : 0;
                    $p=$p*100;
                    //var_dump($p,$file);

                    if(isset(self::$file[$sy])){
                        self::array_px(
                            self::$file[$sy],
                            str_replace('//', '/', $file_location),
                            $p
                        );
                        
                    }
                    else{
                        self::$file[$sy] = array(
                            $p=>str_replace('//', '/', $file_location)
                        );
                    }
                }

                //echo self::exec($file_location);
            }
    	}
    }
    //获取后缀
    static public function exec($filename){
        return substr(strrchr($filename, '.'), 1);
    }
    //删除后缀
    static public function unexe($name){
        return str_replace(C("HOOK_SUFFIX"),'',$name);
    }
    static public function array_px(&$arr,$v,$i=0){
        //$i=0;
        while(1){
            if(isset($arr[$i])){
                $i++;
                continue;
            }
            $arr[$i]=$v;
            break;
        }
    }



}
