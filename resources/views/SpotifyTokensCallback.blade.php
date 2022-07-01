<?php
$session = new SpotifyWebAPI\Session(
    env('SPOTIFY_CLIENT_ID'),
    env('SPOTIFY_CLIENT_SECRET'),
    env('SPOTIFY_CALLBACK_URL')
);

$state = $_GET['state'];

$session->requestAccessToken($_GET['code']);
$_SESSION['accessToken'] = $session->getAccessToken();
$_SESSION['refreshToken'] = $session->getRefreshToken();
$_SESSION['expireTime'] = $session->getTokenExpiration();
