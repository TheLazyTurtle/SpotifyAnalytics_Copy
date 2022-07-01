<?php
$session = new SpotifyWebAPI\Session(
    env('SPOTIFY_CLIENT_ID'),
    env('SPOTIFY_CLIENT_SECRET'),
    env('SPOTIFY_CALLBACK_URL')
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
