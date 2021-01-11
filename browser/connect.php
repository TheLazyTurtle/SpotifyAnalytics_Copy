<?php

function getConnection() {
	$server = "localhost";
	$user = "remote";
	$pass = "***REMOVED***";
	$db = "spotify";

	try {
		return mysqli_connect($server, $user, $pass, $db);
	} catch (mysqli_sql_error $e) {
		print("Couldn't connect ".$e);
	} 
}
?>

