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
 * 数据库方式缓存驱动
 *    CREATE TABLE hy_cache (
 *      cachekey varchar(255) NOT NULL,
 *      expire int(11) NOT NULL,
 *      data blob,
 *      datacrc int(32),
 *      UNIQUE KEY `cachekey` (`cachekey`)
 *    );
 */
class Db extends Cache {

    /**
     * 架构函数
     * @param array $options 缓存参数
     * @access public
     */
    public function __construct($options=array()) {
        if(empty($options)) {
            $options = array (
                'table'     =>  C('DATA_CACHE_TABLE'),
            );
        }
        $this->options  =   $options;   
        $this->options['prefix']    =   isset($options['prefix'])?  $options['prefix']  :   C('DATA_CACHE_PREFIX');
        $this->options['expire']    =   isset($options['expire'])?  $options['expire']  :   C('DATA_CACHE_TIME');
        $this->handler   = S($this->options['table']);

    }

    /**
     * 读取缓存
     * @access public
     * @param string $name 缓存变量名
     * @return mixed
     */
    public function get($name) {
        $name       =  $this->options['prefix'].addslashes($name);
        $result     =  $this->handler->find("*",array(
            'AND'=>array(
                
                'OR'    =>array(
                    'expire'    =>  0,
                    'expire[!=]' =>  NOW_TIME,
                ),
                'cachekey'  =>  $name,
            ),
        ));
        //$result     =  $this->handler->query('SELECT `data`,`datacrc` FROM `'.$this->options['table'].'` WHERE `cachekey`=\''.$name.'\' AND (`expire` =0 OR `expire`>'.time().') LIMIT 0,1');
        if(!empty($result)) {
            $content   =  $result['data'];
            if(C('DATA_CACHE_COMPRESS') && function_exists('gzcompress')) {
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
     * @param integer $expire  有效时间（秒）
     * @return boolean
     */
    public function set($name, $value,$expire=null) {

        $data   =  serialize($value);
        $name   =  $this->options['prefix'].addslashes($name);
        if( C('DATA_CACHE_COMPRESS') && function_exists('gzcompress')) {
            //数据压缩
            $data   =   gzcompress($data,3);
        }

        
        $crc  =  '';
        
        if(is_null($expire)) {
            $expire  =  $this->options['expire'];
        }
        $expire	    =   ($expire==0)?0: (time()+$expire) ;//缓存有效期为0表示永久缓存
        $result     =   $this->handler->find("*",array("cachekey"=>$name));

        
        if(!empty($result) ) {
        	//更新记录
            $result  =  $this->handler->update(array('data'=>$data,'datacrc'=>$crc,'expire'=>$expire),array('cachekey'=>$name));

        }else {

        	//新增记录
            $result  =  $this->handler->insert(array(
                'cachekey'  =>  $name,
                'data'      =>  $data,
                'datacrc'   =>  $crc,
                'expire'    =>  $expire
            ));
             
        }

        if($result === false) {
            return false;
        }else {
            return true;
            
        }
    }

    /**
     * 删除缓存
     * @access public
     * @param string $name 缓存变量名
     * @return boolean
     */
    public function rm($name) {
        $name  =  $this->options['prefix'].addslashes($name);
        return $this->handler->delete(array('cachekey'=>$name));
    }

    /**
     * 清除缓存
     * @access public
     * @return boolean
     */
    public function clear() {
        return $this->handler->delete(array());
    }

}