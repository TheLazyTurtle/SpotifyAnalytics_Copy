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
require '../objects/songs.php';

// Get posted data
$songs = file_get_contents("php://input");
$songs = json_decode($songs, true);
$result = array();

if (!($userID = validateToken($songs["jwt"])) || $userID != "system") {
	die(json_encode(array("message" => "Not a valid token")));
}

unset($songs["jwt"]);

// Make db connection and make new song object
$database = new Database();
$db = $database->getConnection();
$song = new Song($db);

// Check if data is not empty
foreach ($songs as $data) {
    if (
        !empty($data["songID"]) &&
        !empty($data["name"]) &&
        !empty($data["length"]) &&
        !empty($data["url"]) &&
        !empty($data["img"]) &&
        !empty($data["preview"]) &&
        !empty($data["albumID"]) &&
        !empty($data["trackNumber"]) &&
        isset($data["explicit"])
    ) {
        $song->id = $data["songID"];
        $song->name = $data["name"];
        $song->length = $data["length"];
        $song->url = $data["url"];
        $song->img = $data["img"];
        $song->preview = $data["preview"];
        $song->albumID = $data["albumID"];
        $song->trackNumber = $data["trackNumber"];
        $song->explicit = $data["explicit"];

        if ($stmt = $song->createOne()) {
            array_push($result, array("Succesfully added song", $data["name"]));
        } else {
            array_push($result, array("Failed to add song", $data["name"]));
        }
    } else {
        array_push($result, array("Data incomplete for song", $data["name"]));
    }
}

http_response_code(200);
echo json_encode($result);
