<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
namespace HY\Lib\Cache;
use HY\Lib\Cache;
defined('HY_PATH') or exit();
/**
 * 文件类型缓存类
 */
class File extends Cache {

    /**
     * 架构函数
     * @access public
     */
    public function __construct($options=array()) {
        if(!empty($options)) {
            $this->options =  $options;
        }
        $this->options['temp']      =   !empty($options['temp'])?   $options['temp']    :   C('DATA_CACHE_PATH');
        $this->options['prefix']    =   isset($options['prefix'])?  $options['prefix']  :   C('DATA_CACHE_PREFIX');
        $this->options['expire']    =   isset($options['expire'])?  $options['expire']  :   C('DATA_CACHE_TIME');
        if(substr($this->options['temp'], -1) != '/')    $this->options['temp'] .= '/';
        $this->init();
    }

    /**
     * 初始化检查
     * @access private
     * @return boolean
     */
    private function init() {
        // 创建应用缓存目录
        if (!is_dir($this->options['temp'])) {
            mkdir($this->options['temp'],0777,true);
        }
    }

    /**
     * 取得变量的存储文件名
     * @access private
     * @param string $name 缓存变量名
     * @return string
     */
    private function filename($name) {
        $name	=	md5(C('DATA_CACHE_KEY').$name.C('TMP_PATH_KEY'));
        
        $filename	=	$this->options['prefix'].C('TMP_PATH_PREFIX').$name.'.cache';
        
        return $this->options['temp'].$filename;
    }

    /**
     * 读取缓存
     * @access public
     * @param string $name 缓存变量名
     * @return mixed
     */
    public function get($name) {
        $filename   =   $this->filename($name);

        if (!is_file($filename)) {
            
            return false;
        }
        $content    =   file_get_contents($filename);
        if( false !== $content) {
            $expire  =  (int)substr($content,0, 12);
            
            if($expire != 0 && time() > filemtime($filename) + $expire) {
                //缓存过期删除缓存文件
                if(is_file($filename))
                    unlink($filename);
                return false;
            }
            
            $content   =  substr($content,12);
            
            
            if(C('DATA_CACHE_COMPRESS') && function_exists('gzuncompress')) {
                //启用数据压缩
                $content   =   gzuncompress($content);
            }
            $content    =   unserialize($content);
            
            return $content;
        }
        else {
            return false;
        }
    }

    /**
     * 写入缓存
     * @access public
     * @param string $name 缓存变量名
     * @param mixed $value  存储数据
     * @param int $expire  有效时间 0为永久
     * @return boolean
     */
    public function set($name,$value,$expire=null) {
        
        if(is_null($expire)) {
            $expire =  $this->options['expire'];
        }
        $filename   =   $this->filename($name);
        $data   =   serialize($value);
        if( C('DATA_CACHE_COMPRESS') && function_exists('gzcompress')) {
            //数据压缩
            $data   =   gzcompress($data,3);
        }
        
        $check  =  '';
        
        $data    = sprintf('%012d',$expire).$check.$data;
        $result  =   file_put_contents($filename,$data);
        if($result) {
            clearstatcache();
            return true;
        }else {
            return false;
        }
    }

    /**
     * 删除缓存
     * @access public
     * @param string $name 缓存变量名
     * @return boolean
     */
    public function rm($name) {
        $name = $this->filename($name);
      
        if(is_file($name))
            return unlink($name);
        return false;
    }

    /**
     * 清除缓存
     * @access public
     * @param string $name 缓存变量名
     * @return boolean
     */
    public function clear() {
        $path   =  $this->options['temp'];
        if(!is_dir($path))
            return false;
        $files = opendir($path);
        if($files){
            while (($file = readdir($files)) !== false) {
                if ($file != '.' && $file != '..' && is_dir($path.$file) ){
                    array_map( 'unlink', glob( $path.$file.'/*.*' ) );
                }elseif(is_file($path.$file)){
                    unlink( $path . $file );
                }
            }
            return true;
        }
        return false;
    }
}