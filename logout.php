<?php
require "src/header.php";

session_unset();
session_destroy();

// This removes all the coockies
if (isset($_SERVER['HTTP_COOKIE'])) {
	$cookies = explode(';', $_SERVER["HTTP_COOKIE"]);

	foreach ($cookies as $cookie) {
		$parts = explode('=', $cookie);
		$name = trim($parts[0]);
		setcookie($name, '', time() - 1000);
		setcookie($name, '', time() - 1000, '/');
	}
}
