<?php
require 'vendor/autoload.php';

$session = new SpotifyWebAPI\Session(
    '***REMOVED***',
    '***REMOVED***',
    'http://192.168.2.7/Spotify/callback.php'
);

$options = [
    'scope' => [
        'user-read-recently-played'
    ],
];

header('Location: ' . $session->getAuthorizeUrl($options));
die();
