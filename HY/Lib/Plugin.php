<?php
namespace HY\Lib;
/**
 * Plugin 插件控制器
 * 处理流程: 先Re后Hook
*/
class Plugin{
	public static function run($filePath,$cache_filePath,$class = ''){
		//缓存文件不存在
		if (!is_file($cache_filePath) || DEBUG) {
			//原文件不存在
            if (!is_file($filePath)) {
                throw new \Exception('控制器 ' . $class . ' 不存在!');
            }
            //获取源文件源码
            $code = file_get_contents($filePath);
            $code = hook::re($code,$filePath);

            //多语言
            /*if(PLUGIN_MORE_LANG_ON){
                static $more_lang_lib = null;
                if($more_lang_lib == null){
                    $more_lang_lib = new more_lang_lib;
                }
                $code = $more_lang_lib->decode($code);
            }
            */
            hook::put(hook::encode($code), $cache_filePath);
        }

	}
}