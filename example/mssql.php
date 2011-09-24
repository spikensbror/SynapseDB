<?php

// Include SynapseDB.
include_once('../SynapseDB.php');

// Create some connection constants.
if(SDB_OS == SDB_OS_WIN) // If on windows.
{
	define('DB_HOST', '');
	define('DB_USER', '');
	define('DB_PASS', '');
	define('DB_NAME', '');
}
else // *NIX
{
	define('DB_HOST', '');
	define('DB_USER', '');
	define('DB_PASS', '');
	define('DB_NAME', '');
}

// Establish connection.
$sql = SynapseFactory::GetInstance()->Connect(
	SDB_MSSQL,
	DB_HOST,
	DB_USER,
	DB_PASS,
	DB_NAME
);

// Insert values.
$sql->Query('INSERT INTO EXAMPLE_TBL(name, email) VALUES(?, ?);', array('Example User 1', 'user1@example.com'));
$sql->Query('INSERT INTO EXAMPLE_TBL(name, email) VALUES(?, ?);', array('Example User 2', 'user2@example.com'));
$sql->Query('INSERT INTO EXAMPLE_TBL(name, email) VALUES(?, ?);', array('Example User 3', 'user3@example.com'));
$sql->Query('INSERT INTO EXAMPLE_TBL(name, email) VALUES(?, ?);', array('Example User 4', 'user4@example.com'));
$sql->Query('INSERT INTO EXAMPLE_TBL(name, email) VALUES(?, ?);', array('Example User 5', 'user5@example.com'));

// Test different injections.
$sql->Query('SELECT * FROM EXAMPLE_TBL WHERE id = ?;', array('; TRUNCATE EXAMPLE_TBL;--'));
$sql->Query('SELECT * FROM EXAMPLE_TBL WHERE id = ?;', array('; SHUTDOWN;/*'));

// Execute get query.
$sql->Query('SELECT * FROM EXAMPLE_TBL ORDER BY id ASC;');

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8"/>
		<title>SynapseDB Example Page</title>
	</head>
	<body>
		<h2>SynapseDB Example Page</h2>
		<h3>Hybrid fetch.</h3>
		<p>
			<?php
			while($row = $sql->Fetch())
				var_dump($row);
			?>
		</p>
		<h3>Associative fetch.</h3>
		<p>
			<?php
			while($row = $sql->Fetch(SDB_FETCH_ASSOC))
				var_dump($row);
			?>
		</p>
		<h3>Numeric fetch.</h3>
		<p>
			<?php
			while($row = $sql->Fetch(SDB_FETCH_NUMERIC))
				var_dump($row);
			?>
		</p>
	</body>
</html>

<?php

$sql->Query('DELETE FROM EXAMPLE_TBL;');
$sql->Query('DBCC CHECKIDENT(EXAMPLE_TBL, RESEED, 0) WITH NO_INFOMSGS;');

?>