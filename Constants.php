<?php

// Database types.
define('SDB_MYSQL', 0);
define('SDB_MSSQL', 1);

// Fetch types.
define('SDB_FETCH_BOTH', 0);
define('SDB_FETCH_ASSOC', 1);
define('SDB_FETCH_NUMERIC', 2);

// OS Types.
define('SDB_OS_WIN', 0);
define('SDB_OS_OTHER', 1);

// Define OS.
if(substr(PHP_OS, 0, 3) == 'WIN')
	define('SDB_OS', SDB_OS_WIN);
else
	define('SDB_OS', SDB_OS_OTHER);

?>
