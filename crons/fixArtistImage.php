<?php
require '../vendor/autoload.php';
require '../api/config/database.php';
require '../api/objects/artists.php';
require '../api/objects/logger.php';

$session = new SpotifyWebAPI\Session(
    "***REMOVED***",
    "***REMOVED***",
    "https://spa.jcg-ict.nl/callback.php"
);

$database = new Database();
$db = $database->getConnection();
$artistObject = new Artist($db);
$loggerObject = new Logger($db);
$tokens = refreshAccessToken($session, "AQAVKEyT-PTVLDZ42IqQD6DaT9DstWmqKuBRQJKWDE5xfZj-8wPcmNz-28VY2RO4fCUEANuTRXAzkHEvuxMhLxXnzPb42CiVWba5qI-Wa422KdDGM0UidEx3YD2b0qUiPD8");

$api = new SpotifyWebAPI\SpotifyWebAPI();
$api->setAccessToken($tokens);
if (isset($_GET["artistID"])) {
    $sadArtists = $artistObject->getArtistWithoutImage($_GET["artistID"]);
} else {
    $sadArtists = $artistObject->getArtistWithoutImage();
}

foreach ($sadArtists as $sadArtist) {
    $artistID = $sadArtist["artistID"];
    $result = $api->getArtist($artistID);
    $result = json_decode(json_encode($result), true);

    if (count($result["images"]) > 0) {
        $img = $result["images"][0]["url"];
        $loggerObject->create(2, "Updating artist image for: $artistID", "Updating artist image");
        $res = $artistObject->addImage($artistID, $img);
    } else {
        $img = "http://www.techspot.com/images2/downloads/topdownload/2016/12/spotify-icon-18.png";
    }
}

function refreshAccessToken($session, $refreshToken) {
    $session->refreshAccessToken($refreshToken);

    $tokens = array();
    $tokens["accessToken"] = $session->getAccessToken();

    return $tokens["accessToken"];
}
?>
