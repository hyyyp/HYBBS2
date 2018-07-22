<?php

namespace HY\Lib;

class Logs{
	public function log($content){
		if(!is_writable(TMP_PATH))
			return;
		if(!is_file(TMP_PATH . 'log.php'))
			file_put_contents(TMP_PATH . 'log.php','<?php die(); ?>');
		file_put_contents(TMP_PATH . 'log.php',trim($content), FILE_APPEND);
	}
}