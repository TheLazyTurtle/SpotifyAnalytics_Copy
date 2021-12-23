<?php
// Show error reporing
error_reporting(E_ALL);

// Set timezone (dont know if this is correct)
date_default_timezone_set("Europe/Amsterdam");

// Set variables for jwt
// This key must be secret??
$key = "superFancyKey";
$issued_at = time();
$expiration_time = $issued_at + (24 * 60 * 60 * 1000); // valid for one hour
$issuer = "http://192.168.2.198/";
$minDate_def = "2020-01-01";
$maxDate_def = "2099-01-01";
$maxDate_def = new DateTime();
$maxDate_def = $maxDate_def->format("Y-m-d");
$minPlayed_def = 0;
$maxPlayed_def = 9999;
// Make sure that these are identical in the creds.py file otherwise the back end can't insert songs and artists
$backEndUser = "7d2d44dcb4060983031497187073e0bfdf110143";
$backEndPass = "811df84a16461e2c01e6210187c1cdb049b8ee0f";
