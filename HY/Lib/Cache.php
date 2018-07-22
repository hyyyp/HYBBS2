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
// | HY-PHP: 感谢所有无私奉献的作者. 
// +----------------------------------------------------------------------
namespace HY\Lib;
/**
 * 缓存管理类
 */
class Cache {

    /**
     * 操作句柄
     * @var string
     * @access protected
     */
    protected $handler    ;

    /**
     * 缓存连接参数
     * @var integer
     * @access protected
     */
    protected $options = array();

    /**
     * 连接缓存
     * @access public
     * @param string $type 缓存类型
     * @param array $options  配置数组
     * @return object
     */
    public function connect($type='',$options=array()) {
        if(empty($type))  $type = C("DATA_CACHE_TYPE");

        $class  =   strpos($type,'\\')? $type : 'HY\\Lib\\Cache\\'.ucwords(strtolower($type));            

        if(class_exists($class))
            $cache = new $class($options);
        else
            E('无法加载该类库:'.$type);
        return $cache;
    }

    /**
     * 取得缓存类实例
     * @static
     * @access public
     * @return mixed
     */
    static function getInstance($type='',$options=array()) {
		static $_instance	=	array();
        //相同配置 不在实例
		$guid	=	$type.to_guid_string($options);
		if(!isset($_instance[$guid])){
			$obj	=	new Cache();
			$_instance[$guid]	=	$obj->connect($type,$options);
		}
		return $_instance[$guid];
    }

    public function __get($name) {
        return $this->get($name);
    }

    public function __set($name,$value) {
        return $this->set($name,$value);
    }

    public function __unset($name) {
        $this->rm($name);
    }
    public function setOptions($name,$value) {
        $this->options[$name]   =   $value;
    }

    public function getOptions($name) {
        return $this->options[$name];
    }

    public function __call($method,$args){
        //调用缓存类型自己的方法
        if(method_exists($this->handler, $method)){
           return call_user_func_array(array($this->handler,$method), $args);
        }else{
            E(__CLASS__.'::'.$method.' 该缓存类没有定义你所调用的方法');
            return;
        }
    }
}