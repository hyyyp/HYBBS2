<?php
namespace HY;

class Tpl{

    public $view='';

    public function init($content,$file_path) {
        
        $content = $this->parseInclude($content); //include 遍历

        //'/({)([^\d\w\s{}].+?)(})/is'
        ///({)(.+?)(})/is
        if(PLUGIN_ON){ //hook 模板
            $content = Lib\hook::re($content,$file_path);
            $content = Lib\hook::encode($content);

        }

        
        //$content = str_replace("\\","\\\\",$content);
        $content = preg_replace_callback('/({)([$].*?)(})/i', array($this, 'parseTag'),$content);
        $content = preg_replace_callback('/({)([$].*?)(})/is', array($this, 'parseTag'),$content);
        
         
        //$content = str_replace("\\","\\\\",$content);
        //$content = preg_replace_callback('/({)([^\d\s{}].+?)(})/i', array($this, 'parseTag'),$content);
        //var_dump($content);die;
        $content = preg_replace_callback('/({)(.*?)(})/i', array($this, 'parseTag'),$content);
        $content = preg_replace_callback('/({)(.*?)(})/is', array($this, 'parseTag'),$content);
        
        //$content = str_replace("\\\\","\\",$content);
       

        return $content;
    }
    public function parseTag($tagStr){
       
        if(is_array($tagStr)) $tagStr = $tagStr[2];
        //if (MAGIC_QUOTES_GPC) {
            //$tagStr = stripslashes($tagStr);
        
        //}
        //echo $tagStr."<br>\r\n";
        // if(strpos($tagStr,C('var_left_tpl')) && strpos($tagStr,C('var_right_tpl'))){
        //     $tagStr = preg_replace_callback('/({)(.+?)(})/is', array($this, 'parseTag'),$tagStr);
        // }
        // if(strpos($tagStr,C('var_left_tpl')) || strpos($tagStr,C('var_right_tpl'))){
        //     return C('var_left_tpl') . $tagStr .C('var_right_tpl');
        // }


        $flag   =  substr($tagStr,0,1);
        $name   = substr($tagStr,1);

        $a2 = substr($tagStr,0,3);
        $aa2 = substr($tagStr,3);

        $elseif = substr($tagStr,0,7);
        $elseif_v = substr($tagStr,7);

        $for = substr($tagStr,0,4);
        $for_v = substr($tagStr,4);

        $foreach = substr($tagStr,0,8);
        $foreach_v = substr($tagStr,8);

        //tplhook::encode('asd');

        // if(PLUGIN_ON){
        //     $hook = substr($tagStr,0,5); //hook
        //     $hook_v = substr($tagStr,5);
        //     if($hook == 'hook '){
        //
        //     }
        // }

        //echo $foreach_v."\r\n";
        if($flag=="$"){
            if(strpos($tagStr,'.') && !strpos($tagStr,'[')){
                $d = explode(".",$tagStr);

                if(count($d)>1){
                    $flag = $d[0];
                    $name = '';
                    for ($i=1; $i < count($d); $i++) {
                        $name.="['{$d[$i]}']";
                    }
                }
            }
            return  '<?php echo '.$flag.$name.';?>';
        }elseif($flag=="#"){
            return  '<?php echo '.$name.';?>';
        }elseif(strtolower($a2)=="if "){
            return  '<?php if ('.$aa2.'): ?>';
        }elseif(strtolower($tagStr)=="/if"){
            return  '<?php endif ?>';
        }elseif(strtolower($tagStr)=="else"){
            return  '<?php else: ?>';
        }elseif(strtolower($elseif)=="elseif "){
            return '<?php elseif ('.$elseif_v.'): ?>';
        }elseif(strtolower($tagStr)=="/foreach"){
            return  '<?php endforeach ?>';
        }elseif(strtolower($foreach)=="foreach "){
            return  '<?php foreach ('.$foreach_v.'): ?>';
        }elseif(strtolower($for) =="for "){
            return  '<?php for ('.$for_v.'): ?>';
        }elseif(strtolower($for) =="php "){
            return  '<?php '.$for_v.' ?>';
        }elseif(strtolower($tagStr) =="/for"){
            return  '<?php endfor ?>';
        }
        

        // 未识别的标签直接返回   foreach
        return C('var_left_tpl') . $tagStr .C('var_right_tpl');
    }

    // 解析模板中的include标签
    protected function parseInclude($content) {

        // 解析布局
        //$content    =   $this->parseLayout($content);
        // 读取模板中的include标签
        $find       =   preg_match_all('/'.C('var_left_tpl').'include\s(.+?)\s*?'.C('var_right_tpl').'/is',$content,$matches);
        //print_r($matches);
        if($find) {
            for($i=0;$i<$find;$i++) {
                $include    =   $matches[1][$i];
                //echo $include."\r\n";

                $content    =   str_replace(
                    $matches[0][$i],
                    $this->parseIncludeItem($include),
                    $content
                );
            }
        }
        return $content;
    }
    //获取include 内容
    protected function parseIncludeItem($file) {
        $tmp_view = $this->view;
        $View = $this->view ? $this->view.'/' : '';
        if(strpos($file,"::")){

            $d = explode("::",$file);
            $this->view =$d[0];
            $View = $d[0] . '/';
            $file = $d[1];

            $Lang = new Lib\Lang();
            $Lang->append_lang($View);
        }
        
        $path = VIEW_PATH .$View. $file;
        if(is_array(C('tpl_suffix'))){
            foreach (C('tpl_suffix') as $v) {
                if (is_file($path . $v)) {
                    $path.=$v;
                    break;
                }
            }
            
        }
        else{
            $path.=C('tpl_suffix');
        }
        Lib\hook::$include_file[]=$path;
        $content = file_get_contents( $path);
        $content = Lib\hook::re($content,$path);
        $content = $this->parseInclude($content); //递归调用
        $this->view = $tmp_view;
        return $content;
        
    }
}
