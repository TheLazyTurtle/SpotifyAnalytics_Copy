<?php
// Require hearders
header("Access-Control-Allow-Origin: *");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Heades: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require '../vendor/autoload.php';
require '../api/config/database.php';
require '../api/objects/user.php';
require '../api/objects/album.php';
require '../api/objects/songs.php';
require '../api/objects/artists.php';
require '../api/objects/played.php';
require '../api/objects/logger.php';

try {
    $session = new SpotifyWebAPI\Session(
        "***REMOVED***",
        "***REMOVED***",
        "https://spa.jcg-ict.nl/callback.php"
    );
} catch (Exception $e) {
    print_r($e);
}

$database = new Database();
$db = $database->getConnection();
$userObject = new User($db);
$albumObject = new Album($db);
$songObject = new Song($db);
$artistObject = new Artist($db);
$playedObject = new Played($db);
$loggerObject = new Logger($db);
$users = $userObject->getAllActiveUsersIncludingTokens();

$startTotalTime = time();
foreach ($users as $user) {
    $userID = $user["userID"];
    $startTime = time();

    $tokens = refreshAccessToken($session, $user["refreshToken"], $user["userID"]);

    $api = new SpotifyWebAPI\SpotifyWebAPI();
    $api->setAccessToken($tokens["accessToken"]);

    $options = ["limit" => "50"];
    $tracks = $api->getMyRecentTracks($options);

    $tracks = json_decode(json_encode($tracks), true);
    parseTracks($tracks["items"], $userID);

    $resultTime = time() - $startTime;
    $loggerObject->create(3, "Fetched for user $userID. Fetching duration: $resultTime sec", "Fetching");
}
$totalTime = time() - $startTotalTime;
$loggerObject->create(3, "Total fetch time: $totalTime sec", "Fetching");

function refreshAccessToken($session, $refreshToken, $userID) {
    global $userObject, $loggerObject;
    try {
        $session->refreshAccessToken($refreshToken);

        $tokens = array();
        $tokens["accessToken"] = $session->getAccessToken();
        $tokens["refreshToken"] = $session->getRefreshToken();

        $userObject->updateAuthTokens($userID, $tokens["accessToken"], $tokens["refreshToken"]);
        $loggerObject->create(3, "Updated spotify tokens for user $userID", "Refreshing tokens");

        return $tokens;
    } catch (Exception $e) {
        $loggerObject->create(1, json_encode($e), "Failed to updated accessToken $userID");
    }
}

function parseTracks($tracks, $userID) {
    foreach ($tracks as $track) {
        insertSong($track, $userID);
        insertAlbum($track["track"]["album"]);
        insertArtists($track["track"]["artists"], $track["track"]["id"]);
    }
}

function insertSong($song, $userID) {
    global $songObject, $playedObject, $loggerObject;
    $playedAt = $song["played_at"];
    $playedAt = str_replace("T", " ", $playedAt);
    $playedAt = str_replace("Z", "", $playedAt);
    $playedAt = substr($playedAt, 0, -4);

    $songInfo = $song["track"];
    $songID = $songInfo["id"];
    $url = $songInfo["external_urls"]["spotify"];
    $name = $songInfo["name"];
    $img = $songInfo["album"]["images"][0]["url"];
    $length = $songInfo["duration_ms"];
    $trackNumber = $songInfo["track_number"];

    if ($songInfo["explicit"] == "1") {
        $explicit = "true";
    } else {
        $explicit = "false";
    }

    if (isset($songInfo["preview_url"])) {
        $preview = $songInfo["preview_url"];
    } else {
        $preview = "NULL";
    }
    $albumID = $song["track"]["album"]["id"];

    $songRes = $songObject->createSpecial($songID, $name, $length, $url, $img, $albumID, $trackNumber, $explicit, $preview);
    $playedRes = $playedObject->createSpecial($songID, $playedAt, $userID, $name);

    if ($songRes != "1" && $songRes != "23000") {
        $output = json_encode($songRes);
        $loggerObject->create(2, $output, "Failed adding song");
    }

    if ($playedRes != "1" && !isset($playedRes)) {
        $output = json_encode($playedRes);
        $loggerObject->create(2, $output, "Failed adding played");
    }
}

function insertAlbum($album) {
    global $albumObject, $loggerObject;

    $albumID = $album["id"];
    $name = $album["name"];
    $releaseDate = $album["release_date"];
    $url = $album["external_urls"]["spotify"];
    $img = $album["images"][0]["url"];
    $albumType = $album['album_type'];

    # Primary artist is only needed when albumType = album,
    # because when it's a single all artist are primary
    if ($albumType == 'album'){
        $primaryArtist = $album["artists"][0]["id"];
    } else {
        $primaryArtist = "NULL";
    }

    $res = $albumObject->createSpecial($albumID, $name, $url, $releaseDate, $primaryArtist, $img, $albumType);
    if ($res == "1") {
        getAlbumSongs($albumID, $img);
    } else if ($res != "1" && !isset($res)) {
        $output = json_encode($res);
        $loggerObject->create(2, $output, "Failed adding album");
    }
}

function insertArtists($artists, $songID) {
    global $artistObject, $songObject, $loggerObject, $api;

    foreach ($artists as $artist) {
        $artistID = $artist["id"];
        $name = $artist["name"];
        $url = $artist["external_urls"]["spotify"];

        $artistRes = $artistObject->createSpecial($artistID, $name, $url, "");
        $linkRes = $songObject->linkArtistToSong($songID, $artistID);

        if ($artistRes != "1" && !isset($artistRes)) {
            $output = json_encode($artistRes);
            $loggerObject->create(2, $output, "Failed adding artist");
        } elseif ($artistRes == "1") {
            $result = $api->search($name, "artist");
            $result = json_decode(json_encode($result), true);
            $loggerObject->create(2, "Getting artist image", "Fetching artist img");
            if (array_key_exists("images", $result)) {
                $img = $result["artists"]["items"][0]["images"][0]["url"];
            } else {
                $img = "http://www.techspot.com/images2/downloads/topdownload/2016/12/spotify-icon-18.png";
            }
            $artistObject->addImage($artistID, $img);
        }

        if ($linkRes != "1" && !isset($linkRes)) {
            $output = json_encode($linkRes);
            $loggerObject->create(2, $output, "Failed linking artist to song");
        }
    }
}

function getAlbumSongs($albumID, $albumImg) {
    global $api, $songObject, $loggerObject;
    $loggerObject->create(2, "Getting album songs for: $albumID", "Fetching album songs");

    $results = $api->getAlbumTracks($albumID);
    $results = json_decode(json_encode($results), true);

    foreach ($results["items"] as $song) {
        $songID = $song["id"];
        $name = $song["name"];
        $length = $song["duration_ms"];
        $url = $song["external_urls"]["spotify"];
        $preview = $song["preview_url"];
        $trackNumber = $song["track_number"];

        if ($song["explicit"]) {
            $explicit = "true";
        } else {
            $explicit = "false";
        }

        insertArtists($song["artists"], $songID);
        $songRes = $songObject->createSpecial($songID, $name, $length, $url, $albumImg, $albumID, $trackNumber, $explicit, $preview);

        if ($songRes != "1" && !isset($songRes)) {
            $output = json_encode($songRes);
            $loggerObject->create(2, $output, "Failed adding album song");
        }
    }
}
?>
