<?php
// Require hearders
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset-UTF-8");
header("Access-Control-Allow_Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Heades: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Get db connection and get song object
require '../system/validate_token.php';
require '../config/database.php';
require '../objects/album.php';
require '../objects/artists.php';
require '../objects/played.php';
require '../objects/songs.php';

// Get posted data
$data = file_get_contents("php://input");
$data = json_decode($data, true);
$result = array();

if (!($userID = validateToken($data["jwt"])) || $userID != "system") {
    die(json_encode(array("message" => "Not a valid token")));
}

$playedBy = $data["playedBy"];
unset($data["jwt"]);
unset($data["playedBy"]);

// Make db connection and make new objects
$database = new Database();
$db = $database->getConnection();
$album = new Album($db);
$artist = new Artist($db);
$played = new Played($db);
$song = new Song($db);

// Check if data is not empty
foreach ($data as $i) {
    $a = $i["album"];
    $songRes = $song->createSpecial($i["songID"], $i["name"], $i["length"], $i["url"], $i["img"], $i["album"]["albumID"], $i["trackNumber"], $i["explicit"], $i["preview"]);
    push_result($songRes, "song", $i["name"]);

    if (isset($a["name"])) {
        $albumRes = $album->createSpecial($a["albumID"], $a["name"], $a["url"], $a["releaseDate"], $a["primaryArtist"], $a["img"], $a["albumType"]);
        push_result($albumRes, "album", $a["name"], $a["albumID"], $i["img"]);
    }

    if (isset($i["playedAt"])) {
        $playedRes = $played->createSpecial($i["songID"], $i["playedAt"], $playedBy, $i["name"]);
        push_result($playedRes, "played", $i["name"]);
    }

    foreach ($i["artists"] as $ar) {
        $artistRes = $artist->createSpecial($ar["artistID"], $ar["name"], $ar["url"], $ar["img"]);
        $linkRes = $song->linkArtistToSong($i["songID"], $ar["artistID"]);
        push_result($artistRes, "artist", $ar["name"]);
        push_result($linkRes, "linked", $ar["name"]);
    }
}

http_response_code(200);
echo json_encode($result);

function push_result($returnValue, $type, $value, $albumID = null, $img = null) {
    global $result;

    if ($returnValue == false) {
        array_push($result, array("Failed to add $type:", $value));
    } else {
        if ($albumID == null) {
            array_push($result, array("Successfully added $type:", $value));
        } else {
            array_push($result, array("Successfully added $type:", $value, $albumID, $img));
        }
    }
}

