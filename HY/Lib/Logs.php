<?php

namespace HY\Lib;

class Logs{
	public function log($content){
		if(!is_writable(TMP_PATH))
			return;
		$LOG_FILE_PATH = TMP_PATH . 'log.php';
		$log_content = '';
		if(is_file($LOG_FILE_PATH))
			$log_content = trim(file_get_contents($LOG_FILE_PATH));

		if(!is_file($LOG_FILE_PATH) || empty($log_content))
			file_put_contents($LOG_FILE_PATH,'<?php die(); ?>');
		file_put_contents($LOG_FILE_PATH,($content), FILE_APPEND);
	}
}