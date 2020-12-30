<?php

function getConnection() {
	$host = "localhost";
	$username = "root";
	$pass = "";
	$db = "spotify";	

	try {
		return mysqli_connect($host, $username, $pass, $db);
	} catch (Exception $e) {
		echo $e->getMessage();
		echo "----";
		echo mysql_error();
	}

}