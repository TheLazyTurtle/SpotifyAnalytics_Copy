<?php
require 'src/header.php';
require 'vendor/autoload.php';

$session = new SpotifyWebAPI\Session(
	"***REMOVED***",
	"***REMOVED***",
	"http://localhost/callback.php"
);

$state = $session->getAccessToken();
$options = [
	'scope' => [
		'user-read-recently-played',
		'user-read-private',
	],
	'state' => $state,
];

header('Location: ' . $session->getAuthorizeUrl($options));
die();
