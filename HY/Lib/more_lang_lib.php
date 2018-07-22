<?php
namespace HY\Lib;
class more_lang_lib
{
	public $conf = array();
	public $regex = array(); //正则
	public $value = array(); //替换值
	public $regex_i =0;

	public $regex_string=array();
	public $regex_string_value =array();
	public function __construct(){
		$this->conf = include LIB_PATH . 'more_lang_lib_conf.php';

		foreach((array)C("MORE_LANG_LIB_FILE") as $v){
			$this->conf = array_merge($this->conf,include $v);
		}
		//print_r($this->conf);

		$i=0;
		foreach ($this->conf as $k => $v) {
			$this->regex[$i]=$k;
			$this->value[$i]=$v;
			$i++;
		}
	}
	//解析引号
	public function quotes($match){
		$this->regex_string_value[$this->regex_i] = $match[2];
		return $match[1].'HYPHP_QUOTES['.$this->regex_i++.']'.$match[3];
	}
	public function quotes_de($match){

		return $this->regex_string_value[$match[1]];
	}
	public function quotes_min($match){
		$this->regex_string_value[$this->regex_i] = $match[2];
		return $match[1].'HYPHP_QUOTES_MIN['.$this->regex_i++.']'.$match[3];
	}
	public function quotes_min_de($match){
		return $this->regex_string_value[$match[1]];
	}
	public function notes($match){
		
		$this->regex_string_value[$this->regex_i] = $match[0];
		return '/*HYPHP_NOTES['.$this->regex_i++.']*/';
	}
	public function notes_de($match){
		return $this->regex_string_value[$match[1]];
	}

	public function notes_min($match){
		$this->regex_string_value[$this->regex_i] = $match[0];
		return '//HYPHP_NOTES_MIN['.$this->regex_i++.']';
	}
	public function notes_min_de($match){
		return $this->regex_string_value[$match[1]];
	}
	public function notes_min1($match){
		$this->regex_string_value[$this->regex_i] = $match[0];
		return '#HYPHP_NOTES_MIN1['.$this->regex_i++.']';
	}
	public function notes_min_de1($match){
		return $this->regex_string_value[$match[1]];
	}
	//解析代码
	public function decode($code){
	
		//收藏引号
		$code = preg_replace_callback('/(")([^\\"]+)(")/i', array(&$this,'quotes'),$code );
		//echo $code;die;
		$code = preg_replace_callback('/(\')([^\\\']+)(\')/i', array(&$this,'quotes_min'),$code );

		
		//注释板块
		$code = preg_replace_callback('/\/\*(\s|.)*?\*\//i', array(&$this,'notes'),$code );

		//行注释
		$code = preg_replace_callback('/\/\/[^\n]*/i', array(&$this,'notes_min'),$code );
		$code = preg_replace_callback('/#[^\n]*/i', array(&$this,'notes_min1'),$code );

		

		//$code = preg_replace_callback('/([\'])(.+?)([\'])/i', array(&$this,'regex'),$code );
		//print_r($this->regex_string_value);
		
		
		$code = preg_replace($this->regex,$this->value,$code);
		//echo $code;die;
		$code = preg_replace_callback('/#HYPHP_NOTES_MIN1\[(\d+)\]/', array(&$this,'notes_min_de1'),$code );
		$code = preg_replace_callback('/\/\/HYPHP_NOTES_MIN\[(\d+)\]/', array(&$this,'notes_min_de'),$code );
		$code = preg_replace_callback('/\/\*HYPHP_NOTES\[(\d+)\]\*\//', array(&$this,'notes_de'),$code );
		$code = preg_replace_callback('/HYPHP_QUOTES_MIN\[(\d+)\]/', array(&$this,'quotes_min_de'),$code );
		$code = preg_replace_callback('/HYPHP_QUOTES\[(\d+)\]/', array(&$this,'quotes_de'),$code );

		
		return $code;
		
	}
	public function encode(){

	}
}