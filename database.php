<?php
ini_set("session.cooke_httponly", 1);
// Content of database.php

$mysqli = new mysqli('localhost', 'USERNAME', 'PASSWORD', 'calendar');		//connect to database

if($mysqli->connect_errno) {
	printf("Connection Failed: %s\n", $mysqli->connect_error);
	exit;
}
else {
}

?>