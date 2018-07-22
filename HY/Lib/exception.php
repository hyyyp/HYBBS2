<?php


namespace HY\Lib;
class exception {
	public static function format_exception($e) {
		$trace = $e->getTrace();
		if(!empty($trace) && $trace[0]['function'] == 'error_handle' && $trace[0]['class'] == 'core') {
			$line = $trace[0]['args'][3];
			$file = $trace[0]['args'][2];
			$message = $trace[0]['args'][1];
		} else {
			$line = $e->getLine();
			$file = $e->getFile();
			$message = $e->getMessage();
		}

		/*if(!empty(\HY\HY::$logs)){
			$_SERVER['sqls'] = \HY\HY::$logs;
			
		}
		if(!empty(\HY\HY::$_CLASS)){
			$_SERVER['new_class'] = \HY\HY::$_CLASS;
		}*/
		$backtracelist = array();
		foreach($trace as $k=>$v) {
			$args = $comma = '';
			if(!empty($v['args'])) {
				if(DEBUG) {
					if($v['function'] == 'error_handle') {
						$v['class'] = '';
						$v['function'] = '';
						$args = '';
					} else {
						foreach((array)$v['args'] as $arg) {

							//var_dump($arg);
							if(is_string($arg)) {
								$args .= $comma."'$arg'";
							} elseif(is_object($arg)) {
								$args .= $comma."Object";
							} elseif(is_array($arg)) {
								$args .= $comma."Arrary";
							} else {
								$args .= $comma.''.($arg === NULL ? 'NULL' : $arg);
							}
							$comma = ', ';
						}
					}
				} else {
					$args = '';
				}
			}
			!isset($v['file']) && $v['file'] = '';
			!isset($v['line']) && $v['line'] = '';
			!isset($v['function']) && $v['function'] = '';
			!isset($v['class']) && $v['class'] = '';
			!isset($v['type']) && $v['type'] = '';

			$backtracelist[] = array (
				'file'=>$v['file'],
				'line'=>$v['line'],
				'function'=>$v['function'],
				'class'=>$v['class'],
				'type'=>$v['type'],
				'args'=>$args,
			);
		}
		$codelist = self::get_code($file, $line);
		return array(
			'line'=>$line,
			'file'=>$file,
			'codelist'=>$codelist,
			'message'=>$message,
			'backtracelist'=>$backtracelist,
		);
	}

	public static function get_code($file, $line) {
		$arr = file($file);
		$arr2 = array_slice($arr, max(0, $line - 5), 10, true);
		foreach ($arr2 as &$v) {
			$v = htmlspecialchars($v);
			$v = str_replace(' ', '&nbsp;', $v);
			$v = str_replace('	', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $v);
		}
		return $arr2;
	}

	public static function to_text($e) {
		return self::to_string();
	}

	public static function to_html($e) {
		return self::to_string($e);
	}

	public static function to_string($e) {
		//var_dump($e);
		if(is_object($e)) {
			$arr = self::format_exception($e);
			$line = $arr['line'];
			$file = $arr['file'];
			$codelist = $arr['codelist'];
			$message = $arr['message'];
			$backtracelist = $arr['backtracelist'];

			ob_start();

			include HY_PATH . 'View/exec.php';

			$s = ob_get_contents();
			ob_end_clean();
			return $s;
		} elseif(is_string($e)) {
			return $e;
		}
	}

}

?>
