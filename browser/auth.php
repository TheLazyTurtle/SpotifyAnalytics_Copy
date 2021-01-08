<?php
require 'vendor/autoload.php';

$session = new SpotifyWebAPI\Session(
    '***REMOVED***',
    '***REMOVED***',
    'http://localhost/Spotify/callback.php'
);

$options = [
    'scope' => [
        'playlist-read-private',
        'user-read-private',
        'user-read-currently-playing',
        'user-read-playback-state'
    ],
];

header('Location: ' . $session->getAuthorizeUrl($options));
die();