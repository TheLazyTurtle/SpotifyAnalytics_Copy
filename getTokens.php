<?php
require 'header.php';
require 'vendor/autoload.php';

$session = new SpotifyWebAPI\Session(
	"***REMOVED***",
	"***REMOVED***",
	"http://localhost/callback.php"
);

$state = $session->generateState();
$options = [
	'scope' => [
		'user-read-recently-played',
		'user-read-private',
	],
	'state' => $state,
];

header('Location: ' . $session->getAuthorizeUrl($options));
die();
