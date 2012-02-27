<?php
define('TODATE_BASEDIR', __DIR__);
spl_autoload_register(function ($className) {
	$file = TODATE_BASEDIR.DIRECTORY_SEPARATOR.str_replace('\\', DIRECTORY_SEPARATOR, $className).'.php';
	if (file_exists($file)) {
		require_once $file;
		return true;
	} 
});
?>
