<?php
namespace HY\Lib;
class Lang
{
	public function init($theme){ //部分代码来自于 Thinkphp
		global $_LANG;
        $_LANG=array();
        
        $lang_var = C("lang_var");
        $now_lang = C("default_lang");
        if (C('auto_lang')){ //自动切换语言
            if(isset($_GET[$lang_var])){// URL切换语言
                $now_lang = $_GET[$lang_var];
                cookie('hyphp_lang',$now_lang,3600);
            }elseif(cookie('hyphp_lang')){// 获取上次用户的选择
                $now_lang = cookie('hyphp_lang');
            }elseif(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])){// 自动侦测浏览器语言
                preg_match('/^([a-z\d\-]+)/i', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $matches);
                if(isset($matches[1])){
                    $now_lang = $matches[1];
                    cookie('hyphp_lang',$now_lang,3600);
                }
            }
        }
        $now_lang = strtolower($now_lang);
        $file = VIEW_PATH . $theme . 'lang/' . $now_lang . '.php';
        if(!is_file($file)){
            $now_lang = substr($now_lang,0,2);
            $file = VIEW_PATH . $theme . 'lang/' . $now_lang . '.php';
        }
        if(!is_file($file))
            $now_lang = C("default_lang");


            
        
        defined('NOW_LANG') or define('NOW_LANG',strtolower($now_lang));

	}
    //追加语言文件
    public function append_lang($theme){
        global $_LANG;

        $file = VIEW_PATH . $theme . 'lang/' . NOW_LANG . '.php';
        $Tmp_Lang = array();
        if(is_file($file)){
            $Tmp_Lang = include $file;
        }else{
            $file = VIEW_PATH . $theme . 'lang/' . C("default_lang") . '.php';
            if(is_file($file))
                $Tmp_Lang = include $file;
        }
        $_LANG = array_merge($_LANG,$Tmp_Lang);
    }
    //
    public function check_lang_tmpfile($name){
        return is_file(TMP_PATH . 'Lang/' . $name .'.lang');
    }
    //获取语言文件
    public function get_lang_tmpfile($name){
        if($this->check_lang_tmpfile($name)){
            $content    =   file_get_contents(TMP_PATH . 'Lang/' . $name .'.lang');
            return unserialize($content);
        }
        return false;
    
    }
    //写入语言文件
    public function put_lang_tmpfile($name){
        global $_LANG;
        $data   =   serialize($_LANG);
        is_dir(TMP_PATH . 'Lang/')    or mkdir(TMP_PATH . 'Lang/');
        return file_put_contents(TMP_PATH . 'Lang/' . $name .'.lang',$data);
    }
}
/*
$_SERVER['HTTP_ACCEPT_LANGUAGE']获取当前语言
Afrikaans (af)
Albanian (sq)
Basque (eu)
Bulgarian (bg)
Byelorussian (be)
Catalan (ca)
Chinese (zh)
Chinese/China (zh-cn)
Chinese/Taiwan (zh-tw)
Chinese/Hong Kong (zh-hk)
Chinese/singapore (zh-sg)
Croatian (hr)
Czech (cs)
Danish (da)
Dutch (nl)
Dutch/Belgium (nl-be)
English (en)
English/United Kingdom (en-gb)
English/United Satates (en-us)
English/Australian (en-au)
English/Canada (en-ca)
English/New Zealand (en-nz)
English/Ireland (en-ie)
English/South Africa (en-za)
English/Jamaica (en-jm)
English/Belize (en-bz)
English/Trinidad (en-tt)
Estonian (et)
Faeroese (fo)
Farsi (fa)
Finnish (fi)
French (fr)
French/Belgium (fr-be)
French/France (fr-fr)
French/Switzerland (fr-ch)
French/Canada (fr-ca)
French/Luxembourg (fr-lu)
Gaelic (gd)
Galician (gl)
German (de)
German/Austria (de-at)
German/Germany (de-de)
German/Switzerland (de-ch)
German/Luxembourg (de-lu)
German/Liechtenstein (de-li)
Greek (el)
Hindi (hi)
Hungarian (hu)
Icelandic (is)
Indonesian (id or in)
Irish (ga)
Italian (it)
Italian/ Switzerland (it-ch)
Japanese (ja)
Korean (ko)
Latvian (lv)
Lithuanian (lt)
Macedonian (mk)
Malaysian (ms)
Maltese (mt)
Norwegian (no)
Polish (pl)
Portuguese (pt)
Portuguese/Brazil (pt-br)
Rhaeto-Romanic (rm)
Romanian (ro)
Romanian/Moldavia (ro-mo)
Russian (ru)
Russian /Moldavia (ru-mo)
Scots Gaelic (gd)
Serbian (sr)
Slovack (sk)
Slovenian (sl)
Sorbian (sb)
Spanish (es or es-do)
Spanish/Argentina (es-ar)
Spanish/Colombia (es-co)
Spanish/Mexico (es-mx)
Spanish/Spain (es-es)
Spanish/Guatemala (es-gt)
Spanish/Costa Rica (es-cr)
Spanish/Panama (es-pa)
Spanish/Venezuela (es-ve)
Spanish/Peru (es-pe)
Spanish/Ecuador (es-ec)
Spanish/Chile (es-cl)
Spanish/Uruguay (es-uy)
Spanish/Paraguay (es-py)
Spanish/Bolivia (es-bo)
Spanish/El salvador (es-sv)
Spanish/Honduras (es-hn)
Spanish/Nicaragua (es-ni)
Spanish/Puerto Rico (es-pr)
Sutu (sx)
Swedish (sv)
Swedish/Findland (sv-fi)
Thai (ts)
Tswana (tn)
Turkish (tr)
Ukrainian (uk)
Urdu (ur)
Vietnamese (vi)
Xshosa (xh)
Yiddish (ji)
Zulu (zu) 

 */