<?php

// Include SynapseDB.
include_once('../SynapseDB.php');

// Create some connection constants.
define('DB_HOST', '');
define('DB_USER', '');
define('DB_PASS', '');
define('DB_NAME', '');

// Establish connection.
$sql = SynapseFactory::GetInstance()->Connect(
	SDB_MYSQL,
	DB_HOST,
	DB_USER,
	DB_PASS,
	DB_NAME
);

// Insert values.
$sql->Query('INSERT INTO example_tbl(name, email) VALUES(?, ?);', array('Example User 1', 'user1@example.com'));
$sql->Query('INSERT INTO example_tbl(name, email) VALUES(?, ?);', array('Example User 2', 'user2@example.com'));
$sql->Query('INSERT INTO example_tbl(name, email) VALUES(?, ?);', array('Example User 3', 'user3@example.com'));
$sql->Query('INSERT INTO example_tbl(name, email) VALUES(?, ?);', array('Example User 4', 'user4@example.com'));
$sql->Query('INSERT INTO example_tbl(name, email) VALUES(?, ?);', array('Example User 5', 'user5@example.com'));

// Test different injections.
$sql->Query('SELECT * FROM example_tbl WHERE id = ?;', array('; TRUNCATE example_tbl;#'));
$sql->Query('SELECT * FROM example_tbl WHERE id = ?;', array('; DELETE example_tbl;#'));

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
			var_dump($sql->Fetch('SELECT * FROM example_tbl ORDER BY id ASC;'));
			?>
		</p>
		<h3>Associative fetch.</h3>
		<p>
			<?php
			var_dump($sql->FetchAssoc('SELECT * FROM example_tbl ORDER BY id ASC;'));
			?>
		</p>
		<h3>Numeric fetch.</h3>
		<p>
			<?php
			var_dump($sql->FetchNumeric('SELECT * FROM example_tbl ORDER BY id ASC;'));
			?>
		</p>
	</body>
</html>

<?php

$sql->Query('TRUNCATE example_tbl;');

?>
