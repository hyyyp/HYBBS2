<?php
namespace HY;
/**
 * Action.php Action
 */
abstract class Action
{
    protected $var = array();
    public $Tpl;
    public $view = '';
    //模板分组
    //编译获取HTML
    protected function GetHtml($file_name,$del_cache = false){
        $View = $this->view ? $this->view . '/' : '';
        //缓存路劲
        $view_file_md5 = md5($View . $file_name);
        $tmp_path = TMP_PATH . 'View_'.$file_name . '_' . $view_file_md5 . C("tmp_file_suffix");
        $plugin_name = '';
        $plugin_view = '';
        if(PLUGIN_ON){ //插件开启         
            $t = explode(".",$file_name);
            if(isset($t[0]) && isset($t[1])){
                if($t[0] == 'plugin'){
                    $t = explode("::",$t[1]);
                    if(isset($t[0]) && isset($t[1])){

                        $plugin_name = $t[0];
                        $plugin_view = $t[1];
                        
                        $tmp_path = TMP_PATH . "plugin_tmp_{$plugin_name}_".str_replace('/','_',$plugin_view)."_".md5("{$plugin_name}_{$plugin_view}") . C("tmp_file_suffix");

                        

                    }
                }
            }
            //throw new \Exception('控制器 ' . $class . ' 不存在!');
        }
        $lang_need_put = false;
        if(C("lang_switch_on")){ //开启多语言功能
            global $_LANG;
            $Lang = new \HY\Lib\Lang();
            $Lang->init($View);
            if(!$Lang->check_lang_tmpfile($view_file_md5. NOW_LANG) || DEBUG){
                $Lang->append_lang($View);
                $lang_need_put = true;
            }else{
                $_LANG = $Lang->get_lang_tmpfile($view_file_md5. NOW_LANG);
            }
        }
        if (!is_file($tmp_path) || DEBUG || $lang_need_put || $del_cache) {
            //写入缓存文件
            $tpl_path = VIEW_PATH . $View . $file_name; //模板文件路劲
            if(!empty($plugin_name) && !empty($plugin_view)){

                $tpl_path = PLUGIN_PATH . "{$plugin_name}/{$plugin_view}";
                //echo $tpl_path;
            }
            if(is_array(C('tpl_suffix'))){
                $tpl_is = false;
                $tpl_str='';
                foreach (C('tpl_suffix') as $v) {
                    if (is_file($tpl_path . $v)) {
                        $tpl_path.=$v;
                        $tpl_is=true;
                        break;
                    }
                    $tpl_str.=$v.',';
                }
                if(!$tpl_is)
                    E('模板不存在(file_path): ' . $View . $file_name . "($tpl_str)");
                
            }
            else{
                $tpl_path.=C('tpl_suffix');
                if (!is_file($tpl_path )) {
                    E('模板不存在(file_path): ' . $View . $file_name . C('tpl_suffix'));
                }
            }
            
            $content = file_get_contents($tpl_path);
            Lib\hook::$include_file[]=$tpl_path;
            Lib\hook::$file_type = 'Tpl';

            //获取 模板文件
            $this->Tpl = new \HY\Tpl();
            $this->Tpl->view = $this->view;

            $content = $this->Tpl->init($content,$tpl_path);
            put_tmp_file($tmp_path, $content);

            
        }
        if(C("lang_switch_on")){ 
            if(!$Lang->check_lang_tmpfile($view_file_md5. NOW_LANG) || DEBUG){
                $Lang->put_lang_tmpfile($view_file_md5. NOW_LANG);
            }
        }
        //print_r($content);
        $_SERVER['ob_start'] = true;
        ob_start();
        ob_implicit_flush(0);
        extract($this->var, EXTR_OVERWRITE);
        
        include $tmp_path;
        
        $content = ob_get_clean();

        return $content;
    }
    
    protected function display($file_name, $del_cache = false){
        $content = $this->GetHtml($file_name,$del_cache);
        echo $content;
    }
    protected function v($name, $value = ''){
        if (is_array($name)) {
            $this->var = array_merge($this->var, $name);
        } else {
            $this->var[$name] = $value;
        }
    }
    public function json($data){
        header('Content-Type:application/json; charset=utf-8');
        die(json_encode($data));
    }
    public function jsonp($data, $fun = ''){
        header('Content-Type:application/json; charset=utf-8');
        if (empty($fun)) {
            $fun = X('get.jsoncallback');
        }
        die($fun . '(' . json_encode($data) . ');');
    }
}
