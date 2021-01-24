<?php

function getConnection() {
	$host = "localhost";
	$username = "remote";
	$pass = "***REMOVED***";
	$db = "spotify";	

	try {
		return mysqli_connect($host, $username, $pass, $db);
	} catch (Exception $e) {
		echo $e->getMessage();
		echo "----";
		echo mysql_error();
	}

}
