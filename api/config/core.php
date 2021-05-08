<?php
// Show error reporing
error_reporting(E_ALL);

// Set timezone (dont know if this is correct)
date_default_timezone_set("Europe/Amsterdam");

// Set variables for jwt
// This key must be secret??
$key = "superFancyKey";
$issued_at = time();
$expiration_time = $issued_at + (60*60); // valid for one hour
$issuer = "http://localhost/";
?>
