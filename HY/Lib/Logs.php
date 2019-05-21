<?php

namespace HY\Lib;

class Logs{
	public function log($content){
		if(!is_writable(TMP_PATH))
			return;
		$tmp = trim(file_get_contents(TMP_PATH . 'log.php'));
		if(!is_file(TMP_PATH . 'log.php') || empty($tmp))
			file_put_contents(TMP_PATH . 'log.php','<?php die(); ?>');
		file_put_contents(TMP_PATH . 'log.php',($content), FILE_APPEND);
	}
}