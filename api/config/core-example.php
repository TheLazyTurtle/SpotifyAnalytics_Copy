<?php
// Show error reporing
error_reporting(E_ALL);

// Set timezone (dont know if this is correct)
date_default_timezone_set("Europe/Amsterdam");

// Set variables for jwt
// This key must be secret??
$key = "SECRECT KEY JWT";
$issued_at = time();
$expiration_time = $issued_at + (24*60*60*1000); // valid for one hour
$issuer = "HOST URL";
$minDate_def = "2020-01-01";
$maxDate_def = "2099-01-01";
$maxDate_def = new DateTime();
$maxDate_def = $maxDate_def->format("Y-m-d");
$minPlayed_def = 0;
$maxPlayed_def = 9999;

?>
