<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
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
 * Xcache缓存驱动
 */
class Xcache extends Cache {

    /**
     * 架构函数
     * @param array $options 缓存参数
     * @access public
     */
    public function __construct($options=array()) {
        if ( !function_exists('xcache_info') ) {
            E('系统不支持:Xcache');
        }
        $this->options['expire']    =   isset($options['expire'])?$options['expire']:C('DATA_CACHE_TIME');
        $this->options['prefix']    =   isset($options['prefix'])?$options['prefix']:C('DATA_CACHE_PREFIX');
    }

    /**
     * 读取缓存
     * @access public
     * @param string $name 缓存变量名
     * @return mixed
     */
    public function get($name) {
        
        $name   =   $this->options['prefix'].$name;
        if (xcache_isset($name)) {
            return xcache_get($name);
        }
        return false;
    }

    /**
     * 写入缓存
     * @access public
     * @param string $name 缓存变量名
     * @param mixed $value  存储数据
     * @param integer $expire  有效时间（秒）
     * @return boolean
     */
    public function set($name, $value,$expire=null) {
        if(is_null($expire)) {
            $expire = $this->options['expire'] ;
        }
        $name   =   $this->options['prefix'].$name;
        if(xcache_set($name, $value, $expire)) {
            return true;
        }
        return false;
    }

    /**
     * 删除缓存
     * @access public
     * @param string $name 缓存变量名
     * @return boolean
     */
    public function rm($name) {
        return xcache_unset($this->options['prefix'].$name);
    }

    /**
     * 清除缓存
     * @access public
     * @return boolean
     */
    public function clear() {
        return xcache_clear_cache(1, -1);
    }
}
