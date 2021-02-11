<?php

function getConnection() {
    //$host = "localhost";
    $host = "192.168.2.7";
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

