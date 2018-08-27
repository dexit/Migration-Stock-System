<?php
set_time_limit(600);

define('LIB', 'Lib');
define('LAYOUT', 'Layout');
define('COMPONENTS', 'Layout/components');
define('ACTIONS', 'Actions');

class Loader{
	public function load($dir, $file) {
		include $dir . '/' . $file . '.php';
	}
	
	public function loadClass($class) {
		include LIB . '/' . $class . '.class.php';
	}
}



$loaderObj = new Loader();

$loaderObj->loadClass('pdoCrud');
$loaderObj->loadClass('pvxAuth');
$loaderObj->load(LIB, 'functions');

$pvxAuthObj = new pvxAuth();


