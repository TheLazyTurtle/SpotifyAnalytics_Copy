<?php
require 'vendor/autoload.php';

$session = new SpotifyWebAPI\Session(
    '***REMOVED***',
    '***REMOVED***',
    'http://localhost/Spotify/callback.php'
);

$options = [
    'scope' => [
        'user-read-recently-played'
    ],
];

header('Location: ' . $session->getAuthorizeUrl($options));
die();
