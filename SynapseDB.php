<?php

// Define SynapseDB directory constant.
define('SDB_DIR', dirname(__FILE__).'/');

// Include constants.
include_once(SDB_DIR.'Constants.php');

// Autoload:
function sdb_autoload($class)
{
	$path = SDB_DIR.'class/'.$class.'.php';
	if(!is_file($path))
		return false;
	include_once($path);
	return true;
}
spl_autoload_register('sdb_autoload');

?>